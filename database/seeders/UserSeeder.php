<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un admin
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@hotelazure.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telephone' => '+228 90 00 00 00',
        ]);

        // Créer quelques clients
        User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'telephone' => '+228 91 11 11 11',
        ]);

        User::create([
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'telephone' => '+228 92 22 22 22',
        ]);
    }
}