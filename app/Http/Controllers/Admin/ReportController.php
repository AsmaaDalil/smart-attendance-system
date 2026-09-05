<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendanceReportExport;
use App\Exports\StudentAttendanceSummaryExport;
use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Subject;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    /**
     * Display the administrator reports page.
     */
    public function index(Request $request): View
    {
        $filters = $this->getFilters($request);

        $records = $this->attendanceQuery($filters)
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $allRecords = $this->attendanceQuery($filters)
            ->get();

        $summary = $this->makeSummary($allRecords);

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
                'warningCount'
            )
        );
    }

    /**
     * Export the detailed attendance report as Excel.
     */
    public function excel(
        Request $request
    ): BinaryFileResponse {
        $filters = $this->getFilters($request);

        return Excel::download(
            new AttendanceReportExport($filters),
            'attendance-report-'
                .now()->format('Y-m-d-His')
                .'.xlsx'
        );
    }

    /**
     * Export student attendance summaries and warnings as Excel.
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
                .'.xlsx'
        );
    }

    /**
     * Export the detailed attendance report as PDF.
     */
    public function pdf(Request $request): Response
    {
         ini_set('memory_limit', '512M');
         set_time_limit(120);
        $filters = $this->getFilters($request);

        $records = $this->attendanceQuery($filters)
            ->latest('created_at')
            ->get();

        $summary = $this->makeSummary($records);

        $studentSummaries =
            StudentAttendanceSummaryExport::build(
                $filters
            );

        $subject = filled($filters['subject_id'])
            ? Subject::query()
                ->find($filters['subject_id'])
            : null;

        /*
         * نرسل دالة معالجة العربي إلى قالب PDF.
         */
        $shapeArabic = fn (?string $text): string =>
            $this->shapeArabic($text);

        return Pdf::loadView(
            'admin.reports.pdf.attendance',
            compact(
                'records',
                'summary',
                'filters',
                'subject',
                'studentSummaries',
                'shapeArabic'
            )
        )
            ->setPaper('a4', 'landscape')
            ->download(
                'attendance-report-'
                    .now()->format('Y-m-d-His')
                    .'.pdf'
            );
    }

    /**
     * Validate report filters.
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

    /**
     * Build the filtered attendance query.
     */
    private function attendanceQuery(
        array $filters
    ): Builder {
        return AttendanceRecord::query()
            ->with([
                'student.user',
                'session.subject',
                'session.room',
            ])
            ->when(
                filled($filters['subject_id']),
                function (
                    Builder $query,
                    $subjectId
                ): Builder {
                    return $query->whereHas(
                        'session',
                        fn (
                            Builder $sessionQuery
                        ): Builder =>
                            $sessionQuery->where(
                                'subject_id',
                                $subjectId
                            )
                    );
                }
            )
            ->when(
                filled($filters['status']),
                fn (
                    Builder $query,
                    $status
                ): Builder =>
                    $query->where(
                        'status',
                        $status
                    )
            )
            ->when(
                filled($filters['from']),
                fn (
                    Builder $query,
                    $date
                ): Builder =>
                    $query->whereDate(
                        'created_at',
                        '>=',
                        $date
                    )
            )
            ->when(
                filled($filters['until']),
                fn (
                    Builder $query,
                    $date
                ): Builder =>
                    $query->whereDate(
                        'created_at',
                        '<=',
                        $date
                    )
            );
    }

    /**
     * Generate the attendance summary.
     */
    private function makeSummary(
        Collection $records
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
                (($present + $late) / $total) * 100,
                1
            )
            : 0;

        return [
            'total' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'excused' => $excused,
            'attendance_rate' => $attendanceRate,
        ];
    }

    /**
     * Safely prepare Arabic text for DomPDF.
     */
    private function shapeArabic(?string $text): string
    {
        $text = trim((string) $text);

        if (
            $text === ''
            || ! preg_match('/\p{Arabic}/u', $text)
        ) {
            return $text;
        }

        try {
            $arabic = new Arabic();

            /*
             * المسافتان تمنعان خطأ:
             * Undefined array key -1
             * مع بعض الكلمات العربية القصيرة.
             */
            $shapedText = $arabic->utf8Glyphs(
                ' '.$text.' ',
                200,
                false,
                false
            );

            return trim($shapedText);
        } catch (\Throwable $exception) {
            /*
             * لا نوقف تصدير PDF إذا فشلت
             * معالجة نص عربي معين.
             */
            report($exception);

            return $text;
        }
    }
}