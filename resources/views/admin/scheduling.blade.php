@extends('layouts.app')

@section('title', 'Machinery Scheduling - PSARECO')

@section('content')

    <!-- ================= MAIN CONTENT AREA ================= -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 overflow-y-auto transition-all duration-300">

        <!-- Mobile Navigation Trigger Bar -->
        <div class="flex items-center justify-between mb-4 lg:hidden bg-white/60 backdrop-blur p-3 rounded-xl shadow-sm border border-emerald-100 print:hidden">
            <span class="font-bold text-emerald-950 text-sm">PSARECO System</span>
            <button @click="mobileOpen = !mobileOpen" class="p-2 text-emerald-800 hover:bg-emerald-100 rounded-lg focus:outline-none">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Hero Header & Actions -->
        <section class="bg-gradient-to-r from-[#2c7a56] to-[#40a072] text-white rounded-2xl p-6 mb-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-calendar-alt"></i> Machinery Scheduling
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">Book equipment, track daily rental rates, and monitor agricultural fleet availability</p>
            </div>

            <div class="flex items-center gap-2 print:hidden">
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white text-emerald-950 hover:bg-emerald-50 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-print"></i> Print Schedule
                </button>
            </div>
        </section>

        <!-- Overdue Equipment Alert Card (Hidden by default) -->
        <div id="overdueSection" class="hidden bg-red-50/90 rounded-2xl shadow-sm border border-red-200 overflow-hidden mb-6 print:hidden">
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
                    <i class="fa-solid fa-calendar-plus text-emerald-600"></i> Request Machine Booking
                </h3>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">New Reservation</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                <!-- Select Machine -->
                <div class="sm:col-span-4">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Select Machinery <span class="text-red-500">*</span></label>
                    <select id="bookingMachine" onchange="updateDailyRate()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <option value="">Select Machine</option>
                    </select>
                </div>

                <!-- Booking Date -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" id="bookingDate" min=""
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>

                <!-- Days -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Duration (Days)</label>
                    <input type="number" id="bookingDays" min="1" value="1" placeholder="Days" oninput="updateDailyRate()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>

                <!-- Total Amount -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Estimated</label>
                    <input type="text" id="totalAmount" readonly placeholder="₱0.00"
                        class="w-full px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 font-extrabold cursor-not-allowed">
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-1">
                    <button onclick="submitBooking()" class="w-full inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2 px-3 rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Machinery Fleet List & Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-tractor text-emerald-600"></i> Machinery Fleet
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800" id="totalMachinesCount">0</span>
                </h3>

                <!-- Search Machinery Input -->
                <div class="relative w-full sm:w-64 print:hidden">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchMachinery" placeholder="Search machinery..." onkeyup="filterMachinery()"
                        class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Machinery Card Grid Container -->
            <div id="machineryList" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="col-span-full text-center text-slate-400 py-12 text-xs">Loading machinery fleet...</div>
            </div>
        </div>

    </main>

@endsection

