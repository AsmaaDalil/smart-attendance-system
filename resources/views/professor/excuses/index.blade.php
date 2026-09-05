<x-professor-layout>
    <main class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">

            <div class="mb-7">
                <h1 class="text-3xl font-bold text-[#184d42] dark:text-white">
                    Student Excuses
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Review excuse requests submitted by students in your subjects.
                </p>
            </div>

            @if (session('success'))
                <div
                    class="mb-5 rounded-2xl border border-emerald-200
                           bg-emerald-50 p-4 text-sm text-emerald-700
                           dark:border-emerald-500/20
                           dark:bg-emerald-500/10
                           dark:text-emerald-300"
                >
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div
                    class="mb-5 rounded-2xl border border-blue-200
                           bg-blue-50 p-4 text-sm text-blue-700
                           dark:border-blue-500/20
                           dark:bg-blue-500/10
                           dark:text-blue-300"
                >
                    {{ session('info') }}
                </div>
            @endif

            <form
                method="GET"
                action="{{ route('professor.excuses.index') }}"
                class="mb-6 grid grid-cols-1 gap-4 rounded-3xl
                       border border-gray-100 bg-white p-5 shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]
                       md:grid-cols-[1fr_220px_auto]"
            >

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Student name or university number"
                        class="w-full rounded-xl border-gray-200
                               dark:border-white/10 dark:bg-[#101312]"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border-gray-200
                               dark:border-white/10 dark:bg-[#101312]"
                    >
                        <option value="">All Statuses</option>

                        @foreach (['Pending', 'Approved', 'Rejected'] as $status)
                            <option
                                value="{{ $status }}"
                                @selected(($filters['status'] ?? null) === $status)
                            >
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="rounded-xl bg-[#184d42] px-5 py-3
                               text-sm font-semibold text-white
                               hover:bg-[#24584d]"
                    >
                        Apply
                    </button>

                    <a
                        href="{{ route('professor.excuses.index') }}"
                        class="rounded-xl border border-gray-200
                               px-5 py-3 text-sm font-medium
                               dark:border-white/10"
                    >
                        Reset
                    </a>

                </div>
            </form>

            <div
                class="overflow-hidden rounded-3xl border
                       border-gray-100 bg-white shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]"
            >

                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="bg-gray-50 dark:bg-white/[0.03]">
                            <tr>
                                <th class="px-5 py-4">Student</th>
                                <th class="px-5 py-4">Subject</th>
                                <th class="px-5 py-4">Lecture</th>
                                <th class="px-5 py-4">Reason</th>
                                <th class="px-5 py-4">Attachment</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Submitted</th>
                                <th class="px-5 py-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-gray-100
                                   dark:divide-white/10"
                        >

                            @forelse ($excuses as $excuse)

                                @php
                                    $record = $excuse->attendanceRecord;
                                    $student = $record->student;
                                    $session = $record->session;
                                @endphp

                                <tr>

                                    <td class="px-5 py-4">
                                        <p class="font-semibold">
                                            {{ $student->user->name }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $student->university_number }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $session->subject->subject_name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $session->lecture_number }}
                                        —
                                        {{ $session->lecture_title }}
                                    </td>

                                    {{-- Full excuse reason --}}
                                    <td
                                        class="max-w-sm whitespace-normal
                                               break-words px-5 py-4"
                                    >
                                        <p>
                                            {{ $excuse->reason }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">

                                        @if ($excuse->file_path)

                                            <a
                                                href="{{ asset('storage/'.$excuse->file_path) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="font-semibold text-[#184d42]
                                                       underline
                                                       dark:text-[#8bb9ac]"
                                            >
                                                Open File
                                            </a>

                                        @else

                                            <span class="text-gray-400">
                                                No file
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-5 py-4">

                                        <span
                                            class="rounded-full px-3 py-1
                                                   text-xs font-bold
                                                   {{
                                                        match ($excuse->status) {
                                                            'Approved' =>
                                                                'bg-emerald-100 text-emerald-700',
                                                            'Rejected' =>
                                                                'bg-red-100 text-red-700',
                                                            default =>
                                                                'bg-amber-100 text-amber-700'
                                                        }
                                                   }}"
                                        >
                                            {{ $excuse->status }}
                                        </span>

                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $excuse->created_at?->format('Y-m-d H:i') }}
                                    </td>

                                    <td class="px-5 py-4">

                                        @if ($excuse->status === 'Pending')

                                            <div class="flex gap-2">

                                                <form
                                                    method="POST"
                                                    action="{{ route('professor.excuses.approve', $excuse) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="rounded-lg bg-emerald-600
                                                               px-3 py-2 text-xs
                                                               font-semibold text-white
                                                               hover:bg-emerald-700"
                                                    >
                                                        Approve
                                                    </button>
                                                </form>

                                                <form
                                                    method="POST"
                                                    action="{{ route('professor.excuses.reject', $excuse) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="rounded-lg bg-red-600
                                                               px-3 py-2 text-xs
                                                               font-semibold text-white
                                                               hover:bg-red-700"
                                                    >
                                                        Reject
                                                    </button>
                                                </form>

                                            </div>

                                        @else

                                            <span class="text-xs text-gray-400">
                                                Reviewed
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="8"
                                        class="px-5 py-14 text-center
                                               text-gray-500"
                                    >
                                        No excuse requests found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>

                @if ($excuses->hasPages())

                    <div
                        class="border-t border-gray-100 p-5
                               dark:border-white/10"
                    >
                        {{ $excuses->links() }}
                    </div>

                @endif

            </div>

        </div>
    </main>
</x-professor-layout>