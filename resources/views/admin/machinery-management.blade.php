@extends('layouts.app')
@section('title', 'Machinery Management - PSARECO')
@section('content')
@php
    $totalMachinery = $totalMachinery ?? ($machineries->count() ?? 12);
    $availableCount = $availableCount ?? 7;
    $inUseCount = $inUseCount ?? 3;
    $maintenanceCount = $maintenanceCount ?? 2;
    $overdueCount = $overdueCount ?? 1;
    $storeRoute = Route::has('machinery.store') ? route('machinery.store') : '#';
    $indexRoute = Route::has('machinery.index') ? route('machinery.index') : '#';
@endphp
<div x-data="{
    showView: false,
    showEdit: false,
    selected: null,
    editForm: {},
    openView(item) {
        this.selected = item;
        this.showView = true;
    },
    openEdit(item) {
        this.editForm = {
            id: item.id,
            machinery_name: item.machinery_name,
            model: item.model,
            serial_number: item.serial_number,
            price: item.price,
            status: item.status,
            total_units: item.total_units || 1,
            image_path: item.image_path,
        };
        this.showEdit = true;
    }
}">
<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
    <x-dashboard-header />
    <x-page-header
        eyebrow="PSARECO Machinery Management"
        title="Machinery Management"
        description="Browse agricultural machinery available for rental"
        icon="fa-solid fa-tractor"
    >
        @role('officer')
            <x-slot:actions>
                <button
                    type="button"
                    onclick="document.getElementById('addMachineryModal').classList.remove('hidden')"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-700 text-sm font-bold shadow-sm hover:bg-emerald-50 transition"
                >
                    <i class="fa-solid fa-plus"></i>
                    Add Machinery
                </button>
            </x-slot:actions>
        @endrole
    </x-page-header>
    @role('officer')
        <section class="bg-white border border-slate-100 rounded-2xl shadow-sm p-4 mb-6">
            <form action="{{ $indexRoute }}" method="GET" class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search machinery, model, serial..."
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    >
                </div>
                <select name="status" class="lg:w-48 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="all">All Statuses</option>
                    <option value="Available" @selected(request('status') === 'Available')>Available</option>
                    <option value="Reserved" @selected(request('status') === 'Reserved')>Reserved</option>
                    <option value="In Use" @selected(request('status') === 'In Use')>In Use</option>
                    <option value="Under Maintenance" @selected(request('status') === 'Under Maintenance')>Under Maintenance</option>
                    <option value="Unavailable" @selected(request('status') === 'Unavailable')>Unavailable</option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold transition">
                    <i class="fa-solid fa-filter text-xs"></i>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ $indexRoute }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        Clear
                    </a>
                @endif
            </form>
        </section>
        <x-success />
        <x-errors />
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Machinery Fleet</h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ $machineries->count() }} {{ Str::plural('machine', $machineries->count()) }} displayed
                </p>
            </div>
        </div>
        @if($machineries->count())
            <section class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-left">
                                <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Machine</th>
                                <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Model</th>
                                <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Serial No.</th>
                                <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Rent / Hour</th>
                                <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-center">Units</th>
                                <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Status</th>
                                <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($machineries as $item)
                                @php
                                    $statusClasses = match ($item->status) {
                                        'Available' => 'bg-emerald-50 text-emerald-700',
                                        'Reserved' => 'bg-blue-50 text-blue-700',
                                        'In Use' => 'bg-amber-50 text-amber-700',
                                        'Under Maintenance' => 'bg-orange-50 text-orange-700',
                                        'Unavailable' => 'bg-red-50 text-red-700',
                                        default => 'bg-slate-50 text-slate-700',
                                    };
                                    $deleteRoute = Route::has('machinery.destroy')
                                        ? route('machinery.destroy', $item->id)
                                        : (Route::has('machinery.delete') ? route('machinery.delete', $item->id) : '#');
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 shrink-0 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center overflow-hidden">
                                                @if($item->image_path)
                                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="fa-solid fa-tractor text-sm"></i>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-slate-800 truncate">{{ $item->machinery_name }}</p>
                                                <p class="text-[11px] text-slate-400">Added {{ $item->created_at?->format('M d, Y') ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-600">{{ $item->model ?? 'N/A' }}</td>
                                    <td class="px-5 py-4 text-slate-600 font-mono text-xs">{{ $item->serial_number ?? 'N/A' }}</td>
                                    <td class="px-5 py-4 text-right font-semibold text-slate-700">₱{{ number_format($item->price ?? 0, 2) }}</td>
                                    <td class="px-5 py-4 text-center text-slate-700 font-semibold">{{ $item->total_units ?? 1 }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $statusClasses }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <button type="button" @click="openView(@js($item))" title="View details" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 transition">
                                                <i class="fa-regular fa-eye text-xs"></i>
                                            </button>
                                            <button type="button" @click="openEdit(@js($item))" title="Edit machinery" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition">
                                                <i class="fa-regular fa-pen-to-square text-xs"></i>
                                            </button>
                                            <x-confirm-modal
                                                title="Delete Machinery"
                                                :message="'Delete ' . $item->machinery_name . '? This will move it to trash — you can restore it later.'"
                                                confirmText="Delete"
                                                confirmClass="bg-red-600 hover:bg-red-700 text-white"
                                                icon="shield-alert"
                                                :action="$deleteRoute"
                                                method="DELETE"
                                            >
                                                <button type="button" title="Delete machinery" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition">
                                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                                </button>
                                            </x-confirm-modal>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden divide-y divide-slate-100">
                    @foreach($machineries as $item)
                        @php
                            $statusClasses = match ($item->status) {
                                'Available' => 'bg-emerald-50 text-emerald-700',
                                'Reserved' => 'bg-blue-50 text-blue-700',
                                'In Use' => 'bg-amber-50 text-amber-700',
                                'Under Maintenance' => 'bg-orange-50 text-orange-700',
                                'Unavailable' => 'bg-red-50 text-red-700',
                                default => 'bg-slate-50 text-slate-700',
                            };
                            $deleteRoute = Route::has('machinery.destroy')
                                ? route('machinery.destroy', $item->id)
                                : (Route::has('machinery.delete') ? route('machinery.delete', $item->id) : '#');
                        @endphp
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 shrink-0 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center overflow-hidden">
                                        @if($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-tractor text-sm"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-800 truncate">{{ $item->machinery_name }}</p>
                                        <p class="text-[11px] text-slate-400">{{ $item->model ?? 'Model N/A' }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $statusClasses }}">
                                    {{ $item->status }}
                                </span>
                            </div>
                            <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-slate-400">Serial: </span>
                                    <span class="font-semibold text-slate-700">{{ $item->serial_number ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400">Price/Hr: </span>
                                    <span class="font-semibold text-slate-700">₱{{ number_format($item->price ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-end gap-1">
                                <button type="button" @click="openView(@js($item))" title="View details" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                                <button type="button" @click="openEdit(@js($item))" title="Edit machinery" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <x-confirm-modal
                                    title="Delete Machinery"
                                    :message="'Delete ' . $item->machinery_name . '? This will move it to trash — you can restore it later.'"
                                    confirmText="Delete"
                                    confirmClass="bg-red-600 hover:bg-red-700 text-white"
                                    icon="shield-alert"
                                    :action="$deleteRoute"
                                    method="DELETE"
                                >
                                    <button type="button" title="Delete machinery" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </x-confirm-modal>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @if(method_exists($machineries, 'hasPages') && $machineries->hasPages())
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-slate-400 order-2 sm:order-1">
                        Showing {{ $machineries->firstItem() }}–{{ $machineries->lastItem() }}
                        of {{ $machineries->total() }} {{ Str::plural('machine', $machineries->total()) }}
                    </p>
                    <div class="order-1 sm:order-2">
                        {{ $machineries->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
        @else
            <section class="bg-white border border-dashed border-slate-200 rounded-3xl p-10 sm:p-14 text-center">
                <div class="mx-auto w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-tractor text-3xl"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-slate-800">No machinery found</h3>
                <p class="mt-2 text-sm text-slate-400 max-w-md mx-auto">
                    @if(request()->hasAny(['search', 'status']))
                        Try adjusting your search or filter options.
                    @else
                        Your machinery fleet is currently empty. Add your first farm machine to get started.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ $indexRoute }}" class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition">
                        Clear filters
                    </a>
                @else
                    <button type="button" onclick="document.getElementById('addMachineryModal').classList.remove('hidden')" class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition">
                        <i class="fa-solid fa-plus"></i>
                        Add First Machinery
                    </button>
                @endif
            </section>
        @endif
    @endrole
    @role('farmer')
        <div
            x-data="{
                search: '',
                status: 'All',
                sort: 'default',
                machineries: @js($machineries->values()),
                get filteredMachineries() {
                    let items = [...this.machineries];
                    if (this.search.trim() !== '') {
                        const search = this.search.toLowerCase();
                        items = items.filter(item =>
                            (item.machinery_name || '').toLowerCase().includes(search) ||
                            (item.model || '').toLowerCase().includes(search) ||
                            (item.serial_number || '').toLowerCase().includes(search)
                        );
                    }
                    if (this.status !== 'All') {
                        items = items.filter(item => item.status === this.status);
                    }
                    if (this.sort === 'price_low') {
                        items.sort((a, b) => Number(a.price || 0) - Number(b.price || 0));
                    }
                    if (this.sort === 'price_high') {
                        items.sort((a, b) => Number(b.price || 0) - Number(a.price || 0));
                    }
                    if (this.sort === 'name') {
                        items.sort((a, b) => (a.machinery_name || '').localeCompare(b.machinery_name || ''));
                    }
                    return items;
                }
            }"
        >
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Available Machinery</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Browse agricultural machinery available for rental.</p>
                </div>
                <div class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <i class="fa-solid fa-tractor text-emerald-600"></i>
                    <span><span x-text="filteredMachineries.length"></span> machines</span>
                </div>
            </div>
            <section class="bg-white border border-slate-100 rounded-2xl shadow-sm p-4 mb-6">
                <div class="flex flex-col lg:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </div>
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search machinery, model, serial..."
                            class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        >
                    </div>
                    <select
                        x-model="status"
                        class="lg:w-48 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                        <option value="All">All Statuses</option>
                        <option value="Available">Available</option>
                        <option value="Reserved">Reserved</option>
                        <option value="In Use">In Use</option>
                        <option value="Under Maintenance">Under Maintenance</option>
                        <option value="Unavailable">Unavailable</option>
                    </select>
                    <select
                        x-model="sort"
                        class="lg:w-48 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                        <option value="default">Sort: Default</option>
                        <option value="name">Name</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                    <button
                        type="button"
                        @click="search = ''; status = 'All'; sort = 'default'"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-sm font-semibold transition"
                    >
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        Reset
                    </button>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-xs text-slate-400">
                        Showing <span class="font-semibold text-slate-600" x-text="filteredMachineries.length"></span> machinery
                    </p>
                    <template x-if="search || status !== 'All'">
                        <span class="text-[11px] text-emerald-600 font-semibold">Filters active</span>
                    </template>
                </div>
            </section>
            <template x-if="filteredMachineries.length > 0">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    <template x-for="item in filteredMachineries" :key="item.id">
                        <article class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden hover:shadow-md hover:border-emerald-100 transition">
                            <div class="relative">
                                <div class="h-48 bg-slate-50 flex items-center justify-center overflow-hidden">
                                    <template x-if="item.image_path">
                                        <img
                                            :src="`/storage/${item.image_path}`"
                                            :alt="item.machinery_name"
                                            class="w-full h-full object-cover"
                                        >
                                    </template>
                                    <template x-if="!item.image_path">
                                        <div class="flex flex-col items-center justify-center text-slate-300">
                                            <i class="fa-solid fa-tractor text-4xl mb-2"></i>
                                            <span class="text-xs font-medium">No image available</span>
                                        </div>
                                    </template>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-[10px] font-bold shadow-sm"
                                        :class="{
                                            'bg-emerald-50 text-emerald-700': item.status === 'Available',
                                            'bg-blue-50 text-blue-700': item.status === 'Reserved',
                                            'bg-amber-50 text-amber-700': item.status === 'In Use',
                                            'bg-orange-50 text-orange-700': item.status === 'Under Maintenance',
                                            'bg-red-50 text-red-700': item.status === 'Unavailable',
                                            'bg-slate-50 text-slate-700': !['Available', 'Reserved', 'In Use', 'Under Maintenance', 'Unavailable'].includes(item.status)
                                        }"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        <span x-text="item.status"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-slate-800 truncate" x-text="item.machinery_name"></h3>
                                        <p class="text-xs text-slate-400 mt-0.5" x-text="item.model || 'Model not specified'"></p>
                                    </div>
                                    <div class="w-9 h-9 shrink-0 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                        <i class="fa-solid fa-tractor text-sm"></i>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mt-4">
                                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
                                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Rent / Hour</p>
                                        <p class="mt-1 text-sm font-bold text-emerald-700">
                                            ₱<span x-text="Number(item.price || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                                        </p>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
                                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Units</p>
                                        <p class="mt-1 text-sm font-bold text-slate-700" x-text="item.total_units || 1"></p>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2 text-slate-400 min-w-0">
                                        <i class="fa-solid fa-barcode shrink-0"></i>
                                        <span class="font-mono text-slate-500 truncate" x-text="item.serial_number || 'N/A'"></span>
                                    </div>
                                    <span x-show="item.status === 'Available'" class="text-emerald-600 font-semibold shrink-0 ml-2">Ready to rent</span>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <div x-show="item.status === 'Available'" class="flex items-center gap-2 text-xs text-emerald-700 font-semibold">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Machinery is available
                                    </div>
                                    <div x-show="item.status !== 'Available'" class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                                        <i class="fa-solid fa-circle-info"></i>
                                        Currently unavailable for rental
                                    </div>
                                </div>
                            </div>
                        </article>
                    </template>
                </div>
            </template>
            <template x-if="filteredMachineries.length === 0">
                <section class="bg-white border border-dashed border-slate-200 rounded-2xl p-10 text-center">
                    <div class="mx-auto w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-tractor text-2xl"></i>
                    </div>
                    <h3 class="mt-4 text-base font-bold text-slate-800">No machinery found</h3>
                    <p class="mt-1.5 text-sm text-slate-400">Try changing your search or filter options.</p>
                    <button
                        type="button"
                        @click="search = ''; status = 'All'; sort = 'default'"
                        class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition"
                    >
                        <i class="fa-solid fa-rotate-left"></i>
                        Reset Filters
                    </button>
                </section>
            </template>
        </div>
    @endrole
</main>
@role('officer')
<div x-show="showView" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showView = false"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden" x-show="showView" x-transition>
            <template x-if="selected">
                <div>
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 shrink-0 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-tractor"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-base font-bold text-slate-800 truncate" x-text="selected.machinery_name"></h2>
                                <p class="text-xs text-slate-400 mt-0.5">Machinery Details</p>
                            </div>
                        </div>
                        <button type="button" @click="showView = false" class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-[200px_1fr] gap-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Machinery Photo</p>
                            <div class="w-full aspect-square rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center overflow-hidden">
                                <template x-if="selected.image_path">
                                    <img :src="`/storage/${selected.image_path}`" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selected.image_path">
                                    <div class="flex flex-col items-center text-slate-300">
                                        <i class="fa-solid fa-tractor text-3xl mb-1.5"></i>
                                        <span class="text-[11px] font-semibold text-slate-400 px-4 text-center">No image</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <div class="mb-6">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Information</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Machine Name</label>
                                        <div class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700" x-text="selected.machinery_name"></div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Model</label>
                                        <div class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700" x-text="selected.model || 'N/A'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Serial Number</label>
                                        <div class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-mono text-slate-700" x-text="selected.serial_number || 'N/A'"></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Pricing & Status</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Rent / Hour</label>
                                        <div class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-bold text-slate-700">
                                            ₱<span x-text="Number(selected.price || 0).toFixed(2)"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                                        <div class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-bold text-emerald-700" x-text="selected.status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                        <button type="button" @click="showView = false" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">Close</button>
                        <button type="button" @click="showView = false; openEdit(selected)" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition">
                            <i class="fa-regular fa-pen-to-square"></i>
                            Edit
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
<div x-show="showEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog" x-data="{ editImagePreview: null }">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showEdit = false"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden" x-show="showEdit" x-transition>
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Edit Machinery</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Update machinery specifications or status.</p>
                    </div>
                </div>
                <button type="button" @click="showEdit = false" class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" :action="'{{ route('machinery.update', ['id' => ':id']) }}'.replace(':id', editForm.id)" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 grid grid-cols-1 sm:grid-cols-[200px_1fr] gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Machinery Image</p>
                        <label for="edit_image_path" class="cursor-pointer group block">
                            <div class="w-full aspect-square rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 group-hover:border-emerald-400 flex flex-col items-center justify-center overflow-hidden relative">
                                <img x-show="editImagePreview || editForm.image_path" :src="editImagePreview ? editImagePreview : `/storage/${editForm.image_path}`" class="w-full h-full object-cover absolute inset-0">
                                <template x-if="!editImagePreview && !editForm.image_path">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-image text-slate-300 text-3xl mb-2"></i>
                                        <span class="text-[11px] font-semibold text-slate-400 group-hover:text-emerald-500 px-4 text-center">Click to upload image</span>
                                    </div>
                                </template>
                            </div>
                        </label>
                        <input type="file" id="edit_image_path" name="image_path" accept="image/*" class="hidden" @change="
                            const file = $event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => editImagePreview = e.target.result;
                                reader.readAsDataURL(file);
                            }
                        ">
                    </div>
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Machinery Name</label>
                                <input type="text" name="machinery_name" x-model="editForm.machinery_name" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Model</label>
                                <input type="text" name="model" x-model="editForm.model" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Serial Number</label>
                                <input type="text" name="serial_number" x-model="editForm.serial_number" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Rent / Hour (₱)</label>
                                <input type="number" step="0.01" name="price" x-model="editForm.price" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                                <select name="status" x-model="editForm.status" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                                    <option value="Available">Available</option>
                                    <option value="Reserved">Reserved</option>
                                    <option value="In Use">In Use</option>
                                    <option value="Under Maintenance">Under Maintenance</option>
                                    <option value="Unavailable">Unavailable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showEdit = false" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="addMachineryModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('addMachineryModal').classList.add('hidden')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-tractor"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Add New Machinery</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Register a machine to the PSARECO fleet.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('addMachineryModal').classList.add('hidden')" class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ $storeRoute }}" method="POST" enctype="multipart/form-data" id="addMachineryForm">
                @csrf
                <div class="p-6 grid grid-cols-1 sm:grid-cols-[220px_1fr] gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Machinery Image</p>
                        <label for="image_path" class="cursor-pointer group block">
                            <div id="imagePreviewWrapper" class="w-full aspect-square rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 group-hover:border-emerald-400 flex flex-col items-center justify-center overflow-hidden transition relative">
                                <img id="imagePreview" src="" alt="Machinery preview" class="hidden w-full h-full object-cover absolute inset-0">
                                <i id="imagePreviewIcon" class="fa-solid fa-image text-slate-300 text-3xl mb-2"></i>
                                <span id="imagePreviewLabel" class="text-[11px] font-semibold text-slate-400 group-hover:text-emerald-500 px-4 text-center">Click to upload image</span>
                                <div id="imagePreviewOverlay" class="hidden absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/40 items-center justify-center transition">
                                    <span class="opacity-0 group-hover:opacity-100 text-white text-xs font-semibold transition">
                                        <i class="fa-solid fa-pen"></i> Change
                                    </span>
                                </div>
                            </div>
                        </label>
                        <input type="file" id="image_path" name="image_path" accept="image/*" required class="hidden" onchange="previewProductImage(this)">
                        <p id="imageFileName" class="mt-2 text-[11px] text-slate-400 text-center truncate">PNG, JPG up to 2MB. Required.</p>
                    </div>
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Machinery Name <span class="text-red-500">*</span></label>
                                <input type="text" name="machinery_name" placeholder="e.g. Kubota Tractor" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Model <span class="text-red-500">*</span></label>
                                <input type="text" name="model" placeholder="e.g. L3901" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Serial Number <span class="text-red-500">*</span></label>
                                <input type="text" name="serial_number" placeholder="e.g. TR-2026-001" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Rent / Hour (₱) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="price" placeholder="0.00" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                                    <option value="Available">Available</option>
                                    <option value="Reserved">Reserved</option>
                                    <option value="In Use">In Use</option>
                                    <option value="Under Maintenance">Under Maintenance</option>
                                    <option value="Unavailable">Unavailable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('addMachineryModal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition">Add Machinery</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endrole
</div>
@endsection
@push('scripts')
<script>
function previewProductImage(input) {
    const preview = document.getElementById('imagePreview');
    const icon = document.getElementById('imagePreviewIcon');
    const label = document.getElementById('imagePreviewLabel');
    const overlay = document.getElementById('imagePreviewOverlay');
    const fileName = document.getElementById('imageFileName');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
            label.classList.add('hidden');
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        };
        reader.readAsDataURL(file);
        fileName.textContent = file.name;
    } else {
        preview.src = '';
        preview.classList.add('hidden');
        icon.classList.remove('hidden');
        label.classList.remove('hidden');
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        fileName.textContent = 'PNG, JPG up to 2MB. Required.';
    }
}
</script>
@endpush
