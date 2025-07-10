/**
 * @Oracode AlgoKit Express Microservice - Container Native Version
 * 🎯 Purpose: REST API microservice for Algorand blockchain operations via AlgoKit
 * 🧱 Core Logic: NFT minting, treasury management, asset transfers - Container optimized
 * 🛡️ Security: Input validation, error handling, secure key management, Docker-ready
 *
 * @package AlgoKit-Microservice
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (Laravel Sail + AlgoKit Integration)
 * @date 2025-07-08
 * @purpose Container-native Express microservice bridge between Laravel and Algorand
 */

const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const { config } = require('dotenv');
const winston = require('winston');

// AlgoKit e Algorand SDK imports
const algosdk = require('algosdk');

// Load environment variables
config();

const app = express();
const PORT = process.env.PORT || 3000;

// ========================================
// WINSTON LOGGER SETUP (Container-friendly)
// ========================================

const logger = winston.createLogger({
    level: process.env.LOG_LEVEL || 'info',
    format: winston.format.combine(
        winston.format.timestamp(),
        winston.format.errors({ stack: true }),
        winston.format.json()
    ),
    transports: [
        // Console output per Docker logs
        new winston.transports.Console({
            format: winston.format.combine(
                winston.format.colorize(),
                winston.format.simple()
            )
        }),
        // File output se directory esiste
        ...(process.env.NODE_ENV === 'production' ? [
            new winston.transports.File({ filename: '/app/logs/error.log', level: 'error' }),
            new winston.transports.File({ filename: '/app/logs/combined.log' })
        ] : [])
    ]
});

// ========================================
// MIDDLEWARE SETUP
// ========================================

app.use(helmet({
    contentSecurityPolicy: false // Permetti CORS per Laravel
}));

app.use(cors({
    origin: process.env.CORS_ORIGINS?.split(',') || ['http://localhost:80'],
    credentials: true,
    methods: ['GET', 'POST', 'PUT', 'DELETE'],
    allowedHeaders: ['Content-Type', 'Authorization']
}));

app.use(morgan('combined', {
    stream: { write: message => logger.info(message.trim()) }
}));

app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true }));

// Request logging middleware
app.use((req, res, next) => {
    logger.info(`📨 ${req.method} ${req.url}`, {
        ip: req.ip,
        userAgent: req.get('User-Agent')
    });
    next();
});

// ========================================
// ALGORAND CLIENT SETUP
// ========================================

let algodClient;
let indexerClient;
let treasuryAccount;

async function initializeAlgorand() {
    try {
        logger.info('🔧 Initializing Algorand client...');

        // Setup Algod client
        const algodServer = process.env.ALGOD_SERVER || 'https://testnet-api.algonode.cloud';
        const algodPort = process.env.ALGOD_PORT || 443;
        const algodToken = process.env.ALGOD_TOKEN || '';

        algodClient = new algosdk.Algodv2(algodToken, algodServer, algodPort);

        // Setup Indexer client
        const indexerServer = process.env.INDEXER_SERVER || 'https://testnet-idx.algonode.cloud';
        const indexerPort = process.env.INDEXER_PORT || 443;
        const indexerToken = process.env.INDEXER_TOKEN || '';

        indexerClient = new algosdk.Indexer(indexerToken, indexerServer, indexerPort);

        // Load treasury account
        const treasuryMnemonic = process.env.TREASURY_MNEMONIC;
        if (!treasuryMnemonic) {
            throw new Error('TREASURY_MNEMONIC environment variable is required');
        }

        // Validate mnemonic (should be 25 words)
        const mnemonicWords = treasuryMnemonic.trim().split(/\s+/);
        if (mnemonicWords.length !== 25) {
            throw new Error(`Invalid mnemonic: expected 25 words, got ${mnemonicWords.length}`);
        }

        treasuryAccount = algosdk.mnemonicToSecretKey(treasuryMnemonic);

        // ✅ VALIDATE: Address should be available
        if (!treasuryAccount || !treasuryAccount.addr) {
            throw new Error('Treasury account creation failed - no address generated');
        }

        // ✅ DEBUG: Log what we actually get
        logger.info('🔍 Treasury account debug', {
            accountKeys: Object.keys(treasuryAccount || {}),
            addrType: typeof treasuryAccount?.addr,
            addrValue: treasuryAccount?.addr,
            hasSecretKey: !!treasuryAccount?.sk
        });

        // ✅ VALIDATE: Address should be a string
        if (!treasuryAccount || !treasuryAccount.addr || typeof treasuryAccount.addr !== 'string') {
            throw new Error(`Treasury account creation failed. Got: ${JSON.stringify(treasuryAccount)}`);
        }

        // Test connection
        const status = await algodClient.status().do();

        logger.info('✅ Algorand client initialized successfully', {
            network: process.env.ALGORAND_NETWORK || 'testnet',
            nodeRound: status['last-round'],
            treasuryAddress: treasuryAccount.addr,
            algodServer,
            indexerServer
        });

        return true;
    } catch (error) {
        logger.error('❌ Failed to initialize Algorand client', {
            error: error.message,
            stack: error.stack
        });
        return false;
    }
}

