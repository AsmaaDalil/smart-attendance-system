<!DOCTYPE html>
<html lang="en" class="h-full w-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

<title>Smart Attendance System - Professor</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    {{-- Keep the saved light/dark mode before the page appears. --}}
    <script>
        (function () {
            const savedTheme = localStorage.getItem('smart-attendance-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            document.documentElement.classList.toggle(
                'dark',
                savedTheme === 'dark' || (! savedTheme && prefersDark)
            );
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

#professorSidebarBrand {
    min-height: 112px !important;
    padding: 30px 24px 16px !important;
    align-items: flex-start !important;
}
        /*
         * Mobile/tablet header:
         * always spans the complete viewport width.
         */
        #professorMobileHeader {
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
         * Mobile/tablet sidebar:
         * hidden outside the screen until the menu button is pressed.
         */
        #professorSidebar {
            transform: translateX(-100%);
            transition: transform 0.28s ease;
            will-change: transform;
        }

        #professorSidebarOverlay {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition:
                opacity 0.28s ease,
                visibility 0.28s ease;
        }

        body.professor-sidebar-open #professorSidebar {
            transform: translateX(0);
        }

        body.professor-sidebar-open #professorSidebarOverlay {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /*
         * Page content:
         * full width on mobile and tablet.
         */
        #professorPageContent {
            width: 100%;
            min-width: 0;
            max-width: none;
            min-height: 100vh;
            margin: 0;
            padding-top: 4rem;
            overflow-x: hidden;
        }

        #professorPageContent > * {
            width: 100%;
            min-width: 0;
            max-width: none;
        }

        /*
         * Laptop and desktop.
         */
        @media (min-width: 1024px) {
            #professorSidebar {
                transform: translateX(0);
            }

            #professorSidebarOverlay,
            #professorMobileHeader,
            #closeProfessorSidebar {
                display: none;
            }

            #professorPageContent {
                width: calc(100% - 18rem);
                margin-left: 18rem;
                padding-top: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            #professorSidebar,
            #professorSidebarOverlay {
                transition: none;
            }
        }
    </style>
</head>

