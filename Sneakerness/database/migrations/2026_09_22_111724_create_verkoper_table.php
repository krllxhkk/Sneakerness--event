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
    Schema::create('Verkoper', function (Blueprint $table) {
        $table->engine = 'InnoDB';

        $table->integer('Id')->primary();
        $table->string('Naam', 100);
        $table->string('SpecialeStatus', 50)->nullable();
        $table->string('VerkooptSoort', 100);
        $table->string('StandType', 10);
        $table->string('Dagen', 50);
        $table->string('Logo', 255)->nullable();
        $table->boolean('Isactief');
        $table->string('Opmerking', 250)->nullable();
        $table->dateTime('Datumaangemaakt');
        $table->dateTime('Datumgewijzigd');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Verkoper');
    }
};
