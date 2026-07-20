<x-professor-layout>

    <main class="p-6 lg:p-8">

        <div class="mx-auto max-w-7xl">

            <a
                href="{{ route('professor.subjects.index') }}"
                class="mb-5 inline-flex text-sm text-gray-500
                       transition hover:text-[#184d42]
                       dark:text-gray-400 dark:hover:text-white"
            >
                ← Back to My Subjects
            </a>

            <div class="mb-7 flex flex-wrap
                        items-center justify-between gap-4">

                <div>
                    <span class="text-sm font-bold text-[#d4a373]">
                        {{ $subject->subject_code }}
                    </span>

                    <h1 class="mt-1 text-3xl font-bold
                               text-[#184d42] dark:text-white">
                        {{ $subject->subject_name }}
                    </h1>
                </div>

                <a
                    href="{{
                        route('professor.sessions.create')
                        .'?subject_id='.$subject->id
                    }}"
                    class="rounded-xl bg-[#184d42]
                           px-5 py-3 text-sm font-semibold
                           text-white transition
                           hover:bg-[#24584d]"
                >
                    Start Session
                </a>
            </div>

            <div class="mb-7 grid grid-cols-1 gap-4 md:grid-cols-2">

                <div class="rounded-2xl border border-gray-100
                            bg-white p-5 shadow-sm
                            dark:border-white/10
                            dark:bg-[#191c1b]">
                    <p class="text-sm text-gray-500">
                        Enrolled Students
                    </p>

                    <p class="mt-2 text-3xl font-bold
                              text-[#184d42] dark:text-white">
                        {{ $subject->students_count }}
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-100
                            bg-white p-5 shadow-sm
                            dark:border-white/10
                            dark:bg-[#191c1b]">
                    <p class="text-sm text-gray-500">
                        Attendance Sessions
                    </p>

                    <p class="mt-2 text-3xl font-bold
                              text-[#184d42] dark:text-white">
                        {{ $subject->sessions_count }}
                    </p>
                </div>
            </div>

            <section class="mb-7 overflow-hidden rounded-3xl
                            border border-gray-100 bg-white
                            shadow-sm dark:border-white/10
                            dark:bg-[#191c1b]">

                <div class="border-b border-gray-100 p-5
                            dark:border-white/10">
                    <h2 class="text-xl font-bold text-[#184d42]
                               dark:text-white">
                        Enrolled Students
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">

                        <thead class="bg-gray-50
                                      dark:bg-white/[0.03]">
                            <tr>
                                <th class="px-5 py-4">Student</th>
                                <th class="px-5 py-4">
                                    University Number
                                </th>
                                <th class="px-5 py-4">
                                    Academic Year
                                </th>
                                <th class="px-5 py-4">Dormitory</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100
                                      dark:divide-white/10">

                            @forelse ($students as $student)

                                <tr>
                                    <td class="px-5 py-4 font-medium">
                                        {{ $student->user->name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $student->university_number }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $student->academic_year }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $student->is_dormitory
                                            ? 'Yes'
                                            : 'No' }}
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-5 py-12 text-center
                                               text-gray-500"
                                    >
                                        No students enrolled
                                        in this subject.
                                    </td>
                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($students->hasPages())
                    <div class="border-t border-gray-100 p-5
                                dark:border-white/10">
                        {{ $students->links() }}
                    </div>
                @endif
            </section>

            <section class="overflow-hidden rounded-3xl
                            border border-gray-100 bg-white
                            shadow-sm dark:border-white/10
                            dark:bg-[#191c1b]">

                <div class="border-b border-gray-100 p-5
                            dark:border-white/10">
                    <h2 class="text-xl font-bold text-[#184d42]
                               dark:text-white">
                        Recent Sessions
                    </h2>
                </div>

                <div class="divide-y divide-gray-100
                            dark:divide-white/10">

                    @forelse ($sessions as $session)

                        <a
                            href="{{ route(
                                'professor.sessions.show',
                                $session
                            ) }}"
                            class="flex flex-wrap items-center
                                   justify-between gap-4 p-5
                                   transition hover:bg-gray-50
                                   dark:hover:bg-white/[0.03]"
                        >
                            <div>
                                <p class="font-semibold">
                                    Lecture
                                    {{ $session->lecture_number }}
                                    — {{ $session->lecture_title }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $session->room->room_name }}
                                    •
                                    {{ $session->start_time?->format(
                                        'Y-m-d H:i'
                                    ) }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-500">
                                    {{ $session->attendance_records_count }}
                                    records
                                </span>

                                <span
                                    class="rounded-full px-3 py-1
                                           text-xs font-semibold
                                           {{
                                               $session->status === 'Active'
                                                   ? 'bg-emerald-100 text-emerald-700'
                                                   : 'bg-gray-100 text-gray-600'
                                           }}"
                                >
                                    {{ $session->status }}
                                </span>
                            </div>
                        </a>

                    @empty

                        <div class="p-10 text-center text-gray-500">
                            No sessions created for this subject.
                        </div>

                    @endforelse
                </div>
            </section>
        </div>
    </main>

</x-professor-layout>