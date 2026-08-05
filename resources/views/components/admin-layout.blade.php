<!DOCTYPE html>
<html lang="en" class="h-full w-full scroll-smooth">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smart Attendance - Admin</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

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


        #adminSidebarBrand {
            min-height: 112px !important;
            padding: 30px 24px 16px !important;
            align-items: flex-start !important;
        }

        /*
         * Hide the sidebar scrollbar while keeping scrolling enabled.
         */
        .admin-hidden-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .admin-hidden-scrollbar::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }

        /*
         * Mobile and tablet header:
         * full viewport width.
         */
        #adminMobileHeader {
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
         * Mobile and tablet sidebar:
         * folded by default.
         */
        #adminSidebar {
            transform: translateX(-100%);
            transition: transform 0.28s ease;
            will-change: transform;
        }

        #adminSidebarOverlay {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition:
                opacity 0.28s ease,
                visibility 0.28s ease;
        }

        body.admin-sidebar-open #adminSidebar {
            transform: translateX(0);
        }

        body.admin-sidebar-open #adminSidebarOverlay {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /*
         * Full-width page on mobile and tablet.
         */
        #adminPageContent {
            width: 100%;
            min-width: 0;
            max-width: none;
            min-height: 100vh;
            margin: 0;
            padding-top: 4rem;
            overflow-x: hidden;
        }

        #adminPageContent > * {
            width: 100%;
            min-width: 0;
            max-width: none;
        }

        /*
         * Laptop and desktop:
         * sidebar fixed and page shifted by the same width.
         */
        @media (min-width: 1024px) {
            #adminSidebar {
                transform: translateX(0);
            }

            #adminSidebarOverlay,
            #adminMobileHeader,
            #closeAdminSidebar {
                display: none;
            }

            #adminPageContent {
                width: calc(100% - 18rem);
                margin-left: 18rem;
                padding-top: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            #adminSidebar,
            #adminSidebarOverlay {
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
    id="adminMobileHeader"
    class="fixed inset-x-0 top-0 z-30 flex h-16 w-full min-w-0
           max-w-none items-center justify-between
           border-b border-gray-200 bg-white px-4 shadow-sm
           transition-colors duration-300 sm:px-6
           dark:border-white/10 dark:bg-[#171a19]"
>
    <div class="min-w-0">
        <h1
            class="truncate text-sm font-bold text-[#184d42]
                   sm:text-base dark:text-white"
        >
            Smart Attendance
        </h1>

        <p
            class="text-[10px] text-gray-500
                   sm:text-xs dark:text-gray-400"
        >
            Administration
        </p>
    </div>

    <button
        id="openAdminSidebar"
        type="button"
        aria-label="Open navigation"
        aria-controls="adminSidebar"
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

{{-- Mobile and tablet overlay --}}
<div
    id="adminSidebarOverlay"
    class="fixed inset-0 z-40 bg-black/45 backdrop-blur-[1px]"
></div>

{{-- Sidebar --}}
<aside
    id="adminSidebar"
    class="fixed inset-y-0 left-0 z-50 flex h-screen
           w-72 max-w-[86vw] flex-col overflow-hidden
           bg-[#184d42] text-white shadow-2xl"
>
    {{-- Brand --}}
    <div
        id="adminSidebarBrand"
        class="flex flex-shrink-0 items-start
               justify-between border-b border-white/10"
    >
        <div class="min-w-0">
            <h1 class="truncate text-xl font-bold leading-tight">
                Smart Attendance
            </h1>

            <p class="mt-2 text-xs text-white/60">
                Administration
            </p>
        </div>

        <button
            id="closeAdminSidebar"
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
    <nav
        class="admin-hidden-scrollbar min-h-0 flex-1
               space-y-1.5 overflow-y-auto p-4"
    >
        <a
            href="{{ route('admin.dashboard') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->routeIs('admin.dashboard')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Dashboard
        </a>

        <a
            href="{{ route('filament.admin.pages.dashboard') }}"
            class="admin-nav-link flex items-center justify-between
                   rounded-xl px-4 py-3 text-sm transition
                   text-white/75 hover:bg-white/10 hover:text-white"
        >
            <span>Manage System</span>

            <span
                class="rounded-md bg-[#d4a373]
                       px-2 py-0.5 text-[9px]
                       font-bold text-[#184d42]"
            >
                ADMIN
            </span>
        </a>

        <div class="my-3 h-px bg-white/10"></div>

        <a
            href="{{ url('/admin/students') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->is('admin/students*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Students
        </a>

        <a
            href="{{ url('/admin/professors') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->is('admin/professors*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Professors
        </a>

        <a
            href="{{ url('/admin/subjects') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->is('admin/subjects*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Subjects
        </a>

        <a
            href="{{ url('/admin/rooms') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->is('admin/rooms*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Rooms
        </a>

        <a
            href="{{ url('/admin/enrollments') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->is('admin/enrollments*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Enrollments
        </a>

        <div class="my-3 h-px bg-white/10"></div>

        <a
            href="{{ url('/admin/attendance-records') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->is('admin/attendance-records*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Attendance
        </a>

        <a
            href="{{ url('/admin/excuses') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->is('admin/excuses*')
                           ? 'bg-white/15 font-semibold text-white'
                           : 'text-white/75 hover:bg-white/10 hover:text-white'
                   }}"
        >
            Excuses
        </a>

        <a
            href="{{ route('admin.reports.index') }}"
            class="admin-nav-link block rounded-xl px-4 py-3
                   text-sm transition
                   {{
                       request()->routeIs('admin.reports.*')
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

{{-- Main content --}}
<main
    id="adminPageContent"
    class="min-h-screen w-full min-w-0 max-w-none
           overflow-x-hidden"
>
    {{ $slot }}
</main>

@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const openButton = document.getElementById('openAdminSidebar');
        const closeButton = document.getElementById('closeAdminSidebar');
        const overlay = document.getElementById('adminSidebarOverlay');
        const navigationLinks = document.querySelectorAll('.admin-nav-link');

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
                'admin-sidebar-open',
                'overflow-hidden'
            );

            setExpanded(true);
            closeButton?.focus();
        }

        function closeSidebar(restoreFocus = false) {
            body.classList.remove(
                'admin-sidebar-open',
                'overflow-hidden'
            );

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