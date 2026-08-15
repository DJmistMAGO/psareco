@extends('layouts.app')

@section('title', 'Inventory - PSARECO')

@section('content')

    <!-- ================= MAIN CONTENT AREA ================= -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 overflow-y-auto transition-all duration-300">

        <!-- Mobile Navigation Trigger Bar -->
        <div class="flex items-center justify-between mb-4 lg:hidden bg-white/60 backdrop-blur p-3 rounded-xl shadow-sm border border-emerald-100">
            <span class="font-bold text-emerald-950 text-sm">PSARECO System</span>
            <button @click="mobileOpen = !mobileOpen" class="p-2 text-emerald-800 hover:bg-emerald-100 rounded-lg">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Hero Header & Actions -->
        <section class="bg-gradient-to-r from-[#2c7a56] to-[#40a072] text-white rounded-2xl p-6 mb-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked"></i> Inventory Management
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">Monitor, adjust, and add farm products and supplies</p>
            </div>
            <button onclick="exportInventory()" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition backdrop-blur border border-white/20">
                <i class="fa-solid fa-download"></i> Export Report
            </button>
        </section>

        <!-- Search Bar -->
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </div>
            <input
                type="text"
                id="searchInventory"
                onkeyup="filterInventory()"
                placeholder="Search products..."
                class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent shadow-sm transition"
            >
        </div>

        <!-- Add New Product Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 mb-6 overflow-hidden" id="addProductCard">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-circle-plus text-emerald-600 text-base"></i>
                <h3 class="font-bold text-slate-700 text-sm">Add New Product</h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-9 gap-3">
                    <div class="lg:col-span-1">
                        <input type="text" id="productName" placeholder="Product Name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                    <div class="lg:col-span-1">
                        <select id="productType" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                            <option value="Fertilizer">Fertilizer</option>
                            <option value="Pesticide">Pesticide</option>
                        </select>
                    </div>
                    <div class="lg:col-span-1">
                        <input type="number" id="productQty" placeholder="Quantity" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                    <div class="lg:col-span-1">
                        <input type="text" id="productUnit" placeholder="Unit (bags/L)" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                    <div class="lg:col-span-1">
                        <input type="number" id="productPrice" placeholder="Price (₱)" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                    <div class="lg:col-span-1">
                        <input type="number" id="productCost" placeholder="Cost (₱)" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                    <div class="lg:col-span-1">
                        <input type="number" id="productReorderLevel" placeholder="Reorder at" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                    <div class="lg:col-span-1">
                        <input type="date" id="productExpiration" placeholder="Exp Date" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                    <div class="lg:col-span-1">
                        <button onclick="addProduct()" class="w-full h-full min-h-[38px] bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl flex items-center justify-center gap-1.5 transition">
                            <i class="fa-solid fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fertilizers Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col mb-6">
            <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-leaf text-emerald-600 text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Fertilizers</h3>
                </div>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full" id="fertilizerCount">0</span>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Product Name</th>
                            <th class="py-2.5 px-4">Quantity</th>
                            <th class="py-2.5 px-4">Unit</th>
                            <th class="py-2.5 px-4">Unit Price</th>
                            <th class="py-2.5 px-4">Cost Price</th>
                            <th class="py-2.5 px-4">Reorder Level</th>
                            <th class="py-2.5 px-4">Expiration Date</th>
                            <th class="py-2.5 px-4">Date Added</th>
                            <th class="py-2.5 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody id="fertilizersTableBody" class="divide-y divide-slate-100 text-xs text-slate-700">
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">Loading fertilizers...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pesticides Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden flex flex-col mb-6">
            <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-bug text-emerald-600 text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Pesticides</h3>
                </div>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full" id="pesticideCount">0</span>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Product Name</th>
                            <th class="py-2.5 px-4">Quantity</th>
                            <th class="py-2.5 px-4">Unit</th>
                            <th class="py-2.5 px-4">Unit Price</th>
                            <th class="py-2.5 px-4">Cost Price</th>
                            <th class="py-2.5 px-4">Reorder Level</th>
                            <th class="py-2.5 px-4">Expiration Date</th>
                            <th class="py-2.5 px-4">Date Added</th>
                            <th class="py-2.5 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody id="pesticidesTableBody" class="divide-y divide-slate-100 text-xs text-slate-700">
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">Loading pesticides...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

@endsection

