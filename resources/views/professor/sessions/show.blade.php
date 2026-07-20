<x-professor-layout>
    <main class="p-6 lg:p-8">
        <div class="mx-auto max-w-5xl">

            <a href="{{ route('professor.sessions.index') }}"
               class="mb-5 inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition hover:text-[#184d42] dark:border-white/10 dark:bg-[#191c1b] dark:text-gray-300">
                <span class="text-lg">←</span>
                <span>Back to Sessions</span>
            </a>

            @if (session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 font-bold dark:bg-emerald-500/20">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-3xl font-bold text-[#184d42] dark:text-white">
                    {{ $session->lecture_title }}
                </h1>

                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $session->status === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600 dark:bg-white/10 dark:text-gray-300' }}">
                    {{ $session->status }}
                </span>
            </div>

            <p class="mt-2 text-gray-500 dark:text-gray-400">
                {{ $session->subject->subject_name }} — Lecture {{ $session->lecture_number }}
            </p>

            <div class="mt-7 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#191c1b]">
                    <p class="text-sm text-gray-500">Room</p>
                    <p class="mt-2 font-bold">{{ $session->room->room_name }}</p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#191c1b]">
                    <p class="text-sm text-gray-500">Start Time</p>
                    <p class="mt-2 font-bold">{{ $session->start_time?->format('Y-m-d H:i') }}</p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#191c1b]">
                    <p class="text-sm text-gray-500">Attendance</p>
                    <p class="mt-2 font-bold">{{ $session->attendanceRecords->count() }}</p>
                </div>
            </div>

            @if ($session->status === 'Active')
                <div class="mt-7 flex justify-end">
                    <button type="button"
                            onclick="document.getElementById('end-session-dialog').showModal()"
                            class="rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                        End Session
                    </button>

                    <dialog id="end-session-dialog"
                            class="m-auto w-[calc(100%-2rem)] max-w-md rounded-3xl bg-white p-0 shadow-2xl backdrop:bg-black/50 dark:bg-[#191c1b] dark:text-white">
                        <div class="p-6">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-xl font-bold text-red-600 dark:bg-red-500/10">!</div>

                            <h3 class="mt-5 text-xl font-bold text-[#184d42] dark:text-white">
                                End Attendance Session?
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                                This action will close the session. Every student who has not attended will automatically be marked as absent.
                            </p>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button"
                                        onclick="document.getElementById('end-session-dialog').close()"
                                        class="rounded-xl border border-gray-200 px-5 py-3 text-sm font-semibold transition hover:bg-gray-50 dark:border-white/10 dark:hover:bg-white/5">
                                    Cancel
                                </button>

                                <form method="POST" action="{{ route('professor.sessions.end', $session) }}">
                                    @csrf
                                    <button type="submit" class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                                        Yes, End Session
                                    </button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                </div>
            @endif

            <div class="mt-7 overflow-hidden rounded-3xl bg-[#184d42] text-white shadow-lg">
                @if ($session->status === 'Active')
                    <div id="qr-session-area"
                         data-refresh-url="{{ route('professor.sessions.qr', $session) }}"
                         data-csrf-token="{{ csrf_token() }}"
                         class="grid items-center gap-8 p-7 md:grid-cols-[1fr_auto] lg:p-10">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#d4a373]">Live Attendance</p>
                            <h2 class="mt-3 text-3xl font-bold">Scan to Record Attendance</h2>
                            <p class="mt-3 max-w-xl text-sm leading-6 text-white/65">
                                This QR code changes automatically every 15 seconds. Students must scan the current code while they are inside the room.
                            </p>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <div class="rounded-xl bg-white/10 px-4 py-3">
                                    <p class="text-xs text-white/50">Session Ends</p>
                                    <p class="mt-1 font-bold">{{ $session->end_time->format('H:i') }}</p>
                                </div>

                                <div class="rounded-xl bg-white/10 px-4 py-3">
                                    <p class="text-xs text-white/50">QR Refresh</p>
                                    <p class="mt-1 font-bold"><span id="qr-countdown">15</span> seconds</p>
                                </div>

                                <div class="rounded-xl bg-white/10 px-4 py-3">
                                    <p class="text-xs text-white/50">Attendance</p>
                                    <p class="mt-1 font-bold">{{ $session->attendanceRecords->count() }} students</p>
                                </div>
                            </div>

                            <p id="qr-status" class="mt-5 text-sm text-white/60">
                                Generating secure QR code...
                            </p>
                        </div>

                        <div class="flex min-h-[300px] min-w-[300px] items-center justify-center rounded-3xl bg-white p-5">
                            <canvas id="attendance-qr" width="260" height="260"></canvas>
                        </div>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-sm text-white/60">Attendance Session</p>
                        <h2 class="mt-3 text-2xl font-bold">This session has ended</h2>
                        <p class="mt-2 text-sm text-white/60">The QR code is no longer available.</p>
                    </div>
                @endif
            </div>

            <div class="mt-7 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-white/10 dark:bg-[#191c1b]">
                <div class="border-b border-gray-100 p-5 dark:border-white/10">
                    <h2 class="text-xl font-bold text-[#184d42] dark:text-white">Attendance Records</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-white/[0.03]">
                            <tr>
                                <th class="px-5 py-4">Student</th>
                                <th class="px-5 py-4">University Number</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Scanned At</th>
                                <th class="px-5 py-4">Distance</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                            @forelse ($session->attendanceRecords as $record)
                                <tr>
                                    <td class="px-5 py-4">{{ $record->student->user->name }}</td>
                                    <td class="px-5 py-4">{{ $record->student->university_number }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ match ($record->status) { 'Present' => 'bg-emerald-100 text-emerald-700', 'Late' => 'bg-amber-100 text-amber-700', 'Excused' => 'bg-blue-100 text-blue-700', default => 'bg-red-100 text-red-700' } }}">
                                            {{ $record->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">{{ $record->scanned_at ? $record->scanned_at->format('H:i:s') : '—' }}</td>
                                    <td class="px-5 py-4">{{ $record->distance_meters !== null ? $record->distance_meters.' m' : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-gray-500">No attendance records yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @if ($session->status === 'Active')
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const qrArea = document.getElementById('qr-session-area');
                    const canvas = document.getElementById('attendance-qr');
                    const countdownElement = document.getElementById('qr-countdown');
                    const statusElement = document.getElementById('qr-status');
                    const refreshUrl = qrArea.dataset.refreshUrl;
                    const csrfToken = qrArea.dataset.csrfToken;

                    let secondsRemaining = 15;
                    let refreshInterval = null;
                    let countdownInterval = null;

                    async function generateQrCode() {
                        try {
                            statusElement.textContent = 'Generating secure QR code...';
                            statusElement.classList.remove('text-red-300');

                            const response = await fetch(refreshUrl, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                throw new Error(data.message || 'Unable to generate QR code.');
                            }

                            if (!window.QRCode) {
                                throw new Error('QR library was not loaded. Run npm install qrcode and npm run dev.');
                            }

                            await window.QRCode.toCanvas(canvas, data.payload, {
                                width: 260,
                                margin: 1,
                                color: {
                                    dark: '#184d42',
                                    light: '#ffffff',
                                },
                            });

                            secondsRemaining = 15;
                            countdownElement.textContent = secondsRemaining;
                            statusElement.textContent = 'QR code is active and ready to scan.';
                        } catch (error) {
                            statusElement.textContent = error.message;
                            statusElement.classList.add('text-red-300');
                            clearInterval(refreshInterval);
                            clearInterval(countdownInterval);
                        }
                    }

                    function startCountdown() {
                        countdownInterval = setInterval(function () {
                            secondsRemaining--;

                            if (secondsRemaining < 0) {
                                secondsRemaining = 15;
                            }

                            countdownElement.textContent = secondsRemaining;
                        }, 1000);
                    }

                    generateQrCode();
                    startCountdown();
                    refreshInterval = setInterval(generateQrCode, 15000);

                    window.addEventListener('beforeunload', function () {
                        clearInterval(refreshInterval);
                        clearInterval(countdownInterval);
                    });
                });
            </script>
        @endpush
    @endif
</x-professor-layout>