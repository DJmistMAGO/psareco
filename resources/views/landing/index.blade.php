@extends('layouts.guest')
@section('title', 'PSARECO | Farm Resource Management System')

@section('content')

{{-- sample images only --}}
@php
    $images = [
        'tractor' => 'https://images.pexels.com/photos/33984723/pexels-photo-33984723.jpeg?auto=compress&cs=tinysrgb&w=1000',
        'transplanter' => 'https://images.pexels.com/photos/17234758/pexels-photo-17234758.jpeg?auto=compress&cs=tinysrgb&w=1000',
        'harvester' => 'https://images.pexels.com/photos/37412189/pexels-photo-37412189.jpeg?auto=compress&cs=tinysrgb&w=1000',
        'inventory' => 'https://images.pexels.com/photos/36440522/pexels-photo-36440522.jpeg?auto=compress&cs=tinysrgb&w=1400',
        'farmers' => 'https://images.pexels.com/photos/36033665/pexels-photo-36033665.jpeg?auto=compress&cs=tinysrgb&w=1400',
        'cta' => 'https://images.pexels.com/photos/37395391/pexels-photo-37395391.jpeg?auto=compress&cs=tinysrgb&w=1800',
    ];
@endphp

<header  x-data="{ open: false }" class="absolute inset-x-0 top-0 z-50">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 sm:py-5 lg:px-8">

        {{-- Logo --}}
        <a
            href="{{ route('home') }}"
            class="flex min-w-0 items-center gap-2.5 sm:gap-3"
        >

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white shadow-md sm:h-15 sm:w-15">
                <img
                    src="{{ asset('assets/images/PSARECO_logo.png') }}"
                    alt="PSARECO Logo"
                    class="h-full w-full object-contain"
                >
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


        {{-- Desktop Navigation --}}
        <div class="hidden items-center gap-8 lg:flex">

            <a href="#home"
               class="border-b-2 border-farm-700 pb-1 text-sm font-semibold text-farm-800">
                Home
            </a>

            {{-- <a href="#machinery"
               class="text-sm font-medium text-gray-700 transition hover:text-farm-700">
                Machinery
            </a>

            <a href="#inventory"
               class="text-sm font-medium text-gray-700 transition hover:text-farm-700">
                Inventory
            </a> --}}

            <a href="#how-it-works"
               class="text-sm font-medium text-gray-700 transition hover:text-farm-700">
                How It Works
            </a>

            <a href="#about"
               class="text-sm font-medium text-gray-700 transition hover:text-farm-700">
                About PSARECO
            </a>

        </div>


        {{-- Desktop Actions --}}
        <div class="hidden items-center gap-3 lg:flex">

            <a
                href="{{ route('login') }}"
                class="rounded-lg bg-farm-700 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-farm-700/20 transition hover:bg-farm-800"
            >
                Login
            </a>

            {{-- <a
                href="{{ route('register') }}"
                class="rounded-lg bg-farm-700 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-farm-700/20 transition hover:bg-farm-800"
            >
                Get Started
            </a> --}}

        </div>


        {{-- Mobile Menu Button --}}
        <button
            type="button"
            @click="open = !open"
            :aria-expanded="open"
            class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/90 text-farm-900 shadow-sm backdrop-blur lg:hidden"
        >

            <svg
                x-show="!open"
                x-cloak
                class="h-6 w-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>

            <svg
                x-show="open"
                x-cloak
                class="h-6 w-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>

        </button>

    </nav>


    {{-- Mobile Menu --}}
    <div
        x-show="open"
        x-cloak
        x-transition
        @click.outside="open = false"
        class="mx-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-2xl sm:mx-6 lg:hidden"
    >

        <div class="flex flex-col">

            <a
                @click="open = false"
                href="#home"
                class="rounded-xl px-4 py-3 font-semibold text-farm-900 hover:bg-farm-50"
            >
                Home
            </a>

            <a
                @click="open = false"
                href="#machinery"
                class="rounded-xl px-4 py-3 font-medium text-gray-700 hover:bg-farm-50"
            >
                Machinery
            </a>

            <a
                @click="open = false"
                href="#inventory"
                class="rounded-xl px-4 py-3 font-medium text-gray-700 hover:bg-farm-50"
            >
                Inventory
            </a>

            <a
                @click="open = false"
                href="#how-it-works"
                class="rounded-xl px-4 py-3 font-medium text-gray-700 hover:bg-farm-50"
            >
                How It Works
            </a>

            <a
                @click="open = false"
                href="#about"
                class="rounded-xl px-4 py-3 font-medium text-gray-700 hover:bg-farm-50"
            >
                About PSARECO
            </a>

            <div class="my-3 border-t border-gray-100"></div>

            <div class="grid grid-cols-2 gap-3">

                <a
                    href="{{ route('login') }}"
                    class="rounded-xl border border-farm-700 px-4 py-3 text-center text-sm font-bold text-farm-700"
                >
                    Login
                </a>

                {{-- <a
                    href="{{ route('register') }}"
                    class="rounded-xl bg-farm-700 px-4 py-3 text-center text-sm font-bold text-white"
                >
                    Get Started
                </a> --}}

            </div>

        </div>

    </div>
