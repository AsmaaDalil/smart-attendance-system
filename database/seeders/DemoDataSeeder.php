<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Enrollment;
use App\Models\Excuse;
use App\Models\Room;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            // 1) Main login accounts + extra professors.
        $admin = User::create([
    'name' => 'System Administrator',
    'email' => 'admin@gmail.com',
    'email_verified_at' => now(),
    'password' => Hash::make('Asmaa@123'),
    'role' => 'admin',
]);

$professors = collect([
    ['name' => 'Dr. Ahmad Khalil', 'email' => 'professor@gmail.com'],
    ['name' => 'Dr. Lina Hassan', 'email' => 'lina.professor@gmail.com'],
    ['name' => 'Dr. Omar Darwish', 'email' => 'omar.professor@gmail.com'],
])->map(function (array $data): User {
    return User::create([
        ...$data,
        'email_verified_at' => now(),
        'password' => Hash::make('Asmaa@123'),
        'role' => 'professor',
    ]);
});

            // 2) Students. Asmaa keeps the familiar demo login.
            $studentData = [
                ['Asmaa Dalil', 'asmaadalil@student.com', '20231001', '0935123456', 'Idlib', 3, false],
                ['Noor Al-Bakri', 'nooralbakri@student.com', '20231002', '0935234567', 'Idlib', 3, false],
                ['Layan Ahmad', 'layan.ahmad@student.com', '20231003', '0935345678', 'Sarmada', 3, true],
                ['Yazan Mahmoud', 'yazan.mahmoud@student.com', '20231004', '0935456789', 'Idlib', 3, false],
                ['Rama Khaled', 'rama.khaled@student.com', '20231005', '0935567890', 'Binnish', 3, true],
                ['Mohammad Saleh', 'mohammad.saleh@student.com', '20231006', '0935678901', 'Idlib', 3, false],
                ['Hala Ibrahim', 'hala.ibrahim@student.com', '20231007', '0935789012', 'Ariha', 3, false],
                ['Karam Ali', 'karam.ali@student.com', '20231008', '0935890123', 'Idlib', 3, true],
                ['Samar Mustafa', 'samar.mustafa@student.com', '20231009', '0935901234', 'Idlib', 3, false],
                ['Tareq Hamoud', 'tareq.hamoud@student.com', '20231010', '0935012345', 'Sarmada', 3, false],
                ['Maya Al-Hassan', 'maya.hassan@student.com', '20231011', '0935123987', 'Idlib', 3, true],
                ['Omar Al-Sheikh', 'omar.sheikh@student.com', '20231012', '0935234098', 'Binnish', 3, false],
            ];

            $students = collect($studentData)->map(function (array $row, int $index): Student {
                [$name, $email, $number, $phone, $address, $year, $dormitory] = $row;

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => Hash::make('Asmaa@123'),
                    'role' => 'student',
                ]);

                return Student::create([
                    'user_id' => $user->id,
                    'university_number' => $number,
                    'phone' => $phone,
                    'address' => $address,
                    'academic_year' => $year,
                    'is_dormitory' => $dormitory,
                    // Keep tokens null so the real phone can bind on first successful scan.
                    'device_token' => null,
                ]);
            });

            // 3) Rooms. Replace coordinates later with your real campus-room coordinates if needed for live scanning.
            $rooms = collect([
                ['Computer Lab 1', 35.93060000, 36.63390000, 35],
                ['Computer Lab 2', 35.93072000, 36.63405000, 35],
                ['Hall A',          35.93085000, 36.63418000, 45],
                ['Hall B',          35.93048000, 36.63376000, 45],
            ])->map(fn (array $r): Room => Room::create([
                'room_name' => $r[0],
                'latitude' => $r[1],
                'longitude' => $r[2],
                'allowed_radius' => $r[3],
            ]));

            // 4) Subjects distributed across professors.
            $subjectData = [
                ['Algorithms', 'CS301', 0],
                ['Internet Applications', 'CS402', 0],
                ['Advanced Programming', 'CS403', 0],
                ['Database Systems', 'CS305', 1],
                ['Software Engineering', 'CS404', 1],
                ['Project Management', 'CS406', 2],
            ];

            $subjects = collect($subjectData)->map(function (array $row) use ($professors): Subject {
                return Subject::create([
                    'subject_name' => $row[0],
                    'subject_code' => $row[1],
                    'user_id' => $professors[$row[2]]->id,
                ]);
            });

            // 5) Enrollments: enough rows to fill the admin page and reports.
            foreach ($subjects as $subjectIndex => $subject) {
                foreach ($students as $studentIndex => $student) {
                    // Most students take each subject, with a few realistic gaps.
                    if (($studentIndex + $subjectIndex) % 5 !== 4) {
                        Enrollment::create([
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                        ]);
                    }
                }
            }

            // 6) Five ended attendance sessions per subject with varied attendance states.
            $lectureTitles = [
                'Introduction and Course Overview',
                'Core Concepts',
                'Practical Examples',
                'Applied Exercise',
                'Review and Discussion',
            ];

            $absentRecords = collect();

            foreach ($subjects as $subjectIndex => $subject) {
                for ($lecture = 1; $lecture <= 5; $lecture++) {
                    $start = Carbon::now()
                        ->subDays(42 - (($subjectIndex * 5) + $lecture))
                        ->setTime(9 + ($subjectIndex % 3), 0);

                    $session = AttendanceSession::create([
                        'subject_id' => $subject->id,
                        'room_id' => $rooms[($subjectIndex + $lecture) % $rooms->count()]->id,
                        'lecture_number' => $lecture,
                        'lecture_title' => $lectureTitles[$lecture - 1],
                        'qr_current_code' => null,
                        'qr_expires_at' => null,
                        'start_time' => $start,
                        'end_time' => (clone $start)->addMinutes(90),
                        'status' => 'Ended',
                    ]);

                    $enrolledStudents = Student::query()
                        ->whereHas('subjects', fn ($query) => $query->whereKey($subject->id))
                        ->orderBy('id')
                        ->get();

                    foreach ($enrolledStudents as $studentIndex => $student) {
                        $pattern = ($studentIndex + $lecture + $subjectIndex) % 10;

                        if ($pattern <= 5) {
                            $status = 'Present';
                            $scannedAt = (clone $start)->addMinutes(3 + ($studentIndex % 6));
                            $distance = 4.50 + (($studentIndex * 2.37 + $lecture) % 24);
                        } elseif ($pattern <= 7) {
                            $status = 'Late';
                            $scannedAt = (clone $start)->addMinutes(11 + ($studentIndex % 4));
                            $distance = 6.20 + (($studentIndex * 1.91 + $lecture) % 22);
                        } else {
                            $status = 'Absent';
                            $scannedAt = null;
                            $distance = null;
                        }

                        $record = AttendanceRecord::create([
                            'student_id' => $student->id,
                            'session_id' => $session->id,
                            'scanned_at' => $scannedAt,
                            'status' => $status,
                            'distance_meters' => $distance === null ? null : round($distance, 2),
                            'is_dorm_approved' => $student->is_dormitory && $status !== 'Absent' && (($lecture + $studentIndex) % 7 === 0),
                        ]);

                        if ($status === 'Absent') {
                            $absentRecords->push($record);
                        }
                    }
                }
            }

            // 7) Excuses in all three workflow states.
            $excuseReasons = [
                'Medical appointment with supporting documents.',
                'Acute illness prevented attendance on the lecture date.',
                'Family emergency required absence from the lecture.',
                'Transportation issue caused unavoidable absence.',
                'Official university activity conflicted with lecture time.',
                'Medical rest was recommended for the day.',
                'Urgent personal circumstances prevented attendance.',
                'Health condition required a clinic visit.',
                'Approved academic activity outside the classroom.',
            ];

            $excuseStatuses = [
                'Pending', 'Approved', 'Rejected',
                'Approved', 'Pending', 'Rejected',
                'Approved', 'Pending', 'Rejected',
            ];

            foreach ($absentRecords->take(9)->values() as $index => $record) {
                Excuse::create([
                    'attendance_record_id' => $record->id,
                    'reason' => $excuseReasons[$index],
                    'file_path' => null,
                    'status' => $excuseStatuses[$index],
                ]);
            }
        });
    }
}
