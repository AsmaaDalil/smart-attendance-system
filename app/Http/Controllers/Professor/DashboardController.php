<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $professor = $request->user();

        $subjects = Subject::query()
            ->where('user_id', $professor->id)
            ->withCount([
                'students',
                'sessions',
            ])
            ->orderBy('subject_name')
            ->get();

        $activeSessions = AttendanceSession::query()
            ->whereHas(
                'subject',
                fn ($query) => $query->where(
                    'user_id',
                    $professor->id
                )
            )
            ->where('status', 'Active')
            ->count();

        $attendanceRecords = AttendanceRecord::query()
            ->whereHas(
                'session.subject',
                fn ($query) => $query->where(
                    'user_id',
                    $professor->id
                )
            )
            ->count();

        $recentSessions = AttendanceSession::query()
            ->with([
                'subject',
                'room',
            ])
            ->withCount('attendanceRecords')
            ->whereHas(
                'subject',
                fn ($query) => $query->where(
                    'user_id',
                    $professor->id
                )
            )
            ->latest('created_at')
            ->take(5)
            ->get();

        return view(
            'professor.dashboard',
            compact(
                'subjects',
                'activeSessions',
                'attendanceRecords',
                'recentSessions',
            )
        );
    }
}