</header>

<section id="home"  class="relative min-h-[760px] overflow-hidden sm:min-h-[700px]" >

    {{-- Background --}}
    <div class="absolute inset-0">

        <img
            src="{{ asset('assets/images/tractor.png') }}"
            alt="Farmer working with agricultural machinery"
            class="h-full w-full object-cover object-center"
        >

        {{-- Desktop gradient --}}
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-transparent"></div>

        {{-- Mobile stronger overlay --}}
        <div class="absolute inset-0 bg-white/55 lg:bg-transparent"></div>

        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>

    </div>


    <div class="relative mx-auto flex min-h-[760px] max-w-7xl items-center px-4 pb-20 pt-32 sm:px-6 sm:pt-28 lg:min-h-[700px] lg:px-8 lg:pt-24">

        <div class="w-full max-w-xl">

            {{-- Label --}}
            <div class="mb-5 inline-flex max-w-full items-center gap-2 rounded-full bg-farm-100 px-3.5 py-2 text-[10px] font-bold uppercase tracking-wider text-farm-800 sm:px-4 sm:text-xs">

                <span class="h-2 w-2 shrink-0 rounded-full bg-farm-600"></span>

                <span>
                    Farm Resource Management System
                </span>

            </div>


            {{-- Heading --}}
            <h1 class="text-4xl font-black leading-[1.05] tracking-tight text-farm-900 sm:text-5xl md:text-6xl">

                Smarter Farm
                <br>

                Resources.
                <br>

                <span class="text-farm-600">
                    Better Harvests.
                </span>

            </h1>


            {{-- Description --}}
            <p class="mt-5 max-w-lg text-base leading-7 text-gray-700 sm:mt-7 sm:text-lg sm:leading-8">

                Schedule agricultural machinery, monitor farm resources,
                and keep track of essential supplies—all in one simple
                platform built for farmers.

            </p>


            {{-- CTA --}}
            <div class="mt-7 flex w-full flex-col gap-3 sm:mt-9 sm:flex-row sm:gap-4">

                {{-- <a
                    href="#machinery"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-farm-700 px-6 py-3.5 font-bold text-white shadow-xl shadow-farm-700/20 transition hover:-translate-y-0.5 hover:bg-farm-800 sm:w-auto sm:px-7 sm:py-4"
                >

                    <svg
                        class="h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>

                    Schedule Machinery

                </a> --}}


                <a
                    href="#inventory"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-farm-700 bg-white/90 px-6 py-3.5 font-bold text-farm-800 transition hover:bg-farm-50 sm:w-auto sm:px-7 sm:py-4"
                >

                    <svg
                        class="h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M14 6a6 6 0 00-8.5 8.5L3 17l4 4 2.5-2.5A6 6 0 0014 10m0-4l4-4m0 0l4 4m-4-4v8"
                        />
                    </svg>

                    Explore Resources

                </a>

            </div>

        </div>


        {{-- Floating cards --}}
        {{-- <div class="absolute right-8 top-44 hidden w-64 rounded-2xl bg-white p-5 shadow-2xl lg:block">

            <div class="text-xs font-semibold text-gray-500">
                Machinery Availability
            </div>

            <div class="mt-3 flex items-center gap-3">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-farm-100">

                    <svg
                        class="h-6 w-6 text-farm-700"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 17h16M6 17v-5h7l3 5M9 12V7h4l2 5M4 17v2h3v-2m10 0v2h3v-2"
                        />
                    </svg>

                </div>

                <div class="min-w-0">

                    <div class="truncate font-bold text-farm-900">
                        Tractor Available
                    </div>

                    <div class="text-xs text-gray-500">
                        Ready to Schedule
                    </div>

                </div>

            </div>

        </div> --}}


        {{-- <div class="absolute right-8 top-80 hidden w-64 rounded-2xl bg-white p-5 shadow-2xl lg:block">

            <div class="text-xs font-semibold text-gray-500">
                Date & Time
            </div>

            <div class="mt-3 flex items-center gap-3">

                <div class="text-3xl">
                    🚜
                </div>

                <div>

                    <div class="font-bold text-farm-900">
                        Aug 12 · 8:00 AM
                    </div>

                    <div class="text-xs text-gray-500">
                        Tractor · Brgy. San Isidro
                    </div>

                </div>

            </div>

        </div> --}}

    </div>

