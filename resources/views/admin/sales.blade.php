@extends('layouts.app')

@section('title', 'Sales Transactions - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <x-page-header eyebrow="PSARECO Sales" title="Sales Transactions" description="Manage product orders, process point-of-sale checkouts, and review sales history" icon="fa-solid fa-cart-shopping" >
            <x-slot:actions>
                <form action="{{ route('sales.export') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-medium text-xs py-2 px-3.5 rounded-xl backdrop-blur border border-white/20 transition" >
                        <i class="fa-solid fa-file-excel"></i>
                        Export CSV
                    </button>
                </form>
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

                    <div class="relative mb-3">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" id="productSearchInput" onkeyup="filterProductList()" placeholder="Search available inventory..."
                            class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <div id="productList" class="max-h-48 overflow-y-auto space-y-1.5 pr-1 mb-4">
                        @forelse ($products as $product)
                            <div
                                onclick="selectProduct({{ $product->id }}, '{{ addslashes($product->name) }}', {{ (float) $product->price }}, {{ (int) $product->quantity }}, '{{ addslashes($product->unit) }}')"
                                data-name="{{ strtolower($product->name) }}"
                                class="product-row flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-emerald-50/60 hover:border-emerald-200 cursor-pointer transition">
                                <div>
                                    <div class="font-semibold text-slate-800 text-xs">{{ $product->name }}</div>
                                    <div class="text-[11px] text-slate-500">Stock: <span class="font-medium text-slate-700">{{ $product->quantity }} {{ $product->unit }}</span></div>
                                </div>
                                <div class="font-bold text-emerald-700 text-xs">₱{{ number_format($product->price, 2) }}</div>
                            </div>
                        @empty
                            <div class="text-center text-slate-400 text-xs py-6">No products available</div>
                        @endforelse
                    </div>
                </div>

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
                            <button id="addToCartBtn" type="button" class="w-full inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2 px-3 rounded-xl shadow-sm transition">
                                <i class="fa-solid fa-circle-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-basket-shopping text-emerald-600"></i> Current Cart
                        </h3>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Step 2</span>
                    </div>

                    <div id="cartItemsList" class="min-h-[140px] max-h-48 overflow-y-auto mb-4">
                        <p class="text-slate-400 text-center text-xs py-8">No items in cart</p>
                    </div>
                </div>

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

                    <button id="checkoutBtn" type="button" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-receipt"></i> Checkout (Cash)
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i> Sales History
                </h3>

                <div class="relative w-full sm:w-64 print:hidden">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchSales" onkeyup="filterSales()" placeholder="Filter sales records..."
                        class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
            </div>

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
                        @forelse ($salesHistory as $sale)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-medium text-slate-600">{{ optional($sale['sale_date'])->format('M d, Y g:ia') }}</td>
                                <td class="py-3 px-4 font-medium text-slate-800 max-w-[220px]">
                                    @php
                                        $items = $sale['items'];
                                        $visibleItems = $items->take(2);
                                        $remainingCount = $items->count() - $visibleItems->count();
                                    @endphp
                                    <div class="flex items-center gap-1.5" x-data="{ open: false }">
                                        <span class="truncate">
                                            {{ $visibleItems->map(fn ($i) => "{$i['name']} (x{$i['quantity']})")->implode(', ') }}
                                        </span>

                                        @if ($remainingCount > 0)
                                            <div class="relative shrink-0">
                                                <button type="button" @click="open = !open" @click.outside="open = false"
                                                    class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-1.5 py-0.5 rounded-md whitespace-nowrap transition">
                                                    +{{ $remainingCount }} more
                                                </button>

                                                <div x-show="open" x-transition x-cloak @click.outside="open = false"
                                                    class="absolute z-20 left-0 mt-1 w-56 bg-white border border-slate-200 rounded-xl shadow-lg p-3 text-xs text-slate-700 space-y-1.5">
                                                    @foreach ($items as $item)
                                                        <div class="flex justify-between gap-2">
                                                            <span class="truncate">{{ $item['name'] }}</span>
                                                            <span class="text-slate-400 font-medium shrink-0">x{{ $item['quantity'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ $sale['buyer_name'] }}</td>
                                <td class="py-3 px-4 font-bold text-emerald-600 text-right">₱{{ number_format($sale['total'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 py-6">No sales recorded</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- alert modal --}}
        <div id="statusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
            <div id="statusModalOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                <div id="statusModalIcon" class="mx-auto mb-4 w-12 h-12 rounded-full flex items-center justify-center">
                    <i id="statusModalIconGlyph" class="text-xl"></i>
                </div>
                <h3 id="statusModalTitle" class="font-bold text-slate-800 text-sm mb-1.5"></h3>
                <p id="statusModalMessage" class="text-xs text-slate-500 leading-relaxed"></p>
                <button id="statusModalOkBtn" type="button"
                    class="mt-5 w-full inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition">
                    OK
                </button>
            </div>
        </div>

    </main>
@endsection

@push('scripts')
    <script>
        let cart = [];
        let currentProductForCart = null;

        function filterProductList() {
            const term = (document.getElementById('productSearchInput')?.value || '').toLowerCase();
            document.querySelectorAll('#productList .product-row').forEach(row => {
                row.style.display = row.dataset.name.includes(term) ? '' : 'none';
            });
        }

        function selectProduct(id, name, price, stock, unit) {
            currentProductForCart = { id, name, price, stock, unit };

            document.getElementById('selectedProductName').value = name;
            document.getElementById('unitPrice').value = '₱' + Number(price).toLocaleString();

            const stockInfo = document.getElementById('stockInfo');
            stockInfo.classList.remove('hidden');
            stockInfo.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-600"></i> <strong class="text-slate-800">${name}</strong> &bull; Stock: ${stock} ${unit} &bull; Price: ₱${Number(price).toLocaleString()}`;
        }

        function addToCart() {
            if (!currentProductForCart) {
                showStatusModal('error', 'No Product Selected', 'Please click a product from the list above before adding it to the cart.');
                return;
            }

            const qtyInput = document.getElementById('cartQty');
            const qty = parseInt(qtyInput.value, 10);

            if (!qty || qty <= 0) {
                showStatusModal('error', 'Invalid Quantity', 'Please enter a valid quantity of at least 1.');
                return;
            }

            if (currentProductForCart.stock < qty) {
                showStatusModal('error', 'Insufficient Stock', `Only ${currentProductForCart.stock} ${currentProductForCart.unit} of this product are available.`);
                return;
            }

            const existing = cart.find(item => item.id === currentProductForCart.id);
            if (existing) {
                if (existing.qty + qty > currentProductForCart.stock) {
                    showStatusModal('error', 'Insufficient Stock', `Cannot add ${qty} more — only ${currentProductForCart.stock - existing.qty} left in stock.`);
                    return;
                }
                existing.qty += qty;
            } else {
                cart.push({ ...currentProductForCart, qty });
            }

            updateCartDisplay();

            document.getElementById('selectedProductName').value = '';
            document.getElementById('unitPrice').value = '';
            document.getElementById('stockInfo').classList.add('hidden');
            qtyInput.value = '1';
            currentProductForCart = null;
        }

        function updateCartDisplay() {
            const container = document.getElementById('cartItemsList');
            const totalInput = document.getElementById('cartTotal');

            if (cart.length === 0) {
                container.innerHTML = '<p class="text-slate-400 text-center text-xs py-8">No items in cart</p>';
                totalInput.value = '';
                return;
            }

            let total = 0;
            let rows = '';
            cart.forEach((item, idx) => {
                const subtotal = item.price * item.qty;
                total += subtotal;
                rows += `
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
                    </tr>`;
            });

            container.innerHTML = `
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
                        <tbody class="divide-y divide-slate-100 text-slate-700">${rows}</tbody>
                    </table>
                </div>`;
            totalInput.value = '₱' + Number(total).toLocaleString();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        }

        function showStatusModal(type, title, message, onOk) {
            const modal = document.getElementById('statusModal');
            const iconWrap = document.getElementById('statusModalIcon');
            const iconGlyph = document.getElementById('statusModalIconGlyph');
            const okBtn = document.getElementById('statusModalOkBtn');
            const overlay = document.getElementById('statusModalOverlay');

            document.getElementById('statusModalTitle').textContent = title;
            document.getElementById('statusModalMessage').textContent = message;

            if (type === 'success') {
                iconWrap.className = 'mx-auto mb-4 w-12 h-12 rounded-full flex items-center justify-center bg-emerald-100';
                iconGlyph.className = 'fa-solid fa-circle-check text-xl text-emerald-600';
                okBtn.className = 'mt-5 w-full inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition';
            } else {
                iconWrap.className = 'mx-auto mb-4 w-12 h-12 rounded-full flex items-center justify-center bg-red-100';
                iconGlyph.className = 'fa-solid fa-triangle-exclamation text-xl text-red-600';
                okBtn.className = 'mt-5 w-full inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition';
            }

            modal.classList.remove('hidden');

            const close = () => {
                modal.classList.add('hidden');
                okBtn.removeEventListener('click', close);
                overlay.removeEventListener('click', close);
                if (typeof onOk === 'function') onOk();
            };

            okBtn.addEventListener('click', close);
            overlay.addEventListener('click', close);
        }

        async function checkout() {
            if (cart.length === 0) {
                showStatusModal('error', 'Cart is Empty', 'Add at least one product to the cart before checking out.');
                return;
            }

            const buyerInput = document.getElementById('buyerName');
            const buyerName = buyerInput.value.trim();

            if (!buyerName) {
                showStatusModal('error', 'Buyer Name Required', 'Please enter the customer\'s name before checking out.');
                return;
            }

            const payload = {
                buyer_name: buyerName,
                items: cart.map(item => ({ product_id: item.id, quantity: item.qty })),
            };

            const checkoutBtn = document.getElementById('checkoutBtn');
            checkoutBtn.disabled = true;

            try {
                const response = await fetch('{{ route('sales.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(payload),
                });

                const raw = await response.text();
                let data;
                try {
                    data = JSON.parse(raw);
                } catch (parseErr) {
                    // Server returned HTML (419 CSRF page, 404, 500, login redirect, etc.)
                    console.error('Non-JSON response from checkout:', response.status, raw);
                    showStatusModal('error', 'Checkout Failed', `The server returned an unexpected response (HTTP ${response.status}). Check the console for details.`);
                    return;
                }

                if (!response.ok) {
                    showStatusModal('error', 'Checkout Failed', data.message || `Something went wrong (HTTP ${response.status}).`);
                    return;
                }

                showStatusModal('success', 'Sale Recorded', 'The sale was checked out and saved successfully.', () => {
                    window.location.reload();
                });
            } catch (err) {
                console.error('Checkout request threw:', err);
                showStatusModal('error', 'Connection Error', 'Could not reach the server: ' + err.message);
            } finally {
                checkoutBtn.disabled = false;
            }
        }

        function filterSales() {
            const term = (document.getElementById('searchSales')?.value || '').toLowerCase();
            document.querySelectorAll('#salesTable tr').forEach(row => {
                row.style.display = (row.textContent || '').toLowerCase().includes(term) ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('addToCartBtn')?.addEventListener('click', addToCart);
            document.getElementById('checkoutBtn')?.addEventListener('click', checkout);
        });
    </script>
@endpush
