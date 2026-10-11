
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Maak de tabel voor gekochte tickets.
     */
    public function up(): void
    {
        Schema::create('bestelde_tickets', function (Blueprint $table) {
            $table->id();

            // Koppeling met de bestelling.
            $table->foreignId('bestelling_id');

            // Koppeling met het gekozen tijdslot.
            // tickets.id is een gewone INT.
            $table->integer('ticket_id');

            // Elk gekocht ticket krijgt een uniek nummer.
            $table->string('ticketnummer', 50)->unique();

            // Datum waarop het ticket is aangemaakt.
            $table->timestamps();

            // Relatie met de bestellingen-tabel.
            $table->foreign('bestelling_id')
                ->references('id')
                ->on('bestellingen')
                ->restrictOnDelete();

            // Relatie met de bestaande tickets-tabel.
            $table->foreign('ticket_id')
                ->references('id')
                ->on('tickets')
                ->restrictOnDelete();
        });
    }

    /**
     * Verwijder de tabel bij het terugdraaien.
     */
    public function down(): void
    {
        Schema::dropIfExists('bestelde_tickets');
    }
};
