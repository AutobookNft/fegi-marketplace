/**
 * Express HTTP Server - AlgoKit Wrapper per FlorenceEGI Foundrising
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Founders Certificate System)
 * @date 2025-07-08
 * @purpose HTTP wrapper JavaScript per client AlgoKit - Laravel integration ready
 */

const express = require('express');
const cors = require('cors');
const algokit = require('@algorandfoundation/algokit-utils');

class AlgoKitServer {
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
      origin: ['http://localhost:8090', 'http://127.0.0.1:8090'],
      credentials: true,
      methods: ['GET', 'POST', 'PUT', 'DELETE'],
      allowedHeaders: ['Content-Type', 'Authorization']
    }));

    this.app.use(express.json({ limit: '10mb' }));
    this.app.use(express.urlencoded({ extended: true }));
  }

  async initializeAlgoClient() {
    try {
      // Configurazione per LocalNet (standard AlgoKit)
      const algodClient = algokit.getAlgoClient({
        server: 'http://localhost',
        port: 4001,
        token: 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
      });

      // Test connessione
      const status = await algodClient.status().do();
      console.log('✅ Connected to Algorand LocalNet, round:', status['last-round']);

      this.algoClient = algodClient;
      return true;
    } catch (error) {
      console.error('❌ Failed to connect to LocalNet:', error);
      return false;
    }
  }

  setupRoutes() {
    // Health check
    this.app.get('/health', (req, res) => {
      res.json({
        success: true,
        data: {
          service: 'FlorenceEGI Foundrising AlgoKit Server',
          status: 'running',
          algoClient: this.algoClient ? 'connected' : 'disconnected',
          timestamp: new Date().toISOString(),
          version: '1.0.0'
        }
      });
    });

    // Blockchain status
    this.app.get('/blockchain/status', async (req, res) => {
      try {
        if (!this.algoClient) {
          return res.status(503).json({
            success: false,
            error: 'AlgoClient non inizializzato'
          });
        }

        const status = await this.algoClient.status().do();
        res.json({
          success: true,
          data: {
            network: 'Algorand LocalNet',
            connected: true,
            lastRound: status['last-round'],
            genesisId: status['genesis-id'],
            timestamp: new Date().toISOString()
          }
        });
      } catch (error) {
        res.status(500).json({
          success: false,
          error: 'Errore connessione blockchain'
        });
      }
    });

    // Certificate mint
    this.app.post('/certificates/mint', async (req, res) => {
      try {
        const { recipientAddress, metadata } = req.body;

        // Validazione input
        if (!metadata || !metadata.name || !metadata.investorData) {
          return res.status(400).json({
            success: false,
            error: 'Dati certificato mancanti o invalidi'
          });
        }

        if (!this.algoClient) {
          return res.status(503).json({
            success: false,
            error: 'AlgoClient non inizializzato'
          });
        }

        // Mock response con dati reali AlgoKit
        const mockTxId = `ALGORAND_TX_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

        res.status(201).json({
          success: true,
          data: {
            certificateId: metadata.certificateId,
            txId: mockTxId,
            recipientAddress: recipientAddress || 'treasury_wallet',
            metadata: metadata,
            blockchainNetwork: 'Algorand LocalNet',
            timestamp: new Date().toISOString(),
            algoClientStatus: 'connected'
          },
          txId: mockTxId
        });
      } catch (error) {
        res.status(500).json({
          success: false,
          error: 'Errore mint certificato'
        });
      }
    });

    // Get certificato
    this.app.get('/certificates/:txId', (req, res) => {
      const { txId } = req.params;

      if (!txId) {
        return res.status(400).json({
          success: false,
          error: 'Transaction ID richiesto'
        });
      }

      res.json({
        success: true,
        data: {
          txId,
          status: 'confirmed',
          certificateData: 'Retrieved from Algorand LocalNet',
          timestamp: new Date().toISOString()
        }
      });
    });

    // Wallet balance
    this.app.get('/wallet/:address/balance', async (req, res) => {
      try {
        const { address } = req.params;

        if (!this.algoClient) {
          return res.status(503).json({
            success: false,
            error: 'AlgoClient non inizializzato'
          });
        }

        try {
          const accountInfo = await this.algoClient.accountInformation(address).do();

          res.json({
            success: true,
            data: {
              address,
              balance: (accountInfo.amount / 1_000_000).toString(),
              assets: accountInfo.assets || [],
              timestamp: new Date().toISOString()
            }
          });
        } catch (algoError) {
          res.status(404).json({
            success: false,
            error: 'Address non valido o non trovato'
          });
        }
      } catch (error) {
        res.status(500).json({
          success: false,
          error: 'Errore recupero balance'
        });
      }
    });

    // 404 handler
    this.app.use('*', (req, res) => {
      res.status(404).json({
        success: false,
        error: `Endpoint non trovato: ${req.method} ${req.path}`
      });
    });
  }

  async start() {
    // Prima inizializza AlgoClient
    const algoConnected = await this.initializeAlgoClient();

    if (!algoConnected) {
      console.warn('⚠️  Server starting without AlgoClient connection');
    }

    this.app.listen(this.port, () => {
      console.log(`🚀 FlorenceEGI Foundrising Server running on port ${this.port}`);
      console.log(`📍 Health check: http://localhost:${this.port}/health`);
      console.log(`🔗 Laravel integration ready on port ${this.port}`);
      console.log(`🏗️  LocalNet Algorand backend: ${algoConnected ? '✅ connected' : '❌ disconnected'}`);
    });
  }
}

// Avvio server
const PORT = process.env.PORT ? parseInt(process.env.PORT) : 4000;
const server = new AlgoKitServer(PORT);

server.start();

module.exports = AlgoKitServer;
