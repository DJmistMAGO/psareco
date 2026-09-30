@extends('layouts.app')

@section('title', 'Machinery Booking - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <x-page-header
            eyebrow="PSARECO Machinery Booking"
            title="Machinery Booking"
            description="Book equipment, track daily rental rates, and monitor agricultural fleet availability"
            icon="fa-solid fa-calendar-alt"
        />

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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 mb-6 print:hidden" id="bookingFormContainer">
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
                    <div class="sm:col-span-12 md:col-span-4">
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="bookingMachine">
                            Select Machinery <span class="text-red-500">*</span>
                        </label>
                        <select id="bookingMachine" name="machine_id" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="">-- Select a Machinery --</option>
                            @foreach ($availableMachinery as $machine)
                                <option value="{{ $machine->id }}">
                                    {{ $machine->machinery_name }} - ₱{{ number_format($machine->price, 2) }}/day
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Booking Date Range -->
                    <div class="sm:col-span-6 md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="date-picker">
                            Booking Dates <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="date-picker" placeholder="Select Dates" required readonly
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">

                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                    </div>

                    <!-- Start Day Type -->
                    <div class="sm:col-span-6 md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="start_day_type">Start Session</label>
                        <select name="start_day_type" id="start_day_type"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="Whole Day">Whole Day</option>
                            <option value="Morning Half Day">Morning Half Day</option>
                            <option value="Afternoon Half Day">Afternoon Half Day</option>
                        </select>
                    </div>

                    <!-- End Day Type -->
                    <div class="sm:col-span-6 md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="end_day_type">End Session</label>
                        <select name="end_day_type" id="end_day_type"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="Whole Day">Whole Day</option>
                            <option value="Morning Half Day">Morning Half Day</option>
                            <option value="Afternoon Half Day">Afternoon Half Day</option>
                        </select>
                    </div>

                    <!-- Days Counter -->
                    <div class="sm:col-span-5 md:col-span-1">
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="bookingDays">Days</label>
                        <input type="number" id="bookingDays" disabled placeholder="0" step="0.5"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <input type="hidden" name="days" id="hiddenBookingDays">
                    </div>

                    <!-- Submit Button -->
                    <div class="sm:col-span-12 md:col-span-12">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-base py-2.5 px-3 rounded-xl shadow-sm transition cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i> <span>Submit Booking</span>
                        </button>
                    </div>
                </div>
            </form>
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
                            <p class="text-[11px] text-slate-400 mt-0.5">Manage and track your equipment rentals</p>
                        </div>
                    </div>

                    <!-- Total Count Badge -->
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-semibold px-2.5 py-0.5 rounded-full"
                        id="fertilizerCount">
                        {{ $userBookings->count() }} Total
                    </span>
                </div>

                <!-- Filter Tabs -->
                <div class="flex space-x-1 border-b border-slate-100 -mb-px">
                    <button @click="tab = 'pending'"
                        :class="tab === 'pending' ? 'border-emerald-600 text-emerald-700 bg-emerald-50/50' :
                            'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                        class="flex items-center gap-2 py-2.5 px-3.5 border-b-2 font-medium text-xs transition-all duration-150 rounded-t-lg">
                        <span>Pending</span>
                        <span :class="tab === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600'"
                            class="text-[10px] font-bold px-1.5 py-0.5 rounded-full transition-colors">
                            {{ $userBookings->where('status', 'Pending')->count() }}
                        </span>
                    </button>

                    <button @click="tab = 'approved'"
                        :class="tab === 'approved' ? 'border-emerald-600 text-emerald-700 bg-emerald-50/50' :
                            'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
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
                            <tr x-show="tab === '{{ strtolower($booking->status) }}'" x-cloak
                                class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-4 font-medium text-slate-800">{{ $booking->machine->machinery_name }}</td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('M j, Y') }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('M j, Y') }}
                                </td>
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
                                    @if ($booking->status === 'Pending')
                                        <x-confirm-modal
                                            title="Delete Booking"
                                            :message="'Are you sure you want to delete this booking?'"
                                            confirmText="Delete"
                                            confirmClass="bg-red-600 hover:bg-red-700 text-white"
                                            icon="shield-alert"
                                            :action="route('farmers.deleteBooking', $booking->id)"
                                            method="DELETE"
                                        >
                                            <button type="button" title="Delete product" class="inline-flex items-center gap-1 bg-white hover:bg-rose-50 text-red-600 hover:text-rose-700 font-medium text-base py-1.5 px-3 rounded-lg border border-slate-200 hover:border-rose-200 shadow-sm transition-all duration-150">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </x-confirm-modal>
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
    const bookingDetailsByMachine = @json($bookingDetailsByMachine);

    const machineSelect = document.getElementById("bookingMachine");
    const startDaySelect = document.getElementById("start_day_type");
    const endDaySelect = document.getElementById("end_day_type");
    const hiddenStartDayInput = document.getElementById("hidden_start_day_type");
    const hiddenEndDayInput = document.getElementById("hidden_end_day_type");

    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const daysInput = document.getElementById("bookingDays");
    const hiddenDaysInput = document.getElementById("hiddenBookingDays");

    let selectedMachineId = null;

    // Helper: Sync hidden inputs with select dropdowns
    function syncHiddenInputs() {
        if (hiddenStartDayInput) hiddenStartDayInput.value = startDaySelect.value;
        if (hiddenEndDayInput) hiddenEndDayInput.value = endDaySelect.value;
    }

    // Helper: Disable/Enable specific <option> elements based on booked sessions
    function updateDropdownOptions(selectElement, bookedSessions = []) {
        const options = selectElement.options;

        for (let i = 0; i < options.length; i++) {
            const optValue = options[i].value;

            // Reset option state
            options[i].disabled = false;

            // If "Whole Day" is already booked, disable everything
            if (bookedSessions.includes("Whole Day")) {
                options[i].disabled = true;
            }
            // If "Morning Half Day" is booked -> disable "Morning Half Day" and "Whole Day"
            else if (bookedSessions.includes("Morning Half Day")) {
                if (optValue === "Morning Half Day" || optValue === "Whole Day") {
                    options[i].disabled = true;
                }
            }
            // If "Afternoon Half Day" is booked -> disable "Afternoon Half Day" and "Whole Day"
            else if (bookedSessions.includes("Afternoon Half Day")) {
                if (optValue === "Afternoon Half Day" || optValue === "Whole Day") {
                    options[i].disabled = true;
                }
            }
        }

        // Auto-select first available/enabled option if currently selected option gets disabled
        if (selectElement.options[selectElement.selectedIndex]?.disabled) {
            for (let i = 0; i < options.length; i++) {
                if (!options[i].disabled) {
                    selectElement.value = options[i].value;
                    break;
                }
            }
        }

        syncHiddenInputs();
    }

    function computeTotalDays(selectedDates) {
        if (!selectedDates || selectedDates.length < 2) {
            if (daysInput) daysInput.value = "";
            if (hiddenDaysInput) hiddenDaysInput.value = "";
            return;
        }

        let diffTime = Math.abs(selectedDates[1] - selectedDates[0]);
        let calendarDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

        if (calendarDays === 1) {
            // For 1-day range, force end session to match start session and disable dropdown
            endDaySelect.value = startDaySelect.value;
            endDaySelect.disabled = true;
            endDaySelect.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            // Re-enable end day select for multi-day range
            endDaySelect.disabled = false;
            endDaySelect.classList.remove("opacity-50", "cursor-not-allowed");
        }

        let adjustedDays = calendarDays;

        if (calendarDays === 1) {
            if (startDaySelect.value !== "Whole Day") adjustedDays = 0.5;
        } else {
            if (startDaySelect.value !== "Whole Day") adjustedDays -= 0.5;
            if (endDaySelect.value !== "Whole Day") adjustedDays -= 0.5;
        }

        adjustedDays = Math.max(adjustedDays, 0.5);

        if (daysInput) daysInput.value = adjustedDays;
        if (hiddenDaysInput) hiddenDaysInput.value = adjustedDays;

        syncHiddenInputs();
    }

    const fpInstance = flatpickr("#date-picker", {
        mode: "range",
        minDate: "today",
        dateFormat: "M j, Y",
        disable: [],

        onChange: function(selectedDates, dateStr, instance) {
            startDateInput.value = "";
            endDateInput.value = "";

            const machineBookings = bookingDetailsByMachine[selectedMachineId] ?? {};

            // 1. Single Click (Start Date Selected)
            if (selectedDates.length === 1) {
                const startFormatted = instance.formatDate(selectedDates[0], "Y-m-d");
                startDateInput.value = startFormatted;

                // Update start dropdown for start date
                const startBooked = machineBookings[startFormatted] ?? [];
                updateDropdownOptions(startDaySelect, startBooked);

                // Clear end dropdown options until end date is picked
                updateDropdownOptions(endDaySelect, []);
            }

            // 2. Range Click (Start Date & End Date Selected)
            if (selectedDates.length === 2) {
                const startFormatted = instance.formatDate(selectedDates[0], "Y-m-d");
                const endFormatted = instance.formatDate(selectedDates[1], "Y-m-d");

                startDateInput.value = startFormatted;
                endDateInput.value = endFormatted;

                // Evaluate Start Date booked sessions -> update Start Dropdown
                const startBooked = machineBookings[startFormatted] ?? [];
                updateDropdownOptions(startDaySelect, startBooked);

                // Evaluate End Date booked sessions -> update End Dropdown
                const endBooked = machineBookings[endFormatted] ?? [];
                updateDropdownOptions(endDaySelect, endBooked);

                // Check for overlapping full-day bookings in the date range
                let curr = new Date(selectedDates[0]);
                let hasOverlap = false;

                while (curr <= selectedDates[1]) {
                    let formatted = instance.formatDate(curr, "Y-m-d");
                    let dateSessions = machineBookings[formatted] ?? [];

                    if (
                        dateSessions.includes("Whole Day") ||
                        (dateSessions.includes("Morning Half Day") && dateSessions.includes("Afternoon Half Day"))
                    ) {
                        hasOverlap = true;
                        break;
                    }
                    curr.setDate(curr.getDate() + 1);
                }

                if (hasOverlap) {
                    alert("Selected range includes fully booked dates. Please select an available continuous range.");
                    instance.clear();
                    startDateInput.value = "";
                    endDateInput.value = "";
                    updateDropdownOptions(startDaySelect, []);
                    updateDropdownOptions(endDaySelect, []);
                    computeTotalDays([]);
                    return;
                }

                computeTotalDays(selectedDates);
            }
        }
    });

    startDaySelect.addEventListener("change", function() {
        if (endDaySelect.disabled) {
            endDaySelect.value = startDaySelect.value;
        }
        computeTotalDays(fpInstance.selectedDates);
    });

    endDaySelect.addEventListener("change", function() {
        computeTotalDays(fpInstance.selectedDates);
    });

    machineSelect.addEventListener("change", function() {
        selectedMachineId = String(this.value);

        fpInstance.clear();
        startDateInput.value = "";
        endDateInput.value = "";
        updateDropdownOptions(startDaySelect, []);
        updateDropdownOptions(endDaySelect, []);
        computeTotalDays([]);

        if (!selectedMachineId) {
            fpInstance.set("disable", []);
            return;
        }

        const machineBookings = bookingDetailsByMachine[selectedMachineId] ?? {};

        // Disable dates on Flatpickr calendar ONLY if fully booked
        fpInstance.set("disable", [
            function(date) {
                const formattedDate = fpInstance.formatDate(date, "Y-m-d");
                const sessions = machineBookings[formattedDate] ?? [];

                return (
                    sessions.includes("Whole Day") ||
                    (sessions.includes("Morning Half Day") && sessions.includes("Afternoon Half Day"))
                );
            }
        ]);

        fpInstance.redraw();
        fpInstance.open();
    });
});
    </script>
@endpush
