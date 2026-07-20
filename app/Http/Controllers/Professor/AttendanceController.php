<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'subject_id' => [
                'nullable',
                'integer',
                'exists:subjects,id',
            ],

            'status' => [
                'nullable',
                'in:Present,Late,Absent,Excused',
            ],

            'date' => [
                'nullable',
                'date',
            ],
        ]);

        $subjects = Subject::query()
            ->where('user_id', auth()->id())
            ->orderBy('subject_name')
            ->get();

        $records = AttendanceRecord::query()
            ->whereHas(
                'session.subject',
                fn (Builder $query) =>
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
                $filters['search'] ?? null,
                function (Builder $query, string $search) {
                    $query->whereHas(
                        'student',
                        function (Builder $studentQuery) use (
                            $search
                        ) {
                            $studentQuery
                                ->where(
                                    'university_number',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'user',
                                    fn (Builder $userQuery) =>
                                        $userQuery->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        )
                                );
                        }
                    );
                }
            )
            ->when(
                $filters['subject_id'] ?? null,
                fn (Builder $query, $subjectId) =>
                    $query->whereHas(
                        'session',
                        fn (Builder $sessionQuery) =>
                            $sessionQuery->where(
                                'subject_id',
                                $subjectId
                            )
                    )
            )
            ->when(
                $filters['status'] ?? null,
                fn (Builder $query, $status) =>
                    $query->where('status', $status)
            )
            ->when(
                $filters['date'] ?? null,
                fn (Builder $query, $date) =>
                    $query->whereHas(
                        'session',
                        fn (Builder $sessionQuery) =>
                            $sessionQuery->whereDate(
                                'start_time',
                                $date
                            )
                    )
            )
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view(
            'professor.attendance.index',
            compact(
                'records',
                'subjects',
                'filters'
            )
        );
    }

    public function update(
        Request $request,
        AttendanceRecord $record
    ): RedirectResponse {
        $belongsToProfessor = $record
            ->session()
            ->whereHas(
                'subject',
                fn (Builder $query) =>
                    $query->where(
                        'user_id',
                        auth()->id()
                    )
            )
            ->exists();

        abort_unless($belongsToProfessor, 403);

        $validated = $request->validate([
            'status' => [
                'required',
                'in:Present,Late,Absent',
            ],
        ]);

        $record->update([
            'status' => $validated['status'],
        ]);

        return back()->with(
            'success',
            'Attendance status updated successfully.'
        );
    }
}