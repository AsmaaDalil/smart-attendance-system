<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceHistoryController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Get authenticated student
        |--------------------------------------------------------------------------
        */

        $student = $request->user()?->student;

        abort_if(
            ! $student,
            404,
            'Student profile was not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Read filters
        |--------------------------------------------------------------------------
        */

        $selectedSubjectId = $request->integer(
            'subject_id'
        );

        $selectedStatus = $request->input(
            'status'
        );

        $allowedStatuses = [
            'Present',
            'Late',
            'Absent',
            'Excused',
        ];

        if (
            ! in_array(
                $selectedStatus,
                $allowedStatuses,
                true
            )
        ) {
            $selectedStatus = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Get student subjects
        |--------------------------------------------------------------------------
        */

        $subjects = $student
            ->subjects()
            ->orderBy('subject_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Get attendance records with filters
        |--------------------------------------------------------------------------
        */

        $records = AttendanceRecord::query()
            ->where(
                'student_id',
                $student->id
            )
            ->with([
                'session.subject',
                'excuse',
            ])
            ->when(
                $selectedSubjectId,
                function ($query) use (
                    $selectedSubjectId
                ) {
                    $query->whereHas(
                        'session',
                        function ($sessionQuery) use (
                            $selectedSubjectId
                        ) {
                            $sessionQuery->where(
                                'subject_id',
                                $selectedSubjectId
                            );
                        }
                    );
                }
            )
            ->when(
                $selectedStatus,
                fn ($query) =>
                    $query->where(
                        'status',
                        $selectedStatus
                    )
            )
            ->orderByDesc(
                AttendanceSession::query()
                    ->select('start_time')
                    ->whereColumn(
                        'attendance_sessions.id',
                        'attendance_records.session_id'
                    )
                    ->limit(1)
            )
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Get all records for subject statistics
        |--------------------------------------------------------------------------
        */

        $allRecords = AttendanceRecord::query()
            ->where(
                'student_id',
                $student->id
            )
            ->with([
                'session:id,subject_id',
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Calculate attendance percentage for every subject
        |--------------------------------------------------------------------------
        |
        | Excused records are excluded from the percentage calculation.
        |
        */

        $subjectStatistics = $subjects->map(
            function ($subject) use ($allRecords) {
                $subjectRecords = $allRecords
                    ->filter(
                        fn ($record) =>
                            (int) $record
                                ->session
                                ?->subject_id
                            === (int) $subject->id
                    );

                $presentCount = $subjectRecords
                    ->where('status', 'Present')
                    ->count();

                $lateCount = $subjectRecords
                    ->where('status', 'Late')
                    ->count();

                $absentCount = $subjectRecords
                    ->where('status', 'Absent')
                    ->count();

                $excusedCount = $subjectRecords
                    ->where('status', 'Excused')
                    ->count();

                /*
                 * Excused is not counted as absence
                 * and is excluded from the denominator.
                 */
                $countedRecords =
                    $presentCount
                    + $lateCount
                    + $absentCount;

                $attendancePercentage =
                    $countedRecords > 0
                        ? round(
                            (
                                (
                                    $presentCount
                                    + $lateCount
                                )
                                / $countedRecords
                            ) * 100,
                            1
                        )
                        : 0;

                return [
                    'subject' => $subject,

                    'present_count' =>
                        $presentCount,

                    'late_count' =>
                        $lateCount,

                    'absent_count' =>
                        $absentCount,

                    'excused_count' =>
                        $excusedCount,

                    'total_count' =>
                        $subjectRecords->count(),

                    'attendance_percentage' =>
                        $attendancePercentage,
                ];
            }
        );

        return view(
            'student.attendance.index',
            [
                'student' => $student,

                'subjects' => $subjects,

                'records' => $records,

                'subjectStatistics' =>
                    $subjectStatistics,

                'selectedSubjectId' =>
                    $selectedSubjectId,

                'selectedStatus' =>
                    $selectedStatus,
            ]
        );
    }
}