// ========================================
// HEALTH CHECK ENDPOINT
// ========================================

app.get('/health', async (req, res) => {
    try {
        const healthData = {
            success: true,
            service: 'FlorenceEGI AlgoKit Microservice',
            version: '1.0.0',
            environment: process.env.NODE_ENV || 'development',
            timestamp: new Date().toISOString(),
            uptime: process.uptime()
        };

        // Test Algorand connection se disponibile
        if (algodClient && treasuryAccount) {
            try {
                const status = await algodClient.status().do();
                healthData.algorand = {
                    network: process.env.ALGORAND_NETWORK || 'testnet',
                    node_round: status['last-round'],
                    treasury_address: treasuryAccount.addr,
                    connection: 'healthy'
                };
            } catch (algodError) {
                healthData.algorand = {
                    connection: 'error',
                    error: algodError.message
                };
            }
        } else {
            healthData.algorand = {
                connection: 'not_initialized'
            };
        }

        const statusCode = healthData.algorand?.connection === 'healthy' ? 200 : 503;
        res.status(statusCode).json(healthData);

    } catch (error) {
        logger.error('❌ Health check failed', { error: error.message });
        res.status(500).json({
            success: false,
            error: 'Health check failed',
            details: error.message,
            timestamp: new Date().toISOString()
        });
    }
});

// ========================================
// MINT FOUNDER CERTIFICATE NFT
// ========================================

