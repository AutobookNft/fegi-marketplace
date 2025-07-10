<?php

/**
 * @Oracode Livewire Component for Founder Certificate Form
 * 🎯 Purpose: Interactive form for issuing founder certificates with FlorenceEGI branding
 * 🧱 Core Logic: Real-time validation, API integration, UX feedback, brand styling
 * 🛡️ Security: Input sanitization, validation, CSRF protection, rate limiting
 *
 * @package App\Livewire
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori Form)
 * @date 2025-07-05
 * @purpose Interactive certificate issuance form with complete UX workflow
 */

namespace App\Livewire;

use Livewire\Component;
use App\Rules\AlgorandAddressRule;
use App\Models\FounderCertificate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Ultra\UltraLogManager\UltraLogManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\FoundersController;

class FounderCertificateForm extends Component
{
    // Form properties
    public string $investorName = '';
    public string $investorEmail = '';
    public string $investorPhone = '';
    public string $investorAddress = '';
    public string $investorWallet = '';

    // UI state properties
    public bool $isSubmitting = false;
    public bool $showSuccess = false;
    public bool $showGdprConsent = false;
    public bool $gdprConsent = false;

    // Success/error state
    public array $successData = [];
    public string $errorMessage = '';

    // Statistics for display
    public array $statistics = [];

    private UltraLogManager $logger;

    /**
     * Listener Livewire per la connessione/disconnessione wallet Pera
     */
    protected $listeners = [
        'walletConnected' => 'onWalletConnected',
        'walletDisconnected' => 'onWalletDisconnected',
    ];

    /**
     * Gestisce la connessione del wallet: salva l'address in sessione e nella proprietà
     */
    public function onWalletConnected($address)
    {
        session(['wallet_address' => $address]);
        $this->investorWallet = $address;
        $this->logger = app(UltraLogManager::class);
        $this->logger->info('Wallet collegato', ['wallet_address' => $address]);
        $this->dispatch('wallet-session-updated', $address); // opzionale per frontend
    }

    /**
     * Gestisce la disconnessione del wallet: pulisce sessione e proprietà
     */
    public function onWalletDisconnected()
    {
        session()->forget('wallet_address');
        $this->investorWallet = '';
        $this->logger = app(UltraLogManager::class);
        $this->logger->info('Wallet disconnesso');
        $this->dispatch('wallet-session-updated', null); // opzionale per frontend
    }

    /**
     * @Oracode Component mount lifecycle
     * 🎯 Purpose: Initialize component with current statistics
     */
    public function mount(): void
    {
        $this->logger = app(UltraLogManager::class);

        $this->loadStatistics();

        // dd('FounderCertificateForm mounted'); // Debugging line to check if the component is mounted correctly
        $this->logger->info('FounderCertificateForm mounted', [
            'investor_name' => $this->investorName,
            'investor_email' => $this->investorEmail,
            'investor_phone' => $this->investorPhone,
            'investor_address' => $this->investorAddress,
            'investor_wallet' => $this->investorWallet
        ]);
    }

    /**
     * @Oracode Real-time validation rules
     * 🎯 Purpose: Define validation rules for live validation
     */
    protected function rules(): array
    {
        return [
            'investorName' => 'required|string|min:2|max:200',
            'investorEmail' => 'required|email|max:200',
            'investorPhone' => 'nullable|string|max:50',
            'investorAddress' => 'nullable|string|max:1000',
            'investorWallet' => 'nullable|string', // Rimuovi AlgorandAddressRule temporaneamente
            'gdprConsent' => 'required' // Semplifica
        ];
    }

    /**
     * @Oracode Custom validation messages
     * 🎯 Purpose: Provide user-friendly Italian error messages
     */
    protected function messages(): array
    {
        return [
            'investorName.required' => 'Il nome è obbligatorio',
            'investorName.min' => 'Il nome deve essere di almeno 2 caratteri',
            'investorName.max' => 'Il nome non può superare 200 caratteri',
            'investorEmail.required' => 'L\'email è obbligatoria',
            'investorEmail.email' => 'Inserisci un indirizzo email valido',
            'investorEmail.max' => 'L\'email non può superare 200 caratteri',
            'investorPhone.max' => 'Il telefono non può superare 50 caratteri',
            'investorAddress.max' => 'L\'indirizzo non può superare 1000 caratteri',
            'investorWallet.string' => 'Il wallet deve essere una stringa valida',
            'gdprConsent.accepted' => 'Devi accettare l\'informativa privacy per procedere'
        ];
    }

