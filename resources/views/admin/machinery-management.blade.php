@extends('layouts.app')

@section('title', 'Machinery Management - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <x-page-header eyebrow="PSARECO Machinery Management" title="Machinery Management" description="Manage machinery inventory, registration, and machinery details" icon="fa-solid fa-tractor" />

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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 mb-6 print:hidden" id="machineryForm">

            {{-- Header --}}
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-tractor text-emerald-600"></i>
                    Add New Machinery
                </h3>

                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                    Machinery Registration
                </span>
            </div>

            <form action="{{ route('machinery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Changed items-end to items-start so text inputs align naturally -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-start">

                    {{-- --- ROW 1 --- --}}

                    {{-- Machinery Name --}}
                    <div class="sm:col-span-4">
                        <label for="machinery_name" class="block text-xs font-semibold text-slate-600 mb-1">
                            Machinery Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="machinery_name" name="machinery_name" placeholder="e.g. Tractor" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    {{-- Model --}}
                    <div class="sm:col-span-3">
                        <label for="model" class="block text-xs font-semibold text-slate-600 mb-1">
                            Model <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="model" name="model" placeholder="e.g. John Deere 5075E" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    {{-- Total Unit --}}
                    <div class="sm:col-span-2">
                        <label for="serial_number" class="block text-xs font-semibold text-slate-600 mb-1">
                            Serial Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="serial_number" name="serial_number"  placeholder="e.g. TR-001" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    {{-- Cost Per Hour --}}
                    <div class="sm:col-span-3">
                        <label for="rent_per_day" class="block text-xs font-semibold text-slate-600 mb-1">
                            Rent Per Hour <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-500">
                                ₱
                            </span>
                            <input type="number" id="rent_per_day" name="price" min="0" step="0.01"
                                placeholder="0.00" required
                                class="w-full pl-7 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        </div>
                    </div>


                    {{-- --- ROW 2 --- --}}

                    {{-- Upload Image (Takes left side of row 2) --}}
                    <div class="sm:col-span-6">
                        <label for="image" class="block text-xs font-semibold text-slate-600 mb-1">
                            Upload Image
                        </label>
                        <div class="flex flex-col gap-2">
                            <label for="image"
                                class="w-full flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100 transition">
                                <i class="fa-solid fa-cloud-arrow-up text-emerald-600"></i>
                                <span id="fileName" class="text-xs text-slate-500 truncate">
                                    Choose machinery image
                                </span>
                                <input type="file" id="image" name="image_path" accept="image/*" class="hidden"
                                    onchange="previewMachineryImage(event)">
                            </label>

                            {{-- Image container limits width and keeps it looking tidy --}}
                            <div class="mt-1 max-w-[200px]">
                                <img id="imagePreview" src="#" alt="Preview"
                                    class="hidden w-full max-h-32 rounded-xl border border-slate-200 object-cover">
                            </div>
                        </div>
                    </div>

                    {{-- Status (Right side next to image upload) --}}
                    <div class="sm:col-span-3">
                        <label for="status" class="block text-xs font-semibold text-slate-600 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="Available">Available</option>
                            <option value="Reserved">Reserved</option>
                            <option value="In Use">In Use</option>
                            <option value="Under Maintenance">Under Maintenance</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>

                    {{-- Submit Button (Perfectly aligns next to status and matches height style) --}}
                    <div class="sm:col-span-3 self-start sm:mt-5">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-plus"></i>
                            Add Machinery
                        </button>
                    </div>

                </div>
            </form>
        </div>



        <!-- Machinery Fleet List & Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-tractor text-emerald-600"></i> Machinery Fleet
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800"
                        id="totalMachinesCount">0</span>
                </h3>

                <!-- Search Machinery Input -->
                <div class="relative w-full sm:w-64 print:hidden">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchMachinery" placeholder="Search machinery..."
                        onkeyup="filterMachinery()"
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
            allMachinery = @json($machineries);

            allMachinery = allMachinery.map(machine => {
                return {
                    id: machine.id,
                    name: machine.machinery_name,
                    model: machine.model,
                    dailyRate: machine.price,
                    status: machine.status,
                    serialNumber: machine.serial_number,
                    bookedUnits: 0,
                    image: `{{ asset('storage') }}/${machine.image_path}`
                };
            });

            // 3. Render your freshly loaded database data into the UI
            renderMachineryList(allMachinery);
        }

        function renderMachineryList(machines) {
            const container = document.getElementById('machineryList');
            const select = document.getElementById('bookingMachine');
            const countBadge = document.getElementById('totalMachinesCount');

            if (countBadge) countBadge.textContent = machines.length;

            if (!container) return;

            if (machines.length === 0) {
                container.innerHTML =
                    '<div class="col-span-full text-center text-slate-400 py-10 text-xs">No machinery matching criteria.</div>';
                if (select) select.innerHTML = '<option value="">No machinery available</option>';
                return;
            }

            // Render Machinery Grid Cards
            container.innerHTML = machines.map(machine => {
                const rate = Number(machine.dailyRate || 0);
                const placeholder =
                    `https://placehold.co/300x200/f1f5f9/334155?text=${encodeURIComponent(machine.name)}`;

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
                            <p class="text-[11px] text-slate-400 mb-2">${machine.model || 'Model N/A'} - ${machine.serialNumber || 'Serial N/A'}</p>
                                <div class="text-sm font-extrabold text-emerald-700 mb-3">
                                    ₱${rate.toLocaleString()} <span class="text-[10px] font-normal text-slate-500">/ Hour</span>
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

        function previewMachineryImage(event) {
            const input = event.target;
            const fileNameSpan = document.getElementById('fileName');
            const previewImg = document.getElementById('imagePreview');

            // Check if the user actually selected a file
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // 1. Update the text to show the actual filename
                fileNameSpan.textContent = file.name;
                fileNameSpan.classList.remove('text-slate-500');
                fileNameSpan.classList.add('text-slate-800', 'font-medium'); // Make it look active

                // 2. Generate the preview URL and unhide the image tag
                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('hidden');
            } else {
                // Reset if they cancel or clear the selection
                fileNameSpan.textContent = "Choose machinery image";
                fileNameSpan.classList.add('text-slate-500');
                fileNameSpan.classList.remove('text-slate-800', 'font-medium');

                previewImg.src = "#";
                previewImg.classList.add('hidden');
            }
        }
    </script>
@endpush
