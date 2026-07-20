<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Room;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class AttendanceSessionController extends Controller
{
    public function index(): View
    {
        /*
         * إنهاء الجلسات التي انتهى وقتها تلقائياً
         * وإنشاء سجلات غياب للطلاب الذين لم يسجلوا.
         */
        $expiredSessions = AttendanceSession::query()
            ->whereHas(
                'subject',
                fn ($query) =>
                    $query->where('user_id', auth()->id())
            )
            ->where('status', 'Active')
            ->where('end_time', '<=', now())
            ->get();

        foreach ($expiredSessions as $expiredSession) {
            $this->finishSession($expiredSession);
        }

        $activeSessions = AttendanceSession::query()
            ->whereHas(
                'subject',
                fn ($query) =>
                    $query->where('user_id', auth()->id())
            )
            ->where('status', 'Active')
            ->where('end_time', '>', now())
            ->with([
                'subject',
                'room',
            ])
            ->withCount('attendanceRecords')
            ->latest('start_time')
            ->get();

        $endedSessions = AttendanceSession::query()
            ->whereHas(
                'subject',
                fn ($query) =>
                    $query->where('user_id', auth()->id())
            )
            ->where('status', 'Ended')
            ->with([
                'subject',
                'room',
            ])
            ->withCount('attendanceRecords')
            ->latest('start_time')
            ->paginate(10);

        return view(
            'professor.sessions.index',
            compact(
                'activeSessions',
                'endedSessions'
            )
        );
    }

    public function create(): View
    {
        $subjects = Subject::query()
            ->where('user_id', auth()->id())
            ->orderBy('subject_name')
            ->get();

        $rooms = Room::query()
            ->orderBy('room_name')
            ->get();

        return view(
            'professor.sessions.create',
            compact('subjects', 'rooms')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => [
                'required',

                Rule::exists('subjects', 'id')
                    ->where(
                        fn ($query) =>
                            $query->where(
                                'user_id',
                                auth()->id()
                            )
                    ),
            ],

            'room_id' => [
                'required',
                'exists:rooms,id',
            ],

            'lecture_number' => [
                'required',
                'integer',
                'min:1',
            ],

            'lecture_title' => [
                'required',
                'string',
                'max:255',
            ],

            'duration_minutes' => [
                'required',
                'integer',
                'min:5',
                'max:300',
            ],
        ]);

        /*
         * إنهاء أي جلسة انتهى وقتها قبل التحقق.
         */
        $expiredSessions = AttendanceSession::query()
            ->where('subject_id', $validated['subject_id'])
            ->where('status', 'Active')
            ->where('end_time', '<=', now())
            ->get();

        foreach ($expiredSessions as $expiredSession) {
            $this->finishSession($expiredSession);
        }

        $alreadyActive = AttendanceSession::query()
            ->where('subject_id', $validated['subject_id'])
            ->where('status', 'Active')
            ->where('end_time', '>', now())
            ->exists();

        if ($alreadyActive) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'This subject already has an active session.',
                ]);
        }
$startTime = now();

$endTime = $startTime
    ->copy()
    ->addMinutes(
        (int) $validated['duration_minutes']
    );

/*
|--------------------------------------------------------------------------
| منع وجود جلستين في القاعة نفسها بالوقت نفسه
|--------------------------------------------------------------------------
*/

$roomConflict = AttendanceSession::query()
    ->where('room_id', $validated['room_id'])
    ->where('status', 'Active')
    ->where('start_time', '<', $endTime)
    ->where('end_time', '>', $startTime)
    ->with([
        'subject.professor',
        'room',
    ])
    ->first();

if ($roomConflict) {
    $professorName =
        $roomConflict->subject->professor->name
        ?? 'another professor';

    return back()
        ->withInput()
        ->withErrors([
            'room_id' =>
                'This room is currently occupied by '
                .$professorName
                .' for '
                .$roomConflict->subject->subject_name
                .' until '
                .$roomConflict->end_time->format('H:i')
                .'.',
        ]);
}

/*
|--------------------------------------------------------------------------
| إنشاء الجلسة
|--------------------------------------------------------------------------
*/

