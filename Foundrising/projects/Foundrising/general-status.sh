#!/bin/bash
echo "🔧 FlorenceEGI System Health Check"
echo "=================================="

echo "📡 1. LocalNet Algorand..."
algokit localnet status | grep "Status: Running" && echo "✅ LocalNet OK" || echo "❌ LocalNet FAIL"

echo "🚀 2. AlgoKit Server..."
curl -s http://localhost:4000/health > /dev/null && echo "✅ AlgoKit Server OK" || echo "❌ AlgoKit Server FAIL"

echo "🐳 3. Laravel Container..."
curl -s -I http://localhost:8090 > /dev/null && echo "✅ Laravel OK" || echo "❌ Laravel FAIL"

echo "🔗 4. Cross-container connectivity..."
curl -s http://host.docker.internal:4000/health > /dev/null && echo "✅ Docker connectivity OK" || echo "❌ Docker connectivity FAIL"

echo "📄 5. Founders page..."
curl -s -I http://localhost:8090/founders > /dev/null && echo "✅ Founders page OK" || echo "❌ Founders page FAIL"

echo "🎯 6. API Overview..."
curl -s http://localhost:8090/api/founders/overview > /dev/null && echo "✅ API OK" || echo "❌ API FAIL"

echo "=================================="
echo "Health check completed!"
