import { PeraWalletConnect } from "@perawallet/connect";

/**
 * @Oracode PeraWallet Integration for FlorenceEGI Founders System
 * 🎯 Purpose: Wallet connection, authentication, and treasury validation
 * 🧱 Core Logic: Connect → Validate → Store session → Enable form
 * 🛡️ Security: Treasury-only access, session management, error handling
 *
 * @version 1.0.0 (FlorenceEGI - Wallet Integration)
 * @date 2025-07-09
 */

// Espone PeraWalletConnect globalmente per uso in altre parti del sistema
window.PeraWalletConnect = PeraWalletConnect;

// Configurazione globale
const WALLET_CONFIG = {
    POLLING_INTERVAL: 2000,
    MAX_POLLING_ATTEMPTS: 30,
    RETRY_ATTEMPTS: 3,
    RETRY_DELAY: 1000,
    SYNC_CHECK_INTERVAL: 5000 // Controlla sincronizzazione ogni 5 secondi
};

// Variabili di stato globali
let peraWallet = null;
let connectionPollingInterval = null;
let syncCheckInterval = null;
let connectionRetryCount = 0;
let isConnecting = false;
let currentWalletAddress = null;

// Inizializzazione PeraWallet con configurazione ottimizzata
function initializePeraWallet() {
    try {
        // Configurazione ottimizzata per la connessione
        peraWallet = new PeraWalletConnect({
            chainId: 4160, // All networks per massima compatibilità
            shouldShowSignTxnToast: false, // Disabilita toast per evitare interferenze
            compactMode: false // UI completa per desktop
        });

        console.log('PeraWallet inizializzato con successo');

        // Setup event listeners avanzati
        setupWalletEventListeners();

        return true;
    } catch (error) {
        console.error('Errore inizializzazione PeraWallet:', error);
        return false;
    }
}

// Setup avanzato degli event listeners
function setupWalletEventListeners() {
    if (!peraWallet) return;

    // Listener per eventi di connessione/disconnessione
    try {
        // Gestione evento connect
        peraWallet.connector?.on("connect", (accounts) => {
            console.log('Evento connect ricevuto:', accounts);
            handleWalletConnected(accounts);
        });

        // Gestione evento disconnect
        peraWallet.connector?.on("disconnect", () => {
            console.log('Evento disconnect ricevuto');
            handleWalletDisconnected();
        });

        // Gestione eventi di sessione
        peraWallet.connector?.on("session_update", (accounts) => {
            console.log('Evento session_update ricevuto:', accounts);
            if (accounts && accounts.length > 0) {
                handleWalletConnected(accounts);
            } else {
                // Se session_update ha array vuoto, è una disconnessione
                handleWalletDisconnected();
            }
        });

        console.log('Event listeners configurati');
    } catch (error) {
        console.error('Errore setup event listeners:', error);
    }
}

// Controllo sincronizzazione periodico
function startSyncCheck() {
    if (syncCheckInterval) {
        clearInterval(syncCheckInterval);
    }

    syncCheckInterval = setInterval(async () => {
        if (currentWalletAddress) {
            await validateWalletSync();
        }
    }, WALLET_CONFIG.SYNC_CHECK_INTERVAL);

    console.log('Controllo sincronizzazione avviato');
}

// Ferma il controllo sincronizzazione
function stopSyncCheck() {
    if (syncCheckInterval) {
        clearInterval(syncCheckInterval);
        syncCheckInterval = null;
        console.log('Controllo sincronizzazione fermato');
    }
}