$session = AttendanceSession::create([
    'subject_id' => $validated['subject_id'],
    'room_id' => $validated['room_id'],
    'lecture_number' =>
        $validated['lecture_number'],
    'lecture_title' =>
        $validated['lecture_title'],

    'start_time' => $startTime,
    'end_time' => $endTime,

    'status' => 'Active',
    'qr_current_code' => null,
    'qr_expires_at' => null,
]);

        return redirect()
            ->route('professor.sessions.show', $session)
            ->with(
                'success',
                'Attendance session started successfully.'
            );
    }

    public function show(
        AttendanceSession $session
    ): View {
        $this->ensureSessionBelongsToProfessor($session);

        /*
         * إذا انتهى وقتها، ننهيها عند فتح الصفحة.
         */
        if (
            $session->status === 'Active'
            && $session->end_time
            && $session->end_time->isPast()
        ) {
            $this->finishSession($session);
            $session->refresh();
        }

        $session->load([
            'subject',
            'room',
            'attendanceRecords.student.user',
        ]);

        return view(
            'professor.sessions.show',
            compact('session')
        );
    }

    public function end(
        AttendanceSession $session
    ): RedirectResponse {
        $this->ensureSessionBelongsToProfessor($session);

        if ($session->status === 'Ended') {
            return redirect()
                ->route('professor.sessions.index')
                ->with(
                    'info',
                    'This session has already ended.'
                );
        }

        $this->finishSession($session);

        return redirect()
            ->route('professor.sessions.show', $session)
            ->with(
                'success',
                'Session ended and absent students were recorded.'
            );
    }
  
 public function refreshQr(
    AttendanceSession $session
): JsonResponse {
    $this->ensureSessionBelongsToProfessor($session);

    if (
        $session->status !== 'Active'
        || !$session->end_time
        || $session->end_time->isPast()
    ) {
        if ($session->status === 'Active') {
            $this->finishSession($session);
        }

        return response()->json([
            'message' =>
                'This attendance session has ended.',
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | إغلاق تسجيل الحضور بعد 15 دقيقة
    |--------------------------------------------------------------------------
    */

    $attendanceWindowEndsAt = $session->start_time
        ->copy()
        ->addMinutes(15);

    if (
        now()->greaterThanOrEqualTo(
            $attendanceWindowEndsAt
        )
    ) {
        $session->update([
            'qr_current_code' => null,
            'qr_expires_at' => null,
        ]);

        return response()->json([
            'message' =>
                'The 15-minute attendance period has ended.',

            'attendance_closed' => true,
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | إنشاء QR جديد صالح لمدة 15 ثانية
    |--------------------------------------------------------------------------
    */

    $code = Str::random(64);

    $expiresAt = now()->addSeconds(15);

    $session->update([
        'qr_current_code' => hash(
            'sha256',
            $code
        ),

        'qr_expires_at' => $expiresAt,
    ]);

    $payload = json_encode([
        'type' => 'smart_attendance',
        'session_id' => $session->id,
        'code' => $code,
    ]);

    return response()->json([
        'payload' => $payload,
        'expires_at' => $expiresAt->toIso8601String(),
        'refresh_after' => 15,
    ]);
}

    private function finishSession(
        AttendanceSession $session
    ): void {
        DB::transaction(function () use ($session) {
            $session->loadMissing('subject.students');

            foreach ($session->subject->students as $student) {
                AttendanceRecord::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'session_id' => $session->id,
                    ],
                    [
                        'scanned_at' => null,
                        'status' => 'Absent',
                        'distance_meters' => null,
                        'is_dorm_approved' => false,
                    ]
                );
            }

            $session->update([
                'status' => 'Ended',
                'end_time' => now(),
                'qr_current_code' => null,
                'qr_expires_at' => null,
            ]);
        });
    }

    private function ensureSessionBelongsToProfessor(
        AttendanceSession $session
    ): void {
        abort_unless(
            $session->subject()
                ->where('user_id', auth()->id())
                ->exists(),
            403
        );
    }
}