app.post('/mint-founder-token', async (req, res) => {
    const requestId = Date.now().toString();
    logger.info('🎯 ALGORAND_MINT_START', { requestId, body: req.body });

    try {
        const { index, metadata } = req.body;

        // Input validation
        if (!index || !Number.isInteger(index) || index < 1 || index > 40) {
            return res.status(400).json({
                success: false,
                error: 'Invalid index: must be integer between 1-40',
                code: 'INVALID_INDEX',
                requestId
            });
        }

        if (!metadata || typeof metadata !== 'object') {
            return res.status(400).json({
                success: false,
                error: 'Invalid metadata: object required',
                code: 'INVALID_METADATA',
                requestId
            });
        }

        // Check Algorand client
        if (!algodClient || !treasuryAccount) {
            throw new Error('Algorand client not initialized');
        }

        // ✅ VALIDATE: Treasury address should be available
        if (!treasuryAccount.addr) {
            throw new Error(`Treasury address missing: ${treasuryAccount.addr}`);
        }

        // Get suggested params
        const suggestedParams = await algodClient.getTransactionParams().do();

        // Build ASA creation parameters
        const asaParams = {
            from: treasuryAccount.addr,
            total: 1, // Single NFT
            decimals: 0, // NFT standard
            defaultFrozen: false,
            unitName: `FEG${String(index).padStart(2, '0')}`,
            assetName: metadata.name || `FlorenceEGI Padre Fondatore #${String(index).padStart(2, '0')}`,
            assetURL: metadata.url || `https://florenceegi.it/certificates/${index}`,
            assetMetadataHash: metadata.hash ? new Uint8Array(Buffer.from(metadata.hash, 'hex')) : undefined,
            manager: treasuryAccount.addr,
            reserve: treasuryAccount.addr,
            freeze: treasuryAccount.addr,
            clawback: treasuryAccount.addr,
            suggestedParams
        };

        // Create asset transaction
        const assetCreateTxn = algosdk.makeAssetCreateTxnWithSuggestedParamsFromObject(asaParams);

        // Sign transaction
        const signedTxn = assetCreateTxn.signTxn(treasuryAccount.sk);

        // Submit transaction
        const txn = await algodClient.sendRawTransaction(signedTxn).do();

        logger.info('📤 Transaction submitted', { requestId, txId: txn.txId });

        // Wait for confirmation
        const confirmedTxn = await algosdk.waitForConfirmation(algodClient, txn.txId, 4);

        const assetId = confirmedTxn['asset-index'];

        logger.info('✅ ALGORAND_MINT_SUCCESS', {
            requestId,
            index,
            assetId,
            txId: txn.txId,
            confirmedRound: confirmedTxn['confirmed-round']
        });

        res.json({
            success: true,
            requestId,
            data: {
                asaId: assetId,
                txId: txn.txId,
                certificate_number: String(index).padStart(2, '0'),
                transaction_id: txn.txId,
                asset_url: asaParams.assetURL,
                treasury_address: treasuryAccount.addr,
                confirmed_round: confirmedTxn['confirmed-round'],
                network: process.env.ALGORAND_NETWORK || 'testnet'
            }
        });

    } catch (error) {
        logger.error('❌ ALGORAND_MINT_FAILED', {
            requestId,
            error: error.message,
            stack: error.stack
        });

        res.status(500).json({
            success: false,
            requestId,
            error: 'Failed to mint founder token',
            details: error.message,
            code: 'MINT_FAILED'
        });
    }
});

// ========================================
// TRANSFER ASSET TO WALLET
// ========================================

app.post('/transfer-asset', async (req, res) => {
    const requestId = Date.now().toString();
    logger.info('🔄 ALGORAND_TRANSFER_START', { requestId, body: req.body });

    try {
        const { to, asaId, amount = 1 } = req.body;

        // Input validation
        if (!to || !algosdk.isValidAddress(to)) {
            return res.status(400).json({
                success: false,
                error: 'Invalid recipient address',
                code: 'INVALID_ADDRESS',
                requestId
            });
        }

        if (!asaId || !Number.isInteger(Number(asaId))) {
            return res.status(400).json({
                success: false,
                error: 'Invalid asset ID',
                code: 'INVALID_ASSET_ID',
                requestId
            });
        }

        // Check Algorand client
        if (!algodClient || !treasuryAccount) {
            throw new Error('Algorand client not initialized');
        }

        // Get suggested params
        const suggestedParams = await algodClient.getTransactionParams().do();

        // Create asset transfer transaction
        const assetTransferTxn = algosdk.makeAssetTransferTxnWithSuggestedParamsFromObject({
            from: treasuryAccount.addr,
            to: to,
            assetIndex: parseInt(asaId),
            amount: parseInt(amount),
            suggestedParams
        });

        // Sign transaction
        const signedTxn = assetTransferTxn.signTxn(treasuryAccount.sk);

        // Submit transaction
        const txn = await algodClient.sendRawTransaction(signedTxn).do();

        // Wait for confirmation
        const confirmedTxn = await algosdk.waitForConfirmation(algodClient, txn.txId, 4);

        logger.info('✅ ALGORAND_TRANSFER_SUCCESS', {
            requestId,
            txId: txn.txId,
            to,
            asaId,
            confirmedRound: confirmedTxn['confirmed-round']
        });

        res.json({
            success: true,
            requestId,
            data: {
                txId: txn.txId,
                to: to,
                asaId: asaId,
                amount: amount,
                confirmed_round: confirmedTxn['confirmed-round']
            }
        });

    } catch (error) {
        logger.error('❌ ALGORAND_TRANSFER_FAILED', {
            requestId,
            error: error.message,
            stack: error.stack
        });

        res.status(500).json({
            success: false,
            requestId,
            error: 'Failed to transfer asset',
            details: error.message,
            code: 'TRANSFER_FAILED'
        });
    }
});

