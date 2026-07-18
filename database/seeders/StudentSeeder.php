<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $studentUser = User::where(
            'email',
            'student@attendance.com'
        )->firstOrFail();

        Student::updateOrCreate(
            ['user_id' => $studentUser->id],
            [
                'university_number' => '20260001',
                'phone' => '0912345678',
                'address' => 'Damascus',
                'is_dormitory' => false,
                'academic_year' => 4,
                'device_token' => null,
            ]
        );
    }
}