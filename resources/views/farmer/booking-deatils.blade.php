@extends('layouts.app')

@section('title', 'Machinery Booking - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <x-page-header eyebrow="PSARECO Booking Details" title="Booking Details" description="Look for Booking Details here"
            icon="fa-solid fa-calendar-alt" />

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

        <div class="relative bg-white rounded-2xl shadow-md border border-slate-200 mb-6 print:hidden overflow-hidden"
            id="bookingForm">
            <!-- Ticket Header -->
            <div class="bg-slate-800 text-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-ticket text-emerald-400 text-lg"></i>
                    <span class="font-bold text-sm tracking-wide uppercase">Machinery Booking Pass</span>
                </div>
                <span
                    class="text-xs bg-emerald-500/20 text-emerald-300 font-semibold px-2.5 py-1 rounded-full border border-emerald-500/30">
                    <i class="fa-solid fa-[#000] fa-circle-check mr-1"></i> {{ $booking->status }}
                </span>
            </div>

            <!-- Ticket Body: Main Details -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <!-- Machinery Information -->
                <div class="md:col-span-5 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-4">
                    <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-1">Equipment</p>
                    <h4 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-gear text-emerald-600"></i>
                        {{ $booking->machine->machinery_name }}
                    </h4>
                    <p class="text-xs font-semibold text-emerald-600 mt-1">
                        ₱{{ number_format($booking->machine->price, 2) }} <span class="text-slate-400 font-normal">/
                            hour</span>
                    </p>
                </div>

                <!-- Dates & Schedule -->
                <div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-4 gap-4 text-left">
                    <div>
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-1">
                            <i class="fa-regular fa-calendar text-slate-400 mr-1"></i> Start Date
                        </p>
                        <p class="text-xs font-bold text-slate-700">{{ $booking->start_date->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-1">
                            <i class="fa-regular fa-calendar-check text-slate-400 mr-1"></i> End Date
                        </p>
                        <p class="text-xs font-bold text-slate-700">{{ $booking->end_date->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-1">
                            <i class="fa-regular fa-clock text-slate-400 mr-1"></i> Duration
                        </p>
                        <p class="text-xs font-bold text-slate-700">{{ $booking->days }} Days</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-1">
                            <i class="fa-solid fa-hourglass-half text-slate-400 mr-1"></i> Total Hours
                        </p>
                        <p class="text-xs font-bold text-slate-700" id="totalHours">0 hrs</p>
                    </div>
                </div>
            </div>

            <!-- Ticket Tear-Off Divider -->
            <div class="relative flex items-center justify-between my-1">
                <div class="w-4 h-8 bg-slate-100 rounded-r-full border-r border-t border-b border-slate-200"></div>
                <div class="flex-1 border-b-2 border-dashed border-slate-200 mx-2"></div>
                <div class="w-4 h-8 bg-slate-100 rounded-l-full border-l border-t border-b border-slate-200"></div>
            </div>

            <!-- Ticket Stub / Footer: Pricing Details -->
            <div class="bg-slate-50/70 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <i class="fa-solid fa-receipt text-slate-400 text-sm"></i>
                    <span>Summary computed based on selected operating hours.</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Amount:</span>
                    <div class="bg-emerald-600 text-white px-4 py-2 rounded-xl flex items-center gap-1 shadow-sm">
                        <span class="text-sm font-semibold">₱</span>
                        <span class="text-lg font-extrabold tracking-tight" id="totalCost">0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <x-success />
        <x-errors />

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
                    <div>
                        @if($booking->status === 'Approved')
                        <x-confirm-modal title="Complete Booking" :message="'Are you sure to complete your Booking?'" confirmText="Complete"
                            confirmClass="bg-green-600 hover:bg-green-700 text-white" icon="shield-alert" :action="route('farmers.completeBooking', $booking->id)"
                            method="PUT" :data='"<input type=\"hidden\" name=\"total_hours\" id=\"total_hours\"> <input type=\"hidden\" name=\"total_cost\" id=\"total_cost\">"'>
                            <button type="button" title="Complete Booking"
                                class="inline-flex items-center gap-2 bg-emerald-600 text-white hover:bg-emerald-700 font-semibold text-sm py-2 px-3.5 rounded-xl shadow-sm transition ">
                                Complete Booking
                            </button>
                        </x-confirm-modal>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Table Body Container -->
            <div class="w-full overflow-x-auto">
                <form action="{{ route('farmers.updateBookingSlot', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                                <th class="py-3 px-4">Day</th>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4">Start Time</th>
                                <th class="py-3 px-4">End Time</th>
                                <th class="py-3 px-4">Total Hours</th>
                                @if($booking->status === 'Approved')
                                <th class="py-3 px-4 text-center">Submit Hours</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="fertilizersTableBody" class="divide-y divide-slate-100 text-xs text-slate-700">
                            @forelse ($bookingSlots as $slot)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3 px-4 font-medium text-slate-800">Day {{ $loop->iteration }}
                                        <input type="hidden" name="slot_id[]" value="{{ $slot->id }}">
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">
                                        {{ \Carbon\Carbon::parse($slot->booking_date)->format('F j, Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">
                                        <input type="time"
                                            value="{{ $slot->start_time ? \Carbon\Carbon::parse($slot->start_time)->format('H:i') : '' }}"
                                            name="start_time[]"
                                            class="start-time px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                                    </td>

                                    <td class="py-3 px-4 text-slate-600">
                                        <input type="time"
                                            value="{{ $slot->end_time ? \Carbon\Carbon::parse($slot->end_time)->format('H:i') : '' }}"
                                            name="end_time[]"
                                            class="end-time px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                                    </td>

                                    <td class="py-3 px-4 text-slate-600">
                                        <input type="number" value="{{ $slot->hours ?? '' }}" name="hours[]"
                                            class="hours px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition"
                                            step="0.01" readonly>
                                    </td>
                                    @if($booking->status === 'Approved')
                                    <td class="py-3 px-4 text-center">
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 bg-emerald-600 text-white hover:bg-emerald-700 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition">
                                            UPDATE
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400">No bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
            </div>
        </div>


    </main>

@endsection

@push('scripts')
    <script>
        const machinePrice = {{ $booking->machine->price ?? 0 }};

        function calculateTotalHours() {
            let totalHours = 0;

            document.querySelectorAll('.hours').forEach(input => {
                totalHours += parseFloat(input.value) || 0;
            });

            const totalHoursEl = document.getElementById('totalHours');
            const totalCostEl = document.getElementById('totalCost');

            if (totalHoursEl) totalHoursEl.textContent = totalHours.toFixed(2);

            const totalCost = totalHours * machinePrice;
            if (totalCostEl) totalCostEl.textContent = totalCost.toFixed(2);

            document.querySelectorAll('input[name="total_hours"]').forEach(input => {
                input.value = totalHours.toFixed(2);
            });
            document.querySelectorAll('input[name="total_cost"]').forEach(input => {
                input.value = totalCost.toFixed(2);
            });
        }

        document.querySelectorAll('tr').forEach(row => {
            const startTime = row.querySelector('.start-time');
            const endTime = row.querySelector('.end-time');
            const totalHours = row.querySelector('.hours');

            if (!startTime || !endTime || !totalHours) return;

            function calculateHours() {
                if (!startTime.value || !endTime.value) {
                    totalHours.value = '';
                    calculateTotalHours();
                    return;
                }

                const start = new Date(`1970-01-01T${startTime.value}`);
                const end = new Date(`1970-01-01T${endTime.value}`);

                let difference = (end - start) / (1000 * 60 * 60);

                if (difference < 0) {
                    difference += 24;
                }

                totalHours.value = difference.toFixed(2);

                calculateTotalHours();
            }

            startTime.addEventListener('change', calculateHours);
            endTime.addEventListener('change', calculateHours);
        });

        document.addEventListener('DOMContentLoaded', () => {
            calculateTotalHours();

            document.querySelectorAll('.hours').forEach(input => {
                input.addEventListener('input', calculateTotalHours);
            });
        });
    </script>
@endpush
