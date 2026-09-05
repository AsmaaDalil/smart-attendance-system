<x-student-layout>
    <main class="min-h-screen bg-[#f8f9fa] p-6
                 dark:bg-[#0f1110] lg:p-8">

        <div class="mx-auto max-w-7xl">

            {{-- Page Header --}}
            <div class="flex flex-col justify-between gap-4
                        sm:flex-row sm:items-center">

                <div>
                    <p class="text-sm font-semibold uppercase
                              tracking-[0.18em] text-[#d4a373]">
                        Student Portal
                    </p>

                    <h1 class="mt-2 text-3xl font-bold
                               text-[#184d42] dark:text-white">
                        Attendance History
                    </h1>

                    <p class="mt-2 text-sm text-gray-500
                              dark:text-gray-400">
                        Review your attendance records and
                        attendance percentage for every subject.
                    </p>
                </div>

                <a
                    href="{{ route('student.scanner') }}"
                    class="inline-flex items-center justify-center
                           rounded-xl bg-[#184d42] px-5 py-3
                           text-sm font-semibold text-white
                           transition hover:bg-[#24584d]"
                >
                    Scan QR Code
                </a>
            </div>

            {{-- Subject Statistics --}}
            <section class="mt-8">

                <div class="mb-4">
                    <h2 class="text-xl font-bold text-[#184d42]
                               dark:text-white">
                        Attendance by Subject
                    </h2>

                    <p class="mt-1 text-sm text-gray-500
                              dark:text-gray-400">
                        Excused records are not counted as absence.
                    </p>
                </div>

                @if ($subjectStatistics->isEmpty())

                    <div class="rounded-3xl border border-gray-100
                                bg-white p-8 text-center shadow-sm
                                dark:border-white/10
                                dark:bg-[#191c1b]">

                        <p class="font-semibold text-gray-700
                                  dark:text-gray-200">
                            No registered subjects were found.
                        </p>
                    </div>

                @else

                    <div class="grid gap-5 md:grid-cols-2
                                xl:grid-cols-3">

                        @foreach ($subjectStatistics as $statistic)

                            @php
                                $countedRecords =
                                    $statistic['present_count']
                                    + $statistic['late_count']
                                    + $statistic['absent_count'];

                                $absencePercentage =
                                    $countedRecords > 0
                                        ? round(
                                            (
                                                $statistic['absent_count']
                                                / $countedRecords
                                            ) * 100,
                                            1
                                        )
                                        : 0;

                                if ($absencePercentage >= 25) {
                                    $riskLabel = 'At Risk';

                                    $riskClasses =
                                        'bg-red-100 text-red-700 '
                                        .'dark:bg-red-500/10 '
                                        .'dark:text-red-300';
                                } elseif ($absencePercentage >= 15) {
                                    $riskLabel = 'Warning';

                                    $riskClasses =
                                        'bg-amber-100 text-amber-700 '
                                        .'dark:bg-amber-500/10 '
                                        .'dark:text-amber-300';
                                } else {
                                    $riskLabel = 'Safe';

                                    $riskClasses =
                                        'bg-emerald-100 '
                                        .'text-emerald-700 '
                                        .'dark:bg-emerald-500/10 '
                                        .'dark:text-emerald-300';
                                }

                                $attendanceWidth = min(
                                    100,
                                    max(
                                        0,
                                        (float) (
                                            $statistic['attendance_percentage']
                                            ?? 0
                                        )
                                    )
                                );
                            @endphp

                            <article
                                class="rounded-3xl border
                                       border-gray-100 bg-white
                                       p-6 shadow-sm
                                       dark:border-white/10
                                       dark:bg-[#191c1b]"
                            >
                                <div class="flex items-start
                                            justify-between gap-3">

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm text-gray-500
                                                  dark:text-gray-400">
                                            Subject
                                        </p>

                                        <h3 class="mt-1 break-words text-lg
                                                   font-bold text-[#184d42]
                                                   dark:text-white">
                                            {{
                                                $statistic['subject']
                                                    ->subject_name
                                            }}
                                        </h3>
                                    </div>

                                    <span
                                        class="inline-flex flex-shrink-0
                                               items-center justify-center
                                               whitespace-nowrap rounded-full
                                               px-3 py-1 text-xs font-bold
                                               {{ $riskClasses }}"
                                        style="white-space: nowrap;
                                               flex-shrink: 0;
                                               min-width: max-content;"
                                    >
                                        {{ $riskLabel }}
                                    </span>
                                </div>

                                <div class="mt-6 flex items-end
                                            justify-between gap-4">

                                    <div>
                                        <p class="text-sm text-gray-500
                                                  dark:text-gray-400">
                                            Attendance
                                        </p>

                                        <p class="mt-1 text-3xl font-bold
                                                  text-[#184d42]
                                                  dark:text-white">
                                            {{
                                                $statistic[
                                                    'attendance_percentage'
                                                ]
                                            }}%
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm text-gray-500
                                                  dark:text-gray-400">
                                            Absence
                                        </p>

                                        <p class="mt-1 text-lg font-bold
                                                  text-gray-700
                                                  dark:text-gray-200">
                                            {{ $absencePercentage }}%
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 h-2 overflow-hidden
                                            rounded-full bg-gray-100
                                            dark:bg-white/10">

                                 <div
    class="h-full rounded-full
           bg-[#184d42]"
    @style(["width: {$attendanceWidth}%"])
></div>
                                </div>

                                <div class="mt-6 grid grid-cols-4 gap-2
                                            text-center">

                                    <div class="rounded-xl bg-emerald-50
                                                p-3
                                                dark:bg-emerald-500/10">
                                        <p class="text-lg font-bold
                                                  text-emerald-700
                                                  dark:text-emerald-300">
                                            {{
                                                $statistic[
                                                    'present_count'
                                                ]
                                            }}
                                        </p>

                                        <p class="mt-1 text-[11px]
                                                  text-emerald-600
                                                  dark:text-emerald-400">
                                            Present
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-amber-50
                                                p-3
                                                dark:bg-amber-500/10">
                                        <p class="text-lg font-bold
                                                  text-amber-700
                                                  dark:text-amber-300">
                                            {{
                                                $statistic[
                                                    'late_count'
                                                ]
                                            }}
                                        </p>

                                        <p class="mt-1 text-[11px]
                                                  text-amber-600
                                                  dark:text-amber-400">
                                            Late
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-red-50 p-3
                                                dark:bg-red-500/10">
                                        <p class="text-lg font-bold
                                                  text-red-700
                                                  dark:text-red-300">
                                            {{
                                                $statistic[
                                                    'absent_count'
                                                ]
                                            }}
                                        </p>

                                        <p class="mt-1 text-[11px]
                                                  text-red-600
                                                  dark:text-red-400">
                                            Absent
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-blue-50 p-3
                                                dark:bg-blue-500/10">
                                        <p class="text-lg font-bold
                                                  text-blue-700
                                                  dark:text-blue-300">
                                            {{
                                                $statistic[
                                                    'excused_count'
                                                ]
                                            }}
                                        </p>

                                        <p class="mt-1 text-[11px]
                                                  text-blue-600
                                                  dark:text-blue-400">
                                            Excused
                                        </p>
                                    </div>
                                </div>
                            </article>

                        @endforeach
                    </div>

                @endif
            </section>

            {{-- Filters --}}
            <section
                class="mt-8 rounded-3xl border border-gray-100
                       bg-white p-6 shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]"
            >
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-[#184d42]
                               dark:text-white">
                        Filter Records
                    </h2>

                    <p class="mt-1 text-sm text-gray-500
                              dark:text-gray-400">
                        Filter attendance by subject or status.
                    </p>
                </div>

                <form
                    method="GET"
                    action="{{ route('student.attendance.index') }}"
                    class="grid gap-4 md:grid-cols-[1fr_1fr_auto]"
                >
                    <div>
                        <label
                            for="subject_id"
                            class="mb-2 block text-sm font-medium
                                   text-gray-700 dark:text-gray-200"
                        >
                            Subject
                        </label>

                        <select
                            id="subject_id"
                            name="subject_id"
                            class="w-full rounded-xl border-gray-200
                                   bg-white text-sm text-gray-700
                                   focus:border-[#184d42]
                                   focus:ring-[#184d42]
                                   dark:border-white/10
                                   dark:bg-[#101312]
                                   dark:text-gray-200"
                        >
                            <option value="">
                                All Subjects
                            </option>

                            @foreach ($subjects as $subject)
                                <option
                                    value="{{ $subject->id }}"
                                    @selected(
                                        (int) $selectedSubjectId
                                        === (int) $subject->id
                                    )
                                >
                                    {{ $subject->subject_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            for="status"
                            class="mb-2 block text-sm font-medium
                                   text-gray-700 dark:text-gray-200"
                        >
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-xl border-gray-200
                                   bg-white text-sm text-gray-700
                                   focus:border-[#184d42]
                                   focus:ring-[#184d42]
                                   dark:border-white/10
                                   dark:bg-[#101312]
                                   dark:text-gray-200"
                        >
                            <option value="">
                                All Statuses
                            </option>

                            @foreach (
                                [
                                    'Present',
                                    'Late',
                                    'Absent',
                                    'Excused',
                                ] as $status
                            )
                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        $selectedStatus === $status
                                    )
                                >
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="rounded-xl bg-[#184d42]
                                   px-5 py-3 text-sm font-semibold
                                   text-white transition
                                   hover:bg-[#24584d]"
                        >
                            Apply Filters
                        </button>

                        <a
                            href="{{ route(
                                'student.attendance.index'
                            ) }}"
                            class="rounded-xl border border-gray-200
                                   px-5 py-3 text-sm font-semibold
                                   text-gray-600 transition
                                   hover:bg-gray-50
                                   dark:border-white/10
                                   dark:text-gray-300
                                   dark:hover:bg-white/5"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </section>

            {{-- Attendance Records --}}
            <section
                class="mt-8 overflow-hidden rounded-3xl
                       border border-gray-100 bg-white shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]"
            >
                <div class="border-b border-gray-100 p-6
                            dark:border-white/10">

                    <h2 class="text-xl font-bold text-[#184d42]
                               dark:text-white">
                        Attendance Records
                    </h2>

                    <p class="mt-1 text-sm text-gray-500
                              dark:text-gray-400">
                        Your latest lectures and attendance status.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] text-left
                                  text-sm">

                        <thead class="bg-gray-50 text-xs uppercase
                                      tracking-wide text-gray-500
                                      dark:bg-white/[0.03]
                                      dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-4">
                                    Subject
                                </th>

                                <th class="px-6 py-4">
                                    Lecture
                                </th>

                                <th class="px-6 py-4">
                                    Date
                                </th>

                                <th class="px-6 py-4">
                                    Status
                                </th>

                                <th class="px-6 py-4">
                                    Scan Time
                                </th>

                                <th class="px-6 py-4">
                                    Distance
                                </th>

                                <th class="px-6 py-4">
                                    Excuse
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100
                                      dark:divide-white/10">

                            @forelse ($records as $record)

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

                                <tr class="transition
                                           hover:bg-gray-50/70
                                           dark:hover:bg-white/[0.02]">

                                    <td class="px-6 py-5">
                                        <p class="font-semibold
                                                  text-gray-800
                                                  dark:text-white">
                                            {{
                                                $record
                                                    ->session
                                                    ?->subject
                                                    ?->subject_name
                                                ?? 'Unknown Subject'
                                            }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-semibold
                                                  text-gray-700
                                                  dark:text-gray-200">
                                            Lecture
                                            {{
                                                $record
                                                    ->session
                                                    ?->lecture_number
                                                ?? '—'
                                            }}
                                        </p>

                                        <p class="mt-1 max-w-xs
                                                  text-xs text-gray-500
                                                  dark:text-gray-400">
                                            {{
                                                $record
                                                    ->session
                                                    ?->lecture_title
                                                ?? 'No lecture title'
                                            }}
                                        </p>

                                        @if (
                                            $record->status === 'Absent'
                                        )
                                            <p class="mt-2 text-xs
                                                      font-semibold
                                                      text-red-600
                                                      dark:text-red-400">
                                                You missed this lesson.
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5 text-gray-600
                                               dark:text-gray-300">
                                        {{
                                            $record
                                                ->session
                                                ?->start_time
                                                ?->format('Y-m-d')
                                            ?? '—'
                                        }}

                                        <p class="mt-1 text-xs
                                                  text-gray-400">
                                            {{
                                                $record
                                                    ->session
                                                    ?->start_time
                                                    ?->format('H:i')
                                                ?? ''
                                            }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        <span
                                            class="rounded-full px-3 py-1
                                                   text-xs font-bold
                                                   {{ $statusClasses }}"
                                        >
                                            {{ $record->status }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 text-gray-600
                                               dark:text-gray-300">
                                        {{
                                            $record->scanned_at
                                                ?->format('H:i:s')
                                            ?? '—'
                                        }}
                                    </td>

                                    <td class="px-6 py-5 text-gray-600
                                               dark:text-gray-300">

                                        @if (
                                            $record->distance_meters
                                            !== null
                                        )
                                            {{
                                                number_format(
                                                    (float)
                                                    $record
                                                        ->distance_meters,
                                                    2
                                                )
                                            }} m
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="px-6 py-5">

                                        @if ($record->excuse)

                                            @php
                                                $excuseClasses = match (
                                                    $record
                                                        ->excuse
                                                        ->status
                                                ) {
                                                    'Approved' =>
                                                        'bg-emerald-100 '
                                                        .'text-emerald-700',

                                                    'Rejected' =>
                                                        'bg-red-100 '
                                                        .'text-red-700',

                                                    default =>
                                                        'bg-amber-100 '
                                                        .'text-amber-700',
                                                };
                                            @endphp

                                            <span
                                                class="rounded-full
                                                       px-3 py-1 text-xs
                                                       font-bold
                                                       {{ $excuseClasses }}"
                                            >
                                                {{
                                                    $record
                                                        ->excuse
                                                        ->status
                                                }}
                                            </span>

                                        @elseif (
                                            $record->status === 'Absent'
                                            && \Illuminate\Support\Facades\Route::has(
                                                'student.excuses.create'
                                            )
                                        )

                                            <a
                                                href="{{ route(
                                                    'student.excuses.create',
                                                    $record
                                                ) }}"
                                                class="text-sm font-semibold
                                                       text-[#184d42]
                                                       hover:underline
                                                       dark:text-[#d4a373]"
                                            >
                                                Submit Excuse
                                            </a>

                                        @elseif (
                                            $record->status === 'Absent'
                                        )

                                            <span class="text-xs
                                                         text-gray-400">
                                                No excuse submitted
                                            </span>

                                        @else

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        @endif
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="7"
                                        class="px-6 py-14 text-center"
                                    >
                                        <p class="font-semibold
                                                  text-gray-700
                                                  dark:text-gray-200">
                                            No attendance records found.
                                        </p>

                                        <p class="mt-2 text-sm
                                                  text-gray-500
                                                  dark:text-gray-400">
                                            Attendance records will appear
                                            after your lectures.
                                        </p>
                                    </td>
                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($records->hasPages())
                    <div class="border-t border-gray-100 p-5
                                dark:border-white/10">
                        {{ $records->links() }}
                    </div>
                @endif
            </section>
        </div>
    </main>
</x-student-layout>