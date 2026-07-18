<x-admin-layout>

    <div class="p-6 lg:p-8">

        {{-- Page header --}}
        <header class="flex items-center justify-between mb-7">

            <div>
                <h1 class="text-3xl font-bold text-[#1a4a40]
                           dark:text-white">
                    Dashboard
                </h1>

                <p class="text-sm text-gray-500
                          dark:text-gray-400 mt-1">
                    Overview of the smart attendance system
                </p>
            </div>

            <div class="flex items-center gap-3">

                <div class="hidden sm:block text-right">
                    <p class="text-sm font-semibold text-[#1a4a40]
                              dark:text-white">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-gray-500
                              dark:text-gray-400">
                        Administrator
                    </p>
                </div>

                <x-theme-toggle />

            </div>

        </header>

        {{-- Welcome banner --}}
        <section
            class="relative overflow-hidden
                   bg-gradient-to-r
                   from-[#1a4a40] to-[#24584d]
                   rounded-3xl px-7 py-7
                   text-white shadow-lg mb-7"
        >
            <div class="relative z-10">

                <p class="text-xs uppercase tracking-[0.2em]
                          text-white/55 font-semibold">
                    Smart Attendance
                </p>

                <h2 class="text-2xl font-bold mt-2">
                    Welcome back,
                    {{ auth()->user()->name }}
                </h2>

                <p class="text-sm text-white/70 mt-2">
                    Manage academic data and monitor attendance
                    from one place.
                </p>

            </div>

            <div
                class="absolute -right-14 -top-20
                       w-56 h-56 rounded-full
                       bg-[#d4a373]/15">
            </div>
        </section>

        {{-- Statistics --}}
        <section class="grid grid-cols-1 sm:grid-cols-2
                        xl:grid-cols-4 gap-5 mb-7">

            <div class="bg-white dark:bg-[#191c1b]
                        border border-gray-100
                        dark:border-white/10
                        rounded-2xl p-5 shadow-sm">

                <p class="text-sm text-gray-500
                          dark:text-gray-400">
                    Students
                </p>

                <p class="text-3xl font-bold text-[#1a4a40]
                          dark:text-white mt-2">
                    {{ $studentsCount }}
                </p>
            </div>

            <div class="bg-white dark:bg-[#191c1b]
                        border border-gray-100
                        dark:border-white/10
                        rounded-2xl p-5 shadow-sm">

                <p class="text-sm text-gray-500
                          dark:text-gray-400">
                    Attendance Rate
                </p>

                <p class="text-3xl font-bold text-[#d4a373] mt-2">
                    {{ $attendanceRate }}%
                </p>
            </div>

            <div class="bg-white dark:bg-[#191c1b]
                        border border-gray-100
                        dark:border-white/10
                        rounded-2xl p-5 shadow-sm">

                <p class="text-sm text-gray-500
                          dark:text-gray-400">
                    Subjects
                </p>

                <p class="text-3xl font-bold text-[#1a4a40]
                          dark:text-white mt-2">
                    {{ $subjectsCount }}
                </p>
            </div>

            <div class="bg-white dark:bg-[#191c1b]
                        border border-gray-100
                        dark:border-white/10
                        rounded-2xl p-5 shadow-sm">

                <p class="text-sm text-gray-500
                          dark:text-gray-400">
                    QR Scans
                </p>

                <p class="text-3xl font-bold text-[#d4a373] mt-2">
                    {{ $qrScans }}
                </p>
            </div>

        </section>

        {{-- Recent attendance --}}
        <section class="bg-white dark:bg-[#191c1b]
                        border border-gray-100
                        dark:border-white/10
                        rounded-2xl shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100
                        dark:border-white/10">

                <h3 class="font-semibold text-[#1a4a40]
                           dark:text-white">
                    Recent Attendance
                </h3>

                <p class="text-xs text-gray-500
                          dark:text-gray-400 mt-1">
                    Latest attendance activity in the system
                </p>

            </div>

            @if ($recentAttendance->isEmpty())

                <div class="px-6 py-12 text-center">

                    <p class="font-medium text-gray-600
                              dark:text-gray-300">
                        No attendance records yet
                    </p>

                    <p class="text-sm text-gray-400 mt-1">
                        Records will appear after professors
                        start attendance sessions.
                    </p>

                </div>

            @else

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-50
                                      dark:bg-white/[0.03]">

                            <tr class="text-left text-xs
                                       text-gray-500
                                       dark:text-gray-400">

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

                        <tbody class="divide-y divide-gray-100
                                      dark:divide-white/10">

                            @foreach ($recentAttendance as $record)

                                <tr class="text-sm">

                                    <td class="px-6 py-4
                                               text-gray-700
                                               dark:text-gray-200">
                                        {{ $record->student->user->name }}
                                    </td>

                                    <td class="px-6 py-4
                                               text-gray-600
                                               dark:text-gray-300">
                                        {{ $record->session->subject->subject_name }}
                                    </td>

                                    <td class="px-6 py-4
                                               text-gray-500
                                               dark:text-gray-400">
                                        {{ $record->created_at->format('Y-m-d H:i') }}
                                    </td>

                                    <td class="px-6 py-4">

                                        <span class="px-3 py-1 rounded-full
                                            text-xs font-medium
                                            {{
                                                in_array(
                                                    $record->status,
                                                    ['Present', 'Late']
                                                )
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-700'
                                            }}">

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