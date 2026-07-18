<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@attendance.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'professor@attendance.com'],
            [
                'name' => 'Test Professor',
                'password' => Hash::make('12345678'),
                'role' => 'professor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@attendance.com'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('12345678'),
                'role' => 'student',
            ]
        );
    }
}