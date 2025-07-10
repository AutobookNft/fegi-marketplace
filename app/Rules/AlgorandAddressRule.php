<?php

/**
 * @Oracode Custom Validation Rule for Algorand Addresses
 * 🎯 Purpose: Validate Algorand wallet addresses format and checksum
 * 🧱 Core Logic: Base32 validation, length check, checksum verification
 * 🛡️ Security: Input sanitization, proper error messaging, format validation
 *
 * @package App\Rules
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori System)
 * @date 2025-07-05
 * @purpose Validate Algorand wallet addresses for certificate token transfers
 */

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlgorandAddressRule implements ValidationRule
{
    /**
     * @Oracode Algorand address validation constants
     * 🎯 Purpose: Define validation parameters for Algorand addresses
     */
    private const ALGORAND_ADDRESS_LENGTH = 58;
    private const BASE32_PATTERN = '/^[A-Z2-7]+$/';
    private const CHECKSUM_LENGTH = 4;

    /**
     * @Oracode Run the validation rule
     * 🎯 Purpose: Validate that input is a properly formatted Algorand address
     *
     * @param string $attribute The name of the attribute being validated
     * @param mixed $value The value of the attribute being validated
     * @param Closure $fail Closure to call when validation fails
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Early return for null/empty values (handled by 'nullable' rule)
        if (empty($value)) {
            return;
        }

        // Convert to string for validation
        $address = (string) $value;

        // Step 1: Check exact length requirement
        if (strlen($address) !== self::ALGORAND_ADDRESS_LENGTH) {
            $fail($this->getLengthErrorMessage($attribute, strlen($address)));
            return;
        }

        // Step 2: Check for valid base32 characters only
        if (!preg_match(self::BASE32_PATTERN, $address)) {
            $fail($this->getFormatErrorMessage($attribute));
            return;
        }

        // Step 3: Validate Algorand address checksum
        if (!$this->validateAlgorandChecksum($address)) {
            $fail($this->getChecksumErrorMessage($attribute));
            return;
        }

        // Address is valid - no action needed
    }

    /**
     * @Oracode Validate Algorand address checksum
     * 🎯 Purpose: Verify the last 4 bytes match the SHA512/256 checksum
     *
     * @param string $address The Algorand address to validate
     * @return bool True if checksum is valid, false otherwise
     */
    private function validateAlgorandChecksum(string $address): bool
    {
        try {
            // Decode the base32 address
            $decoded = $this->base32Decode($address);

            if ($decoded === false || strlen($decoded) !== 36) {
                return false;
            }

            // Extract public key (first 32 bytes) and checksum (last 4 bytes)
            $publicKey = substr($decoded, 0, 32);
            $checksum = substr($decoded, 32, 4);

            // Calculate expected checksum using SHA512/256
            $expectedChecksum = substr(hash('sha512/256', $publicKey, true), -4);

            // Compare checksums
            return hash_equals($checksum, $expectedChecksum);

        } catch (\Exception $e) {
            // If any error occurs during validation, consider invalid
            return false;
        }
    }

    /**
     * @Oracode Decode base32 string to binary
     * 🎯 Purpose: Convert Algorand base32 address to binary for checksum validation
     *
     * @param string $input Base32 encoded string
     * @return string|false Binary data or false on failure
     */
    private function base32Decode(string $input): string|false
    {
        // Algorand uses RFC 4648 Base32 alphabet
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $alphabetFlipped = array_flip(str_split($alphabet));

        $input = strtoupper($input);
        $output = '';
        $buffer = 0;
        $bitsLeft = 0;

        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];

            if (!isset($alphabetFlipped[$char])) {
                return false;
            }

            $buffer = ($buffer << 5) | $alphabetFlipped[$char];
            $bitsLeft += 5;

            if ($bitsLeft >= 8) {
                $output .= chr(($buffer >> ($bitsLeft - 8)) & 0xFF);
                $bitsLeft -= 8;
            }
        }

        return $output;
    }

    /**
     * @Oracode Generate length validation error message
     * 🎯 Purpose: Provide clear error message for length validation failures
     *
     * @param string $attribute The attribute name
     * @param int $actualLength The actual length provided
     * @return string Formatted error message
     */
    private function getLengthErrorMessage(string $attribute, int $actualLength): string
    {
        return sprintf(
            'Il campo %s deve essere un indirizzo Algorand valido di %d caratteri. Lunghezza fornita: %d caratteri.',
            $attribute,
            self::ALGORAND_ADDRESS_LENGTH,
            $actualLength
        );
    }

    /**
     * @Oracode Generate format validation error message
     * 🎯 Purpose: Provide clear error message for format validation failures
     *
     * @param string $attribute The attribute name
     * @return string Formatted error message
     */
    private function getFormatErrorMessage(string $attribute): string
    {
        return sprintf(
            'Il campo %s deve contenere solo caratteri validi per un indirizzo Algorand (A-Z, 2-7).',
            $attribute
        );
    }

    /**
     * @Oracode Generate checksum validation error message
     * 🎯 Purpose: Provide clear error message for checksum validation failures
     *
     * @param string $attribute The attribute name
     * @return string Formatted error message
     */
    private function getChecksumErrorMessage(string $attribute): string
    {
        return sprintf(
            'Il campo %s non è un indirizzo Algorand valido. Verificare che sia stato copiato correttamente.',
            $attribute
        );
    }

    /**
     * @Oracode Static helper to validate address format quickly
     * 🎯 Purpose: Provide quick validation without instantiating the rule
     *
     * @param string|null $address The address to validate
     * @return bool True if address is valid, false otherwise
     */
    public static function isValidAddress(?string $address): bool
    {
        if (empty($address)) {
            return false;
        }

        $rule = new self();
        $isValid = true;

        $rule->validate('address', $address, function() use (&$isValid) {
            $isValid = false;
        });

        return $isValid;
    }

    /**
     * @Oracode Get sample valid Algorand addresses for testing
     * 🎯 Purpose: Provide test addresses for development and testing
     *
     * @return array Array of valid Algorand addresses for testing
     */
    public static function getTestAddresses(): array
    {
        return [
            'testnet' => [
                // These are example TestNet addresses - replace with real ones for testing
                'MFRGG424BAEBPONPUBXJZHPGQEZAASQ5FHXTQP5XOGOFO5YGSCJHK2HZMA',
                'XRQIQKNHQY6VUPXNX2VRDGR5CYZXMBWCUQWVXSRTFYDPZQ7AMKP4FOFWY',
            ],
            'mainnet' => [
                // These are example MainNet addresses - replace with real ones for testing
                'TMHQT6EQGUBRSNJ4VV7EPKYDGQCJVQSAFJ6XX5WEJQZQ7YBKCSQOWDGDWY',
                'UKRZKQEWPCRG6YBMWZXFGFPZHZ3QRAXS5QZGNMN7VPLHPTPRMN2OUWCKWY',
            ],
        ];
    }
}
