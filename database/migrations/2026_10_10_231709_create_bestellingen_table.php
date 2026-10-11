
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Maak de tabel voor ticketbestellingen.
     */
    public function up(): void
    {
        Schema::create('bestellingen', function (Blueprint $table) {
            $table->id();

            // Gekozen tijdslot uit de bestaande tickets-tabel.
            $table->integer('ticket_id');

            
            // Gegevens van de bezoeker.
            $table->string('naam', 100);
            $table->string('email', 255);

            // Bestelgegevens.
            $table->unsignedTinyInteger('aantal');
            $table->decimal('prijs_per_ticket', 8, 2);
            $table->decimal('totaalprijs', 10, 2);
            $table->enum('betaalmethode', ['ideal', 'contant']);

            // Status van de bestelling.
            $table->string('status', 30)->default('bevestigd');

            // Datum en tijd van de bestelling.
            $table->timestamps();

            // Koppeling met de bestaande tickets-tabel.
            $table->foreign('ticket_id')
                ->references('id')
                ->on('tickets')
                ->restrictOnDelete();
        });
    }

    /**
     * Verwijder de tabel bij het terugdraaien van de migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('bestellingen');
    }
};
