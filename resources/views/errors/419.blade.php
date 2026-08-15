<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/PSARECO_logo.png') }}" type="image/x-icon">

    <title>419 - Page Expired | PSARECO Farm Resource Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8faf6] text-slate-800 antialiased selection:bg-emerald-600 selection:text-white font-sans">

    <div class="relative min-h-screen bg-gradient-to-b from-emerald-50/50 via-[#f8faf6] to-slate-100/60 flex items-center justify-center px-4 py-12 overflow-hidden">

        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[300px] bg-amber-200/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 left-10 w-64 h-64 bg-emerald-200/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-2xl w-full text-center z-10">

            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-100/80 border border-amber-200/60 text-amber-800 text-xs font-semibold uppercase tracking-wider mb-3 shadow-sm">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                </svg>
                PSARECO • Session Security
            </div>

            <div class="flex justify-center items-center my-0 py-0 leading-none">
                <div class="w-full max-w-md my-0 py-0">
                    <img src="{{ asset('assets/animations/Farmers_Tractor.svg') }}"
                        alt="Farmer Tractor Animation"
                        class="block w-full h-auto my-0 py-0 opacity-95" />
                </div>
            </div>

            <div class="mt-2 mb-8">
                <p class="text-6xl sm:text-7xl font-black text-amber-600 tracking-tight mb-2">
                    419
                </p>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-3">
                    Field Session Expired
                </h1>

                <p class="text-base sm:text-lg text-slate-600 max-w-lg mx-auto leading-relaxed">
                    Your farm management session has expired for security reasons.
                    Please return to the previous page and submit the request again.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">

                <a href="{{ route('home') }}"
                    class="w-full sm:w-auto px-7 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-xl transition-all duration-200 shadow-sm shadow-emerald-700/20 flex items-center justify-center gap-2">

                    <svg class="w-5 h-5 text-emerald-100" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-7 4v6m0 0l-4-2m4 2l4-2" />
                    </svg>

                    <span>Return to Dashboard</span>
                </a>

                <a href="javascript:location.reload()"
                    class="w-full sm:w-auto px-7 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-xl border border-slate-200/80 transition-all duration-200 shadow-sm flex items-center justify-center gap-2">

                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h5M20 20v-5h-5M5.64 18.36A9 9 0 1018.36 5.64" />
                    </svg>

                    <span>Refresh Page</span>
                </a>

            </div>

            <div class="mt-10 text-slate-400 text-xs font-medium">
                <p>PSARECO Farm Resource Management System &copy; {{ date('Y') }}</p>
            </div>

        </div>
    </div>

</body>
</html>
