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
            // Aggiungo la colonna minted_at per tracciare quando il certificato è stato mintato
            $table->timestamp('minted_at')->nullable()->after('issued_at')->comment('Timestamp quando il certificato è stato mintato sulla blockchain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('founder_certificates', function (Blueprint $table) {
            $table->dropColumn('minted_at');
        });
    }
};
