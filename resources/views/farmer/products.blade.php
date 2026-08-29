@extends('layouts.app')

@section('title', 'Products for Sale - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <x-page-header eyebrow="PSARECO Products" title="Products Available" description="List of products available for sale"
            icon="fa-solid fa-jar" />

        {{-- Search & Filter Bar --}}
        <section class="bg-white border border-slate-100 rounded-2xl shadow-sm p-4 mb-6">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-col lg:flex-row gap-3">

                {{-- Search Input --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search product name, type..."
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                {{-- Type Filter --}}
                <select name="type" class="lg:w-48 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
    <option value="all">All Types</option>
    <option value="Fertilizer" @selected(request('type') == 'Fertilizer')>Fertilizer</option>
    <option value="Pesticide" @selected(request('type') == 'Pesticide')>Pesticide</option>
</select>

                {{-- Stock Availability Filter --}}
                <select name="availability"
                    class="lg:w-48 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="all">All Availability</option>
                    <option value="in_stock" @selected(request('availability') === 'in_stock')>In Stock</option>
                    <option value="out_of_stock" @selected(request('availability') === 'out_of_stock')>Out of Stock</option>
                </select>

                {{-- Submit Button --}}
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold transition">
                    <i class="fa-solid fa-filter text-xs"></i> Filter
                </button>

                {{-- Clear Button --}}
                @if (request()->hasAny(['search', 'type', 'availability']))
                    <a href="{{ url()->current() }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">
                        <i class="fa-solid fa-xmark text-xs"></i> Clear
                    </a>
                @endif
            </form>
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($products ?? [] as $product)
                @php
                    $rate = (float) ($product['price'] ?? ($product->price ?? 0));
                    $name = $product['name'] ?? ($product->name ?? 'Unnamed Product');
                    $type = $product['type'] ?? ($product->type ?? 'N/A');
                    $unit = $product['unit'] ?? ($product->unit ?? 'unit');
                    $totalUnits = $product['totalUnits'] ?? ($product->total_units ?? 1);
                    $image =
                        $product['image'] ??
                        ($product->image ?? 'https://placehold.co/300x200/f1f5f9/334155?text=' . urlencode($name));
                @endphp

                <div
                    class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="h-36 w-full bg-slate-100 relative overflow-hidden">
                            <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-slate-800 text-xs line-clamp-1">{{ $name }}</h4>
                            <p class="text-[11px] text-slate-400 mb-2">{{ $type }}</p>
                            <div class="text-sm font-extrabold text-emerald-700 mb-3">
                                ₱{{ number_format($rate, 2) }} <span class="text-[10px] font-normal text-slate-500">/ per
                                    {{ $unit }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 pb-4 pt-0">
                        <div
                            class="flex items-center justify-between text-[11px] bg-slate-50 p-2 rounded-xl text-slate-600 border border-slate-100">
                            <span>Total Available: <strong class="text-slate-800">{{ $totalUnits }}</strong></span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-slate-400 py-10 text-xs">
                    No products available.
                </div>
            @endforelse
        </div>
        </div>
    </main>
@endsection
