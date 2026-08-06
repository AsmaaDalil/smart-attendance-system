<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Professor',
            'email' => 'professor@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'role' => 'professor',
        ]);

        User::create([
            'name' => 'Asmaa Dalil',
            'email' => 'asmaadalil@student.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Noor Al-Bakri',
            'email' => 'nooralbakri@student.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'role' => 'student',
        ]);
    }
}