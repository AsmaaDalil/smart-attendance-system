<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

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
</head>

<body
    class="bg-[#f8f9fa] text-gray-800
           dark:bg-[#0f1110] dark:text-gray-100"
    style="font-family: Poppins, sans-serif;"
>

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside
        class="sticky top-0 flex h-screen w-60
               flex-shrink-0 flex-col overflow-hidden
               border-r border-white/5
               bg-[#184d42] pt-4 text-white"
    >

        {{-- Logo --}}
        <div
            class="flex h-20 flex-shrink-0
                   items-center border-b border-white/10
                   px-5"
        >
            <div
                class="mr-3 flex h-10 w-10
                       flex-shrink-0 items-center
                       justify-center rounded-xl
                       bg-white/10 text-sm font-bold"
            >
                SA
            </div>

            <div class="min-w-0">
                <h1 class="text-base font-semibold leading-6">
                    Smart Attendance
                </h1>

                <p class="mt-0.5 text-[11px] text-white/45">
                    Administration
                </p>
            </div>
        </div>

        {{-- Scrollable navigation --}}
        <nav
            class="min-h-0 flex-1 space-y-1
                   overflow-y-auto px-3 py-4"
        >
            <a
                href="{{ route('admin.dashboard') }}"
                class="block rounded-xl px-4 py-3
                       text-sm font-medium transition
                       {{
                           request()->routeIs('admin.dashboard')
                               ? 'bg-white/15 text-white'
                               : 'text-white/65 hover:bg-white/10 hover:text-white'
                       }}"
            >
                Dashboard
            </a>

            <a
                href="{{ route('filament.admin.pages.dashboard') }}"
                class="flex items-center justify-between
                       rounded-xl px-4 py-3
                       text-sm font-medium text-white/65
                       transition
                       hover:bg-white/10 hover:text-white"
            >
                <span>Manage System</span>

                <span
                    class="rounded-md bg-[#d4a373]
                           px-2 py-0.5
                           text-[9px] font-bold text-[#184d42]"
                >
                    ADMIN
                </span>
            </a>

            <div class="my-3 h-px bg-white/10"></div>

            <a
                href="{{ url('/admin/students') }}"
                class="block rounded-xl px-4 py-2.5
                       text-sm text-white/65 transition
                       hover:bg-white/10 hover:text-white"
            >
                Students
            </a>

            <a
                href="{{ url('/admin/professors') }}"
                class="block rounded-xl px-4 py-2.5
                       text-sm text-white/65 transition
                       hover:bg-white/10 hover:text-white"
            >
                Professors
            </a>

            <a
                href="{{ url('/admin/subjects') }}"
                class="block rounded-xl px-4 py-2.5
                       text-sm text-white/65 transition
                       hover:bg-white/10 hover:text-white"
            >
                Subjects
            </a>

            <a
                href="{{ url('/admin/rooms') }}"
                class="block rounded-xl px-4 py-2.5
                       text-sm text-white/65 transition
                       hover:bg-white/10 hover:text-white"
            >
                Rooms
            </a>

            <a
                href="{{ url('/admin/subjects') }}"
                class="block rounded-xl px-4 py-2.5
                       text-sm text-white/65 transition
                       hover:bg-white/10 hover:text-white"
            >
                Enrollments
            </a>

            <div class="my-3 h-px bg-white/10"></div>

            <a
                href="#"
                class="block rounded-xl px-4 py-2.5
                       text-sm text-white/65 transition
                       hover:bg-white/10 hover:text-white"
            >
                Attendance
            </a>

            <a
                href="#"
                class="block rounded-xl px-4 py-2.5
                       text-sm text-white/65 transition
                       hover:bg-white/10 hover:text-white"
            >
                Excuses
            </a>

            <a
                href="#"
                class="block rounded-xl px-4 py-2.5
                       text-sm text-white/65 transition
                       hover:bg-white/10 hover:text-white"
            >
                Reports
            </a>
        </nav>

        {{-- Fixed logout --}}
        <div
            class="flex-shrink-0 border-t border-white/10
                   px-3 pb-5 pt-3"
        >
            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-xl px-4 py-3
                           text-sm text-white/60 transition
                           hover:bg-red-500/15
                           hover:text-red-200"
                >
                    Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- Main page --}}
    <main class="min-w-0 flex-1">
        {{ $slot }}
    </main>

</div>

</body>
</html>