<body
    class="min-h-screen w-full min-w-0 max-w-none overflow-x-hidden
           bg-[#f8f9fa] text-gray-800 transition-colors duration-300
           dark:bg-[#0f1110] dark:text-gray-100"
    style="font-family: Poppins, sans-serif;"
>

{{-- Mobile and tablet header --}}
<header
    id="professorMobileHeader"
    class="fixed inset-x-0 top-0 z-30 flex h-16 w-full min-w-0
           max-w-none items-center justify-between
           border-b border-gray-200 bg-white px-4 shadow-sm
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
        id="openProfessorSidebar"
        type="button"
        aria-label="Open navigation"
        aria-controls="professorSidebar"
        aria-expanded="false"
        class="ml-3 flex h-10 w-10 flex-shrink-0 items-center
               justify-center rounded-xl bg-[#184d42] text-white
               shadow-sm transition hover:bg-[#24584d]
               focus:outline-none focus:ring-2 focus:ring-[#d4a373]/70"
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

{{-- Mobile and tablet overlay --}}
<div
    id="professorSidebarOverlay"
    class="fixed inset-0 z-40 bg-black/45 backdrop-blur-[1px]"
></div>

{{-- Sidebar --}}
<aside
    id="professorSidebar"
    class="fixed inset-y-0 left-0 z-50 flex h-screen
           w-72 max-w-[86vw] flex-col overflow-hidden
           bg-[#1a4a40] text-white shadow-2xl"
>
{{-- Brand --}}
<div
    id="professorSidebarBrand"
    class="flex flex-shrink-0 justify-between
           border-b border-white/10"
>
<div class="w-full text-center">

    <img
        src="{{ asset('images/logo.png') }}"
        alt="Smart Attendance System"
        style="width: 125px; height: auto; margin: 5px auto 0;"
    >

    <p class="mt-1 text-xs text-white/60">
        Professor Portal
    </p>

</div>

</div>

    <button
        id="closeProfessorSidebar"
        type="button"
        aria-label="Close navigation"
        class="ml-3 mt-1 flex h-9 w-9 flex-shrink-0
               items-center justify-center rounded-xl
               bg-white/10 text-white transition
               hover:bg-white/20
               focus:outline-none focus:ring-2
               focus:ring-white/40"
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
    <nav class="min-h-0 flex-1 space-y-1.5 overflow-y-auto p-4">
        <a
            href="{{ route('professor.dashboard') }}"
            class="professor-nav-link block rounded-xl px-4 py-3 text-sm
                   transition
                   {{
                       request()->routeIs('professor.dashboard')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Dashboard
        </a>

        <a
            href="{{ route('professor.subjects.index') }}"
            class="professor-nav-link block rounded-xl px-4 py-3 text-sm
                   transition
                   {{
                       request()->routeIs('professor.subjects.*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            My Subjects
        </a>

        <a
            href="{{ route('professor.sessions.create') }}"
            class="professor-nav-link block rounded-xl px-4 py-3 text-sm
                   transition
                   {{
                       request()->routeIs('professor.sessions.create')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Start Session
        </a>

        <a
            href="{{ route('professor.sessions.index') }}"
            class="professor-nav-link block rounded-xl px-4 py-3 text-sm
                   transition
                   {{
                       request()->routeIs('professor.sessions.index')
                       || request()->routeIs('professor.sessions.show')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Active Session
        </a>

        <a
            href="{{ route('professor.attendance.index') }}"
            class="professor-nav-link block rounded-xl px-4 py-3 text-sm
                   transition
                   {{
                       request()->routeIs('professor.attendance.*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Attendance
        </a>

        <a
            href="{{ route('professor.excuses.index') }}"
            class="professor-nav-link block rounded-xl px-4 py-3 text-sm
                   transition
                   {{
                       request()->routeIs('professor.excuses.*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Excuses
        </a>

        <a
            href="{{ route('professor.reports.index') }}"
            class="professor-nav-link block rounded-xl px-4 py-3 text-sm
                   transition
                   {{
                       request()->routeIs('professor.reports.*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Reports
        </a>
    </nav>

    {{-- Logout --}}
    <div class="flex-shrink-0 border-t border-white/10 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full rounded-xl px-4 py-3 text-sm
                       font-medium text-white/70 transition
                       hover:bg-red-500/15 hover:text-red-200"
            >
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- Main content --}}
<main id="professorPageContent" class="min-h-screen w-full min-w-0 max-w-none overflow-x-hidden">
    {{ $slot }}
</main>

@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const openButton = document.getElementById('openProfessorSidebar');
        const closeButton = document.getElementById('closeProfessorSidebar');
        const overlay = document.getElementById('professorSidebarOverlay');
        const navigationLinks = document.querySelectorAll('.professor-nav-link');

        function setExpanded(isExpanded) {
            openButton?.setAttribute('aria-expanded', String(isExpanded));
        }

        function openSidebar() {
            if (window.innerWidth >= 1024) {
                return;
            }

            body.classList.add('professor-sidebar-open', 'overflow-hidden');
            setExpanded(true);
            closeButton?.focus();
        }

        function closeSidebar(restoreFocus = false) {
            body.classList.remove('professor-sidebar-open', 'overflow-hidden');
            setExpanded(false);

            if (restoreFocus && window.innerWidth < 1024) {
                openButton?.focus();
            }
        }

        openButton?.addEventListener('click', openSidebar);
        closeButton?.addEventListener('click', function () {
            closeSidebar(true);
        });
        overlay?.addEventListener('click', function () {
            closeSidebar(true);
        });

        navigationLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                closeSidebar(false);
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeSidebar(true);
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                closeSidebar(false);
            }
        });
    });
</script>

</body>
</html>