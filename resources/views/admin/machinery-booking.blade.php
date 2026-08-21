@extends('layouts.app')

@section('title', 'Machinery Booking - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />


        <section
            class="bg-gradient-to-r from-[#2c7a56] to-[#40a072] text-white rounded-2xl p-6 mb-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-tractor"></i> Machinery Management
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">Manage machinery inventory, track daily rental rates, and
                    monitor agricultural fleet availability</p>
            </div>

        </section>

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

                    <div class="relative w-full sm:w-64">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>

                        <input id="bookingSearchInput" type="text" placeholder="Search farmer or machinery..."
                            class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                </div>

                <div class="flex items-center gap-1.5 mt-5 overflow-x-auto pb-1" id="statusTabs">

                    <button type="button" data-status="pending"
                        class="status-tab inline-flex shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold transition">
                        <i class="fa-solid fa-clock text-[11px]"></i>
                        Pending
                        <span class="tab-count px-1.5 py-0.5 rounded-full bg-amber-100 text-[10px] font-bold">
                            {{ $bookings->where('status', 'Pending')->count() }}
                        </span>
                    </button>

                    <button type="button" data-status="approved"
                        class="status-tab inline-flex shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-slate-500 border border-transparent hover:bg-emerald-50 hover:text-emerald-700 text-xs font-semibold transition">
                        <i class="fa-solid fa-circle-check text-[11px]"></i>
                        Approved
                        <span
                            class="tab-count px-1.5 py-0.5 rounded-full bg-emerald-100 text-slate-500 text-[10px] font-bold">
                            {{ $bookings->where('status', 'Approved')->count() }}
                        </span>
                    </button>

                    <button type="button" data-status="completed"
                        class="status-tab inline-flex shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-slate-500 border border-transparent hover:bg-emerald-50 hover:text-emerald-700 text-xs font-semibold transition">
                        <i class="fa-solid fa-flag-checkered text-[11px]"></i>
                        Completed
                        <span class="tab-count px-1.5 py-0.5 rounded-full bg-emerald-400 text-white text-[10px] font-bold">
                            {{ $bookings->where('status', 'Completed')->count() }}
                        </span>
                    </button>

                    <button type="button" data-status="cancelled"
                        class="status-tab inline-flex shrink-0 items-center gap-1.5 px-3 py-2 rounded-xl text-slate-500 border border-transparent hover:bg-red-50 hover:text-red-700 text-xs font-semibold transition">
                        <i class="fa-solid fa-circle-xmark text-[11px]"></i>
                        Cancelled
                        <span class="tab-count px-1.5 py-0.5 rounded-full bg-red-100 text-slate-500 text-[10px] font-bold">
                            {{ $bookings->where('status', 'Cancelled')->count() }}
                        </span>
                    </button>

                </div>
            </div>


            <div class="overflow-x-auto">
                <table class="w-full text-left">
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


                    <tbody class="divide-y divide-slate-100" id="bookingsTableBody">

                        @foreach ($bookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition" data-status="{{ strtolower($booking->status) }}"
                                data-search="{{ strtolower($booking->user->name . ' ' . $booking->machine->machinery_name . ' ' . $booking->machine->model) }}">

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
                                        {{ '₱ ' . number_format($booking->total_amount, 2) }}
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
                                    @elseif ($booking->status === 'Cancelled')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-100 text-red-800 border border-red-200 text-[10px] font-bold">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            {{ $booking->status }}
                                        </span>
                                    @endif

                                </td>


                                <td class="px-5 py-4 text-center">

                                    <form id="approve-form-{{ $booking->id }}"
                                        action="{{ route('officer.approve-booking', $booking->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>

                                    <button type="button"
                                        class="inline-flex items-center gap-1 bg-white hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 font-medium text-base py-1.5 px-3 rounded-lg border border-slate-200 hover:border-emerald-200 shadow-sm transition-all duration-150"
                                        title="Approved Booking"
                                        onclick="approvedSubmit('approve-form-{{ $booking->id }}')">
                                        <i class="fa-solid fa-check text-base"></i>
                                    </button>

                                    <button type="button"
                                        class="inline-flex items-center gap-1 bg-white hover:bg-rose-50 text-red-600 hover:text-rose-700 font-medium text-base py-1.5 px-3 rounded-lg border border-slate-200 hover:border-rose-200 shadow-sm transition-all duration-150"
                                        title="Reject Booking" onclick="rejectSubmit()">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>

                                </td>

                            </tr>
                        @endforeach

                        {{-- Add data-status="rented" / "completed" / "cancelled" to future rows the same way --}}

                        {{-- Empty State --}}
                        <tr id="emptyState" class="hidden">
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
                                        There are no bookings under this status.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- Footer / Pagination --}}
            <div class="px-5 py-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">

                <p class="text-[11px] text-slate-400">
                    Showing
                    <span class="font-semibold text-slate-600">1–2</span>
                    of
                    <span class="font-semibold text-slate-600">4</span>
                    bookings
                </p>

                <div class="flex items-center gap-1">

                    <button type="button"
                        class="h-8 w-8 rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>

                    <button type="button" class="h-8 w-8 rounded-lg bg-emerald-600 text-white text-xs font-bold">
                        1
                    </button>

                    <button type="button"
                        class="h-8 w-8 rounded-lg border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                        2
                    </button>

                    <button type="button"
                        class="h-8 w-8 rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>

                </div>

            </div>

        </div>



    </main>