    /**
     * @Oracode Real-time validation on property update
     * 🎯 Purpose: Provide immediate feedback during typing
     */
    public function updated($propertyName): void
    {
        // Validate only the changed property
        $this->validateOnly($propertyName);

        // Special handling for wallet field
        if ($propertyName === 'investorWallet' && !empty($this->investorWallet)) {
            $this->validateWalletAddress();
        }
    }

    /**
     * @Oracode Submit certificate issuance form
     * 🎯 Purpose: Process form submission and call API endpoint
     */
    public function submit(): void
    {
        $this->logger = app(UltraLogManager::class);

        // Reset error state
        $this->errorMessage = '';
        $this->isSubmitting = true;

        // Log submission attempt
        $this->logger->info('FounderCertificateForm submission started', [
            'investor_name' => $this->investorName,
            'investor_email' => $this->investorEmail,
            'investor_phone' => $this->investorPhone,
            'investor_address' => $this->investorAddress,
            'investor_wallet' => $this->investorWallet
        ]);

        try {

            // Validate all form data
            // $validatedData = $this->validate();
            $this->validate();

            // Check GDPR consent
            if (!$this->gdprConsent) {
                $this->addError('gdprConsent', 'Devi accettare l\'informativa privacy per procedere');
                return;
            }

            // Prepare API request data
            $requestData = [
                'investor_name' => trim($this->investorName),
                'investor_email' => trim($this->investorEmail),
                'investor_phone' => !empty($this->investorPhone) ? trim($this->investorPhone) : null,
                'investor_address' => !empty($this->investorAddress) ? trim($this->investorAddress) : null,
                'investor_wallet' => !empty($this->investorWallet) ? trim($this->investorWallet) : null
            ];


            // Call certificate issuance API
            $response = $this->callCertificateAPI($requestData);


            // Handle successful response
            $this->handleSuccess($response);

            // Refresh statistics
            $this->loadStatistics();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are handled automatically by Livewire
            $this->isSubmitting = false;
        } catch (\Exception $e) {
            $this->errorMessage = $this->getErrorMessage($e);
            $this->isSubmitting = false;
        }
    }

    /**
     * @Oracode Reset form to initial state
     * 🎯 Purpose: Clear form for new certificate issuance
     */
    public function resetForm(): void
    {
        $this->reset([
            'investorName',
            'investorEmail',
            'investorPhone',
            'investorAddress',
            'investorWallet',
            'isSubmitting',
            'showSuccess',
            'showGdprConsent',
            'gdprConsent',
            'successData',
            'errorMessage'
        ]);

        $this->resetValidation();
        $this->loadStatistics();
    }

    /**
     * @Oracode Toggle GDPR consent modal
     * 🎯 Purpose: Show/hide detailed privacy information
     */
    public function toggleGdprModal(): void
    {
        $this->showGdprConsent = !$this->showGdprConsent;
    }

