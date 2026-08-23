@extends('layouts.app')

@section('title', 'Sales Transactions - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <x-page-header eyebrow="PSARECO Sales" title="Sales Transactions" description="Manage product orders, process point-of-sale checkouts, and review sales history" icon="fa-solid fa-cart-shopping" >
            <x-slot:actions>
                <button onclick="exportSalesXLSX()" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-medium text-xs py-2 px-3.5 rounded-xl backdrop-blur border border-white/20 transition" >
                    <i class="fa-solid fa-file-excel"></i>
                    Export Excel
                </button>
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white text-emerald-950 hover:bg-emerald-50 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition" >
                    <i class="fa-solid fa-print"></i>
                    Print Sales
                </button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6 print:hidden">
            <div class="lg:col-span-7 bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 flex flex-col justify-between" id="recordSaleCard">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-cart-plus text-emerald-600"></i> Add Product to Cart
                        </h3>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Step 1</span>
                    </div>

                    <!-- Inventory Search Input -->
                    <div class="relative mb-3">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" id="productSearchInput" onkeyup="filterProductList()" placeholder="Search available inventory..."
                            class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Dynamic Product List Container -->
                    <div id="productList" class="max-h-48 overflow-y-auto space-y-1.5 pr-1 mb-4">
                        <div class="text-center text-slate-400 text-xs py-6">Loading inventory...</div>
                    </div>
                </div>

                <!-- Form Controls for Adding to Cart -->
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <div id="stockInfo" class="hidden p-2.5 rounded-xl bg-emerald-50 border border-emerald-200/60 text-xs text-emerald-900 font-medium"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                        <div class="sm:col-span-5">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Selected Product</label>
                            <input type="text" id="selectedProductName" readonly placeholder="Click a product above"
                                class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-700 font-semibold cursor-not-allowed">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Unit Price</label>
                            <input type="text" id="unitPrice" readonly placeholder="₱0.00"
                                class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-700 font-medium cursor-not-allowed">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Qty <span class="text-red-500">*</span></label>
                            <input type="number" id="cartQty" min="1" value="1"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        </div>

                        <div class="sm:col-span-3">
                            <button id="addToCartBtn" class="w-full inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2 px-3 rounded-xl shadow-sm transition">
                                <i class="fa-solid fa-circle-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Cart Review & Checkout -->
            <div class="lg:col-span-5 bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-basket-shopping text-emerald-600"></i> Current Cart
                        </h3>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Step 2</span>
                    </div>

                    <!-- Cart Item Table/List -->
                    <div id="cartItemsList" class="min-h-[140px] max-h-48 overflow-y-auto mb-4">
                        <p class="text-slate-400 text-center text-xs py-8">No items in cart</p>
                    </div>
                </div>

                <!-- Checkout Form Inputs -->
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Buyer Name <span class="text-red-500">*</span></label>
                            <input type="text" id="buyerName" placeholder="Enter customer name"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Total Amount</label>
                            <input type="text" id="cartTotal" readonly placeholder="₱0.00"
                                class="w-full px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 font-extrabold cursor-not-allowed">
                        </div>
                    </div>

                    <button id="checkoutBtn" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-receipt"></i> Checkout (Cash)
                    </button>
                </div>
            </div>

        </div>

        <!-- Sales History Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i> Sales History
                </h3>

                <!-- Table Filter/Search -->
                <div class="relative w-full sm:w-64 print:hidden">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchSales" onkeyup="filterSales()" placeholder="Filter sales records..."
                        class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Table -->
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse" id="salesHistoryTable">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Date</th>
                            <th class="py-2.5 px-4">Items Purchased</th>
                            <th class="py-2.5 px-4">Buyer</th>
                            <th class="py-2.5 px-4 text-right">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody id="salesTable" class="divide-y divide-slate-100 text-xs text-slate-700">
                        <tr>
                            <td colspan="4" class="text-center text-slate-400 py-6">No sales recorded</td>
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
        let allProducts = [];
        let cart = [];
        let currentProductForCart = null;

        function loadSalesPage() {
            if (typeof requireAuth === 'function' && !requireAuth()) return;

            if (typeof getCurrentUser === 'function') {
                currentUser = getCurrentUser();
                if (currentUser && currentUser.role === 'farmer') {
                    alert('Access restricted.');
                    window.location.href = '{{ route('dashboard') }}';
                    return;
                }
            }

            if (typeof loadSidebar === 'function') loadSidebar();
            loadProducts();
            loadSalesHistory();
            updateCartDisplay();
        }

        function loadProducts() {
            allProducts = typeof getInventory === 'function' ? getInventory() : [];
            renderProductList(allProducts);
        }

        function renderProductList(products) {
            const container = document.getElementById('productList');
            if (!container) return;

            if (!products.length) {
                container.innerHTML = '<div class="text-center text-slate-400 py-6 text-xs">No products available</div>';
                return;
            }

            container.innerHTML = products.map(product => {
                const price = Number(product.price || 0);
                const qty = Number(product.quantity || product.qty || 0);
                const unit = product.unit || 'unit';
                const costPrice = Number(product.costPrice || 0);
                const expDate = product.expirationDate || '';

                return `
                    <div onclick="selectProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${price}, ${qty}, '${unit}', '${expDate}', ${costPrice})"
                        class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-emerald-50/60 hover:border-emerald-200 cursor-pointer transition">
                        <div>
                            <div class="font-semibold text-slate-800 text-xs">${product.name}</div>
                            <div class="text-[11px] text-slate-500">Stock: <span class="font-medium text-slate-700">${qty} ${unit}</span></div>
                        </div>
                        <div class="font-bold text-emerald-700 text-xs">₱${price.toLocaleString()}</div>
                    </div>
                `;
            }).join('');
        }

        function filterProductList() {
            const term = (document.getElementById('productSearchInput')?.value || '').toLowerCase();
            renderProductList(allProducts.filter(p => p.name.toLowerCase().includes(term)));
        }

        function selectProduct(id, name, price, stock, unit, expirationDate, costPrice) {
            currentProductForCart = { id, name, price, stock, unit, expirationDate, costPrice };

            const nameInput = document.getElementById('selectedProductName');
            const priceInput = document.getElementById('unitPrice');
            const stockInfo = document.getElementById('stockInfo');

            if (nameInput) nameInput.value = name;
            if (priceInput) priceInput.value = '₱' + Number(price).toLocaleString();

            if (stockInfo) {
                stockInfo.classList.remove('hidden');
                stockInfo.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-600"></i> <strong class="text-slate-800">${name}</strong> &bull; Stock: ${stock} ${unit} &bull; Price: ₱${Number(price).toLocaleString()}`;
            }
        }

        function addToCart() {
            if (!currentProductForCart) {
                alert('Select a product first');
                return;
            }

            const qtyInput = document.getElementById('cartQty');
            const qty = parseInt(qtyInput?.value, 10);

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

            if (document.getElementById('selectedProductName')) document.getElementById('selectedProductName').value = '';
            if (qtyInput) qtyInput.value = '1';
            if (document.getElementById('unitPrice')) document.getElementById('unitPrice').value = '';
            if (document.getElementById('stockInfo')) document.getElementById('stockInfo').classList.add('hidden');

            currentProductForCart = null;
        }

        function updateCartDisplay() {
            const container = document.getElementById('cartItemsList');
            const totalInput = document.getElementById('cartTotal');

            if (!container) return;

            if (cart.length === 0) {
                container.innerHTML = '<p class="text-slate-400 text-center text-xs py-8">No items in cart</p>';
                if (totalInput) totalInput.value = '';
                return;
            }

            let total = 0;
            let html = `
                <div class="w-full overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase font-semibold border-b border-slate-100">
                                <th class="py-2 px-3">Product</th>
                                <th class="py-2 px-3">Qty</th>
                                <th class="py-2 px-3">Price</th>
                                <th class="py-2 px-3">Subtotal</th>
                                <th class="py-2 px-2 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
            `;

            cart.forEach((item, idx) => {
                const subtotal = item.price * item.qty;
                total += subtotal;
                html += `
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-2 px-3 font-semibold text-slate-800">${item.name}</td>
                        <td class="py-2 px-3">${item.qty} ${item.unit}</td>
                        <td class="py-2 px-3">₱${Number(item.price).toLocaleString()}</td>
                        <td class="py-2 px-3 font-semibold text-slate-800">₱${Number(subtotal).toLocaleString()}</td>
                        <td class="py-2 px-2 text-center">
                            <button onclick="removeFromCart(${idx})" class="p-1 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += `</tbody></table></div>`;
            container.innerHTML = html;
            if (totalInput) totalInput.value = '₱' + Number(total).toLocaleString();
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

            const buyerInput = document.getElementById('buyerName');
            const buyerName = buyerInput?.value.trim();

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

            const sales = typeof getSales === 'function' ? getSales() : JSON.parse(localStorage.getItem('sales') || '[]');
            sales.push(sale);
            localStorage.setItem('sales', JSON.stringify(sales));

            cart = [];
            updateCartDisplay();
            if (buyerInput) buyerInput.value = '';
            loadSalesHistory();
            alert('Sale recorded successfully');
        }

        function loadSalesHistory() {
            const sales = typeof getSales === 'function' ? getSales() : JSON.parse(localStorage.getItem('sales') || '[]');
            const table = document.getElementById('salesTable');
            if (!table) return;

            if (sales.length === 0) {
                table.innerHTML = '<tr><td colspan="4" class="text-center text-slate-400 py-6">No sales recorded</td></tr>';
                return;
            }

            table.innerHTML = sales.slice().reverse().map(sale => `
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-4 font-medium text-slate-600">${new Date(sale.saleDate || sale.date).toLocaleDateString()}</td>
                    <td class="py-3 px-4 font-medium text-slate-800">${(sale.items || []).map(item => `${item.name} (x${item.qty})`).join(', ') || '—'}</td>
                    <td class="py-3 px-4 text-slate-600">${sale.buyerName || '—'}</td>
                    <td class="py-3 px-4 font-bold text-emerald-600 text-right">₱${Number(sale.total || 0).toLocaleString()}</td>
                </tr>
            `).join('');
        }

        function filterSales() {
            const term = (document.getElementById('searchSales')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#salesTable tr');
            rows.forEach(row => {
                const text = row.textContent?.toLowerCase() || '';
                row.style.display = text.includes(term) ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadSalesPage();
            document.getElementById('addToCartBtn')?.addEventListener('click', addToCart);
            document.getElementById('checkoutBtn')?.addEventListener('click', checkout);
        });
    </script>
@endpush
