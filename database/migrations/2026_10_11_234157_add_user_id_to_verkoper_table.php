<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verkoper', function (Blueprint $table) {
            $table->foreignId('UserId')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('verkoper', function (Blueprint $table) {
            $table->dropConstrainedForeignId('UserId');
        });
    }
};
