<?php

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProfileController;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;

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
                'recentAttendance',
            )
        );
    })->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | التقارير
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
])->group(function () {
    Route::get('/professor-dashboard', function () {
        return view('professor.dashboard');
    })->name('professor.dashboard');
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