<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/PSARECO_logo.png') }}" type="image/x-icon">

    <title>
        @yield('title', '404 - Field Not Found | PSARECO Farm Resource Management')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8faf6] text-slate-800 antialiased selection:bg-emerald-600 selection:text-white font-sans">
    <div class="relative min-h-screen bg-gradient-to-b from-emerald-50/50 via-[#f8faf6] to-slate-100/60 flex items-center justify-center px-4 py-12 overflow-hidden">

        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[300px] bg-emerald-200/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 left-10 w-64 h-64 bg-amber-200/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-2xl w-full text-center z-10">

            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100/80 border border-emerald-200/60 text-emerald-800 text-xs font-semibold uppercase tracking-wider mb-3 shadow-sm">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                PSARECO • Machinery & Inventory System
            </div>

            <div class="flex justify-center items-center my-0 py-0 leading-none">
                <div class="w-full max-w-md my-0 py-0">
                    <img src="{{ asset('assets/animations/Farmers_Tractor.svg') }}"
                        alt="Farmer Tractor Animation"
                        class="block w-full h-auto my-0 py-0 opacity-95" />
                </div>
            </div>

            <div class="mt-2 mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-3">
                    Resource Outside Field Limits
                </h1>
                <p class="text-base sm:text-lg text-slate-600 max-w-lg mx-auto leading-relaxed">
                    The requested machinery dispatch, inventory item, or schedule log could not be located in our active farm registry.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <!-- Return to Dashboard -->
                <a href="{{ route('dashboard') }}"
                class="w-full sm:w-auto px-7 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-xl transition-all duration-200 shadow-sm shadow-emerald-700/20 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-7 4v6m0 0l-4-2m4 2l4-2"></path>
                    </svg>
                    <span>Return to Dashboard</span>
                </a>

                <a href="javascript:history.back()"
                class="w-full sm:w-auto px-7 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-xl border border-slate-200/80 transition-all duration-200 shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Previous Page</span>
                </a>
            </div>

            <div class="mt-10 text-slate-400 text-xs font-medium">
                <p>PSARECO Farm Resource Management System &copy; {{ date('Y') }}</p>
            </div>

        </div>
    </div>
</body>

</html>
