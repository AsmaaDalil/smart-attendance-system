<x-professor-layout>

    <main class="p-6 lg:p-8">

        <div class="mx-auto max-w-7xl">

            <div class="mb-7 flex flex-wrap
                        items-center justify-between gap-4">

                <div>
                    <h1 class="text-3xl font-bold
                               text-[#184d42] dark:text-white">
                        My Subjects
                    </h1>

                    <p class="mt-1 text-sm text-gray-500
                              dark:text-gray-400">
                        View your assigned subjects,
                        students and attendance sessions.
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

            <div class="grid grid-cols-1 gap-5
                        md:grid-cols-2 xl:grid-cols-3">

                @forelse ($subjects as $subject)

                    <article
                        class="rounded-3xl border border-gray-100
                               bg-white p-6 shadow-sm transition
                               hover:-translate-y-1 hover:shadow-md
                               dark:border-white/10
                               dark:bg-[#191c1b]"
                    >
                        <div class="flex items-start
                                    justify-between gap-4">

                            <div>
                                <span
                                    class="inline-flex rounded-lg
                                           bg-[#d4a373]/15
                                           px-3 py-1 text-xs
                                           font-bold text-[#b77d51]
                                           dark:text-[#e3b78e]"
                                >
                                    {{ $subject->subject_code }}
                                </span>

                                <h2 class="mt-4 text-xl font-bold
                                           text-[#184d42]
                                           dark:text-white">
                                    {{ $subject->subject_name }}
                                </h2>
                            </div>

                            <div
                                class="flex h-12 w-12 items-center
                                       justify-center rounded-2xl
                                       bg-[#184d42]/10
                                       font-bold text-[#184d42]
                                       dark:bg-white/10
                                       dark:text-white"
                            >
                                {{ $subject->students_count }}
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-3">

                            <div class="rounded-2xl bg-gray-50 p-4
                                        dark:bg-white/[0.04]">
                                <p class="text-xs text-gray-500">
                                    Students
                                </p>

                                <p class="mt-1 text-xl font-bold
                                          text-[#184d42]
                                          dark:text-white">
                                    {{ $subject->students_count }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-gray-50 p-4
                                        dark:bg-white/[0.04]">
                                <p class="text-xs text-gray-500">
                                    Sessions
                                </p>

                                <p class="mt-1 text-xl font-bold
                                          text-[#184d42]
                                          dark:text-white">
                                    {{ $subject->sessions_count }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">

                            <a
                                href="{{ route(
                                    'professor.subjects.show',
                                    $subject
                                ) }}"
                                class="flex-1 rounded-xl border
                                       border-[#184d42]/20
                                       px-4 py-3 text-center
                                       text-sm font-semibold
                                       text-[#184d42] transition
                                       hover:bg-[#184d42]
                                       hover:text-white
                                       dark:border-white/15
                                       dark:text-white"
                            >
                                View Details
                            </a>

                            <a
                                href="{{
                                    route('professor.sessions.create')
                                    .'?subject_id='.$subject->id
                                }}"
                                class="flex-1 rounded-xl bg-[#184d42]
                                       px-4 py-3 text-center
                                       text-sm font-semibold
                                       text-white transition
                                       hover:bg-[#24584d]"
                            >
                                Start Session
                            </a>
                        </div>
                    </article>

                @empty

                    <div
                        class="col-span-full rounded-3xl border
                               border-dashed border-gray-300
                               bg-white px-6 py-16 text-center
                               dark:border-white/10
                               dark:bg-[#191c1b]"
                    >
                        <h2 class="text-xl font-bold
                                   text-[#184d42]
                                   dark:text-white">
                            No subjects assigned
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            Contact the administrator to assign
                            subjects to your account.
                        </p>
                    </div>

                @endforelse
            </div>

            @if ($subjects->hasPages())
                <div class="mt-6">
                    {{ $subjects->links() }}
                </div>
            @endif
        </div>
    </main>

</x-professor-layout>