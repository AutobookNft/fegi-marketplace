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

// Initialize Pera Wallet
const peraWallet = new PeraWalletConnect({
    chainId: 416002, // TestNet - change to 416001 for MainNet
    shouldShowSignTxnToast: false
});

// Treasury wallet address - this will be set from Laravel config
const TREASURY_ADDRESS = window.TREASURY_ADDRESS || 'TLR3CV4Z4LIXZIMRCO45ZURSARCYCCW5RAPW26LYW4E2LMBO5TYM227W64';

// DOM elements
const connectBtn = document.getElementById('connect-pera-wallet');
const connectingDiv = document.getElementById('wallet-connecting');
const connectionSection = document.getElementById('wallet-connection-section');
const mainFormSection = document.getElementById('main-form-section');
const walletStatus = document.getElementById('wallet-status');
const connectedWalletAddress = document.getElementById('connected-wallet-address');
const errorModal = document.getElementById('wallet-error-modal');
const errorMessage = document.getElementById('wallet-error-message');
const closeErrorModal = document.getElementById('close-error-modal');
const closeErrorBtn = document.getElementById('close-error-btn');

// Wallet connection state
let isConnected = false;
let connectedAddress = null;

/**
 * Show error modal with message
 */
function showError(message) {
    if (errorMessage && errorModal) {
        errorMessage.textContent = message;
        errorModal.classList.remove('hidden');
        errorModal.classList.add('flex');
    }
}

/**
 * Hide error modal
 */
function hideError() {
    if (errorModal) {
        errorModal.classList.remove('flex');
        errorModal.classList.add('hidden');
    }
}

/**
 * Show connecting state
 */
function showConnecting() {
    if (connectBtn) {
        connectBtn.style.display = 'none';
    }
    if (connectingDiv) {
        connectingDiv.classList.remove('hidden');
    }
}

/**
 * Hide connecting state
 */
function hideConnecting() {
    if (connectBtn) {
        connectBtn.style.display = 'inline-block';
    }
    if (connectingDiv) {
        connectingDiv.classList.add('hidden');
    }
}

/**
 * Show main form after successful connection
 */
function showMainForm(walletAddress) {
    if (connectionSection) {
        connectionSection.classList.add('hidden');
    }
    if (mainFormSection) {
        mainFormSection.classList.remove('hidden');
    }
    if (walletStatus) {
        walletStatus.classList.remove('hidden');
    }

    // Display connected wallet address (truncated)
    if (connectedWalletAddress) {
        const truncatedAddress = walletAddress.slice(0, 8) + '...' + walletAddress.slice(-8);
        connectedWalletAddress.textContent = truncatedAddress;
    }

    // Store in session
    sessionStorage.setItem('connected_wallet', walletAddress);

    // Set global state
    isConnected = true;
    connectedAddress = walletAddress;

    console.log('✅ Wallet connected successfully:', walletAddress);
}

/**
 * Validate wallet address against treasury
 */
function validateWalletAddress(address) {
    if (!address) {
        return { valid: false, message: 'Indirizzo wallet non fornito' };
    }

    if (address !== TREASURY_ADDRESS) {
        return {
            valid: false,
            message: 'Solo il wallet Treasury può accedere al sistema Padri Fondatori.\n\nWallet connesso: ' + address.slice(0, 12) + '...\nWallet richiesto: ' + TREASURY_ADDRESS.slice(0, 12) + '...'
        };
    }

    return { valid: true };
}

/**
 * Send wallet address to Laravel backend
 */
