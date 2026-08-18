@extends('layouts.app')

@section('title', 'Machinery Scheduling - PSARECO')

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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col mb-6">
            <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-tractor text-emerald-600 text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Book Status</h3>
                </div>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    id="fertilizerCount">0</span>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Machinery Rented</th>
                            <th class="py-2.5 px-4">Start Date</th>
                            <th class="py-2.5 px-4">End Date</th>
                            <th class="py-2.5 px-4">Total Days</th>
                            <th class="py-2.5 px-4">Cost Price</th>
                            <th class="py-2.5 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody id="fertilizersTableBody" class="divide-y divide-slate-100 text-xs text-slate-700">
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">Loading List...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </main>

@endsection

@push('scripts')

@endpush
