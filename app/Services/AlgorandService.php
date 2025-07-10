<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Ultra\UltraLogManager\UltraLogManager;
use Ultra\ErrorManager\Interfaces\ErrorManagerInterface;

/**
 * @Oracode Algorand Service per FlorenceEGI Founders System - MICROSERVICE VERSION
 * 🎯 Manage ASA creation, asset transfer e account info via AlgoKit Microservice
 * 🧱 Core: HTTP calls to AlgoKit microservice, same interface as before
 * 🛡️ Security: input validation, timeout handling, error management with UEM
 *
 * MICROSERVICE INTEGRATION:
 * - Uses AlgoKit 3.0 microservice instead of direct API calls
 * - Same public interface as original AlgorandService
 * - Improved reliability and error handling
 * - TypeScript AlgoKit backend for better blockchain integration
 *
 * @package App\Services
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 2.0.0 - MICROSERVICE INTEGRATION
 * @date 2025-07-08
 * @purpose Laravel HTTP client bridge to AlgoKit microservice
 */
class AlgorandService
{
    private UltraLogManager $logger;
    private ErrorManagerInterface $errorManager;
    private string $microserviceUrl;
    private int $apiTimeout;
    private int $apiRetries;
    private int $apiRetryDelay;
    private array $config;
    private int $totalTokens;
    private array $asaConfig;

    /**
     * AlgorandService constructor.
     * Carica config e imposta HTTP client per microservice
     */
    public function __construct(
        UltraLogManager $logger,
        ErrorManagerInterface $errorManager
    ) {
        $this->logger = $logger;
        $this->errorManager = $errorManager;

        // Carico la config principale
        $cfg = config('founders');
        $this->totalTokens = $cfg['total_tokens'] ?? 40;
        $this->asaConfig = $cfg['asa_config'] ?? [];

        // Configurazione microservice
        $this->microserviceUrl = rtrim(config('founders.algokit_microservice.url', 'http://localhost:3000'), '/');
        $this->apiTimeout = config('founders.algokit_microservice.timeout', 30);
        $this->apiRetries = config('founders.algokit_microservice.retries', 3);
        $this->apiRetryDelay = config('founders.algokit_microservice.retry_delay', 1000);

        $this->logger->info('AlgorandService initialized (Microservice Mode)', [
            'microservice_url' => $this->microserviceUrl,
            'total_tokens' => $this->totalTokens,
            'timeout' => $this->apiTimeout
        ]);
    }

