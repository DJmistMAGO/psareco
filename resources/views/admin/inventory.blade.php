@extends('layouts.app')

@section('title', 'Inventory - PSARECO')

@section('content')
    <div class="d-flex">

        <div class="main-content" style="margin-left: 250px; flex: 1; padding: 30px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
            @include('components.breadcrumb', [
                'title' => 'Inventory Management',
                'icon' => 'fas fa-boxes'
            ])

                <button class="btn btn-outline-success" onclick="exportInventory()"><i class="fas fa-download"></i> Export Report</button>
            </div>

            <div class="search-box position-relative mb-4">
                <i class="fas fa-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                <input type="text" id="searchInventory" class="form-control ps-5" placeholder="Search products..." onkeyup="filterInventory()">
            </div>

            <div class="card mb-4" id="addProductCard">
                <div class="card-header"><i class="fas fa-plus-circle"></i> Add New Product</div>
                <div class="card-body">
                    <div class="add-product-row">
                        <div class="form-group"><input type="text" id="productName" class="form-control" placeholder="Product Name"></div>
                        <div class="form-group"><select id="productType" class="form-select"><option value="Fertilizer">Fertilizer</option><option value="Pesticide">Pesticide</option></select></div>
                        <div class="form-group"><input type="number" id="productQty" class="form-control" placeholder="Quantity"></div>
                        <div class="form-group"><input type="text" id="productUnit" class="form-control" placeholder="Unit (bags/liters)"></div>
                        <div class="form-group"><input type="number" id="productPrice" class="form-control" placeholder="Price (₱)"></div>
                        <div class="form-group"><input type="number" id="productCost" class="form-control" placeholder="Cost (₱)"></div>
                        <div class="form-group"><input type="number" id="productReorderLevel" class="form-control" placeholder="Reorder at"></div>
                        <div class="form-group"><input type="date" id="productExpiration" class="form-control" placeholder="Expiration Date"></div>
                        <div class="form-group"><button class="btn btn-success w-100" onclick="addProduct()"><i class="fas fa-plus"></i> Add Product</button></div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header fertilizer-header"><span><i class="fas fa-leaf"></i> Fertilizers</span><span class="badge bg-light text-dark ms-2" id="fertilizerCount">0</span></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr><th>Product Name</th><th>Quantity</th><th>Unit</th><th>Unit Price</th><th>Cost Price</th><th>Reorder Level</th><th>Expiration Date</th><th>Date Added</th><th>Status</th></tr>
                            </thead>
                            <tbody id="fertilizersTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pesticide-header"><span><i class="fas fa-bug"></i> Pesticides</span><span class="badge bg-light text-dark ms-2" id="pesticideCount">0</span></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr><th>Product Name</th><th>Quantity</th><th>Unit</th><th>Unit Price</th><th>Cost Price</th><th>Reorder Level</th><th>Expiration Date</th><th>Date Added</th><th>Status</th></tr>
                            </thead>
                            <tbody id="pesticidesTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        let currentUser = null;
        let allFertilizers = [], allPesticides = [];

        function getExpirationStatus(expirationDate) {
            if (!expirationDate) return { class: '', text: 'Not set', isExpired: false, isExpiring: false };
            const today = new Date(); today.setHours(0, 0, 0, 0);
            const expDate = new Date(expirationDate);
            if (expDate < today) return { class: 'expired-badge', text: 'EXPIRED', isExpired: true, isExpiring: false };
            const daysDiff = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
            if (daysDiff <= 30) return { class: 'expiring-badge', text: `Expires in ${daysDiff} days`, isExpired: false, isExpiring: true };
            return { class: 'badge-success', text: `Valid until ${expDate.toLocaleDateString()}`, isExpired: false, isExpiring: false };
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;');
        }

        function loadInventoryPage() {
            if (!requireAuth()) return;
            currentUser = getCurrentUser();
            if (typeof loadSidebar === 'function') loadSidebar();

            if (currentUser.role !== 'officer' && currentUser.role !== 'admin') {
                document.getElementById('addProductCard').style.display = 'none';
            }

            loadInventory();
        }

        function loadInventory() {
            const inventory = getInventory();
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
            if (items.length === 0) { tableBody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4">No fertilizers found</td></tr>'; return; }
            tableBody.innerHTML = items.map(item => renderInventoryRow(item)).join('');
        }

        function renderPesticides(items) {
            const tableBody = document.getElementById('pesticidesTableBody');
            if (items.length === 0) { tableBody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4">No pesticides found</td></tr>'; return; }
            tableBody.innerHTML = items.map(item => renderInventoryRow(item)).join('');
        }

        function renderInventoryRow(item) {
            const isLowStock = item.qty <= item.reorderLevel;
            const expStatus = getExpirationStatus(item.expirationDate);
            const statusBadges = [];
            if (isLowStock) statusBadges.push('<span class="badge badge-low-stock me-1">Low Stock</span>');
            if (expStatus.isExpired) statusBadges.push('<span class="badge expired-badge">Expired</span>');
            else if (expStatus.isExpiring) statusBadges.push(`<span class="badge expiring-badge">${expStatus.text}</span>`);
            if (statusBadges.length === 0) statusBadges.push('<span class="badge badge-success">In Stock</span>');

            const createdAtDisplay = item.createdAt ? new Date(item.createdAt).toLocaleDateString() : '—';

            return `
                <tr class="inventory-row">
                    <td>
                        <div class="product-name-cell">
                            <span class="product-name">${escapeHtml(item.name)}</span>
                        </div>
                    </td>
                    <td>${item.qty}</td>
                    <td>${item.unit || '—'}</td>
                    <td>₱${Number(item.price || 0).toLocaleString()}</td>
                    <td>₱${Number(item.costPrice || 0).toLocaleString()}</td>
                    <td>${item.reorderLevel || 0}</td>
                    <td>${item.expirationDate ? new Date(item.expirationDate).toLocaleDateString() : '—'}</td>
                    <td>${createdAtDisplay}</td>
                    <td>${statusBadges.join(' ')}</td>
                </tr>
            `;
        }

        document.addEventListener('DOMContentLoaded', loadInventoryPage);
    </script> --}}
@endpush
