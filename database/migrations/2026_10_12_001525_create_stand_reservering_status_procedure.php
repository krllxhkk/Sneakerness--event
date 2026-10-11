
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Stored procedure om de standstatus op te halen
        DB::unprepared('
            CREATE PROCEDURE sp_stand_reservering_status(
                IN p_stand_id INT
            )
            BEGIN
                SELECT VerhuurdStatus AS status
                FROM stand
                WHERE Id = p_stand_id;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared(
            'DROP PROCEDURE IF EXISTS sp_stand_reservering_status'
        );
    }
};
