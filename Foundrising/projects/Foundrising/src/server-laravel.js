/**
 * Express HTTP Server - AlgoKit Wrapper per FlorenceEGI Laravel Integration
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Laravel Compatible Server)
 * @date 2025-07-08
 * @purpose HTTP server compatibile con Laravel AlgorandService esistente
 */

const express = require('express');
const cors = require('cors');
const algokit = require('@algorandfoundation/algokit-utils');

class FlorenceEGIServer {
  constructor(port = 4000) {
    this.app = express();
    this.port = port;
    this.algoClient = null;

    this.setupMiddleware();
    this.setupRoutes();
  }

  setupMiddleware() {
    // CORS per Laravel integration
    this.app.use(cors({
      origin: [
        'http://localhost:8090',
        'http://127.0.0.1:8090',
        'http://localhost:80',
        'https://localhost:8443',
        'https://host.docker.internal:8443'
      ],
      credentials: true,
      methods: ['GET', 'POST', 'PUT', 'DELETE'],
      allowedHeaders: ['Content-Type', 'Authorization', 'User-Agent']
    }));

    this.app.use(express.json({ limit: '10mb' }));
    this.app.use(express.urlencoded({ extended: true }));

    // Request logging
    this.app.use((req, res, next) => {
      console.log(`📡 ${req.method} ${req.path} - ${new Date().toISOString()}`);
      next();
    });
  }

  async initializeAlgoClient() {
    try {
      // Configurazione per TestNet
      this.algoClient = require('@algorandfoundation/algokit-utils').getAlgoClient({
        server: 'https://testnet-api.algonode.cloud',
        token: '',
        port: ''
      });
      const status = await this.algoClient.status().do();
      console.log('✅ Connected to Algorand TestNet, round:', status['last-round']);
      return true;
    } catch (error) {
      console.error('❌ Failed to connect to TestNet:', error);
      return false;
    }
  }

