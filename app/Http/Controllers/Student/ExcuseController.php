<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Excuse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ExcuseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display student excuses
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $student = $request->user()?->student;

        abort_if(
            ! $student,
            404,
            'Student profile was not found.'
        );

        $selectedStatus = $request->input('status');

        $allowedStatuses = [
            'Pending',
            'Approved',
            'Rejected',
        ];

        if (
            ! in_array(
                $selectedStatus,
                $allowedStatuses,
                true
            )
        ) {
            $selectedStatus = null;
        }

        $excuses = Excuse::query()
            ->whereHas(
                'attendanceRecord',
                fn ($query) =>
                    $query->where(
                        'student_id',
                        $student->id
                    )
            )
            ->with([
                'attendanceRecord.session.subject',
            ])
            ->when(
                $selectedStatus,
                fn ($query) =>
                    $query->where(
                        'status',
                        $selectedStatus
                    )
            )
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view(
            'student.excuses.index',
            [
                'student' => $student,
                'excuses' => $excuses,
                'selectedStatus' => $selectedStatus,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Display excuse submission form
    |--------------------------------------------------------------------------
    */

    public function create(
        Request $request,
        AttendanceRecord $attendanceRecord
    ): View {
        $student = $request->user()?->student;

        abort_if(
            ! $student,
            404,
            'Student profile was not found.'
        );

        $this->ensureRecordBelongsToStudent(
            $attendanceRecord,
            $student->id
        );

        abort_unless(
            $attendanceRecord->status === 'Absent',
            422,
            'An excuse can only be submitted for an absent record.'
        );

        abort_if(
            $attendanceRecord->excuse()->exists(),
            409,
            'An excuse was already submitted for this attendance record.'
        );

        $attendanceRecord->load([
            'session.subject',
        ]);

        return view(
            'student.excuses.create',
            compact('attendanceRecord')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Save student excuse
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        AttendanceRecord $attendanceRecord
    ): RedirectResponse {
        $student = $request->user()?->student;

        abort_if(
            ! $student,
            404,
            'Student profile was not found.'
        );

        $this->ensureRecordBelongsToStudent(
            $attendanceRecord,
            $student->id
        );

        abort_unless(
            $attendanceRecord->status === 'Absent',
            422,
            'An excuse can only be submitted for an absent record.'
        );

        abort_if(
            $attendanceRecord->excuse()->exists(),
            409,
            'An excuse was already submitted for this attendance record.'
        );

        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $filePath = null;

        if ($request->hasFile('attachment')) {
            $filePath = $request
                ->file('attachment')
                ->store(
                    'excuses',
                    'public'
                );
        }

        try {
            DB::transaction(function () use (
                $attendanceRecord,
                $validated,
                $filePath
            ) {
                Excuse::create([
                    'attendance_record_id' =>
                        $attendanceRecord->id,

                    'reason' =>
                        $validated['reason'],

                    'file_path' =>
                        $filePath,

                    'status' =>
                        'Pending',
                ]);
            });
        } catch (Throwable $exception) {
            if ($filePath) {
                Storage::disk('public')->delete(
                    $filePath
                );
            }

            throw $exception;
        }

        return redirect()
            ->route('student.excuses.index')
            ->with(
                'success',
                'Your excuse was submitted successfully and is waiting for review.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Ensure the attendance record belongs to the logged-in student
    |--------------------------------------------------------------------------
    */

    private function ensureRecordBelongsToStudent(
        AttendanceRecord $attendanceRecord,
        int $studentId
    ): void {
        abort_unless(
            (int) $attendanceRecord->student_id
                === $studentId,
            403
        );
    }
}