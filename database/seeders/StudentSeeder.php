<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Student::create([
        'name' => 'Test Student',
        'email' => 'student@test.com',
        'password' => bcrypt('12345678'),
        'phone' => '0912345678',
        'address' => 'Damascus',
        'is_dormitory' => 0,
        'academic_year' => 4,
    ]);
    }
}