  setupRoutes() {
    // ===========================================
    // LARAVEL COMPATIBLE ENDPOINTS
    // ===========================================

    // Health check (compatibile con Laravel)
    this.app.get('/health', (req, res) => {
      res.json({
        success: true,
        service: 'FlorenceEGI Foundrising AlgoKit Server',
        status: 'running',
        algorand: {
          connected: this.algoClient ? true : false,
          treasury_address: 'TREASURY_PLACEHOLDER_ADDRESS'
        },
        timestamp: new Date().toISOString(),
        version: '1.0.0'
      });
    });

    // Mint founder token (endpoint che Laravel si aspetta)
    this.app.post('/mint-founder-token', async (req, res) => {
      try {
        const { index, metadata } = req.body;

        console.log(`🔥 Minting founder token #${index}...`);

        if (!index || index < 1 || index > 40) {
          return res.status(400).json({
            success: false,
            error: 'Invalid certificate index (1-40)'
          });
        }

        // Mock ASA ID e Transaction ID per ora
        const mockAsaId = `${100000 + index}`;
        const mockTxId = `FOUNDRISING_TX_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

        // Simula tempo di processing blockchain
        await new Promise(resolve => setTimeout(resolve, 1000));

        const response = {
          success: true,
          data: {
            asaId: mockAsaId,
            txId: mockTxId,
            certificate_number: String(index).padStart(2, '0'),
            asset_url: `https://florenceegi.it/certificates/${index}/metadata.json`,
            treasury_address: 'TREASURY_ALGORAND_ADDRESS_PLACEHOLDER'
          }
        };

        console.log('✅ Token minted successfully:', mockAsaId);
        res.json(response);

      } catch (error) {
        console.error('❌ Mint failed:', error);
        res.status(500).json({
          success: false,
          error: 'Token minting failed',
          details: error.message
        });
      }
    });

    // Transfer asset (endpoint che Laravel si aspetta)
    this.app.post('/transfer-asset', async (req, res) => {
      try {
        const { to, asaId, amount = 1 } = req.body;

        console.log(`🔥 Transferring ASA ${asaId} to ${to}...`);

        if (!to || !asaId) {
          return res.status(400).json({
            success: false,
            error: 'Recipient address and ASA ID required'
          });
        }

        // Validazione basic address Algorand (58 caratteri)
        if (to.length !== 58) {
          return res.status(400).json({
            success: false,
            error: 'Invalid Algorand address format'
          });
        }

        // Mock transfer transaction
        const mockTransferTxId = `TRANSFER_TX_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

        // Simula tempo di processing
        await new Promise(resolve => setTimeout(resolve, 800));

        const response = {
          success: true,
          data: {
            txId: mockTransferTxId
          }
        };

        console.log('✅ Asset transferred successfully:', mockTransferTxId);
        res.json(response);

      } catch (error) {
        console.error('❌ Transfer failed:', error);
        res.status(500).json({
          success: false,
          error: 'Asset transfer failed',
          details: error.message
        });
      }
    });

    // Get account info (endpoint che Laravel si aspetta)
    this.app.get('/account/:address', async (req, res) => {
      try {
        const { address } = req.params;

        if (!address) {
          return res.status(400).json({
            success: false,
            error: 'Address required'
          });
        }

        // Mock account info per ora
        const response = {
          success: true,
          data: {
            address: address,
            amount: 5000000, // 5 ALGO in microAlgos
            assets: [
              {
                'asset-id': 100001,
                amount: 1,
                'is-frozen': false
              }
            ]
          }
        };

        res.json(response);

      } catch (error) {
        console.error('❌ Account info failed:', error);
        res.status(500).json({
          success: false,
          error: 'Account info retrieval failed'
        });
      }
    });

    // ===========================================
    // ADDITIONAL ENDPOINTS FOR TESTING
    // ===========================================

    // Blockchain status
    this.app.get('/blockchain/status', async (req, res) => {
      try {
        if (!this.algoClient) {
          return res.status(503).json({
            success: false,
            error: 'AlgoClient not initialized'
          });
        }

        const status = await this.algoClient.status().do();
        res.json({
          success: true,
          data: {
            network: 'Algorand TestNet',
            connected: true,
            lastRound: status['last-round'],
            genesisId: status['genesis-id'],
            timestamp: new Date().toISOString()
          }
        });
      } catch (error) {
        res.status(500).json({
          success: false,
          error: 'Blockchain status check failed'
        });
      }
    });

    // Laravel overview endpoint
    this.app.get('/overview', (req, res) => {
      // Mock statistiche per Laravel dashboard
      const totalIssued = Math.floor(Math.random() * 15); // 0-15 certificati emessi
      const totalAvailable = 40;

      res.json({
        success: true,
        data: {
          certificates: {
            total_available: totalAvailable,
            total_issued: totalIssued,
            remaining: totalAvailable - totalIssued,
            completion_percentage: parseFloat(((totalIssued / totalAvailable) * 100).toFixed(1))
          },
          round_info: {
            name: 'Padri Fondatori - Round 1',
            price: 250,
            currency: 'EUR',
            network: 'testnet'
          }
        }
      });
    });

    // 404 handler
    this.app.use('*', (req, res) => {
      console.log(`❌ 404: ${req.method} ${req.path}`);
      res.status(404).json({
        success: false,
        error: `Endpoint not found: ${req.method} ${req.path}`,
        available_endpoints: [
          'GET /health',
          'POST /mint-founder-token',
          'POST /transfer-asset',
          'GET /account/:address',
          'GET /blockchain/status',
          'GET /overview'
        ]
      });
    });

    // Error handler
    this.app.use((error, req, res, next) => {
      console.error('Server Error:', error);
      res.status(500).json({
        success: false,
        error: 'Internal server error'
      });
    });
  }

  async start() {
    console.log('🚀 Starting FlorenceEGI Foundrising Server...');

    // Inizializza AlgoClient
    const algoConnected = await this.initializeAlgoClient();

    if (!algoConnected) {
      console.warn('⚠️  Server starting without AlgoClient connection');
    }

    this.app.listen(this.port, '0.0.0.0', () => {
      console.log(`🚀 FlorenceEGI Server running on port ${this.port}`);
      console.log(`📍 Health: https://localhost:8443/health`);
      console.log(`🔗 Docker: https://host.docker.internal:8443/health`);
      console.log(`🔗 Laravel endpoints ready:`);
      console.log(`   POST https://localhost:8443/mint-founder-token`);
      console.log(`   POST https://localhost:8443/transfer-asset`);
      console.log(`   GET https://localhost:8443/account/:address`);
      console.log(`   GET https://localhost:8443/overview`);
      console.log(`🏗️  TestNet: ${algoConnected ? '✅ connected' : '❌ disconnected'}`);
      console.log(`🎯 Ready for Laravel integration!`);
      console.log(`🌐 Network binding: 0.0.0.0:${this.port} (Docker compatible)`);
    });
  }
}

// Avvio server
const PORT = process.env.PORT ? parseInt(process.env.PORT) : 4000;
const server = new FlorenceEGIServer(PORT);

server.start();

module.exports = FlorenceEGIServer;
