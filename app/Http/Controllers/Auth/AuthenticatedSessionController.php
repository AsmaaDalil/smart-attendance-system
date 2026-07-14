<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    // هاد السطر بيعمل التحقق، إذا فشل بيعمل Throw لـ ValidationException وبيرجعك لصفحة الـ Login
    $request->authenticate();

    $request->session()->regenerate();

    // هون التوجيه بناءً على الدور
    $user = Auth::user();
    if ($user->role === 'professor') {
        return redirect()->intended('/admin-dashboard');
    }
    return redirect()->intended('/student-scanner');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