    /**
     * Crea un nuovo ASA per certificato fondatore
     * @param int $index
     * @return array [asaId, txId]
     * @throws \Exception
     */
    public function mintFounderToken(int $index): array
    {
        $this->logger->info('ALGORAND_MINT_START', ['index' => $index]);

        if ($index < 1 || $index > $this->totalTokens) {
            throw new \InvalidArgumentException('Index fuori range (1-' . $this->totalTokens . ')');
        }

        try {
            // Prepara metadata per il certificato
            $metadata = $this->buildCertificateMetadata($index);

            // Chiama microservice per mint
            $response = $this->callMicroservice('POST', '/mint-founder-token', [
                'index' => $index,
                'metadata' => $metadata
            ]);

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Mint failed without error message');
            }

            $data = $response['data'];
            $asaId = $data['asaId'];
            $txId = $data['txId'];

            $this->logger->info('ALGORAND_MINT_SUCCESS', compact('index', 'asaId', 'txId'));

            return [
                'asaId' => $asaId,
                'txId' => $txId,
                'certificate_number' => $data['certificate_number'],
                'asset_url' => $data['asset_url'],
                'treasury_address' => $data['treasury_address']
            ];

        } catch (\Exception $e) {
            $this->logger->error('ALGORAND_MINT_FAILED', ['error' => $e->getMessage()]);
            throw new \Exception("Fallimento creazione token ASA: {$e->getMessage()}");
        }
    }

    /**
     * Trasferisce ASA al wallet investitore
     */
    public function transferAsset(string $to, string $asaId, int $amount = 1): string
    {
        $this->logger->info('ALGORAND_TRANSFER_START', compact('to', 'asaId', 'amount'));

        try {
            if (!$this->isValidAlgorandAddress($to)) {
                throw new \InvalidArgumentException('Address Algorand non valido');
            }

            // Chiama microservice per transfer
            $response = $this->callMicroservice('POST', '/transfer-asset', [
                'to' => $to,
                'asaId' => $asaId,
                'amount' => $amount
            ]);

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Transfer failed without error message');
            }

            $txId = $response['data']['txId'];

            $this->logger->info('ALGORAND_TRANSFER_SUCCESS', ['txId' => $txId]);
            return $txId;

        } catch (\Exception $e) {
            $this->logger->error('ALGORAND_TRANSFER_FAILED', ['error' => $e->getMessage()]);
            throw new \Exception("Fallimento trasferimento token: {$e->getMessage()}");
        }
    }

    /**
     * Ottiene info account
     */
    public function getAccountInfo(string $address): array
    {
        try {
            if (!$this->isValidAlgorandAddress($address)) {
                throw new \InvalidArgumentException('Address Algorand non valido');
            }

            $response = $this->callMicroservice('GET', "/account/{$address}");

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Account info failed');
            }

            return $response['data'];

        } catch (\Exception $e) {
            $this->logger->error('ALGORAND_ACCOUNT_INFO_FAILED', ['error' => $e->getMessage()]);
            throw new \Exception("Errore recupero info account: {$e->getMessage()}");
        }
    }

    /**
     * Verifica stato microservice
     */
    public function getNetworkStatus(): array
    {
        try {
            $response = $this->callMicroservice('GET', '/health');

            return [
                'success' => $response['success'],
                'microservice' => $response['service'] ?? 'AlgoKit Microservice',
                'version' => $response['version'] ?? 'Unknown',
                'algorand' => $response['algorand'] ?? [],
                'timestamp' => $response['timestamp'] ?? now()->toISOString()
            ];

        } catch (\Exception $e) {
            $this->logger->error('NETWORK_STATUS_FAILED', ['error' => $e->getMessage()]);
            throw new \Exception("Errore verifica stato rete: {$e->getMessage()}");
        }
    }

    /**
     * Stato tesoro
     */
    public function getTreasuryStatus(): array
    {
        try {
            $healthStatus = $this->getNetworkStatus();
            $treasuryAddress = $healthStatus['algorand']['treasury_address'] ?? null;

            if (!$treasuryAddress) {
                throw new \Exception('Treasury address not available from microservice');
            }

            return $this->getAccountInfo($treasuryAddress);

        } catch (\Exception $e) {
            $this->logger->error('TREASURY_STATUS_FAILED', ['error' => $e->getMessage()]);
            throw new \Exception("Errore stato treasury: {$e->getMessage()}");
        }
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * Effettua chiamata HTTP al microservice con retry logic
     */
    private function callMicroservice(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->microserviceUrl . $endpoint;
        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->apiRetries) {
            try {
                $attempt++;
                $this->logger->debug('MICROSERVICE_CALL', [
                    'method' => $method,
                    'url' => $url,
                    'attempt' => $attempt,
                    'data' => $data
                ]);

                $response = Http::timeout($this->apiTimeout)
                    ->acceptJson()
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'FlorenceEGI-Laravel/1.0'
                    ]);

                // Esegui la chiamata HTTP
                if ($method === 'GET') {
                    $httpResponse = $response->get($url);
                } elseif ($method === 'POST') {
                    $httpResponse = $response->post($url, $data);
                } else {
                    throw new \Exception("Metodo HTTP non supportato: {$method}");
                }

                // Verifica status HTTP
                if (!$httpResponse->successful()) {
                    $errorData = $httpResponse->json();
                    throw new \Exception(
                        $errorData['error'] ?? "HTTP {$httpResponse->status()}: {$httpResponse->body()}"
                    );
                }

                $responseData = $httpResponse->json();

                $this->logger->debug('MICROSERVICE_RESPONSE', [
                    'status' => $httpResponse->status(),
                    'success' => $responseData['success'] ?? false
                ]);

                return $responseData;

            } catch (\Exception $e) {
                $lastException = $e;
                $this->logger->warning('MICROSERVICE_CALL_FAILED', [
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                    'will_retry' => $attempt < $this->apiRetries
                ]);

                if ($attempt < $this->apiRetries) {
                    usleep($this->apiRetryDelay * 1000); // Convert to microseconds
                }
            }
        }

        throw new \Exception(
            "Microservice call failed after {$this->apiRetries} attempts. Last error: " .
            ($lastException ? $lastException->getMessage() : 'Unknown error')
        );
    }

    /**
     * Build metadata per certificato
     */
    private function buildCertificateMetadata(int $index): array
    {
        $cfg = $this->asaConfig;

        return [
            'name' => str_replace('{index}', str_pad($index, 2, '0', STR_PAD_LEFT), $cfg['asset_name']),
            'description' => $cfg['description'] ?? "Certificato Padre Fondatore FlorenceEGI #{$index}",
            'url' => str_replace('{index}', str_pad($index, 2, '0', STR_PAD_LEFT), $cfg['metadata_template_url']),
            'image' => $cfg['image_url'] ?? "https://florenceegi.it/images/certificates/{$index}.png",
            'external_url' => "https://florenceegi.it/certificates/{$index}",
            'attributes' => [
                [
                    'trait_type' => 'Collection',
                    'value' => 'Padri Fondatori'
                ],
                [
                    'trait_type' => 'Number',
                    'value' => $index
                ],
                [
                    'trait_type' => 'Series',
                    'value' => 'Genesis'
                ],
                [
                    'trait_type' => 'Rarity',
                    'value' => 'Unique'
                ]
            ]
        ];
    }

    /**
     * Validate Algorand address (basic validation)
     */
    private function isValidAlgorandAddress(string $address): bool
    {
        // Basic validation - 58 characters, alphanumeric
        if (strlen($address) !== 58) {
            return false;
        }

        return preg_match('/^[A-Z2-7]+$/', $address) === 1;
    }
}