</section>

<section class="relative z-10 mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:-mt-14 lg:px-8">

    <div class="grid overflow-hidden rounded-2xl bg-white shadow-2xl sm:grid-cols-2 lg:grid-cols-3">

        @php
            $quickLinks = [
                [
                    'icon' => '🚜',
                    'title' => 'Machinery Scheduling',
                    'text' => 'Reserve agricultural machinery when you need it.',
                    'button' => 'View Machinery',
                    'href' => '#machinery'
                ],
                [
                    'icon' => '📦',
                    'title' => 'Inventory Monitoring',
                    'text' => 'Keep track of available farm supplies and resources.',
                    'button' => 'Check Inventory',
                    'href' => '#inventory'
                ],
                [
                    'icon' => '📅',
                    'title' => 'My Schedules',
                    'text' => 'View upcoming machinery reservations and requests.',
                    'button' => 'View Schedule',
                    'href' => '#schedule'
                ]
            ];
        @endphp

        @foreach($quickLinks as $item)

            <div class="flex flex-col items-center justify-center border-b border-gray-100 px-5 py-6 text-center transition hover:bg-farm-50 sm:px-6 lg:border-b-0 lg:border-r last:border-r-0">

                {{-- Icon --}}
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-farm-100 text-2xl">
                    {{ $item['icon'] }}
                </div>

                {{-- Title --}}
                <h3 class="mt-3 text-base font-bold text-farm-900">
                    {{ $item['title'] }}
                </h3>

                {{-- Description --}}
                <p class="mt-1.5 max-w-xs text-sm leading-5 text-gray-600">
                    {{ $item['text'] }}
                </p>

                {{-- Button --}}
                {{--
                <a
                    href="{{ $item['href'] }}"
                    class="mt-4 inline-flex rounded-lg bg-farm-700 px-5 py-2 text-sm font-bold text-white transition hover:bg-farm-800"
                >
                    {{ $item['button'] }}
                </a>
                --}}

            </div>

        @endforeach

    </div>

</section>

{{-- <section id="how-it-works" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8" >

    <div class="text-center">

        <div class="mx-auto mb-4 h-1 w-16 rounded-full bg-farm-500 sm:w-20"></div>

        <h2 class="text-3xl font-black text-farm-900 sm:text-4xl">
            Getting Started Is Easy
        </h2>

        <p class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-gray-600 sm:text-base">
            Access agricultural resources in just a few simple steps.
        </p>

    </div>


    <div class="mt-10 grid gap-6 md:mt-14 md:grid-cols-3 md:gap-8">

        @php
            $steps = [
                [
                    'number' => '01',
                    'icon' => '👨‍🌾',
                    'title' => 'Register',
                    'text' => 'Create your farmer account and provide the required information.'
                ],
                [
                    'number' => '02',
                    'icon' => '📅',
                    'title' => 'Schedule',
                    'text' => 'Find available machinery and submit a schedule request based on your farming needs.'
                ],
                [
                    'number' => '03',
                    'icon' => '🚜',
                    'title' => 'Farm',
                    'text' => 'Use the scheduled machinery and keep track of your resources through the platform.'
                ]
            ];
        @endphp


        @foreach($steps as $step)

            <div class="relative rounded-3xl border border-farm-100 bg-white p-7 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-xl sm:p-8">

                <div class="absolute left-5 top-5 flex h-9 w-9 items-center justify-center rounded-full bg-farm-700 text-sm font-black text-white sm:left-6 sm:top-6">
                    {{ $step['number'] }}
                </div>

                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-farm-100 text-3xl sm:h-24 sm:w-24 sm:text-4xl">
                    {{ $step['icon'] }}
                </div>

                <h3 class="mt-5 text-xl font-bold text-farm-900">
                    {{ $step['title'] }}
                </h3>

                <p class="mt-3 leading-7 text-gray-600">
                    {{ $step['text'] }}
                </p>

            </div>

        @endforeach

    </div>

</section> --}}

