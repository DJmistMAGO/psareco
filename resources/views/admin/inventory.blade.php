@extends('layouts.app')

@section('title', 'Inventory - PSARECO')

@section('content')

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
                name: item.name,
                type: item.type,
                unit: item.unit,
                quantity: item.quantity,
                price: item.price,
                reorder_level: item.reorder_level,
                expiration_date: item.expiration_date ? item.expiration_date.substring(0, 10) : '',
                image_path: item.image_path,
            };
            this.showEdit = true;
        },
    }"
>

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
                <option value="Fertilizer" @selected(request('type') === 'Fertilizer')>Fertilizers</option>
                <option value="Pesticide" @selected(request('type') === 'Pesticide')>Pesticides</option>
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

    <x-success />
    <x-errors />

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Products</h2>
            <p class="text-xs text-slate-400 mt-0.5">
                {{ $inventories->count() }} {{ Str::plural('product', $inventories->count()) }} displayed
            </p>
        </div>
    </div>

    @if($inventories->count())

        <section class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">

            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-left">
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Product</th>
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Type</th>
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Stock</th>
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Threshold</th>
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Unit Price</th>
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Expiration</th>
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Status</th>
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">

                        @foreach($inventories as $item)

                            @php
                                $isLowStock = $item->quantity <= $item->reorder_level;

                                $isExpired = false;
                                $isExpiring = false;
                                $daysUntilExpiration = null;

                                if ($item->expiration_date) {
                                    $today = now()->startOfDay();
                                    $expirationDate = \Carbon\Carbon::parse($item->expiration_date)->startOfDay();
                                    $isExpired = $expirationDate->lt($today);

                                    if (!$isExpired) {
                                        $daysUntilExpiration = $today->diffInDays($expirationDate);
                                        $isExpiring = $daysUntilExpiration <= 30;
                                    }
                                }

                                if ($item->type === 'Fertilizer') {
                                    $icon = 'fa-leaf';
                                    $iconBg = 'bg-emerald-100';
                                    $iconColor = 'text-emerald-600';
                                } else {
                                    $icon = 'fa-bug';
                                    $iconBg = 'bg-amber-100';
                                    $iconColor = 'text-amber-600';
                                }
                            @endphp

                            <tr class="hover:bg-slate-50/70 transition-colors">

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 shrink-0 rounded-lg {{ $iconBg }} {{ $iconColor }} flex items-center justify-center">
                                            <i class="fa-solid {{ $icon }} text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-800 truncate">{{ $item->name }}</p>
                                            <p class="text-[11px] text-slate-400">
                                                Added {{ $item->created_at?->format('M d, Y') ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-slate-500">
                                    {{ $item->type }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <span class="font-semibold {{ $isLowStock ? 'text-red-600' : 'text-slate-700' }}">
                                        {{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $item->unit }}</span>
                                </td>

                                <td class="px-5 py-4 text-right text-slate-500">
                                    {{ rtrim(rtrim(number_format($item->reorder_level, 2), '0'), '.') }} {{ $item->unit }}
                                </td>

                                <td class="px-5 py-4 text-right font-semibold text-slate-700">
                                    ₱{{ number_format($item->price, 2) }}
                                </td>

                                <td class="px-5 py-4">
                                    @if($isExpired)
                                        <span class="text-red-600 font-semibold">Expired</span>
                                    @elseif($item->expiration_date)
                                        <span class="{{ $isExpiring ? 'text-amber-600 font-semibold' : 'text-slate-600' }}">
                                            {{ $item->expiration_date->format('M d, Y') }}
                                        </span>
                                        @if($isExpiring)
                                            <div class="text-[11px] text-amber-500">
                                                in {{ $daysUntilExpiration }} {{ Str::plural('day', $daysUntilExpiration) }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-1 items-start">
                                        @if($isLowStock)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Low Stock
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                In Stock
                                            </span>
                                        @endif

                                        @if($isExpired)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold">
                                                Expired
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-1">

                                        <button type="button" @click="openView(@js($item))" title="View details" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-emerald-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                            <i class="fa-regular fa-eye text-xs"></i>
                                        </button>

                                        {{-- <button type="button" @click="openEdit(@js($item))" title="Edit product" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition">
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </button> --}}

                                        <x-confirm-modal
                                            title="Delete Product"
                                            :message="'Delete ' . $item->name . '? This will move it to trash — you can restore it later.'"
                                            confirmText="Delete"
                                            confirmClass="bg-red-600 hover:bg-red-700 text-white"
                                            icon="shield-alert"
                                            :action="route('inventory.deleteProduct', $item->id)"
                                            method="DELETE"
                                        >
                                            <button type="button" title="Delete product" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition">
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

            {{-- Mobile stacked rows --}}
            <div class="md:hidden divide-y divide-slate-100">

                @foreach($inventories as $item)

                    @php
                        $isLowStock = $item->quantity <= $item->reorder_level;

                        $isExpired = false;
                        $isExpiring = false;
                        $daysUntilExpiration = null;

                        if ($item->expiration_date) {
                            $today = now()->startOfDay();
                            $expirationDate = \Carbon\Carbon::parse($item->expiration_date)->startOfDay();
                            $isExpired = $expirationDate->lt($today);

                            if (!$isExpired) {
                                $daysUntilExpiration = $today->diffInDays($expirationDate);
                                $isExpiring = $daysUntilExpiration <= 30;
                            }
                        }

                        if ($item->type === 'Fertilizer') {
                            $icon = 'fa-leaf';
                            $iconBg = 'bg-emerald-100';
                            $iconColor = 'text-emerald-600';
                        } else {
                            $icon = 'fa-bug';
                            $iconBg = 'bg-amber-100';
                            $iconColor = 'text-amber-600';
                        }
                    @endphp

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 shrink-0 rounded-lg {{ $iconBg }} {{ $iconColor }} flex items-center justify-center">
                                    <i class="fa-solid {{ $icon }} text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-800 truncate">{{ $item->name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $item->type }}</p>
                                </div>
                            </div>

                            @if($isLowStock)
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold">
                                    Low
                                </span>
                            @else
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">
                                    OK
                                </span>
                            @endif
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-slate-400">Stock: </span>
                                <span class="font-semibold {{ $isLowStock ? 'text-red-600' : 'text-slate-700' }}">
                                    {{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }} {{ $item->unit }}
                                </span>
                            </div>
                            <div>
                                <span class="text-slate-400">Price: </span>
                                <span class="font-semibold text-slate-700">₱{{ number_format($item->price, 2) }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-slate-400">Expiration: </span>
                                @if($isExpired)
                                    <span class="text-red-600 font-semibold">Expired</span>
                                @elseif($item->expiration_date)
                                    <span class="{{ $isExpiring ? 'text-amber-600 font-semibold' : 'text-slate-600' }}">
                                        {{ $item->expiration_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-end gap-1">

                            <button type="button" @click="openView(@js($item))" title="View details" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>

                            {{-- <button type="button" @click="openEdit(@js($item))" title="Edit product" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition">
                                <i class="fa-regular fa-pen-to-square text-xs"></i>
                            </button> --}}

                            <x-confirm-modal
                                title="Delete Product"
                                :message="'Delete ' . $item->name . '? This will move it to trash — you can restore it later.'"
                                confirmText="Delete"
                                confirmClass="bg-red-600 hover:bg-red-700 text-white"
                                icon="shield-alert"
                                :action="route('inventory.deleteProduct', $item->id)"
                                method="DELETE"
                            >
                                <button type="button" title="Delete product" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </x-confirm-modal>

                        </div>
                    </div>

                @endforeach

            </div>

        </section>

        @if($inventories->hasPages())
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">

                <p class="text-xs text-slate-400 order-2 sm:order-1">
                    Showing {{ $inventories->firstItem() }}–{{ $inventories->lastItem() }}
                    of {{ $inventories->total() }} {{ Str::plural('product', $inventories->total()) }}
                </p>

                <div class="order-1 sm:order-2">
                    {{ $inventories->onEachSide(1)->links() }}
                </div>

            </div>
        @endif

    @else
        {{-- EMPTY STATE --}}
        <section class="bg-white border border-dashed border-slate-200 rounded-3xl p-10 sm:p-14 text-center">
            <div class="mx-auto w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-box-open text-3xl"></i>
            </div>
            <h3 class="mt-5 text-lg font-bold text-slate-800">No products found</h3>
            <p class="mt-2 text-sm text-slate-400 max-w-md mx-auto">
                @if(request()->hasAny(['search', 'type']))
                    Try adjusting your search or filters.
                @else
                    Your inventory is currently empty. Add your first farm product to get started.
                @endif
            </p>

            @if(request()->hasAny(['search', 'type']))
                <a href="{{ route('inventory.index') }}" class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition">
                    Clear filters
                </a>
            @else
                <button type="button" onclick="document.getElementById('addProductModal').classList.remove('hidden')" class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-plus"></i>
                    Add First Product
                </button>
            @endif
        </section>

    @endif

</main>


{{-- view modal --}}
<div x-show="showView" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showView = false"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden" x-show="showView" x-transition>
            <template x-if="selected">
                <div>
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center" :class="selected.type === 'Fertilizer' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'" >
                                <i class="fa-solid" :class="selected.type === 'Fertilizer' ? 'fa-leaf' : 'fa-bug'" ></i>
                            </div>

                            <div class="min-w-0">
                                <h2 class="text-base font-bold text-slate-800 truncate" x-text="selected.name" ></h2>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Product Details
                                </p>
                            </div>
                        </div>
                    </div>


                    <div class="p-6 grid grid-cols-1 sm:grid-cols-[220px_1fr] gap-6">

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                Product Image
                            </p>

                            <div class="w-full aspect-square rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center overflow-hidden">
                                <template x-if="selected.image_path">
                                    <img :src="`/storage/${selected.image_path}`" alt="Product image" class="w-full h-full object-cover" >
                                </template>

                                <template x-if="!selected.image_path">
                                    <div class="flex flex-col items-center text-slate-300">
                                        <i class="fa-solid fa-image text-3xl mb-1.5"></i>
                                        <span class="text-[11px] font-semibold text-slate-400 px-4 text-center">No image uploaded</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div>

                            <div class="mb-6">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                    Product Information
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                            Product Name
                                        </label>

                                        <div class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700">
                                            <span x-text="selected.name"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                            Product Type
                                        </label>

                                        <div class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 flex items-center gap-2">
                                            <i class="fa-solid text-xs" :class="selected.type === 'Fertilizer' ? 'fa-leaf text-emerald-600' : 'fa-bug text-amber-600'"></i>

                                            <span x-text="selected.type"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                            Unit
                                        </label>

                                        <div class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700">
                                            <span x-text="selected.unit"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="mb-6">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                    Stock & Pricing
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                            Current Quantity
                                        </label>

                                        <div class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200">
                                            <div class="flex items-baseline gap-1.5">
                                                <span class="text-sm font-bold" :class="Number(selected.quantity) <= Number(selected.reorder_level)
                                                        ? 'text-red-600'
                                                        : 'text-slate-700'"
                                                    x-text="selected.quantity"
                                                ></span>

                                                <span class="text-xs font-medium text-slate-400" x-text="selected.unit"></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                            Unit Price
                                        </label>

                                        <div class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200">
                                            <div class="flex items-baseline gap-0.5">
                                                <span class="text-sm font-bold text-slate-700">
                                                    ₱
                                                </span>

                                                <span class="text-sm font-bold text-slate-700" x-text="Number(selected.price).toFixed(2)" ></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                            Reorder Level
                                        </label>

                                        <div class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200">
                                            <div class="flex items-baseline gap-1.5">
                                                <span class="text-sm font-bold text-slate-700" x-text="selected.reorder_level" ></span>

                                                <span class="text-xs font-medium text-slate-400" x-text="selected.unit" ></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="mb-6">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                    Expiration
                                </p>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Expiration Date
                                    </label>

                                    <div class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                                        <template x-if="selected.expiration_date">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-regular fa-calendar text-slate-400"></i>

                                                <span class="text-slate-700 font-medium" x-text="selected.expiration_date.substring(0, 10)" ></span>
                                            </div>
                                        </template>

                                        <template x-if="!selected.expiration_date">
                                            <div class="flex items-center gap-2 text-slate-400">
                                                <i class="fa-regular fa-calendar-xmark"></i>
                                                <span>No expiration date</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>


                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                    Inventory Status
                                </p>
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center" :class="Number(selected.quantity) <= Number(selected.reorder_level)
                                                    ? 'bg-red-50 text-red-600'
                                                    : 'bg-emerald-50 text-emerald-600'"
                                            >
                                                <i class="fa-solid" :class="Number(selected.quantity) <= Number(selected.reorder_level)
                                                        ? 'fa-triangle-exclamation'
                                                        : 'fa-circle-check'"></i>
                                            </div>

                                            <div>
                                                <p class="text-xs font-semibold text-slate-500">
                                                    Stock Level
                                                </p>

                                                <p class="text-sm font-bold" :class="Number(selected.quantity) <= Number(selected.reorder_level)
                                                        ? 'text-red-600'
                                                        : 'text-emerald-700'"
                                                    x-text="Number(selected.quantity) <= Number(selected.reorder_level)
                                                        ? 'Low Stock'
                                                        : 'In Stock'"
                                                ></p>
                                            </div>
                                        </div>


                                        {{-- <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-xl flex items-center justify-center"
                                                :class="!selected.expiration_date
                                                    ? 'bg-slate-100 text-slate-400'
                                                    : 'bg-emerald-50 text-emerald-600'"
                                            >
                                                <i
                                                    class="fa-solid"
                                                    :class="!selected.expiration_date
                                                        ? 'fa-calendar'
                                                        : 'fa-calendar-check'"
                                                ></i>
                                            </div>

                                            <div>
                                                <p class="text-xs font-semibold text-slate-500">
                                                    Expiration
                                                </p>

                                                <p
                                                    class="text-sm font-bold"
                                                    :class="selected.expiration_date
                                                        ? 'text-emerald-700'
                                                        : 'text-slate-500'"
                                                    x-text="selected.expiration_date
                                                        ? 'Expiration date set'
                                                        : 'No expiration'"
                                                ></p>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">

                        <button type="button" @click="showView = false" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-semibold hover:bg-slate-100 transition" >
                            Close
                        </button>

                        <button type="button" @click="showView = false; openEdit(selected)" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition" >
                            <i class="fa-regular fa-pen-to-square"></i>
                            Edit Product
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

{{-- edit modal --}}
<div x-show="showEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog" x-data="{ editImagePreview: null }" @edit-opened.window="editImagePreview = null">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showEdit = false"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden" x-show="showEdit" x-transition>

            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Edit Product</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Update this product's details.</p>
                    </div>
                </div>
            </div>

            <form method="POST" :action="`{{ url('inventory') }}/${editForm.id}`" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 grid grid-cols-1 sm:grid-cols-[220px_1fr] gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Product Image</p>

                        <label for="edit_image_path" class="cursor-pointer group block">
                            <div class="w-full aspect-square rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 group-hover:border-emerald-400 flex flex-col items-center justify-center overflow-hidden transition relative">
                                <img
                                    x-show="editImagePreview || editForm.image_path"
                                    :src="editImagePreview ? editImagePreview : `/storage/${editForm.image_path}`"
                                    alt="Preview"
                                    class="w-full h-full object-cover absolute inset-0"
                                >
                                <template x-if="!editImagePreview && !editForm.image_path">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-image text-slate-300 text-3xl mb-2"></i>
                                        <span class="text-[11px] font-semibold text-slate-400 group-hover:text-emerald-500 px-4 text-center">
                                            Click to upload image
                                        </span>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/40 flex items-center justify-center transition">
                                    <span class="opacity-0 group-hover:opacity-100 text-white text-xs font-semibold transition">
                                        <i class="fa-solid fa-pen"></i> Change
                                    </span>
                                </div>
                            </div>
                        </label>
                        <input type="file" id="edit_image_path" name="image_path" accept="image/*" class="hidden"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => editImagePreview = e.target.result;
                                    reader.readAsDataURL(file);
                                } else {
                                    editImagePreview = null;
                                }
                            "
                        >
                        <p class="mt-2 text-[11px] text-slate-400 text-center">Leave empty to keep current image.</p>
                    </div>

                    <div>

                        <div class="mb-6">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Product Information</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Product Name</label>
                                    <input type="text" name="name" x-model="editForm.name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Product Type</label>
                                    <select name="type" x-model="editForm.type" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                        <option value="Fertilizer">Fertilizer</option>
                                        <option value="Pesticide">Pesticide</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Unit</label>
                                    <input type="text" name="unit" x-model="editForm.unit" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Stock & Pricing</p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quantity</label>
                                    <input type="number" name="quantity" x-model="editForm.quantity" min="0" step="0.01" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Unit Price</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 text-sm">₱</span>
                                        <input type="number" name="price" x-model="editForm.price" min="0" step="0.01" required class="w-full pl-9 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Reorder Level</label>
                                    <input type="number" name="reorder_level" x-model="editForm.reorder_level" min="0" step="0.01" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Expiration</p>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Expiration Date
                                    <span class="font-normal text-slate-400">(optional)</span>
                                </label>
                                <input type="date" name="expiration_date" min="{{ now()->format('Y-m-d') }}" x-model="editForm.expiration_date" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                    <button type="button" @click="showEdit = false" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition">
                        <i class="fa-solid fa-check"></i>
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- add product modal --}}
<div id="addProductModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="addProductTitle" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('addProductModal').classList.add('hidden')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-jar"></i>
                    </div>
                    <div>
                        <h2 id="addProductTitle" class="text-base font-bold text-slate-800">Add New Product</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Add a farm supply to your inventory.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('addProductModal').classList.add('hidden')" class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('inventory.addProduct') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
                @csrf
                <div class="p-6 grid grid-cols-1 sm:grid-cols-[220px_1fr] gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Product Image</p>
                        <label for="image_path" class="cursor-pointer group block">
                            <div id="imagePreviewWrapper" class="w-full aspect-square rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 group-hover:border-emerald-400 flex flex-col items-center justify-center overflow-hidden transition relative">
                                <img id="imagePreview" src="" alt="Preview" class="hidden w-full h-full object-cover absolute inset-0">
                                <i id="imagePreviewIcon" class="fa-solid fa-image text-slate-300 text-3xl mb-2"></i>
                                <span id="imagePreviewLabel" class="text-[11px] font-semibold text-slate-400 group-hover:text-emerald-500 px-4 text-center">
                                    Click to upload image
                                </span>
                                <div id="imagePreviewOverlay" class="hidden absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/40 items-center justify-center transition">
                                    <span class="opacity-0 group-hover:opacity-100 text-white text-xs font-semibold transition">
                                        <i class="fa-solid fa-pen"></i> Change
                                    </span>
                                </div>
                            </div>
                        </label>
                        <input type="file" id="image_path" name="image_path" accept="image/*" class="hidden" onchange="previewProductImage(this)">
                        <p id="imageFileName" class="mt-2 text-[11px] text-slate-400 text-center truncate">PNG, JPG up to 2MB. Optional.</p>
                    </div>

                    <div>
                        <div class="mb-6">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Product Information</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Product Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Urea 46-0-0" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Product Type</label>
                                    <select name="type" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                        <option value="Fertilizer">Fertilizer</option>
                                        <option value="Pesticide">Pesticide</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Unit</label>
                                    <input type="text" name="unit" placeholder="e.g. bags, liters, kg" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Stock & Pricing</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Initial Quantity</label>
                                    <input type="number" name="quantity" min="0" step="0.01" placeholder="0" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Unit Price</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 text-sm">₱</span>
                                        <input type="number" name="price" min="0" step="0.01" placeholder="0.00" required class="w-full pl-9 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Reorder Level</label>
                                    <input type="number" name="reorder_level" min="0" step="0.01" placeholder="10" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Expiration</p>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Expiration Date
                                    <span class="font-normal text-slate-400">(optional)</span>
                                </label>
                                <input type="date" name="expiration_date" min="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <p class="mt-1.5 text-[11px] text-slate-400">Leave blank if the product does not have an expiration date.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                    <button type="button" onclick="document.getElementById('addProductModal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">
                        Cancel
                    </button>
                    <button type="submit" id="addProductSubmitBtn" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition disabled:opacity-70 disabled:cursor-not-allowed">
                        <i id="addProductBtnIcon" class="fa-solid fa-plus"></i>
                        <span id="addProductBtnText">Add Product</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
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
            fileName.textContent = 'PNG, JPG up to 2MB. Optional.';
        }
    }
</script>

<script>
    document.getElementById('addProductForm').addEventListener('submit', function (e) {
        const btn = document.getElementById('addProductSubmitBtn');
        const icon = document.getElementById('addProductBtnIcon');
        const text = document.getElementById('addProductBtnText');

        if (btn.disabled) {
            e.preventDefault();
            return;
        }

        btn.disabled = true;
        icon.classList.remove('fa-plus');
        icon.classList.add('fa-spinner', 'fa-spin');
        text.textContent = 'Adding...';
    });
</script>
@endpush
