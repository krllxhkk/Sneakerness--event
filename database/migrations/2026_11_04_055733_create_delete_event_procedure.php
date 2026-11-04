<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Maak de procedure voor het verwijderen van een evenement.
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE DeleteEvent(
                IN p_Id BIGINT,
                IN p_Vandaag DATE
            )
            BEGIN
                IF EXISTS (
                    SELECT 1
                    FROM Evenement
                    WHERE Id = p_Id
                        AND Datum = p_Vandaag
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'EVENT_VANDAAG';
                END IF;

                DELETE doel
                FROM Evenement AS doel
                LEFT JOIN Evenement AS beschermd
                    ON beschermd.Id = doel.Id
                    AND beschermd.Datum = p_Vandaag
                WHERE doel.Id = p_Id
                    AND beschermd.Id IS NULL;

                IF ROW_COUNT() = 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'EVENT_NIET_GEVONDEN';
                END IF;
            END
        ");
    }

    // Verwijder de procedure bij het terugdraaien van deze migratie.
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS DeleteEvent');
    }
};