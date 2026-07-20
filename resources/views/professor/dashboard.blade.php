<x-professor-layout>

    <main class="p-6 lg:p-8">

        {{-- Header --}}
        <header class="mb-7 flex items-center
                       justify-between">

            <div>
                <h1 class="text-3xl font-bold
                           text-[#184d42]
                           dark:text-white">
                    Professor Dashboard
                </h1>

                <p class="mt-1 text-sm text-gray-500
                          dark:text-gray-400">
                    Manage subjects and attendance sessions
                </p>
            </div>

            <div class="flex items-center gap-3">

                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold
                              text-[#184d42]
                              dark:text-white">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-gray-500
                              dark:text-gray-400">
                        Professor
                    </p>
                </div>

                <x-theme-toggle />

            </div>

        </header>

        {{-- Welcome --}}
        <section
            class="relative mb-7 overflow-hidden
                   rounded-3xl bg-gradient-to-r
                   from-[#184d42] to-[#24584d]
                   px-7 py-7 text-white shadow-lg"
        >
            <div class="relative z-10">

                <p class="text-xs font-semibold uppercase
                          tracking-[0.2em] text-white/55">
                    Smart Attendance
                </p>

                <h2 class="mt-2 text-2xl font-bold">
                    Welcome back,
                    {{ auth()->user()->name }}
                </h2>

                <p class="mt-2 text-sm text-white/70">
                    Start attendance sessions, display QR codes
                    and monitor students in real time.
                </p>

            </div>

            <div
                class="absolute -right-14 -top-20
                       h-56 w-56 rounded-full
                       bg-[#d4a373]/15">
            </div>
        </section>

        {{-- Statistics --}}
        <section class="mb-7 grid grid-cols-1 gap-5
                        sm:grid-cols-3">

            <div class="rounded-2xl border border-gray-100
                        bg-white p-5 shadow-sm
                        dark:border-white/10
                        dark:bg-[#191c1b]">

                <p class="text-sm text-gray-500
                          dark:text-gray-400">
                    My Subjects
                </p>

                <p class="mt-2 text-3xl font-bold
                          text-[#184d42]
                          dark:text-white">
                    {{ $subjects->count() }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-100
                        bg-white p-5 shadow-sm
                        dark:border-white/10
                        dark:bg-[#191c1b]">

                <p class="text-sm text-gray-500
                          dark:text-gray-400">
                    Active Sessions
                </p>

                <p class="mt-2 text-3xl font-bold
                          text-[#d4a373]">
                    {{ $activeSessions }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-100
                        bg-white p-5 shadow-sm
                        dark:border-white/10
                        dark:bg-[#191c1b]">

                <p class="text-sm text-gray-500
                          dark:text-gray-400">
                    Attendance Records
                </p>

                <p class="mt-2 text-3xl font-bold
                          text-[#184d42]
                          dark:text-white">
                    {{ $attendanceRecords }}
                </p>
            </div>

        </section>

        {{-- Professor subjects --}}
        <section class="mb-7">

            <div class="mb-4 flex items-center
                        justify-between">

                <div>
                    <h2 class="text-lg font-semibold
                               text-[#184d42]
                               dark:text-white">
                        My Subjects
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Subjects assigned to your account
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 gap-5
                        md:grid-cols-2 xl:grid-cols-3">

                @forelse ($subjects as $subject)

                    <article
                        class="rounded-2xl border
                               border-gray-100 bg-white
                               p-5 shadow-sm transition
                               hover:-translate-y-1
                               hover:shadow-md
                               dark:border-white/10
                               dark:bg-[#191c1b]"
                    >
                        <div class="flex items-start
                                    justify-between">

                            <div>
                                <p class="text-xs font-semibold
                                          uppercase tracking-wider
                                          text-[#d4a373]">
                                    {{ $subject->subject_code }}
                                </p>

                                <h3 class="mt-2 font-semibold
                                           text-[#184d42]
                                           dark:text-white">
                                    {{ $subject->subject_name }}
                                </h3>
                            </div>

                            <div
                                class="flex h-10 w-10
                                       items-center justify-center
                                       rounded-xl bg-[#184d42]/10
                                       text-sm font-bold
                                       text-[#184d42]
                                       dark:bg-white/10
                                       dark:text-white"
                            >
                                {{ $subject->students_count }}
                            </div>
                        </div>

                        <div class="mt-5 flex items-center
                                    justify-between border-t
                                    border-gray-100 pt-4
                                    text-xs text-gray-500
                                    dark:border-white/10">

                            <span>
                                {{ $subject->students_count }}
                                Students
                            </span>

                            <span>
                                {{ $subject->sessions_count }}
                                Sessions
                            </span>
                        </div>
                    </article>

                @empty

                    <div class="col-span-full rounded-2xl
                                border border-dashed
                                border-gray-200 bg-white
                                px-6 py-12 text-center
                                dark:border-white/10
                                dark:bg-[#191c1b]">

                        <p class="font-medium text-gray-600
                                  dark:text-gray-300">
                            No subjects assigned
                        </p>

                        <p class="mt-1 text-sm text-gray-400">
                            Ask the administrator to assign
                            a subject to your account.
                        </p>
                    </div>

                @endforelse

            </div>
        </section>

        {{-- Recent sessions --}}
        <section class="overflow-hidden rounded-2xl
                        border border-gray-100 bg-white
                        shadow-sm dark:border-white/10
                        dark:bg-[#191c1b]">

            <div class="border-b border-gray-100
                        px-6 py-5 dark:border-white/10">

                <h2 class="font-semibold text-[#184d42]
                           dark:text-white">
                    Recent Sessions
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Your latest attendance sessions
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-left
                                  dark:bg-white/[0.03]">
                        <tr>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">Lecture</th>
                            <th class="px-6 py-4">Room</th>
                            <th class="px-6 py-4">Records</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100
                                  dark:divide-white/10">

                        @forelse ($recentSessions as $session)

                            <tr>
                                <td class="px-6 py-4">
                                    {{ $session->subject->subject_name }}
                                </td>

                                <td class="px-6 py-4">
                                    #{{ $session->lecture_number }}
                                    —
                                    {{ $session->lecture_title }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $session->room->room_name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $session->attendance_records_count }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        @class([
                                            'rounded-full px-3 py-1 text-xs font-medium',

                                            'bg-green-100 text-green-700' =>
                                                $session->status === 'Active',

                                            'bg-gray-100 text-gray-700' =>
                                                $session->status === 'Ended',
                                        ])
                                    >
                                        {{ $session->status }}
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="px-6 py-12 text-center
                                           text-gray-500"
                                >
                                    No attendance sessions yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>
        </section>

    </main>

</x-professor-layout>