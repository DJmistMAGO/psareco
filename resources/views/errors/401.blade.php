<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/PSARECO_logo.png') }}" type="image/x-icon">

    <title>401 - Authentication Required | PSARECO Farm Resource Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8faf6] text-slate-800 antialiased selection:bg-emerald-600 selection:text-white font-sans">

<div class="relative min-h-screen bg-gradient-to-b from-emerald-50/50 via-[#f8faf6] to-slate-100/60 flex items-center justify-center px-4 py-12 overflow-hidden">

    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[300px] bg-emerald-200/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-2xl w-full text-center z-10">

        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100/80 border border-emerald-200/60 text-emerald-800 text-xs font-semibold uppercase tracking-wider mb-3">
            PSARECO • Access Control
        </div>

        <div class="flex justify-center">
            <img src="{{ asset('assets/animations/Farmers_Tractor.svg') }}"
                alt="Farmer Tractor Animation"
                class="w-full max-w-md opacity-95">
        </div>

        <div class="mt-2 mb-8">
            <p class="text-6xl sm:text-7xl font-black text-emerald-700 mb-2">401</p>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-3">
                Farm Access Required
            </h1>

            <p class="text-base sm:text-lg text-slate-600 max-w-lg mx-auto leading-relaxed">
                You need to be authenticated before accessing this farm resource.
                Please sign in to continue.
            </p>
        </div>

        <div class="flex justify-center">
            <a href="{{ route('login') }}"
                class="w-full sm:w-auto px-7 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-xl transition-all duration-200 shadow-sm shadow-emerald-700/20">
                Sign In to PSARECO
            </a>
        </div>

        <div class="mt-10 text-slate-400 text-xs font-medium">
            <p>PSARECO Farm Resource Management System &copy; {{ date('Y') }}</p>
        </div>

    </div>
</div>

</body>
</html>
