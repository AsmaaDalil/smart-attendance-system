<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],

            'university_number' => [
                'required',
                'string',
                'max:50',
                'unique:students,university_number',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'academic_year' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'is_dormitory' => [
                'nullable',
                'boolean',
            ],
        ]);

        $user = DB::transaction(function () use (
            $request,
            $validated
        ): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(
                    $validated['password']
                ),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,

                'university_number' =>
                    $validated['university_number'],

                'phone' =>
                    $validated['phone'] ?? null,

                'address' =>
                    $validated['address'] ?? null,

                'academic_year' =>
                    $validated['academic_year'],

                'is_dormitory' =>
                    $request->boolean('is_dormitory'),

                'device_token' => null,
            ]);

            return $user;
        });

        event(new Registered($user));

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Your student account was created successfully. You can now log in.'
            );
    }
}