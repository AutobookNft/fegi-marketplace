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
        Schema::create('collection_certificate_benefit', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->foreignId('certificate_benefit_id')->constrained()->onDelete('cascade');

            // Pivot specific data
            $table->json('custom_configuration')->nullable()->comment('Configurazione specifica per questa collection');
            $table->boolean('is_highlighted')->default(false)->comment('Se evidenziare questo beneficio per questa collection');
            $table->integer('sort_order')->default(0)->comment('Ordine specifico per questa collection');

            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['collection_id', 'certificate_benefit_id'], 'collection_benefit_unique');

            // Indexes
            $table->index(['collection_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_certificate_benefit');
    }
};