{{-- <section id="machinery" class="bg-farm-50 py-20 sm:py-24">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid items-start gap-10 lg:grid-cols-5 lg:items-center lg:gap-12">

            {{-- Content
            <div class="lg:col-span-2">

                <span class="text-sm font-bold uppercase tracking-wider text-farm-600">
                    Machinery Scheduling
                </span>

                <h2 class="mt-3 text-3xl font-black leading-tight text-farm-900 sm:text-4xl">

                    The Machinery You Need,

                    <span class="text-farm-600">
                        When You Need It
                    </span>

                </h2>

                <p class="mt-5 leading-7 text-gray-600">
                    Find available agricultural machinery and schedule equipment
                    according to your farming activities.
                </p>

                <a
                    href="#"
                    class="mt-6 inline-flex items-center gap-2 font-bold text-farm-700 hover:text-farm-900 sm:mt-7"
                >
                    View All Machinery
                    <span>→</span>
                </a>

            </div>


            {{-- Cards
            <div class="grid gap-5 sm:grid-cols-2 lg:col-span-3 lg:grid-cols-3">

                @php
                    $machinery = [
                        [
                            'name' => 'Tractor',
                            'type' => 'Farm Tractor',
                            'capacity' => '50 HP',
                            'image' => $images['tractor'],
                        ],
                        [
                            'name' => 'Rice Transplanter',
                            'type' => 'Rice Transplanter',
                            'capacity' => '8 Rows',
                            'image' => $images['transplanter'],
                        ],
                        [
                            'name' => 'Harvester',
                            'type' => 'Combine Harvester',
                            'capacity' => '4–5 Ha/hr',
                            'image' => $images['harvester'],
                        ]
                    ];
                @endphp


                @foreach($machinery as $machine)

                    <div class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                        <div class="relative h-44 overflow-hidden sm:h-40">

                            <img
                                src="{{ $machine['image'] }}"
                                alt="{{ $machine['name'] }}"
                                loading="lazy"
                                class="h-full w-full object-cover transition duration-500 hover:scale-105"
                            >

                            <div class="absolute right-3 top-3 rounded-full bg-white px-3 py-1 text-xs font-bold text-farm-700 shadow">
                                ● Available
                            </div>

                        </div>


                        <div class="p-5">

                            <h3 class="font-bold text-farm-900">
                                {{ $machine['name'] }}
                            </h3>

                            <p class="mt-1 text-xs text-gray-500">
                                Type: {{ $machine['type'] }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Capacity: {{ $machine['capacity'] }}
                            </p>

                            <a
                                href="#"
                                class="mt-5 block rounded-lg bg-farm-700 py-2.5 text-center text-sm font-bold text-white hover:bg-farm-800"
                            >
                                Schedule
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</section> --}}

{{-- <section id="inventory" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8" >

    <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-12">

        {{-- Image
        <div class="overflow-hidden rounded-3xl">

            <img
                src="{{ $images['inventory'] }}"
                alt="Farm supplies"
                loading="lazy"
                class="h-64 w-full object-cover sm:h-80 lg:h-[480px]"
            >

        </div>


        {{-- Content
        <div>

            <span class="text-sm font-bold uppercase tracking-wider text-farm-600">
                Inventory Monitoring
            </span>

            <h2 class="mt-3 text-3xl font-black text-farm-900 sm:text-4xl">

                Know What Resources

                <span class="text-farm-600">
                    Are Available
                </span>

            </h2>

            <p class="mt-5 leading-7 text-gray-600">
                Monitor essential farm resources and supplies so you can
                plan your farming activities with confidence.
            </p>

            {{-- Mobile scroll wrapper
            <div class="mt-8 overflow-x-auto rounded-2xl border border-farm-100 bg-white shadow-sm">

                <div class="min-w-[520px]">

                    <div class="grid grid-cols-3 bg-farm-700 px-5 py-4 text-sm font-bold text-white">

                        <span>Resource</span>
                        <span>Available</span>
                        <span>Status</span>

                    </div>


                    @php
                        $inventory = [
                            [
                                'name' => 'Rice Seeds',
                                'amount' => '1,250 kg',
                                'status' => 'Available',
                                'color' => 'green'
                            ],
                            [
                                'name' => 'Fertilizer',
                                'amount' => '580 bags',
                                'status' => 'Low Stock',
                                'color' => 'yellow'
                            ],
                            [
                                'name' => 'Diesel',
                                'amount' => '820 L',
                                'status' => 'Available',
                                'color' => 'green'
                            ],
                            [
                                'name' => 'Spare Parts',
                                'amount' => '42 items',
                                'status' => 'Low Stock',
                                'color' => 'yellow'
                            ]
                        ];
                    @endphp


                    @foreach($inventory as $item)

                        <div class="grid grid-cols-3 border-t border-gray-100 px-5 py-4 text-sm">

                            <span class="font-semibold text-gray-700">
                                {{ $item['name'] }}
                            </span>

                            <span class="text-gray-600">
                                {{ $item['amount'] }}
                            </span>

                            <span>

                                @if($item['color'] === 'green')

                                    <span class="inline-flex items-center gap-1 text-farm-700">

                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>

                                        {{ $item['status'] }}

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1 text-yellow-600">

                                        <span class="h-2 w-2 rounded-full bg-yellow-500"></span>

                                        {{ $item['status'] }}

                                    </span>

                                @endif

                            </span>

                        </div>

                    @endforeach

                </div>

            </div>


            <a
                href="#"
                class="mt-7 inline-flex w-full justify-center rounded-xl bg-farm-700 px-7 py-3.5 font-bold text-white hover:bg-farm-800 sm:w-auto"
            >
                View Inventory
            </a>

        </div>

    </div>

</section> --}}

{{-- <section class="bg-farm-50 py-20 sm:py-24">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-12">

            <div>

                <span class="text-sm font-bold uppercase tracking-wider text-farm-600">
                    Farmer Dashboard
                </span>

                <h2 class="mt-3 text-3xl font-black text-farm-900 sm:text-4xl">

                    Everything You Need

                    <span class="text-farm-600">
                        In One Place
                    </span>

                </h2>

                <p class="mt-5 leading-7 text-gray-600">
                    Get an overview of your machinery requests,
                    upcoming schedules, resources, and notifications
                    from one simple dashboard.
                </p>

                <a
                    href="#"
                    class="mt-7 inline-flex w-full justify-center rounded-xl bg-farm-700 px-7 py-3.5 font-bold text-white hover:bg-farm-800 sm:w-auto"
                >
                    Try the System
                </a>

            </div>


            {{-- Dashboard
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">

                {{-- Header --
                <div class="flex flex-col gap-3 border-b px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                    <div class="flex items-center gap-2">

                        <div class="h-8 w-8 shrink-0 rounded-full bg-farm-700"></div>

                        <span class="font-bold text-farm-900">
                            PSARECO
                        </span>

                    </div>

                    <span class="text-xs text-gray-500 sm:text-sm">
                        Welcome back, Farmer!
                    </span>

                </div>


                <div class="grid min-h-0 grid-cols-1 md:grid-cols-[140px_1fr]">

                    {{-- Sidebar --
                    <aside class="hidden bg-farm-900 p-4 text-white md:block">

                        <div class="space-y-2 text-xs">

                            <div class="rounded-lg bg-farm-700 px-3 py-2">
                                Dashboard
                            </div>

                            <div class="px-3 py-2 opacity-80">
                                Machinery
                            </div>

                            <div class="px-3 py-2 opacity-80">
                                My Schedules
                            </div>

                            <div class="px-3 py-2 opacity-80">
                                Inventory
                            </div>

                            <div class="px-3 py-2 opacity-80">
                                Requests
                            </div>

                            <div class="px-3 py-2 opacity-80">
                                Notifications
                            </div>

                        </div>

                    </aside>


                    {{-- Mobile mini navigation
                    <div class="overflow-x-auto bg-farm-900 p-3 md:hidden">

                        <div class="flex min-w-max gap-2 text-[11px] text-white">

                            <span class="rounded-lg bg-farm-700 px-3 py-2">
                                Dashboard
                            </span>

                            <span class="rounded-lg bg-white/10 px-3 py-2">
                                Machinery
                            </span>

                            <span class="rounded-lg bg-white/10 px-3 py-2">
                                Schedules
                            </span>

                            <span class="rounded-lg bg-white/10 px-3 py-2">
                                Inventory
                            </span>

                            <span class="rounded-lg bg-white/10 px-3 py-2">
                                Requests
                            </span>

                        </div>

                    </div>


                    {{-- Dashboard Content -
                    <div class="bg-gray-50 p-4 sm:p-5">

                        <h3 class="font-bold text-farm-900">
                            Today's Overview
                        </h3>


                        <div class="mt-4 grid grid-cols-2 gap-3">

                            <div class="rounded-xl bg-white p-3 shadow-sm sm:p-4">

                                <div class="text-[10px] text-gray-500 sm:text-xs">
                                    Upcoming Machinery
                                </div>

                                <div class="mt-2 text-xl font-black text-farm-700 sm:text-2xl">
                                    1
                                </div>

                            </div>


                            <div class="rounded-xl bg-white p-3 shadow-sm sm:p-4">

                                <div class="text-[10px] text-gray-500 sm:text-xs">
                                    Active Requests
                                </div>

                                <div class="mt-2 text-xl font-black text-farm-700 sm:text-2xl">
                                    2
                                </div>

                            </div>


                            <div class="rounded-xl bg-white p-3 shadow-sm sm:p-4">

                                <div class="text-[10px] text-gray-500 sm:text-xs">
                                    Available Resources
                                </div>

                                <div class="mt-2 text-xl font-black text-farm-700 sm:text-2xl">
                                    4
                                </div>

                            </div>


                            <div class="rounded-xl bg-white p-3 shadow-sm sm:p-4">

                                <div class="text-[10px] text-gray-500 sm:text-xs">
                                    Notifications
                                </div>

                                <div class="mt-2 text-xl font-black text-yellow-500 sm:text-2xl">
                                    3
                                </div>

                            </div>

                        </div>


                        <div class="mt-4 rounded-xl bg-white p-4 shadow-sm">

                            <div class="text-sm font-bold text-farm-900">
                                Upcoming Schedule
                            </div>

                            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-farm-100 text-xl">
                                        🚜
                                    </div>

                                    <div>

                                        <div class="font-semibold">
                                            Tractor
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            August 12, 2026
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            8:00 AM – 12:00 PM
                                        </div>

                                    </div>

                                </div>

                                <span class="w-fit rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                    Approved
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section> --}}

<section id="how-it-works" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">

    <div class="text-center">

        <h2 class="text-3xl font-black text-farm-900 sm:text-4xl">
            Built Around the Needs of Farmers
        </h2>

    </div>


    <div class="mt-10 grid gap-5 sm:mt-12 sm:grid-cols-2 lg:grid-cols-4">

        @php
            $benefits = [
                [
                    'icon' => '🌱',
                    'title' => 'Plan Better',
                    'text' => 'Schedule machinery according to your farming activities.'
                ],
                [
                    'icon' => '🚜',
                    'title' => 'Access Resources',
                    'text' => 'Know which agricultural equipment is available.'
                ],
                [
                    'icon' => '📦',
                    'title' => 'Monitor Supplies',
                    'text' => 'Keep track of essential farm resources.'
                ],
                [
                    'icon' => '📱',
                    'title' => 'Stay Updated',
                    'text' => 'Receive important updates about requests and schedules.'
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

                <img
                    src="{{ $images['cta'] }}"
                    alt="Farmers working in rice field"
                    loading="lazy"
                    class="h-64 w-full object-cover sm:h-80 lg:h-[420px]"
                >

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
                    PSARECO's Farm Resource Management System helps
                    connect farmers with agricultural resources through
                    a simpler, more organized, and accessible digital platform.
                </p>


                <div class="mt-7 space-y-4">

                    <div class="flex gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-farm-100">
                            🌾
                        </div>

                        <div>
                            <h3 class="font-bold text-farm-900">
                                For Farmers
                            </h3>

                            <p class="text-sm leading-6 text-gray-600">
                                Designed specifically around the needs of farming communities.
                            </p>
                        </div>

                    </div>


                    <div class="flex gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-farm-100">
                            🤝
                        </div>

                        <div>
                            <h3 class="font-bold text-farm-900">
                                Community Focused
                            </h3>

                            <p class="text-sm leading-6 text-gray-600">
                                Helping agricultural communities organize resources better.
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
                                Easy to use and available whenever farmers need it.
                            </p>
                        </div>

                    </div>

                </div>


                <a
                    href="#"
                    class="mt-8 inline-flex w-full justify-center rounded-xl border-2 border-farm-700 px-6 py-3 font-bold text-farm-700 transition hover:bg-farm-700 hover:text-white sm:w-auto"
                >
                    Learn More About PSARECO
                </a>

            </div>

        </div>

    </div>

</section>

<section class="relative overflow-hidden bg-farm-900">

    <div class="absolute inset-0">

        <img
            src="{{ $images['farmers'] }}"
            alt=""
            loading="lazy"
            class="h-full w-full object-cover opacity-20"
        >

    </div>


    <div class="relative mx-auto flex max-w-7xl flex-col items-center gap-7 px-4 py-14 text-center sm:px-6 sm:py-16 lg:flex-row lg:justify-between lg:px-8 lg:text-left">

        <div>

            <h2 class="text-2xl font-black text-white sm:text-3xl sm:text-4xl">
                Ready to Plan Your Next Farm Activity?
            </h2>

            <p class="mt-3 text-sm leading-6 text-farm-100 sm:text-base">
                Schedule machinery, monitor resources, and manage your farm needs with ease.
            </p>

        </div>


        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">

            {{-- <a
                href="{{ route('register') }}"
                class="w-full rounded-xl bg-white px-7 py-3.5 text-center font-bold text-farm-900 transition hover:bg-farm-50 sm:w-auto"
            >
                Get Started
            </a> --}}

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
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-white sm:h-20 sm:w-20">
                        <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="h-full w-full object-contain">
                    </div>
                    <div>
                        <div class="text-2xl font-black"> PSARECO </div>
                        <div class="text-[9px] uppercase tracking-wider text-farm-200">
                            Farm Resource Management System
                        </div>
                    </div>
                </div>
                {{-- <p class="mt-5 max-w-sm text-sm leading-6 text-farm-100/70">
                    Empowering farmers. Strengthening communities.
                    Building a better tomorrow.
                </p> --}}
            </div>
            {{-- <div>
                <h3 class="font-bold"> Quick Links </h3>
                <ul class="mt-5 space-y-3 text-sm text-farm-100/70">
                    <li>
                        <a href="#home" class="hover:text-white">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#machinery" class="hover:text-white">
                            Machinery
                        </a>
                    </li>
                    <li>
                        <a href="#inventory" class="hover:text-white">
                            Inventory
                        </a>
                    </li>
                    <li>
                        <a href="#how-it-works" class="hover:text-white">
                            How It Works
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold"> Resources </h3>
                <ul class="mt-5 space-y-3 text-sm text-farm-100/70">
                    <li>
                        <a href="#" class="hover:text-white">
                            Help Center
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white">
                            FAQs
                        </a>
                    </li>
                    <li>
                        <a href="#about" class="hover:text-white">
                            About PSARECO
                        </a>
                    </li>

                    <li>
                        <a href="#" class="hover:text-white">
                            Contact
                        </a>
                    </li>

                </ul>

            </div>


            <div>

                <h3 class="font-bold">
                    Contact Us
                </h3>

                <div class="mt-5 space-y-3 break-words text-sm text-farm-100/70">

                    <p>
                        📞 (0917) 123-4567
                    </p>

                    <p>
                        ✉ info@psareco.com.ph
                    </p>

                    <p>
                        📍 Agricultural Community Office
                    </p>

                </div>

            </div> --}}

        </div>


        <div class="mt-10 border-t border-white/10 pt-6 text-center text-sm text-farm-100/50 sm:mt-12">
            © 2026 PSARECO. All Rights Reserved.
        </div>

    </div>

</footer>

@endsection
