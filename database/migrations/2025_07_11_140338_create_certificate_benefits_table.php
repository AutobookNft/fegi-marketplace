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
        Schema::create('certificate_benefits', function (Blueprint $table) {
            $table->id();

            // Basic benefit information
            $table->string('title')->comment('Titolo del beneficio');
            $table->text('description')->comment('Descrizione dettagliata del beneficio');
            $table->string('category')->comment('Categoria del beneficio (es: physical, digital, vip, utility)');
            $table->string('icon')->nullable()->comment('Icona per la UI (es: gift, star, crown, zap)');
            $table->string('color', 7)->default('#10B981')->comment('Colore hex per la UI');

            // Benefit configuration
            $table->json('metadata')->nullable()->comment('Configurazione aggiuntiva specifica per tipo');
            $table->boolean('is_active')->default(true)->comment('Se il beneficio è attivo');
            $table->integer('sort_order')->default(0)->comment('Ordine di visualizzazione');

            // Validity and limitations
            $table->date('valid_from')->nullable()->comment('Data inizio validità');
            $table->date('valid_until')->nullable()->comment('Data fine validità');
            $table->integer('max_uses')->nullable()->comment('Numero massimo utilizzi (null = illimitato)');
            $table->integer('current_uses')->default(0)->comment('Utilizzi attuali');

            // Management
            $table->string('created_by')->nullable()->comment('Chi ha creato il beneficio');
            $table->timestamps();

            // Indexes
            $table->index(['category', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_benefits');
    }
};
