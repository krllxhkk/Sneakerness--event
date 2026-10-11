<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Voeg verkoper toe aan de bestaande gebruikersrollen
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN rol
            ENUM('organisator', 'bezoeker', 'verkoper')
            NOT NULL DEFAULT 'bezoeker'
        ");
    }

    public function down(): void
    {
        // Alleen terugdraaien als er geen verkopersaccounts bestaan
        if (DB::table('users')->where('rol', 'verkoper')->exists()) {
            throw new \RuntimeException(
                'Kan rol verkoper niet verwijderen zolang verkopersaccounts bestaan.'
            );
        }

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN rol
            ENUM('organisator', 'bezoeker')
            NOT NULL DEFAULT 'bezoeker'
        ");
    }
};