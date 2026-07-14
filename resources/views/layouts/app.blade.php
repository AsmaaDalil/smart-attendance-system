<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[#f8f9fa]">
    <div class="flex min-h-screen">
        <!-- Sidebar الثابت -->
        <aside class="w-72 bg-[#1a4a40] text-white flex-shrink-0">
            <div class="p-8 border-b border-white/10">
                <h1 class="text-2xl font-bold">Smart Attendance</h1>
                <p class="text-white/70 text-sm mt-1">Management System</p>
            </div>
            
            <nav class="p-4 space-y-2">
                <a href="/admin-dashboard" class="flex items-center p-4 rounded-2xl {{ request()->is('admin-dashboard') ? 'bg-white/10' : 'hover:bg-white/10' }} transition">
                    Dashboard
                </a>
                <a href="#" class="flex items-center p-4 rounded-2xl hover:bg-white/10 transition">Students</a>
                <a href="#" class="flex items-center p-4 rounded-2xl hover:bg-white/10 transition">Attendance</a>
                <a href="#" class="flex items-center p-4 rounded-2xl hover:bg-white/10 transition">QR Codes</a>
                <a href="#" class="flex items-center p-4 rounded-2xl hover:bg-white/10 transition">Reports</a>
                <a href="#" class="flex items-center p-4 rounded-2xl hover:bg-white/10 transition">Settings</a>
            </nav>
        </aside>

        <!-- المحتوى الرئيسي -->
        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>
</body>
</html>