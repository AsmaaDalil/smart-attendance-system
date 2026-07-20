<?php

namespace App\Http\Controllers\Professor;

use App\Exports\ProfessorAttendanceReportExport;
use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Subject;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    /**
     * Display the professor attendance reports page.
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

        $subjects = Subject::query()
            ->where('user_id', auth()->id())
            ->orderBy('subject_name')
            ->get();

        return view(
            'professor.reports.index',
            compact(
                'records',
                'summary',
                'subjects',
                'filters'
            )
        );
    }

    /**
     * Export the filtered attendance report as Excel.
     */
    public function excel(Request $request): BinaryFileResponse
    {
        $filters = $this->getFilters($request);

        return Excel::download(
            new ProfessorAttendanceReportExport(
                $filters,
                auth()->id()
            ),
            'professor-attendance-report-'
                .now()->format('Y-m-d-His')
                .'.xlsx'
        );
    }

    /**
     * Export the filtered attendance report as PDF.
     */
    public function pdf(Request $request): Response
    {
        $filters = $this->getFilters($request);

        $records = $this->attendanceQuery($filters)
            ->latest('created_at')
            ->get();

        $summary = $this->makeSummary($records);

        $subject = filled($filters['subject_id'])
            ? Subject::query()
                ->where('user_id', auth()->id())
                ->find($filters['subject_id'])
            : null;

        /*
         * تُرسل هذه الدالة إلى ملف Blade حتى نعالج
         * النصوص العربية قبل عرضها داخل PDF.
         */
        $shapeArabic = fn (?string $text): string =>
            $this->shapeArabic($text);

        return Pdf::loadView(
            'professor.reports.pdf.attendance',
            compact(
                'records',
                'summary',
                'filters',
                'subject',
                'shapeArabic'
            )
        )
            ->setPaper('a4', 'landscape')
            ->download(
                'professor-attendance-report-'
                .now()->format('Y-m-d-His')
                .'.pdf'
            );
    }

    /**
     * Validate and return report filters.
     */
    private function getFilters(Request $request): array
    {
        $validated = $request->validate([
            'subject_id' => [
                'nullable',
                'integer',

                Rule::exists('subjects', 'id')
                    ->where(
                        fn ($query) =>
                            $query->where(
                                'user_id',
                                auth()->id()
                            )
                    ),
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'Present',
                    'Late',
                    'Absent',
                    'Excused',
                ]),
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
     * Build an attendance query restricted to the current professor.
     */
    private function attendanceQuery(array $filters): Builder
    {
        return AttendanceRecord::query()
            ->whereHas(
                'session.subject',
                fn (Builder $query): Builder =>
                    $query->where(
                        'user_id',
                        auth()->id()
                    )
            )
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
                function (
                    Builder $query,
                    $date
                ): Builder {
                    return $query->whereHas(
                        'session',
                        fn (
                            Builder $sessionQuery
                        ): Builder =>
                            $sessionQuery->whereDate(
                                'start_time',
                                '>=',
                                $date
                            )
                    );
                }
            )
            ->when(
                filled($filters['until']),
                function (
                    Builder $query,
                    $date
                ): Builder {
                    return $query->whereHas(
                        'session',
                        fn (
                            Builder $sessionQuery
                        ): Builder =>
                            $sessionQuery->whereDate(
                                'start_time',
                                '<=',
                                $date
                            )
                    );
                }
            );
    }

    /**
     * Generate attendance report summary.
     */
    private function makeSummary(Collection $records): array
    {
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
     * Safely shape Arabic text for DomPDF.
     */
    private function shapeArabic(?string $text): string
    {
        $text = trim((string) $text);

        /*
         * لا تحتاج النصوص الفارغة أو الإنجليزية
         * إلى معالجة من مكتبة اللغة العربية.
         */
        if (
            $text === ''
            || ! preg_match('/\p{Arabic}/u', $text)
        ) {
            return $text;
        }

        try {
            $arabic = new Arabic();

            /*
             * نضيف مسافة في البداية والنهاية لتجنب خطأ:
             * Undefined array key -1
             * الذي قد يظهر مع بعض الكلمات القصيرة.
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
             * لا نوقف تصدير التقرير كاملًا إذا لم تستطع
             * المكتبة معالجة كلمة عربية معينة.
             */
            report($exception);

            return $text;
        }
    }
}