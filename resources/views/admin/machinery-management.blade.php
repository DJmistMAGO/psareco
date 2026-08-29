@extends('layouts.app')

@section('title', 'Machinery Management - PSARECO')

@section('content')

@php
    // Dummy Data Fallbacks (if variables are not passed from Controller)
    $totalMachinery = $totalMachinery ?? ($machineries->count() ?? 12);
    $availableCount = $availableCount ?? 7;
    $inUseCount = $inUseCount ?? 3;
    $maintenanceCount = $maintenanceCount ?? 2;
    $overdueCount = $overdueCount ?? 1;

    // Dummy route fallbacks
    $storeRoute = Route::has('machinery.store') ? route('machinery.store') : '#';
    $indexRoute = Route::has('machinery.index') ? route('machinery.index') : '#';
@endphp

<div
    x-data="{
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
        },
    }"
>

<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">

    <x-dashboard-header />

    <x-page-header
        eyebrow="PSARECO Machinery Management"
        title="Machinery Management"
        description="Manage machinery inventory, registration, and machinery details."
        icon="fa-solid fa-tractor"
    >
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
    </x-page-header>

    {{-- Overdue Equipment Alert Card --}}
    {{-- @if($overdueCount > 0)
        <div class="bg-red-50/90 rounded-2xl shadow-sm border border-red-200 overflow-hidden mb-6 print:hidden">
            <div class="bg-red-600 text-white px-5 py-3 flex items-center justify-between text-sm font-bold">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> Overdue Equipment Alert
                </div>
                <span class="bg-red-700 text-white text-xs px-2.5 py-0.5 rounded-full font-semibold">
                    {{ $overdueCount }} Overdue
                </span>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-red-100/60 text-red-950 uppercase text-[10px] tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Machine</th>
                            <th class="py-2.5 px-4">Farmer</th>
                            <th class="py-2.5 px-4">Start Date</th>
                            <th class="py-2.5 px-4">Return Date</th>
                            <th class="py-2.5 px-4">Overdue Days</th>
                            <th class="py-2.5 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-100 text-slate-700">
                        <tr>
                            <td class="py-3 px-4 font-semibold">John Deere Tractor (TR-001)</td>
                            <td class="py-3 px-4">Juan Dela Cruz</td>
                            <td class="py-3 px-4">Aug 10, 2026</td>
                            <td class="py-3 px-4 text-red-600 font-semibold">Aug 15, 2026</td>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-bold text-[10px]">14 Days</span></td>
                            <td class="py-3 px-4 text-right">
                                <button type="button" class="px-3 py-1 bg-red-600 text-white text-[11px] font-semibold rounded-lg hover:bg-red-700 transition">
                                    Notify Farmer
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif --}}

    {{-- Top Statistics Cards --}}
    {{-- <section class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 mb-6">
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Total Machinery</p>
                    <p class="mt-1 text-2xl font-bold text-slate-800">{{ $totalMachinery }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-tractor text-slate-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Available</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $availableCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">In Use</p>
                    <p class="mt-1 text-2xl font-bold text-amber-600">{{ $inUseCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class="fa-solid fa-key text-amber-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Maintenance</p>
                    <p class="mt-1 text-2xl font-bold text-orange-600">{{ $maintenanceCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                    <i class="fa-solid fa-wrench text-orange-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Overdue</p>
                    <p class="mt-1 text-2xl font-bold text-red-600">{{ $overdueCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-red-500"></i>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- Search & Filter Bar --}}
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
                <i class="fa-solid fa-filter text-xs"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ $indexRoute }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">
                    <i class="fa-solid fa-xmark text-xs"></i> Clear
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

            {{-- Desktop Table View --}}
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

                                $deleteRoute = route('machinery.destroy', $item->id);
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

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->model ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600 font-mono text-xs">
                                    {{ $item->serial_number ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-4 text-right font-semibold text-slate-700">
                                    ₱{{ number_format($item->price ?? 0, 2) }}
                                </td>

                                <td class="px-5 py-4 text-center text-slate-700 font-semibold">
                                    {{ $item->total_units ?? 1 }}
                                </td>

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

            {{-- Mobile Stacked Cards --}}
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
        {{-- EMPTY STATE --}}
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

</main>


{{-- VIEW DETAILS MODAL --}}
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
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>


{{-- EDIT MACHINERY MODAL --}}
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
            </div>

            <form
                method="POST"
                :action="'{{ route('machinery.update', ['id' => ':id']) }}'.replace(':id', editForm.id)"
                enctype="multipart/form-data"
            >
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


{{-- ADD MACHINERY MODAL --}}
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

            <form action="{{ $storeRoute }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 grid grid-cols-1 sm:grid-cols-[200px_1fr] gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Upload Photo</p>
                        <label for="image_path" class="cursor-pointer group block">
                            <div class="w-full aspect-square rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 group-hover:border-emerald-400 flex flex-col items-center justify-center overflow-hidden relative">
                                <img id="addPreview" src="#" class="hidden w-full h-full object-cover absolute inset-0">
                                <i id="addIcon" class="fa-solid fa-image text-slate-300 text-3xl mb-2"></i>
                                <span id="addLabel" class="text-[11px] font-semibold text-slate-400 px-4 text-center">Click to upload</span>
                            </div>
                        </label>
                        <input type="file" id="image_path" name="image_path" accept="image/*" class="hidden" onchange="
                            if (this.files && this.files[0]) {
                                document.getElementById('addPreview').src = URL.createObjectURL(this.files[0]);
                                document.getElementById('addPreview').classList.remove('hidden');
                                document.getElementById('addIcon').classList.add('hidden');
                                document.getElementById('addLabel').classList.add('hidden');
                            }
                        ">
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

</div>

@endsection
