@extends('layouts.app')

@section('title', 'Dashboard - PSARECO')

@section('content')

<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
    <x-dashboard-header />
    <x-page-header eyebrow="Dashboard" title="Welcome Back {{ auth()->user()->name ?? 'User' }}!" description="Manage your farm resources efficiently with PSARECO Enterprise System" icon="fa-solid fa-chart-line" />

    @role('admin|officer')
        <section class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-indigo-500 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                <i class="fa-solid fa-boxes-stacked text-indigo-500 text-2xl mb-1"></i>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">
                    {{ $totalInventory }}
                </span>
                <p class="text-xs font-medium text-slate-400">
                    Total Inventory Items
                </p>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-amber-400 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                <i class="fa-solid fa-hourglass-half text-amber-400 text-2xl mb-1"></i>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">
                    {{ $expiringCount }}
                </span>
                <p class="text-xs font-medium text-slate-400">
                    Expiring Soon (&lt;30 days)
                </p>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-red-500 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl mb-1"></i>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">
                    {{ $lowStockCount }}
                </span>
                <p class="text-xs font-medium text-slate-400">
                    Low Stock Items
                </p>
            </div>


            <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-emerald-500 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                <i class="fa-solid fa-chart-line text-emerald-500 text-2xl mb-1"></i>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">
                    ₱{{ number_format($totalSales, 2) }}
                </span>
                <p class="text-xs font-medium text-slate-400">
                    Total Sales
                </p>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-sky-400 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                <i class="fa-regular fa-clock text-sky-400 text-2xl mb-1"></i>
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">
                    {{ $pendingBookings }}
                </span>
                <p class="text-xs font-medium text-slate-400">
                    Pending Bookings
                </p>
            </div>
        </section>
    @endrole



    {{-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 flex items-center space-x-2 border-b border-slate-100">
                <i class="fa-solid fa-rotate-left text-slate-600 text-sm"></i>
                <h3 class="font-bold text-slate-700 text-sm">
                    Recent Sales Transactions
                </h3>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Date</th>
                            <th class="py-2.5 px-4">Product</th>
                            <th class="py-2.5 px-4">Quantity</th>
                            <th class="py-2.5 px-4">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($recentSales as $sale)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap text-slate-600">{{ $sale->sale_date?->format('M d, Y') }}</td>
                                <td class="py-3 px-4 font-medium text-slate-700">{{ $sale->product?->name ?? 'Unknown Product' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $sale->quantity }}</td>
                                <td class="py-3 px-4 font-semibold text-emerald-700">₱{{ number_format($sale->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400">No sales recorded</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 flex items-center space-x-2 border-b border-slate-100">
                <i class="fa-regular fa-calendar-days text-emerald-700 text-sm"></i>
                <h3 class="font-bold text-slate-700 text-sm">
                    Upcoming Bookings
                </h3>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Date</th>
                            <th class="py-2.5 px-4">Machine</th>
                            <th class="py-2.5 px-4">Farmer</th>
                            <th class="py-2.5 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($upcomingBookings as $booking)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-700">
                                        {{ $booking->start_date?->format('M d, Y') }}
                                    </div>
                                    @if ($booking->end_date && $booking->end_date->ne($booking->start_date))
                                        <div class="text-[10px] text-slate-400">
                                            to {{ $booking->end_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-medium text-slate-700">
                                        {{ $booking->machine?->name ?? 'Unknown Machine' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-slate-600">
                                        {{ $booking->user?->name ?? 'Unknown User' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $status = strtolower($booking->status ?? 'pending');
                                        $statusClasses = match ($status) {
                                            'approved' => 'bg-emerald-100 text-emerald-700',
                                            'confirmed' => 'bg-emerald-100 text-emerald-700',
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'cancelled' => 'bg-slate-100 text-slate-600',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'declined' => 'bg-red-100 text-red-700',
                                            default => 'bg-sky-100 text-sky-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold uppercase {{ $statusClasses }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-regular fa-calendar-xmark text-2xl mb-2"></i>
                                        <span>
                                            No upcoming bookings
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}

    @role('admin|officer')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 flex flex-col min-h-[180px]">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <i class="fa-regular fa-bell text-emerald-700 text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">
                        Low Stock & Expiring Alerts
                    </h3>
                </div>

                <div class="space-y-2">
                    @foreach ($lowStockItems as $item)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-700">
                                        {{ $item->name }}
                                    </p>
                                    <p class="text-[10px] text-red-600">
                                        Low stock
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-red-600">
                                    {{ number_format($item->quantity, 2) }} {{ $item->unit }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    Reorder at {{ number_format($item->reorder_level, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    @foreach ($expiringItems as $item)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50 border border-amber-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-hourglass-half text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-700">
                                        {{ $item->name }}
                                    </p>
                                    <p class="text-[10px] text-amber-600">
                                        Expiring soon
                                    </p>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-xs font-bold text-amber-600">
                                    {{ $item->expiration_date->format('M d, Y') }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    {{ $item->expiration_date->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach


                    @if ($lowStockItems->isEmpty() && $expiringItems->isEmpty())
                        <div class="flex items-center justify-center py-8">
                            <div class="flex items-center space-x-2 text-slate-500 text-xs font-medium">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span>
                                    No alerts
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 flex flex-col min-h-[180px]">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <i class="fa-solid fa-chart-line text-emerald-700 text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Monthly Sales Trend</h3>
                </div>

                <div class="h-[180px] w-full">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>
        </div>
    @endrole

@role('farmer')
    <!-- Stat Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-emerald-500 flex items-center justify-between transition-all hover:-translate-y-0.5 hover:shadow-md">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Bookings</p>
                <span class="text-2xl sm:text-3xl font-black text-slate-800 mt-1 block">
                    {{ $myActiveBookingsCount }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-tractor"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-amber-400 flex items-center justify-between transition-all hover:-translate-y-0.5 hover:shadow-md">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Requests</p>
                <span class="text-2xl sm:text-3xl font-black text-slate-800 mt-1 block">
                    {{ $myPendingBookingsCount }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-regular fa-clock"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-sky-500 flex items-center justify-between transition-all hover:-translate-y-0.5 hover:shadow-md sm:col-span-2 lg:col-span-1">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Reservations</p>
                <span class="text-2xl sm:text-3xl font-black text-slate-800 mt-1 block">
                    {{ $myBookings->count() }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- My Bookings Table (Takes up 2 columns on large screens) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <i class="fa-regular fa-calendar-check text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">My Equipment Bookings</h3>
                </div>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 text-slate-500 text-[11px] uppercase tracking-wider font-semibold border-b border-slate-100">
                            <th class="py-3 px-4">Machine</th>
                            <th class="py-3 px-4">Schedule</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($myBookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 font-medium text-slate-800">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 text-xs">
                                            <i class="fa-solid fa-tractor"></i>
                                        </div>
                                        <span>{{ $booking->machine?->name ?? 'Unknown Machine' }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-700">
                                        {{ $booking->start_date?->format('M d, Y') }}
                                    </div>
                                    @if ($booking->end_date && $booking->end_date->ne($booking->start_date))
                                        <div class="text-[10px] text-slate-400 font-medium">
                                            to {{ $booking->end_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @php
                                        $status = strtolower($booking->status ?? 'pending');
                                        $statusClasses = match ($status) {
                                            'approved', 'confirmed' => 'bg-emerald-100/80 text-emerald-700 border-emerald-200',
                                            'pending' => 'bg-amber-100/80 text-amber-700 border-amber-200',
                                            'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            'rejected', 'declined' => 'bg-red-100/80 text-red-700 border-red-200',
                                            default => 'bg-sky-100/80 text-sky-700 border-sky-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $statusClasses }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mb-3 text-slate-300 text-xl">
                                            <i class="fa-solid fa-tractor"></i>
                                        </div>
                                        <p class="font-medium text-slate-500 text-xs">No equipment bookings found</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Click "New Booking" to reserve farm machinery.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Card Column -->
        <div class="bg-gradient-to-br from-emerald-800 to-teal-900 rounded-2xl shadow-sm p-6 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 text-emerald-700/20 text-9xl pointer-events-none">
                <i class="fa-solid fa-wheat-awn"></i>
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-200 backdrop-blur-md mb-4 border border-emerald-400/20">
                    Quick Reservation
                </span>
                <h4 class="text-xl font-bold mb-2">Need Equipment for Your Farm?</h4>
                <p class="text-emerald-100/80 text-xs leading-relaxed mb-6">
                    Reserve tractors, harvesters, and other essential tools easily. Track your approval status in real-time.
                </p>
            </div>
            <a href="{{ route('farmers.index') }}" class="inline-flex items-center justify-center space-x-2 w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold text-xs rounded-xl shadow-lg transition-all text-center">
                <i class="fa-solid fa-calendar-plus"></i>
                <span>Book Machinery Now</span>
            </a>
        </div>
    </div>
@endrole




</main>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('salesTrendChart');

        if (!canvas) return;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: @json($salesLabels),
                datasets: [{
                    label: 'Sales',
                    data: @json($salesData),
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return '₱' + Number(context.raw).toLocaleString('en-PH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 10
                            },
                            callback: function (value) {
                                return '₱' + Number(value).toLocaleString('en-PH');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
