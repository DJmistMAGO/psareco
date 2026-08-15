<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/images/PSARECO_logo.png') }}" type="image/x-icon">

    <title>
        @yield('title', 'PSARECO - Farm Resource Management System')
    </title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#d2e8d9] min-h-screen text-slate-700 font-sans antialiased overflow-x-hidden">

    <div x-data="{ sidebarOpen: true, mobileOpen: false }" class="flex min-h-screen relative">

        <x-sidebar />

        <main class="flex-1 min-w-0 w-full">
            @yield('content')
        </main>

    </div>

    @stack('scripts')

</body>
</html>
