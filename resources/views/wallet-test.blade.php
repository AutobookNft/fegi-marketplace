<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test PeraWallet Connection</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .status {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .status.disconnected {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status.connected {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .wallet-info {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .log {
            background: #1f2937;
            color: #e5e7eb;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            text-align: left;
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 style="color: #374151; margin-bottom: 2rem;">🔗 Test PeraWallet</h1>

        <!-- Status Display -->
        <div id="wallet-status" class="status disconnected">
            ❌ Wallet non connesso
        </div>

        <!-- Wallet Info -->
        <div id="wallet-info" class="wallet-info" style="display: none;">
            <strong>Indirizzo:</strong> <span id="wallet-address"></span><br>
            <strong>Network:</strong> TestNet (416002)<br>
            <strong>Treasury:</strong> {{ config('founders.algorand.treasury_address') }}
        </div>

        <!-- Buttons -->
        <div style="margin: 2rem 0;">
            <button id="connect-pera-wallet" class="btn">
                🔗 Connetti PeraWallet
            </button>
            <button id="disconnect-wallet" class="btn" style="display: none;">
                🔌 Disconnetti
            </button>
        </div>

        <!-- Test API Button -->
        <div style="margin: 1rem 0;">
            <button id="test-api" class="btn" style="background: #059669;" disabled>
                🧪 Test API Backend
            </button>
            <button id="force-reset" class="btn" style="background: #dc2626;">
                🔄 Reset Completo
            </button>
        </div>

        <!-- Log Console -->
        <div id="log-console" class="log">
            <div id="log-content">📋 Console log:</div>
        </div>

        <!-- Clear Log -->
        <button onclick="clearLog()" class="btn"
            style="background: #dc2626; font-size: 0.9rem; padding: 0.5rem 1rem;">
            🗑️ Pulisci Log
        </button>
    </div>

    <!-- Treasury address for JavaScript -->
    <script>
        window.TREASURY_ADDRESS = '{{ config('founders.algorand.treasury_address') }}';
    </script>

    <!-- Test-specific JavaScript -->
    <script>
        // Log function
        function log(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const logContent = document.getElementById('log-content');
            const color = type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#60a5fa';
            logContent.innerHTML += `<div style="color: ${color};">[${timestamp}] ${message}</div>`;
            logContent.scrollTop = logContent.scrollHeight;
        }

        function clearLog() {
            document.getElementById('log-content').innerHTML = '📋 Console log:';
        }

        // Update UI based on wallet state
        function updateUI(connected, address = null) {
            const status = document.getElementById('wallet-status');
            const info = document.getElementById('wallet-info');
            const addressEl = document.getElementById('wallet-address');
            const connectBtn = document.getElementById('connect-pera-wallet');
            const disconnectBtn = document.getElementById('disconnect-wallet');
            const testBtn = document.getElementById('test-api');

            if (connected && address) {
                status.className = 'status connected';
                status.textContent = '✅ Wallet connesso';
                info.style.display = 'block';
                addressEl.textContent = address.slice(0, 8) + '...' + address.slice(-8);
                connectBtn.style.display = 'none';
                disconnectBtn.style.display = 'inline-block';
                testBtn.disabled = false;
                log('✅ UI aggiornata: wallet connesso', 'success');
            } else {
                status.className = 'status disconnected';
                status.textContent = '❌ Wallet non connesso';
                info.style.display = 'none';
                connectBtn.style.display = 'inline-block';
                disconnectBtn.style.display = 'none';
                testBtn.disabled = true;
                log('❌ UI aggiornata: wallet disconnesso');
            }
        }

        // Test API endpoint
        async function testAPI() {
            try {
                log('🧪 Testing API endpoint...');
                const response = await fetch('/api/wallet/status');
                const data = await response.json();

                if (response.ok) {
                    log(`✅ API Response: ${JSON.stringify(data)}`, 'success');
                } else {
                    log(`❌ API Error: ${data.error || 'Unknown error'}`, 'error');
                }
            } catch (error) {
                log(`❌ API Request failed: ${error.message}`, 'error');
            }
        }

        // Wait for FoundersWallet to be available
        function waitForFoundersWallet() {
            return new Promise((resolve, reject) => {
                let attempts = 0;
                const maxAttempts = 50; // 5 seconds max

                function check() {
                    attempts++;
                    if (window.FoundersWallet && window.FoundersWallet.connect) {
                        log('✅ FoundersWallet disponibile dopo ' + attempts + ' tentativi', 'success');
                        resolve();
                    } else if (attempts >= maxAttempts) {
                        log('❌ Timeout: FoundersWallet non disponibile dopo ' + maxAttempts + ' tentativi',
                            'error');
                        reject(new Error('FoundersWallet timeout'));
                    } else {
                        log('⏳ Aspettando FoundersWallet... tentativo ' + attempts);
                        setTimeout(check, 100);
                    }
                }
                check();
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', async function() {
            log('🚀 Test page loaded');
            log('📍 Treasury address: ' + window.TREASURY_ADDRESS);

            // Debug: check what's available in window
            log('🔍 Debug - window.FoundersWallet: ' + (typeof window.FoundersWallet));
            if (window.FoundersWallet) {
                log('🔍 Debug - FoundersWallet keys: ' + Object.keys(window.FoundersWallet).join(', '));
            }

            // Wait for FoundersWallet to be available
            try {
                await waitForFoundersWallet();
                log('🎯 FoundersWallet pronto per l\'uso', 'success');
            } catch (error) {
                log('❌ FoundersWallet non disponibile: ' + error.message, 'error');
                return;
            }

            // Test API button
            document.getElementById('test-api').addEventListener('click', testAPI);

            // Connect button - use the exposed global function
            document.getElementById('connect-pera-wallet').addEventListener('click', async function() {
                log('🔘 Pulsante di test cliccato!');

                if (window.FoundersWallet && window.FoundersWallet.connect) {
                    try {
                        // First, try to disconnect any existing session
                        log('🔌 Disconnettendo sessioni esistenti...');
                        if (window.FoundersWallet.peraWallet) {
                            try {
                                await window.FoundersWallet.peraWallet.disconnect();
                                log('✅ Sessione precedente disconnessa');
                            } catch (disconnectError) {
                                log('ℹ️ Nessuna sessione da disconnettere: ' + disconnectError
                                    .message);
                            }
                        }

                        // Clear any stored session
                        sessionStorage.removeItem('connected_wallet');
                        log('🗑️ Session storage pulito');

                        // Wait a moment
                        await new Promise(resolve => setTimeout(resolve, 500));

                        log('🔗 Chiamando FoundersWallet.connect()...');
                        await window.FoundersWallet.connect();

                        // Check if connected after attempt
                        if (window.FoundersWallet.isConnected()) {
                            const address = window.FoundersWallet.getAddress();
                            updateUI(true, address);
                            log('✅ Connessione completata con successo!', 'success');
                        }
                    } catch (error) {
                        log('❌ Errore durante connessione: ' + error.message, 'error');

                        // If it's a "Session currently connected" error, try to handle it
                        if (error.message.includes('Session currently connected')) {
                            log('🔄 Tentativo di riconnessione alla sessione esistente...');
                            try {
                                const accounts = await window.FoundersWallet.peraWallet
                                    .reconnectSession();
                                if (accounts && accounts.length > 0) {
                                    const address = accounts[0];
                                    log('✅ Riconnesso alla sessione esistente: ' + address,
                                        'success');

                                    // Validate the address
                                    const validation = window.FoundersWallet.validateWalletAddress(
                                        address);
                                    if (validation.valid) {
                                        updateUI(true, address);
                                        log('✅ Wallet validato con successo!', 'success');
                                    } else {
                                        log('❌ Wallet non autorizzato: ' + validation.message,
                                            'error');
                                    }
                                }
                            } catch (reconnectError) {
                                log('❌ Errore nella riconnessione: ' + reconnectError.message,
                                    'error');
                            }
                        }
                    }
                } else {
                    log('❌ FoundersWallet.connect non disponibile', 'error');
                }
            });

            // Check if wallet is already connected (from main app)
            if (window.FoundersWallet && window.FoundersWallet.isConnected()) {
                const address = window.FoundersWallet.getAddress();
                updateUI(true, address);
                log('♻️ Wallet già connesso dalla pagina principale', 'success');
            } else {
                updateUI(false);
                log('ℹ️ Nessun wallet connesso');
            }

            // Disconnect button
            document.getElementById('disconnect-wallet').addEventListener('click', function() {
                if (window.FoundersWallet) {
                    window.FoundersWallet.disconnect();
                    updateUI(false);
                    log('🔌 Wallet disconnesso manualmente');
                }
            });

            // Force reset button
            document.getElementById('force-reset').addEventListener('click', async function() {
                log('🔄 Eseguendo reset completo...');

                try {
                    // Disconnect from FoundersWallet
                    if (window.FoundersWallet && window.FoundersWallet.peraWallet) {
                        await window.FoundersWallet.peraWallet.disconnect();
                        log('✅ PeraWallet disconnesso');
                    }

                    // Clear all session storage
                    sessionStorage.clear();
                    log('✅ Session storage pulito');

                    // Clear local storage (if any)
                    localStorage.clear();
                    log('✅ Local storage pulito');

                    // Update UI
                    updateUI(false);
                    log('✅ Reset completo eseguito', 'success');

                } catch (error) {
                    log('❌ Errore durante reset: ' + error.message, 'error');
                }
            });
        });

        // Listen for wallet connection events from main app
        window.addEventListener('storage', function(e) {
            if (e.key === 'connected_wallet') {
                if (e.newValue) {
                    updateUI(true, e.newValue);
                    log('📡 Wallet connesso da altra finestra', 'success');
                } else {
                    updateUI(false);
                    log('📡 Wallet disconnesso da altra finestra');
                }
            }
        });
    </script>
</body>

</html>
