<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Club;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin FFA',
            'email' => 'admin@ffa.com',
            'password' => Hash::make('password123'),
            'role' => 'Super Admin',
        ]);

        Club::create([
            'name' => 'Future Football Academy',
            'logo_path' => null,
        ]);
    }
}