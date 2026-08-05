<x-admin-layout>

    <div class="w-full min-w-0 max-w-none p-4 sm:p-6 lg:p-8">

        {{-- Page header --}}
        <header
            class="mb-6 flex items-start justify-between
                   gap-4 sm:items-center"
        >
            <div class="min-w-0">
                <h1
                    class="text-2xl font-bold leading-tight
                           text-[#1a4a40]
                           sm:text-3xl dark:text-white"
                >
                    Dashboard
                </h1>

                <p
                    class="mt-1 text-sm text-gray-500
                           dark:text-gray-400"
                >
                    Overview of the smart attendance system
                </p>
            </div>

            <div class="flex flex-shrink-0 items-center gap-3">

                <div class="hidden text-right sm:block">
                    <p
                        class="max-w-44 truncate text-sm font-semibold
                               text-[#1a4a40] dark:text-white"
                    >
                        {{ auth()->user()->name }}
                    </p>

                    <p
                        class="text-xs text-gray-500
                               dark:text-gray-400"
                    >
                        Administrator
                    </p>
                </div>

                <x-theme-toggle />
            </div>
        </header>

        {{-- Welcome banner --}}
        <section
            class="relative mb-6 min-w-0 overflow-hidden
                   rounded-3xl bg-gradient-to-r
                   from-[#1a4a40] to-[#24584d]
                   px-5 py-6 text-white shadow-lg
                   sm:px-7 sm:py-7"
        >
            <div class="relative z-10 max-w-3xl">

                <p
                    class="text-[11px] font-semibold uppercase
                           tracking-[0.2em] text-white/55
                           sm:text-xs"
                >
                    Smart Attendance
                </p>

                <h2 class="mt-2 text-xl font-bold sm:text-2xl">
                    Welcome back,
                    {{ auth()->user()->name }}
                </h2>

                <p
                    class="mt-2 text-sm leading-6
                           text-white/75"
                >
                    Manage academic data and monitor attendance
                    from one place.
                </p>
            </div>

            <div
                class="absolute -right-16 -top-20
                       h-52 w-52 rounded-full
                       bg-[#d4a373]/15
                       sm:h-56 sm:w-56"
            ></div>
        </section>

        {{-- Statistics --}}
        <section
            class="mb-7 grid min-w-0 grid-cols-1 gap-4
                   sm:grid-cols-2 xl:grid-cols-4"
        >
            <article
                class="rounded-2xl border border-gray-100
                       bg-white p-5 shadow-sm
                       dark:border-white/10
                       dark:bg-[#191c1b]"
            >
                <p
                    class="text-sm text-gray-500
                           dark:text-gray-400"
                >
                    Students
                </p>

                <p
                    class="mt-2 text-3xl font-bold
                           text-[#1a4a40] dark:text-white"
                >
                    {{ $studentsCount }}
                </p>
            </article>

            <article
                class="rounded-2xl border border-gray-100
                       bg-white p-5 shadow-sm
                       dark:border-white/10
                       dark:bg-[#191c1b]"
            >
                <p
                    class="text-sm text-gray-500
                           dark:text-gray-400"
                >
                    Attendance Rate
                </p>

                <p
                    class="mt-2 text-3xl font-bold
                           text-[#d4a373]"
                >
                    {{ $attendanceRate }}%
                </p>
            </article>

            <article
                class="rounded-2xl border border-gray-100
                       bg-white p-5 shadow-sm
                       dark:border-white/10
                       dark:bg-[#191c1b]"
            >
                <p
                    class="text-sm text-gray-500
                           dark:text-gray-400"
                >
                    Subjects
                </p>

                <p
                    class="mt-2 text-3xl font-bold
                           text-[#1a4a40] dark:text-white"
                >
                    {{ $subjectsCount }}
                </p>
            </article>

            <article
                class="rounded-2xl border border-gray-100
                       bg-white p-5 shadow-sm
                       dark:border-white/10
                       dark:bg-[#191c1b]"
            >
                <p
                    class="text-sm text-gray-500
                           dark:text-gray-400"
                >
                    QR Scans
                </p>

                <p
                    class="mt-2 text-3xl font-bold
                           text-[#d4a373]"
                >
                    {{ $qrScans }}
                </p>
            </article>
        </section>

        {{-- Recent attendance --}}
        <section
            class="min-w-0 overflow-hidden rounded-2xl
                   border border-gray-100 bg-white
                   shadow-sm dark:border-white/10
                   dark:bg-[#191c1b]"
        >
            <div
                class="border-b border-gray-100
                       px-5 py-5 sm:px-6
                       dark:border-white/10"
            >
                <h3
                    class="font-semibold text-[#1a4a40]
                           dark:text-white"
                >
                    Recent Attendance
                </h3>

                <p
                    class="mt-1 text-xs text-gray-500
                           dark:text-gray-400"
                >
                    Latest attendance activity in the system
                </p>
            </div>

            @if ($recentAttendance->isEmpty())

                <div class="px-5 py-12 text-center sm:px-6">

                    <p
                        class="font-medium text-gray-600
                               dark:text-gray-300"
                    >
                        No attendance records yet
                    </p>

                    <p class="mt-1 text-sm text-gray-400">
                        Records will appear after professors
                        start attendance sessions.
                    </p>
                </div>

            @else

                {{-- Mobile: cards --}}
                <div class="space-y-3 p-4 sm:hidden">

                    @foreach ($recentAttendance as $record)

                        @php
                            $isPresent = in_array(
                                $record->status,
                                ['Present', 'Late']
                            );
                        @endphp

                        <article
                            class="rounded-2xl border
                                   border-gray-100 bg-gray-50
                                   p-4 dark:border-white/10
                                   dark:bg-white/[0.04]"
                        >
                            <div
                                class="flex items-start
                                       justify-between gap-3"
                            >
                                <div class="min-w-0">
                                    <p
                                        class="break-words font-semibold
                                               text-[#1a4a40]
                                               dark:text-white"
                                    >
                                        {{ $record->student->user->name }}
                                    </p>

                                    <p
                                        class="mt-1 break-words text-xs
                                               text-gray-500
                                               dark:text-gray-400"
                                    >
                                        {{ $record->session->subject->subject_name }}
                                    </p>
                                </div>

                                <span
                                    class="flex-shrink-0 rounded-full
                                           px-3 py-1 text-xs font-medium
                                           {{
                                               $isPresent
                                                   ? 'bg-green-100 text-green-700'
                                                   : 'bg-red-100 text-red-700'
                                           }}"
                                >
                                    {{ $record->status }}
                                </span>
                            </div>

                            <div
                                class="mt-4 border-t border-gray-100
                                       pt-3 text-xs text-gray-500
                                       dark:border-white/10
                                       dark:text-gray-400"
                            >
                                {{ $record->created_at->format('Y-m-d H:i') }}
                            </div>
                        </article>

                    @endforeach
                </div>

                {{-- Tablet and laptop: table --}}
                <div class="hidden overflow-x-auto sm:block">

                    <table class="w-full min-w-[720px] text-sm">

                        <thead
                            class="bg-gray-50 text-left
                                   text-gray-600
                                   dark:bg-white/[0.03]
                                   dark:text-gray-300"
                        >
                            <tr>
                                <th class="px-6 py-4 font-medium">
                                    Student
                                </th>

                                <th class="px-6 py-4 font-medium">
                                    Subject
                                </th>

                                <th class="px-6 py-4 font-medium">
                                    Date
                                </th>

                                <th class="px-6 py-4 font-medium">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-gray-100
                                   dark:divide-white/10"
                        >
                            @foreach ($recentAttendance as $record)

                                @php
                                    $isPresent = in_array(
                                        $record->status,
                                        ['Present', 'Late']
                                    );
                                @endphp

                                <tr>
                                    <td
                                        class="px-6 py-4
                                               text-gray-700
                                               dark:text-gray-200"
                                    >
                                        {{ $record->student->user->name }}
                                    </td>

                                    <td
                                        class="px-6 py-4
                                               text-gray-600
                                               dark:text-gray-300"
                                    >
                                        {{ $record->session->subject->subject_name }}
                                    </td>

                                    <td
                                        class="whitespace-nowrap
                                               px-6 py-4
                                               text-gray-500
                                               dark:text-gray-400"
                                    >
                                        {{ $record->created_at->format('Y-m-d H:i') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full
                                                   px-3 py-1
                                                   text-xs font-medium
                                                   {{
                                                       $isPresent
                                                           ? 'bg-green-100 text-green-700'
                                                           : 'bg-red-100 text-red-700'
                                                   }}"
                                        >
                                            {{ $record->status }}
                                        </span>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
        </section>
    </div>

</x-admin-layout>
