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
            // Rendo nullable i campi dell'investitore
            $table->string('investor_name', 200)->nullable()->change();
            $table->string('investor_email', 200)->nullable()->change();
            $table->text('investor_address')->nullable()->change();
            $table->string('investor_phone', 50)->nullable()->change();
            $table->string('investor_wallet', 58)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('founder_certificates', function (Blueprint $table) {
            // Ripristino i campi come non nullable
            $table->string('investor_name', 200)->nullable(false)->change();
            $table->string('investor_email', 200)->nullable(false)->change();
            $table->text('investor_address')->nullable(false)->change();
            $table->string('investor_phone', 50)->nullable(false)->change();
            $table->string('investor_wallet', 58)->nullable(false)->change();
        });
    }
};
