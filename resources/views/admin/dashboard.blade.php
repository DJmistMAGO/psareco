@extends('layouts.app')

@section('title', 'Dashboard - PSARECO')

@section('content')
    <div class="d-flex">

        <!-- Main Content -->
        <div class="main-content" style="margin-left: 220px; flex: 1; padding: 30px; transition: margin-left 0.3s ease;">
            <div id="backButtonContainer" style="display: none;">
                <button class="back-btn" onclick="goBackToDashboard()">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </button>
            </div>

            <div class="welcome-banner">
                <h3 class="mb-1 fw-bold" id="welcomeName">
                    <i class="fas fa-hand-wave"></i> Welcome back, {{ auth()->user()->name }}!
                </h3>
                <p class="mb-0 opacity-75">Manage your farm resources efficiently with PSARECO Enterprise System</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-4" id="statsContainer">
                <!-- Total Inventory Items Card -->
                <div class="h-full">
                    <div class="stat-card h-full bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-md cursor-pointer transition-transform duration-200 hover:-translate-y-1 border-l-4 border-indigo-600">
                        <div class="stat-icon mb-3 text-4xl text-indigo-600">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h3 class="stat-value text-2xl font-bold text-gray-900 mb-2" id="totalInventory">0</h3>
                        <small class="text-xs text-gray-500 block">Total Inventory Items</small>
                    </div>
                </div>

                <!-- Expiring Soon Card -->
                <div class="h-full">
                    <div class="stat-card h-full bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-md cursor-pointer transition-transform duration-200 hover:-translate-y-1 border-l-4 border-amber-400">
                        <div class="stat-icon mb-3 text-4xl text-amber-400">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <h3 class="stat-value text-2xl font-bold text-amber-500 mb-2" id="expiringSoon">0</h3>
                        <small class="text-xs text-gray-500 block">Expiring Soon (&le;30 days)</small>
                    </div>
                </div>

                <!-- Low Stock Items Card -->
                <div class="h-full">
                    <div class="stat-card h-full bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-md cursor-pointer transition-transform duration-200 hover:-translate-y-1 border-l-4 border-red-500">
                        <div class="stat-icon mb-3 text-4xl text-red-500">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="stat-value text-2xl font-bold text-red-500 mb-2" id="lowStock">0</h3>
                        <small class="text-xs text-gray-500 block">Low Stock Items</small>
                    </div>
                </div>

                <!-- Total Sales Card -->
                <div class="h-full">
                    <div class="stat-card h-full bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-md cursor-pointer transition-transform duration-200 hover:-translate-y-1 border-l-4 border-emerald-500">
                        <div class="stat-icon mb-3 text-4xl text-emerald-500">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="stat-value text-2xl font-bold text-emerald-500 mb-2" id="totalSales">₱0</h3>
                        <small class="text-xs text-gray-500 block">Total Sales</small>
                    </div>
                </div>

                <!-- Pending Bookings Card -->
                <div class="h-full">
                    <div class="stat-card h-full bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-md cursor-pointer transition-transform duration-200 hover:-translate-y-1 border-l-4 border-cyan-500">
                        <div class="stat-icon mb-3 text-4xl text-cyan-500">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="stat-value text-2xl font-bold text-cyan-500 mb-2" id="pendingBookings">0</h3>
                        <small class="text-xs text-gray-500 block">Pending Bookings</small>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-history"></i> Recent Sales Transactions
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr><th>Date</th><th>Product</th><th>Quantity</th><th>Total</th></tr>
                                    </thead>
                                    <tbody id="recentSalesTable">
                                        <tr><td colspan="4" class="text-center text-muted py-4">No sales recorded</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-calendar-alt"></i> Upcoming Bookings
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr><th>Date</th><th>Machine</th><th>Farmer</th><th>Status</th></tr>
                                    </thead>
                                    <tbody id="upcomingBookingsTable">
                                        <tr><td colspan="4" class="text-center text-muted py-4">No upcoming bookings</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bell"></i> Low Stock & Expiring Alerts
                        </div>
                        <div class="card-body" id="lowStockAlerts">
                            <p class="text-muted text-center py-3"><i class="fas fa-check-circle"></i> No alerts</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-line"></i> Monthly Sales Trend
                        </div>
                        <div class="card-body chart-container">
                            <canvas id="salesChart" style="max-height: 220px; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Populate dashboard stat cards with data from localStorage (legacy) or from database queries
            function initializeDashboard() {
                // Get inventory data from localStorage for now
                const inventory = JSON.parse(localStorage.getItem('inventory')) || [];
                const bookings = JSON.parse(localStorage.getItem('bookings')) || [];
                const sales = JSON.parse(localStorage.getItem('sales')) || [];

                // Calculate stats
                const totalInventory = inventory.length;

                const expiringSoon = inventory.filter(item => {
                    if (!item.expirationDate) return false;
                    const expDate = new Date(item.expirationDate);
                    const today = new Date();
                    const daysDiff = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
                    return daysDiff > 0 && daysDiff <= 30;
                }).length;

                const lowStock = inventory.filter(item =>
                    item.quantity <= (item.reorderLevel || 10)
                ).length;

                const totalSales = sales.reduce((sum, sale) => {
                    return sum + (parseFloat(sale.total) || 0);
                }, 0);

                const pendingBookings = bookings.filter(b =>
                    b.status === 'Pending' || b.status === 'Confirmed'
                ).length;

                // Update cards
                document.getElementById('totalInventory').textContent = totalInventory;
                document.getElementById('expiringSoon').textContent = expiringSoon;
                document.getElementById('lowStock').textContent = lowStock;
                document.getElementById('totalSales').textContent = '₱' + totalSales.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('pendingBookings').textContent = pendingBookings;

                // Add hover effect
                document.querySelectorAll('.stat-card').forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-5px)';
                    });
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            }

            initializeDashboard();
        });
    </script>
@endpush
