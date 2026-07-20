<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Smart Attendance - Professor</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8f9fa]" style="font-family: Poppins, sans-serif;">
<x-theme-toggle />
<div class="flex min-h-screen">

    <aside class="w-72 bg-[#1a4a40] text-white flex-shrink-0">

        <div class="p-8 border-b border-white/10">
            <h1 class="text-2xl font-bold">
                Smart Attendance
            </h1>

            <p class="text-white/60 text-sm mt-1">
                Professor Portal
            </p>
        </div>

        <nav class="p-4 space-y-2">

            <a href="{{ route('professor.dashboard') }}"
               class="block p-4 rounded-2xl transition
               {{ request()->routeIs('professor.dashboard')
                    ? 'bg-white/15'
                    : 'hover:bg-white/10' }}">
                Dashboard
            </a>

           <a
    href="{{ route('professor.subjects.index') }}"
    class="block rounded-xl px-4 py-3
           text-sm transition
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
    class="block rounded-xl px-4 py-3
           text-sm text-white/75 transition
           hover:bg-white/10 hover:text-white
           {{ request()->routeIs('professor.sessions.create')
                ? 'bg-white/15 text-white'
                : '' }}"
>
    Start Session
</a>

          <a
    href="{{ route('professor.sessions.index') }}"
    class="block rounded-xl px-4 py-3
           text-sm transition
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
    class="block rounded-xl px-4 py-3
           text-sm transition
           {{
               request()->routeIs(
                   'professor.attendance.*'
               )
                   ? 'bg-white/15 font-semibold text-white'
                   : 'text-white/75 hover:bg-white/10 hover:text-white'
           }}"
>
    Attendance
</a>

          <a
    href="{{ route('professor.excuses.index') }}"
    class="block rounded-xl px-4 py-3 text-sm transition
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
    class="block rounded-xl px-4 py-3 text-sm transition
           {{
               request()->routeIs('professor.reports.*')
                   ? 'bg-white/15 font-semibold text-white'
                   : 'text-white/75 hover:bg-white/10 hover:text-white'
           }}"
>
    Reports
</a>

        </nav>
<div class="mt-auto border-t border-white/10 p-4">
    <form
        method="POST"
        action="{{ route('logout') }}"
    >
        @csrf

        <button
            type="submit"
            class="w-full rounded-xl px-4 py-3
                   text-sm font-medium text-white/70
                   transition
                   hover:bg-red-500/15
                   hover:text-red-200"
        >
            Logout
        </button>
    </form>
</div>
    </aside>

    <main class="flex-1 min-w-0">
        {{ $slot }}
    </main>

</div>
@stack('scripts')
</body>
</html>