// ========================================
// GET ACCOUNT INFO
// ========================================

app.get('/account/:address', async (req, res) => {
    const requestId = Date.now().toString();

    try {
        const { address } = req.params;

        if (!algosdk.isValidAddress(address)) {
            return res.status(400).json({
                success: false,
                error: 'Invalid Algorand address',
                code: 'INVALID_ADDRESS',
                requestId
            });
        }

        if (!algodClient) {
            throw new Error('Algorand client not initialized');
        }

        const accountInfo = await algodClient.accountInformation(address).do();

        logger.info('✅ Account info retrieved', { requestId, address });

        res.json({
            success: true,
            requestId,
            data: accountInfo
        });

    } catch (error) {
        logger.error('❌ ACCOUNT_INFO_FAILED', {
            requestId,
            error: error.message
        });

        res.status(500).json({
            success: false,
            requestId,
            error: 'Failed to get account information',
            details: error.message,
            code: 'ACCOUNT_INFO_FAILED'
        });
    }
});

// ========================================
// ERROR HANDLING MIDDLEWARE
// ========================================

app.use((error, req, res, next) => {
    const requestId = Date.now().toString();
    logger.error('🚨 Unhandled error', {
        requestId,
        error: error.message,
        stack: error.stack,
        url: req.url,
        method: req.method
    });

    res.status(500).json({
        success: false,
        requestId,
        error: 'Internal server error',
        details: process.env.NODE_ENV === 'development' ? error.message : 'Contact support',
        code: 'INTERNAL_ERROR'
    });
});

// 404 handler
app.use('*', (req, res) => {
    res.status(404).json({
        success: false,
        error: 'Endpoint not found',
        code: 'NOT_FOUND',
        available_endpoints: [
            'GET /health',
            'POST /mint-founder-token',
            'POST /transfer-asset',
            'GET /account/:address'
        ]
    });
});

// ========================================
// GRACEFUL SHUTDOWN HANDLERS
// ========================================

function gracefulShutdown(signal) {
    logger.info(`🛑 ${signal} received. Shutting down gracefully...`);

    server.close(() => {
        logger.info('✅ Server closed. Exiting process.');
        process.exit(0);
    });

    // Force exit after 10 seconds
    setTimeout(() => {
        logger.error('❌ Forced exit after 10 seconds');
        process.exit(1);
    }, 10000);
}

process.on('SIGTERM', () => gracefulShutdown('SIGTERM'));
process.on('SIGINT', () => gracefulShutdown('SIGINT'));

// ========================================
// SERVER STARTUP
// ========================================

async function startServer() {
    try {
        logger.info('🚀 Starting FlorenceEGI AlgoKit Microservice...', {
            version: '1.0.0',
            nodeVersion: process.version,
            environment: process.env.NODE_ENV || 'development'
        });

        // Initialize Algorand connection
        const algorandReady = await initializeAlgorand();

        if (!algorandReady) {
            logger.error('❌ Failed to initialize Algorand client. Service will start but blockchain operations will fail.');
            // In container environment, we continue anyway for health checks
        }

        // Start Express server
        const server = app.listen(PORT, '0.0.0.0', () => {
            logger.info('✅ FlorenceEGI AlgoKit Microservice running', {
                port: PORT,
                network: process.env.ALGORAND_NETWORK || 'testnet',
                treasuryAddress: treasuryAccount?.addr || 'Not loaded',
                healthCheck: `http://localhost:${PORT}/health`
            });
        });

        // Store server reference for graceful shutdown
        global.server = server;

        return server;

    } catch (error) {
        logger.error('❌ Failed to start server', {
            error: error.message,
            stack: error.stack
        });
        process.exit(1);
    }
}

// Start the server
startServer();