// Validazione sincronizzazione wallet
async function validateWalletSync() {
    try {
        console.log('Validazione sincronizzazione wallet...');

        // Controlla stato PeraWallet
        const peraConnected = peraWallet && peraWallet.isConnected;
        const peraAccounts = peraConnected ? await peraWallet.reconnectSession() : [];

        // Controlla stato Laravel
        const laravelSession = await checkLaravelSession();
        const laravelConnected = laravelSession && laravelSession.connected;

        console.log('Stati attuali:', {
            peraConnected,
            peraAccounts,
            laravelConnected,
            currentAddress: currentWalletAddress
        });

        // Scenari di sincronizzazione
        if (peraConnected && peraAccounts.length > 0 && laravelConnected) {
            // Entrambi connessi - verifica che abbiano lo stesso indirizzo
            const peraAddress = peraAccounts[0];
            const laravelAddress = laravelSession.address;

            if (peraAddress !== laravelAddress) {
                console.warn('Indirizzi diversi tra PeraWallet e Laravel:', {
                    pera: peraAddress,
                    laravel: laravelAddress
                });
                // Sincronizza con l'indirizzo di PeraWallet (più affidabile)
                await saveWalletSession(peraAddress);
                currentWalletAddress = peraAddress;
                updateWalletDisplay(peraAddress);
            }
        } else if (peraConnected && peraAccounts.length > 0 && !laravelConnected) {
            // PeraWallet connesso ma Laravel no - sincronizza Laravel
            console.log('PeraWallet connesso ma Laravel no - sincronizzazione...');
            await saveWalletSession(peraAccounts[0]);
            currentWalletAddress = peraAccounts[0];
            updateConnectionStatus('Wallet connesso', 'connected');
            updateWalletDisplay(peraAccounts[0]);
        } else if (!peraConnected && laravelConnected) {
            // Laravel connesso ma PeraWallet no - disconnetti tutto
            console.log('Laravel connesso ma PeraWallet no - disconnessione...');
            await handleWalletDisconnected();
        } else if (!peraConnected && !laravelConnected && currentWalletAddress) {
            // Entrambi disconnessi ma UI ancora connessa - aggiorna UI
            console.log('Entrambi disconnessi - aggiornamento UI...');
            handleWalletDisconnected();
        }

    } catch (error) {
        console.error('Errore validazione sincronizzazione:', error);
    }
}

// Funzione di connessione migliorata con retry logic
async function connectWallet() {
    if (isConnecting) {
        console.log('Connessione già in corso...');
        return;
    }

    isConnecting = true;
    connectionRetryCount = 0;

    updateConnectionStatus('Inizializzazione connessione...', 'connecting');

    try {
        await attemptConnection();
    } catch (error) {
        console.error('Errore durante la connessione:', error);
        updateConnectionStatus('Errore di connessione', 'error');
        handleConnectionError(error);
    } finally {
        isConnecting = false;
    }
}

// Tentativo di connessione con retry logic
async function attemptConnection() {
    for (let attempt = 1; attempt <= WALLET_CONFIG.RETRY_ATTEMPTS; attempt++) {
        try {
            console.log(`Tentativo di connessione ${attempt}/${WALLET_CONFIG.RETRY_ATTEMPTS}`);

            // Clear delle sessioni precedenti prima di ogni tentativo
            await clearWalletSessions();

            updateConnectionStatus(`Tentativo ${attempt}/${WALLET_CONFIG.RETRY_ATTEMPTS}...`, 'connecting');

            // Tentativo di connessione
            const accounts = await peraWallet.connect();

            console.log('Risultato connect():', accounts);

            // Verifica se abbiamo ricevuto account
            if (accounts && accounts.length > 0) {
                console.log('Connessione riuscita immediatamente:', accounts);
                handleWalletConnected(accounts);
                return;
            }

            // Se non abbiamo account, inizia il polling avanzato
            console.log('Nessun account ricevuto, avvio polling avanzato...');
            updateConnectionStatus('Scansiona il QR code con PeraWallet...', 'qr-waiting');

            const pollingResult = await startAdvancedConnectionPolling();

            if (pollingResult) {
                console.log('Connessione riuscita tramite polling:', pollingResult);
                handleWalletConnected(pollingResult);
                return;
            }

            // Se siamo qui, il tentativo è fallito
            if (attempt < WALLET_CONFIG.RETRY_ATTEMPTS) {
                console.log(`Tentativo ${attempt} fallito, retry in ${WALLET_CONFIG.RETRY_DELAY}ms...`);
                await new Promise(resolve => setTimeout(resolve, WALLET_CONFIG.RETRY_DELAY));
            }

        } catch (error) {
            console.error(`Errore tentativo ${attempt}:`, error);

            // Se l'utente ha chiuso il modal, non fare retry
            if (error?.data?.type === "CONNECT_MODAL_CLOSED") {
                console.log('Utente ha chiuso il modal di connessione');
                updateConnectionStatus('Connessione annullata', 'cancelled');
                return;
            }

            // Se è l'ultimo tentativo, rilancia l'errore
            if (attempt === WALLET_CONFIG.RETRY_ATTEMPTS) {
                throw error;
            }

            await new Promise(resolve => setTimeout(resolve, WALLET_CONFIG.RETRY_DELAY));
        }
    }

    throw new Error('Tutti i tentativi di connessione sono falliti');
}

