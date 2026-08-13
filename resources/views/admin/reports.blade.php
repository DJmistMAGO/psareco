@extends('layouts.app')

@section('title', 'Reports - PSARECO')

@section('content')
    <div class="d-flex">


        <div class="main-content" style="margin-left: 250px; flex: 1; padding: 30px;">
            @include('components.breadcrumb', [
                'title' => 'Reports',
                'icon' => 'fas fa-chart-line'
            ])

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="bg-gray-100 p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-2">
            <i class="fas fa-chart-line text-blue-600 me-2"></i>PSARECO Enterprise Report
        </h3>
        <p class="text-gray-500 text-sm mb-0">Comprehensive Financial & Operational Summary</p>
    </div>

    <!-- Card Body -->
    <div class="p-6">
        <!-- Date Filters & Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 print:hidden no-print">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1" for="startDate">Start Date</label>
                <input type="date" id="startDate" class="w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1" for="endDate">End Date</label>
                <input type="date" id="endDate" class="w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end gap-2">
                <!-- Generate Button (Primary Blue) -->
                <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-1.5 px-3 rounded-md transition-colors duration-150 flex items-center justify-center" onclick="refreshReports()">
                    <i class="fas fa-sync-alt me-2"></i>Generate
                </button>
                <!-- Print / PDF Button (Success Green) -->
                <button class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1.5 px-3 rounded-md transition-colors duration-150 flex items-center justify-center" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print / PDF
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs flex border-b border-gray-200 mb-6 print:hidden no-print gap-2">
            <li class="nav-item">
                <button class="nav-link active font-medium px-4 py-2 text-sm text-gray-600 hover:text-blue-600 border-b-2 border-transparent focus:outline-none" data-bs-toggle="tab" data-bs-target="#financial">
                    <i class="fas fa-dollar-sign me-2"></i>Financial
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link font-medium px-4 py-2 text-sm text-gray-600 hover:text-blue-600 border-b-2 border-transparent focus:outline-none" data-bs-toggle="tab" data-bs-target="#utilization">
                    <i class="fas fa-chart-bar me-2"></i>Utilization
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link font-medium px-4 py-2 text-sm text-gray-600 hover:text-blue-600 border-b-2 border-transparent focus:outline-none" data-bs-toggle="tab" data-bs-target="#maintenance">
                    <i class="fas fa-tools me-2"></i>Maintenance
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content pt-2">
            <div class="tab-pane fade show active" id="financial">
                <div id="financialReportContent"></div>
            </div>
            <div class="tab-pane fade" id="utilization">
                <div id="utilizationReportContent"></div>
            </div>
            <div class="tab-pane fade" id="maintenance">
                <div id="maintenanceReportContent"></div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        function loadReportsPage() {
            if (!requireAuth()) return;
            const user = getCurrentUser();
            if (user.role === 'farmer') {
                alert('Access restricted');
                window.location.href = '{{ route('dashboard') }}';
                return;
            }
            if (typeof loadSidebar === 'function') loadSidebar();
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
            document.getElementById('endDate').value = lastDay.toISOString().split('T')[0];
            refreshReports();
        }

        function refreshReports() {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            if (start && end) {
                generateFinancialReport(start, end);
                generateUtilizationReport();
                generateMaintenanceReport();
            }
        }

        function generateFinancialReport(startDate, endDate) {
            const salesData = getSalesByDateRange(startDate, endDate);
            const fertilizerSales = getFertilizerSales(salesData);
            const pesticideSales = getPesticideSales(salesData);
            const fertSum = getSalesSummary(fertilizerSales);
            const pestSum = getSalesSummary(pesticideSales);
            const totalSum = getSalesSummary(salesData);

            document.getElementById('financialReportContent').innerHTML = `
                <div class="row g-4 mb-4 no-print">
                    <div class="col-md-4"><div class="stat-card"><h6>Total Sales</h6><h3 class="text-success">₱${totalSum.totalSales.toLocaleString()}</h3><small>Cost: ₱${totalSum.totalCost.toLocaleString()} | Profit: ₱${totalSum.totalProfit.toLocaleString()}</small></div></div>
                    <div class="col-md-4"><div class="stat-card"><h6>Fertilizer Sales</h6><h3 class="text-primary">₱${fertSum.totalSales.toLocaleString()}</h3><small>Cost: ₱${fertSum.totalCost.toLocaleString()} | Profit: ₱${fertSum.totalProfit.toLocaleString()}</small></div></div>
                    <div class="col-md-4"><div class="stat-card"><h6>Pesticide Sales</h6><h3 class="text-warning">₱${pestSum.totalSales.toLocaleString()}</h3><small>Cost: ₱${pestSum.totalCost.toLocaleString()} | Profit: ₱${pestSum.totalProfit.toLocaleString()}</small></div></div>
                </div>
                <div class="table-responsive mt-4"><table class="table table-bordered"><thead class="table-light"><tr><th>Category</th><th>Total Sales (₱)</th><th>Total Cost (₱)</th><th>Gross Profit (₱)</th><th>Margin (%)</th></tr></thead>
                <tbody>
                    <tr><td>Fertilizers</td><td>₱${fertSum.totalSales.toLocaleString()}</td><td>₱${fertSum.totalCost.toLocaleString()}</td><td>₱${fertSum.totalProfit.toLocaleString()}</td><td>${fertSum.totalSales ? ((fertSum.totalProfit / fertSum.totalSales) * 100).toFixed(1) : 0}%</td></tr>
                    <tr><td>Pesticides</td><td>₱${pestSum.totalSales.toLocaleString()}</td><td>₱${pestSum.totalCost.toLocaleString()}</td><td>₱${pestSum.totalProfit.toLocaleString()}</td><td>${pestSum.totalSales ? ((pestSum.totalProfit / pestSum.totalSales) * 100).toFixed(1) : 0}%</td></tr>
                    <tr class="table-active"><td><strong>TOTAL</strong></td><td><strong>₱${totalSum.totalSales.toLocaleString()}</strong></td><td><strong>₱${totalSum.totalCost.toLocaleString()}</strong></td><td><strong>₱${totalSum.totalProfit.toLocaleString()}</strong></td><td><strong>${totalSum.totalSales ? ((totalSum.totalProfit / totalSum.totalSales) * 100).toFixed(1) : 0}%</strong></td></tr>
                </tbody></table></div>
            `;
        }

        function generateUtilizationReport() {
            const report = getMachineUtilizationReport();
            document.getElementById('utilizationReportContent').innerHTML = `
                <div class="table-responsive"><table class="table table-bordered"><thead class="table-light"><tr><th>Machine</th><th>Status</th><th>Total Units</th><th>Currently Booked</th><th>Cumulative Booked (unit-days)</th><th>Utilization Rate</th><th>Performance</th></tr></thead><tbody>
                    ${report.map(machine => `
                        <tr>
                            <td>${machine.name}</td>
                            <td>${machine.status}</td>
                            <td>${machine.totalUnits}</td>
                            <td>${machine.currentBooked}</td>
                            <td>${machine.cumulativeBookedDays}</td>
                            <td>${machine.utilizationRate}%</td>
                            <td>${machine.utilizationRate > 70 ? '<span class="badge bg-success">High</span>' : (machine.utilizationRate > 30 ? '<span class="badge bg-warning">Medium</span>' : '<span class="badge bg-secondary">Low</span>')}</td>
                        </tr>
                    `).join('')}
                </tbody></table></div>
            `;
        }

        function generateMaintenanceReport() {
            const machines = getMachines();
            const underMaint = getMachinesUnderMaintenance();
            const overdue = getMachinesOverdueMaintenance(90);
            const totalCost = getTotalMaintenanceCost();

            document.getElementById('maintenanceReportContent').innerHTML = `
                <div class="row g-4 mb-4 no-print">
                    <div class="col-md-4"><div class="stat-card"><h6>Under Maintenance</h6><h3 class="text-warning">${underMaint.length}</h3><small>Machines currently being repaired</small></div></div>
                    <div class="col-md-4"><div class="stat-card"><h6>Overdue for Maintenance</h6><h3 class="text-danger">${overdue.length}</h3><small>No maintenance in last 90 days</small></div></div>
                    <div class="col-md-4"><div class="stat-card"><h6>Total Maintenance Cost</h6><h3 class="text-primary">₱${totalCost.toLocaleString()}</h3><small>All-time repair expenses</small></div></div>
                </div>
                <div class="table-responsive"><table class="table table-bordered"><thead class="table-light"><tr><th>Machine</th><th>Status</th><th>Last Maintenance</th><th>Total Cost (₱)</th><th>Maintenance Records</th></tr></thead><tbody>
                    ${machines.map(machine => {
                        const cost = getMaintenanceCost(machine.id);
                        const records = (machine.maintenanceRecords || []).map(record => `<div><small>${new Date(record.date).toLocaleDateString()}: ${record.description} - ₱${Number(record.cost).toLocaleString()}</small></div>`).join('') || '-';
                        return `<tr><td>${machine.name}</td><td>${machine.status}</td><td>${machine.lastMaintenanceDate ? new Date(machine.lastMaintenanceDate).toLocaleDateString() : 'Never'}</td><td class="text-end">₱${Number(cost).toLocaleString()}</td><td>${records}</td></tr>`;
                    }).join('')}
                </tbody></table></div>
            `;
        }

        document.addEventListener('DOMContentLoaded', loadReportsPage);
    </script> --}}
@endpush
