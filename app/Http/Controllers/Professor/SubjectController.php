<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::query()
            ->where('user_id', auth()->id())
            ->withCount([
                'students',
                'sessions',
            ])
            ->orderBy('subject_name')
            ->paginate(9);

        return view(
            'professor.subjects.index',
            compact('subjects')
        );
    }

    public function show(Subject $subject): View
    {
        $this->ensureSubjectBelongsToProfessor($subject);

        $subject->loadCount([
            'students',
            'sessions',
        ]);

        $students = $subject->students()
            ->with('user')
            ->orderBy('university_number')
            ->paginate(10);

        $sessions = $subject->sessions()
            ->with('room')
            ->withCount('attendanceRecords')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view(
            'professor.subjects.show',
            compact(
                'subject',
                'students',
                'sessions'
            )
        );
    }

    private function ensureSubjectBelongsToProfessor(
        Subject $subject
    ): void {
        abort_unless(
            $subject->user_id === auth()->id(),
            403
        );
    }
}