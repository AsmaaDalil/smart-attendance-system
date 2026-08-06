<x-student-layout>
    <main class="p-4 sm:p-6 lg:p-8">

        <div class="mx-auto max-w-7xl">

            {{-- Header Exactly Like Professor Dashboard --}}
            <header
                class="mb-6 flex items-start
                       justify-between gap-4
                       sm:items-center"
            >
                <div class="min-w-0">
                    <h1
                        class="text-2xl font-bold leading-tight
                               text-[#184d42] sm:text-3xl
                               dark:text-white"
                    >
                        Dashboard
                    </h1>

                    <p
                        class="mt-1 max-w-xs text-sm
                               leading-6 text-gray-500
                               sm:max-w-none
                               dark:text-gray-400"
                    >
                        Monitor your attendance, active lectures
                        and academic warning status.
                    </p>
                </div>

                <div
                    class="flex flex-shrink-0
                           items-center gap-3"
                >
                    <div
                        class="hidden text-right
                               sm:block"
                    >
                        <p
                            class="max-w-44 truncate
                                   text-sm font-semibold
                                   text-[#184d42]
                                   dark:text-white"
                        >
                            {{ auth()->user()->name }}
                        </p>

                        <p
                            class="text-xs text-gray-500
                                   dark:text-gray-400"
                        >
                            Student
                        </p>
                    </div>

                    <x-theme-toggle />
                </div>
            </header>

            {{-- Scan QR Button --}}
            <div class="mb-6 flex justify-end">
                <a
                    href="{{ route('student.scanner') }}"
                    class="inline-flex items-center
                           justify-center rounded-xl
                           bg-[#184d42] px-5 py-3
                           text-sm font-semibold text-white
                           transition hover:bg-[#24584d]"
                >
                    Scan QR Code
                </a>
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
                        Welcome back, {{ auth()->user()->name }}
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm
                              leading-6 text-white/75">
                        Follow your attendance percentage,
                        absence warnings and active lecture
                        sessions from one place.
                    </p>
                </div>

                <div class="absolute -right-14 -top-20
                            h-56 w-56 rounded-full bg-white/10">
                </div>

                <div class="absolute -bottom-24 right-28
                            h-44 w-44 rounded-full bg-white/5">
                </div>
            </section>

            {{-- Student Information --}}
            <section class="mb-6 grid gap-4
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
                        {{ $student->university_number }}
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
                        Year {{ $student->academic_year }}
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
                        Registered Subjects
                    </p>

                    <p class="mt-2 text-xl font-bold
                              text-[#184d42] dark:text-white">
                        {{ $subjectsCount }}
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
                        Pending Excuses
                    </p>

                    <p class="mt-2 text-xl font-bold
                              {{
                                  $pendingExcusesCount > 0
                                      ? 'text-amber-600 dark:text-amber-400'
                                      : 'text-[#184d42] dark:text-white'
                              }}">
                        {{ $pendingExcusesCount }}
                    </p>
                </article>

            </section>

            {{-- General Attendance Statistics --}}
            <section class="mb-6 grid grid-cols-2 gap-4
                            lg:grid-cols-3 xl:grid-cols-6">

                <article
                    class="col-span-2 rounded-3xl border
                           border-gray-100 bg-white p-5 shadow-sm
                           dark:border-white/10
                           dark:bg-[#191c1b]
                           lg:col-span-1"
                >
                    <p class="text-sm text-gray-500
                              dark:text-gray-400">
                        Attendance Rate
                    </p>

                    <p class="mt-2 text-3xl font-bold
                              text-[#184d42] dark:text-white">
                        {{ $attendanceRate }}%
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
                        Absence Rate
                    </p>

                    <p class="mt-2 text-3xl font-bold
                              {{
                                  $absenceRate >= 25
                                      ? 'text-red-600 dark:text-red-400'
                                      : (
                                          $absenceRate >= 15
                                              ? 'text-amber-600 dark:text-amber-400'
                                              : 'text-emerald-600 dark:text-emerald-400'
                                      )
                              }}">
                        {{ $absenceRate }}%
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
                        Present
                    </p>

                    <p class="mt-2 text-3xl font-bold
                              text-emerald-600
                              dark:text-emerald-400">
                        {{ $presentCount }}
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
                        Late
                    </p>

                    <p class="mt-2 text-3xl font-bold
                              text-amber-600
                              dark:text-amber-400">
                        {{ $lateCount }}
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
                        Absent
                    </p>

                    <p class="mt-2 text-3xl font-bold
                              text-red-600 dark:text-red-400">
                        {{ $absentCount }}
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
                        Excused
                    </p>

                    <p class="mt-2 text-3xl font-bold
                              text-blue-600 dark:text-blue-400">
                        {{ $excusedCount }}
                    </p>
                </article>

            </section>

            {{-- Academic Warnings --}}
            @if ($atRiskSubjects->isNotEmpty())

                <section
                    class="mb-6 rounded-3xl border border-red-200
                           bg-red-50 p-6
                           dark:border-red-500/20
                           dark:bg-red-500/10"
                >
                    <div class="flex flex-col justify-between gap-4
                                sm:flex-row sm:items-start">

                        <div>
                            <h3 class="text-lg font-bold text-red-700
                                       dark:text-red-300">
                                Attendance Risk Warning
                            </h3>

                            <p class="mt-1 text-sm text-red-600
                                      dark:text-red-400">
                                Your absence rate reached 25% or more
                                in the following subjects.
                            </p>
                        </div>

                        <span
                            class="w-fit rounded-full bg-red-100
                                   px-4 py-2 text-xs font-bold
                                   text-red-700
                                   dark:bg-red-500/20
                                   dark:text-red-300"
                        >
                            {{ $atRiskSubjects->count() }}
                            At Risk
                        </span>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-2">

                        @foreach ($atRiskSubjects as $statistic)

                            <div
                                class="rounded-2xl border border-red-200
                                       bg-white/70 p-4
                                       dark:border-red-500/20
                                       dark:bg-black/10"
                            >
                                <div class="flex items-center
                                            justify-between gap-4">

                                    <div>
                                        <p class="font-bold text-red-700
                                                  dark:text-red-300">
                                            {{
                                                $statistic['subject']
                                                    ->subject_name
                                            }}
                                        </p>

                                        <p class="mt-1 text-xs
                                                  text-red-600
                                                  dark:text-red-400">
                                            {{
                                                $statistic[
                                                    'absent_count'
                                                ]
                                            }}
                                            absence records
                                        </p>
                                    </div>

                                    <p class="text-2xl font-bold
                                              text-red-700
                                              dark:text-red-300">
                                        {{
                                            $statistic[
                                                'absence_rate'
                                            ]
                                        }}%
                                    </p>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </section>

            @endif

            @if ($warningSubjects->isNotEmpty())

                <section
                    class="mb-6 rounded-3xl border border-amber-200
                           bg-amber-50 p-6
                           dark:border-amber-500/20
                           dark:bg-amber-500/10"
                >
                    <div class="flex flex-col justify-between gap-4
                                sm:flex-row sm:items-start">

                        <div>
                            <h3 class="text-lg font-bold
                                       text-amber-700
                                       dark:text-amber-300">
                                Attendance Warning
                            </h3>

                            <p class="mt-1 text-sm text-amber-600
                                      dark:text-amber-400">
                                Improve your attendance in the
                                following subjects before reaching
                                the risk level.
                            </p>
                        </div>

                        <span
                            class="w-fit rounded-full bg-amber-100
                                   px-4 py-2 text-xs font-bold
                                   text-amber-700
                                   dark:bg-amber-500/20
                                   dark:text-amber-300"
                        >
                            {{ $warningSubjects->count() }}
                            Warning
                        </span>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-2">

                        @foreach ($warningSubjects as $statistic)

                            <div
                                class="rounded-2xl border
                                       border-amber-200
                                       bg-white/70 p-4
                                       dark:border-amber-500/20
                                       dark:bg-black/10"
                            >
                                <div class="flex items-center
                                            justify-between gap-4">

                                    <div>
                                        <p class="font-bold
                                                  text-amber-700
                                                  dark:text-amber-300">
                                            {{
                                                $statistic['subject']
                                                    ->subject_name
                                            }}
                                        </p>

                                        <p class="mt-1 text-xs
                                                  text-amber-600
                                                  dark:text-amber-400">
                                            Please attend the upcoming
                                            lectures.
                                        </p>
                                    </div>

                                    <p class="text-2xl font-bold
                                              text-amber-700
                                              dark:text-amber-300">
                                        {{
                                            $statistic[
                                                'absence_rate'
                                            ]
                                        }}%
                                    </p>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </section>

            @endif

            @if (! $hasWarning)

                <section
                    class="mb-6 rounded-3xl border
                           border-emerald-200 bg-emerald-50 p-6
                           dark:border-emerald-500/20
                           dark:bg-emerald-500/10"
                >
                    <h3 class="text-lg font-bold
                               text-emerald-700
                               dark:text-emerald-300">
                        Safe Attendance Status
                    </h3>

                    <p class="mt-1 text-sm text-emerald-600
                              dark:text-emerald-400">
                        You currently have no subjects with
                        attendance warnings.
                    </p>
                </section>

            @endif

            {{-- Subject Statistics --}}
            <section
                class="mb-6 rounded-3xl border border-gray-100
                       bg-white p-6 shadow-sm
                       dark:border-white/10
                       dark:bg-[#191c1b]"
            >
                <div class="flex flex-col justify-between gap-4
                            sm:flex-row sm:items-center">

                    <div>
                        <h3 class="text-xl font-bold
                                   text-[#184d42] dark:text-white">
                            Attendance by Subject
                        </h3>

                        <p class="mt-1 text-sm text-gray-500
                                  dark:text-gray-400">
                            Review the attendance and absence
                            percentage for every subject.
                        </p>
                    </div>

                    <a
                        href="{{ route(
                            'student.attendance.index'
                        ) }}"
                        class="text-sm font-semibold text-[#184d42]
                               hover:underline dark:text-[#d4a373]"
                    >
                        View Full History
                    </a>
                </div>

                <div class="mt-6 grid gap-5
                            md:grid-cols-2 xl:grid-cols-3">

                    @forelse ($subjectStatistics as $statistic)

                        @php
                            $riskClasses = match (
                                $statistic['risk_level']
                            ) {
                                'at_risk' =>
                                    'bg-red-100 text-red-700 '
                                    .'dark:bg-red-500/10 '
                                    .'dark:text-red-300',

                                'warning' =>
                                    'bg-amber-100 text-amber-700 '
                                    .'dark:bg-amber-500/10 '
                                    .'dark:text-amber-300',

                                default =>
                                    'bg-emerald-100 '
                                    .'text-emerald-700 '
                                    .'dark:bg-emerald-500/10 '
                                    .'dark:text-emerald-300',
                            };

                            $attendanceProgress = min(
                                100,
                                max(
                                    0,
                                    (float) (
                                        $statistic['attendance_rate']
                                        ?? 0
                                    )
                                )
                            );
                        @endphp

                        <article
                            class="rounded-3xl border
                                   border-gray-100 p-5
                                   dark:border-white/10"
                        >
                            <div class="flex items-start
                                        justify-between gap-4">

                                <div>
                                    <span
                                        class="rounded-lg
                                               bg-[#d4a373]/15
                                               px-3 py-1 text-xs
                                               font-bold
                                               text-[#b77d51]"
                                    >
                                        {{
                                            $statistic['subject']
                                                ->subject_code
                                            ?? 'SUBJECT'
                                        }}
                                    </span>

                                    <h4 class="mt-3 font-bold
                                               text-[#184d42]
                                               dark:text-white">
                                        {{
                                            $statistic['subject']
                                                ->subject_name
                                        }}
                                    </h4>
                                </div>

                                <span
                                    class="rounded-full px-3 py-1
                                           text-xs font-bold
                                           {{ $riskClasses }}"
                                >
                                    {{
                                        $statistic[
                                            'risk_label'
                                        ]
                                    }}
                                </span>
                            </div>

                            <div class="mt-5 flex items-end
                                        justify-between gap-4">

                                <div>
                                    <p class="text-xs text-gray-500
                                              dark:text-gray-400">
                                        Attendance
                                    </p>

                                    <p class="mt-1 text-2xl font-bold
                                              text-[#184d42]
                                              dark:text-white">
                                        {{
                                            $statistic[
                                                'attendance_rate'
                                            ]
                                        }}%
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs text-gray-500
                                              dark:text-gray-400">
                                        Absence
                                    </p>

                                    <p class="mt-1 text-lg font-bold
                                              text-gray-700
                                              dark:text-gray-200">
                                        {{
                                            $statistic[
                                                'absence_rate'
                                            ]
                                        }}%
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 h-2 overflow-hidden
                                        rounded-full bg-gray-100
                                        dark:bg-white/10">

                                <div
                                    class="h-full rounded-full
                                           bg-[#184d42]"
                                    @style(["width: {$attendanceProgress}%"])
                                ></div>
                            </div>

                            <div class="mt-5 grid grid-cols-4 gap-2
                                        text-center">

                                <div class="rounded-xl
                                            bg-emerald-50 p-2
                                            dark:bg-emerald-500/10">
                                    <p class="font-bold
                                              text-emerald-700
                                              dark:text-emerald-300">
                                        {{
                                            $statistic[
                                                'present_count'
                                            ]
                                        }}
                                    </p>

                                    <p class="text-[10px]
                                              text-emerald-600
                                              dark:text-emerald-400">
                                        Present
                                    </p>
                                </div>

                                <div class="rounded-xl
                                            bg-amber-50 p-2
                                            dark:bg-amber-500/10">
                                    <p class="font-bold
                                              text-amber-700
                                              dark:text-amber-300">
                                        {{
                                            $statistic[
                                                'late_count'
                                            ]
                                        }}
                                    </p>

                                    <p class="text-[10px]
                                              text-amber-600
                                              dark:text-amber-400">
                                        Late
                                    </p>
                                </div>

                                <div class="rounded-xl
                                            bg-red-50 p-2
                                            dark:bg-red-500/10">
                                    <p class="font-bold text-red-700
                                              dark:text-red-300">
                                        {{
                                            $statistic[
                                                'absent_count'
                                            ]
                                        }}
                                    </p>

                                    <p class="text-[10px]
                                              text-red-600
                                              dark:text-red-400">
                                        Absent
                                    </p>
                                </div>

                                <div class="rounded-xl
                                            bg-blue-50 p-2
                                            dark:bg-blue-500/10">
                                    <p class="font-bold text-blue-700
                                              dark:text-blue-300">
                                        {{
                                            $statistic[
                                                'excused_count'
                                            ]
                                        }}
                                    </p>

                                    <p class="text-[10px]
                                              text-blue-600
                                              dark:text-blue-400">
                                        Excused
                                    </p>
                                </div>

                            </div>
                        </article>

                    @empty

                        <div class="col-span-full rounded-2xl
                                    bg-gray-50 px-6 py-10
                                    text-center
                                    dark:bg-white/[0.03]">

                            <p class="font-semibold text-gray-600
                                      dark:text-gray-300">
                                You are not registered in any
                                subjects yet.
                            </p>
                        </div>

                    @endforelse
                </div>
            </section>

            {{-- Active Lectures --}}
            <section
                class="mb-6 rounded-3xl border border-gray-100
                       bg-white p-6 shadow-sm
                       dark:border-white/10
                       dark:bg-[#191c1b]"
            >
                <div class="flex flex-col justify-between gap-4
                            sm:flex-row sm:items-center">

                    <div>
                        <h3 class="text-xl font-bold
                                   text-[#184d42] dark:text-white">
                            Active Lectures
                        </h3>

                        <p class="mt-1 text-sm text-gray-500
                                  dark:text-gray-400">
                            Lectures currently open for attendance.
                        </p>
                    </div>

                    <span
                        class="w-fit rounded-full bg-emerald-100
                               px-4 py-2 text-sm font-semibold
                               text-emerald-700
                               dark:bg-emerald-500/10
                               dark:text-emerald-300"
                    >
                        {{ $activeSessions->count() }} Active
                    </span>
                </div>

                <div class="mt-5 space-y-4">

                    @forelse ($activeSessions as $activeSession)

                        <article
                            class="rounded-2xl border
                                   border-gray-100 p-5
                                   dark:border-white/10"
                        >
                            <div class="grid gap-4
                                        sm:grid-cols-2
                                        xl:grid-cols-4">

                                <div>
                                    <p class="text-xs text-gray-500
                                              dark:text-gray-400">
                                        Subject
                                    </p>

                                    <p class="mt-1 font-semibold
                                              text-gray-800
                                              dark:text-white">
                                        {{
                                            $activeSession
                                                ->subject
                                                ?->subject_name
                                            ?? 'Unknown Subject'
                                        }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500
                                              dark:text-gray-400">
                                        Lecture
                                    </p>

                                    <p class="mt-1 font-semibold
                                              text-gray-800
                                              dark:text-white">
                                        {{
                                            $activeSession
                                                ->lecture_number
                                        }}
                                        —
                                        {{
                                            $activeSession
                                                ->lecture_title
                                        }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500
                                              dark:text-gray-400">
                                        Room
                                    </p>

                                    <p class="mt-1 font-semibold
                                              text-gray-800
                                              dark:text-white">
                                        {{
                                            $activeSession
                                                ->room
                                                ?->room_name
                                            ?? 'Not assigned'
                                        }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500
                                              dark:text-gray-400">
                                        Ends At
                                    </p>

                                    <p class="mt-1 font-semibold
                                              text-gray-800
                                              dark:text-white">
                                        {{
                                            $activeSession
                                                ->end_time
                                                ?->format('H:i')
                                            ?? '—'
                                        }}
                                    </p>
                                </div>
                            </div>

                            <a
                                href="{{ route(
                                    'student.scanner'
                                ) }}"
                                class="mt-5 block w-full
                                       rounded-xl bg-[#184d42]
                                       px-5 py-3 text-center
                                       text-sm font-semibold
                                       text-white transition
                                       hover:bg-[#24584d]"
                            >
                                Open QR Scanner
                            </a>
                        </article>

                    @empty

                        <div
                            class="rounded-2xl bg-gray-50
                                   px-6 py-10 text-center
                                   dark:bg-white/[0.03]"
                        >
                            <p class="font-semibold text-gray-600
                                      dark:text-gray-300">
                                No active lectures now
                            </p>

                            <p class="mt-1 text-sm text-gray-500
                                      dark:text-gray-400">
                                Active lectures will appear here
                                when your professor starts a session.
                            </p>
                        </div>

                    @endforelse
                </div>
            </section>

            {{-- Recent Attendance --}}
            <section
                class="overflow-hidden rounded-3xl border
                       border-gray-100 bg-white shadow-sm
                       dark:border-white/10
                       dark:bg-[#191c1b]"
            >
                <div class="flex flex-col justify-between gap-4
                            border-b border-gray-100 p-6
                            dark:border-white/10
                            sm:flex-row sm:items-center">

                    <div>
                        <h3 class="text-xl font-bold
                                   text-[#184d42] dark:text-white">
                            Recent Attendance
                        </h3>

                        <p class="mt-1 text-sm text-gray-500
                                  dark:text-gray-400">
                            Your five most recent attendance records.
                        </p>
                    </div>

                    <a
                        href="{{ route(
                            'student.attendance.index'
                        ) }}"
                        class="text-sm font-semibold
                               text-[#184d42] hover:underline
                               dark:text-[#d4a373]"
                    >
                        View All Records
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px]
                                  text-left text-sm">

                        <thead
                            class="bg-gray-50 text-xs uppercase
                                   tracking-wide text-gray-500
                                   dark:bg-white/[0.03]
                                   dark:text-gray-400"
                        >
                            <tr>
                                <th class="px-6 py-4">Subject</th>
                                <th class="px-6 py-4">Lecture</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-gray-100
                                   dark:divide-white/10"
                        >
                            @forelse ($recentAttendance as $record)

                                @php
                                    $statusClasses = match (
                                        $record->status
                                    ) {
                                        'Present' =>
                                            'bg-emerald-100 '
                                            .'text-emerald-700 '
                                            .'dark:bg-emerald-500/10 '
                                            .'dark:text-emerald-300',

                                        'Late' =>
                                            'bg-amber-100 '
                                            .'text-amber-700 '
                                            .'dark:bg-amber-500/10 '
                                            .'dark:text-amber-300',

                                        'Excused' =>
                                            'bg-blue-100 '
                                            .'text-blue-700 '
                                            .'dark:bg-blue-500/10 '
                                            .'dark:text-blue-300',

                                        default =>
                                            'bg-red-100 '
                                            .'text-red-700 '
                                            .'dark:bg-red-500/10 '
                                            .'dark:text-red-300',
                                    };
                                @endphp

                                <tr
                                    class="transition
                                           hover:bg-gray-50/70
                                           dark:hover:bg-white/[0.02]"
                                >
                                    <td class="px-6 py-5
                                               font-semibold
                                               text-gray-800
                                               dark:text-white">
                                        {{
                                            $record
                                                ->session
                                                ?->subject
                                                ?->subject_name
                                            ?? 'Unknown Subject'
                                        }}
                                    </td>

                                    <td class="px-6 py-5
                                               text-gray-600
                                               dark:text-gray-300">
                                        Lecture
                                        {{
                                            $record
                                                ->session
                                                ?->lecture_number
                                            ?? '—'
                                        }}

                                        @if (
                                            $record
                                                ->session
                                                ?->lecture_title
                                        )
                                            <p class="mt-1 text-xs
                                                      text-gray-400">
                                                {{
                                                    $record
                                                        ->session
                                                        ->lecture_title
                                                }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5
                                               text-gray-600
                                               dark:text-gray-300">
                                        {{
                                            $record
                                                ->session
                                                ?->start_time
                                                ?->format(
                                                    'Y-m-d H:i'
                                                )
                                            ?? $record
                                                ->created_at
                                                ?->format(
                                                    'Y-m-d H:i'
                                                )
                                        }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <span
                                            class="rounded-full
                                                   px-3 py-1 text-xs
                                                   font-semibold
                                                   {{ $statusClasses }}"
                                        >
                                            {{ $record->status }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-6 py-14
                                               text-center"
                                    >
                                        <p class="font-semibold
                                                  text-gray-600
                                                  dark:text-gray-300">
                                            No attendance records yet.
                                        </p>

                                        <p class="mt-1 text-sm
                                                  text-gray-500
                                                  dark:text-gray-400">
                                            Your attendance records
                                            will appear after lectures.
                                        </p>
                                    </td>
                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>
</x-student-layout>