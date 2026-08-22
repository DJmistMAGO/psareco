@extends('layouts.app')

@section('title', 'Inventory - PSARECO')

@section('content')

<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">

    <x-dashboard-header />

    <x-page-header eyebrow="PSARECO Inventory" title="Inventory Management" description="Monitor farm supplies, stock levels, pricing, and expiration dates." icon="fa-solid fa-boxes-stacked" >
        <x-slot:actions>
            <button type="button" onclick="document.getElementById('addProductModal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-700 text-sm font-bold shadow-sm hover:bg-emerald-50 transition" >
                <i class="fa-solid fa-plus"></i>
                Add Product
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-success />
    <x-errors />
    {{-- @if(session('success'))
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold">
                    Success
                </p>
                <p class="text-xs mt-0.5 text-emerald-700">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif


    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
            <div class="flex items-center gap-2 text-red-700 mb-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p class="text-sm font-bold">
                    Please correct the following:
                </p>
            </div>
            <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}


    <section class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 mb-6">
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">
                        Total Products
                    </p>
                    <p class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $totalProducts }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-box text-slate-500"></i>
                </div>
            </div>
        </div>


        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">
                        Fertilizers
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-700">
                        {{ $fertilizerCount }}
                    </p>
                </div>

                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class="fa-solid fa-leaf text-emerald-600"></i>
                </div>

            </div>
        </div>


        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">
                        Pesticides
                    </p>
                    <p class="mt-1 text-2xl font-bold text-amber-600">
                        {{ $pesticideCount }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class="fa-solid fa-bug text-amber-500"></i>
                </div>
            </div>
        </div>


        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">
                        Low Stock
                    </p>
                    <p class="mt-1 text-2xl font-bold text-red-600">
                        {{ $lowStockCount }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                </div>
            </div>
        </div>


        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">
                        Expiring Soon
                    </p>
                    <p class="mt-1 text-2xl font-bold text-orange-600">
                        {{ $expiringCount }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-xmark text-orange-500"></i>
                </div>
            </div>
        </div>

    </section>

    <section class="bg-white border border-slate-100 rounded-2xl shadow-sm p-4 mb-6">

        <form action="{{ route('inventory.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3" >

            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </div>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" >

            </div>


            <select name="type" class="lg:w-48 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500" >
                <option value="all">All Categories</option>
                <option value="Fertilizer" @selected(request('type') === 'Fertilizer')>
                    Fertilizers
                </option>
                <option value="Pesticide" @selected(request('type') === 'Pesticide')>
                    Pesticides
                </option>
            </select>


            <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold transition" >
                <i class="fa-solid fa-filter text-xs"></i>
                Filter
            </button>

            @if(request()->hasAny(['search', 'type']))

                <a href="{{ route('inventory.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition" >
                    <i class="fa-solid fa-xmark text-xs"></i>
                    Clear
                </a>
            @endif

        </form>

    </section>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">

        <div>
            <h2 class="text-lg font-bold text-slate-800">
                Products
            </h2>

            <p class="text-xs text-slate-400 mt-0.5">
                {{ $inventories->count() }}
                {{ Str::plural('product', $inventories->count()) }}
                displayed
            </p>
        </div>

    </div>


    @if($inventories->count())

        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

            @foreach($inventories as $item)

                @php

                    $isLowStock = $item->quantity <= $item->reorder_level;

                    $isExpired = false;
                    $isExpiring = false;
                    $daysUntilExpiration = null;

                    if ($item->expiration_date) {

                        $today = now()->startOfDay();

                        $expirationDate = \Carbon\Carbon::parse(
                            $item->expiration_date
                        )->startOfDay();

                        $isExpired = $expirationDate->lt($today);

                        if (!$isExpired) {

                            $daysUntilExpiration = $today->diffInDays(
                                $expirationDate
                            );

                            $isExpiring = $daysUntilExpiration <= 30;
                        }
                    }

                    if ($item->type === 'Fertilizer') {
                        $icon = 'fa-leaf';
                        $iconBg = 'bg-emerald-100';
                        $iconColor = 'text-emerald-600';
                        $accent = 'from-emerald-50 to-green-50';
                    } else {
                        $icon = 'fa-bug';
                        $iconBg = 'bg-amber-100';
                        $iconColor = 'text-amber-600';
                        $accent = 'from-amber-50 to-orange-50';
                    }

                @endphp


                <article class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">

                    {{-- Product visual --}}
                    <div class="relative h-36 bg-gradient-to-br {{ $accent }} overflow-hidden">

                        <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/50"></div>

                        <div class="absolute -left-10 -bottom-16 w-36 h-36 rounded-full bg-white/30"></div>

                        <div class="relative h-full flex items-center justify-center">

                            <div class="w-20 h-20 rounded-2xl {{ $iconBg }} {{ $iconColor }} flex items-center justify-center shadow-sm">
                                <i class="fa-solid {{ $icon }} text-3xl"></i>
                            </div>

                        </div>


                        {{-- Category badge --}}
                        <div class="absolute top-4 left-4">

                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur text-[10px] font-bold uppercase tracking-wide text-slate-600 shadow-sm">

                                <i class="fa-solid {{ $icon }} {{ $iconColor }}"></i>

                                {{ $item->type }}

                            </span>

                        </div>


                        {{-- Stock badge --}}
                        <div class="absolute top-4 right-4">

                            @if($isLowStock)

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-500 text-white text-[10px] font-bold shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    Low Stock
                                </span>

                            @else

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur text-emerald-700 text-[10px] font-bold shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    In Stock
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Product information --}}
                    <div class="p-5">

                        <div class="mb-4">

                            <h3 class="font-bold text-slate-800 text-base leading-tight truncate">
                                {{ $item->name }}
                            </h3>

                            <p class="text-xs text-slate-400 mt-1">
                                Added {{ $item->created_at?->format('M d, Y') ?? '—' }}
                            </p>

                        </div>


                        {{-- Main stats --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">

                            <div class="rounded-xl bg-slate-50 p-3">

                                <p class="text-[10px] uppercase tracking-wide font-semibold text-slate-400">
                                    Current Stock
                                </p>

                                <p class="mt-1 text-lg font-bold text-slate-800">
                                    {{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}
                                    <span class="text-xs font-medium text-slate-400">
                                        {{ $item->unit }}
                                    </span>
                                </p>

                            </div>


                            <div class="rounded-xl bg-slate-50 p-3">

                                <p class="text-[10px] uppercase tracking-wide font-semibold text-slate-400">
                                    Unit Price
                                </p>

                                <p class="mt-1 text-lg font-bold text-slate-800">
                                    ₱{{ number_format($item->price, 2) }}
                                </p>

                            </div>

                        </div>


                        {{-- Product details --}}
                        <div class="space-y-2.5 text-xs">

                            <div class="flex items-center justify-between">

                                <span class="text-slate-400 flex items-center gap-2">
                                    <i class="fa-solid fa-arrow-down-1-9 w-3"></i>
                                    Reorder Level
                                </span>

                                <span class="font-semibold text-slate-700">
                                    {{ rtrim(rtrim(number_format($item->reorder_level, 2), '0'), '.') }}
                                    {{ $item->unit }}
                                </span>

                            </div>


                            <div class="flex items-center justify-between">

                                <span class="text-slate-400 flex items-center gap-2">
                                    <i class="fa-regular fa-calendar w-3"></i>
                                    Expiration
                                </span>

                                <span class="font-semibold
                                    @if($isExpired)
                                        text-red-600
                                    @elseif($isExpiring)
                                        text-amber-600
                                    @else
                                        text-slate-700
                                    @endif
                                ">

                                    @if($isExpired)

                                        Expired

                                    @elseif($isExpiring)

                                        {{ $item->expiration_date->format('M d, Y') }}

                                    @elseif($item->expiration_date)

                                        {{ $item->expiration_date->format('M d, Y') }}

                                    @else

                                        No expiration

                                    @endif

                                </span>

                            </div>

                        </div>


                        {{-- Expiration warning --}}
                        @if($isExpired)

                            <div class="mt-4 flex items-center gap-2 rounded-xl bg-red-50 border border-red-100 px-3 py-2.5 text-red-700">

                                <i class="fa-solid fa-circle-exclamation text-xs"></i>

                                <span class="text-[11px] font-semibold">
                                    This product has expired.
                                </span>

                            </div>

                        @elseif($isExpiring)

                            <div class="mt-4 flex items-center gap-2 rounded-xl bg-amber-50 border border-amber-100 px-3 py-2.5 text-amber-700">

                                <i class="fa-solid fa-clock text-xs"></i>

                                <span class="text-[11px] font-semibold">
                                    Expires in {{ $daysUntilExpiration }} {{ Str::plural('day', $daysUntilExpiration) }}.
                                </span>

                            </div>

                        @endif


                        {{-- Footer --}}
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">

                            <span class="text-[10px] text-slate-400">
                                Inventory item
                            </span>

                            <button
                                type="button"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition"
                            >
                                View details
                                <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </button>

                        </div>

                    </div>

                </article>

            @endforeach

        </section>

    @else

        {{-- =====================================================
            EMPTY STATE
        ====================================================== --}}
        <section class="bg-white border border-dashed border-slate-200 rounded-3xl p-10 sm:p-14 text-center">

            <div class="mx-auto w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center">

                <i class="fa-solid fa-box-open text-3xl"></i>

            </div>

            <h3 class="mt-5 text-lg font-bold text-slate-800">
                No products found
            </h3>

            <p class="mt-2 text-sm text-slate-400 max-w-md mx-auto">
                @if(request()->hasAny(['search', 'type']))
                    Try adjusting your search or filters.
                @else
                    Your inventory is currently empty. Add your first farm product to get started.
                @endif
            </p>

            @if(request()->hasAny(['search', 'type']))

                <a
                    href="{{ route('inventory.index') }}"
                    class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition"
                >
                    Clear filters
                </a>

            @else

                <button
                    type="button"
                    onclick="document.getElementById('addProductModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition"
                >
                    <i class="fa-solid fa-plus"></i>
                    Add First Product
                </button>

            @endif

        </section>

    @endif

</main>


{{-- =============================================================
    ADD PRODUCT MODAL
============================================================== --}}
<div
    id="addProductModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="addProductTitle"
    role="dialog"
    aria-modal="true"
>

    {{-- Backdrop --}}
    <div
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="document.getElementById('addProductModal').classList.add('hidden')"
    ></div>


    {{-- Modal --}}
    <div class="relative min-h-screen flex items-center justify-center p-4">

        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden">

            {{-- Modal header --}}
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-box-circle-plus"></i>
                    </div>

                    <div>

                        <h2
                            id="addProductTitle"
                            class="text-base font-bold text-slate-800"
                        >
                            Add New Product
                        </h2>

                        <p class="text-xs text-slate-400 mt-0.5">
                            Add a farm supply to your inventory.
                        </p>

                    </div>

                </div>


                <button
                    type="button"
                    onclick="document.getElementById('addProductModal').classList.add('hidden')"
                    class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>


            {{-- Form --}}
            <form
                action="{{ route('inventory.addProduct') }}"
                method="POST"
            >

                @csrf

                <div class="p-6">

                    {{-- Product identity --}}
                    <div class="mb-6">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                            Product Information
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Name --}}
                            <div class="sm:col-span-2">

                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Product Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Urea 46-0-0"
                                    required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                >

                            </div>


                            {{-- Type --}}
                            <div>

                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Product Type
                                </label>

                                <select
                                    name="type"
                                    required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                >

                                    <option value="Fertilizer">
                                        Fertilizer
                                    </option>

                                    <option value="Pesticide">
                                        Pesticide
                                    </option>

                                </select>

                            </div>


                            {{-- Unit --}}
                            <div>

                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Unit
                                </label>

                                <input
                                    type="text"
                                    name="unit"
                                    placeholder="e.g. bags, liters, kg"
                                    required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- Stock --}}
                    <div class="mb-6">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                            Stock & Pricing
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                            {{-- Quantity --}}
                            <div>

                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Initial Quantity
                                </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    min="0"
                                    step="0.01"
                                    placeholder="0"
                                    required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                >

                            </div>


                            {{-- Price --}}
                            <div>

                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Unit Price
                                </label>

                                <div class="relative">

                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 text-sm">
                                        ₱
                                    </span>

                                    <input
                                        type="number"
                                        name="price"
                                        min="0"
                                        step="0.01"
                                        placeholder="0.00"
                                        required
                                        class="w-full pl-9 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >

                                </div>

                            </div>


                            {{-- Reorder --}}
                            <div>

                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Reorder Level
                                </label>

                                <input
                                    type="number"
                                    name="reorder_level"
                                    min="0"
                                    step="0.01"
                                    placeholder="10"
                                    required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- Expiration --}}
                    <div>

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                            Expiration
                        </p>

                        <div>

                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Expiration Date
                                <span class="font-normal text-slate-400">
                                    (optional)
                                </span>
                            </label>

                            <input
                                type="date"
                                name="expiration_date"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            >

                            <p class="mt-1.5 text-[11px] text-slate-400">
                                Leave blank if the product does not have an expiration date.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">

                    <button
                        type="button"
                        onclick="document.getElementById('addProductModal').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-semibold hover:bg-slate-100 transition"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Add Product
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
