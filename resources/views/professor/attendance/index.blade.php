<x-professor-layout>

    <main class="p-6 lg:p-8">

        <div class="mx-auto max-w-7xl">

            <div class="mb-7">

                <h1 class="text-3xl font-bold
                           text-[#184d42] dark:text-white">
                    Attendance Records
                </h1>

                <p class="mt-1 text-sm text-gray-500
                          dark:text-gray-400">
                    Review and manage attendance records
                    for your subjects.
                </p>
            </div>

            @if (session('success'))

                <div
                    class="mb-5 rounded-2xl
                           border border-emerald-200
                           bg-emerald-50 p-4
                           text-sm text-emerald-700
                           dark:border-emerald-500/20
                           dark:bg-emerald-500/10
                           dark:text-emerald-300"
                >
                    {{ session('success') }}
                </div>

            @endif

            {{-- Filters --}}
            <form
                method="GET"
                action="{{ route(
                    'professor.attendance.index'
                ) }}"
                class="mb-6 grid grid-cols-1 gap-4
                       rounded-3xl border border-gray-100
                       bg-white p-5 shadow-sm
                       dark:border-white/10
                       dark:bg-[#191c1b]
                       md:grid-cols-2 xl:grid-cols-5"
            >
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Name or university number"
                        class="w-full rounded-xl
                               border-gray-200
                               dark:border-white/10
                               dark:bg-[#101312]"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Subject
                    </label>

                    <select
                        name="subject_id"
                        class="w-full rounded-xl
                               border-gray-200
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
                                {{ $subject->subject_code }}
                                —
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
                        class="w-full rounded-xl
                               border-gray-200
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
                        Date
                    </label>

                    <input
                        type="date"
                        name="date"
                        value="{{ $filters['date'] ?? '' }}"
                        class="w-full rounded-xl
                               border-gray-200
                               dark:border-white/10
                               dark:bg-[#101312]"
                    >
                </div>

                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="rounded-xl bg-[#184d42]
                               px-5 py-3 text-sm
                               font-semibold text-white
                               hover:bg-[#24584d]"
                    >
                        Apply
                    </button>

                    <a
                        href="{{ route(
                            'professor.attendance.index'
                        ) }}"
                        class="rounded-xl border
                               border-gray-200 px-5 py-3
                               text-sm font-medium
                               dark:border-white/10"
                    >
                        Reset
                    </a>
                </div>
            </form>

            {{-- Records --}}
            <div
                class="overflow-hidden rounded-3xl
                       border border-gray-100 bg-white
                       shadow-sm dark:border-white/10
                       dark:bg-[#191c1b]"
            >
                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead
                            class="bg-gray-50
                                   dark:bg-white/[0.03]"
                        >
                            <tr>
                                <th class="px-5 py-4">Student</th>
                                <th class="px-5 py-4">Number</th>
                                <th class="px-5 py-4">Subject</th>
                                <th class="px-5 py-4">Lecture</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Scanned</th>
                                <th class="px-5 py-4">Distance</th>
                                <th class="px-5 py-4">Update</th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-gray-100
                                   dark:divide-white/10"
                        >
                            @forelse ($records as $record)

                                <tr>
                                    <td class="px-5 py-4 font-medium">
                                        {{ $record->student->user->name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{
                                            $record->student
                                                ->university_number
                                        }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{
                                            $record->session
                                                ->subject
                                                ->subject_name
                                        }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $record->session->lecture_number }}
                                        —
                                        {{ $record->session->lecture_title }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span
                                            class="rounded-full
                                                   px-3 py-1
                                                   text-xs font-bold
                                                   {{
                                                       match (
                                                           $record->status
                                                       ) {
                                                           'Present' =>
                                                               'bg-emerald-100 text-emerald-700',
                                                           'Late' =>
                                                               'bg-amber-100 text-amber-700',
                                                           'Excused' =>
                                                               'bg-blue-100 text-blue-700',
                                                           default =>
                                                               'bg-red-100 text-red-700',
                                                       }
                                                   }}"
                                        >
                                            {{ $record->status }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        {{
                                            $record->scanned_at
                                                ? $record->scanned_at
                                                    ->format('H:i:s')
                                                : '—'
                                        }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{
                                            $record->distance_meters
                                                !== null
                                                ? $record
                                                    ->distance_meters
                                                    .' m'
                                                : '—'
                                        }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($record->status === 'Excused')
                                            <span class="text-xs font-medium text-gray-400">
                                                Managed through excuses
                                            </span>
                                        @else
                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'professor.attendance.update',
                                                    $record
                                                ) }}"
                                                class="flex items-center gap-2"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <select
                                                    name="status"
                                                    class="rounded-lg
                                                           border-gray-200
                                                           py-2 text-xs
                                                           dark:border-white/10
                                                           dark:bg-[#101312]"
                                                >
                                                    @foreach (
                                                        [
                                                            'Present',
                                                            'Late',
                                                            'Absent',
                                                        ]
                                                        as $status
                                                    )
                                                        <option
                                                            value="{{ $status }}"
                                                            @selected(
                                                                $record->status
                                                                === $status
                                                            )
                                                        >
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <button
                                                    type="submit"
                                                    class="rounded-lg
                                                           bg-[#184d42]
                                                           px-3 py-2
                                                           text-xs font-semibold
                                                           text-white"
                                                >
                                                    Save
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="8"
                                        class="px-5 py-14
                                               text-center
                                               text-gray-500"
                                    >
                                        No attendance records found.
                                    </td>
                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($records->hasPages())
                    <div
                        class="border-t border-gray-100
                               p-5 dark:border-white/10"
                    >
                        {{ $records->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

</x-professor-layout>