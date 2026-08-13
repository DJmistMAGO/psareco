@extends('layouts.app')

@section('title', 'Sales - PSARECO')

@section('content')
    <div class="d-flex">

        <div class="main-content" style="margin-left: 250px; flex: 1; padding: 30px;">
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                @include('components.breadcrumb', [
                    'title' => 'Sales Transactions',
                    'icon' => 'fas fa-shopping-cart'
                ])
                <div>
                    <button class="btn btn-outline-success me-2" onclick="exportSalesXLSX()"><i class="fas fa-file-excel"></i> Export Excel (XLSX)</button>
                    <button class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print Sales</button>
                </div>
            </div>

            <div class="card mb-4 no-print" id="recordSaleCard">
                <div class="card-header"><i class="fas fa-cart-plus"></i> Add Product to Cart</div>
                <div class="card-body">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="productSearchInput" class="form-control" placeholder="Search product..." autocomplete="off" onkeyup="filterProductList()">
                    </div>
                    <div class="product-list-container">
                        <div id="productList"></div>
                    </div>
                    <div class="row g-3 mt-3 align-items-end">
                        <div class="col-md-5"><label class="form-label fw-semibold">Selected Product</label><input type="text" id="selectedProductName" class="form-control" readonly placeholder="Click a product above"></div>
                        <div class="col-md-2"><label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label><input type="number" id="cartQty" class="form-control" placeholder="Quantity" min="1" value="1"></div>
                        <div class="col-md-2"><label class="form-label fw-semibold">Unit Price</label><input type="text" id="unitPrice" class="form-control" placeholder="Unit Price" readonly></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">&nbsp;</label><button class="btn btn-primary w-100" id="addToCartBtn"><i class="fas fa-plus-circle"></i> Add to Cart</button></div>
                    </div>
                    <div id="stockInfo" class="selected-info" style="display: none;"></div>
                </div>
            </div>

            <div class="card mb-4 no-print">
                <div class="card-header"><i class="fas fa-shopping-cart"></i> Current Cart</div>
                <div class="card-body">
                    <div id="cartItemsList" class="mb-3"><p class="text-muted text-center">No items in cart</p></div>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6"><label class="form-label fw-semibold">Buyer Name <span class="text-danger">*</span></label><input type="text" id="buyerName" class="form-control" placeholder="Enter buyer name"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Total Amount</label><input type="text" id="cartTotal" class="form-control" readonly></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">&nbsp;</label><button class="btn btn-success w-100" id="checkoutBtn"><i class="fas fa-receipt"></i> Checkout (Cash)</button></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="fas fa-history"></i> Sales History</div>
                <div class="card-body">
                    <div class="search-box no-print"><i class="fas fa-search"></i><input type="text" id="searchSales" class="form-control" placeholder="Search sales..." onkeyup="filterSales()"></div>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover mb-0" id="salesHistoryTable">
                            <thead>
                                <tr><th>Date</th><th>Items</th><th>Total</th><th>Buyer</th></tr>
                            </thead>
                            <tbody id="salesTable"><tr><td colspan="4" class="text-center text-muted py-4">No sales recorded</td></tr></tbody>
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
        let allProducts = [];
        let cart = [];
        let currentProductForCart = null;

        function loadSalesPage() {
            if (!requireAuth()) return;
            currentUser = getCurrentUser();
            if (currentUser.role === 'farmer') {
                alert('Access restricted.');
                window.location.href = '{{ route('dashboard') }}';
                return;
            }
            if (typeof loadSidebar === 'function') loadSidebar();
            loadProducts();
            loadSalesHistory();
            updateCartDisplay();
        }

        function loadProducts() {
            allProducts = getInventory();
            renderProductList(allProducts);
        }

        function renderProductList(products) {
            const container = document.getElementById('productList');
            if (!products.length) {
                container.innerHTML = '<div class="text-center text-muted py-4">No products available</div>';
                return;
            }
            container.innerHTML = products.map(product => `
                <div class="product-item" onclick="selectProduct(${product.id}, '${product.name}', ${Number(product.price || 0)}, ${Number(product.quantity || product.qty || 0)}, '${product.unit || 'unit'}', '${product.expirationDate || ''}', ${Number(product.costPrice || 0)})">
                    <div>
                        <div class="product-name">${product.name}</div>
                        <div class="product-stock">Stock: ${product.quantity || product.qty || 0} ${product.unit || 'units'}</div>
                    </div>
                    <div class="product-price">₱${Number(product.price || 0).toLocaleString()}</div>
                </div>
            `).join('');
        }

        function filterProductList() {
            const term = document.getElementById('productSearchInput').value.toLowerCase();
            renderProductList(allProducts.filter(p => p.name.toLowerCase().includes(term)));
        }

        function selectProduct(id, name, price, stock, unit, expirationDate, costPrice) {
            currentProductForCart = { id, name, price, stock, unit, expirationDate, costPrice };
            document.getElementById('selectedProductName').value = name;
            document.getElementById('unitPrice').value = '₱' + Number(price).toLocaleString();
            document.getElementById('stockInfo').style.display = 'block';
            document.getElementById('stockInfo').innerHTML = `<i class="fas fa-check-circle text-success"></i> <strong>${name}</strong> | Stock: ${stock} ${unit} | Price: ₱${Number(price).toLocaleString()}`;
        }

        function addToCart() {
            if (!currentProductForCart) {
                alert('Select a product first');
                return;
            }

            const qty = parseInt(document.getElementById('cartQty').value, 10);
            if (!qty || qty <= 0) {
                alert('Valid quantity required');
                return;
            }

            if (currentProductForCart.stock < qty) {
                alert(`Insufficient stock! Only ${currentProductForCart.stock} ${currentProductForCart.unit} available.`);
                return;
            }

            const existingIndex = cart.findIndex(item => item.id === currentProductForCart.id);
            if (existingIndex !== -1) {
                const newQty = cart[existingIndex].qty + qty;
                if (newQty > currentProductForCart.stock) {
                    alert(`Cannot add ${qty} more. Only ${currentProductForCart.stock - cart[existingIndex].qty} left.`);
                    return;
                }
                cart[existingIndex].qty = newQty;
            } else {
                cart.push({
                    id: currentProductForCart.id,
                    name: currentProductForCart.name,
                    price: currentProductForCart.price,
                    qty,
                    stock: currentProductForCart.stock,
                    unit: currentProductForCart.unit,
                    costPrice: currentProductForCart.costPrice,
                });
            }

            updateCartDisplay();
            document.getElementById('selectedProductName').value = '';
            document.getElementById('cartQty').value = '1';
            document.getElementById('unitPrice').value = '';
            document.getElementById('stockInfo').style.display = 'none';
            currentProductForCart = null;
        }

        function updateCartDisplay() {
            const container = document.getElementById('cartItemsList');
            if (cart.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">No items in cart</p>';
                document.getElementById('cartTotal').value = '';
                return;
            }

            let total = 0;
            let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th><th></th></tr></thead><tbody>';

            cart.forEach((item, idx) => {
                const subtotal = item.price * item.qty;
                total += subtotal;
                html += `<tr><td><strong>${item.name}</strong></td><td>${item.qty} ${item.unit}</td><td>₱${Number(item.price).toLocaleString()}</td><td>₱${Number(subtotal).toLocaleString()}</td><td><button class="btn btn-sm btn-danger" onclick="removeFromCart(${idx})"><i class="fas fa-trash"></i></button></td></tr>`;
            });

            html += '</tbody></table></div>';
            container.innerHTML = html;
            document.getElementById('cartTotal').value = '₱' + Number(total).toLocaleString();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            const buyerName = document.getElementById('buyerName').value.trim();
            if (!buyerName) {
                alert('Please enter buyer name');
                return;
            }

            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const sale = {
                id: Date.now(),
                buyerName,
                total,
                saleDate: new Date().toISOString(),
                items: cart,
            };

            const sales = getSales();
            sales.push(sale);
            localStorage.setItem('sales', JSON.stringify(sales));

            cart = [];
            updateCartDisplay();
            document.getElementById('buyerName').value = '';
            loadSalesHistory();
            alert('Sale recorded successfully');
        }

        function loadSalesHistory() {
            const sales = getSales();
            const table = document.getElementById('salesTable');
            if (sales.length === 0) {
                table.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">No sales recorded</td></tr>';
                return;
            }

            table.innerHTML = sales.slice().reverse().map(sale => `
                <tr>
                    <td>${new Date(sale.saleDate || sale.date).toLocaleDateString()}</td>
                    <td>${(sale.items || []).map(item => `${item.name} x${item.qty}`).join(', ') || '—'}</td>
                    <td>₱${Number(sale.total || 0).toLocaleString()}</td>
                    <td>${sale.buyerName || '—'}</td>
                </tr>
            `).join('');
        }

        document.addEventListener('DOMContentLoaded', loadSalesPage);
        document.getElementById('addToCartBtn')?.addEventListener('click', addToCart);
        document.getElementById('checkoutBtn')?.addEventListener('click', checkout);
    </script> --}}
@endpush
