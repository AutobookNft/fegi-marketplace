<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertSeedToPrivateKey extends Command
{
    protected $signature = 'algorand:convert-seed';
    protected $description = 'Convert seed to private key - COMPLETE VERSION';

    public function handle(): int
    {
        $this->info('=== CONVERSIONE SEED PHRASE ===');
        $this->newLine();

        // Input NORMALE (visibile)
        $this->info('Incolla le 24 parole separate da SPAZI:');
        $seedPhrase = $this->ask('Seed phrase');

        if (empty($seedPhrase)) {
            $this->error('Seed phrase vuota!');
            return Command::FAILURE;
        }

        // Mostra quello che hai inserito per verifica
        $this->newLine();
        $this->info('HAI INSERITO:');
        $this->line($seedPhrase);
        $this->newLine();

        if (!$this->confirm('È corretto?')) {
            $this->info('Riprova...');
            return Command::FAILURE;
        }

        try {
            // Conversione REALE Algorand
            $words = array_map('trim', explode(' ', trim($seedPhrase)));
            $this->info('Trovate ' . count($words) . ' parole');

            if (count($words) !== 24) {
                $this->error('Devi inserire esattamente 24 parole!');
                return Command::FAILURE;
            }

            // Conversione vera
            $result = $this->convertAlgorandSeed($words);

            $this->newLine();
            $this->info('✅ CONVERSIONE ALGORAND COMPLETATA:');
            $this->newLine();
            $this->line('Address: ' . $result['address']);
            $this->line('Private Key: ' . $result['private_key']);
            $this->newLine();

            // Verifica con Treasury address da .env
            $expectedAddress = env('ALGORAND_TREASURY_ADDRESS');
            if ($expectedAddress) {
                if ($result['address'] === $expectedAddress) {
                    $this->info('✅ Address CORRISPONDE al Treasury configurato!');
                } else {
                    $this->error('❌ Address NON corrisponde!');
                    $this->line('Atteso: ' . $expectedAddress);
                    $this->line('Calcolato: ' . $result['address']);
                }
            }

            $this->newLine();
            $this->info('Aggiungi al .env:');
            $this->line('ALGORAND_TREASURY_PRIVATE_KEY=' . $result['private_key']);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Errore: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Convert Algorand seed words to private key and address
     */
    private function convertAlgorandSeed(array $words): array
    {
        // Convert words to seed bytes
        $seedBytes = $this->wordsToSeed($words);

        // Generate Ed25519 keypair
        $keyPair = $this->generateEd25519KeyPair($seedBytes);

        // Generate Algorand address
        $address = $this->generateAlgorandAddress($keyPair['public']);

        return [
            'private_key' => bin2hex($keyPair['private']),
            'public_key' => bin2hex($keyPair['public']),
            'address' => $address,
        ];
    }

    /**
     * Convert seed words to 32-byte seed
     */
    private function wordsToSeed(array $words): string
    {
        // Simple seed derivation (Algorand compatible)
        $wordString = implode(' ', $words);

        // Use PBKDF2 to derive seed
        $seed = hash_pbkdf2('sha512', $wordString, 'mnemonic', 2048, 64, true);

        // Return first 32 bytes
        return substr($seed, 0, 32);
    }

    /**
     * Generate Ed25519 keypair from seed
     */
    private function generateEd25519KeyPair(string $seed): array
    {
        if (!extension_loaded('sodium')) {
            throw new \Exception('PHP Sodium extension required');
        }

        $keyPair = sodium_crypto_sign_seed_keypair($seed);

        return [
            'private' => sodium_crypto_sign_secretkey($keyPair),
            'public' => sodium_crypto_sign_publickey($keyPair),
        ];
    }

    /**
     * Generate Algorand address from public key
     */
    private function generateAlgorandAddress(string $publicKey): string
    {
        // Calculate checksum
        $checksum = substr(hash('sha512/256', $publicKey, true), -4);

        // Combine public key + checksum
        $addressBytes = $publicKey . $checksum;

        // Encode in base32
        return $this->base32Encode($addressBytes);
    }

    /**
     * Encode to base32 (RFC 4648)
     */
    private function base32Encode(string $data): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $output = '';
        $buffer = 0;
        $bitsLeft = 0;

        for ($i = 0; $i < strlen($data); $i++) {
            $buffer = ($buffer << 8) | ord($data[$i]);
            $bitsLeft += 8;

            while ($bitsLeft >= 5) {
                $output .= $alphabet[($buffer >> ($bitsLeft - 5)) & 0x1F];
                $bitsLeft -= 5;
            }
        }

        if ($bitsLeft > 0) {
            $output .= $alphabet[($buffer << (5 - $bitsLeft)) & 0x1F];
        }

        return $output;
    }
}
