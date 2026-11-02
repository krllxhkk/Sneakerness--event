<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Maak de procedure waarmee een bestaande stand wordt gewijzigd.
    public function up(): void
    {
        DB::unprepared('
            CREATE PROCEDURE UpdateStand(
                IN p_Id BIGINT,
                IN p_Standnaam VARCHAR(100),
                IN p_Locatie VARCHAR(100),
                IN p_Prijs DECIMAL(8, 2),
                IN p_Kwaliteitsklasse VARCHAR(30),
                IN p_VerhuurdStatus TINYINT,
                IN p_VerkoperId BIGINT
            )
            BEGIN
                UPDATE Stand
                SET
                    Standnaam = p_Standnaam,
                    Locatie = p_Locatie,
                    Prijs = p_Prijs,
                    Kwaliteitsklasse = p_Kwaliteitsklasse,
                    VerhuurdStatus = p_VerhuurdStatus,
                    VerkoperId = p_VerkoperId,
                    Datumgewijzigd = NOW()
                WHERE Id = p_Id;
            END
        ');
    }

    // Verwijder de procedure wanneer deze migratie wordt teruggedraaid.
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS UpdateStand');
    }
};