<!DOCTYPE html>
<html lang="en" class="h-full w-full scroll-smooth">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

<title>Smart Attendance System - Student</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    {{-- Apply the saved theme exactly like the admin layout. --}}
    <script>
        (() => {
            const theme = localStorage.getItem('theme');

            document.documentElement.classList.toggle(
                'dark',
                theme === 'dark'
            );
        })();
    </script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-width: 0;
            max-width: none;
            min-height: 100%;
            margin: 0;
        }

        body {
            overflow-x: hidden;
        }

        #studentSidebarBrand {
            min-height: 112px !important;
            padding: 30px 24px 16px !important;
            align-items: flex-start !important;
        }

        .student-hidden-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .student-hidden-scrollbar::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }

        /*
         * Mobile And Tablet Header
         */
        #studentMobileHeader {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            width: auto;
            min-width: 0;
            max-width: none;
            margin: 0;
        }

        /*
         * Sidebar Is Hidden On Mobile And Tablet
         */
        #studentSidebar {
            height: 100vh;
            height: 100dvh;
            max-height: 100dvh;
            transform: translateX(-100%);
            transition: transform 0.28s ease;
            will-change: transform;
        }

        #studentSidebarOverlay {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition:
                opacity 0.28s ease,
                visibility 0.28s ease;
        }

        body.student-sidebar-open #studentSidebar {
            transform: translateX(0);
        }

        body.student-sidebar-open #studentSidebarOverlay {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /*
         * Page Content Uses Full Width On Mobile And Tablet
         */
        #studentPageContent {
            width: 100%;
            min-width: 0;
            max-width: none;
            min-height: 100vh;
            margin: 0;
            padding-top: 4rem;
            overflow-x: hidden;
        }

        #studentPageContent > * {
            width: 100%;
            min-width: 0;
            max-width: none;
        }

        /*
         * The Theme Control Must Stay Content-Sized.
         * This Overrides The General Full-Width Child Rule Above.
         */
        #studentThemeControl {
            width: auto;
            min-width: auto;
            max-width: none;
        }

        /*
         * Laptop And Desktop
         */
        @media (min-width: 1024px) {

            #studentSidebar {
                transform: translateX(0);
            }

            #studentSidebarOverlay,
            #studentMobileHeader,
            #closeStudentSidebar {
                display: none;
            }

            #studentPageContent {
                width: calc(100% - 18rem);
                margin-left: 18rem;
                padding-top: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            #studentSidebar,
            #studentSidebarOverlay {
                transition: none;
            }
        }
    </style>
</head>

