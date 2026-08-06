<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $asmaaUser = User::query()
            ->where(
                'email',
                'asmaadalil@student.com'
            )
            ->first();

        $noorUser = User::query()
            ->where(
                'email',
                'nooralbakri@student.com'
            )
            ->first();

        if (! $asmaaUser || ! $noorUser) {
            throw new RuntimeException(
                'Student accounts were not created. '
                .'Run UserSeeder before StudentSeeder.'
            );
        }

        Student::create([
            'user_id' => $asmaaUser->id,
            'university_number' => '20231001',
            'academic_year' => 3,
            'is_dormitory' => false,
            'device_token' => null,
        ]);

        Student::create([
            'user_id' => $noorUser->id,
            'university_number' => '20231002',
            'academic_year' => 3,
            'is_dormitory' => false,
            'device_token' => null,
        ]);
    }
}