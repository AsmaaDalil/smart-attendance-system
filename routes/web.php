<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'can:is-professor'])->group(function () {
    Route::get('/admin-dashboard', function () {
        return view('admin.dashboard'); 
    });
});

// مسار الطالب
Route::middleware(['auth'])->group(function () {
    Route::get('/student-scanner', function () {
        return view('student.scanner');
    });
});
require __DIR__.'/auth.php';
