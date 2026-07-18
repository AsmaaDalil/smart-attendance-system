<?php

namespace App\Exports;

use App\Models\AttendanceRecord;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentAttendanceSummaryExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private readonly array $filters = []
    ) {
    }

    public static function build(array $filters): Collection
    {
        return Enrollment::query()
            ->with([
                'student.user',
                'subject',
            ])
            ->when(
                $filters['subject_id'] ?? null,
                fn (Builder $query, $subjectId): Builder =>
                    $query->where('subject_id', $subjectId)
            )
            ->get()
            ->map(function (
                Enrollment $enrollment
            ) use ($filters): array {
                $query = AttendanceRecord::query()
                    ->where(
                        'student_id',
                        $enrollment->student_id
                    )
                    ->whereHas(
                        'session',
                        fn (Builder $sessionQuery): Builder =>
                            $sessionQuery->where(
                                'subject_id',
                                $enrollment->subject_id
                            )
                    )
                    ->when(
                        $filters['from'] ?? null,
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
                        $filters['until'] ?? null,
                        fn (
                            Builder $query,
                            $date
                        ): Builder => $query->whereDate(
                            'created_at',
                            '<=',
                            $date
                        )
                    );

                $counts = (clone $query)
                    ->selectRaw(
                        'status, COUNT(*) as records_count'
                    )
                    ->groupBy('status')
                    ->pluck(
                        'records_count',
                        'status'
                    );

                $present = (int) (
                    $counts['Present'] ?? 0
                );

                $late = (int) (
                    $counts['Late'] ?? 0
                );

                $absent = (int) (
                    $counts['Absent'] ?? 0
                );

                $excused = (int) (
                    $counts['Excused'] ?? 0
                );

                $total = $present
                    + $late
                    + $absent
                    + $excused;

                $absenceRate = $total > 0
                    ? round(
                        ($absent / $total) * 100,
                        1
                    )
                    : 0;

                return [
                    'student_name' =>
                        $enrollment->student->user->name,

                    'university_number' =>
                        $enrollment
                            ->student
                            ->university_number,

                    'subject_name' =>
                        $enrollment->subject->subject_name,

                    'subject_code' =>
                        $enrollment->subject->subject_code,

                    'total' => $total,
                    'present' => $present,
                    'late' => $late,
                    'absent' => $absent,
                    'excused' => $excused,

                    'absence_rate' => $absenceRate,

                    'is_warning' =>
                        $absenceRate >= 25,
                ];
            })
            ->filter(
                fn (array $summary): bool =>
                    $summary['total'] > 0
            )
            ->sortByDesc('absence_rate')
            ->values();
    }

    public function collection(): Collection
    {
        return self::build($this->filters);
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'University Number',
            'Subject',
            'Subject Code',
            'Total Records',
            'Present',
            'Late',
            'Absent',
            'Excused',
            'Absence Rate',
            'Warning Status',
        ];
    }

   public function map($summary): array
{
    return [
        $summary['student_name'],

        $summary['university_number'],

        $summary['subject_name'],

        $summary['subject_code'],

        (string) $summary['total'],

        (string) $summary['present'],

        (string) $summary['late'],

        (string) $summary['absent'],

        (string) $summary['excused'],

        $summary['absence_rate'].'%',

        $summary['is_warning']
            ? 'Warning'
            : 'Safe',
    ];
}
}