@endsection

@push('scripts')
    <script>
        function approvedSubmit(formId) {
            Swal.fire({
                title: 'Are you sure you want to approve this booking?',
                text: "You won't be able to undo this action!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {

            const tabs = document.querySelectorAll('.status-tab');
            const rows = document.querySelectorAll('#bookingsTableBody tr[data-status]');
            const emptyState = document.getElementById('emptyState');
            const searchInput = document.getElementById('bookingSearchInput');

            let activeStatus = 'pending';
            let searchTerm = '';

            const activeClasses = {
                pending: ['bg-amber-50', 'text-amber-700', 'border-amber-200'],
                approved: ['bg-emerald-50', 'text-emerald-700', 'border-emerald-200'],
                rented: ['bg-emerald-50', 'text-emerald-700', 'border-emerald-200'],
                completed: ['bg-emerald-50', 'text-emerald-700', 'border-emerald-200'],
                cancelled: ['bg-red-50', 'text-red-700', 'border-red-200']
            };

            const inactiveClasses = [
                'text-slate-500',
                'border-transparent'
            ];

            const allColorClasses = [
                'bg-amber-50',
                'text-amber-700',
                'border-amber-200',

                'bg-emerald-50',
                'text-emerald-700',
                'border-emerald-200',

                'bg-red-50',
                'text-red-700',
                'border-red-200',

                'text-slate-500',
                'border-transparent'
            ];

            function paintTabs() {

                tabs.forEach(tab => {

                    const status = tab.dataset.status;

                    tab.classList.remove(...allColorClasses);

                    if (status === activeStatus) {

                        tab.classList.add(...activeClasses[status]);

                    } else {

                        tab.classList.add(...inactiveClasses);

                    }

                });

            }

            function applyFilters() {

                let visibleCount = 0;

                rows.forEach(row => {

                    const rowStatus = row.dataset.status.toLowerCase();

                    const rowSearch = (row.dataset.search || '').toLowerCase();

                    const matchesStatus = rowStatus === activeStatus;

                    const matchesSearch =
                        searchTerm === '' ||
                        rowSearch.includes(searchTerm);

                    if (matchesStatus && matchesSearch) {

                        row.classList.remove('hidden');

                        visibleCount++;

                    } else {

                        row.classList.add('hidden');

                    }

                });

                if (emptyState) {

                    emptyState.classList.toggle(
                        'hidden',
                        visibleCount !== 0
                    );

                }

            }

            tabs.forEach(tab => {

                tab.addEventListener('click', function() {

                    activeStatus = this.dataset.status.toLowerCase();

                    paintTabs();

                    applyFilters();

                });

            });

            if (searchInput) {

                searchInput.addEventListener('input', function() {

                    searchTerm = this.value.trim().toLowerCase();

                    applyFilters();

                });

            }

            paintTabs();

            applyFilters();

        });
    </script>
@endpush
