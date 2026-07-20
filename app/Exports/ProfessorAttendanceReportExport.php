<?php

namespace App\Exports;

use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProfessorAttendanceReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private readonly array $filters,
        private readonly int $professorId,
    ) {
    }

    public function collection(): Collection
    {
        return AttendanceRecord::query()
            ->whereHas(
                'session.subject',
                fn (Builder $query) =>
                    $query->where('user_id', $this->professorId)
            )
            ->with([
                'student.user',
                'session.subject',
                'session.room',
            ])
            ->when(
                $this->filters['subject_id'],
                fn (Builder $query, $subjectId) =>
                    $query->whereHas(
                        'session',
                        fn (Builder $sessionQuery) =>
                            $sessionQuery->where('subject_id', $subjectId)
                    )
            )
            ->when(
                $this->filters['status'],
                fn (Builder $query, $status) =>
                    $query->where('status', $status)
            )
            ->when(
                $this->filters['from'],
                fn (Builder $query, $date) =>
                    $query->whereHas(
                        'session',
                        fn (Builder $sessionQuery) =>
                            $sessionQuery->whereDate('start_time', '>=', $date)
                    )
            )
            ->when(
                $this->filters['until'],
                fn (Builder $query, $date) =>
                    $query->whereHas(
                        'session',
                        fn (Builder $sessionQuery) =>
                            $sessionQuery->whereDate('start_time', '<=', $date)
                    )
            )
            ->latest('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Student',
            'University Number',
            'Subject',
            'Subject Code',
            'Lecture Number',
            'Lecture Title',
            'Room',
            'Status',
            'Scanned At',
            'Distance (m)',
        ];
    }

    public function map($record): array
    {
        return [
            $record->student->user->name,
            (string) $record->student->university_number,
            $record->session->subject->subject_name,
            $record->session->subject->subject_code,
            (string) $record->session->lecture_number,
            $record->session->lecture_title,
            $record->session->room->room_name,
            $record->status,
            $record->scanned_at?->format('Y-m-d H:i:s') ?? '',
            $record->distance_meters !== null
                ? (string) $record->distance_meters
                : '',
        ];
    }
}