// Polling avanzato per la connessione
async function startAdvancedConnectionPolling() {
    return new Promise((resolve, reject) => {
        let attempts = 0;

        const checkConnection = async () => {
            attempts++;

            try {
                console.log(`Polling attempt ${attempts}/${WALLET_CONFIG.MAX_POLLING_ATTEMPTS}`);

                // Controlla stato PeraWallet
                const isConnected = peraWallet.isConnected;
                const platform = peraWallet.platform;

                console.log('Stato PeraWallet:', { isConnected, platform });

                // Tenta reconnectSession per ottenere gli account
                const accounts = await peraWallet.reconnectSession();

                console.log('Risultato reconnectSession:', accounts);

                // Verifica se abbiamo account validi
                if (accounts && accounts.length > 0) {
                    console.log('Account trovati tramite polling:', accounts);
                    clearInterval(connectionPollingInterval);
                    resolve(accounts);
                    return;
                }

                // Aggiorna il messaggio di stato
                const remainingAttempts = WALLET_CONFIG.MAX_POLLING_ATTEMPTS - attempts;
                updateConnectionStatus(
                    `Attendo connessione... (${remainingAttempts} tentativi rimasti)`,
                    'qr-waiting'
                );

                // Se abbiamo raggiunto il limite, fermati
                if (attempts >= WALLET_CONFIG.MAX_POLLING_ATTEMPTS) {
                    console.log('Raggiunto limite tentativi polling');
                    clearInterval(connectionPollingInterval);
                    reject(new Error('Timeout durante l\'attesa della connessione'));
                }

            } catch (error) {
                console.error('Errore durante il polling:', error);
                // Continua il polling anche in caso di errore
            }
        };

        // Avvia il polling
        connectionPollingInterval = setInterval(checkConnection, WALLET_CONFIG.POLLING_INTERVAL);

        // Primo check immediato
        checkConnection();
    });
}

// Verifica sessione Laravel
async function checkLaravelSession() {
    try {
        const response = await fetch('/founders/wallet/status', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            const data = await response.json();
            return data;
        }
    } catch (error) {
        console.error('Errore controllo sessione Laravel:', error);
    }
    return null;
}

// Clear delle sessioni wallet
async function clearWalletSessions() {
    try {
        // Disconnect da PeraWallet se connesso
        if (peraWallet && peraWallet.isConnected) {
            await peraWallet.disconnect();
        }

        // Clear localStorage relativo a WalletConnect
        const keysToRemove = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && (key.includes('walletconnect') || key.includes('pera'))) {
                keysToRemove.push(key);
            }
        }

        keysToRemove.forEach(key => localStorage.removeItem(key));

        console.log('Sessioni wallet pulite');
    } catch (error) {
        console.error('Errore pulizia sessioni:', error);
    }
}

