<x-professor-layout>
    <main class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-7">
                <h1 class="text-3xl font-bold text-[#184d42] dark:text-white">Attendance Reports</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Filter and export attendance records for your subjects.
                </p>
            </div>

            <form method="GET"
                  action="{{ route('professor.reports.index') }}"
                  class="mb-6 grid grid-cols-1 gap-4 rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#191c1b] md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label class="mb-2 block text-sm font-medium">Subject</label>
                    <select name="subject_id" class="w-full rounded-xl border-gray-200 dark:border-white/10 dark:bg-[#101312]">
                        <option value="">All Subjects</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(($filters['subject_id'] ?? null) == $subject->id)>
                                {{ $subject->subject_code }} — {{ $subject->subject_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 dark:border-white/10 dark:bg-[#101312]">
                        <option value="">All Statuses</option>
                        @foreach (['Present', 'Late', 'Absent', 'Excused'] as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? null) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">From</label>
                    <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="w-full rounded-xl border-gray-200 dark:border-white/10 dark:bg-[#101312]">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">Until</label>
                    <input type="date" name="until" value="{{ $filters['until'] ?? '' }}" class="w-full rounded-xl border-gray-200 dark:border-white/10 dark:bg-[#101312]">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-xl bg-[#184d42] px-5 py-3 text-sm font-semibold text-white hover:bg-[#24584d]">Apply</button>
                    <a href="{{ route('professor.reports.index') }}" class="rounded-xl border border-gray-200 px-5 py-3 text-sm dark:border-white/10">Reset</a>
                </div>
            </form>

            <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
                @foreach ([
                    'Total' => $summary['total'],
                    'Present' => $summary['present'],
                    'Late' => $summary['late'],
                    'Absent' => $summary['absent'],
                    'Excused' => $summary['excused'],
                    'Rate' => $summary['attendance_rate'].'%',
                ] as $label => $value)
                    <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#191c1b]">
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p class="mt-2 text-2xl font-bold text-[#184d42] dark:text-white">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mb-5 flex flex-wrap gap-3">
                <a href="{{ route('professor.reports.excel', request()->query()) }}" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Export Excel</a>
                <a href="{{ route('professor.reports.pdf', request()->query()) }}" class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">Export PDF</a>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-white/10 dark:bg-[#191c1b]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-white/[0.03]">
                            <tr>
                                <th class="px-5 py-4">Student</th>
                                <th class="px-5 py-4">Number</th>
                                <th class="px-5 py-4">Subject</th>
                                <th class="px-5 py-4">Lecture</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                            @forelse ($records as $record)
                                <tr>
                                    <td class="px-5 py-4 font-medium">{{ $record->student->user->name }}</td>
                                    <td class="px-5 py-4">{{ $record->student->university_number }}</td>
                                    <td class="px-5 py-4">{{ $record->session->subject->subject_name }}</td>
                                    <td class="px-5 py-4">{{ $record->session->lecture_number }} — {{ $record->session->lecture_title }}</td>
                                    <td class="px-5 py-4">{{ $record->status }}</td>
                                    <td class="px-5 py-4">{{ $record->session->start_time?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-14 text-center text-gray-500">No records match the selected filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($records->hasPages())
                    <div class="border-t border-gray-100 p-5 dark:border-white/10">{{ $records->links() }}</div>
                @endif
            </div>
        </div>
    </main>
</x-professor-layout>