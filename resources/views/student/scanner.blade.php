<x-student-layout>

    <div class="w-full min-w-0 max-w-none p-4 sm:p-6 lg:p-8">

        <div class="mx-auto max-w-7xl">

            {{-- Page header --}}
            <header
                class="mb-6 flex items-start justify-between gap-4
                       sm:items-center"
            >
                <div class="min-w-0">
                    <h1
                        class="text-2xl font-bold leading-tight
                               text-[#1a4a40] sm:text-3xl
                               dark:text-white"
                    >
                        Attendance Scanner
                    </h1>

                    <p
                        class="mt-1 text-sm text-gray-500
                               dark:text-gray-400"
                    >
                        Scan the QR code displayed by your professor.
                    </p>
                </div>

                {{-- Same account and theme area used on the admin dashboard. --}}
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
                            Student
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
                       sm:px-7 sm:py-7">

                <div class="relative z-10">

                    <p class="text-[11px] font-semibold uppercase
                              tracking-[0.2em] text-white/55
                              sm:text-xs">
                        Smart Attendance
                    </p>

                    <h2 class="mt-2 text-xl font-bold sm:text-2xl">
                        Ready to record your attendance
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm
                              leading-6 text-white/75">
                        Start the camera, scan the active lecture
                        QR code and allow access to your location.
                    </p>

                </div>

                <div class="absolute -right-16 -top-20
                            h-52 w-52 rounded-full
                            bg-[#d4a373]/15
                            sm:h-56 sm:w-56">
                </div>


            </section>

            <div class="grid gap-6 lg:grid-cols-3">

                {{-- Scanner section --}}
                <section
                    class="overflow-hidden rounded-3xl
                           border border-gray-100
                           bg-white shadow-sm
                           dark:border-white/10
                           dark:bg-[#191c1b]
                           lg:col-span-2">

                    {{-- Scanner card header --}}
                    <div class="border-b border-gray-100
                                p-6 dark:border-white/10">

                        <div class="flex flex-wrap
                                    items-center
                                    justify-between gap-4">

                            <div>

                                <h3 class="text-xl font-bold
                                           text-[#184d42]
                                           dark:text-white">
                                    Scan lecture QR code
                                </h3>

                                <p class="mt-1 text-sm
                                          text-gray-500
                                          dark:text-gray-400">
                                    Point the camera clearly toward
                                    the active QR code.
                                </p>

                            </div>

                            <span
                                id="scanner-status"
                                class="rounded-full
                                       bg-gray-100
                                       px-4 py-2
                                       text-xs font-semibold
                                       text-gray-600
                                       dark:bg-white/10
                                       dark:text-gray-300">
                                Camera stopped
                            </span>

                        </div>

                    </div>

                    {{-- Scanner area --}}
                    <div class="p-6">

                        {{-- Camera renders here --}}
                        <div
                            id="reader"
                            class="mx-auto hidden
                                   w-full max-w-xl
                                   overflow-hidden
                                   rounded-2xl
                                   border border-gray-200
                                   bg-black
                                   dark:border-white/10"></div>

                        {{-- Camera placeholder --}}
                        <div
                            id="camera-placeholder"
                            class="flex min-h-80
                                   flex-col items-center
                                   justify-center
                                   rounded-2xl
                                   bg-[#184d42]
                                   p-8 text-center
                                   text-white">

                            <div class="mb-5 flex h-20 w-20
                                        items-center justify-center
                                        rounded-full
                                        bg-white/10">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                    class="h-10 w-10">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.5 7.5 8 5h8l1.5 2.5H20
                                          a2 2 0 0 1 2 2v8
                                          a2 2 0 0 1-2 2H4
                                          a2 2 0 0 1-2-2v-8
                                           a2 2 0 0 1 2-2h2.5Z" />

                                    <circle
                                        cx="12"
                                        cy="13"
                                        r="3.5" />
                                </svg>

                            </div>

                            <h4 class="text-2xl font-bold">
                                Camera is ready
                            </h4>

                            <p class="mt-2 max-w-md text-sm
                                      leading-6 text-white/70">
                                Press Start Camera and place the
                                lecture QR code inside the scanning
                                area.
                            </p>

                        </div>

                        {{-- Scanner buttons --}}
                        <div class="mt-6 flex flex-wrap
                                    justify-center gap-3">

                            <button
                                type="button"
                                id="start-scanner"
                                class="rounded-xl
                                       bg-[#184d42]
                                       px-7 py-3
                                       text-sm font-semibold
                                       text-white transition
                                       hover:bg-[#24584d]
                                       disabled:cursor-not-allowed
                                       disabled:opacity-50"
                            >
                                Start Camera
                            </button>

                            <button
                                type="button"
                                id="stop-scanner"
                                disabled
                                class="rounded-xl
                                       border border-gray-200
                                       bg-white px-7 py-3
                                       text-sm font-semibold
                                       text-gray-600 transition
                                       hover:bg-gray-50
                                       disabled:cursor-not-allowed
                                       disabled:opacity-40
                                       dark:border-white/10
                                       dark:bg-[#191c1b]
                                       dark:text-gray-300
                                       dark:hover:bg-white/5"
                            >
                                Stop Camera
                            </button>

                        </div>

                    </div>

                </section>

                {{-- Instructions --}}
                <aside class="space-y-6">

                    <section
                        class="rounded-3xl
                               border border-gray-100
                               bg-white p-6 shadow-sm
                               dark:border-white/10
                               dark:bg-[#191c1b]">

                        <h3 class="text-lg font-bold
                                   text-[#184d42]
                                   dark:text-white">
                            Before scanning
                        </h3>

                        <div class="mt-5 space-y-5">

                            <div class="flex gap-4">

                                <span
                                    class="flex h-8 w-8
                                           flex-shrink-0
                                           items-center
                                           justify-center
                                           rounded-full
                                           bg-[#184d42]/10
                                           text-sm font-bold
                                           text-[#184d42]
                                           dark:bg-white/10
                                           dark:text-white">
                                    1
                                </span>

                                <div>
                                    <p class="text-sm font-semibold
                                              text-gray-800
                                              dark:text-gray-200">
                                        Camera permission
                                    </p>

                                    <p class="mt-1 text-xs
                                              leading-5
                                              text-gray-500
                                              dark:text-gray-400">
                                        Allow the browser to use
                                        your camera.
                                    </p>
                                </div>

                            </div>

                            <div class="flex gap-4">

                                <span
                                    class="flex h-8 w-8
                                           flex-shrink-0
                                           items-center
                                           justify-center
                                           rounded-full
                                           bg-[#184d42]/10
                                           text-sm font-bold
                                           text-[#184d42]
                                           dark:bg-white/10
                                           dark:text-white">
                                    2
                                </span>

                                <div>
                                    <p class="text-sm font-semibold
                                              text-gray-800
                                              dark:text-gray-200">
                                        Location permission
                                    </p>

                                    <p class="mt-1 text-xs
                                              leading-5
                                              text-gray-500
                                              dark:text-gray-400">
                                        Allow access to your
                                        current location.
                                    </p>
                                </div>

                            </div>

                            <div class="flex gap-4">

                                <span
                                    class="flex h-8 w-8
                                           flex-shrink-0
                                           items-center
                                           justify-center
                                           rounded-full
                                           bg-[#184d42]/10
                                           text-sm font-bold
                                           text-[#184d42]
                                           dark:bg-white/10
                                           dark:text-white">
                                    3
                                </span>

                                <div>
                                    <p class="text-sm font-semibold
                                              text-gray-800
                                              dark:text-gray-200">
                                        Lecture room
                                    </p>

                                    <p class="mt-1 text-xs
                                              leading-5
                                              text-gray-500
                                              dark:text-gray-400">
                                        Make sure you are inside
                                        the assigned lecture room.
                                    </p>
                                </div>

                            </div>

                        </div>

                    </section>

                    <section
                        class="rounded-3xl
                               border border-[#d4a373]/20
                               bg-[#d4a373]/10 p-6
                               dark:border-[#d4a373]/20
                               dark:bg-[#d4a373]/10">

                        <p class="text-sm font-semibold
                                  text-[#8a603d]
                                  dark:text-[#e7bd94]">
                            Important
                        </p>

                        <p class="mt-2 text-xs leading-6
                                  text-gray-600
                                  dark:text-gray-300">
                            Only scan the current QR code displayed
                            by your professor. The code may expire
                            and refresh automatically.
                        </p>

                    </section>

                </aside>

            </div>

            {{-- Scan result --}}
            <div
                id="scan-result"
                class="mt-6 hidden rounded-2xl
                       border p-5">
                <h3
                    id="result-title"
                    class="font-bold"></h3>

                <p
                    id="result-message"
                    class="mt-2 text-sm leading-6"></p>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {
                const startButton =
                    document.getElementById(
                        'start-scanner'
                    );

                const stopButton =
                    document.getElementById(
                        'stop-scanner'
                    );

                const reader =
                    document.getElementById(
                        'reader'
                    );

                const placeholder =
                    document.getElementById(
                        'camera-placeholder'
                    );

                const scannerStatus =
                    document.getElementById(
                        'scanner-status'
                    );

                const result =
                    document.getElementById(
                        'scan-result'
                    );

                const resultTitle =
                    document.getElementById(
                        'result-title'
                    );

                const resultMessage =
                    document.getElementById(
                        'result-message'
                    );

                let scanner = null;
                let isScanning = false;
                let qrWasRead = false;

                /*
                |--------------------------------------------------------------------------
                | Create or retrieve the device identifier
                |--------------------------------------------------------------------------
                */

                function getDeviceToken() {
                    let token = localStorage.getItem(
                        'smart_attendance_device_token'
                    );

                    if (!token) {
                        if (
                            window.crypto &&
                            typeof window.crypto.randomUUID ===
                            'function'
                        ) {
                            token =
                                window.crypto.randomUUID();
                        } else {
                            token =
                                'device-' +
                                Date.now() +
                                '-' +
                                Math.random()
                                .toString(36)
                                .substring(2);
                        }

                        localStorage.setItem(
                            'smart_attendance_device_token',
                            token
                        );
                    }

                    return token;
                }

                /*
                |--------------------------------------------------------------------------
                | Change scanner status
                |--------------------------------------------------------------------------
                */

                function setScannerStatus(
                    type,
                    message
                ) {
                    scannerStatus.textContent =
                        message;

                    scannerStatus.className =
                        'rounded-full px-4 py-2 ' +
                        'text-xs font-semibold';

                    if (type === 'success') {
                        scannerStatus.classList.add(
                            'bg-green-100',
                            'text-green-700'
                        );
                    } else if (type === 'error') {
                        scannerStatus.classList.add(
                            'bg-red-100',
                            'text-red-700'
                        );
                    } else if (type === 'warning') {
                        scannerStatus.classList.add(
                            'bg-amber-100',
                            'text-amber-700'
                        );
                    } else {
                        scannerStatus.classList.add(
                            'bg-gray-100',
                            'text-gray-600',
                            'dark:bg-white/10',
                            'dark:text-gray-300'
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Show styled result
                |--------------------------------------------------------------------------
                */

                function showResult(
                    type,
                    title,
                    message
                ) {
                    result.className =
                        'mt-6 rounded-2xl border p-5';

                    if (type === 'success') {
                        result.classList.add(
                            'border-green-200',
                            'bg-green-50',
                            'text-green-700',
                            'dark:border-green-500/20',
                            'dark:bg-green-500/10',
                            'dark:text-green-300'
                        );
                    } else if (type === 'error') {
                        result.classList.add(
                            'border-red-200',
                            'bg-red-50',
                            'text-red-700',
                            'dark:border-red-500/20',
                            'dark:bg-red-500/10',
                            'dark:text-red-300'
                        );
                    } else {
                        result.classList.add(
                            'border-amber-200',
                            'bg-amber-50',
                            'text-amber-700',
                            'dark:border-amber-500/20',
                            'dark:bg-amber-500/10',
                            'dark:text-amber-300'
                        );
                    }

                    resultTitle.textContent =
                        title;

                    resultMessage.textContent =
                        message;
                }

                /*
                |--------------------------------------------------------------------------
                | Stop camera
                |--------------------------------------------------------------------------
                */

                async function stopScanner() {
                    if (!scanner || !isScanning) {
                        return;
                    }

                    try {
                        await scanner.stop();
                        await scanner.clear();
                    } catch (error) {
                        console.error(error);
                    }

                    isScanning = false;

                    reader.classList.add('hidden');
                    placeholder.classList.remove(
                        'hidden'
                    );

                    startButton.disabled = false;
                    stopButton.disabled = true;

                    setScannerStatus(
                        'default',
                        'Camera stopped'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Read QR content
                |--------------------------------------------------------------------------
                */

                function parseQrContent(
                    decodedText
                ) {
                    const text =
                        decodedText.trim();

                    console.log(
                        'Scanned QR content:',
                        text
                    );

                    /*
                    |--------------------------------------------------------------
                    | JSON format
                    |--------------------------------------------------------------
                    */

                    try {
                        const qrData =
                            JSON.parse(text);

                        const sessionId =
                            qrData.session_id ??
                            qrData
                            .attendance_session_id ??
                            qrData.sessionId ??
                            qrData.id;

                        const qrCode =
                            qrData.qr_code ??
                            qrData.qrCode ??
                            qrData.code ??
                            qrData.token;

                        if (sessionId && qrCode) {
                            return {
                                session_id: sessionId,

                                qr_code: qrCode
                            };
                        }
                    } catch (error) {
                        // Not JSON.
                    }

                    /*
                    |--------------------------------------------------------------
                    | URL format
                    |--------------------------------------------------------------
                    */

                    try {
                        const url =
                            new URL(text);

                        const sessionId =
                            url.searchParams.get(
                                'session_id'
                            ) ??
                            url.searchParams.get(
                                'attendance_session_id'
                            ) ??
                            url.searchParams.get(
                                'session'
                            ) ??
                            url.searchParams.get(
                                'id'
                            );

                        const qrCode =
                            url.searchParams.get(
                                'qr_code'
                            ) ??
                            url.searchParams.get(
                                'code'
                            ) ??
                            url.searchParams.get(
                                'token'
                            );

                        if (sessionId && qrCode) {
                            return {
                                session_id: sessionId,

                                qr_code: qrCode
                            };
                        }
                    } catch (error) {
                        // Not a URL.
                    }

                    /*
                    |--------------------------------------------------------------
                    | Example: 5|abc or 5:abc
                    |--------------------------------------------------------------
                    */

                    const match =
                        text.match(
                            /^(\d+)\s*[|:;,]\s*(.+)$/
                        );

                    if (match) {
                        return {
                            session_id: match[1],
                            qr_code: match[2]
                        };
                    }

                    alert(
                        'محتوى QR الأستاذ هو:\n\n' +
                        text
                    );

                    return null;
                }

                /*
                |--------------------------------------------------------------------------
                | Send attendance data
                |--------------------------------------------------------------------------
                */

                async function submitAttendance(
                    qrData,
                    latitude,
                    longitude
                ) {
                    setScannerStatus(
                        'warning',
                        'Verifying attendance...'
                    );

                    try {
                      
                          const response = await fetch(
    "{{ route('student.attendance.scan') }}",
    {
        method: 'POST',
                                   

                                    headers: {
                                        'Content-Type': 'application/json',

                                        'Accept': 'application/json',

                                        'X-CSRF-TOKEN': document
                                            .querySelector(
                                                'meta[name="csrf-token"]'
                                            )
                                            .getAttribute(
                                                'content'
                                            )
                                    },

                                    body: JSON.stringify({
                                        session_id: qrData
                                            .session_id,

                                        qr_code: qrData
                                            .qr_code,

                                        latitude: latitude,

                                        longitude: longitude,

                                        device_token: getDeviceToken()
                                    })
                                }
                            );

                        const data =
                            await response.json();

                        if (
                            !response.ok ||
                            !data.success
                        ) {
                            showResult(
                                'error',
                                'Attendance was not recorded',
                                data.message ||
                                'The attendance request failed.'
                            );

                            setScannerStatus(
                                'error',
                                'Attendance rejected'
                            );

                            return;
                        }

                        const statusMessage =
                            data.status === 'Late' ?
                            'Your attendance was recorded as late.' :
                            'Your attendance was recorded successfully.';

                        let message =
                            statusMessage;

                        if (
                            data.distance_meters !==
                            undefined &&
                            data.distance_meters !==
                            null
                        ) {
                            message +=
                                ' Distance: ' +
                                data.distance_meters +
                                ' meters.';
                        }

                        showResult(
                            'success',

                            data.status === 'Late' ?
                            'Attendance recorded as Late' :
                            'Attendance recorded as Present',

                            message
                        );

                        setScannerStatus(
                            'success',
                            'Attendance recorded'
                        );
                    } catch (error) {
                        console.error(error);

                        showResult(
                            'error',
                            'Connection error',
                            'Could not connect to the attendance server.'
                        );

                        setScannerStatus(
                            'error',
                            'Connection failed'
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Get student location
                |--------------------------------------------------------------------------
                */

                function getLocation(
                    qrData
                ) {
                    setScannerStatus(
                        'warning',
                        'Getting location...'
                    );

                    if (!navigator.geolocation) {
                        showResult(
                            'error',
                            'Location is unavailable',
                            'Your browser does not support location services.'
                        );

                        setScannerStatus(
                            'error',
                            'Location unavailable'
                        );

                        return;
                    }

                    navigator.geolocation
                        .getCurrentPosition(
                            function(position) {
                                submitAttendance(
                                    qrData,

                                    position
                                    .coords
                                    .latitude,

                                    position
                                    .coords
                                    .longitude
                                );
                            },

                            function(error) {
                                let message =
                                    'Could not determine your location.';

                                if (error.code === 1) {
                                    message =
                                        'Location permission was denied.';
                                } else if (
                                    error.code === 2
                                ) {
                                    message =
                                        'Your location is currently unavailable.';
                                } else if (
                                    error.code === 3
                                ) {
                                    message =
                                        'Location request timed out. Try again.';
                                }

                                showResult(
                                    'error',
                                    'Location error',
                                    message
                                );

                                setScannerStatus(
                                    'error',
                                    'Location unavailable'
                                );
                            },

                            {
                                enableHighAccuracy: true,

                                timeout: 20000,

                                maximumAge: 0
                            }
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Start camera
                |--------------------------------------------------------------------------
                */

                startButton.addEventListener(
                    'click',
                    async function() {
                        if (
                            !window.Html5Qrcode
                        ) {
                            showResult(
                                'error',
                                'Scanner library unavailable',
                                'Restart Vite and refresh the page.'
                            );

                            return;
                        }

                        qrWasRead = false;
                        result.classList.add(
                            'hidden'
                        );

                        scanner =
                            new window.Html5Qrcode(
                                'reader'
                            );

                        reader.classList.remove(
                            'hidden'
                        );

                        placeholder.classList.add(
                            'hidden'
                        );

                        setScannerStatus(
                            'warning',
                            'Starting camera...'
                        );

                        try {
                            await scanner.start({
                                    facingMode: 'environment'
                                },

                                {
                                    fps: 10,

                                    qrbox: {
                                        width: 250,
                                        height: 250
                                    }
                                },

                                async function(
                                        decodedText
                                    ) {
                                        if (qrWasRead) {
                                            return;
                                        }

                                        const qrData =
                                            parseQrContent(
                                                decodedText
                                            );

                                        if (!qrData) {
                                            qrWasRead =
                                                true;

                                            await stopScanner();

                                            showResult(
                                                'error',
                                                'Invalid QR code',
                                                'This QR code does not belong to Smart Attendance.'
                                            );

                                            setScannerStatus(
                                                'error',
                                                'Invalid QR code'
                                            );

                                            return;
                                        }

                                        qrWasRead = true;

                                        await stopScanner();

                                        getLocation(
                                            qrData
                                        );
                                    },

                                    function() {
                                        // Continue scanning.
                                    }
                            );

                            isScanning = true;

                            startButton.disabled =
                                true;

                            stopButton.disabled =
                                false;

                            setScannerStatus(
                                'success',
                                'Scanning'
                            );
                        } catch (error) {
                            console.error(error);

                            reader.classList.add(
                                'hidden'
                            );

                            placeholder
                                .classList
                                .remove('hidden');

                            showResult(
                                'error',
                                'Camera could not start',
                                'Allow camera permission, then try again.'
                            );

                            setScannerStatus(
                                'error',
                                'Camera unavailable'
                            );
                        }
                    }
                );

                stopButton.addEventListener(
                    'click',
                    stopScanner
                );
            }
        );
    </script>

</x-student-layout>