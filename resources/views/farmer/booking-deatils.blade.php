@extends('layouts.app')

@section('title', 'Machinery Booking - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />


        <!-- Hero Header & Actions -->
        <section
            class="bg-gradient-to-r from-[#2c7a56] to-[#40a072] text-white rounded-2xl p-6 mb-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-calendar-alt"></i> Booking Details
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">Look for Booking Details here</p>
            </div>

            {{-- <div class="flex items-center gap-2 print:hidden">
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white text-emerald-950 hover:bg-emerald-50 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-print"></i> Print Schedule
                </button>
            </div> --}}
        </section>

        <!-- Overdue Equipment Alert Card (Hidden by default) -->
        <div id="overdueSection"
            class="hidden bg-red-50/90 rounded-2xl shadow-sm border border-red-200 overflow-hidden mb-6 print:hidden">
            <div class="bg-red-600 text-white px-5 py-3 flex items-center gap-2 text-sm font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i> Overdue Equipment
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
                    <tbody id="overdueTable" class="divide-y divide-red-100 text-slate-700">
                        <!-- Dynamic rows populated via Javascript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Request Machine Booking Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 mb-6 print:hidden" id="bookingForm">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-calendar-plus text-emerald-600"></i> Booking Details
                </h3>
            </div>

            <form id="bookingForm" method="POST" action="{{ route('farmers.bookMachinery') }}">

                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                    <!-- Select Machine -->
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Machinery Name<span
                                class="text-red-500">*</span></label>
                        <input type="text"
                            value="{{ $booking->machine->machinery_name }} - ₱{{ number_format($booking->machine->price, 2) }}/hour"
                            disabled placeholder="0"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Booking Date -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Start Date <span
                                class="text-red-500">*</span></label>
                        <input type="text" value="{{ $booking->start_date->format('F j, Y') }}" disabled id="date-picker"
                            placeholder="Select Date"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">End Date <span
                                class="text-red-500">*</span></label>
                        <input type="text" value="{{ $booking->end_date->format('F j, Y') }}" disabled id="date-picker"
                            placeholder="Select Date"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Days -->
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Duration (Days)</label>
                        <input type="number" id="bookingDays" value="{{ $booking->days }}" disabled placeholder="0"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Total Amount -->
                    {{-- <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Estimated</label>
                    <input type="text" id="totalAmount" readonly placeholder="₱0.00"
                        class="w-full px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 font-extrabold cursor-not-allowed">
                </div> --}}


                </div>
            </form>
        </div>



        <div x-data="{ tab: 'pending' }"
            class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col mb-6">
            <!-- Card Header with Navigation Tabs -->
            <div class="px-5 pt-4 border-b border-slate-100 bg-white">
                <div class="flex items-center justify-between pb-3">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-emerald-50 rounded-lg">
                            <i class="fa-solid fa-leaf text-emerald-600 text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm leading-none">Booking Status</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Manage and track your time rentals</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Body Container -->
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-3 px-4">Day</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Start Time</th>
                            <th class="py-3 px-4">End Time</th>
                            <th class="py-3 px-4">Total Hours</th>
                        </tr>
                    </thead>
                    <tbody id="fertilizersTableBody" class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse ($bookingSlots as $slot)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-4 font-medium text-slate-800">Day {{ $loop->iteration }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($slot->booking_date)->format('F j, Y') }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <input type="time" name="start_time" id="start_time"
                                        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <input type="time" name="end_time" id="end_time"
                                        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <input type="number" name="end_time" id="end_time"
                                        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </main>

@endsection

@push('scripts')
    <script></script>
@endpush
