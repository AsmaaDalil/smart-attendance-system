<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display student profile
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request): View
    {
        $student = $request->user()?->student;

        abort_if(
            ! $student,
            404,
            'Student profile was not found.'
        );

        return view(
            'student.profile',
            compact('student')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update student information
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request
    ): RedirectResponse {
        $user = $request->user();
        $student = $user?->student;

        abort_if(
            ! $student,
            404,
            'Student profile was not found.'
        );

        $validated = $request->validateWithBag(
            'updateProfile',
            [
                'name' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'nullable',
                    'email',
                    'max:255',

                    Rule::unique(
                        'users',
                        'email'
                    )->ignore($user->id),
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'max:30',
                ],

                'address' => [
                    'nullable',
                    'string',
                    'max:500',
                ],

                'is_dormitory' => [
                    'required',
                    'boolean',
                ],
            ]
        );

        DB::transaction(function () use (
            $user,
            $student,
            $validated,
            $request
        ) {
            /*
             * Empty fields keep their previous values.
             */

            if ($request->filled('name')) {
                $user->name = $validated['name'];
            }

            if ($request->filled('email')) {
                if (
                    $user->email
                    !== $validated['email']
                ) {
                    $user->email_verified_at = null;
                }

                $user->email = $validated['email'];
            }

            if ($user->isDirty()) {
                $user->save();
            }

            $studentData = [
                'is_dormitory' =>
                    $request->boolean(
                        'is_dormitory'
                    ),
            ];

            if ($request->filled('phone')) {
                $studentData['phone'] =
                    $validated['phone'];
            }

            if ($request->filled('address')) {
                $studentData['address'] =
                    $validated['address'];
            }

            $student->update($studentData);
        });

        return redirect()
            ->route('student.profile.edit')
            ->with(
                'profile_status',
                'Profile information updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Update student password
    |--------------------------------------------------------------------------
    */

    public function updatePassword(
        Request $request
    ): RedirectResponse {
        $validated = $request->validateWithBag(
            'updatePassword',
            [
                'current_password' => [
                    'required',
                    'current_password',
                ],

                'password' => [
                    'required',
                    'confirmed',
                    Password::defaults(),
                ],
            ]
        );

        $request->user()->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        return redirect()
            ->route('student.profile.edit')
            ->with(
                'password_status',
                'Password updated successfully.'
            );
    }
}