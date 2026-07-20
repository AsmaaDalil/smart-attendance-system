<x-professor-layout>

    <main class="p-6 lg:p-8">

        <div class="mx-auto max-w-7xl">

            <div class="mb-7 flex flex-wrap
                        items-center justify-between gap-4">

                <div>
                    <h1 class="text-3xl font-bold
                               text-[#184d42] dark:text-white">
                        Attendance Sessions
                    </h1>

                    <p class="mt-1 text-sm text-gray-500
                              dark:text-gray-400">
                        Monitor active sessions and review
                        your previous lectures.
                    </p>
                </div>

                <a
                    href="{{ route('professor.sessions.create') }}"
                    class="rounded-xl bg-[#184d42]
                           px-5 py-3 text-sm font-semibold
                           text-white transition
                           hover:bg-[#24584d]"
                >
                    Start New Session
                </a>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-2xl
                            bg-emerald-50 p-4
                            text-sm text-emerald-700
                            dark:bg-emerald-500/10
                            dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mb-5 rounded-2xl
                            bg-blue-50 p-4
                            text-sm text-blue-700
                            dark:bg-blue-500/10
                            dark:text-blue-300">
                    {{ session('info') }}
                </div>
            @endif

            <section class="mb-8">

                <div class="mb-4 flex items-center gap-3">
                    <h2 class="text-xl font-bold
                               text-[#184d42] dark:text-white">
                        Active Sessions
                    </h2>

                    <span class="rounded-full bg-emerald-100
                                 px-3 py-1 text-xs font-bold
                                 text-emerald-700">
                        {{ $activeSessions->count() }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-5
                            lg:grid-cols-2">

                    @forelse ($activeSessions as $session)

                        <article
                            class="rounded-3xl border
                                   border-emerald-200
                                   bg-white p-6 shadow-sm
                                   dark:border-emerald-500/20
                                   dark:bg-[#191c1b]"
                        >
                            <div class="flex items-start
                                        justify-between gap-4">

                                <div>
                                    <span
                                        class="inline-flex rounded-full
                                               bg-emerald-100 px-3 py-1
                                               text-xs font-bold
                                               text-emerald-700"
                                    >
                                        Active
                                    </span>

                                    <h3 class="mt-3 text-xl font-bold
                                               text-[#184d42]
                                               dark:text-white">
                                        {{ $session->lecture_title }}
                                    </h3>

                                    <p class="mt-1 text-sm
                                              text-gray-500">
                                        {{ $session->subject->subject_code }}
                                        —
                                        {{ $session->subject->subject_name }}
                                    </p>
                                </div>

                                <div class="rounded-2xl
                                            bg-[#184d42]/10
                                            px-4 py-3 text-center">
                                    <p class="text-xs text-gray-500">
                                        Attendance
                                    </p>

                                    <p class="mt-1 text-xl font-bold
                                              text-[#184d42]
                                              dark:text-white">
                                        {{ $session->attendance_records_count }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3
                                        text-sm">

                                <div class="rounded-xl bg-gray-50 p-3
                                            dark:bg-white/[0.04]">
                                    <p class="text-xs text-gray-500">
                                        Room
                                    </p>

                                    <p class="mt-1 font-semibold">
                                        {{ $session->room->room_name }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-gray-50 p-3
                                            dark:bg-white/[0.04]">
                                    <p class="text-xs text-gray-500">
                                        Ends At
                                    </p>

                                    <p class="mt-1 font-semibold">
                                        {{ $session->end_time->format(
                                            'H:i'
                                        ) }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-3">

                                <a
                                    href="{{ route(
                                        'professor.sessions.show',
                                        $session
                                    ) }}"
                                    class="flex-1 rounded-xl
                                           bg-[#184d42] px-4 py-3
                                           text-center text-sm
                                           font-semibold text-white
                                           hover:bg-[#24584d]"
                                >
                                    Open Session
                                </a>
                                <div class="flex-1">

    <button
        type="button"
        onclick="
            document
                .getElementById(
                    'end-session-{{ $session->id }}'
                )
                .showModal()
        "
        class="w-full rounded-xl border
               border-red-200 px-4 py-3
               text-sm font-semibold text-red-600
               transition hover:bg-red-50
               dark:border-red-500/20
               dark:hover:bg-red-500/10"
    >
        End Session
    </button>

    <dialog
        id="end-session-{{ $session->id }}"
        class="m-auto w-[calc(100%-2rem)]
               max-w-md rounded-3xl
               bg-white p-0 shadow-2xl
               backdrop:bg-black/50
               dark:bg-[#191c1b]
               dark:text-white"
    >
        <div class="p-6">

            <div
                class="flex h-12 w-12
                       items-center justify-center
                       rounded-2xl bg-red-100
                       text-xl font-bold text-red-600
                       dark:bg-red-500/10"
            >
                !
            </div>

            <h3
                class="mt-5 text-xl font-bold
                       text-[#184d42] dark:text-white"
            >
                End Attendance Session?
            </h3>

            <p
                class="mt-2 text-sm leading-6
                       text-gray-500 dark:text-gray-400"
            >
                Students who have not recorded their
                attendance will automatically be marked
                as absent.
            </p>

            <div class="mt-6 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="
                        document
                            .getElementById(
                                'end-session-{{ $session->id }}'
                            )
                            .close()
                    "
                    class="rounded-xl border
                           border-gray-200 px-5 py-3
                           text-sm font-semibold
                           transition hover:bg-gray-50
                           dark:border-white/10
                           dark:hover:bg-white/5"
                >
                    Cancel
                </button>

                <form
                    method="POST"
                    action="{{ route(
                        'professor.sessions.end',
                        $session
                    ) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl bg-red-600
                               px-5 py-3 text-sm
                               font-semibold text-white
                               transition hover:bg-red-700"
                    >
                        Yes, End Session
                    </button>
                </form>
            </div>
        </div>
    </dialog>
</div>
                    @empty

                        <div class="col-span-full rounded-3xl
                                    border border-dashed
                                    border-gray-300 bg-white
                                    px-6 py-14 text-center
                                    dark:border-white/10
                                    dark:bg-[#191c1b]">

                            <h3 class="text-lg font-bold
                                       text-[#184d42]
                                       dark:text-white">
                                No active sessions
                            </h3>

                            <p class="mt-2 text-sm text-gray-500">
                                Start a session when your lecture begins.
                            </p>
                        </div>

                    @endforelse
                </div>
            </section>

            <section class="overflow-hidden rounded-3xl
                            border border-gray-100 bg-white
                            shadow-sm dark:border-white/10
                            dark:bg-[#191c1b]">

                <div class="border-b border-gray-100 p-5
                            dark:border-white/10">
                    <h2 class="text-xl font-bold text-[#184d42]
                               dark:text-white">
                        Previous Sessions
                    </h2>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="bg-gray-50
                                      dark:bg-white/[0.03]">
                            <tr>
                                <th class="px-5 py-4">Subject</th>
                                <th class="px-5 py-4">Lecture</th>
                                <th class="px-5 py-4">Room</th>
                                <th class="px-5 py-4">Date</th>
                                <th class="px-5 py-4">Records</th>
                                <th class="px-5 py-4"></th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100
                                      dark:divide-white/10">

                            @forelse ($endedSessions as $session)

                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold">
                                            {{ $session->subject->subject_name }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $session->subject->subject_code }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $session->lecture_number }}
                                        —
                                        {{ $session->lecture_title }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $session->room->room_name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $session->start_time->format(
                                            'Y-m-d H:i'
                                        ) }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $session->attendance_records_count }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <a
                                            href="{{ route(
                                                'professor.sessions.show',
                                                $session
                                            ) }}"
                                            class="font-semibold
                                                   text-[#184d42]
                                                   dark:text-[#8bb9ac]"
                                        >
                                            View
                                        </a>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="6"
                                        class="px-5 py-12 text-center
                                               text-gray-500"
                                    >
                                        No previous sessions.
                                    </td>
                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($endedSessions->hasPages())
                    <div class="border-t border-gray-100 p-5
                                dark:border-white/10">
                        {{ $endedSessions->links() }}
                    </div>
                @endif
            </section>
        </div>
    </main>

</x-professor-layout>