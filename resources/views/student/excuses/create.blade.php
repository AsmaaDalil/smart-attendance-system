<x-student-layout>
    <div class="min-h-screen p-6 lg:p-8">

        <div class="mx-auto max-w-4xl">

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
                        Submit Excuse
                    </h1>

                    <p class="mt-2 text-sm text-gray-500
                              dark:text-gray-400">
                        Explain the reason for your absence
                        and attach supporting evidence when available.
                    </p>
                </div>

                <a
                    href="{{ route('student.attendance.index') }}"
                    class="inline-flex items-center justify-center
                           rounded-xl border border-gray-200
                           px-5 py-3 text-sm font-semibold
                           text-gray-600 transition
                           hover:bg-gray-50
                           dark:border-white/10
                           dark:text-gray-300
                           dark:hover:bg-white/5"
                >
                    Back to Attendance
                </a>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-red-200
                            bg-red-50 px-5 py-4
                            dark:border-red-500/20
                            dark:bg-red-500/10">

                    <p class="font-semibold text-red-700
                              dark:text-red-300">
                        Please correct the following errors:
                    </p>

                    <ul class="mt-3 list-disc space-y-1 pl-5
                               text-sm text-red-600
                               dark:text-red-300">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>
                </div>
            @endif

            {{-- Attendance Record Information --}}
            <section
                class="mt-8 rounded-3xl border border-gray-100
                       bg-white p-6 shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]"
            >
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-[#184d42]
                               dark:text-white">
                        Absence Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500
                              dark:text-gray-400">
                        This excuse will be linked to the
                        following attendance record.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">

                    <div class="rounded-2xl bg-gray-50 p-4
                                dark:bg-white/[0.04]">
                        <p class="text-xs font-semibold uppercase
                                  tracking-wide text-gray-400">
                            Subject
                        </p>

                        <p class="mt-2 font-bold text-gray-800
                                  dark:text-white">
                            {{
                                $attendanceRecord
                                    ->session
                                    ?->subject
                                    ?->subject_name
                                ?? 'Unknown Subject'
                            }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-4
                                dark:bg-white/[0.04]">
                        <p class="text-xs font-semibold uppercase
                                  tracking-wide text-gray-400">
                            Lecture
                        </p>

                        <p class="mt-2 font-bold text-gray-800
                                  dark:text-white">
                            Lecture
                            {{
                                $attendanceRecord
                                    ->session
                                    ?->lecture_number
                                ?? '—'
                            }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500
                                  dark:text-gray-400">
                            {{
                                $attendanceRecord
                                    ->session
                                    ?->lecture_title
                                ?? 'No lecture title'
                            }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-4
                                dark:bg-white/[0.04]">
                        <p class="text-xs font-semibold uppercase
                                  tracking-wide text-gray-400">
                            Lecture Date
                        </p>

                        <p class="mt-2 font-bold text-gray-800
                                  dark:text-white">
                            {{
                                $attendanceRecord
                                    ->session
                                    ?->start_time
                                    ?->format('Y-m-d')
                                ?? '—'
                            }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500
                                  dark:text-gray-400">
                            {{
                                $attendanceRecord
                                    ->session
                                    ?->start_time
                                    ?->format('H:i')
                                ?? ''
                            }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-red-50 p-4
                                dark:bg-red-500/10">
                        <p class="text-xs font-semibold uppercase
                                  tracking-wide text-red-400">
                            Attendance Status
                        </p>

                        <p class="mt-2 font-bold text-red-700
                                  dark:text-red-300">
                            {{ $attendanceRecord->status }}
                        </p>

                        <p class="mt-1 text-sm text-red-600
                                  dark:text-red-400">
                            An excuse can only be submitted
                            for an absent record.
                        </p>
                    </div>

                </div>
            </section>

            {{-- Excuse Form --}}
            <section
                class="mt-6 rounded-3xl border border-gray-100
                       bg-white p-6 shadow-sm
                       dark:border-white/10 dark:bg-[#191c1b]"
            >
                <form
                    method="POST"
                    action="{{ route(
                        'student.excuses.store',
                        $attendanceRecord
                    ) }}"
                    enctype="multipart/form-data"
                >
                    @csrf

                    {{-- Reason --}}
                    <div>
                        <label
                            for="reason"
                            class="mb-2 block text-sm font-semibold
                                   text-gray-700 dark:text-gray-200"
                        >
                            Excuse Reason
                            <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            id="reason"
                            name="reason"
                            rows="7"
                            required
                            minlength="10"
                            maxlength="2000"
                            placeholder="Explain the reason for your absence..."
                            class="w-full resize-none rounded-2xl
                                   border-gray-200 bg-white
                                   text-sm text-gray-700
                                   placeholder:text-gray-400
                                   focus:border-[#184d42]
                                   focus:ring-[#184d42]
                                   dark:border-white/10
                                   dark:bg-[#101312]
                                   dark:text-gray-200"
                        >{{ old('reason') }}</textarea>

                        <div class="mt-2 flex items-center
                                    justify-between gap-4">

                            <p class="text-xs text-gray-500
                                      dark:text-gray-400">
                                Minimum 10 characters and
                                maximum 2000 characters.
                            </p>

                            <p
                                id="reason-counter"
                                class="text-xs font-semibold
                                       text-gray-400"
                            >
                                0 / 2000
                            </p>
                        </div>

                        @error('reason')
                            <p class="mt-2 text-sm font-medium
                                      text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Attachment --}}
                    <div class="mt-6">
                        <label
                            for="attachment"
                            class="mb-2 block text-sm font-semibold
                                   text-gray-700 dark:text-gray-200"
                        >
                            Supporting Attachment
                        </label>

                        <div class="rounded-2xl border-2
                                    border-dashed border-gray-200
                                    p-6 text-center
                                    transition hover:border-[#184d42]
                                    dark:border-white/10">

                            <input
                                id="attachment"
                                name="attachment"
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="block w-full text-sm
                                       text-gray-500
                                       file:mr-4 file:rounded-xl
                                       file:border-0
                                       file:bg-[#184d42]
                                       file:px-4 file:py-2
                                       file:text-sm
                                       file:font-semibold
                                       file:text-white
                                       hover:file:bg-[#24584d]
                                       dark:text-gray-400"
                            >

                            <p class="mt-3 text-xs text-gray-500
                                      dark:text-gray-400">
                                Allowed formats:
                                PDF, JPG, JPEG, PNG.
                                Maximum size: 5 MB.
                            </p>

                            <p
                                id="selected-file"
                                class="mt-3 hidden text-sm
                                       font-semibold text-[#184d42]
                                       dark:text-[#d4a373]"
                            ></p>
                        </div>

                        @error('attachment')
                            <p class="mt-2 text-sm font-medium
                                      text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Notice --}}
                    <div class="mt-6 rounded-2xl
                                border border-amber-200
                                bg-amber-50 px-5 py-4
                                dark:border-amber-500/20
                                dark:bg-amber-500/10">

                        <p class="font-semibold text-amber-700
                                  dark:text-amber-300">
                            Important
                        </p>

                        <p class="mt-1 text-sm text-amber-600
                                  dark:text-amber-400">
                            After submission, your excuse will
                            remain Pending until the professor
                            approves or rejects it.
                        </p>
                    </div>

                    {{-- Buttons --}}
                    <div class="mt-8 flex flex-col-reverse gap-3
                                sm:flex-row sm:justify-end">

                        <a
                            href="{{ route(
                                'student.attendance.index'
                            ) }}"
                            class="inline-flex items-center
                                   justify-center rounded-xl
                                   border border-gray-200
                                   px-6 py-3 text-sm font-semibold
                                   text-gray-600 transition
                                   hover:bg-gray-50
                                   dark:border-white/10
                                   dark:text-gray-300
                                   dark:hover:bg-white/5"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center
                                   justify-center rounded-xl
                                   bg-[#184d42] px-6 py-3
                                   text-sm font-semibold text-white
                                   transition hover:bg-[#24584d]"
                        >
                            Submit Excuse
                        </button>
                    </div>
                </form>
            </section>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener(
                'DOMContentLoaded',
                function () {
                    const reasonInput =
                        document.getElementById('reason');

                    const reasonCounter =
                        document.getElementById(
                            'reason-counter'
                        );

                    const attachmentInput =
                        document.getElementById(
                            'attachment'
                        );

                    const selectedFile =
                        document.getElementById(
                            'selected-file'
                        );

                    function updateReasonCounter() {
                        reasonCounter.textContent =
                            reasonInput.value.length
                            + ' / 2000';
                    }

                    reasonInput.addEventListener(
                        'input',
                        updateReasonCounter
                    );

                    updateReasonCounter();

                    attachmentInput.addEventListener(
                        'change',
                        function () {
                            const file =
                                attachmentInput.files[0];

                            if (! file) {
                                selectedFile.textContent = '';

                                selectedFile.classList.add(
                                    'hidden'
                                );

                                return;
                            }

                            selectedFile.textContent =
                                'Selected file: '
                                + file.name;

                            selectedFile.classList.remove(
                                'hidden'
                            );
                        }
                    );
                }
            );
        </script>
    @endpush
</x-student-layout>