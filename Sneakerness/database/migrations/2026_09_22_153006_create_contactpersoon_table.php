<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Maak de Contactpersoon tabel
    public function up(): void
    {
        Schema::create('Contactpersoon', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('Id')->primary();
            $table->string('Naam', 100);
            $table->string('Telefoonnummer', 20);
            $table->string('E-mailadres', 150);
            $table->boolean('Isactief');
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('Datumaangemaakt');
            $table->dateTime('Datumgewijzigd');
        });
    }

    // Verwijder de Contactpersoon tabel
    public function down(): void
    {
        Schema::dropIfExists('Contactpersoon');
    }
};