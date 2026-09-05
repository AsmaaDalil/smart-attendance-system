<x-student-layout>
    <main class="p-6 lg:p-8">

        <div class="mx-auto max-w-7xl">

            {{-- Page Header --}}
            <div class="mb-7">
                <p class="text-sm font-semibold uppercase
                          tracking-[0.18em] text-[#d4a373]">
                    Student Portal
                </p>

                <h1 class="mt-2 text-3xl font-bold
                           text-[#184d42] dark:text-white">
                    My Profile
                </h1>

                <p class="mt-2 text-sm text-gray-500
                          dark:text-gray-400">
                    Review your academic information and
                    manage your account securely.
                </p>
            </div>

            {{-- Welcome Banner --}}
            <section
                class="relative mb-7 overflow-hidden
                       rounded-3xl bg-[#184d42]
                       px-7 py-7 text-white shadow-sm"
            >
                <div class="relative z-10">
                    <p class="text-xs font-semibold uppercase
                              tracking-[0.25em] text-white/60">
                        Smart Attendance
                    </p>

                    <h2 class="mt-3 text-2xl font-bold">
                        Account and student information
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm
                              leading-6 text-white/75">
                        You can update your contact information
                        and password. Academic identifiers and
                        device information are protected.
                    </p>
                </div>

                <div class="absolute -right-14 -top-20
                            h-56 w-56 rounded-full bg-white/10">
                </div>
            </section>

            {{-- Success Messages --}}
            @if (session('profile_status'))
                <div class="mb-6 rounded-2xl border
                            border-emerald-200 bg-emerald-50
                            px-5 py-4 text-sm font-semibold
                            text-emerald-700
                            dark:border-emerald-500/20
                            dark:bg-emerald-500/10
                            dark:text-emerald-300">
                    {{ session('profile_status') }}
                </div>
            @endif

            @if (session('password_status'))
                <div class="mb-6 rounded-2xl border
                            border-emerald-200 bg-emerald-50
                            px-5 py-4 text-sm font-semibold
                            text-emerald-700
                            dark:border-emerald-500/20
                            dark:bg-emerald-500/10
                            dark:text-emerald-300">
                    {{ session('password_status') }}
                </div>
            @endif

            {{-- Student Summary --}}
            <section class="mb-7 grid gap-4
                            sm:grid-cols-2 xl:grid-cols-4">

                <article
                    class="rounded-3xl border border-gray-100
                           bg-white p-5 shadow-sm
                           dark:border-white/10
                           dark:bg-[#191c1b]"
                >
                    <p class="text-sm text-gray-500
                              dark:text-gray-400">
                        University Number
                    </p>

                    <p class="mt-2 text-xl font-bold
                              text-[#184d42] dark:text-white">
                        {{
                            $student->university_number
                            ?? 'Not available'
                        }}
                    </p>
                </article>

                <article
                    class="rounded-3xl border border-gray-100
                           bg-white p-5 shadow-sm
                           dark:border-white/10
                           dark:bg-[#191c1b]"
                >
                    <p class="text-sm text-gray-500
                              dark:text-gray-400">
                        Academic Year
                    </p>

                    <p class="mt-2 text-xl font-bold
                              text-[#184d42] dark:text-white">
                        {{
                            $student->academic_year
                            ?? 'Not available'
                        }}
                    </p>
                </article>

                <article
                    class="rounded-3xl border border-gray-100
                           bg-white p-5 shadow-sm
                           dark:border-white/10
                           dark:bg-[#191c1b]"
                >
                    <p class="text-sm text-gray-500
                              dark:text-gray-400">
                        Dormitory Status
                    </p>

                    <p
                        class="mt-2 text-xl font-bold
                               {{
                                   $student->is_dormitory
                                       ? 'text-emerald-600 dark:text-emerald-400'
                                       : 'text-gray-600 dark:text-gray-300'
                               }}"
                    >
                        {{
                            $student->is_dormitory
                                ? 'Dormitory Student'
                                : 'Not Dormitory'
                        }}
                    </p>
                </article>

                <article
                    class="rounded-3xl border border-gray-100
                           bg-white p-5 shadow-sm
                           dark:border-white/10
                           dark:bg-[#191c1b]"
                >
                    <p class="text-sm text-gray-500
                              dark:text-gray-400">
                        Device Status
                    </p>

                    <p
                        class="mt-2 text-xl font-bold
                               {{
                                   $student->device_token
                                       ? 'text-emerald-600 dark:text-emerald-400'
                                       : 'text-amber-600 dark:text-amber-400'
                               }}"
                    >
                        {{
                            $student->device_token
                                ? 'Registered'
                                : 'Not registered'
                        }}
                    </p>
                </article>

            </section>

            <div class="grid gap-6 xl:grid-cols-2">

                {{-- Profile Information --}}
                <section
                    class="rounded-3xl border border-gray-100
                           bg-white p-6 shadow-sm
                           dark:border-white/10
                           dark:bg-[#191c1b]"
                >
                    <div class="mb-6">
                        <h2 class="text-xl font-bold
                                   text-[#184d42] dark:text-white">
                            Profile Information
                        </h2>

                        <p class="mt-1 text-sm text-gray-500
                                  dark:text-gray-400">
                            Update your personal and contact
                            information.
                        </p>
                    </div>

                    <form
                        method="POST"
                        action="{{ route(
                            'student.profile.update'
                        ) }}"
                        class="space-y-5"
                    >
                        @csrf
                        @method('PATCH')

                        {{-- Name --}}
                        <div>
                            <label
                                for="name"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700
                                       dark:text-gray-200"
                            >
                                Full Name
                            </label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="w-full rounded-xl
                                       border-gray-200 bg-white
                                       text-gray-800
                                       placeholder:text-gray-400
                                       focus:border-[#184d42]
                                       focus:ring-[#184d42]
                                       dark:border-white/10
                                       dark:bg-[#101312]
                                       dark:text-white
                                       dark:placeholder:text-gray-500"
                            >

                            @error('name', 'updateProfile')
                                <p class="mt-2 text-sm text-red-600
                                          dark:text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label
                                for="email"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700
                                       dark:text-gray-200"
                            >
                                Email Address
                            </label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="w-full rounded-xl
                                       border-gray-200 bg-white
                                       text-gray-800
                                       placeholder:text-gray-400
                                       focus:border-[#184d42]
                                       focus:ring-[#184d42]
                                       dark:border-white/10
                                       dark:bg-[#101312]
                                       dark:text-white
                                       dark:placeholder:text-gray-500"
                            >

                            @error('email', 'updateProfile')
                                <p class="mt-2 text-sm text-red-600
                                          dark:text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label
                                for="phone"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700
                                       dark:text-gray-200"
                            >
                                Phone Number
                            </label>

                            <input
                                id="phone"
                                name="phone"
                                type="text"
                                autocomplete="tel"
                                value="{{ old('phone', $student->phone) }}"
                                class="w-full rounded-xl
                                       border-gray-200 bg-white
                                       text-gray-800
                                       placeholder:text-gray-400
                                       focus:border-[#184d42]
                                       focus:ring-[#184d42]
                                       dark:border-white/10
                                       dark:bg-[#101312]
                                       dark:text-white
                                       dark:placeholder:text-gray-500"
                            >

                            @error('phone', 'updateProfile')
                                <p class="mt-2 text-sm text-red-600
                                          dark:text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div>
                            <label
                                for="address"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700
                                       dark:text-gray-200"
                            >
                                Address
                            </label>

                            <textarea
                                id="address"
                                name="address"
                                rows="4"
                                maxlength="500"
                                class="w-full resize-none rounded-xl
                                       border-gray-200 bg-white
                                       text-gray-800
                                       placeholder:text-gray-400
                                       focus:border-[#184d42]
                                       focus:ring-[#184d42]
                                       dark:border-white/10
                                       dark:bg-[#101312]
                                       dark:text-white
                                       dark:placeholder:text-gray-500"
                            >{{ old('address', $student->address) }}</textarea>

                            @error('address', 'updateProfile')
                                <p class="mt-2 text-sm text-red-600
                                          dark:text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Dormitory --}}
                        <div
                            class="rounded-2xl border border-gray-200
                                   p-4 dark:border-white/10"
                        >
                            <input
                                type="hidden"
                                name="is_dormitory"
                                value="0"
                            >

                            <label
                                for="is_dormitory"
                                class="flex cursor-pointer
                                       items-start gap-3"
                            >
                                <input
                                    id="is_dormitory"
                                    name="is_dormitory"
                                    type="checkbox"
                                    value="1"
                                    @checked(
                                        old(
                                            'is_dormitory',
                                            $student->is_dormitory
                                        )
                                    )
                                    class="mt-1 rounded
                                           border-gray-300
                                           text-[#184d42]
                                           focus:ring-[#184d42]"
                                >

                                <span>
                                    <span
                                        class="block text-sm
                                               font-semibold
                                               text-gray-700
                                               dark:text-gray-200"
                                    >
                                        University Dormitory Student
                                    </span>

                                    <span
                                        class="mt-1 block text-xs
                                               text-gray-500
                                               dark:text-gray-400"
                                    >
                                        Enable this option when you
                                        currently live in university
                                        housing.
                                    </span>
                                </span>
                            </label>

                            @error(
                                'is_dormitory',
                                'updateProfile'
                            )
                                <p class="mt-2 text-sm text-red-600
                                          dark:text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="rounded-xl bg-[#184d42]
                                   px-6 py-3 text-sm font-semibold
                                   text-white transition
                                   hover:bg-[#24584d]"
                        >
                            Save Changes
                        </button>
                    </form>
                </section>

                {{-- Update Password --}}
                <section
                    class="rounded-3xl border border-gray-100
                           bg-white p-6 shadow-sm
                           dark:border-white/10
                           dark:bg-[#191c1b]"
                >
                    <div class="mb-6">
                        <h2 class="text-xl font-bold
                                   text-[#184d42] dark:text-white">
                            Update Password
                        </h2>

                        <p class="mt-1 text-sm text-gray-500
                                  dark:text-gray-400">
                            Use a strong password that you do
                            not use on other services.
                        </p>
                    </div>

                    <form
                        method="POST"
                        action="{{ route(
                            'student.profile.password.update'
                        ) }}"
                        class="space-y-5"
                    >
                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <div>
                            <label
                                for="current_password"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700
                                       dark:text-gray-200"
                            >
                                Current Password
                            </label>

                            <input
                                id="current_password"
                                name="current_password"
                                type="password"
                                required
                                autocomplete="current-password"
                                class="w-full rounded-xl
                                       border-gray-200 bg-white
                                       text-gray-800
                                       focus:border-[#184d42]
                                       focus:ring-[#184d42]
                                       dark:border-white/10
                                       dark:bg-[#101312]
                                       dark:text-white"
                            >

                            @error(
                                'current_password',
                                'updatePassword'
                            )
                                <p class="mt-2 text-sm text-red-600
                                          dark:text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label
                                for="password"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700
                                       dark:text-gray-200"
                            >
                                New Password
                            </label>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-xl
                                       border-gray-200 bg-white
                                       text-gray-800
                                       focus:border-[#184d42]
                                       focus:ring-[#184d42]
                                       dark:border-white/10
                                       dark:bg-[#101312]
                                       dark:text-white"
                            >

                            @error(
                                'password',
                                'updatePassword'
                            )
                                <p class="mt-2 text-sm text-red-600
                                          dark:text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-medium
                                       text-gray-700
                                       dark:text-gray-200"
                            >
                                Confirm New Password
                            </label>

                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-xl
                                       border-gray-200 bg-white
                                       text-gray-800
                                       focus:border-[#184d42]
                                       focus:ring-[#184d42]
                                       dark:border-white/10
                                       dark:bg-[#101312]
                                       dark:text-white"
                            >
                        </div>

                        <div
                            class="rounded-2xl border
                                   border-amber-200 bg-amber-50
                                   px-5 py-4
                                   dark:border-amber-500/20
                                   dark:bg-amber-500/10"
                        >
                            <p class="font-semibold text-amber-700
                                      dark:text-amber-300">
                                Security Notice
                            </p>

                            <p class="mt-1 text-sm text-amber-600
                                      dark:text-amber-400">
                                Your current password is required
                                before setting a new password.
                            </p>
                        </div>

                        <button
                            type="submit"
                            class="rounded-xl bg-[#184d42]
                                   px-6 py-3 text-sm font-semibold
                                   text-white transition
                                   hover:bg-[#24584d]"
                        >
                            Update Password
                        </button>
                    </form>
                </section>

            </div>
        </div>
    </main>
</x-student-layout>