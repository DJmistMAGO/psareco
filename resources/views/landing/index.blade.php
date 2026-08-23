@extends('layouts.guest')
@section('title', 'PSARECO | Web-Based Farm Resource Management System')

@section('content')

@php
    $images = [
        'about' => 'https://images.pexels.com/photos/37395391/pexels-photo-37395391.jpeg?auto=compress&cs=tinysrgb&w=1800',
        'cta' => 'https://images.pexels.com/photos/36033665/pexels-photo-36033665.jpeg?auto=compress&cs=tinysrgb&w=1400',
    ];
@endphp

<header x-data="{ open: false }" class="absolute inset-x-0 top-0 z-50">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 sm:py-5 lg:px-8">

        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2.5 sm:gap-3"  >

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white shadow-md sm:h-15 sm:w-15">
                <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="h-full w-full object-contain" >
            </div>

            <div class="min-w-0">
                <div class="text-xl font-black tracking-tight text-farm-900 sm:text-2xl">
                    PSARECO
                </div>

                <div class="-mt-1 hidden text-[9px] font-semibold uppercase tracking-wider text-farm-700 sm:block">
                    Farm Resource Management System
                </div>
            </div>

        </a>


        <div class="hidden items-center gap-8 lg:flex">
            <a href="#home"  class="border-b-2 border-farm-700 pb-1 text-sm font-semibold text-farm-800">
                Home
            </a>

            <a href="#how-it-works" ="text-sm font-medium text-gray-700 transition hover:text-farm-700">
                How It Works
            </a>

            <a href="#about" class="text-sm font-medium text-gray-700 transition hover:text-farm-700">
                About PSARECO
            </a>
        </div>


        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ route('login') }}" class="rounded-lg bg-farm-700 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-farm-700/20 transition hover:bg-farm-800">
                Login
            </a>
        </div>


        <button type="button" @click="open = !open" :aria-expanded="open" class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/90 text-farm-900 shadow-sm backdrop-blur lg:hidden" >
            <svg x-show="!open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>

            <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </nav>


    <div x-show="open" x-cloak x-transition @click.outside="open = false" class="mx-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-2xl sm:mx-6 lg:hidden" >
        <div class="flex flex-col">
            <a @click="open = false href="#home class="rounded-xl px-4 py-3 font-semibold text-farm-900 hover:bg-farm-50 >
                Home
            </a>

            <a @click="open = false" href="#how-it-works" class="rounded-xl px-4 py-3 font-medium text-gray-700 hover:bg-farm-50" >
                How It Works
            </a>

            <a @click="open = false" href="#about" class="rounded-xl px-4 py-3 font-medium text-gray-700 hover:bg-farm-50" >
                About PSARECO
            </a>

            <div class="my-3 border-t border-gray-100"></div>

            <a href="{{ route('login') }}" class="rounded-xl bg-farm-700 px-4 py-3 text-center text-sm font-bold text-white" >
                Login
            </a>
        </div>
    </div>
</header>

<section id="home" class="relative min-h-[760px] overflow-hidden sm:min-h-[700px]">

    <div class="absolute inset-0">

        <img src="{{ asset('assets/images/tractor.png') }}" alt="Farmer working with agricultural machinery" class="h-full w-full object-cover object-center" >

        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-transparent"></div>

        <div class="absolute inset-0 bg-white/55 lg:bg-transparent"></div>

        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>

    </div>


    <div class="relative mx-auto flex min-h-[760px] max-w-7xl items-center px-4 pb-20 pt-32 sm:px-6 sm:pt-28 lg:min-h-[700px] lg:px-8 lg:pt-24">

        <div class="w-full max-w-xl">

            <div class="mb-5 inline-flex max-w-full items-center gap-2 rounded-full bg-farm-100 px-3.5 py-2 text-[10px] font-bold uppercase tracking-wider text-farm-800 sm:px-4 sm:text-xs">

                <span class="h-2 w-2 shrink-0 rounded-full bg-farm-600"></span>

                <span> Farm Resource Management System </span>

            </div>


            <h1 class="text-4xl font-black leading-[1.05] tracking-tight text-farm-900 sm:text-5xl md:text-6xl">

                Smarter Farm
                <br>
                Resources.
                <br>
                <span class="text-farm-600">
                    Better Harvests.
                </span>

            </h1>


            <p class="mt-5 max-w-lg text-base leading-7 text-gray-700 sm:mt-7 sm:text-lg sm:leading-8">

                Request machinery rentals, monitor fertilizer and pesticide
                inventory, and manage cooperative transactions—all in one
                platform built for PSARECO's ARB and non-ARB farmer-members
                in Polot <em>(San Francisco)</em> and Somagongsong, Bulan, Sorsogon.

            </p>


            <div class="mt-7 flex w-full flex-col gap-3 sm:mt-9 sm:flex-row sm:gap-4">

                <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-farm-700 bg-white/90 px-6 py-3.5 font-bold text-farm-800 transition hover:bg-farm-50 sm:w-auto sm:px-7 sm:py-4" >

                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 6a6 6 0 00-8.5 8.5L3 17l4 4 2.5-2.5A6 6 0 0014 10m0-4l4-4m0 0l4 4m-4-4v8" />
                    </svg>

                    Explore Resources

                </a>

            </div>

        </div>

    </div>

