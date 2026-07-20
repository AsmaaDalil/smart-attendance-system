<x-professor-layout>

    <main class="p-6 lg:p-8">

        <div class="mx-auto max-w-4xl">

            <div class="mb-7">
                <a
                    href="{{ route('professor.dashboard') }}"
                    class="mb-4 inline-flex items-center gap-2
                           text-sm text-gray-500 transition
                           hover:text-[#184d42]
                           dark:text-gray-400 dark:hover:text-white"
                >
                    ← Back to Dashboard
                </a>

                <h1 class="text-3xl font-bold text-[#184d42]
                           dark:text-white">
                    Start Attendance Session
                </h1>

                <p class="mt-1 text-sm text-gray-500
                          dark:text-gray-400">
                    Enter the lecture details to begin attendance.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200
                            bg-red-50 p-4 text-sm text-red-700
                            dark:border-red-500/20
                            dark:bg-red-500/10
                            dark:text-red-300">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('professor.sessions.store') }}"
                class="rounded-3xl border border-gray-100
                       bg-white p-6 shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]
                       lg:p-8"
            >
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div>
                        <label
                            for="subject_id"
                            class="mb-2 block text-sm font-medium"
                        >
                            Subject
                        </label>

                        <select
                            id="subject_id"
                            name="subject_id"
                            required
                            class="w-full rounded-xl border-gray-200
                                   focus:border-[#184d42]
                                   focus:ring-[#184d42]
                                   dark:border-white/10
                                   dark:bg-[#101312]"
                        >
                            <option value="">Select Subject</option>

                            @foreach ($subjects as $subject)
                                <option
                                    value="{{ $subject->id }}"
                                 @selected(
    old(
        'subject_id',
        request('subject_id')
    ) == $subject->id
)
                                >
                                    {{ $subject->subject_code }}
                                    — {{ $subject->subject_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            for="room_id"
                            class="mb-2 block text-sm font-medium"
                        >
                            Room
                        </label>

                        <select
                            id="room_id"
                            name="room_id"
                            required
                            class="w-full rounded-xl border-gray-200
                                   focus:border-[#184d42]
                                   focus:ring-[#184d42]
                                   dark:border-white/10
                                   dark:bg-[#101312]"
                        >
                            <option value="">Select Room</option>

                            @foreach ($rooms as $room)
                                <option
                                    value="{{ $room->id }}"
                                    @selected(old('room_id') == $room->id)
                                >
                                    {{ $room->room_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            for="lecture_number"
                            class="mb-2 block text-sm font-medium"
                        >
                            Lecture Number
                        </label>

                        <input
                            id="lecture_number"
                            type="number"
                            name="lecture_number"
                            value="{{ old('lecture_number') }}"
                            min="1"
                            required
                            placeholder="Example: 3"
                            class="w-full rounded-xl border-gray-200
                                   focus:border-[#184d42]
                                   focus:ring-[#184d42]
                                   dark:border-white/10
                                   dark:bg-[#101312]"
                        >
                    </div>

                    <div>
                        <label
                            for="duration_minutes"
                            class="mb-2 block text-sm font-medium"
                        >
                            Session Duration
                        </label>

                        <select
                            id="duration_minutes"
                            name="duration_minutes"
                            required
                            class="w-full rounded-xl border-gray-200
                                   focus:border-[#184d42]
                                   focus:ring-[#184d42]
                                   dark:border-white/10
                                   dark:bg-[#101312]"
                        >
                            @foreach ([10, 15, 30, 45, 60, 90, 120] as $minutes)
                                <option
                                    value="{{ $minutes }}"
                                    @selected(
                                        old('duration_minutes', 60)
                                        == $minutes
                                    )
                                >
                                    {{ $minutes }} minutes
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            for="lecture_title"
                            class="mb-2 block text-sm font-medium"
                        >
                            Lecture Title
                        </label>

                        <input
                            id="lecture_title"
                            type="text"
                            name="lecture_title"
                            value="{{ old('lecture_title') }}"
                            required
                            maxlength="255"
                            placeholder="Example: Encryption using DES"
                            class="w-full rounded-xl border-gray-200
                                   focus:border-[#184d42]
                                   focus:ring-[#184d42]
                                   dark:border-white/10
                                   dark:bg-[#101312]"
                        >
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a
                        href="{{ route('professor.dashboard') }}"
                        class="rounded-xl border border-gray-200
                               px-5 py-3 text-sm font-medium
                               transition hover:bg-gray-50
                               dark:border-white/10
                               dark:hover:bg-white/5"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-xl bg-[#184d42]
                               px-6 py-3 text-sm font-semibold
                               text-white transition
                               hover:bg-[#24584d]"
                    >
                        Start Session
                    </button>
                </div>
            </form>
        </div>
    </main>

</x-professor-layout>