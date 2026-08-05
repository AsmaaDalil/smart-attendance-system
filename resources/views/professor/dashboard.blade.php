<x-professor-layout>

    <div class="w-full min-w-0 max-w-none px-4 py-5 sm:px-6 sm:py-6 lg:p-8">

        {{-- Header --}}
        <header
            class="mb-6 flex items-start justify-between gap-4
                   sm:items-center"
        >
            <div class="min-w-0 ">
                <h1
                    class="text-2xl font-bold leading-tight text-[#184d42]
                           sm:text-3xl dark:text-white"
                >
                    Professor Dashboard
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage subjects and attendance sessions
                </p>
            </div>

            <div class="flex flex-shrink-0 items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p
                        class="max-w-44 truncate text-sm font-semibold
                               text-[#184d42] dark:text-white"
                    >
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Professor
                    </p>
                </div>

                {{-- Keep the original moon / theme button. --}}
                <x-theme-toggle />
            </div>
        </header>

        {{-- Welcome --}}
        <section
            class="relative mb-6 min-w-0 overflow-hidden rounded-3xl
                   bg-gradient-to-r from-[#184d42] to-[#24584d]
                   px-5 py-6 text-white shadow-lg sm:px-7 sm:py-7"
        >
            <div class="relative z-10 max-w-3xl">
                <p
                    class="text-[11px] font-semibold uppercase
                           tracking-[0.2em] text-white/55 sm:text-xs"
                >
                    Smart Attendance
                </p>

                <h2 class="mt-2 text-xl font-bold sm:text-2xl">
                    Welcome back, {{ auth()->user()->name }}
                </h2>

                <p class="mt-2 text-sm leading-6 text-white/75">
                    Start attendance sessions, display QR codes
                    and monitor students in real time.
                </p>
            </div>

            <div
                class="absolute -right-16 -top-20 h-52 w-52
                       rounded-full bg-[#d4a373]/15 sm:h-56 sm:w-56"
            ></div>
        </section>

        {{-- Statistics --}}
        <section
            class="mb-7 grid min-w-0 grid-cols-1 gap-4
                   sm:grid-cols-2 xl:grid-cols-3"
        >
            <article
                class="rounded-2xl border border-gray-100 bg-white
                       p-5 shadow-sm dark:border-white/10 dark:bg-[#191c1b]"
            >
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    My Subjects
                </p>

                <p class="mt-2 text-3xl font-bold text-[#184d42] dark:text-white">
                    {{ $subjects->count() }}
                </p>
            </article>

            <article
                class="rounded-2xl border border-gray-100 bg-white
                       p-5 shadow-sm dark:border-white/10 dark:bg-[#191c1b]"
            >
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Active Sessions
                </p>

                <p class="mt-2 text-3xl font-bold text-[#d4a373]">
                    {{ $activeSessions }}
                </p>
            </article>

            <article
                class="rounded-2xl border border-gray-100 bg-white p-5
                       shadow-sm sm:col-span-2 xl:col-span-1
                       dark:border-white/10 dark:bg-[#191c1b]"
            >
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Attendance Records
                </p>

                <p class="mt-2 text-3xl font-bold text-[#184d42] dark:text-white">
                    {{ $attendanceRecords }}
                </p>
            </article>
        </section>

        {{-- Professor subjects --}}
        <section class="mb-7">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-[#184d42] dark:text-white">
                    My Subjects
                </h2>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Subjects assigned to your account
                </p>
            </div>

            <div
                class="grid grid-cols-1 gap-4
                       md:grid-cols-2 2xl:grid-cols-3"
            >
                @forelse ($subjects as $subject)

                    <article
                        class="min-w-0 rounded-2xl border border-gray-100
                               bg-white p-5 shadow-sm transition
                               hover:-translate-y-1 hover:shadow-md
                               dark:border-white/10 dark:bg-[#191c1b]"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p
                                    class="text-xs font-semibold uppercase
                                           tracking-wider text-[#d4a373]"
                                >
                                    {{ $subject->subject_code }}
                                </p>

                                <h3
                                    class="mt-2 break-words font-semibold
                                           text-[#184d42] dark:text-white"
                                >
                                    {{ $subject->subject_name }}
                                </h3>
                            </div>

                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center
                                       justify-center rounded-xl bg-[#184d42]/10
                                       text-sm font-bold text-[#184d42]
                                       dark:bg-white/10 dark:text-white"
                            >
                                {{ $subject->students_count }}
                            </div>
                        </div>

                        <div
                            class="mt-5 flex flex-wrap items-center
                                   justify-between gap-2 border-t
                                   border-gray-100 pt-4 text-xs text-gray-500
                                   dark:border-white/10 dark:text-gray-400"
                        >
                            <span>
                                {{ $subject->students_count }} Students
                            </span>

                            <span>
                                {{ $subject->sessions_count }} Sessions
                            </span>
                        </div>
                    </article>

                @empty

                    <div
                        class="col-span-full rounded-2xl border border-dashed
                               border-gray-200 bg-white px-6 py-12 text-center
                               dark:border-white/10 dark:bg-[#191c1b]"
                    >
                        <p class="font-medium text-gray-600 dark:text-gray-300">
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
        <section
            class="min-w-0 overflow-hidden rounded-2xl border border-gray-100
                   bg-white shadow-sm dark:border-white/10 dark:bg-[#191c1b]"
        >
            <div
                class="border-b border-gray-100 px-5 py-5 sm:px-6
                       dark:border-white/10"
            >
                <h2 class="font-semibold text-[#184d42] dark:text-white">
                    Recent Sessions
                </h2>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Your latest attendance sessions
                </p>
            </div>

            {{-- Mobile: cards instead of a squeezed table. --}}
            <div class="space-y-3 p-4 sm:hidden">
                @forelse ($recentSessions as $session)

                    <article
                        class="rounded-2xl border border-gray-100 bg-gray-50
                               p-4 dark:border-white/10 dark:bg-white/[0.04]"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p
                                    class="break-words font-semibold
                                           text-[#184d42] dark:text-white"
                                >
                                    {{ $session->subject->subject_name }}
                                </p>

                                <p
                                    class="mt-1 break-words text-xs
                                           text-gray-500 dark:text-gray-400"
                                >
                                    #{{ $session->lecture_number }}
                                    — {{ $session->lecture_title }}
                                </p>
                            </div>

                            <span
                                @class([
                                    'flex-shrink-0 rounded-full px-3 py-1 text-xs font-medium',
                                    'bg-green-100 text-green-700' =>
                                        $session->status === 'Active',
                                    'bg-gray-200 text-gray-700' =>
                                        $session->status === 'Ended',
                                ])
                            >
                                {{ $session->status }}
                            </span>
                        </div>

                        <dl class="mt-4 grid grid-cols-2 gap-3 text-xs">
                            <div class="min-w-0">
                                <dt class="text-gray-400">Room</dt>
                                <dd
                                    class="mt-1 break-words font-medium
                                           text-gray-700 dark:text-gray-200"
                                >
                                    {{ $session->room->room_name }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-gray-400">Records</dt>
                                <dd
                                    class="mt-1 font-medium text-gray-700
                                           dark:text-gray-200"
                                >
                                    {{ $session->attendance_records_count }}
                                </dd>
                            </div>
                        </dl>
                    </article>

                @empty

                    <div class="py-8 text-center text-sm text-gray-500">
                        No attendance sessions yet.
                    </div>

                @endforelse
            </div>

            {{-- Tablet and laptop: keep the original table. --}}
            <div class="hidden overflow-x-auto sm:block">
                <table class="w-full min-w-[760px] text-sm">
                    <thead
                        class="bg-gray-50 text-left text-gray-600
                               dark:bg-white/[0.03] dark:text-gray-300"
                    >
                        <tr>
                            <th class="px-6 py-4 font-medium">Subject</th>
                            <th class="px-6 py-4 font-medium">Lecture</th>
                            <th class="px-6 py-4 font-medium">Room</th>
                            <th class="px-6 py-4 font-medium">Records</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                        </tr>
                    </thead>

                    <tbody
                        class="divide-y divide-gray-100
                               dark:divide-white/10"
                    >
                        @forelse ($recentSessions as $session)

                            <tr>
                                <td class="px-6 py-4">
                                    {{ $session->subject->subject_name }}
                                </td>

                                <td class="px-6 py-4">
                                    #{{ $session->lecture_number }}
                                    — {{ $session->lecture_title }}
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
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    No attendance sessions yet.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>

</x-professor-layout>
