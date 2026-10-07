<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Maakt de standaard gebruikers voor het log-in systeem aan.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'organisator@sneakerness.nl'],
            [
                'name' => 'Organisator',
                'password' => Hash::make('password'),
                'rol' => 'organisator',
            ]
        );

        User::updateOrCreate(
            ['email' => 'bezoeker@sneakerness.nl'],
            [
                'name' => 'Bezoeker',
                'password' => Hash::make('password'),
                'rol' => 'bezoeker',
            ]
        );
    }
}