<body
    class="min-h-screen w-full min-w-0
           max-w-none overflow-x-hidden antialiased
           bg-[#f8f9fa] text-gray-800
           transition-colors duration-300
           dark:bg-[#0f1110] dark:text-gray-100"
    style="font-family: Poppins, sans-serif;"
>

{{-- Mobile And Tablet Navbar --}}
<header
    id="studentMobileHeader"
    class="fixed inset-x-0 top-0 z-30 flex h-16
           w-full min-w-0 max-w-none items-center
           justify-between border-b border-gray-200
           bg-white px-4 shadow-sm
           transition-colors duration-300 sm:px-6
           dark:border-white/10 dark:bg-[#171a19]"
>
<div class="flex min-w-0 items-center gap-2">

    <img
        src="{{ asset('images/logo.png') }}"
        alt="Smart Attendance System"
        class="h-10 w-auto object-contain"
    >

</div>

    <button
        id="openStudentSidebar"
        type="button"
        aria-label="Open navigation"
        aria-controls="studentSidebar"
        aria-expanded="false"
        class="ml-3 flex h-10 w-10 flex-shrink-0 items-center
               justify-center rounded-xl bg-[#184d42] text-white
               shadow-sm transition hover:bg-[#24584d]
               focus:outline-none focus:ring-2
               focus:ring-[#d4a373]/70"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M4 6h16M4 12h16M4 18h16"
            />
        </svg>
    </button>
</header>


{{-- Mobile And Tablet Overlay --}}
<div
    id="studentSidebarOverlay"
    class="fixed inset-0 z-40
           bg-black/45 backdrop-blur-[1px]"
></div>

{{-- Student Sidebar --}}
<aside
    id="studentSidebar"
    class="fixed inset-y-0 left-0 z-50 flex h-screen
           w-72 max-w-[86vw] flex-col overflow-hidden
           bg-[#184d42] text-white shadow-2xl"
>
    {{-- Brand --}}
    <div class="w-full text-center">

    <img
        src="{{ asset('images/logo.png') }}"
        alt="Smart Attendance System"
        style="width: 125px; height: auto; margin: 5px auto 0;"
    >

    <p class="mt-1 text-xs text-white/60">
        Student Portal
    </p>

</div>

        <button
            id="closeStudentSidebar"
            type="button"
            aria-label="Close Navigation"
            class="ml-3 mt-1 flex h-9 w-9 flex-shrink-0
                   items-center justify-center rounded-xl
                   bg-white/10 text-white transition
                   hover:bg-white/20 focus:outline-none
                   focus:ring-2 focus:ring-white/40"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18 18 6M6 6l12 12"
                />
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav
        class="student-hidden-scrollbar min-h-0 flex-1
               space-y-1.5 overflow-y-auto p-4"
    >
        <a
            href="{{ route('student.dashboard') }}"
            class="student-nav-link block rounded-xl
                   px-4 py-3 text-sm transition
                   {{
                       request()->routeIs('student.dashboard')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Dashboard
        </a>

        <a
            href="{{ route('student.scanner') }}"
            class="student-nav-link block rounded-xl
                   px-4 py-3 text-sm transition
                   {{
                       request()->routeIs('student.scanner')
                       || request()->routeIs(
                           'student.attendance.scan'
                       )
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Scan QR
        </a>

        @if (
            \Illuminate\Support\Facades\Route::has(
                'student.attendance.index'
            )
        )
            <a
                href="{{ route(
                    'student.attendance.index'
                ) }}"
                class="student-nav-link block rounded-xl
                       px-4 py-3 text-sm transition
                       {{
                           request()->routeIs(
                               'student.attendance.*'
                           )
                               ? 'bg-white/15 font-semibold text-white'
                               : 'text-white/75 hover:bg-white/10 hover:text-white'
                       }}"
            >
                Attendance History
            </a>
        @endif

        @if (
            \Illuminate\Support\Facades\Route::has(
                'student.excuses.index'
            )
        )
            <a
                href="{{ route(
                    'student.excuses.index'
                ) }}"
                class="student-nav-link block rounded-xl
                       px-4 py-3 text-sm transition
                       {{
                           request()->routeIs(
                               'student.excuses.*'
                           )
                               ? 'bg-white/15 font-semibold text-white'
                               : 'text-white/75 hover:bg-white/10 hover:text-white'
                       }}"
            >
                Excuses
            </a>
        @endif

        @if (
            \Illuminate\Support\Facades\Route::has(
                'student.profile.edit'
            )
        )
            <a
                href="{{ route(
                    'student.profile.edit'
                ) }}"
                class="student-nav-link block rounded-xl
                       px-4 py-3 text-sm transition
                       {{
                           request()->routeIs(
                               'student.profile.*'
                           )
                               ? 'bg-white/15 font-semibold text-white'
                               : 'text-white/75 hover:bg-white/10 hover:text-white'
                       }}"
            >
                Profile
            </a>
        @endif
    </nav>

    {{-- Logout --}}
    <div class="flex-shrink-0 border-t border-white/10 p-4">
        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button
                type="submit"
                class="w-full rounded-xl px-4 py-3
                       text-sm font-medium text-white/70
                       transition hover:bg-red-500/15
                       hover:text-red-200"
            >
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- Main Student Content --}}
<main
    id="studentPageContent"
    class="min-h-screen w-full min-w-0 max-w-none
           overflow-x-hidden"
>
    {{ $slot }}
</main>

@stack('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const body = document.body;

            const openButton =
                document.getElementById(
                    'openStudentSidebar'
                );

            const closeButton =
                document.getElementById(
                    'closeStudentSidebar'
                );

            const overlay =
                document.getElementById(
                    'studentSidebarOverlay'
                );

            const navigationLinks =
                document.querySelectorAll(
                    '.student-nav-link'
                );

            function setExpanded(isExpanded) {
                openButton?.setAttribute(
                    'aria-expanded',
                    String(isExpanded)
                );
            }

            function openSidebar() {
                if (window.innerWidth >= 1024) {
                    return;
                }

                body.classList.add(
                    'student-sidebar-open',
                    'overflow-hidden'
                );

                setExpanded(true);
                closeButton?.focus();
            }

            function closeSidebar(
                restoreFocus = false
            ) {
                body.classList.remove(
                    'student-sidebar-open',
                    'overflow-hidden'
                );

                setExpanded(false);

                if (
                    restoreFocus
                    && window.innerWidth < 1024
                ) {
                    openButton?.focus();
                }
            }

            openButton?.addEventListener(
                'click',
                openSidebar
            );

            closeButton?.addEventListener(
                'click',
                function () {
                    closeSidebar(true);
                }
            );

            overlay?.addEventListener(
                'click',
                function () {
                    closeSidebar(true);
                }
            );

            navigationLinks.forEach(
                function (link) {
                    link.addEventListener(
                        'click',
                        function () {
                            closeSidebar(false);
                        }
                    );
                }
            );

            document.addEventListener(
                'keydown',
                function (event) {
                    if (event.key === 'Escape') {
                        closeSidebar(true);
                    }
                }
            );

            window.addEventListener(
                'resize',
                function () {
                    if (window.innerWidth >= 1024) {
                        closeSidebar(false);
                    }
                }
            );
        }
    );
</script>

</body>
</html>