<?php

/**
 * @Oracode Migration for Founder Certificates Table
 * 🎯 Purpose: Create table for FlorenceEGI Padri Fondatori certificates and artifact tracking
 * 🧱 Core Logic: ASA token data, investor information, artifact lifecycle, GDPR compliance
 * 🛡️ Security: Data integrity, indexed fields, proper constraints
 *
 * @package Database\Migrations
 * @author Padmin D. Curtis (AI Partner OS3.0) for Fabio Cherici
 * @version 1.0.0 (FlorenceEGI - Padri Fondatori System)
 * @date 2025-07-05
 * @purpose Create founder_certificates table with complete lifecycle tracking
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @Oracode Create founder_certificates table
     * 🎯 Purpose: Define complete data structure for Padri Fondatori workflow
     */
    public function up(): void
    {
        Schema::create('founder_certificates', function (Blueprint $table) {
            // Primary identification
            $table->id();

            // Certificate sequence and blockchain data
            $table->unsignedTinyInteger('index')->unique()->comment('Certificate number (1-40)');
            $table->string('asa_id', 20)->unique()->comment('Algorand ASA ID');
            $table->string('tx_id', 60)->unique()->comment('Algorand transaction ID');

            // Investor personal data (GDPR sensitive)
            $table->string('investor_name', 200)->comment('Full name of investor');
            $table->string('investor_email', 200)->comment('Email for certificate delivery');
            $table->text('investor_address')->nullable()->comment('Shipping address for artifact');
            $table->string('investor_phone', 50)->nullable()->comment('Phone for shipping notifications');
            $table->string('investor_wallet', 58)->nullable()->comment('Algorand wallet address');

            // Certificate issuance tracking
            $table->timestamp('issued_at')->comment('Certificate emission timestamp');
            $table->string('pdf_path', 255)->comment('Path to generated PDF certificate');

            // artifact lifecycle management
            $table->boolean('artifact_ordered')->default(false)->comment('Artifact ordered from supplier');
            $table->timestamp('artifact_ordered_at')->nullable()->comment('When artifact was ordered');
            $table->boolean('artifact_paid')->default(false)->comment('Supplier payment completed');
            $table->timestamp('artifact_paid_at')->nullable()->comment('When supplier was paid');
            $table->boolean('artifact_received')->default(false)->comment('Artifact received by Fabio');
            $table->timestamp('artifact_received_at')->nullable()->comment('When artifact arrived to Fabio');
            $table->boolean('artifact_shipped')->default(false)->comment('Artifact shipped to investor');
            $table->timestamp('artifact_shipped_at')->nullable()->comment('When artifact was shipped to investor');
            $table->string('tracking_code', 100)->nullable()->comment('Shipping tracking number');

            // Token transfer management
            $table->boolean('token_transferred')->default(false)->comment('Token transferred to investor wallet');
            $table->timestamp('token_transferred_at')->nullable()->comment('When token was transferred');
            $table->string('transfer_tx_id', 60)->nullable()->comment('Transfer transaction ID');

            // Standard Laravel timestamps
            $table->timestamps();

            // Database indexes for performance
            $table->index(['artifact_ordered', 'artifact_received', 'artifact_shipped'], 'artifact_lifecycle_idx');
            $table->index('issued_at');
            $table->index('investor_email');
            $table->index('token_transferred');
        });
    }

    /**
     * @Oracode Drop founder_certificates table
     * 🎯 Purpose: Clean database rollback for development iterations
     */
    public function down(): void
    {
        Schema::dropIfExists('founder_certificates');
    }
};
