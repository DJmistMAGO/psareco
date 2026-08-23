@extends('layouts.app')

@section('title', 'Booking History - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <!-- Hero Header & Actions -->
        <section
            class="bg-gradient-to-r from-[#2c7a56] to-[#40a072] text-white rounded-2xl p-6 mb-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-calendar-alt"></i> Machinery Booking History
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">View your past machinery bookings and rental history</p>
            </div>
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

        <!-- Machine Booking Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col mb-6">
            <!-- Header & Search Bar -->
            <div
                class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-tractor text-[#2c7a56] text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Book Status</h3>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"
                        id="fertilizerCount">0</span>
                </div>

                <!-- Search Input Feature -->
                <div class="relative w-full sm:w-64">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="bookingSearch" onkeyup="filterTable()" placeholder="Search machinery..."
                        class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-[#2c7a56] focus:bg-white text-slate-700 transition" />
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="border-b border-slate-100 px-5 pt-3 bg-slate-50/50">
                <nav class="-mb-px flex gap-2" aria-label="Tabs">
                    <button id="tab-completed" onclick="switchTab('completed')"
                        class="tab-btn flex items-center gap-2 py-2.5 px-4 text-xs font-semibold border-b-2 border-[#2c7a56] text-[#2c7a56] transition-colors">
                        <i class="fa-solid fa-circle-check text-[#2c7a56]"></i>
                        Completed
                    </button>
                    <button id="tab-cancelled" onclick="switchTab('cancelled')"
                        class="tab-btn flex items-center gap-2 py-2.5 px-4 text-xs font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors">
                        <i class="fa-solid fa-circle-xmark text-slate-400"></i>
                        Cancelled
                    </button>
                </nav>
            </div>

            <!-- Table Container -->
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Machinery Rented</th>
                            <th class="py-2.5 px-4">Start Date</th>
                            <th class="py-2.5 px-4">End Date</th>
                            <th class="py-2.5 px-4">Total Days</th>
                            <th class="py-2.5 px-4">Total Hours</th>
                            <th class="py-2.5 px-4">Cost Price</th>
                            <th class="py-2.5 px-4">Status</th>
                            <th class="py-2.5 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody id="fertilizersTableBody" class="divide-y divide-slate-100 text-xs text-slate-700">
                        @foreach ($bookings as $booking)
                            <tr class="booking-row hover:bg-slate-50/80 transition-colors" data-status="completed">
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $booking->machine->machinery_name }} -
                                    {{ $booking->machine->model }}</td>
                                <td class="py-3 px-4">{{ $booking->start_date->format('F j, Y') }}</td>
                                <td class="py-3 px-4">{{ $booking->end_date->format('F j, Y') }}</td>
                                <td class="py-3 px-4">{{ $booking->days }}</td>
                                <td class="py-3 px-4 font-medium text-slate-900">{{ $booking->total_hours }}</td>
                                <td class="py-3 px-4 font-medium text-slate-900"> ₱
                                    {{ number_format($booking->total_amount, 2) }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                        Completed
                                    </span>
                                </td>
                                <td>
                                    <button type="button"
                                        class="inline-flex items-center gap-2 bg-emerald-600 text-white hover:bg-emerald-700 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition ">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Empty Search Result Indicator -->
                        <tr id="emptyRow" class="hidden">
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                No matching bookings found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection

@push('scripts')
    <script>
        let activeTab = 'completed';

        function switchTab(status) {
            activeTab = status;

            // Tab styling toggles
            const completedBtn = document.getElementById('tab-completed');
            const cancelledBtn = document.getElementById('tab-cancelled');

            if (status === 'completed') {
                completedBtn.className =
                    "tab-btn flex items-center gap-2 py-2.5 px-4 text-xs font-semibold border-b-2 border-[#2c7a56] text-[#2c7a56] transition-colors";
                cancelledBtn.className =
                    "tab-btn flex items-center gap-2 py-2.5 px-4 text-xs font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors";
            } else {
                cancelledBtn.className =
                    "tab-btn flex items-center gap-2 py-2.5 px-4 text-xs font-semibold border-b-2 border-red-500 text-red-600 transition-colors";
                completedBtn.className =
                    "tab-btn flex items-center gap-2 py-2.5 px-4 text-xs font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors";
            }

            filterTable();
        }

        function filterTable() {
            const query = document.getElementById('bookingSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.booking-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const rowText = row.innerText.toLowerCase();

                // Matches tab status AND search query
                if (rowStatus === activeTab && rowText.includes(query)) {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            // Toggle Empty Row & Count
            document.getElementById('emptyRow').classList.toggle('hidden', visibleCount > 0);
            document.getElementById('fertilizerCount').innerText = visibleCount;
        }
    </script>

@endpush
