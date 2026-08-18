@extends('layouts.app')

@section('title', 'Products for Sale - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />


        <!-- Hero Header & Actions -->
        <section class="bg-gradient-to-r from-[#2c7a56] to-[#40a072] text-white rounded-2xl p-6 mb-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-jar"></i> Products Available
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">List of producst available for sale</p>
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
                            <th class="py-2.5 px-4">product</th>
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

        <!-- Machinery Fleet List & Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-jar text-emerald-600"></i> Products for Sale
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
                { id: 1, name: 'Bio-N', type: 'Fertilizer', price: 100, totalUnits: 3, unit: '200g', image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTooUHZTw20Vv17SqaAiFGsk0C9Xx1trUncafyvRKjji5Q56dBMtval7wE&s=10' },
                { id: 2, name: 'Nativo 75 WG', type: 'Pesticide', price: 105, totalUnits: 2, unit: '12g', image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQbtuSpYNTu4PwUeW5LLayi6-QYYMlTuaoJptrAvyWO7ZhUW4Hb-uPLkHM&s=10' },
            ];

            renderMachineryList(allMachinery);
        }

        function renderMachineryList(product) {
            const container = document.getElementById('machineryList');
            const select = document.getElementById('bookingMachine');
            const countBadge = document.getElementById('totalMachinesCount');

            if (countBadge) countBadge.textContent = product.length;

            if (!container) return;

            if (product.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center text-slate-400 py-10 text-xs">No machinery matching criteria.</div>';
                if (select) select.innerHTML = '<option value="">No machinery available</option>';
                return;
            }

            // Render Machinery Grid Cards
            container.innerHTML = product.map(product => {
                const rate = Number(product.price || 0);
                const placeholder = `https://placehold.co/300x200/f1f5f9/334155?text=${encodeURIComponent(product.name)}`;

                return `
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="h-36 w-full bg-slate-100 relative overflow-hidden">
                                <img src="${product.image || placeholder}" alt="${product.name}" class="w-full h-full object-cover">

                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-slate-800 text-xs line-clamp-1">${product.name}</h4>
                                <p class="text-[11px] text-slate-400 mb-2">${product.type || 'Model N/A'}</p>
                                <div class="text-sm font-extrabold text-emerald-700 mb-3">
                                    ₱${rate.toLocaleString()} <span class="text-[10px] font-normal text-slate-500">/ per ${product.unit || 'unit'}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 pb-4 pt-0">
                            <div class="flex items-center justify-between text-[11px] bg-slate-50 p-2 rounded-xl text-slate-600 border border-slate-100">
                                <span>Total Available: <strong class="text-slate-800">${product.totalUnits || 1}</strong></span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Update Select Options
            if (select) {
                select.innerHTML = '<option value="">Select product</option>' + product.map(product =>
                    `<option value="${product.id}" data-rate="${product.dailyRate}">${product.name} - ₱${Number(product.dailyRate || 0).toLocaleString()}/day</option>`
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
                alert('Please select a product.');
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
