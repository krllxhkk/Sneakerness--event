<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Maak de procedure voor het wijzigen van evenementgegevens.
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE UpdateEvent(
                IN p_Id BIGINT,
                IN p_Naam VARCHAR(100),
                IN p_Datum DATE,
                IN p_Tijd TIME,
                IN p_Locatie VARCHAR(150)
            )
            BEGIN
                IF EXISTS (
                    SELECT 1
                    FROM Evenement
                    WHERE Datum = p_Datum
                        AND Tijd = p_Tijd
                        AND Id <> p_Id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'EVENT_DATUM_TIJD_BEZET';
                END IF;

                UPDATE Evenement AS huidig
                LEFT JOIN Evenement AS ander
                    ON ander.Datum = p_Datum
                    AND ander.Tijd = p_Tijd
                    AND ander.Id <> huidig.Id
                SET
                    huidig.Naam = p_Naam,
                    huidig.Datum = p_Datum,
                    huidig.Tijd = p_Tijd,
                    huidig.Locatie = p_Locatie,
                    huidig.Datumgewijzigd = NOW()
                WHERE huidig.Id = p_Id
                    AND ander.Id IS NULL;
            END
        ");
    }

    // Verwijder de procedure bij het terugdraaien van de migratie.
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS UpdateEvent');
    }
};