async function sendWalletToBackend(address) {
    try {
        const response = await fetch('/api/wallet/connect', {
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

        if (!response.ok) {
            throw new Error(data.error || 'Errore di connessione al server');
        }

        return data;
    } catch (error) {
        console.error('❌ Backend connection error:', error);
        throw error;
    }
}

/**
 * Handle wallet connection
 */
async function handleWalletConnection() {
    showConnecting();

    try {
        console.log('🔗 Tentativo di connessione a PeraWallet...');

        // Prima disconnetti eventuali sessioni esistenti
        try {
            await peraWallet.disconnect();
            console.log('🔌 Sessione precedente disconnessa');
        } catch (disconnectError) {
            console.log('ℹ️ Nessuna sessione precedente da disconnettere');
        }

        // Aspetta un momento prima di riconnettersi
        await new Promise(resolve => setTimeout(resolve, 500));

                        // Prima controlla se ci sono account già connessi
        try {
            const existingAccounts = await peraWallet.reconnectSession();
            console.log('🔄 Account già connessi:', existingAccounts);
            if (existingAccounts && existingAccounts.length > 0) {
                console.log('✅ Trovati account esistenti, li uso direttamente');
                const walletAddress = existingAccounts[0];
                console.log('🔗 Wallet address da sessione esistente:', walletAddress);

                const validation = validateWalletAddress(walletAddress);
                if (validation.valid) {
                    await sendWalletToBackend(walletAddress);
                    showMainForm(walletAddress);
                    peraWallet.connector?.on('disconnect', handleWalletDisconnect);
                    return;
                }
            }
        } catch (reconnectError) {
            console.log('ℹ️ Nessuna sessione esistente da riconnettere:', reconnectError);
        }

        // Connect to Pera Wallet
        console.log('🔗 Chiamando peraWallet.connect()...');
        console.log('🔗 PeraWallet instance:', peraWallet);
        console.log('🔗 PeraWallet connector:', peraWallet.connector);

        const accounts = await peraWallet.connect();

        console.log('📋 Accounts ricevuti:', accounts);
        console.log('📋 Tipo accounts:', typeof accounts);
        console.log('📋 Array.isArray(accounts):', Array.isArray(accounts));
        console.log('📋 Lunghezza accounts:', accounts ? accounts.length : 'N/A');
        console.log('📋 JSON.stringify(accounts):', JSON.stringify(accounts));

        if (!accounts || accounts.length === 0) {
            console.error('❌ Nessun account ricevuto da PeraWallet');
            throw new Error('Nessun account selezionato nel wallet. Assicurati di aver selezionato un account in PeraWallet.');
        }

        const walletAddress = accounts[0];
        console.log('🔗 Wallet address ricevuto:', walletAddress);

        if (!walletAddress || walletAddress.length !== 58) {
            throw new Error('Indirizzo wallet non valido ricevuto da PeraWallet');
        }

        // Validate wallet address
        const validation = validateWalletAddress(walletAddress);
        if (!validation.valid) {
            throw new Error(validation.message);
        }

        // Send to backend for session creation
        console.log('📤 Inviando al backend...');
        await sendWalletToBackend(walletAddress);

        // Show main form
        showMainForm(walletAddress);

        // Setup disconnect listener
        peraWallet.connector?.on('disconnect', handleWalletDisconnect);

    } catch (error) {
        console.error('❌ Wallet connection error:', error);
        console.error('❌ Error details:', {
            message: error.message,
            data: error.data,
            type: error.data?.type,
            stack: error.stack
        });

        // Handle specific error types
        if (error.data?.type === 'CONNECT_MODAL_CLOSED') {
            console.log('ℹ️ Utente ha chiuso il modal di connessione');
            // User closed the modal - don't show error
        } else if (error.message && error.message.includes('Session currently connected')) {
            // Gestisci errore di sessione già connessa
            console.log('🔄 Sessione già connessa, provo a riconnettermi...');
            try {
                const existingAccounts = await peraWallet.reconnectSession();
                if (existingAccounts && existingAccounts.length > 0) {
                    const walletAddress = existingAccounts[0];
                    const validation = validateWalletAddress(walletAddress);
                    if (validation.valid) {
                        await sendWalletToBackend(walletAddress);
                        showMainForm(walletAddress);
                        peraWallet.connector?.on('disconnect', handleWalletDisconnect);
                        return;
                    }
                }
            } catch (reconnectError) {
                console.error('❌ Errore durante riconnessione:', reconnectError);
            }
            showError('Errore di sessione PeraWallet. Prova a disconnettere e riconnettere il wallet.');
        } else {
            showError(error.message || 'Errore di connessione al wallet');
        }
    } finally {
        hideConnecting();
    }
}

/**
 * Handle wallet disconnect
 */
function handleWalletDisconnect() {
    console.log('🔌 Wallet disconnected');

    // Clear session
    sessionStorage.removeItem('connected_wallet');

    // Reset UI
    if (connectionSection) {
        connectionSection.classList.remove('hidden');
    }
    if (mainFormSection) {
        mainFormSection.classList.add('hidden');
    }
    if (walletStatus) {
        walletStatus.classList.add('hidden');
    }

    // Reset state
    isConnected = false;
    connectedAddress = null;

    // Show connection section again
    hideConnecting();
}

/**
 * Check for existing session on page load
 */
async function checkExistingSession() {
    try {
        // Prima controlla se Laravel ha la sessione wallet
        const response = await fetch('/api/wallet/status');
        const laravelSession = await response.json();

        if (laravelSession.success && laravelSession.connected) {
            // Laravel ha la sessione, verifica PeraWallet
            const savedAddress = sessionStorage.getItem('connected_wallet');
            if (savedAddress) {
                const accounts = await peraWallet.reconnectSession();
                if (accounts && accounts.length > 0 && accounts[0] === savedAddress) {
                    const validation = validateWalletAddress(savedAddress);
                    if (validation.valid) {
                        showMainForm(savedAddress);
                        peraWallet.connector?.on('disconnect', handleWalletDisconnect);
                        return;
                    }
                }
            }
        } else {
            // Laravel non ha la sessione, disconnetti PeraWallet
            console.log('🔄 Laravel session not found, disconnecting PeraWallet...');
            try {
                await peraWallet.disconnect();
            } catch (disconnectError) {
                console.log('ℹ️ PeraWallet already disconnected');
            }
        }

        // Clear invalid session
        sessionStorage.removeItem('connected_wallet');
        handleWalletDisconnect();
    } catch (error) {
        console.log('ℹ️ No existing session found:', error);
        // In caso di errore, disconnetti tutto
        try {
            await peraWallet.disconnect();
        } catch (disconnectError) {
            console.log('ℹ️ PeraWallet already disconnected');
        }
        sessionStorage.removeItem('connected_wallet');
        handleWalletDisconnect();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check for existing session
    checkExistingSession();

    // Event listeners
    if (connectBtn) {
        connectBtn.addEventListener('click', function() {
            console.log('🔘 Pulsante cliccato!');
            handleWalletConnection();
        });
    }

    if (closeErrorModal) {
        closeErrorModal.addEventListener('click', hideError);
    }

    if (closeErrorBtn) {
        closeErrorBtn.addEventListener('click', hideError);
    }
});

// Expose wallet state and functions globally
window.FoundersWallet = {
    isConnected: () => isConnected,
    getAddress: () => connectedAddress,
    connect: handleWalletConnection,
    disconnect: () => {
        peraWallet.disconnect();
        handleWalletDisconnect();
    },
    // Expose internal functions for testing
    peraWallet: peraWallet,
    validateWalletAddress: validateWalletAddress,
    sendWalletToBackend: sendWalletToBackend
};
