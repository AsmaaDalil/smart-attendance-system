<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full"
>
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>{{ config('app.name', 'Smart Attendance') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
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
    <!-- Scripts -->
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-full font-sans text-gray-900 antialiased">

    <!-- Floating theme toggle -->
    <div class="fixed right-5 top-5 z-50">
        <x-theme-toggle />
    </div>

    <!-- Page content -->
    {{ $slot }}

</body>
</html>