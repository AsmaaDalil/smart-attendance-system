<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Get authenticated student and registered subjects
        |--------------------------------------------------------------------------
        */

        $student = auth()
            ->user()
            ->student()
            ->with('subjects')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Get all attendance records
        |--------------------------------------------------------------------------
        */

        $records = $student
            ->attendanceRecords()
            ->with([
                'session.subject',
                'session.room',
                'excuse',
            ])
            ->latest('created_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | General attendance statistics
        |--------------------------------------------------------------------------
        */

        $totalRecords = $records->count();

        $presentCount = $records
            ->where('status', 'Present')
            ->count();

        $lateCount = $records
            ->where('status', 'Late')
            ->count();

        $absentCount = $records
            ->where('status', 'Absent')
            ->count();

        $excusedCount = $records
            ->where('status', 'Excused')
            ->count();

        /*
         * Excused records are excluded from the
         * attendance and absence percentage.
         */
        $countedRecords =
            $presentCount
            + $lateCount
            + $absentCount;

        $attendanceRate = $countedRecords > 0
            ? round(
                (
                    ($presentCount + $lateCount)
                    / $countedRecords
                ) * 100,
                1
            )
            : 0;

        $absenceRate = $countedRecords > 0
            ? round(
                (
                    $absentCount
                    / $countedRecords
                ) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Calculate statistics and warnings for every subject
        |--------------------------------------------------------------------------
        */

        $subjectStatistics = $student
            ->subjects
            ->map(function ($subject) use ($records) {
                $subjectRecords = $records
                    ->filter(
                        fn ($record) =>
                            (int) $record
                                ->session
                                ?->subject_id
                            === (int) $subject->id
                    );

                $present = $subjectRecords
                    ->where('status', 'Present')
                    ->count();

                $late = $subjectRecords
                    ->where('status', 'Late')
                    ->count();

                $absent = $subjectRecords
                    ->where('status', 'Absent')
                    ->count();

                $excused = $subjectRecords
                    ->where('status', 'Excused')
                    ->count();

                $counted =
                    $present
                    + $late
                    + $absent;

                $subjectAttendanceRate = $counted > 0
                    ? round(
                        (
                            ($present + $late)
                            / $counted
                        ) * 100,
                        1
                    )
                    : 0;

                $subjectAbsenceRate = $counted > 0
                    ? round(
                        (
                            $absent
                            / $counted
                        ) * 100,
                        1
                    )
                    : 0;

                /*
                 * Safe: absence below 15%
                 * Warning: absence from 15% to below 25%
                 * At Risk: absence 25% or more
                 */
                if (
                    $counted > 0
                    && $subjectAbsenceRate >= 25
                ) {
                    $riskLevel = 'at_risk';
                    $riskLabel = 'At Risk';
                } elseif (
                    $counted > 0
                    && $subjectAbsenceRate >= 15
                ) {
                    $riskLevel = 'warning';
                    $riskLabel = 'Warning';
                } else {
                    $riskLevel = 'safe';
                    $riskLabel = 'Safe';
                }

                return [
                    'subject' => $subject,

                    'total_records' =>
                        $subjectRecords->count(),

                    'counted_records' =>
                        $counted,

                    'present_count' =>
                        $present,

                    'late_count' =>
                        $late,

                    'absent_count' =>
                        $absent,

                    'excused_count' =>
                        $excused,

                    'attendance_rate' =>
                        $subjectAttendanceRate,

                    'absence_rate' =>
                        $subjectAbsenceRate,

                    'risk_level' =>
                        $riskLevel,

                    'risk_label' =>
                        $riskLabel,
                ];
            })
            ->sortByDesc('absence_rate')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Warning collections
        |--------------------------------------------------------------------------
        */

        $atRiskSubjects = $subjectStatistics
            ->where('risk_level', 'at_risk')
            ->values();

        $warningSubjects = $subjectStatistics
            ->where('risk_level', 'warning')
            ->values();

        $hasWarning =
            $atRiskSubjects->isNotEmpty()
            || $warningSubjects->isNotEmpty();

        /*
        |--------------------------------------------------------------------------
        | Pending excuses
        |--------------------------------------------------------------------------
        */

        $pendingExcusesCount = $records
            ->filter(
                fn ($record) =>
                    $record->excuse?->status
                    === 'Pending'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Get active sessions for registered subjects
        |--------------------------------------------------------------------------
        */

        $subjectIds = $student
            ->subjects
            ->pluck('id');

        $activeSessions = AttendanceSession::query()
            ->with([
                'subject',
                'room',
            ])
            ->whereIn(
                'subject_id',
                $subjectIds
            )
            ->where('status', 'Active')
            ->where(
                'start_time',
                '<=',
                now()
            )
            ->where(
                'end_time',
                '>',
                now()
            )
            ->latest('start_time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent records
        |--------------------------------------------------------------------------
        */

        $recentAttendance = $records
            ->take(5);

        $subjectsCount = $student
            ->subjects
            ->count();

        return view(
            'student.dashboard',
            compact(
                'student',
                'subjectsCount',
                'totalRecords',
                'countedRecords',
                'presentCount',
                'lateCount',
                'absentCount',
                'excusedCount',
                'attendanceRate',
                'absenceRate',
                'hasWarning',
                'subjectStatistics',
                'atRiskSubjects',
                'warningSubjects',
                'pendingExcusesCount',
                'activeSessions',
                'recentAttendance',
            )
        );
    }
}