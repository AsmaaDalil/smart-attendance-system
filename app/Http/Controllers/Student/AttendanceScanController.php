<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceScanController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => [
                'required',
                'integer',
                'exists:attendance_sessions,id',
            ],

            'qr_code' => [
                'required',
                'string',
                'max:500',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'device_token' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $student = auth()
            ->user()
            ->student;

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'Student profile was not found.',
            ], 404);
        }

        $session = AttendanceSession::query()
            ->with([
                'subject',
                'room',
            ])
            ->findOrFail(
                $validated['session_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Check that the student is enrolled in the subject
        |--------------------------------------------------------------------------
        */

        $isEnrolled = $student
            ->subjects()
            ->whereKey($session->subject_id)
            ->exists();

        if (! $isEnrolled) {
            return response()->json([
                'success' => false,
                'message' =>
                    'You are not enrolled in this subject.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Check session status and time
        |--------------------------------------------------------------------------
        */

        $now = now();

        $startTime = Carbon::parse(
            $session->start_time
        );

        $endTime = Carbon::parse(
            $session->end_time
        );

        if (
            $session->status !== 'Active'
            || $now->lt($startTime)
            || $now->gt($endTime)
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'This attendance session is not active.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance scanning is allowed for only 15 minutes
        |--------------------------------------------------------------------------
        */

        $scanDeadline = $startTime
            ->copy()
            ->addMinutes(15);

        if ($now->gt($scanDeadline)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'The attendance scanning time has ended.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate dynamic QR code
        |--------------------------------------------------------------------------
        */

        $scannedQrHash = hash(
            'sha256',
            (string) $validated['qr_code']
        );

        if (
            empty($session->qr_current_code)
            || ! hash_equals(
                (string) $session->qr_current_code,
                $scannedQrHash
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'The QR code is invalid.',
            ], 422);
        }

        if (
            empty($session->qr_expires_at)
            || $now->gt(
                Carbon::parse($session->qr_expires_at)
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'The QR code has expired. Scan the new code.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate attendance
        |--------------------------------------------------------------------------
        */

        $alreadyRecorded = AttendanceRecord::query()
            ->where('student_id', $student->id)
            ->where('session_id', $session->id)
            ->exists();

        if ($alreadyRecorded) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Your attendance was already recorded.',
            ], 409);
        }

        /*
        |--------------------------------------------------------------------------
        | Device protection
        |--------------------------------------------------------------------------
        */

        $deviceUsedByAnotherStudent = Student::query()
            ->where(
                'device_token',
                $validated['device_token']
            )
            ->whereKeyNot($student->id)
            ->exists();

        if ($deviceUsedByAnotherStudent) {
            return response()->json([
                'success' => false,
                'message' =>
                    'This device is already linked to another student.',
            ], 403);
        }

        if (
            filled($student->device_token)
            && ! hash_equals(
                (string) $student->device_token,
                (string) $validated['device_token']
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'This account is linked to another device.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate distance from lecture room
        |--------------------------------------------------------------------------
        */

      if (! $session->room) {
    return response()->json([
        'success' => false,
        'message' =>
            'No lecture room is assigned to this session.',
    ], 422);
}

if (
    $session->room->latitude === null
    || $session->room->longitude === null
) {
    return response()->json([
        'success' => false,
        'message' =>
            'The lecture room location is not configured.',
    ], 422);
}

if (
    $session->room->allowed_radius === null
    || (float) $session->room->allowed_radius <= 0
) {
    return response()->json([
        'success' => false,
        'message' =>
            'The allowed room radius is not configured.',
    ], 422);
}

        $distance = $this->calculateDistance(
            (float) $validated['latitude'],
            (float) $validated['longitude'],
            (float) $session->room->latitude,
            (float) $session->room->longitude,
        );

        $allowedRadius = (float)
            $session->room->allowed_radius;

        if ($distance > $allowedRadius) {
            return response()->json([
                'success' => false,
                'message' =>
                    'You are outside the allowed lecture room area.',
                'distance_meters' =>
                    round($distance, 2),
                'allowed_radius' =>
                    $allowedRadius,
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Present during first 10 minutes, then Late until minute 15
        |--------------------------------------------------------------------------
        */

        $presentDeadline = $startTime
            ->copy()
            ->addMinutes(10);

        $status = $now->lte($presentDeadline)
            ? 'Present'
            : 'Late';

        /*
        |--------------------------------------------------------------------------
        | Save attendance safely
        |--------------------------------------------------------------------------
        */

        $record = DB::transaction(function () use (
            $student,
            $session,
            $validated,
            $status,
            $distance
        ) {
            if (blank($student->device_token)) {
                $student->update([
                    'device_token' =>
                        $validated['device_token'],
                ]);
            }

            return AttendanceRecord::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'session_id' => $session->id,
                ],
                [
                    'scanned_at' => now(),
                    'status' => $status,
                    'distance_meters' =>
                        round($distance, 2),
                    'is_dorm_approved' => false,
                ]
            );
        });

        return response()->json([
            'success' => true,
            'message' => $status === 'Present'
                ? 'Attendance recorded successfully.'
                : 'Attendance recorded as late.',
            'status' => $record->status,
            'distance_meters' =>
                $record->distance_meters,
        ]);
    }

    private function calculateDistance(
        float $studentLatitude,
        float $studentLongitude,
        float $roomLatitude,
        float $roomLongitude,
    ): float {
        $earthRadius = 6371000;

        $latitudeDifference = deg2rad(
            $roomLatitude - $studentLatitude
        );

        $longitudeDifference = deg2rad(
            $roomLongitude - $studentLongitude
        );

        $a =
            sin($latitudeDifference / 2) ** 2
            + cos(deg2rad($studentLatitude))
            * cos(deg2rad($roomLatitude))
            * sin($longitudeDifference / 2) ** 2;

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }
}