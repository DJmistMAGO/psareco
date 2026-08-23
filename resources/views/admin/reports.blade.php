@extends('layouts.app')

@section('title', 'Reports - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />
        <x-page-header eyebrow="PSARECO Enterprise Reports" title="Enterprise Reports" description="Comprehensive financial summaries, equipment utilization, and maintenance logs" icon="fa-solid fa-chart-line" />

        <!-- Quick Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 print:hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500">Revenue (This Month)</span>
                    <i class="fa-solid fa-coins text-emerald-600"></i>
                </div>
                <p class="text-2xl font-bold text-slate-800">₱{{ number_format($stats['monthly_revenue'], 2) }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500">Completed Bookings</span>
                    <i class="fa-solid fa-calendar-check text-emerald-600"></i>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['monthly_bookings']) }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500">Low Stock Items</span>
                    <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['low_stock_count']) }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500">Inventory Value</span>
                    <i class="fa-solid fa-warehouse text-emerald-600"></i>
                </div>
                <p class="text-2xl font-bold text-slate-800">₱{{ number_format($stats['inventory_value'], 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden" x-data="reportsPage()">

            <div class="p-5 border-b border-slate-100 space-y-5 print:hidden">
                <form method="GET" action="{{ route('reports.generate') }}" id="reportForm" @submit="loading = true">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end mb-5">
                        <div>
                            <label for="startDate" class="block text-xs font-semibold text-slate-600 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="startDate" x-model="startDate" required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label for="endDate" class="block text-xs font-semibold text-slate-600 mb-1">End Date</label>
                            <input type="date" name="end_date" id="endDate" x-model="endDate" required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        </div>

                        <div class="sm:col-span-2 flex items-center gap-3">
                            <button type="button" @click="preview()"
                                class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs py-2.5 px-4 rounded-xl border border-slate-200 shadow-sm transition">
                                <i class="fa-solid fa-eye" :class="{ 'fa-spin fa-spinner': loading }"></i>
                                <span x-text="loading ? 'Loading...' : 'Preview'"></span>
                            </button>

                            <button type="submit"
                                class="flex-1 inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm transition">
                                <i class="fa-solid fa-file-word"></i> Generate (.docx)
                            </button>
                        </div>
                    </div>

                    <!-- Report Type Selector (multi-select chips) -->
                    <div class="pb-1">
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Report Type</label>
                        <div class="flex flex-wrap gap-3 text-xs font-semibold">

                            <label class="cursor-pointer">
                                <input type="checkbox" name="types[]" value="financial" x-model="types" class="peer sr-only">
                                <span class="inline-flex items-center gap-2 py-2.5 px-4 rounded-xl border transition-colors"
                                        :class="types.includes('financial') ? 'bg-emerald-50 border-emerald-600 text-emerald-700' : 'bg-white border-slate-200 text-slate-500 hover:border-slate-300'">
                                    <i class="fa-solid fa-coins"></i> Financial / Sales
                                </span>
                            </label>

                            <label class="cursor-pointer">
                                <input type="checkbox" name="types[]" value="machinery" x-model="types" class="peer sr-only">
                                <span class="inline-flex items-center gap-2 py-2.5 px-4 rounded-xl border transition-colors"
                                        :class="types.includes('machinery') ? 'bg-emerald-50 border-emerald-600 text-emerald-700' : 'bg-white border-slate-200 text-slate-500 hover:border-slate-300'">
                                    <i class="fa-solid fa-tractor"></i> Machinery / Inventory
                                </span>
                            </label>

                        </div>
                        <p class="text-[11px] text-slate-400 mt-2" x-show="types.length === 0">
                            Select at least one report type.
                        </p>
                    </div>
                </form>
            </div>

            <!-- Preview Panel -->
            <div class="p-5">

                <!-- Tabs (only shown once both types are previewed) -->
                <div class="border-b border-slate-100 mb-4 print:hidden" x-show="previewed && types.length > 1">
                    <nav class="flex space-x-6 text-xs font-semibold" aria-label="Tabs">
                        <button type="button" @click="activeTab = 'financial'" x-show="types.includes('financial')"
                            :class="activeTab === 'financial' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                            class="py-3 px-1 border-b-2 flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-coins"></i> Financial
                        </button>

                        <button type="button" @click="activeTab = 'machinery'" x-show="types.includes('machinery')"
                            :class="activeTab === 'machinery' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                            class="py-3 px-1 border-b-2 flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-tractor"></i> Machinery
                        </button>
                    </nav>
                </div>

                <!-- Empty state -->
                <div x-show="!previewed" class="text-center py-16">
                    <i class="fa-solid fa-file-word text-3xl text-slate-300 mb-3"></i>
                    <p class="text-sm font-semibold text-slate-500">No preview yet</p>
                    <p class="text-xs text-slate-400 mt-1">Pick a date range and click Preview, or generate the .docx directly.</p>
                </div>

                <!-- Financial table -->
                <div x-show="previewed && activeTab === 'financial' && types.includes('financial')" class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-left">
                                <th class="py-2 px-3 font-semibold">Machine</th>
                                <th class="py-2 px-3 font-semibold">Customer</th>
                                <th class="py-2 px-3 font-semibold">Start</th>
                                <th class="py-2 px-3 font-semibold">End</th>
                                <th class="py-2 px-3 font-semibold text-right">Days</th>
                                <th class="py-2 px-3 font-semibold text-right">Hours</th>
                                <th class="py-2 px-3 font-semibold text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="row in bookings" :key="row.machine + row.start_date + row.customer">
                                <tr class="border-b border-slate-50">
                                    <td class="py-2 px-3" x-text="row.machine"></td>
                                    <td class="py-2 px-3" x-text="row.customer"></td>
                                    <td class="py-2 px-3" x-text="row.start_date"></td>
                                    <td class="py-2 px-3" x-text="row.end_date"></td>
                                    <td class="py-2 px-3 text-right" x-text="row.days"></td>
                                    <td class="py-2 px-3 text-right" x-text="row.total_hours"></td>
                                    <td class="py-2 px-3 text-right" x-text="'₱' + row.total_amount"></td>
                                </tr>
                            </template>
                            <tr x-show="bookings.length === 0">
                                <td colspan="7" class="py-6 text-center text-slate-400">No completed bookings in this period.</td>
                            </tr>
                        </tbody>
                        <tfoot x-show="bookings.length > 0">
                            <tr class="bg-slate-50 font-semibold text-slate-700">
                                <td colspan="6" class="py-2 px-3 text-right">Total Revenue</td>
                                <td class="py-2 px-3 text-right" x-text="'₱' + bookingsTotal"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Machinery table -->
                <div x-show="previewed && activeTab === 'machinery' && types.includes('machinery')" class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-left">
                                <th class="py-2 px-3 font-semibold">Name</th>
                                <th class="py-2 px-3 font-semibold">Type</th>
                                <th class="py-2 px-3 font-semibold text-right">Qty</th>
                                <th class="py-2 px-3 font-semibold">Unit</th>
                                <th class="py-2 px-3 font-semibold text-right">Price</th>
                                <th class="py-2 px-3 font-semibold text-right">Reorder Lvl</th>
                                <th class="py-2 px-3 font-semibold">Expiration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="row in inventory" :key="row.name + row.expiration">
                                <tr class="border-b border-slate-50" :class="row.low_stock ? 'bg-red-50/40' : ''">
                                    <td class="py-2 px-3" x-text="row.name"></td>
                                    <td class="py-2 px-3" x-text="row.type"></td>
                                    <td class="py-2 px-3 text-right font-semibold" :class="row.low_stock ? 'text-red-600' : ''" x-text="row.quantity"></td>
                                    <td class="py-2 px-3" x-text="row.unit"></td>
                                    <td class="py-2 px-3 text-right" x-text="'₱' + row.price"></td>
                                    <td class="py-2 px-3 text-right" x-text="row.reorder_level"></td>
                                    <td class="py-2 px-3" x-text="row.expiration || '-'"></td>
                                </tr>
                            </template>
                            <tr x-show="inventory.length === 0">
                                <td colspan="7" class="py-6 text-center text-slate-400">No inventory items in this period.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>

@endsection

@push('scripts')
<script>
    function reportsPage() {
        return {
            startDate: '',
            endDate: '',
            types: ['financial', 'machinery'],
            activeTab: 'financial',
            loading: false,
            previewed: false,
            bookings: [],
            inventory: [],
            bookingsTotal: '0.00',

            async preview() {
                if (!this.startDate || !this.endDate || this.types.length === 0) {
                    alert('Please select a date range and at least one report type.');
                    return;
                }

                this.loading = true;

                const params = new URLSearchParams();
                params.append('start_date', this.startDate);
                params.append('end_date', this.endDate);
                this.types.forEach(t => params.append('types[]', t));

                try {
                    const res = await fetch(`{{ route('reports.preview') }}?${params.toString()}`, {
                        headers: { 'Accept': 'application/json' },
                    });

                    if (!res.ok) throw new Error('Preview request failed');

                    const data = await res.json();

                    this.bookings = data.bookings ?? [];
                    this.bookingsTotal = data.bookings_total ?? '0.00';
                    this.inventory = data.inventory ?? [];
                    this.activeTab = this.types.includes('financial') ? 'financial' : 'machinery';
                    this.previewed = true;
                } catch (e) {
                    alert('Could not load preview. Please try again.');
                } finally {
                    this.loading = false;
                }
            }
        };
    }
</script>
@endpush
