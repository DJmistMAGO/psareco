@extends('layouts.app')
@section('title', 'Machinery Booking - PSARECO')
@section('content')

    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8" x-data="{ currentStatus: '{{ strtolower(request('status', 'pending')) }}' }">
        <x-dashboard-header />

        <x-page-header eyebrow="PSARECO Machinery" title="Machinery Booking"
            description="Manage machinery bookings, track daily rental rates, and monitor availability"
            icon="fa-solid fa-tractor" />

        <x-success />
        <x-errors />

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-calendar-check text-emerald-600"></i>
                            Machinery Rental Bookings
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Manage machinery rental requests and booking status.
                        </p>

                    </div>

                    <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:w-64">
                        <input type="hidden" name="status" :value="currentStatus">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search farmer or machinery..."
                            class="w-full pl-9 pr-9 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        @if (request('search'))
                            <a href="{{ url()->current() }}?status={{ request('status', 'pending') }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition"
                                title="Clear Search">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </form>
                </div>

                @php
                    $activeStatus = strtolower(request('status', 'pending'));
                @endphp

                <div class="flex items-center gap-1.5 mt-5 overflow-x-auto pb-1">

                    {{-- Pending --}}
                    <a href="{{ url()->current() }}?status=pending{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}"
                        @click="currentStatus = 'pending'"
                        class="
                            inline-flex
                            shrink-0
                            items-center
                            gap-1.5
                            px-3
                            py-2
                            rounded-xl
                            text-xs
                            font-semibold
                            transition

                            {{ $activeStatus === 'pending'
                                ? 'bg-amber-50 text-amber-700 border border-amber-200'
                                : 'text-slate-500 border border-transparent hover:bg-emerald-50 hover:text-emerald-700' }}
                        ">

                        <i class="fa-solid fa-clock text-[11px]"></i>

                        Pending

                        <span class="px-1.5 py-0.5 rounded-full bg-amber-100 text-[10px] font-bold">
                            {{ $statusCounts['pending'] ?? ($statusCounts['Pending'] ?? 0) }}
                        </span>

                    </a>


                    {{-- Approved --}}
                    <a href="{{ url()->current() }}?status=approved{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}"
                        @click="currentStatus = 'approved'"
                        class="
                            inline-flex
                            shrink-0
                            items-center
                            gap-1.5
                            px-3
                            py-2
                            rounded-xl
                            text-xs
                            font-semibold
                            transition

                            {{ $activeStatus === 'approved'
                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                : 'text-slate-500 border border-transparent hover:bg-emerald-50 hover:text-emerald-700' }}
                        ">

                        <i class="fa-solid fa-circle-check text-[11px]"></i>

                        Approved

                        <span class="px-1.5 py-0.5 rounded-full bg-emerald-100 text-slate-500 text-[10px] font-bold">
                            {{ $statusCounts['approved'] ?? ($statusCounts['Approved'] ?? 0) }}
                        </span>

                    </a>


                    {{-- Completed --}}
                    <a href="{{ url()->current() }}?status=completed{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}"
                        @click="currentStatus = 'completed'"
                        class="
                            inline-flex
                            shrink-0
                            items-center
                            gap-1.5
                            px-3
                            py-2
                            rounded-xl
                            text-xs
                            font-semibold
                            transition

                            {{ $activeStatus === 'completed'
                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                : 'text-slate-500 border border-transparent hover:bg-emerald-50 hover:text-emerald-700' }}
                        ">

                        <i class="fa-solid fa-flag-checkered text-[11px]"></i>

                        Completed

                        <span class="px-1.5 py-0.5 rounded-full bg-emerald-400 text-white text-[10px] font-bold">
                            {{ $statusCounts['completed'] ?? ($statusCounts['Completed'] ?? 0) }}
                        </span>

                    </a>


                    {{-- Declined / Cancelled --}}
                    <a href="{{ url()->current() }}?status=declined{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}"
                        @click="currentStatus = 'declined'"
                        class="
                            inline-flex
                            shrink-0
                            items-center
                            gap-1.5
                            px-3
                            py-2
                            rounded-xl
                            text-xs
                            font-semibold
                            transition

                            {{ in_array($activeStatus, ['declined', 'cancelled'])
                                ? 'bg-red-50 text-red-700 border border-red-200'
                                : 'text-slate-500 border border-transparent hover:bg-red-50 hover:text-red-700' }}
                        ">

                        <i class="fa-solid fa-circle-xmark text-[11px]"></i>

                        Declined

                        <span class="px-1.5 py-0.5 rounded-full bg-red-100 text-slate-500 text-[10px] font-bold">
                            {{ $statusCounts['declined'] ?? ($statusCounts['Declined'] ?? ($statusCounts['cancelled'] ?? ($statusCounts['Cancelled'] ?? 0))) }}
                        </span>

                    </a>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    {{-- Table Header --}}
                    <thead>

                        <tr class="bg-slate-50/80 border-b border-slate-100">

                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                Farmer
                            </th>

                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                Machinery
                            </th>

                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                Rental Period
                            </th>

                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                Days
                            </th>

                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                Total Cost
                            </th>

                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    {{-- Table Body --}}
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($bookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-9 w-9 shrink-0 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                            <i class="fa-solid fa-user text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">
                                                {{ $booking->user->name }}
                                            </p>
                                            <p class="text-[10px] text-slate-400">
                                                Farmer
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                            <i class="fa-solid fa-tractor text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-800">
                                                {{ $booking->machine->machinery_name }}
                                            </p>
                                            <p class="text-[10px] text-slate-400">
                                                {{ $booking->machine->model }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 text-xs">
                                        <div>
                                            <p class="text-[10px] text-slate-400">
                                                Start
                                            </p>
                                            <p class="font-semibold text-slate-700">
                                                {{ $booking->start_date->format('M j, Y') }}
                                            </p>
                                        </div>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-300"></i>

                                        <div>

                                            <p class="text-[10px] text-slate-400">
                                                End
                                            </p>

                                            <p class="font-semibold text-slate-700">
                                                {{ $booking->end_date->format('M j, Y') }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="px-5 py-4">

                                    <span class="text-xs font-bold text-slate-700">
                                        {{ $booking->days }} Days
                                    </span>

                                </td>

                                <td class="px-5 py-4">

                                    <span class="text-sm font-extrabold text-emerald-700">
                                        ₱ {{ number_format($booking->total_amount, 2) }}
                                    </span>

                                </td>

                                <td class="px-5 py-4">

                                    @if ($booking->status === 'Pending')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200 text-[10px] font-bold">

                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                                            {{ $booking->status }}

                                        </span>
                                    @elseif ($booking->status === 'Approved')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 text-[10px] font-bold">

                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                            {{ $booking->status }}

                                        </span>
                                    @elseif ($booking->status === 'Completed')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-800 border border-green-200 text-[10px] font-bold">

                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                            {{ $booking->status }}

                                        </span>
                                    @elseif (in_array($booking->status, ['Declined', 'Cancelled']))
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-100 text-red-800 border border-red-200 text-[10px] font-bold">

                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                            {{ $booking->status }}

                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if ($booking->status === 'Pending')
                                        <div x-data="{ declineModalOpen: false, submitting: false }" class="inline-flex items-center gap-1.5">

                                            <x-confirm-modal title="Approve Booking" :message="'Are you sure you want to approve this booking?'" confirmText="Approve"
                                                confirmClass="bg-green-600 hover:bg-green-700 text-white"
                                                icon="shield-alert" :action="route('officer.approve-booking', $booking->id)" method="PUT" :data='"<input type=\"hidden\" name=\"status\" value=\"Approved\">"'>

                                                
                                                <button type="button" title="Approve Booking"
                                                    class="inline-flex items-center gap-2 bg-emerald-600 text-white hover:bg-emerald-700 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition">
                                                    Approve
                                                </button>
                                            </x-confirm-modal>

                                            <button type="button" @click="declineModalOpen = true"
                                                title="Decline Booking"
                                                class="inline-flex items-center gap-2 bg-red-600 text-white hover:bg-red-700 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition">
                                                Decline
                                            </button>

                                            <template x-teleport="body">
                                                <div x-show="declineModalOpen" x-cloak
                                                    class="fixed inset-0 z-50 overflow-y-auto font-sans"
                                                    aria-labelledby="decline-modal-title-{{ $booking->id }}"
                                                    role="dialog" aria-modal="true">
                                                    <div x-show="declineModalOpen"
                                                        x-transition:enter="ease-out duration-300"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-100"
                                                        x-transition:leave="ease-in duration-200"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0"
                                                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity">
                                                    </div>

                                                    <div
                                                        class="flex min-h-full items-center justify-center p-4 text-center">
                                                        <div x-show="declineModalOpen"
                                                            x-transition:enter="ease-out duration-300"
                                                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                            x-transition:leave="ease-in duration-200"
                                                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-red-900/10">
                                                            <form
                                                                action="{{ route('officer.decline-booking', $booking->id) }}"
                                                                method="POST"
                                                                @submit="if (submitting) { $event.preventDefault(); } else { submitting = true; }">
                                                                @csrf
                                                                @method('PUT')

                                                                <input type="hidden" name="status" value="Declined">

                                                                <div
                                                                    class="bg-gradient-to-r from-red-50 via-rose-50/40 to-white px-6 py-4 border-b border-red-100">
                                                                    <div class="flex items-center gap-3">
                                                                        <div
                                                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700 shadow-inner">
                                                                            <svg class="h-5 w-5" fill="none"
                                                                                viewBox="0 0 24 24" stroke-width="2"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM12 3s7.5 2.25 7.5 10.5c0 5-3.5 8-7.5 9.5-4-1.5-7.5-4.5-7.5-9.5C4.5 5.25 12 3 12 3z" />
                                                                            </svg>
                                                                        </div>

                                                                        <div>
                                                                            <h3 class="text-base font-semibold text-slate-800"
                                                                                id="decline-modal-title-{{ $booking->id }}">
                                                                                Decline Booking
                                                                            </h3>

                                                                            <p
                                                                                class="text-[11px] font-medium tracking-wide uppercase text-red-600">
                                                                                Farmer Resource Management System
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="px-6 py-6">
                                                                    <p class="text-sm leading-relaxed text-slate-600">
                                                                        Are you sure you want to decline this booking?
                                                                    </p>

                                                                    <div class="mt-5">
                                                                        <label for="remarks-{{ $booking->id }}"
                                                                            class="mb-2 block text-sm font-semibold text-slate-700">
                                                                            Remarks <span class="text-red-500">*</span>
                                                                        </label>

                                                                        <textarea id="remarks-{{ $booking->id }}" name="remarks" rows="4" required maxlength="1000"
                                                                            placeholder="Enter the reason for declining this booking..."
                                                                            class="w-full resize-none rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"></textarea>

                                                                        <div
                                                                            class="mt-1.5 flex items-center justify-between">
                                                                            <p class="text-[11px] text-slate-400">
                                                                                Please provide a reason for declining this
                                                                                booking.
                                                                            </p>

                                                                            <span class="text-[10px] text-slate-400">
                                                                                Max 1000 characters
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="flex items-center justify-end gap-3 bg-slate-50/80 px-6 py-3.5 border-t border-slate-100">
                                                                    <button type="button"
                                                                        @click="declineModalOpen = false"
                                                                        class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                                                                        Cancel
                                                                    </button>

                                                                    <button type="submit" :disabled="submitting"
                                                                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/30 disabled:cursor-not-allowed disabled:opacity-60">
                                                                        <span x-show="!submitting">
                                                                            Decline
                                                                        </span>

                                                                        <span x-show="submitting" x-cloak
                                                                            class="flex items-center gap-2">
                                                                            <svg class="h-4 w-4 animate-spin"
                                                                                fill="none" viewBox="0 0 24 24">
                                                                                <circle class="opacity-25" cx="12"
                                                                                    cy="12" r="10"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="4"></circle>

                                                                                <path class="opacity-75"
                                                                                    fill="currentColor"
                                                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                                                                </path>
                                                                            </svg>

                                                                            Processing...
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    @endif

                                    @if ($booking->status === 'Approved')
                                        <div class="inline-flex items-center">
                                            <a href="{{ route('farmers.bookingDetails', $booking->id) }}"
                                                class="inline-flex items-center gap-2 bg-emerald-600 text-white hover:bg-emerald-700 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition">
                                                View Details
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty

                            <tr>

                                <td colspan="7" class="px-5 py-14 text-center">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                            <i class="fa-solid fa-calendar-xmark"></i>
                                        </div>

                                        <p class="text-xs font-semibold text-slate-600">
                                            No rental bookings found
                                        </p>

                                        <p class="text-[11px] text-slate-400 mt-1">
                                            There are no bookings matching your current filters.
                                        </p>


                                        @if (request('search'))
                                            <a href="{{ url()->current() }}?status={{ request('status', 'pending') }}"
                                                class="mt-4 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 text-white text-[11px] font-semibold hover:bg-slate-900 transition">
                                                <i class="fa-solid fa-xmark"></i>
                                                Clear Search
                                            </a>
                                        @endif

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            @if (method_exists($bookings, 'links'))
                <div class="px-5 py-3 border-t border-slate-100">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </main>

@endsection
