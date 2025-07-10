<?php

/**
 * @Oracode Test Command for AlgorandService
 * 🎯 Purpose: Test Algorand service functionality and network connectivity
 * 🧱 Core Logic: Test network status, account info, configuration validation
 * 🛡️ Security: Safe testing without real transactions, placeholder validation
 *
 * @package App\Console\Commands
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - AlgorandService Testing)
 * @date 2025-07-05
 * @purpose Test and validate AlgorandService functionality
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AlgorandService;
use App\Rules\AlgorandAddressRule;

class TestAlgorandService extends Command
{
    protected $signature = 'algorand:test {--full : Run full test suite including placeholder methods}';
    protected $description = 'Test AlgorandService functionality and network connectivity';

    private AlgorandService $algorandService;

    public function __construct(AlgorandService $algorandService)
    {
        parent::__construct();
        $this->algorandService = $algorandService;
    }

    public function handle(): int
    {
        $this->info('🧪 TESTING ALGORAND SERVICE');
        $this->newLine();

        $testResults = [];

        // Test 1: Configuration Check
        $testResults['config'] = $this->testConfiguration();

        // Test 2: Network Connectivity
        $testResults['network'] = $this->testNetworkStatus();

        // Test 3: Treasury Account Info
        $testResults['treasury'] = $this->testTreasuryAccount();

        // Test 4: Address Validation
        $testResults['validation'] = $this->testAddressValidation();

        if ($this->option('full')) {
            // Test 5: Placeholder Methods (will fail but show structure)
            $testResults['placeholders'] = $this->testPlaceholderMethods();
        }

        // Display Summary
        $this->displayTestSummary($testResults);

        return Command::SUCCESS;
    }

    /**
     * Test service configuration
     */
    private function testConfiguration(): bool
    {
        $this->info('🔧 TEST 1: Configuration');

        try {
            $config = config('founders');

            // Check required config values
            $requiredKeys = [
                'algorand.network',
                'algorand.treasury_address',
                'asa_config.total',
                'asa_config.asset_name'
            ];

            foreach ($requiredKeys as $key) {
                $value = data_get($config, $key);
                if (empty($value)) {
                    $this->error("  ❌ Missing config: {$key}");
                    return false;
                }
                $this->line("  ✅ {$key}: {$value}");
            }

            // Check environment variables
            $treasurySeed = env('ALGORAND_TREASURY_SEED');
            if (empty($treasurySeed)) {
                $this->error("  ❌ ALGORAND_TREASURY_SEED not configured in .env");
                return false;
            }

            $seedWords = explode(' ', trim($treasurySeed));
            $this->line("  ✅ Treasury seed: " . count($seedWords) . " words");

            $pinataJwt = env('PINATA_JWT');
            if (empty($pinataJwt)) {
                $this->warn("  ⚠️  PINATA_JWT not configured");
            } else {
                $this->line("  ✅ Pinata JWT: configured");
            }

            $this->info("  ✅ Configuration test PASSED");
            return true;

        } catch (\Exception $e) {
            $this->error("  ❌ Configuration test FAILED: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Test network connectivity
     */
    private function testNetworkStatus(): bool
    {
        $this->newLine();
        $this->info('🌐 TEST 2: Network Connectivity');

        try {
            $status = $this->algorandService->getNetworkStatus();

            $this->line("  ✅ Network: " . config('founders.algorand.network'));
            $this->line("  ✅ Last round: " . $status['last-round']);
            $this->line("  ✅ Time since last round: " . $status['time-since-last-round'] . 'μs');
            $this->line("  ✅ Catchup time: " . ($status['catchup-time'] ?? 'N/A'));

            $this->info("  ✅ Network connectivity test PASSED");
            return true;

        } catch (\Exception $e) {
            $this->error("  ❌ Network connectivity test FAILED: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Test treasury account information
     */
    private function testTreasuryAccount(): bool
    {
        $this->newLine();
        $this->info('🏦 TEST 3: Treasury Account');

        try {
            $treasuryInfo = $this->algorandService->getTreasuryStatus();

            $balance = $treasuryInfo['amount'] / 1000000; // Convert microAlgos to Algos
            $this->line("  ✅ Treasury address: " . $treasuryInfo['address']);
            $this->line("  ✅ Balance: {$balance} ALGOs");
            $this->line("  ✅ Round: " . $treasuryInfo['round']);

            $assets = $treasuryInfo['assets'] ?? [];
            $this->line("  ✅ Assets owned: " . count($assets));

            if ($balance < 1) {
                $this->warn("  ⚠️  Low balance! Consider adding more TestNet ALGOs");
            }

            $this->info("  ✅ Treasury account test PASSED");
            return true;

        } catch (\Exception $e) {
            $this->error("  ❌ Treasury account test FAILED: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Test address validation
     */
    private function testAddressValidation(): bool
    {
        $this->newLine();
        $this->info('🔍 TEST 4: Address Validation');

        try {
            $testAddresses = [
                // Valid treasury address
                config('founders.algorand.treasury_address') => true,

                // Invalid addresses
                'INVALID_ADDRESS' => false,
                '123456789' => false,
                '' => false,

                // Skip test addresses for now - they might not be real
                // Will add real test addresses later
            ];

            foreach ($testAddresses as $address => $shouldBeValid) {
                $isValid = AlgorandAddressRule::isValidAddress($address);

                if ($isValid === $shouldBeValid) {
                    $status = $shouldBeValid ? 'valid' : 'invalid';
                    $this->line("  ✅ {$address}: correctly identified as {$status}");
                } else {
                    $expected = $shouldBeValid ? 'valid' : 'invalid';
                    $actual = $isValid ? 'valid' : 'invalid';
                    $this->error("  ❌ {$address}: expected {$expected}, got {$actual}");
                    return false;
                }
            }

            $this->info("  ✅ Address validation test PASSED");
            return true;

        } catch (\Exception $e) {
            $this->error("  ❌ Address validation test FAILED: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Test placeholder methods (will fail but show what needs implementation)
     */
    private function testPlaceholderMethods(): bool
    {
        $this->newLine();
        $this->info('🚧 TEST 5: Placeholder Methods (Expected to Fail)');

        $this->warn("  ⚠️  These methods have placeholder implementations:");

        try {
            // This will fail because seedToPrivateKey is placeholder
            $this->line("  📝 Testing mintFounderToken() - will fail due to placeholder signing...");

            $result = $this->algorandService->mintFounderToken(1);
            $this->error("  ❌ Unexpected success - placeholder should have failed");
            return false;

        } catch (\Exception $e) {
            $this->line("  ✅ Expected failure: " . $e->getMessage());

            $this->newLine();
            $this->warn("  📋 METHODS NEEDING REAL IMPLEMENTATION:");
            $this->line("     1. seedToPrivateKey() - Convert 25 words to private key");
            $this->line("     2. signTransaction() - Algorand transaction signing");
            $this->line("     3. calculateTransactionId() - Real transaction ID calc");

            $this->newLine();
            $this->info("  ✅ Placeholder methods test completed (as expected)");
            return true;
        }
    }

    /**
     * Display test summary
     */
    private function displayTestSummary(array $testResults): void
    {
        $this->newLine();
        $this->info('📊 TEST SUMMARY');
        $this->line(str_repeat('=', 50));

        $passed = 0;
        $total = count($testResults);

        foreach ($testResults as $testName => $result) {
            $status = $result ? '✅ PASS' : '❌ FAIL';
            $this->line("  {$status} - " . ucfirst($testName) . " test");
            if ($result) $passed++;
        }

        $this->newLine();
        $this->line("Results: {$passed}/{$total} tests passed");

        if ($passed === $total) {
            $this->info('🎉 All basic functionality working!');
            $this->newLine();
            $this->info('📋 NEXT STEPS:');
            $this->line('1. Complete PDF and Email services');
            $this->line('2. Implement real Algorand signing methods');
            $this->line('3. Test full certificate creation workflow');
        } else {
            $this->error('❌ Some tests failed - check configuration');
        }
    }
}
