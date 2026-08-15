@extends('layouts.app')
@section('title', 'Dashboard - PSARECO')

@section('content')

        <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 overflow-y-auto transition-all duration-300">

            <!-- Mobile Navigation Trigger Bar -->
            <div class="flex items-center justify-between mb-4 lg:hidden bg-white/60 backdrop-blur p-3 rounded-xl shadow-sm border border-emerald-100">
                <span class="font-bold text-emerald-950 text-sm">PSARECO System</span>
                <button @click="mobileOpen = !mobileOpen" class="p-2 text-emerald-800 hover:bg-emerald-100 rounded-lg">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>

            <!-- Hero Welcome Banner -->
            <section class="bg-gradient-to-r from-[#2c7a56] to-[#40a072] text-white rounded-2xl p-6 mb-6 shadow-sm">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight">Welcome back, {{ $userName ?? 'Mike' }}!</h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">Manage your farm resources efficiently with PSARECO Enterprise System</p>
            </section>

            <!-- Metrics / KPI Cards Grid -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-indigo-500 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-boxes-stacked text-indigo-500 text-2xl mb-1"></i>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">{{ $totalInventory ?? '4' }}</span>
                    <p class="text-xs font-medium text-slate-400">Total Inventory Items</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-amber-400 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-hourglass-half text-amber-400 text-2xl mb-1"></i>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">{{ $expiringCount ?? '0' }}</span>
                    <p class="text-xs font-medium text-slate-400">Expiring Soon (&lt;30 days)</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-red-500 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl mb-1"></i>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">{{ $lowStockCount ?? '0' }}</span>
                    <p class="text-xs font-medium text-slate-400">Low Stock Items</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-emerald-500 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-chart-line text-emerald-500 text-2xl mb-1"></i>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">₱{{ number_format($totalSales ?? 0, 2) }}</span>
                    <p class="text-xs font-medium text-slate-400">Total Sales</p>
                </div>
                <!-- Card 5 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border-t-4 border-sky-400 text-center flex flex-col items-center justify-between transition-transform hover:-translate-y-0.5">
                    <i class="fa-regular fa-clock text-sky-400 text-2xl mb-1"></i>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 my-1">{{ $pendingBookings ?? '0' }}</span>
                    <p class="text-xs font-medium text-slate-400">Pending Bookings</p>
                </div>
            </section>

            <!-- Dashboard Tables Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Recent Sales Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col">
                    <div class="px-5 py-4 flex items-center space-x-2 border-b border-slate-100">
                        <i class="fa-solid fa-rotate-left text-slate-600 text-sm"></i>
                        <h3 class="font-bold text-slate-700 text-sm">Recent Sales Transactions</h3>
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
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-400">No sales recorded</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Upcoming Bookings Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col">
                    <div class="px-5 py-4 flex items-center space-x-2 border-b border-slate-100">
                        <i class="fa-regular fa-calendar-days text-emerald-700 text-sm"></i>
                        <h3 class="font-bold text-slate-700 text-sm">Upcoming Bookings</h3>
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
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-400">No upcoming bookings</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Alerts & Chart Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Alerts -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 flex flex-col min-h-[180px]">
                    <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                        <i class="fa-regular fa-bell text-emerald-700 text-sm"></i>
                        <h3 class="font-bold text-slate-700 text-sm">Low Stock & Expiring Alerts</h3>
                    </div>
                    <div class="flex-1 flex items-center justify-center">
                        <div class="flex items-center space-x-2 text-slate-500 text-xs font-medium">
                            <div class="w-4 h-4 rounded-full bg-slate-700 text-white flex items-center justify-center text-[10px]">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span>No alerts</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Sales Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 flex flex-col min-h-[180px]">
                    <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                        <i class="fa-solid fa-chart-line text-emerald-700 text-sm"></i>
                        <h3 class="font-bold text-slate-700 text-sm">Monthly Sales Trend</h3>
                    </div>
                    <div class="flex-1 flex items-center justify-center text-slate-400 text-xs">
                        <canvas id="salesTrendChart" class="w-full max-h-[140px]"></canvas>
                    </div>
                </div>
            </div>

        </main>

@endsection
