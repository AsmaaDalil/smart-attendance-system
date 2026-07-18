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

            <a href="#"
               class="block p-4 rounded-2xl hover:bg-white/10 transition">
                My Subjects
            </a>

            <a href="#"
               class="block p-4 rounded-2xl hover:bg-white/10 transition">
                Start Session
            </a>

            <a href="#"
               class="block p-4 rounded-2xl hover:bg-white/10 transition">
                Active Session
            </a>

            <a href="#"
               class="block p-4 rounded-2xl hover:bg-white/10 transition">
                Attendance
            </a>

            <a href="#"
               class="block p-4 rounded-2xl hover:bg-white/10 transition">
                Excuses
            </a>

            <a href="#"
               class="block p-4 rounded-2xl hover:bg-white/10 transition">
                Reports
            </a>

        </nav>

    </aside>

    <main class="flex-1 min-w-0">
        {{ $slot }}
    </main>

</div>

</body>
</html>