</section>

<section class="relative z-10 mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:-mt-14 lg:px-8">

    <div class="grid overflow-hidden rounded-2xl bg-white shadow-2xl sm:grid-cols-2 lg:grid-cols-3">

        @php
            $quickLinks = [
                [
                    'icon' => '🚜',
                    'title' => 'Machinery Scheduling',
                    'text' => 'Request and schedule hand tractors and other farm machinery.',
                ],
                [
                    'icon' => '📦',
                    'title' => 'Inventory Monitoring',
                    'text' => 'Track real-time fertilizer and pesticide stock levels.',
                ],
                [
                    'icon' => '📅',
                    'title' => 'My Schedules',
                    'text' => 'View upcoming machinery rental reservations and requests.',
                ],
            ];
        @endphp

        @foreach($quickLinks as $item)

            <div class="flex flex-col items-center justify-center border-b border-gray-100 px-5 py-6 text-center transition hover:bg-farm-50 sm:px-6 lg:border-b-0 lg:border-r last:border-r-0">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-farm-100 text-2xl">
                    {{ $item['icon'] }}
                </div>

                <h3 class="mt-3 text-base font-bold text-farm-900">
                    {{ $item['title'] }}
                </h3>

                <p class="mt-1.5 max-w-xs text-sm leading-5 text-gray-600">
                    {{ $item['text'] }}
                </p>

            </div>

        @endforeach

    </div>

</section>

<section id="how-it-works" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">

    <div class="text-center">

        <h2 class="text-3xl font-black text-farm-900 sm:text-4xl">
            Built Around PSARECO's Cooperative Operations
        </h2>

    </div>


    <div class="mt-10 grid gap-5 sm:mt-12 sm:grid-cols-2 lg:grid-cols-4">

        @php
            $benefits = [
                [
                    'icon' => '🌱',
                    'title' => 'Plan Better',
                    'text' => 'Book machinery through a calendar-based scheduling system.'
                ],
                [
                    'icon' => '🚜',
                    'title' => 'Access Resources',
                    'text' => 'Check hand tractor and other machinery availability before requesting.'
                ],
                [
                    'icon' => '📦',
                    'title' => 'Monitor Supplies',
                    'text' => 'Track fertilizer and pesticide stock levels in real time.'
                ],
                [
                    'icon' => '📱',
                    'title' => 'Stay Updated',
                    'text' => 'Get updates on rental approvals and transaction records.'
                ]
            ];
        @endphp


        @foreach($benefits as $benefit)

            <div class="rounded-2xl border border-gray-100 bg-white p-6 text-center shadow-sm sm:p-7">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-farm-100 text-2xl sm:h-16 sm:w-16 sm:text-3xl">
                    {{ $benefit['icon'] }}
                </div>

                <h3 class="mt-5 font-bold text-farm-900">
                    {{ $benefit['title'] }}
                </h3>

                <p class="mt-2 text-sm leading-6 text-gray-600">
                    {{ $benefit['text'] }}
                </p>

            </div>

        @endforeach

    </div>

</section>