@push('scripts')
    <script>
        let allMachinery = [];

        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const bookingDateInput = document.getElementById('bookingDate');
            if (bookingDateInput) {
                bookingDateInput.setAttribute('min', today);
                bookingDateInput.value = today;
            }

            loadMachineryData();
            loadOverdueBookings();
        });

        function loadMachineryData() {
            allMachinery = typeof getMachines === 'function' ? getMachines() : [
                { id: 1, name: 'Kubota Four-Wheel Tractor', model: 'L5018', dailyRate: 2500, status: 'Operational', totalUnits: 3, bookedUnits: 1, image: '' },
                { id: 2, name: 'Rice Combine Harvester', model: 'DC-70G', dailyRate: 4500, status: 'Operational', totalUnits: 2, bookedUnits: 2, image: '' },
                { id: 3, name: 'Walk-Behind Rice Transplanter', model: 'SPW-48C', dailyRate: 1200, status: 'Under Maintenance', totalUnits: 1, bookedUnits: 0, image: '' },
                { id: 4, name: 'Corn Sheller Heavy Duty', model: 'CS-1000', dailyRate: 800, status: 'Out of Service', totalUnits: 1, bookedUnits: 0, image: '' }
            ];

            renderMachineryList(allMachinery);
        }

        function renderMachineryList(machines) {
            const container = document.getElementById('machineryList');
            const select = document.getElementById('bookingMachine');
            const countBadge = document.getElementById('totalMachinesCount');

            if (countBadge) countBadge.textContent = machines.length;

            if (!container) return;

            if (machines.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center text-slate-400 py-10 text-xs">No machinery matching criteria.</div>';
                if (select) select.innerHTML = '<option value="">No machinery available</option>';
                return;
            }

            // Render Machinery Grid Cards
            container.innerHTML = machines.map(machine => {
                const rate = Number(machine.dailyRate || 0);
                const placeholder = `https://placehold.co/300x200/f1f5f9/334155?text=${encodeURIComponent(machine.name)}`;

                // Dynamic Tailwind badge styles
                let statusClasses = 'bg-slate-100 text-slate-700 border-slate-200';
                if (machine.status === 'Operational') {
                    statusClasses = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                } else if (machine.status === 'Under Maintenance') {
                    statusClasses = 'bg-amber-100 text-amber-800 border-amber-200';
                } else if (machine.status === 'Out of Service') {
                    statusClasses = 'bg-red-100 text-red-800 border-red-200';
                }

                return `
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="h-36 w-full bg-slate-100 relative overflow-hidden">
                                <img src="${machine.image || placeholder}" alt="${machine.name}" class="w-full h-full object-cover">
                                <span class="absolute top-2 right-2 px-2.5 py-1 rounded-full text-[10px] font-bold border ${statusClasses}">
                                    ${machine.status}
                                </span>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-slate-800 text-xs line-clamp-1">${machine.name}</h4>
                                <p class="text-[11px] text-slate-400 mb-2">${machine.model || 'Model N/A'}</p>
                                <div class="text-sm font-extrabold text-emerald-700 mb-3">
                                    ₱${rate.toLocaleString()} <span class="text-[10px] font-normal text-slate-500">/ day</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 pb-4 pt-0">
                            <div class="flex items-center justify-between text-[11px] bg-slate-50 p-2 rounded-xl text-slate-600 border border-slate-100">
                                <span>Total: <strong class="text-slate-800">${machine.totalUnits || 1}</strong></span>
                                <span>Booked: <strong class="text-emerald-700">${machine.bookedUnits || 0}</strong></span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Update Select Options
            if (select) {
                select.innerHTML = '<option value="">Select Machine</option>' + machines.map(machine =>
                    `<option value="${machine.id}" data-rate="${machine.dailyRate}">${machine.name} - ₱${Number(machine.dailyRate || 0).toLocaleString()}/day</option>`
                ).join('');
            }
        }

        function filterMachinery() {
            const term = (document.getElementById('searchMachinery')?.value || '').toLowerCase();
            const filtered = allMachinery.filter(m =>
                m.name.toLowerCase().includes(term) ||
                (m.model && m.model.toLowerCase().includes(term))
            );
            renderMachineryList(filtered);
        }

        function updateDailyRate() {
            const select = document.getElementById('bookingMachine');
            const daysInput = document.getElementById('bookingDays');
            const totalInput = document.getElementById('totalAmount');

            if (!select || !daysInput || !totalInput) return;

            const selectedOption = select.options[select.selectedIndex];
            const rate = parseFloat(selectedOption?.getAttribute('data-rate') || 0);
            const days = parseInt(daysInput.value || 0, 10);

            if (rate && days > 0) {
                totalInput.value = '₱' + (rate * days).toLocaleString();
            } else {
                totalInput.value = '';
            }
        }

        function submitBooking() {
            const machineSelect = document.getElementById('bookingMachine');
            const dateInput = document.getElementById('bookingDate');
            const daysInput = document.getElementById('bookingDays');

            if (!machineSelect?.value) {
                alert('Please select a machine.');
                return;
            }
            if (!dateInput?.value) {
                alert('Please pick a start date.');
                return;
            }

            alert('Booking request submitted successfully!');
            machineSelect.value = '';
            daysInput.value = '1';
            updateDailyRate();
        }

        function loadOverdueBookings() {
            // Placeholder trigger for overdue listings if present in backend/localStorage
            const overdueSection = document.getElementById('overdueSection');
            if (overdueSection && typeof getOverdueBookings === 'function') {
                const overdue = getOverdueBookings();
                if (overdue && overdue.length > 0) {
                    overdueSection.classList.remove('hidden');
                }
            }
        }
    </script>
@endpush
