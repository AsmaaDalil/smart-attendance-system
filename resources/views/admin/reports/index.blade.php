<x-admin-layout>

    <div class="p-6 lg:p-8">

        {{-- Header --}}
        <div class="mb-7">
            <h1 class="text-3xl font-bold text-[#184d42]
                       dark:text-white">
                Attendance Reports
            </h1>

            <p class="mt-1 text-sm text-gray-500
                      dark:text-gray-400">
                Filter attendance data and export detailed
                or student summary reports.
            </p>
        </div>

        {{-- Filters --}}
        <form
            method="GET"
            action="{{ route('admin.reports.index') }}"
            class="mb-6 grid grid-cols-1 gap-4
                   rounded-2xl border border-gray-100
                   bg-white p-5 shadow-sm
                   dark:border-white/10 dark:bg-[#191c1b]
                   md:grid-cols-2 xl:grid-cols-5"
        >
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Subject
                </label>

                <select
                    name="subject_id"
                    class="w-full rounded-xl border-gray-200
                           dark:border-white/10
                           dark:bg-[#101312]"
                >
                    <option value="">All Subjects</option>

                    @foreach ($subjects as $subject)
                        <option
                            value="{{ $subject->id }}"
                            @selected(
                                ($filters['subject_id'] ?? null)
                                == $subject->id
                            )
                        >
                            {{ $subject->subject_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border-gray-200
                           dark:border-white/10
                           dark:bg-[#101312]"
                >
                    <option value="">All Statuses</option>

                    @foreach (
                        ['Present', 'Late', 'Absent', 'Excused']
                        as $status
                    )
                        <option
                            value="{{ $status }}"
                            @selected(
                                ($filters['status'] ?? null)
                                === $status
                            )
                        >
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">
                    From
                </label>

                <input
                    type="date"
                    name="from"
                    value="{{ $filters['from'] ?? '' }}"
                    class="w-full rounded-xl border-gray-200
                           dark:border-white/10
                           dark:bg-[#101312]"
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">
                    Until
                </label>

                <input
                    type="date"
                    name="until"
                    value="{{ $filters['until'] ?? '' }}"
                    class="w-full rounded-xl border-gray-200
                           dark:border-white/10
                           dark:bg-[#101312]"
                >
            </div>

            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="rounded-xl bg-[#184d42]
                           px-5 py-3 text-sm font-semibold
                           text-white hover:bg-[#24584d]"
                >
                    Apply
                </button>

                <a
                    href="{{ route('admin.reports.index') }}"
                    class="rounded-xl border border-gray-200
                           px-5 py-3 text-sm
                           dark:border-white/10"
                >
                    Reset
                </a>
            </div>
        </form>

        {{-- General summary --}}
        <div class="mb-6 grid grid-cols-2 gap-4
                    md:grid-cols-3 xl:grid-cols-6">

            @foreach ([
                'Total' => $summary['total'],
                'Present' => $summary['present'],
                'Late' => $summary['late'],
                'Absent' => $summary['absent'],
                'Excused' => $summary['excused'],
                'Rate' => $summary['attendance_rate'].'%',
            ] as $label => $value)

                <div class="rounded-2xl border border-gray-100
                            bg-white p-4 shadow-sm
                            dark:border-white/10
                            dark:bg-[#191c1b]">

                    <p class="text-xs text-gray-500">
                        {{ $label }}
                    </p>

                    <p class="mt-2 text-2xl font-bold
                              text-[#184d42]
                              dark:text-white">
                        {{ $value }}
                    </p>
                </div>

            @endforeach
        </div>

        {{-- Export buttons --}}
        <div class="mb-5 flex flex-wrap gap-3">

            <a
                href="{{ route(
                    'admin.reports.excel',
                    request()->query()
                ) }}"
                class="rounded-xl bg-emerald-600
                       px-5 py-3 text-sm font-semibold
                       text-white hover:bg-emerald-700"
            >
                Export Detailed Excel
            </a>

            <a
                href="{{ route(
                    'admin.reports.pdf',
                    request()->query()
                ) }}"
                class="rounded-xl bg-red-600
                       px-5 py-3 text-sm font-semibold
                       text-white hover:bg-red-700"
            >
                Export PDF
            </a>

            <a
                href="{{ route(
                    'admin.reports.warnings.excel',
                    request()->query()
                ) }}"
                class="rounded-xl bg-[#d4a373]
                       px-5 py-3 text-sm font-semibold
                       text-[#184d42]
                       hover:bg-[#c89462]"
            >
                Export Student Summary
            </a>
        </div>

        {{-- Detailed records --}}
        <section class="overflow-hidden rounded-2xl
                        border border-gray-100 bg-white
                        shadow-sm dark:border-white/10
                        dark:bg-[#191c1b]">

            <div class="border-b border-gray-100
                        px-6 py-5 dark:border-white/10">

                <h2 class="font-semibold text-[#184d42]
                           dark:text-white">
                    Detailed Attendance Records
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Individual attendance records matching
                    the selected filters.
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-left
                                  dark:bg-white/[0.03]">

                        <tr>
                            <th class="px-5 py-4">Student</th>
                            <th class="px-5 py-4">Number</th>
                            <th class="px-5 py-4">Subject</th>
                            <th class="px-5 py-4">Lecture</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Date</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100
                                  dark:divide-white/10">

                        @forelse ($records as $record)

                            <tr>
                                <td class="px-5 py-4">
                                    {{ $record->student->user->name }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $record->student->university_number }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $record->session->subject->subject_name }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $record->session->lecture_title }}
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        @class([
                                            'rounded-full px-3 py-1 text-xs font-medium',

                                            'bg-green-100 text-green-700' =>
                                                $record->status === 'Present',

                                            'bg-amber-100 text-amber-700' =>
                                                $record->status === 'Late',

                                            'bg-red-100 text-red-700' =>
                                                $record->status === 'Absent',

                                            'bg-blue-100 text-blue-700' =>
                                                $record->status === 'Excused',
                                        ])
                                    >
                                        {{ $record->status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    {{ $record->created_at->format(
                                        'Y-m-d H:i'
                                    ) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="6"
                                    class="px-5 py-12 text-center
                                           text-gray-500"
                                >
                                    No records match the selected filters.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100
                        px-5 py-4 dark:border-white/10">
                {{ $records->links() }}
            </div>
        </section>

        {{-- Student summaries --}}
        <section class="mt-7 overflow-hidden rounded-2xl
                        border border-gray-100 bg-white
                        shadow-sm dark:border-white/10
                        dark:bg-[#191c1b]">

            <div class="flex items-center justify-between
                        border-b border-gray-100
                        px-6 py-5 dark:border-white/10">

                <div>
                    <h2 class="font-semibold text-[#184d42]
                               dark:text-white">
                        Student Attendance Summary
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Students with 25% absence or more
                        are marked as warnings.
                    </p>
                </div>

                <span class="rounded-full bg-red-100
                             px-3 py-1 text-xs font-semibold
                             text-red-700">
                    {{ $warningCount }} Warnings
                </span>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-left
                                  dark:bg-white/[0.03]">

                        <tr>
                            <th class="px-5 py-4">Student</th>
                            <th class="px-5 py-4">Subject</th>
                            <th class="px-5 py-4">Total</th>
                            <th class="px-5 py-4">Present</th>
                            <th class="px-5 py-4">Late</th>
                            <th class="px-5 py-4">Absent</th>
                            <th class="px-5 py-4">Excused</th>
                            <th class="px-5 py-4">
                                Absence Rate
                            </th>
                            <th class="px-5 py-4">Status</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100
                                  dark:divide-white/10">

                        @forelse ($studentSummaries as $student)

                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-medium">
                                        {{ $student['student_name'] }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $student['university_number'] }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    {{ $student['subject_name'] }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $student['total'] }}
                                </td>

                                <td class="px-5 py-4 text-green-600">
                                    {{ $student['present'] }}
                                </td>

                                <td class="px-5 py-4 text-amber-600">
                                    {{ $student['late'] }}
                                </td>

                                <td class="px-5 py-4 text-red-600">
                                    {{ $student['absent'] }}
                                </td>

                                <td class="px-5 py-4 text-blue-600">
                                    {{ $student['excused'] }}
                                </td>

                                <td class="px-5 py-4 font-semibold">
                                    {{ $student['absence_rate'] }}%
                                </td>

                                <td class="px-5 py-4">
                                    @if ($student['is_warning'])

                                        <span
                                            class="rounded-full
                                                   bg-red-100 px-3 py-1
                                                   text-xs font-semibold
                                                   text-red-700"
                                        >
                                            Warning
                                        </span>

                                    @else

                                        <span
                                            class="rounded-full
                                                   bg-green-100 px-3 py-1
                                                   text-xs font-semibold
                                                   text-green-700"
                                        >
                                            Safe
                                        </span>

                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="9"
                                    class="px-5 py-12 text-center
                                           text-gray-500"
                                >
                                    No student attendance summaries yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>
        </section>

    </div>

</x-admin-layout>