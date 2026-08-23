<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/PSARECO_logo.png') }}" type="image/x-icon">

    <title>403 - Access Denied | PSARECO Farm Resource Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8faf6] text-slate-800 antialiased selection:bg-emerald-600 selection:text-white font-sans">

<div class="relative min-h-screen bg-gradient-to-b from-emerald-50/50 via-[#f8faf6] to-slate-100/60 flex items-center justify-center px-4 py-12 overflow-hidden">

    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[300px] bg-red-200/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-2xl w-full text-center z-10">

        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-100/80 border border-red-200/60 text-red-800 text-xs font-semibold uppercase tracking-wider mb-3">
            PSARECO • Permission Control
        </div>

        <div class="flex justify-center">
            <img src="{{ asset('assets/animations/Farmers_Tractor.svg') }}"
                alt="Farmer Tractor Animation"
                class="w-full max-w-md opacity-95">
        </div>

        <div class="mt-2 mb-8">
            <p class="text-6xl sm:text-7xl font-black text-red-600 mb-2">403</p>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-3">
                Farm Access Restricted
            </h1>

            <p class="text-base sm:text-lg text-slate-600 max-w-lg mx-auto leading-relaxed">
                You are authenticated, but your current role does not have permission
                to access this machinery, inventory, or administrative resource.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">

            <a href="{{ route('dashboard.index') }}"
                class="px-7 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-xl transition-all">
                Return to Dashboard
            </a>

            <a href="javascript:history.back()"
                class="px-7 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-xl border border-slate-200">
                Previous Page
            </a>

        </div>

        <div class="mt-10 text-slate-400 text-xs font-medium">
            <p>PSARECO Farm Resource Management System &copy; {{ date('Y') }}</p>
        </div>

    </div>
</div>

</body>
</html>
