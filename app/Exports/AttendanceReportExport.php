<?php

namespace App\Exports;

use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private readonly array $filters = []
    ) {
    }

    public function collection()
    {
        return AttendanceRecord::query()
            ->with([
                'student.user',
                'session.subject',
            ])
            ->when(
                $this->filters['subject_id'] ?? null,
                fn (Builder $query, $subjectId): Builder =>
                    $query->whereHas(
                        'session',
                        fn (Builder $sessionQuery): Builder =>
                            $sessionQuery->where(
                                'subject_id',
                                $subjectId
                            )
                    )
            )
            ->when(
                $this->filters['status'] ?? null,
                fn (Builder $query, $status): Builder =>
                    $query->where(
                        'status',
                        $status
                    )
            )
            ->when(
                $this->filters['from'] ?? null,
                fn (Builder $query, $date): Builder =>
                    $query->whereDate(
                        'created_at',
                        '>=',
                        $date
                    )
            )
            ->when(
                $this->filters['until'] ?? null,
                fn (Builder $query, $date): Builder =>
                    $query->whereDate(
                        'created_at',
                        '<=',
                        $date
                    )
            )
            ->latest('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'University Number',
            'Subject',
            'Subject Code',
            'Lecture Number',
            'Lecture Title',
            'Status',
            'Scanned At',
            'Distance (m)',
            'Dorm Approved',
        ];
    }

    public function map($record): array
    {
        return [
            $record->student->user->name,

            $record->student->university_number,

            $record->session->subject->subject_name,

            $record->session->subject->subject_code,

            $record->session->lecture_number,

            $record->session->lecture_title,

            $record->status,

            $record->scanned_at?->format(
                'Y-m-d H:i:s'
            ) ?? 'Not scanned',

            $record->distance_meters
                ?? 'Not recorded',

            $record->is_dorm_approved
                ? 'Yes'
                : 'No',
        ];
    }
}