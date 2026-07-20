<?php

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Professor\AttendanceSessionController;
use App\Http\Controllers\Professor\DashboardController;
use App\Http\Controllers\Professor\SubjectController;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Professor\AttendanceController;
use App\Http\Controllers\Professor\ExcuseController;
use App\Http\Controllers\Professor\ReportController as ProfessorReportController;

/*
|--------------------------------------------------------------------------
| الصفحة الرئيسية
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| التوجيه العام حسب دور المستخدم
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route(
            'admin.dashboard'
        ),

        'professor' => redirect()->route(
            'professor.dashboard'
        ),

        'student' => redirect()->route(
            'student.scanner'
        ),

        default => abort(403),
    };
})
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| مسارات الأدمن
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | لوحة تحكم الأدمن
    |--------------------------------------------------------------------------
    */

    Route::get('/admin-dashboard', function () {
        $studentsCount = Student::count();

        $subjectsCount = Subject::count();

        $totalAttendanceRecords =
            AttendanceRecord::count();

        $attendedRecords =
            AttendanceRecord::whereIn(
                'status',
                ['Present', 'Late']
            )->count();

        $attendanceRate =
            $totalAttendanceRecords > 0
                ? round(
                    (
                        $attendedRecords
                        / $totalAttendanceRecords
                    ) * 100
                )
                : 0;

        $qrScans =
            AttendanceRecord::whereNotNull(
                'scanned_at'
            )->count();

        $recentAttendance =
            AttendanceRecord::with([
                'student.user',
                'session.subject',
            ])
                ->latest('created_at')
                ->take(5)
                ->get();

        return view(
            'admin.dashboard',
            compact(
                'studentsCount',
                'subjectsCount',
                'attendanceRate',
                'qrScans',
                'recentAttendance'
            )
        );
    })->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | تقارير الأدمن
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin-dashboard/reports',
        [ReportController::class, 'index']
    )->name('admin.reports.index');

    Route::get(
        '/admin-dashboard/reports/excel',
        [ReportController::class, 'excel']
    )->name('admin.reports.excel');

    Route::get(
        '/admin-dashboard/reports/pdf',
        [ReportController::class, 'pdf']
    )->name('admin.reports.pdf');

    Route::get(
        '/admin-dashboard/reports/warnings-excel',
        [ReportController::class, 'warningsExcel']
    )->name('admin.reports.warnings.excel');
});

/*
|--------------------------------------------------------------------------
| مسارات الدكتور
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:professor',
])
    ->prefix('professor')
    ->name('professor.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | لوحة الدكتور
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | مواد الدكتور
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/subjects',
            [SubjectController::class, 'index']
        )->name('subjects.index');

        Route::get(
            '/subjects/{subject}',
            [SubjectController::class, 'show']
        )->name('subjects.show');

        /*
        |--------------------------------------------------------------------------
        | جلسات الحضور
        |--------------------------------------------------------------------------
        */

        /*
         * قائمة الجلسات الفعالة والمنتهية.
         * يجب وضعه قبل مسار {session}.
         */
        Route::get(
            '/sessions',
            [AttendanceSessionController::class, 'index']
        )->name('sessions.index');

        Route::get(
            '/sessions/create',
            [AttendanceSessionController::class, 'create']
        )->name('sessions.create');

        Route::post(
            '/sessions',
            [AttendanceSessionController::class, 'store']
        )->name('sessions.store');

        Route::post(
            '/sessions/{session}/end',
            [AttendanceSessionController::class, 'end']
        )->name('sessions.end');
Route::post(
    '/sessions/{session}/qr',
    [AttendanceSessionController::class, 'refreshQr']
)->name('sessions.qr');
Route::get(
    '/attendance',
    [AttendanceController::class, 'index']
)->name('attendance.index');

Route::patch(
    '/attendance/{record}',
    [AttendanceController::class, 'update']
)->name('attendance.update');
Route::get(
    '/excuses',
    [ExcuseController::class, 'index']
)->name('excuses.index');

Route::patch(
    '/excuses/{excuse}/approve',
    [ExcuseController::class, 'approve']
)->name('excuses.approve');

Route::patch(
    '/excuses/{excuse}/reject',
    [ExcuseController::class, 'reject']
)->name('excuses.reject');
Route::get(
    '/reports',
    [ProfessorReportController::class, 'index']
)->name('reports.index');

Route::get(
    '/reports/excel',
    [ProfessorReportController::class, 'excel']
)->name('reports.excel');

Route::get(
    '/reports/pdf',
    [ProfessorReportController::class, 'pdf']
)->name('reports.pdf');

        /*
         * هذا المسار يجب أن يبقى آخر مسارات الجلسات
         * لأن {session} قيمة متغيرة.
         */
        Route::get(
            '/sessions/{session}',
            [AttendanceSessionController::class, 'show']
        )->name('sessions.show');
    });

/*
|--------------------------------------------------------------------------
| مسارات الطالب
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:student',
])->group(function () {
    Route::get('/student-scanner', function () {
        return view('student.scanner');
    })->name('student.scanner');
});

/*
|--------------------------------------------------------------------------
| الملف الشخصي
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| مسارات تسجيل الدخول
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';