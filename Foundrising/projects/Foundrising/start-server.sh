#!/bin/bash

echo "🚀 FlorenceEGI Foundrising - Starting AlgoKit Server"
echo "=================================================="

# Verifica che AlgoKit LocalNet sia attivo
echo "📡 Checking AlgoKit LocalNet status..."
algokit localnet status

if [ $? -ne 0 ]; then
    echo "🔧 Starting AlgoKit LocalNet..."
    algokit localnet start

    # Aspetta che LocalNet sia ready
    echo "⏳ Waiting for LocalNet to be ready..."
    sleep 10
fi

echo "✅ LocalNet is running"

# Installa dependencies se non presenti
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install
fi

# Avvia il server Express
echo "🔥 Starting Express HTTP Server on port 4000..."
echo "🔗 Laravel can now call: http://localhost:4000"
echo "📍 Health check: http://localhost:4000/health"
echo "💳 Mint endpoint: POST http://localhost:4000/certificates/mint"
echo ""
echo "Press Ctrl+C to stop server"
echo "=================================================="

npm run server:dev