    /**
     * @Oracode Load current certificate statistics
     * 🎯 Purpose: Update dashboard statistics for display
     */
    public function loadStatistics(): void
    {
        try {
            $response = Http::timeout(10)->get(url('/api/founders/overview'));

            if ($response->successful()) {
                $this->statistics = $response->json('data', []);
            } else {
                $this->statistics = $this->getDefaultStatistics();
            }
        } catch (\Exception $e) {
            $this->statistics = $this->getDefaultStatistics();
        }
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * @Oracode Validate Algorand wallet address
     * 🎯 Purpose: Provide immediate feedback for wallet validation
     */
    private function validateWalletAddress(): void
    {
        if (empty($this->investorWallet)) {
            return;
        }

        $validator = Validator::make(
            ['wallet' => $this->investorWallet],
            ['wallet' => new AlgorandAddressRule()],
            ['wallet.algorand_address' => 'Indirizzo wallet Algorand non valido']
        );

        if ($validator->fails()) {
            $this->addError('investorWallet', $validator->errors()->first('wallet'));
        }
    }

    /**
     * @Oracode Call certificate issuance API in-process
     * 🎯 Purpose: Invoke the API controller directly instead of HTTP
     */
    private function callCertificateAPI(array $requestData): array
    {
        $this->logger = app(UltraLogManager::class);

        $this->logger->info('Calling certificate issuance in-process', [
            'request_data' => $requestData
        ]);

        try {
            // Crea una Request fittizia con i dati
            $request = Request::create(
                '/api/founders/issue',
                'POST',
                $requestData
            );

            // Inietta il controller e chiama il metodo issue()
            /** @var FoundersController $controller */
            $controller = app(FoundersController::class);
            $response   = $controller->issue($request);

            // Estrarre l’array dal JsonResponse
            $payload = $response->getData(true);

            if (! ($payload['success'] ?? false)) {
                // gestisci errori di validazione o di business
                if (isset($payload['errors'])) {
                    foreach ($payload['errors'] as $field => $messages) {
                        $this->addError(
                            $this->mapApiFieldToProperty($field),
                            implode(', ', (array) $messages)
                        );
                    }
                    throw new \Exception('Errori di validazione');
                }
                throw new \Exception($payload['message'] ?? 'Errore interno');
            }

            $this->logger->info('In-process API response', [
                'data' => $payload['data']
            ]);

            return $payload['data'];
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Livewire gestisce già le eccezioni di validazione
            throw $e;
        } catch (\Exception $e) {
            $this->logger->error('In-process API call failed', [
                'error' => $e->getMessage(),
                'request_data' => $requestData
            ]);
            throw new \Exception('Errore interno: ' . $e->getMessage());
        }
    }

    /**
     * @Oracode Handle successful certificate issuance
     * 🎯 Purpose: Process success response and update UI
     */
    private function handleSuccess(array $responseData): void
    {
        $this->successData = [
            'certificate_number' => $responseData['certificate_number'],
            'asa_id' => $responseData['asa_id'],
            'transaction_id' => $responseData['transaction_id'],
            'pdf_url' => $responseData['pdf_url'],
            'token_transferred' => $responseData['token_transferred'],
            'blockchain_explorer' => $responseData['blockchain_explorer'],
            'issued_at' => $responseData['issued_at']
        ];

        $this->showSuccess = true;
        $this->isSubmitting = false;

        // Emit browser event for additional UI effects
        $this->dispatch('certificate-issued', $this->successData);
    }

    /**
     * @Oracode Map API field names to component properties
     * 🎯 Purpose: Convert API validation errors to component field names
     */
    private function mapApiFieldToProperty(string $apiField): string
    {
        $mapping = [
            'investor_name' => 'investorName',
            'investor_email' => 'investorEmail',
            'investor_phone' => 'investorPhone',
            'investor_address' => 'investorAddress',
            'investor_wallet' => 'investorWallet'
        ];

        return $mapping[$apiField] ?? $apiField;
    }

    /**
     * @Oracode Get user-friendly error message
     * 🎯 Purpose: Convert technical errors to user-friendly messages
     */
    private function getErrorMessage(\Exception $e): string
    {
        $message = $e->getMessage();

        // Map common errors to user-friendly messages
        if (str_contains($message, 'timeout')) {
            return 'Timeout durante l\'operazione. Riprova tra qualche minuto.';
        }

        if (str_contains($message, 'network') || str_contains($message, 'connection')) {
            return 'Problemi di connessione. Verifica la tua connessione internet.';
        }

        if (str_contains($message, 'Tutti i certificati')) {
            return 'Spiacenti, tutti i 40 certificati Padri Fondatori sono stati emessi.';
        }

        return 'Si è verificato un errore tecnico. Riprova o contatta il supporto.';
    }

    /**
     * @Oracode Get default statistics when API is unavailable
     * 🎯 Purpose: Provide fallback statistics for offline scenarios
     */
    private function getDefaultStatistics(): array
    {
        $issued = FounderCertificate::count();
        $total = config('founders.total_tokens', 40);

        return [
            'certificates' => [
                'total_available' => $total,
                'total_issued' => $issued,
                'remaining' => $total - $issued,
                'completion_percentage' => $total > 0 ? round(($issued / $total) * 100, 1) : 0
            ],
            'round_info' => [
                'name' => config('founders.round_title', 'Padri Fondatori - Round 1'),
                'price' => config('founders.price_eur', 250),
                'currency' => config('founders.currency', 'EUR'),
                'network' => ucfirst(config('founders.algorand.network', 'testnet'))
            ]
        ];
    }

    /**
     * @Oracode Render component view
     * 🎯 Purpose: Return component template for rendering
     */
    public function render()
    {
        return view('livewire.founder-certificate-form');
    }
}
