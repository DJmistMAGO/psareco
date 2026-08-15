<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/PSARECO_logo.png') }}" type="image/x-icon">

    <title>500 - Farm System Error | PSARECO Farm Resource Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8faf6] text-slate-800 antialiased selection:bg-emerald-600 selection:text-white font-sans">

<div class="relative min-h-screen bg-gradient-to-b from-emerald-50/50 via-[#f8faf6] to-slate-100/60 flex items-center justify-center px-4 py-12">

    <div class="relative max-w-2xl w-full text-center">

        <div class="inline-flex px-3.5 py-1.5 rounded-full bg-red-100 border border-red-200 text-red-800 text-xs font-semibold uppercase tracking-wider mb-3">
            PSARECO • System Status
        </div>

        <div class="flex justify-center">
            <img src="{{ asset('assets/animations/Farmers_Tractor.svg') }}"
                alt="Farmer Tractor Animation"
                class="w-full max-w-md opacity-95">
        </div>

        <div class="mt-2 mb-8">

            <p class="text-6xl sm:text-7xl font-black text-red-600 mb-2">
                500
            </p>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">
                Something Went Wrong
            </h1>

            <p class="text-base sm:text-lg text-slate-600 max-w-lg mx-auto leading-relaxed">
                The PSARECO farm management system encountered an unexpected
                problem while processing your request.
            </p>

        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">

            <a href="{{ route('home') }}"
                class="px-7 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-xl">
                Return to Dashboard
            </a>

            <a href="javascript:location.reload()"
                class="px-7 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-xl border border-slate-200">
                Try Again
            </a>

        </div>

        <div class="mt-10 text-slate-400 text-xs font-medium">
            <p>PSARECO Farm Resource Management System &copy; {{ date('Y') }}</p>
        </div>

    </div>
</div>

</body>
</html>
