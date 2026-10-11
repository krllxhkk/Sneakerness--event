
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Koppel bestellingen aan ingelogde gebruikers.
     * Gasten mogen zonder account blijven bestellen.
     */
    public function up(): void
    {
        Schema::table('bestellingen', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Verwijder de gebruikerskoppeling bij rollback.
     */
    public function down(): void
    {
        Schema::table('bestellingen', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
