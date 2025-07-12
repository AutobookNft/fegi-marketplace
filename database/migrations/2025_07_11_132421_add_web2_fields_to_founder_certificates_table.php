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
        Schema::table('founder_certificates', function (Blueprint $table) {
            // Campi per la gestione Web2.0
            $table->string('certificate_title')->nullable()->after('collection_id')->comment('Titolo del certificato');
            $table->decimal('base_price', 10, 2)->nullable()->after('certificate_title')->comment('Prezzo base dalla collection');
            $table->string('currency', 3)->default('EUR')->after('base_price')->comment('Valuta');
            $table->enum('status', ['draft', 'ready', 'issued', 'minted', 'completed'])->default('draft')->after('currency')->comment('Stato del certificato');
            $table->json('metadata')->nullable()->after('status')->comment('Metadata del certificato/NFT');

            // Rendo nullable alcuni campi che prima erano obbligatori
            $table->string('asa_id', 20)->nullable()->change();
            $table->string('tx_id', 60)->nullable()->change();
            $table->string('pdf_path', 255)->nullable()->change();
            $table->timestamp('issued_at')->nullable()->change();

            // Rimuovo unique constraint temporaneamente dai campi che possono essere null
            $table->dropUnique(['asa_id']);
            $table->dropUnique(['tx_id']);

            // Aggiungo indici per i nuovi campi
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('founder_certificates', function (Blueprint $table) {
            // Rimuovo i campi aggiunti
            $table->dropColumn([
                'certificate_title',
                'base_price',
                'currency',
                'status',
                'metadata'
            ]);

            // Ripristino i campi come non nullable (se necessario)
            $table->string('asa_id', 20)->nullable(false)->change();
            $table->string('tx_id', 60)->nullable(false)->change();
            $table->string('pdf_path', 255)->nullable(false)->change();
            $table->timestamp('issued_at')->nullable(false)->change();

            // Ripristino unique constraints
            $table->unique('asa_id');
            $table->unique('tx_id');

            $table->dropIndex(['status']);
        });
    }
};
