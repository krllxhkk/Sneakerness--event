<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Maak de procedure voor het verwijderen van een beschikbare stand.
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE DeleteStand(
                IN p_Id BIGINT
            )
            BEGIN
                DELETE s
                FROM Stand AS s
                LEFT JOIN Verkoper AS v ON s.VerkoperId = v.Id
                WHERE s.Id = p_Id
                    AND s.VerhuurdStatus = 0
                    AND s.VerkoperId IS NULL;

                IF ROW_COUNT() = 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Stand kon niet worden verwijderd';
                END IF;
            END
        ");
    }

    // Verwijder de procedure bij het terugdraaien van deze migratie.
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS DeleteStand');
    }
};