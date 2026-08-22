@extends('layouts.app')

@section('title', 'Inventory Trash - PSARECO')

@section('content')

<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">

    <x-dashboard-header />

    <x-page-header
        eyebrow="PSARECO Inventory"
        title="Deleted Products"
        description="View and restore products that have been removed from your inventory."
        icon="fa-solid fa-trash-can"
    >
        <x-slot:actions>

            <a
                href="{{ route('inventory.index') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white text-slate-700 text-sm font-bold shadow-sm hover:bg-slate-50 transition"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to Inventory
            </a>

        </x-slot:actions>
    </x-page-header>


    <x-success />
    <x-errors />


    {{-- Trash Summary --}}
    <section class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5 mb-6">

        <div class="flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                <i class="fa-solid fa-trash-can text-lg"></i>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    Trash
                </p>

                <h2 class="text-xl font-bold text-slate-800">
                    {{ $deletedInventories->total() }}
                    {{ Str::plural('deleted product', $deletedInventories->total()) }}
                </h2>

                <p class="text-xs text-slate-400 mt-0.5">
                    Deleted products are kept here until permanently removed.
                </p>
            </div>

        </div>

    </section>


    @if($deletedInventories->count())

        <section class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">

            {{-- Desktop --}}
            <div class="hidden md:block overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr class="bg-slate-50 border-b border-slate-100 text-left">

                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                Product
                            </th>

                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                Type
                            </th>

                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">
                                Stock
                            </th>

                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                Deleted
                            </th>

                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($deletedInventories as $item)

                            @php

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

                                {{-- Product --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-9 h-9 shrink-0 rounded-lg {{ $iconBg }} {{ $iconColor }} flex items-center justify-center"
                                        >
                                            <i class="fa-solid {{ $icon }} text-sm"></i>
                                        </div>

                                        <div class="min-w-0">

                                            <p class="font-semibold text-slate-800 truncate">
                                                {{ $item->name }}
                                            </p>

                                            <p class="text-[11px] text-slate-400">
                                                Added {{ $item->created_at?->format('M d, Y') ?? '—' }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Type --}}
                                <td class="px-5 py-4 text-slate-500">
                                    {{ $item->type }}
                                </td>


                                {{-- Stock --}}
                                <td class="px-5 py-4 text-right">

                                    <span class="font-semibold text-slate-700">
                                        {{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}
                                    </span>

                                    <span class="text-xs text-slate-400">
                                        {{ $item->unit }}
                                    </span>

                                </td>


                                {{-- Deleted --}}
                                <td class="px-5 py-4">

                                    <p class="text-slate-600 font-medium">
                                        {{ $item->deleted_at?->format('M d, Y') }}
                                    </p>

                                    <p class="text-[11px] text-slate-400">
                                        {{ $item->deleted_at?->format('h:i A') }}
                                    </p>

                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Restore --}}
                                        <form
                                            action="{{ route('inventory.restoreProduct', $item->id) }}"
                                            method="POST"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                title="Restore product"
                                                class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 transition"
                                            >
                                                <i class="fa-solid fa-rotate-left text-xs"></i>
                                            </button>
                                        </form>


                                        {{-- Permanent Delete --}}
                                        <x-confirm-modal
                                            title="Permanently Delete Product"
                                            :message="'Permanently delete ' . $item->name . '? This action cannot be undone.'"
                                            confirmText="Delete Permanently"
                                            confirmClass="bg-red-600 hover:bg-red-700 text-white"
                                            icon="triangle-exclamation"
                                            :action="route('inventory.forceDeleteProduct', $item->id)"
                                            method="DELETE"
                                        >

                                            <button
                                                type="button"
                                                title="Delete permanently"
                                                class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition"
                                            >
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>

                                        </x-confirm-modal>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Mobile --}}
            <div class="md:hidden divide-y divide-slate-100">

                @foreach($deletedInventories as $item)

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div class="flex items-center gap-3 min-w-0">

                                @if($item->type === 'Fertilizer')

                                    <div class="w-9 h-9 shrink-0 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                        <i class="fa-solid fa-leaf text-sm"></i>
                                    </div>

                                @else

                                    <div class="w-9 h-9 shrink-0 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                        <i class="fa-solid fa-bug text-sm"></i>
                                    </div>

                                @endif

                                <div class="min-w-0">

                                    <p class="font-semibold text-slate-800 truncate">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-[11px] text-slate-400">
                                        {{ $item->type }}
                                    </p>

                                </div>

                            </div>

                            <span class="shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold">
                                Deleted
                            </span>

                        </div>


                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">

                            <div>
                                <span class="text-slate-400">
                                    Stock:
                                </span>

                                <span class="font-semibold text-slate-700">
                                    {{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}
                                    {{ $item->unit }}
                                </span>
                            </div>

                            <div>
                                <span class="text-slate-400">
                                    Price:
                                </span>

                                <span class="font-semibold text-slate-700">
                                    ₱{{ number_format($item->price, 2) }}
                                </span>
                            </div>

                            <div class="col-span-2">

                                <span class="text-slate-400">
                                    Deleted:
                                </span>

                                <span class="text-slate-600 font-medium">
                                    {{ $item->deleted_at?->format('M d, Y h:i A') }}
                                </span>

                            </div>

                        </div>


                        <div class="mt-3 flex items-center justify-end gap-2">

                            {{-- Restore --}}
                            <form
                                action="{{ route('inventory.restoreProduct', $item->id) }}"
                                method="POST"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition"
                                >
                                    <i class="fa-solid fa-rotate-left"></i>
                                    Restore
                                </button>

                            </form>


                            {{-- Permanent Delete --}}
                            <x-confirm-modal
                                title="Permanently Delete Product"
                                :message="'Permanently delete ' . $item->name . '? This action cannot be undone.'"
                                confirmText="Delete Permanently"
                                confirmClass="bg-red-600 hover:bg-red-700 text-white"
                                icon="triangle-exclamation"
                                :action="route('inventory.forceDeleteProduct', $item->id)"
                                method="DELETE"
                            >

                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                    Delete
                                </button>

                            </x-confirm-modal>

                        </div>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- Pagination --}}
        @if($deletedInventories->hasPages())

            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">

                <p class="text-xs text-slate-400 order-2 sm:order-1">

                    Showing
                    {{ $deletedInventories->firstItem() }}–{{ $deletedInventories->lastItem() }}

                    of

                    {{ $deletedInventories->total() }}

                    {{ Str::plural('deleted product', $deletedInventories->total()) }}

                </p>

                <div class="order-1 sm:order-2">
                    {{ $deletedInventories->onEachSide(1)->links() }}
                </div>

            </div>

        @endif


    @else

        {{-- Empty Trash --}}
        <section class="bg-white border border-dashed border-slate-200 rounded-3xl p-10 sm:p-14 text-center">

            <div class="mx-auto w-20 h-20 rounded-3xl bg-slate-50 text-slate-400 flex items-center justify-center">
                <i class="fa-solid fa-trash-can text-3xl"></i>
            </div>

            <h3 class="mt-5 text-lg font-bold text-slate-800">
                Trash is empty
            </h3>

            <p class="mt-2 text-sm text-slate-400 max-w-md mx-auto">
                Deleted inventory products will appear here.
            </p>

            <a
                href="{{ route('inventory.index') }}"
                class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition"
            >
                <i class="fa-solid fa-boxes-stacked"></i>
                Back to Inventory
            </a>

        </section>

    @endif

</main>

@endsection
