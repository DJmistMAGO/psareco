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
                    <i class="fa-solid fa-calendar-alt"></i> Machinery Booking
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">Book equipment, track daily rental rates, and monitor
                    agricultural fleet availability</p>
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
                    <i class="fa-solid fa-calendar-plus text-emerald-600"></i> Request Booking
                </h3>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">New Reservation</span>
            </div>

            <form id="bookingForm" method="POST" action="{{ route('farmers.bookMachinery') }}">

                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                    <!-- Select Machine -->
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Select Machinery <span
                                class="text-red-500">*</span></label>
                        <select id="bookingMachine" name="machine_id"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="">-- Select a Machinery --</option>

                            @foreach ($availableMachinery as $machine)
                                <option value="{{ $machine->id }}">
                                    {{ $machine->machinery_name }} - ₱{{ number_format($machine->price, 2) }}/day
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Booking Date -->
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Start Date <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="date-picker" placeholder="Select Date"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">

                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                    </div>

                    <!-- Days -->
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Duration (Days)</label>
                        <input type="number" id="bookingDays" disabled placeholder="0"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Total Amount -->
                    {{-- <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Estimated</label>
                    <input type="text" id="totalAmount" readonly placeholder="₱0.00"
                        class="w-full px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 font-extrabold cursor-not-allowed">
                </div> --}}

                    <!-- Submit Button -->
                    <div class="sm:col-span-1">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2 px-3 rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>



        <div x-data="{ tab: 'pending' }" class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col mb-6">
    <!-- Card Header with Navigation Tabs -->
    <div class="px-5 pt-4 border-b border-slate-100 bg-white">
        <div class="flex items-center justify-between pb-3">
            <div class="flex items-center space-x-2">
                <div class="p-1.5 bg-emerald-50 rounded-lg">
                    <i class="fa-solid fa-leaf text-emerald-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm leading-none">Booking Status</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Manage and track your equipment rentals</p>
                </div>
            </div>

            <!-- Total Count Badge -->
            <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-semibold px-2.5 py-0.5 rounded-full" id="fertilizerCount">
                {{ $userBookings->count() }} Total
            </span>
        </div>

        <!-- Filter Tabs -->
        <div class="flex space-x-1 border-b border-slate-100 -mb-px">
            <!-- Pending Tab -->
            <button @click="tab = 'pending'"
                :class="tab === 'pending' ? 'border-emerald-600 text-emerald-700 bg-emerald-50/50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="flex items-center gap-2 py-2.5 px-3.5 border-b-2 font-medium text-xs transition-all duration-150 rounded-t-lg">
                <span>Pending</span>
                <span :class="tab === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600'"
                    class="text-[10px] font-bold px-1.5 py-0.5 rounded-full transition-colors">
                    {{ $userBookings->where('status', 'Pending')->count() }}
                </span>
            </button>

            <!-- Approved Tab -->
            <button @click="tab = 'approved'"
                :class="tab === 'approved' ? 'border-emerald-600 text-emerald-700 bg-emerald-50/50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="flex items-center gap-2 py-2.5 px-3.5 border-b-2 font-medium text-xs transition-all duration-150 rounded-t-lg">
                <span>Approved</span>
                <span :class="tab === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'"
                    class="text-[10px] font-bold px-1.5 py-0.5 rounded-full transition-colors">
                    {{ $userBookings->where('status', 'Approved')->count() }}
                </span>
            </button>
        </div>
    </div>

    <!-- Table Body Container -->
    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                    <th class="py-3 px-4">Machinery Rented</th>
                    <th class="py-3 px-4">Start Date</th>
                    <th class="py-3 px-4">End Date</th>
                    <th class="py-3 px-4">Total Days</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="fertilizersTableBody" class="divide-y divide-slate-100 text-xs text-slate-700">
                @forelse ($userBookings as $booking)
                    <tr x-show="tab === '{{ strtolower($booking->status) }}'" x-cloak class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3 px-4 font-medium text-slate-800">{{ $booking->machine->machinery_name }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ \Carbon\Carbon::parse($booking->start_date)->format('M j, Y') }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ \Carbon\Carbon::parse($booking->end_date)->format('M j, Y') }}</td>
                        <td class="py-3 px-4 text-slate-600">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-medium">
                                {{ $booking->days }} {{ Str::plural('day', $booking->days) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if ($booking->status === 'Pending')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200/60 text-[10px] font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Pending
                                </span>
                            @elseif ($booking->status === 'Approved')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[10px] font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Approved
                                </span>
                            @elseif ($booking->status === 'Rejected')
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200/60 text-[10px] font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($booking->status === 'Pending')
                                <a href=""
                                    class="inline-flex items-center gap-1 bg-white hover:bg-rose-50 text-red-600 hover:text-rose-700 font-medium text-base py-1.5 px-3 rounded-lg border border-slate-200 hover:border-rose-200 shadow-sm transition-all duration-150">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            @else
                            <a href="{{ route('farmers.bookingDetails', $booking->id) }}"
                                class="inline-flex items-center gap-1 bg-white hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 font-medium text-base py-1.5 px-3 rounded-lg border border-slate-200 hover:border-emerald-200 shadow-sm transition-all duration-150">
                                <i class="fa-solid fa-calendar-days"></i>
                            </a>
                            @endif
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const disabledDatesByMachine = @json($disabledDatesByMachine);

            const machineSelect = document.getElementById("bookingMachine");

            let selectedMachineId = null;

            const fpInstance = flatpickr("#date-picker", {
                mode: "range",
                minDate: "today",
                dateFormat: "M j, Y",
                disable: [],

                onChange: function(selectedDates, dateStr, instance) {

                    const startDateInput = document.getElementById("start_date");
                    const endDateInput = document.getElementById("end_date");
                    const daysInput = document.getElementById("bookingDays");

                    startDateInput.value = "";
                    endDateInput.value = "";

                    if (daysInput) {
                        daysInput.value = "";
                    }

                    if (selectedDates.length >= 1) {
                        startDateInput.value = instance.formatDate(
                            selectedDates[0],
                            "Y-m-d"
                        );
                    }

                    if (selectedDates.length === 2) {

                        endDateInput.value = instance.formatDate(
                            selectedDates[1],
                            "Y-m-d"
                        );

                        const totalDays =
                            Math.floor(
                                (selectedDates[1] - selectedDates[0]) /
                                (1000 * 60 * 60 * 24)
                            ) + 1;

                        if (daysInput) {
                            daysInput.value = totalDays;
                        }
                    }
                }
            });

            machineSelect.addEventListener("change", function() {

                selectedMachineId = String(this.value);

                fpInstance.clear();

                document.getElementById("start_date").value = "";
                document.getElementById("end_date").value = "";

                const daysInput = document.getElementById("bookingDays");

                if (daysInput) {
                    daysInput.value = "";
                }

                if (!selectedMachineId) {
                    fpInstance.set("disable", []);
                    fpInstance.redraw();
                    return;
                }

                const disabledDates =
                    disabledDatesByMachine[selectedMachineId] ?? [];

                console.log("Selected machine:", selectedMachineId);
                console.log("Dates:", disabledDates);

                fpInstance.set("disable", [
                    function(date) {

                        const formattedDate =
                            fpInstance.formatDate(date, "Y-m-d");

                        return disabledDates.includes(formattedDate);
                    }
                ]);

                fpInstance.redraw();

                fpInstance.open();
            });
        });
    </script>

@endpush