// Gestione connessione riuscita
function handleWalletConnected(accounts) {
    console.log('Gestione connessione riuscita:', accounts);

    // Ferma il polling se attivo
    if (connectionPollingInterval) {
        clearInterval(connectionPollingInterval);
        connectionPollingInterval = null;
    }

    const address = Array.isArray(accounts) ? accounts[0] : accounts;

    if (!address) {
        console.error('Nessun indirizzo ricevuto');
        updateConnectionStatus('Errore: nessun indirizzo ricevuto', 'error');
        return;
    }

    // Aggiorna stato globale
    currentWalletAddress = address;

    // Aggiorna UI
    updateConnectionStatus('Wallet connesso', 'connected');
    updateWalletDisplay(address);

    // Salva in sessione Laravel
    saveWalletSession(address);

    // Avvia controllo sincronizzazione
    startSyncCheck();

    // Reset retry count
    connectionRetryCount = 0;
    isConnecting = false;
}

// Gestione disconnessione
function handleWalletDisconnected() {
    console.log('Wallet disconnesso');

    // Ferma il polling se attivo
    if (connectionPollingInterval) {
        clearInterval(connectionPollingInterval);
        connectionPollingInterval = null;
    }

    // Ferma controllo sincronizzazione
    stopSyncCheck();

    // Clear stato globale
    currentWalletAddress = null;

    // Aggiorna UI
    updateConnectionStatus('Wallet disconnesso', 'disconnected');
    updateWalletDisplay(null);

    // Clear sessione Laravel
    clearWalletSession();

    // Reset stato
    isConnecting = false;
    connectionRetryCount = 0;
}

// Gestione errori di connessione
function handleConnectionError(error) {
    console.error('Errore di connessione:', error);

    // Ferma il polling se attivo
    if (connectionPollingInterval) {
        clearInterval(connectionPollingInterval);
        connectionPollingInterval = null;
    }

    let errorMessage = 'Errore di connessione';

    if (error?.data?.type === "CONNECT_MODAL_CLOSED") {
        errorMessage = 'Connessione annullata';
    } else if (error.message) {
        errorMessage = error.message;
    }

    updateConnectionStatus(errorMessage, 'error');
    isConnecting = false;
}

// Salvataggio sessione Laravel
async function saveWalletSession(address) {
    try {
        const response = await fetch('/founders/wallet/connect', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                wallet_address: address
            })
        });

        const data = await response.json();

        if (data.success) {
            console.log('Sessione salvata con successo');

            // Redirect alla dashboard SOLO se siamo sulla pagina wallet-connect del Treasury
            // Non fare redirect se siamo su altre pagine (es. pagina certificato)
            const isWalletConnectPage = window.location.pathname === '/founders/wallet';

            if (isWalletConnectPage) {
                // Gestisco il redirect manualmente con l'URL corretto
                // Genero l'URL della dashboard con HTTPS:8443
                let dashboardUrl = '/founders/dashboard';

                // Se siamo in development, forza HTTPS:8443
                if (window.location.hostname === 'localhost') {
                    dashboardUrl = `https://localhost:8443${dashboardUrl}`;
                }

                console.log('Redirect verso:', dashboardUrl);

                // Redirect dopo un breve delay per permettere all'UI di aggiornarsi
                setTimeout(() => {
                    window.location.href = dashboardUrl;
                }, 1000);
            } else {
                console.log('Sessione salvata - nessun redirect (non siamo sulla pagina wallet-connect)');
            }
        } else {
            console.error('Errore salvataggio sessione:', data.error);
            updateConnectionStatus('Errore autorizzazione wallet', 'error');
        }
    } catch (error) {
        console.error('Errore salvataggio sessione:', error);
        updateConnectionStatus('Errore di comunicazione', 'error');
    }
}

