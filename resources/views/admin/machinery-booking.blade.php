@extends('layouts.app')
@section('title', 'Machinery Booking - PSARECO')
@section('content')

    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
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
                        <input type="hidden" name="status" value="{{ request('status', 'pending') }}">
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

                    <a href="{{ url()->current() }}?status=pending{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}"
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
                            {{ $statusCounts['Pending'] ?? 0 }}
                        </span>

                    </a>


                    {{-- Approved --}}
                    <a href="{{ url()->current() }}?status=approved{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}"
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
                            {{ $statusCounts['Approved'] ?? 0 }}
                        </span>

                    </a>


                    {{-- Completed --}}
                    <a href="{{ url()->current() }}?status=completed{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}"
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
                            {{ $statusCounts['Completed'] ?? 0 }}
                        </span>

                    </a>


                    {{-- Cancelled --}}
                    <a href="{{ url()->current() }}?status=cancelled{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}"
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

                            {{ $activeStatus === 'cancelled'
                                ? 'bg-red-50 text-red-700 border border-red-200'
                                : 'text-slate-500 border border-transparent hover:bg-red-50 hover:text-red-700' }}
                        ">

                        <i class="fa-solid fa-circle-xmark text-[11px]"></i>

                        Cancelled

                        <span class="px-1.5 py-0.5 rounded-full bg-red-100 text-slate-500 text-[10px] font-bold">
                            {{ $statusCounts['Cancelled'] ?? 0 }}
                        </span>

                    </a>

                </div>

            </div>


            {{-- =====================================================
                BOOKING TABLE
            ====================================================== --}}


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


                        {{-- =================================================
                            BOOKINGS
                        ================================================== --}}

                        @forelse ($bookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition">


                                {{-- =================================================
                                    FARMER
                                ================================================== --}}

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


                                {{-- =================================================
                                    MACHINERY
                                ================================================== --}}

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


                                {{-- =================================================
                                    RENTAL PERIOD
                                ================================================== --}}

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


                                {{-- =================================================
                                    DAYS
                                ================================================== --}}

                                <td class="px-5 py-4">

                                    <span class="text-xs font-bold text-slate-700">
                                        {{ $booking->days }} Days
                                    </span>

                                </td>


                                {{-- =================================================
                                    TOTAL COST
                                ================================================== --}}

                                <td class="px-5 py-4">

                                    <span class="text-sm font-extrabold text-emerald-700">
                                        ₱ {{ number_format($booking->total_amount, 2) }}
                                    </span>

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

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
                                    @elseif ($booking->status === 'Declined')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-100 text-red-800 border border-red-200 text-[10px] font-bold">

                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                            {{ $booking->status }}

                                        </span>
                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTIONS
                                ================================================== --}}

                                <td class="px-5 py-4 text-center">

                                    @if ($booking->status === 'Pending')
                                        <div class="inline-flex items-center gap-1.5">
                                            <x-confirm-modal title="Approve Booking" :message="'Are you sure you want to approve this booking?'" confirmText="Approve"
                                                confirmClass="bg-green-600 hover:bg-green-700 text-white"
                                                icon="shield-alert" :action="route('officer.approve-booking', $booking->id)" method="PUT" :data='"<input type=\"hidden\" name=\"status\" value=\"Approved\">
                                                                                                "'>
                                                <button type="button" title="Complete Booking"
                                                    class="inline-flex items-center gap-2 bg-emerald-600 text-white hover:bg-emerald-700 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition ">
                                                    Approve
                                                </button>
                                            </x-confirm-modal>


                                            <x-confirm-modal title="Decline Booking" :message="'Are you sure you want to decline this booking?'" confirmText="Decline"
                                                confirmClass="bg-red-600 hover:bg-red-700 text-white" icon="shield-alert"
                                                :action="route('officer.decline-booking', $booking->id)" method="PUT" :data='"<input type=\"hidden\" name=\"status\" value=\"Decline\">
                                                                                                "'>
                                                <button type="button" title="Complete Booking"
                                                    class="inline-flex items-center gap-2 bg-red-600 text-white hover:bg-red-700 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition ">
                                                    Decline
                                                </button>
                                            </x-confirm-modal>
                                            @endif

                                            @if ($booking->status === 'Completed')
                                            <a href="{{ route('farmers.bookingDetails', $booking->id) }}"
                                                class="inline-flex items-center gap-2 bg-emerald-600 text-white hover:bg-emerald-700 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition ">
                                                View Details
                                            </a>
                                            @endif

                                        </div>

                                </td>

                            </tr>

                        @empty


                            {{-- =================================================
                                EMPTY STATE
                            ================================================== --}}

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


            {{-- =====================================================
                PAGINATION
            ====================================================== --}}

            @if (method_exists($bookings, 'links'))
                <div class="px-5 py-3 border-t border-slate-100">

                    {{ $bookings->withQueryString()->links() }}

                </div>
            @endif

        </div>

    </main>

@endsection
