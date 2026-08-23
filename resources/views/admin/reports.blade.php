@extends('layouts.app')

@section('title', 'Reports - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />
        <x-page-header eyebrow="PSARECO Enterprise Reports" title="Enterprise Reports" description="Comprehensive financial summaries, equipment utilization, and maintenance logs" icon="fa-solid fa-chart-line" />


        <!-- Main Card Wrapper -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden" x-data="{ activeTab: 'financial' }">

            <!-- Controls & Tab Header (Hidden during Print) -->
            <div class="p-5 border-b border-slate-100 space-y-5 print:hidden">

                <!-- Date Filters & Global Action Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="startDate" class="block text-xs font-semibold text-slate-600 mb-1">Start Date</label>
                        <input type="date" id="startDate" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label for="endDate" class="block text-xs font-semibold text-slate-600 mb-1">End Date</label>
                        <input type="date" id="endDate" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-3">
                        <button onclick="refreshReports()" class="flex-1 inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-rotate-right"></i> Generate Report
                        </button>

                        <button onclick="window.print()" class="flex-1 inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-print"></i> Print / Export PDF
                        </button>
                    </div>
                </div>

                <!-- Modern Tab Navigation -->
                <div class="border-b border-slate-100">
                    <nav class="flex space-x-6 text-xs font-semibold" aria-label="Tabs">
                        <button
                            @click="activeTab = 'financial'"
                            :class="activeTab === 'financial' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                            class="py-3 px-1 border-b-2 flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-coins"></i> Financial
                        </button>

                        <button
                            @click="activeTab = 'utilization'"
                            :class="activeTab === 'utilization' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                            class="py-3 px-1 border-b-2 flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-chart-column"></i> Utilization
                        </button>

                        <button
                            @click="activeTab = 'maintenance'"
                            :class="activeTab === 'maintenance' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                            class="py-3 px-1 border-b-2 flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-wrench"></i> Maintenance
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Contents Container -->
            <div class="p-5">
                <!-- Financial Report -->
                <div x-show="activeTab === 'financial'" id="financialReportContent">
                    <div class="py-12 text-center text-slate-400 text-xs">Loading financial report...</div>
                </div>

                <!-- Utilization Report -->
                <div x-show="activeTab === 'utilization'" id="utilizationReportContent" x-cloak>
                    <div class="py-12 text-center text-slate-400 text-xs">Loading utilization report...</div>
                </div>

                <!-- Maintenance Report -->
                <div x-show="activeTab === 'maintenance'" id="maintenanceReportContent" x-cloak>
                    <div class="py-12 text-center text-slate-400 text-xs">Loading maintenance report...</div>
                </div>
            </div>

        </div>

    </main>

@endsection

@push('scripts')
    <script>
        function loadReportsPage() {
            if (typeof requireAuth === 'function' && !requireAuth()) return;

            if (typeof getCurrentUser === 'function') {
                const user = getCurrentUser();
                if (user && user.role === 'farmer') {
                    alert('Access restricted');
                    window.location.href = '{{ route('dashboard') }}';
                    return;
                }
            }

            if (typeof loadSidebar === 'function') loadSidebar();

            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');

            if (startInput) startInput.value = firstDay.toISOString().split('T')[0];
            if (endInput) endInput.value = lastDay.toISOString().split('T')[0];

            refreshReports();
        }

        function refreshReports() {
            const start = document.getElementById('startDate')?.value;
            const end = document.getElementById('endDate')?.value;

            if (start && end) {
                generateFinancialReport(start, end);
                generateUtilizationReport();
                generateMaintenanceReport();
            }
        }

        function generateFinancialReport(startDate, endDate) {
            const salesData = typeof getSalesByDateRange === 'function' ? getSalesByDateRange(startDate, endDate) : [];
            const fertilizerSales = typeof getFertilizerSales === 'function' ? getFertilizerSales(salesData) : [];
            const pesticideSales = typeof getPesticideSales === 'function' ? getPesticideSales(salesData) : [];

            const fertSum = typeof getSalesSummary === 'function' ? getSalesSummary(fertilizerSales) : { totalSales: 0, totalCost: 0, totalProfit: 0 };
            const pestSum = typeof getSalesSummary === 'function' ? getSalesSummary(pesticideSales) : { totalSales: 0, totalCost: 0, totalProfit: 0 };
            const totalSum = typeof getSalesSummary === 'function' ? getSalesSummary(salesData) : { totalSales: 0, totalCost: 0, totalProfit: 0 };

            const fertMargin = fertSum.totalSales ? ((fertSum.totalProfit / fertSum.totalSales) * 100).toFixed(1) : 0;
            const pestMargin = pestSum.totalSales ? ((pestSum.totalProfit / pestSum.totalSales) * 100).toFixed(1) : 0;
            const totalMargin = totalSum.totalSales ? ((totalSum.totalProfit / totalSum.totalSales) * 100).toFixed(1) : 0;

            const container = document.getElementById('financialReportContent');
            if (!container) return;

            container.innerHTML = `
                <!-- Stats Summary Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 print:hidden">
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-t-4 border-t-emerald-500">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Sales</span>
                        <h3 class="text-2xl font-extrabold text-slate-800 my-1">₱${totalSum.totalSales.toLocaleString()}</h3>
                        <p class="text-xs text-slate-500">Cost: ₱${totalSum.totalCost.toLocaleString()} &bull; Profit: <span class="text-emerald-600 font-semibold">₱${totalSum.totalProfit.toLocaleString()}</span></p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-t-4 border-t-indigo-500">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Fertilizer Sales</span>
                        <h3 class="text-2xl font-extrabold text-slate-800 my-1">₱${fertSum.totalSales.toLocaleString()}</h3>
                        <p class="text-xs text-slate-500">Cost: ₱${fertSum.totalCost.toLocaleString()} &bull; Profit: <span class="text-indigo-600 font-semibold">₱${fertSum.totalProfit.toLocaleString()}</span></p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-t-4 border-t-amber-500">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pesticide Sales</span>
                        <h3 class="text-2xl font-extrabold text-slate-800 my-1">₱${pestSum.totalSales.toLocaleString()}</h3>
                        <p class="text-xs text-slate-500">Cost: ₱${pestSum.totalCost.toLocaleString()} &bull; Profit: <span class="text-amber-600 font-semibold">₱${pestSum.totalProfit.toLocaleString()}</span></p>
                    </div>
                </div>

                <!-- Breakdown Table -->
                <div class="w-full overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                                <th class="py-2.5 px-4">Category</th>
                                <th class="py-2.5 px-4">Total Sales</th>
                                <th class="py-2.5 px-4">Total Cost</th>
                                <th class="py-2.5 px-4">Gross Profit</th>
                                <th class="py-2.5 px-4">Profit Margin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-800">Fertilizers</td>
                                <td class="py-3 px-4 font-medium">₱${fertSum.totalSales.toLocaleString()}</td>
                                <td class="py-3 px-4 text-slate-500">₱${fertSum.totalCost.toLocaleString()}</td>
                                <td class="py-3 px-4 font-semibold text-emerald-600">₱${fertSum.totalProfit.toLocaleString()}</td>
                                <td class="py-3 px-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">${fertMargin}%</span></td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-800">Pesticides</td>
                                <td class="py-3 px-4 font-medium">₱${pestSum.totalSales.toLocaleString()}</td>
                                <td class="py-3 px-4 text-slate-500">₱${pestSum.totalCost.toLocaleString()}</td>
                                <td class="py-3 px-4 font-semibold text-emerald-600">₱${pestSum.totalProfit.toLocaleString()}</td>
                                <td class="py-3 px-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800">${pestMargin}%</span></td>
                            </tr>
                            <tr class="bg-slate-50/80 font-bold text-slate-900">
                                <td class="py-3.5 px-4">TOTAL</td>
                                <td class="py-3.5 px-4">₱${totalSum.totalSales.toLocaleString()}</td>
                                <td class="py-3.5 px-4 text-slate-600">₱${totalSum.totalCost.toLocaleString()}</td>
                                <td class="py-3.5 px-4 text-emerald-600">₱${totalSum.totalProfit.toLocaleString()}</td>
                                <td class="py-3.5 px-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white">${totalMargin}%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }

        function generateUtilizationReport() {
            const report = typeof getMachineUtilizationReport === 'function' ? getMachineUtilizationReport() : [];
            const container = document.getElementById('utilizationReportContent');
            if (!container) return;

            if (report.length === 0) {
                container.innerHTML = `<div class="py-12 text-center text-slate-400 text-xs">No machine utilization data found</div>`;
                return;
            }

            container.innerHTML = `
                <div class="w-full overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                                <th class="py-2.5 px-4">Machine</th>
                                <th class="py-2.5 px-4">Status</th>
                                <th class="py-2.5 px-4">Total Units</th>
                                <th class="py-2.5 px-4">Currently Booked</th>
                                <th class="py-2.5 px-4">Cumulative (Unit-Days)</th>
                                <th class="py-2.5 px-4">Utilization Rate</th>
                                <th class="py-2.5 px-4">Performance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            ${report.map(machine => {
                                let badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                                let perfLabel = 'Low';

                                if (machine.utilizationRate > 70) {
                                    badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                    perfLabel = 'High';
                                } else if (machine.utilizationRate > 30) {
                                    badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                                    perfLabel = 'Medium';
                                }

                                return `
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-4 font-semibold text-slate-800">${machine.name}</td>
                                        <td class="py-3 px-4 text-slate-600">${machine.status}</td>
                                        <td class="py-3 px-4">${machine.totalUnits}</td>
                                        <td class="py-3 px-4">${machine.currentBooked}</td>
                                        <td class="py-3 px-4">${machine.cumulativeBookedDays}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">${machine.utilizationRate}%</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border ${badgeClass}">
                                                ${perfLabel}
                                            </span>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function generateMaintenanceReport() {
            const machines = typeof getMachines === 'function' ? getMachines() : [];
            const underMaint = typeof getMachinesUnderMaintenance === 'function' ? getMachinesUnderMaintenance() : [];
            const overdue = typeof getMachinesOverdueMaintenance === 'function' ? getMachinesOverdueMaintenance(90) : [];
            const totalCost = typeof getTotalMaintenanceCost === 'function' ? getTotalMaintenanceCost() : 0;

            const container = document.getElementById('maintenanceReportContent');
            if (!container) return;

            container.innerHTML = `
                <!-- Stats Summary Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 print:hidden">
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-t-4 border-t-amber-500">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Under Maintenance</span>
                        <h3 class="text-2xl font-extrabold text-slate-800 my-1">${underMaint.length}</h3>
                        <p class="text-xs text-slate-500">Machines currently being repaired</p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-t-4 border-t-red-500">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Overdue Maintenance</span>
                        <h3 class="text-2xl font-extrabold text-slate-800 my-1">${overdue.length}</h3>
                        <p class="text-xs text-slate-500">No service in last 90 days</p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-t-4 border-t-sky-500">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Service Expenses</span>
                        <h3 class="text-2xl font-extrabold text-slate-800 my-1">₱${totalCost.toLocaleString()}</h3>
                        <p class="text-xs text-slate-500">Cumulative repair costs</p>
                    </div>
                </div>

                <!-- Table -->
                <div class="w-full overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                                <th class="py-2.5 px-4">Machine</th>
                                <th class="py-2.5 px-4">Status</th>
                                <th class="py-2.5 px-4">Last Maintenance</th>
                                <th class="py-2.5 px-4">Total Cost</th>
                                <th class="py-2.5 px-4">Maintenance History</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            ${machines.map(machine => {
                                const cost = typeof getMaintenanceCost === 'function' ? getMaintenanceCost(machine.id) : 0;
                                const records = (machine.maintenanceRecords || []).map(record => `
                                    <div class="text-[11px] text-slate-600 mb-1">
                                        <span class="font-semibold text-slate-800">${new Date(record.date).toLocaleDateString()}</span>: ${record.description} &bull; <span class="font-medium text-slate-700">₱${Number(record.cost).toLocaleString()}</span>
                                    </div>
                                `).join('') || '<span class="text-slate-400">—</span>';

                                return `
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-4 font-semibold text-slate-800">${machine.name}</td>
                                        <td class="py-3 px-4 text-slate-600">${machine.status}</td>
                                        <td class="py-3 px-4 text-slate-500">${machine.lastMaintenanceDate ? new Date(machine.lastMaintenanceDate).toLocaleDateString() : 'Never'}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">₱${Number(cost).toLocaleString()}</td>
                                        <td class="py-3 px-4">${records}</td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        document.addEventListener('DOMContentLoaded', loadReportsPage);
    </script>
@endpush