@push('scripts')
    <script>
        let currentUser = null;
        let allFertilizers = [], allPesticides = [];

        function getExpirationStatus(expirationDate) {
            if (!expirationDate) return { class: '', text: 'Not set', isExpired: false, isExpiring: false };
            const today = new Date(); today.setHours(0, 0, 0, 0);
            const expDate = new Date(expirationDate);
            if (expDate < today) return { class: 'bg-red-100 text-red-700 border-red-200', text: 'EXPIRED', isExpired: true, isExpiring: false };
            const daysDiff = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
            if (daysDiff <= 30) return { class: 'bg-amber-100 text-amber-700 border-amber-200', text: `Expires in ${daysDiff} days`, isExpired: false, isExpiring: true };
            return { class: 'bg-emerald-100 text-emerald-700 border-emerald-200', text: `Valid until ${expDate.toLocaleDateString()}`, isExpired: false, isExpiring: false };
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;');
        }

        function loadInventoryPage() {
            if (typeof requireAuth === 'function' && !requireAuth()) return;
            if (typeof getCurrentUser === 'function') currentUser = getCurrentUser();
            if (typeof loadSidebar === 'function') loadSidebar();

            if (currentUser && currentUser.role !== 'officer' && currentUser.role !== 'admin') {
                const addCard = document.getElementById('addProductCard');
                if (addCard) addCard.style.display = 'none';
            }

            loadInventory();
        }

        function loadInventory() {
            const inventory = typeof getInventory === 'function' ? getInventory() : [];
            allFertilizers = inventory.filter(item => item.type === 'Fertilizer');
            allPesticides = inventory.filter(item => item.type === 'Pesticide');

            document.getElementById('fertilizerCount').innerText = allFertilizers.length;
            document.getElementById('pesticideCount').innerText = allPesticides.length;

            renderFertilizers(allFertilizers);
            renderPesticides(allPesticides);
        }

        function filterInventory() {
            const term = document.getElementById('searchInventory').value.toLowerCase();
            renderFertilizers(allFertilizers.filter(item => item.name.toLowerCase().includes(term)));
            renderPesticides(allPesticides.filter(item => item.name.toLowerCase().includes(term)));
        }

        function renderFertilizers(items) {
            const tableBody = document.getElementById('fertilizersTableBody');
            if (items.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="9" class="py-12 text-center text-slate-400">No fertilizers found</td></tr>';
                return;
            }
            tableBody.innerHTML = items.map(item => renderInventoryRow(item)).join('');
        }

        function renderPesticides(items) {
            const tableBody = document.getElementById('pesticidesTableBody');
            if (items.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="9" class="py-12 text-center text-slate-400">No pesticides found</td></tr>';
                return;
            }
            tableBody.innerHTML = items.map(item => renderInventoryRow(item)).join('');
        }

        function renderInventoryRow(item) {
            const isLowStock = item.qty <= item.reorderLevel;
            const expStatus = getExpirationStatus(item.expirationDate);
            const statusBadges = [];

            if (isLowStock) {
                statusBadges.push('<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-700 border border-red-200">Low Stock</span>');
            }

            if (expStatus.isExpired) {
                statusBadges.push('<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-700 border border-red-200">Expired</span>');
            } else if (expStatus.isExpiring) {
                statusBadges.push(`<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-100 text-amber-700 border border-amber-200">${expStatus.text}</span>`);
            }

            if (statusBadges.length === 0) {
                statusBadges.push('<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">In Stock</span>');
            }

            const createdAtDisplay = item.createdAt ? new Date(item.createdAt).toLocaleDateString() : '—';

            return `
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-800">${escapeHtml(item.name)}</td>
                    <td class="py-3 px-4 font-bold text-slate-800">${item.qty}</td>
                    <td class="py-3 px-4 text-slate-500">${item.unit || '—'}</td>
                    <td class="py-3 px-4 font-medium text-slate-700">₱${Number(item.price || 0).toLocaleString()}</td>
                    <td class="py-3 px-4 text-slate-500">₱${Number(item.costPrice || 0).toLocaleString()}</td>
                    <td class="py-3 px-4 text-slate-500">${item.reorderLevel || 0}</td>
                    <td class="py-3 px-4 text-slate-500">${item.expirationDate ? new Date(item.expirationDate).toLocaleDateString() : '—'}</td>
                    <td class="py-3 px-4 text-slate-400">${createdAtDisplay}</td>
                    <td class="py-3 px-4">
                        <div class="flex flex-wrap gap-1">
                            ${statusBadges.join('')}
                        </div>
                    </td>
                </tr>
            `;
        }

        document.addEventListener('DOMContentLoaded', loadInventoryPage);
    </script>
@endpush
