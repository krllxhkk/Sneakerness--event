<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Voegt de rol van de gebruiker toe.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['organisator', 'bezoeker'])
                ->default('bezoeker')
                ->after('password');
        });
    }

    /**
     * Verwijdert de rol wanneer de migration wordt teruggedraaid.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }
};