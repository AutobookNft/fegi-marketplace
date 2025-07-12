<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();

            // Informazioni base della collection
            $table->string('name'); // Nome della collection/evento
            $table->text('description')->nullable(); // Descrizione dell'evento
            $table->string('slug')->unique(); // URL-friendly slug

            // Configurazione tokens
            $table->integer('total_tokens'); // Quantità totale di certificati
            $table->integer('available_tokens'); // Certificati ancora disponibili
            $table->decimal('base_price', 10, 2); // Prezzo base in EUR
            $table->string('currency', 3)->default('EUR'); // Valuta

            // Metadata NFT
            $table->json('metadata')->nullable(); // Metadata per i token NFT

            // Configurazione Algorand
            $table->string('asset_id')->nullable(); // ASA ID quando creato
            $table->string('treasury_address')->nullable(); // Wallet treasury per questa collection

            // Stato della collection
            $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled'])->default('draft');

            // Date evento
            $table->datetime('event_date')->nullable(); // Data dell'evento FoundRising
            $table->datetime('sale_start_date')->nullable(); // Inizio vendite
            $table->datetime('sale_end_date')->nullable(); // Fine vendite

            // Statistiche
            $table->integer('certificates_issued')->default(0); // Certificati emessi
            $table->decimal('total_revenue', 12, 2)->default(0); // Revenue totale

            // Impostazioni avanzate
            $table->boolean('allow_wallet_payments')->default(true); // Permetti pagamenti wallet
            $table->boolean('allow_fiat_payments')->default(true); // Permetti pagamenti FIAT
            $table->decimal('min_symbolic_price', 8, 6)->default(0.001); // Prezzo simbolico Algo

            // Gestione spedizioni
            $table->boolean('requires_shipping')->default(true); // Richiede spedizione prismi
            $table->json('shipping_info')->nullable(); // Info spedizione

            $table->timestamps();

            // Indici
            $table->index('status');
            $table->index('event_date');
            $table->index('sale_start_date');
            $table->index('sale_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
