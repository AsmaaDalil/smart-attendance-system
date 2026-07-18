<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendanceReportExport;
use App\Exports\StudentAttendanceSummaryExport;
use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reports page
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $filters = $this->getFilters($request);

        $records = $this->attendanceQuery($filters)
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $allRecords = $this
            ->attendanceQuery($filters)
            ->get();

        $summary = $this->makeSummary(
            $allRecords
        );

        $studentSummaries =
            StudentAttendanceSummaryExport::build(
                $filters
            );

        $warningCount = $studentSummaries
            ->where('is_warning', true)
            ->count();

        $subjects = Subject::query()
            ->orderBy('subject_name')
            ->get();

        return view(
            'admin.reports.index',
            compact(
                'records',
                'summary',
                'subjects',
                'filters',
                'studentSummaries',
                'warningCount',
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detailed Excel report
    |--------------------------------------------------------------------------
    */

    public function excel(
        Request $request
    ): BinaryFileResponse {
        $filters = $this->getFilters($request);

        return Excel::download(
            new AttendanceReportExport(
                $filters
            ),
            'attendance-report-'
            .now()->format('Y-m-d-His')
            .'.xlsx',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student summary Excel report
    |--------------------------------------------------------------------------
    */

    public function warningsExcel(
        Request $request
    ): BinaryFileResponse {
        $filters = $this->getFilters($request);

        return Excel::download(
            new StudentAttendanceSummaryExport(
                $filters
            ),
            'student-attendance-summary-'
            .now()->format('Y-m-d-His')
            .'.xlsx',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PDF report
    |--------------------------------------------------------------------------
    */

    public function pdf(Request $request)
    {
        $filters = $this->getFilters($request);

        $records = $this
            ->attendanceQuery($filters)
            ->latest('created_at')
            ->get();

        $summary = $this->makeSummary(
            $records
        );

        $studentSummaries =
            StudentAttendanceSummaryExport::build(
                $filters
            );

        $subject = filled(
            $filters['subject_id']
        )
            ? Subject::find(
                $filters['subject_id']
            )
            : null;

        return Pdf::loadView(
            'admin.reports.pdf.attendance',
            compact(
                'records',
                'summary',
                'filters',
                'subject',
                'studentSummaries',
            )
        )
            ->setPaper('a4', 'landscape')
            ->download(
                'attendance-report-'
                .now()->format('Y-m-d-His')
                .'.pdf'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate filters
    |--------------------------------------------------------------------------
    */

    private function getFilters(
        Request $request
    ): array {
        $validated = $request->validate([
            'subject_id' => [
                'nullable',
                'integer',
                'exists:subjects,id',
            ],

            'status' => [
                'nullable',
                'in:Present,Late,Absent,Excused',
            ],

            'from' => [
                'nullable',
                'date',
            ],

            'until' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],
        ]);

        return [
            'subject_id' =>
                $validated['subject_id'] ?? null,

            'status' =>
                $validated['status'] ?? null,

            'from' =>
                $validated['from'] ?? null,

            'until' =>
                $validated['until'] ?? null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance query
    |--------------------------------------------------------------------------
    */

    private function attendanceQuery(
        array $filters
    ): Builder {
        return AttendanceRecord::query()
            ->with([
                'student.user',
                'session.subject',
            ])

            ->when(
                $filters['subject_id'],
                fn (
                    Builder $query,
                    $subjectId
                ): Builder => $query->whereHas(
                    'session',
                    fn (
                        Builder $sessionQuery
                    ): Builder => $sessionQuery
                        ->where(
                            'subject_id',
                            $subjectId
                        )
                )
            )

            ->when(
                $filters['status'],
                fn (
                    Builder $query,
                    $status
                ): Builder => $query->where(
                    'status',
                    $status
                )
            )

            ->when(
                $filters['from'],
                fn (
                    Builder $query,
                    $date
                ): Builder => $query->whereDate(
                    'created_at',
                    '>=',
                    $date
                )
            )

            ->when(
                $filters['until'],
                fn (
                    Builder $query,
                    $date
                ): Builder => $query->whereDate(
                    'created_at',
                    '<=',
                    $date
                )
            );
    }

    /*
    |--------------------------------------------------------------------------
    | General report summary
    |--------------------------------------------------------------------------
    */

    private function makeSummary(
        $records
    ): array {
        $total = $records->count();

        $present = $records
            ->where('status', 'Present')
            ->count();

        $late = $records
            ->where('status', 'Late')
            ->count();

        $absent = $records
            ->where('status', 'Absent')
            ->count();

        $excused = $records
            ->where('status', 'Excused')
            ->count();

        $attendanceRate = $total > 0
            ? round(
                (
                    ($present + $late)
                    / $total
                ) * 100,
                1
            )
            : 0;

        return [
            'total' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'excused' => $excused,
            'attendance_rate' =>
                $attendanceRate,
        ];
    }
}