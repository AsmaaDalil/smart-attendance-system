<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Excuse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExcuseController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:Pending,Approved,Rejected'],
        ]);

        $excuses = Excuse::query()
            ->whereHas(
                'attendanceRecord.session.subject',
                fn (Builder $query) =>
                    $query->where('user_id', auth()->id())
            )
            ->with([
                'attendanceRecord.student.user',
                'attendanceRecord.session.subject',
            ])
            ->when(
                $filters['search'] ?? null,
                function (Builder $query, string $search) {
                    $query->whereHas(
                        'attendanceRecord.student',
                        function (Builder $studentQuery) use ($search) {
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
                $filters['status'] ?? null,
                fn (Builder $query, string $status) =>
                    $query->where('status', $status)
            )
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view(
            'professor.excuses.index',
            compact('excuses', 'filters')
        );
    }

    public function approve(Excuse $excuse): RedirectResponse
    {
        $this->ensureExcuseBelongsToProfessor($excuse);

        if ($excuse->status !== 'Pending') {
            return back()->with(
                'info',
                'This excuse has already been reviewed.'
            );
        }

        DB::transaction(function () use ($excuse) {
            $excuse->update([
                'status' => 'Approved',
            ]);

            $excuse->attendanceRecord->update([
                'status' => 'Excused',
            ]);
        });

        return back()->with(
            'success',
            'The excuse was approved successfully.'
        );
    }

    public function reject(Excuse $excuse): RedirectResponse
    {
        $this->ensureExcuseBelongsToProfessor($excuse);

        if ($excuse->status !== 'Pending') {
            return back()->with(
                'info',
                'This excuse has already been reviewed.'
            );
        }

        DB::transaction(function () use ($excuse) {
            $excuse->update([
                'status' => 'Rejected',
            ]);

            $excuse->attendanceRecord->update([
                'status' => 'Absent',
            ]);
        });

        return back()->with(
            'success',
            'The excuse was rejected.'
        );
    }

    private function ensureExcuseBelongsToProfessor(
        Excuse $excuse
    ): void {
        $belongsToProfessor = $excuse
            ->attendanceRecord()
            ->whereHas(
                'session.subject',
                fn (Builder $query) =>
                    $query->where('user_id', auth()->id())
            )
            ->exists();

        abort_unless($belongsToProfessor, 403);
    }
}