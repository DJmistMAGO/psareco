@extends('layouts.app')
@section('title', 'PSARECO | Farm Resource Management System')

@section('content')
    <header x-data="{ open: false }" class="absolute inset-x-0 top-0 z-50" >
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3">

                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-md">
                    <svg
                        class="h-9 w-9 text-farm-700"
                        viewBox="0 0 48 48"
                        fill="none"
                    >
                        <circle
                            cx="24"
                            cy="24"
                            r="21"
                            stroke="currentColor"
                            stroke-width="3"
                        />

                        <path
                            d="M10 28C16 22 22 19 29 18C35 17 39 13 40 9"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                        />

                        <path
                            d="M12 33C18 28 24 26 32 26"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                        />

                        <path
                            d="M24 38V22"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div>
                    <div class="text-2xl font-black tracking-tight text-farm-900">
                        PSARECO
                    </div>

                    <div class="-mt-1 text-[9px] font-semibold uppercase tracking-wider text-farm-700">
                        Farm Resource Management System
                    </div>
                </div>

            </a>


            {{-- Desktop Navigation --}}
            <div class="hidden items-center gap-8 lg:flex">

                <a href="#home" class="border-b-2 border-farm-700 pb-1 text-sm font-semibold text-farm-800"
                > Home </a>

                <a href="#machinery" class="text-sm font-medium text-gray-700 transition hover:text-farm-700"
                > Machinery </a>

                <a href="#inventory" class="text-sm font-medium text-gray-700 transition hover:text-farm-700"
                > Inventory </a>

                <a href="#how-it-works" class="text-sm font-medium text-gray-700 transition hover:text-farm-700"
                > How It Works </a>

                <a href="#about" class="text-sm font-medium text-gray-700 transition hover:text-farm-700"
                > About PSARECO </a>

            </div>

            <div class="hidden items-center gap-3 lg:flex">
                <a href="{{-- route('login') --}}" class="rounded-lg border border-farm-700 px-6 py-2.5 text-sm font-semibold text-farm-800 transition hover:bg-farm-50" > Login  </a>
                <a href="{{-- route('register') --}}" class="rounded-lg bg-farm-700 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-farm-700/20 transition hover:bg-farm-800" > Get Started </a>
            </div>


            {{-- Mobile Menu Button --}}
            <button
                @click="open = !open"
                class="rounded-lg p-2 text-farm-900 lg:hidden"
            >
                <svg
                    x-show="!open"
                    class="h-7 w-7"
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
                    class="h-7 w-7"
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
        <div  x-show="open" x-transition class="mx-4 rounded-2xl bg-white p-5 shadow-xl lg:hidden" >
            <div class="flex flex-col gap-4">
                <a href="#home" class="font-medium"> Home </a>
                <a href="#machinery" class="font-medium"> Machinery </a>
                <a href="#inventory" class="font-medium"> Inventory </a>
                <a href="#how-it-works" class="font-medium"> How It Works </a>
                <a href="#about" class="font-medium"> About PSARECO </a>
                <hr>

                <a href="{{-- route('login')--}}" class="rounded-lg border border-farm-700 px-5 py-3 text-center font-semibold text-farm-700" > Login </a>

                <a href="{{-- route('register')--}}" class="rounded-lg bg-farm-700 px-5 py-3 text-center font-semibold text-white" > Get Started </a>
            </div>
        </div>

    </header>

    <section id="home" class="relative min-h-[700px] overflow-hidden" >

        {{-- Background Image --}}
        <div class="absolute inset-0">
            <img src="{{ asset('assets/images/tractor.png')  }}" alt="Farmer working with agricultural machinery" class="h-full w-full object-cover" >
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
        </div>


        <div class="relative mx-auto flex min-h-[700px] max-w-7xl items-center px-6 pt-24 lg:px-8">

            <div class="max-w-xl">

                {{-- Label --}}
                <div
                    class="mb-6 inline-flex items-center gap-2 rounded-full bg-farm-100 px-4 py-2 text-xs font-bold uppercase tracking-wider text-farm-800"
                >

                    <span class="h-2 w-2 rounded-full bg-farm-600"></span>

                    Farm Resource Management System

                </div>


                {{-- Heading --}}
                <h1 class="text-5xl font-black leading-[1.05] tracking-tight text-farm-900 sm:text-6xl">

                    Smarter Farm
                    <br>

                    Resources.

                    <br>

                    <span class="text-farm-600">
                        Better Harvests.
                    </span>

                </h1>


                {{-- Description --}}
                <p class="mt-7 max-w-lg text-lg leading-8 text-gray-700">

                    Schedule agricultural machinery, monitor farm resources,
                    and keep track of essential supplies—all in one simple
                    platform built for farmers.

                </p>


                {{-- CTA --}}
                <div class="mt-9 flex flex-col gap-4 sm:flex-row">

                    <a
                        href="#machinery"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-farm-700 px-7 py-4 font-bold text-white shadow-xl shadow-farm-700/20 transition hover:-translate-y-0.5 hover:bg-farm-800"
                    >

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>

                        Schedule Machinery

                    </a>


                    <a
                        href="#inventory"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-farm-700 bg-white/90 px-7 py-4 font-bold text-farm-800 transition hover:bg-farm-50"
                    >

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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


            {{-- Floating Availability Card --}}
            <div class="absolute right-8 top-44 hidden w-64 rounded-2xl bg-white p-5 shadow-2xl lg:block">

                <div class="text-xs font-semibold text-gray-500">
                    Machinery Availability
                </div>

                <div class="mt-3 flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-farm-100">

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

                    <div>
                        <div class="font-bold text-farm-900">
                            Tractor Available
                        </div>

                        <div class="text-xs text-gray-500">
                            Ready to Schedule
                        </div>
                    </div>

                </div>

            </div>


            {{-- Floating Schedule Card --}}
            <div class="absolute right-8 top-80 hidden w-64 rounded-2xl bg-white p-5 shadow-2xl lg:block">

                <div class="text-xs font-semibold text-gray-500">
                    Upcoming Schedule
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

            </div>

        </div>

    </section>

    <section class="relative z-10 mx-auto -mt-14 max-w-7xl px-6 lg:px-8">

        <div class="grid overflow-hidden rounded-2xl bg-white shadow-2xl sm:grid-cols-2 lg:grid-cols-4">

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
                    ],

                    [
                        'icon' => '🔔',
                        'title' => 'Notifications',
                        'text' => 'Stay updated about reservations, availability, and announcements.',
                        'button' => 'View Updates',
                        'href' => '#updates'
                    ]

                ];

            @endphp


            @foreach($quickLinks as $item)

                <div class="border-b border-gray-100 p-7 text-center transition hover:bg-farm-50 lg:border-b-0 lg:border-r last:border-r-0">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-farm-100 text-3xl">
                        {{ $item['icon']--}}
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-farm-900">
                        {{ $item['title']--}}
                    </h3>

                    <p class="mt-2 min-h-[48px] text-sm leading-6 text-gray-600">
                        {{ $item['text']--}}
                    </p>

                    <a
                        href="{{ $item['href']--}}"
                        class="mt-5 inline-flex rounded-lg bg-farm-700 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-farm-800"
                    >
                        {{ $item['button']--}}
                    </a>

                </div>

            @endforeach

        </div>

    </section>

    <section id="how-it-works" class="mx-auto max-w-7xl px-6 py-24 lg:px-8">

        <div class="text-center">

            <div class="mx-auto mb-4 h-1 w-20 rounded-full bg-farm-500"></div>

            <h2 class="text-3xl font-black text-farm-900 sm:text-4xl">
                Getting Started Is Easy
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-gray-600">
                Access agricultural resources in just a few simple steps.
            </p>

        </div>


        <div class="mt-14 grid gap-8 md:grid-cols-3">

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

                <div class="relative rounded-3xl border border-farm-100 bg-white p-8 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                    <div class="absolute left-6 top-6 flex h-9 w-9 items-center justify-center rounded-full bg-farm-700 text-sm font-black text-white">
                        {{ $step['number']--}}
                    </div>

                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-farm-100 text-4xl">
                        {{ $step['icon']--}}
                    </div>

                    <h3 class="mt-6 text-xl font-bold text-farm-900">
                        {{ $step['title']--}}
                    </h3>

                    <p class="mt-3 leading-7 text-gray-600">
                        {{ $step['text']--}}
                    </p>

                </div>

            @endforeach

        </div>

    </section>

    <section id="machinery" class="bg-farm-50 py-24" >

        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <div class="grid items-center gap-12 lg:grid-cols-5">

                {{-- Left --}}
                <div class="lg:col-span-2">

                    <span class="text-sm font-bold uppercase tracking-wider text-farm-600">
                        Machinery Scheduling
                    </span>

                    <h2 class="mt-3 text-4xl font-black leading-tight text-farm-900">
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
                        class="mt-7 inline-flex items-center gap-2 font-bold text-farm-700 hover:text-farm-900"
                    >
                        View All Machinery

                        <span>
                            →
                        </span>
                    </a>

                </div>


                {{-- Machinery Cards --}}
                <div class="grid gap-5 sm:grid-cols-2 lg:col-span-3 lg:grid-cols-3">

                    @php

                        $machinery = [

                            [
                                'name' => 'Tractor',
                                'type' => 'Farm Tractor',
                                'capacity' => '50 HP',
                                'image' => 'tractor.jpg',
                            ],

                            [
                                'name' => 'Rice Transplanter',
                                'type' => 'Rice Transplanter',
                                'capacity' => '8 Rows',
                                'image' => 'rice-transplanter.jpg',
                            ],

                            [
                                'name' => 'Harvester',
                                'type' => 'Combine Harvester',
                                'capacity' => '4–5 Ha/hr',
                                'image' => 'harvester.jpg',
                            ]

                        ];

                    @endphp


                    @foreach($machinery as $machine)

                        <div class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                            <div class="relative h-40 overflow-hidden">

                                <img
                                    src="{{ asset('images/' . $machine['image'])}}"
                                    alt="{{ $machine['name']--}}"
                                    class="h-full w-full object-cover transition duration-500 hover:scale-105"
                                >

                                <div class="absolute right-3 top-3 rounded-full bg-white px-3 py-1 text-xs font-bold text-farm-700 shadow">
                                    ● Available
                                </div>

                            </div>


                            <div class="p-5">

                                <h3 class="font-bold text-farm-900">
                                    {{ $machine['name']--}}
                                </h3>

                                <p class="mt-1 text-xs text-gray-500">
                                    Type: {{ $machine['type']--}}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    Capacity: {{ $machine['capacity']--}}
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

    </section>

    <section id="inventory" class="mx-auto max-w-7xl px-6 py-24 lg:px-8" >

        <div class="grid items-center gap-12 lg:grid-cols-2">

            {{-- Image --}}
            <div class="overflow-hidden rounded-3xl">

                <img
                    src="{{ asset('images/inventory.jpg') }}"
                    alt="Farm supplies"
                    class="h-[480px] w-full object-cover"
                >

            </div>


            {{-- Content --}}
            <div>

                <span class="text-sm font-bold uppercase tracking-wider text-farm-600">
                    Inventory Monitoring
                </span>

                <h2 class="mt-3 text-4xl font-black text-farm-900">
                    Know What Resources
                    <span class="text-farm-600">
                        Are Available
                    </span>
                </h2>

                <p class="mt-5 leading-7 text-gray-600">
                    Monitor essential farm resources and supplies so you can
                    plan your farming activities with confidence.
                </p>


                {{-- Inventory Table --}}
                <div class="mt-8 overflow-hidden rounded-2xl border border-farm-100 bg-white shadow-sm">

                    <div class="grid grid-cols-3 bg-farm-700 px-5 py-4 text-sm font-bold text-white">

                        <span>
                            Resource
                        </span>

                        <span>
                            Available
                        </span>

                        <span>
                            Status
                        </span>

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
                                {{ $item['name']--}}
                            </span>

                            <span class="text-gray-600">
                                {{ $item['amount']--}}
                            </span>

                            <span>

                                @if($item['color'] === 'green')

                                    <span class="inline-flex items-center gap-1 text-farm-700">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        {{ $item['status']--}}
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1 text-yellow-600">
                                        <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                                        {{ $item['status']--}}
                                    </span>

                                @endif

                            </span>

                        </div>

                    @endforeach

                </div>


                <a
                    href="#"
                    class="mt-7 inline-flex rounded-xl bg-farm-700 px-7 py-3.5 font-bold text-white hover:bg-farm-800"
                >
                    View Inventory
                </a>

            </div>

        </div>

    </section>

    <section class="bg-farm-50 py-24">

        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <div class="grid items-center gap-12 lg:grid-cols-2">

                <div>

                    <span class="text-sm font-bold uppercase tracking-wider text-farm-600">
                        Farmer Dashboard
                    </span>

                    <h2 class="mt-3 text-4xl font-black text-farm-900">
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
                        href="{{-- route('login')--}}"
                        class="mt-7 inline-flex rounded-xl bg-farm-700 px-7 py-3.5 font-bold text-white hover:bg-farm-800"
                    >
                        Try the System
                    </a>

                </div>


                {{-- Dashboard Mockup --}}
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">

                    {{-- Dashboard Header --}}
                    <div class="flex items-center justify-between border-b px-6 py-4">

                        <div class="flex items-center gap-2">

                            <div class="h-8 w-8 rounded-full bg-farm-700"></div>

                            <span class="font-bold text-farm-900">
                                PSARECO
                            </span>

                        </div>

                        <span class="text-sm text-gray-500">
                            Welcome back, Farmer!
                        </span>

                    </div>


                    <div class="grid min-h-[400px] grid-cols-[140px_1fr]">

                        {{-- Sidebar --}}
                        <aside class="bg-farm-900 p-4 text-white">

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


                        {{-- Dashboard Content --}}
                        <div class="bg-gray-50 p-5">

                            <h3 class="font-bold text-farm-900">
                                Today's Overview
                            </h3>


                            <div class="mt-4 grid grid-cols-2 gap-3">

                                <div class="rounded-xl bg-white p-4 shadow-sm">
                                    <div class="text-xs text-gray-500">
                                        Upcoming Machinery
                                    </div>

                                    <div class="mt-2 text-2xl font-black text-farm-700">
                                        1
                                    </div>
                                </div>


                                <div class="rounded-xl bg-white p-4 shadow-sm">
                                    <div class="text-xs text-gray-500">
                                        Active Requests
                                    </div>

                                    <div class="mt-2 text-2xl font-black text-farm-700">
                                        2
                                    </div>
                                </div>


                                <div class="rounded-xl bg-white p-4 shadow-sm">
                                    <div class="text-xs text-gray-500">
                                        Available Resources
                                    </div>

                                    <div class="mt-2 text-2xl font-black text-farm-700">
                                        4
                                    </div>
                                </div>


                                <div class="rounded-xl bg-white p-4 shadow-sm">
                                    <div class="text-xs text-gray-500">
                                        Notifications
                                    </div>

                                    <div class="mt-2 text-2xl font-black text-yellow-500">
                                        3
                                    </div>
                                </div>

                            </div>


                            <div class="mt-4 rounded-xl bg-white p-4 shadow-sm">

                                <div class="text-sm font-bold text-farm-900">
                                    Upcoming Schedule
                                </div>

                                <div class="mt-4 flex items-center justify-between">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-farm-100 text-xl">
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

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                        Approved
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="mx-auto max-w-7xl px-6 py-24 lg:px-8">

        <div class="text-center">

            <h2 class="text-3xl font-black text-farm-900 sm:text-4xl">
                Built Around the Needs of Farmers
            </h2>

        </div>


        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

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

                <div class="rounded-2xl border border-gray-100 bg-white p-7 text-center shadow-sm">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-farm-100 text-3xl">
                        {{ $benefit['icon']--}}
                    </div>

                    <h3 class="mt-5 font-bold text-farm-900">
                        {{ $benefit['title']--}}
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        {{ $benefit['text']--}}
                    </p>

                </div>

            @endforeach

        </div>

    </section>

    <section id="about"  class="bg-farm-50 py-24">

        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <div class="grid items-center gap-12 lg:grid-cols-2">

                <div class="overflow-hidden rounded-3xl">

                    <img
                        src="{{ asset('images/farmers-rice-field.jpg')}}"
                        alt="Farmers working in rice field"
                        class="h-[420px] w-full object-cover"
                    >

                </div>


                <div>

                    <span class="text-sm font-bold uppercase tracking-wider text-farm-600">
                        About PSARECO
                    </span>

                    <h2 class="mt-3 text-4xl font-black text-farm-900">
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

                                <p class="text-sm text-gray-600">
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

                                <p class="text-sm text-gray-600">
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

                                <p class="text-sm text-gray-600">
                                    Easy to use and available whenever farmers need it.
                                </p>

                            </div>

                        </div>

                    </div>


                    <a
                        href="#"
                        class="mt-8 inline-flex rounded-xl border-2 border-farm-700 px-6 py-3 font-bold text-farm-700 transition hover:bg-farm-700 hover:text-white"
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
                src="{{ asset('images/farmer-tractor.jpg') }}"
                alt=""
                class="h-full w-full object-cover opacity-20"
            >

        </div>


        <div class="relative mx-auto flex max-w-7xl flex-col items-center justify-between gap-8 px-6 py-16 text-center lg:flex-row lg:px-8 lg:text-left">

            <div>

                <h2 class="text-3xl font-black text-white sm:text-4xl">
                    Ready to Plan Your Next Farm Activity?
                </h2>

                <p class="mt-3 text-farm-100">
                    Schedule machinery, monitor resources, and manage your farm needs with ease.
                </p>

            </div>


            <div class="flex shrink-0 gap-3">

                <a
                    href="{{-- route('register')--}}"
                    class="rounded-xl bg-white px-7 py-3.5 font-bold text-farm-900 transition hover:bg-farm-50"
                >
                    Get Started
                </a>

                <a
                    href="{{-- route('login')--}}"
                    class="rounded-xl border border-white/70 px-7 py-3.5 font-bold text-white transition hover:bg-white/10"
                >
                    Login
                </a>

            </div>

        </div>

    </section>

    <footer class="bg-[#102d14] text-white">

        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">

            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">

                {{-- Brand --}}
                <div>

                    <div class="flex items-center gap-3">

                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white">

                            <svg
                                class="h-9 w-9 text-farm-700"
                                viewBox="0 0 48 48"
                                fill="none"
                            >

                                <circle
                                    cx="24"
                                    cy="24"
                                    r="21"
                                    stroke="currentColor"
                                    stroke-width="3"
                                />

                                <path
                                    d="M10 28C16 22 22 19 29 18C35 17 39 13 40 9"
                                    stroke="currentColor"
                                    stroke-width="3"
                                />

                            </svg>

                        </div>

                        <div>

                            <div class="text-2xl font-black">
                                PSARECO
                            </div>

                            <div class="text-[9px] uppercase tracking-wider text-farm-200">
                                Farm Resource Management System
                            </div>

                        </div>

                    </div>


                    <p class="mt-5 text-sm leading-6 text-farm-100/70">
                        Empowering farmers. Strengthening communities.
                        Building a better tomorrow.
                    </p>

                </div>


                {{-- Quick Links --}}
                <div>

                    <h3 class="font-bold">
                        Quick Links
                    </h3>

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


                {{-- Resources --}}
                <div>

                    <h3 class="font-bold">
                        Resources
                    </h3>

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
                            <a href="#" class="hover:text-white">
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


                {{-- Contact --}}
                <div>

                    <h3 class="font-bold">
                        Contact Us
                    </h3>

                    <div class="mt-5 space-y-3 text-sm text-farm-100/70">

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

                </div>

            </div>


            <div class="mt-12 border-t border-white/10 pt-6 text-center text-sm text-farm-100/50">

                © {{ date('Y')}} PSARECO. All Rights Reserved.

            </div>

        </div>

    </footer>
@endsection
