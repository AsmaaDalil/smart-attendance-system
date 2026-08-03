<x-student-layout>
    <div class="min-h-screen p-6 lg:p-8">
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
                        My Excuses
                    </h1>

                    <p class="mt-2 text-sm text-gray-500
                              dark:text-gray-400">
                        Review the excuses you submitted
                        and their current status.
                    </p>
                </div>

                <a
                    href="{{ route('student.attendance.index') }}"
                    class="inline-flex items-center justify-center
                           rounded-xl bg-[#184d42] px-5 py-3
                           text-sm font-semibold text-white
                           transition hover:bg-[#24584d]"
                >
                    Attendance History
                </a>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mt-6 rounded-2xl border
                            border-emerald-200 bg-emerald-50
                            px-5 py-4 text-sm font-semibold
                            text-emerald-700
                            dark:border-emerald-500/20
                            dark:bg-emerald-500/10
                            dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Status Filter --}}
            <section
                class="mt-8 rounded-3xl border border-gray-100
                       bg-white p-6 shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]"
            >
                <form
                    method="GET"
                    action="{{ route('student.excuses.index') }}"
                    class="flex flex-col gap-4
                           sm:flex-row sm:items-end"
                >
                    <div class="w-full sm:max-w-sm">
                        <label
                            for="status"
                            class="mb-2 block text-sm font-medium
                                   text-gray-700 dark:text-gray-200"
                        >
                            Excuse Status
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
                                    'Pending',
                                    'Approved',
                                    'Rejected',
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

                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="rounded-xl bg-[#184d42]
                                   px-5 py-3 text-sm font-semibold
                                   text-white transition
                                   hover:bg-[#24584d]"
                        >
                            Apply
                        </button>

                        <a
                            href="{{ route('student.excuses.index') }}"
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

            {{-- Excuses Table --}}
            <section
                class="mt-8 overflow-hidden rounded-3xl
                       border border-gray-100 bg-white shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]"
            >
                <div class="border-b border-gray-100 p-6
                            dark:border-white/10">
                    <h2 class="text-xl font-bold text-[#184d42]
                               dark:text-white">
                        Submitted Excuses
                    </h2>

                    <p class="mt-1 text-sm text-gray-500
                              dark:text-gray-400">
                        Each excuse is reviewed by the professor.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px] text-left text-sm">

                        <thead class="bg-gray-50 text-xs uppercase
                                      tracking-wide text-gray-500
                                      dark:bg-white/[0.03]
                                      dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-4">Subject</th>
                                <th class="px-6 py-4">Lecture</th>
                                <th class="px-6 py-4">Reason</th>
                                <th class="px-6 py-4">Attachment</th>
                                <th class="px-6 py-4">Submitted</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100
                                      dark:divide-white/10">

                            @forelse ($excuses as $excuse)

                                @php
                                    $statusClasses = match (
                                        $excuse->status
                                    ) {
                                        'Approved' =>
                                            'bg-emerald-100 '
                                            .'text-emerald-700 '
                                            .'dark:bg-emerald-500/10 '
                                            .'dark:text-emerald-300',

                                        'Rejected' =>
                                            'bg-red-100 '
                                            .'text-red-700 '
                                            .'dark:bg-red-500/10 '
                                            .'dark:text-red-300',

                                        default =>
                                            'bg-amber-100 '
                                            .'text-amber-700 '
                                            .'dark:bg-amber-500/10 '
                                            .'dark:text-amber-300',
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
                                                $excuse
                                                    ->attendanceRecord
                                                    ?->session
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
                                                $excuse
                                                    ->attendanceRecord
                                                    ?->session
                                                    ?->lecture_number
                                                ?? '—'
                                            }}
                                        </p>

                                        <p class="mt-1 text-xs
                                                  text-gray-500
                                                  dark:text-gray-400">
                                            {{
                                                $excuse
                                                    ->attendanceRecord
                                                    ?->session
                                                    ?->lecture_title
                                                ?? 'No lecture title'
                                            }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="max-w-xs text-gray-600
                                                  dark:text-gray-300">
                                            {{ $excuse->reason }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">

                                        @if ($excuse->file_path)
                                            <a
                                                href="{{ asset(
                                                    'storage/'
                                                    .$excuse->file_path
                                                ) }}"
                                                target="_blank"
                                                class="font-semibold
                                                       text-[#184d42]
                                                       hover:underline
                                                       dark:text-[#d4a373]"
                                            >
                                                View File
                                            </a>
                                        @else
                                            <span class="text-gray-400">
                                                No attachment
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-5 text-gray-600
                                               dark:text-gray-300">
                                        {{ $excuse->created_at?->format(
                                            'Y-m-d'
                                        ) }}

                                        <p class="mt-1 text-xs
                                                  text-gray-400">
                                            {{ $excuse->created_at?->format(
                                                'H:i'
                                            ) }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        <span
                                            class="rounded-full px-3 py-1
                                                   text-xs font-bold
                                                   {{ $statusClasses }}"
                                        >
                                            {{ $excuse->status }}
                                        </span>

                                        @if (
                                            $excuse->status === 'Pending'
                                        )
                                            <p class="mt-2 text-xs
                                                      text-gray-500
                                                      dark:text-gray-400">
                                                Waiting for review
                                            </p>
                                        @elseif (
                                            $excuse->status === 'Approved'
                                        )
                                            <p class="mt-2 text-xs
                                                      text-emerald-600
                                                      dark:text-emerald-400">
                                                Attendance changed to Excused
                                            </p>
                                        @else
                                            <p class="mt-2 text-xs
                                                      text-red-600
                                                      dark:text-red-400">
                                                Attendance remains Absent
                                            </p>
                                        @endif
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="6"
                                        class="px-6 py-14 text-center"
                                    >
                                        <p class="font-semibold
                                                  text-gray-700
                                                  dark:text-gray-200">
                                            No excuses were found.
                                        </p>

                                        <p class="mt-2 text-sm
                                                  text-gray-500
                                                  dark:text-gray-400">
                                            Submit an excuse from an absent
                                            record in Attendance History.
                                        </p>
                                    </td>
                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($excuses->hasPages())
                    <div class="border-t border-gray-100 p-5
                                dark:border-white/10">
                        {{ $excuses->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-student-layout>