<section id="about" class="bg-farm-50 py-20 sm:py-24">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-12">

            <div class="overflow-hidden rounded-3xl">

                <img src="{{ $images['about'] }}" alt="Farmers working in rice field" loading="lazy" class="h-64 w-full object-cover sm:h-80 lg:h-[420px]" >

            </div>


            <div>

                <span class="text-sm font-bold uppercase tracking-wider text-farm-600">
                    About PSARECO
                </span>

                <h2 class="mt-3 text-3xl font-black text-farm-900 sm:text-4xl">

                    Technology Supporting

                    <span class="text-farm-600">
                        Local Agriculture
                    </span>

                </h2>

                <p class="mt-5 leading-7 text-gray-600">
                    The Polot Somagongsong Agrarian Reform Cooperative (PSARECO)
                    serves Agrarian Reform Beneficiaries (ARBs) and non-ARB
                    farmer-members in Polot <em>(San Francisco)</em> and Somagongsong, Bulan, Sorsogon.
                    This system replaces handwritten records with a centralized
                    platform for machinery scheduling, inventory monitoring, and
                    transaction recording.
                </p>


                <div class="mt-7 space-y-4">

                    <div class="flex gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-farm-100">
                            🌾
                        </div>

                        <div>
                            <h3 class="font-bold text-farm-900">
                                For ARBs & Farmer-Members
                            </h3>

                            <p class="text-sm leading-6 text-gray-600">
                                Designed for PSARECO's Agrarian Reform Beneficiaries and non-ARB farmer-members.
                            </p>
                        </div>

                    </div>


                    <div class="flex gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-farm-100">
                            🤝
                        </div>

                        <div>
                            <h3 class="font-bold text-farm-900">
                                Cooperative Focused
                            </h3>

                            <p class="text-sm leading-6 text-gray-600">
                                Supporting machinery rental, fertilizer, and pesticide operations for the cooperative.
                            </p>
                        </div>

                    </div>


                    <div class="flex gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-farm-100">
                            ✓
                        </div>

                        <div>
                            <h3 class="font-bold text-farm-900">
                                Reliable & Accessible
                            </h3>

                            <p class="text-sm leading-6 text-gray-600">
                                Replacing manual, handwritten recordkeeping with a transparent digital system.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<section class="relative overflow-hidden bg-farm-900">

    <div class="absolute inset-0">

        <img
            src="{{ $images['cta'] }}"
            alt=""
            loading="lazy"
            class="h-full w-full object-cover opacity-20"
        >

    </div>


    <div class="relative mx-auto flex max-w-7xl flex-col items-center gap-7 px-4 py-14 text-center sm:px-6 sm:py-16 lg:flex-row lg:justify-between lg:px-8 lg:text-left">

        <div>

            <h2 class="text-2xl font-black text-white sm:text-3xl sm:text-4xl">
                Ready to Schedule Your Next Machinery Rental?
            </h2>

            <p class="mt-3 text-sm leading-6 text-farm-100 sm:text-base">
                Request machinery, monitor fertilizer and pesticide stock, and manage cooperative transactions with ease.
            </p>

        </div>


        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">

            <a
                href="{{ route('login') }}"
                class="w-full rounded-xl border border-white/70 px-7 py-3.5 text-center font-bold text-white transition hover:bg-white/10 sm:w-auto"
            >
                Login
            </a>

        </div>

    </div>

</section>

<footer class="bg-[#102d14] text-white">

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8">

        <div class="flex flex-col items-center gap-4 text-center">

            <div class="flex items-center gap-3">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-white sm:h-20 sm:w-20">
                    <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="h-full w-full object-contain">
                </div>
                <div class="text-left">
                    <div class="text-2xl font-black"> PSARECO </div>
                    <div class="text-[9px] uppercase tracking-wider text-farm-200">
                        Farm Resource Management System
                    </div>
                </div>
            </div>

            <p class="max-w-md text-sm leading-6 text-farm-100/70">
                Serving Agrarian Reform Beneficiaries and non-ARB farmer-members in Polot and Somagongsong, Bulan, Sorsogon.
            </p>

        </div>


        <div class="mt-10 border-t border-white/10 pt-6 text-center text-sm text-farm-100/50 sm:mt-12">
            © 2026 PSARECO. All Rights Reserved.
        </div>

    </div>

</footer>

@endsection
