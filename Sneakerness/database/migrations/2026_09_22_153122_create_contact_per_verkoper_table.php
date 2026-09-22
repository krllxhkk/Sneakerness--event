<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Maak de koppeltabel
    public function up(): void
    {
        Schema::create('ContactPerVerkoper', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('Id')->primary();

            // Koppel de verkoper
            $table->integer('VerkoperId');

            // Koppel de contactpersoon
            $table->integer('ContactpersoonId');

            $table->boolean('Isactief');
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('Datumaangemaakt');
            $table->dateTime('Datumgewijzigd');

            // Foreign keys
            $table->foreign('VerkoperId')
                ->references('Id')
                ->on('Verkoper');

            $table->foreign('ContactpersoonId')
                ->references('Id')
                ->on('Contactpersoon');
        });
    }

    // Verwijder de koppeltabel
    public function down(): void
    {
        Schema::dropIfExists('ContactPerVerkoper');
    }
};