// Clear sessione Laravel
async function clearWalletSession() {
    try {
        await fetch('/founders/wallet/disconnect', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        console.log('Sessione Laravel pulita');
    } catch (error) {
        console.error('Errore pulizia sessione Laravel:', error);
    }
}

// Disconnessione wallet
async function disconnectWallet() {
    try {
        updateConnectionStatus('Disconnessione...', 'disconnecting');

        // Disconnect da PeraWallet
        if (peraWallet) {
            await peraWallet.disconnect();
        }

        // Clear sessione Laravel
        await clearWalletSession();

        // Clear localStorage
        await clearWalletSessions();

        // Aggiorna UI
        handleWalletDisconnected();

        console.log('Disconnessione completata');
    } catch (error) {
        console.error('Errore durante la disconnessione:', error);
        updateConnectionStatus('Errore disconnessione', 'error');
    }
}

// Aggiornamento stato connessione
function updateConnectionStatus(message, status) {
    const statusElement = document.getElementById('connection-status');
    const connectButton = document.getElementById('connect-wallet-btn');
    const disconnectButton = document.getElementById('disconnect-wallet-btn');

    if (statusElement) {
        statusElement.textContent = message;
        statusElement.className = `connection-status ${status}`;
    }

    // Aggiorna pulsanti
    if (connectButton && disconnectButton) {
        switch (status) {
            case 'connected':
                connectButton.style.display = 'none';
                disconnectButton.style.display = 'inline-block';
                break;
            case 'connecting':
            case 'qr-waiting':
                connectButton.disabled = true;
                connectButton.textContent = 'Connessione...';
                disconnectButton.style.display = 'none';
                break;
            case 'disconnected':
            case 'error':
            case 'cancelled':
                connectButton.style.display = 'inline-block';
                connectButton.disabled = false;
                connectButton.textContent = 'Connetti Wallet';
                disconnectButton.style.display = 'none';
                break;
        }
    }
}

// Aggiornamento display wallet
function updateWalletDisplay(address) {
    const addressElement = document.getElementById('wallet-address');
    const walletInfo = document.getElementById('wallet-info');

    if (address) {
        if (addressElement) {
            addressElement.textContent = `${address.substring(0, 6)}...${address.substring(address.length - 4)}`;
        }
        if (walletInfo) {
            walletInfo.style.display = 'block';
        }
    } else {
        if (addressElement) {
            addressElement.textContent = '';
        }
        if (walletInfo) {
            walletInfo.style.display = 'none';
        }
    }
}

// Controlla e valida sessione esistente all'avvio
async function checkExistingSession() {
    try {
        console.log('Controllo sessione esistente...');

        // Controlla entrambi gli stati
        const laravelSession = await checkLaravelSession();
        const peraAccounts = peraWallet ? await peraWallet.reconnectSession() : [];

        console.log('Sessioni trovate:', {
            laravel: laravelSession,
            pera: peraAccounts
        });

        // Validazione incrociata
        const laravelConnected = laravelSession && laravelSession.connected;
        const peraConnected = peraAccounts && peraAccounts.length > 0;

        if (laravelConnected && peraConnected) {
            // Entrambi connessi - verifica coerenza
            const laravelAddress = laravelSession.address;
            const peraAddress = peraAccounts[0];

            if (laravelAddress === peraAddress) {
                console.log('Sessione valida trovata:', laravelAddress);
                currentWalletAddress = laravelAddress;
                updateConnectionStatus('Wallet connesso', 'connected');
                updateWalletDisplay(laravelAddress);
                startSyncCheck();
                return true;
            } else {
                console.warn('Indirizzi diversi - pulizia sessioni');
                await clearWalletSessions();
                await clearWalletSession();
                return false;
            }
        } else if (laravelConnected && !peraConnected) {
            // Solo Laravel connesso - probabilmente disconnesso dal telefono
            console.log('Solo Laravel connesso - pulizia sessione');
            await clearWalletSession();
            return false;
        } else if (!laravelConnected && peraConnected) {
            // Solo PeraWallet connesso - ristabilisci Laravel
            console.log('Solo PeraWallet connesso - sincronizzazione');
            await saveWalletSession(peraAccounts[0]);
            currentWalletAddress = peraAccounts[0];
            updateConnectionStatus('Wallet connesso', 'connected');
            updateWalletDisplay(peraAccounts[0]);
            startSyncCheck();
            return true;
        }

        // Nessuna sessione valida
        return false;
    } catch (error) {
        console.error('Errore controllo sessione esistente:', error);
        return false;
    }
}

// Inizializzazione quando il DOM è pronto
document.addEventListener('DOMContentLoaded', async function() {
    // Inizializza il sistema Treasury SOLO sulla pagina wallet-connect
    const isWalletConnectPage = window.location.pathname === '/founders/wallet';

    if (!isWalletConnectPage) {
        console.log('Sistema Treasury non inizializzato - non siamo sulla pagina wallet-connect');

        // Inizializza solo PeraWallet per uso generale (senza UI Treasury)
        if (!initializePeraWallet()) {
            console.error('Impossibile inizializzare PeraWallet');
        } else {
            console.log('PeraWallet inizializzato per uso generale');
        }
        return;
    }

    console.log('Inizializzazione wallet system Treasury...');

    // Inizializza PeraWallet
    if (!initializePeraWallet()) {
        console.error('Impossibile inizializzare PeraWallet');
        updateConnectionStatus('Errore inizializzazione', 'error');
        return;
    }

    // Controlla sessioni esistenti con validazione incrociata
    const hasExistingSession = await checkExistingSession();

    if (!hasExistingSession) {
        updateConnectionStatus('Wallet non connesso', 'disconnected');
    }

    // Setup event listeners per i pulsanti
    const connectButton = document.getElementById('connect-wallet-btn');
    const disconnectButton = document.getElementById('disconnect-wallet-btn');

    if (connectButton) {
        connectButton.addEventListener('click', connectWallet);
    }

    if (disconnectButton) {
        disconnectButton.addEventListener('click', disconnectWallet);
    }

    console.log('Wallet system Treasury inizializzato');
});

// Cleanup quando la pagina viene chiusa
window.addEventListener('beforeunload', function() {
    if (connectionPollingInterval) {
        clearInterval(connectionPollingInterval);
    }
    if (syncCheckInterval) {
        clearInterval(syncCheckInterval);
    }
});

// Export per uso globale
window.WalletManager = {
    connect: connectWallet,
    disconnect: disconnectWallet,
    checkSession: checkExistingSession,
    clearSessions: clearWalletSessions,
    validateSync: validateWalletSync,
    // Funzioni di debug
    debug: {
        getCurrentState: () => ({
            currentWalletAddress,
            peraConnected: peraWallet?.isConnected,
            isConnecting,
            hasSyncCheck: !!syncCheckInterval
        }),
        forceValidation: validateWalletSync,
        forceDisconnect: async () => {
            console.log('🔧 DEBUG: Forzando disconnessione completa...');
            await clearWalletSession();
            await clearWalletSessions();
            handleWalletDisconnected();
        },
        checkBothSessions: async () => {
            const laravel = await checkLaravelSession();
            const pera = peraWallet ? await peraWallet.reconnectSession() : [];
            console.log('🔧 DEBUG: Stati sessioni:', {
                laravel,
                pera,
                peraConnected: peraWallet?.isConnected
            });
            return { laravel, pera };
        },
        startSyncCheck: () => {
            console.log('🔧 DEBUG: Avvio manuale sync check...');
            startSyncCheck();
        },
        stopSyncCheck: () => {
            console.log('🔧 DEBUG: Fermo sync check...');
            stopSyncCheck();
        },
        simulateConnection: (address = 'TEST_ADDRESS') => {
            console.log('🔧 DEBUG: Simulazione connessione con indirizzo:', address);
            currentWalletAddress = address;
            startSyncCheck();
            console.log('Sync check avviato. Stato:', {
                currentWalletAddress,
                hasSyncCheck: !!syncCheckInterval
            });
        }
    }
};

// Wrapper per compatibilità con il codice esistente
window.FoundersWallet = {
    connect: async () => {
        console.log('FoundersWallet.connect() chiamato - usando WalletManager');
        return await connectWallet();
    },
    disconnect: async () => {
        console.log('FoundersWallet.disconnect() chiamato - usando WalletManager');
        return await disconnectWallet();
    },
    isConnected: () => {
        return !!currentWalletAddress && peraWallet?.isConnected;
    },
    getAddress: () => {
        return currentWalletAddress;
    }
};
