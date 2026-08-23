@extends('layouts.app')

@section('title', 'Reports - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />
        <x-page-header eyebrow="PSARECO Enterprise Reports" title="Enterprise Reports" description="Generate machinery, booking, sales, and inventory reports." icon="fa-solid fa-chart-line" />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 print:hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cart-shopping text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">Sales Income</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">
                    ₱{{ number_format($stats['monthly_sales_income'], 2) }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">This month</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-tractor text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">Booking Income</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">
                    ₱{{ number_format($stats['monthly_booking_income'], 2) }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">This month</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">Low Stock Items</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">
                    {{ number_format($stats['low_stock_count']) }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">At or below reorder level</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-warehouse text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">Inventory Value</span>
                </div>
                <p class="text-2xl font-bold text-slate-800">
                    ₱{{ number_format($stats['inventory_value'], 2) }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Current inventory</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden" x-data="reportsPage()">
            <div class="p-5 border-b border-slate-100 print:hidden">
                <form
                    method="GET"
                    action="{{ route('reports.generate') }}"
                    id="reportForm"
                    @submit="loading = true"
                >
                    <div class="flex flex-col lg:flex-row lg:items-end gap-4 lg:gap-6 pb-5 border-b border-slate-100">
                        <div class="grid grid-cols-2 gap-4 w-full lg:w-auto lg:min-w-[340px]">
                            <div>
                                <label for="startDate" class="block text-xs font-semibold text-slate-600 mb-1">
                                    Start Date
                                </label>
                                <input
                                    type="date"
                                    name="start_date"
                                    id="startDate"
                                    x-model="startDate"
                                    @change="validateDates()"
                                    required
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition"
                                >
                            </div>

                            <div>
                                <label for="endDate" class="block text-xs font-semibold text-slate-600 mb-1">
                                    End Date
                                </label>
                                <input
                                    type="date"
                                    name="end_date"
                                    id="endDate"
                                    x-model="endDate"
                                    :min="startDate"
                                    @change="validateDates()"
                                    required
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition"
                                >
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Quick Range
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="setRange('thisWeek')"
                                    class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                    This Week
                                </button>
                                <button type="button" @click="setRange('last2Weeks')"
                                    class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                    Last 2 Weeks
                                </button>
                                <button type="button" @click="setRange('month')"
                                    class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                    This Month
                                </button>
                                <button type="button" @click="setRange('lastMonth')"
                                    class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                    Last Month
                                </button>
                                <button type="button" @click="setRange('quarter')"
                                    class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                    Last 3 Months
                                </button>
                                <button type="button" @click="setRange('year')"
                                    class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                    This Year
                                </button>
                            </div>
                            <p
                                x-show="dateError"
                                x-cloak
                                class="text-[10px] text-red-500 mt-2 flex items-center gap-1"
                            >
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span x-text="dateError"></span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                    <i class="fa-solid fa-tractor"></i>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        Machinery Reports
                                    </h3>
                                    <p class="text-[11px] text-slate-400">
                                        Machinery and booking records
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="block cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="types[]"
                                        value="machinery"
                                        x-model="types"
                                        class="sr-only"
                                    >

                                    <div
                                        class="flex items-center gap-3 p-3 rounded-xl border transition"
                                        :class="types.includes('machinery')
                                            ? 'bg-emerald-50 border-emerald-500 text-emerald-700'
                                            : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300'"
                                    >
                                        <div
                                            class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                            :class="types.includes('machinery')
                                                ? 'bg-emerald-100'
                                                : 'bg-slate-100'"
                                        >
                                            <i class="fa-solid fa-tractor"></i>
                                        </div>

                                        <div class="flex-1">
                                            <p class="text-xs font-bold">Machinery Report</p>
                                            <p class="text-[10px] text-slate-400">
                                                Existing machinery and equipment
                                            </p>
                                        </div>

                                        <i
                                            class="fa-solid shrink-0"
                                            :class="types.includes('machinery')
                                                ? 'fa-circle-check text-emerald-600'
                                                : 'fa-circle text-slate-300'"
                                        ></i>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="types[]"
                                        value="bookings"
                                        x-model="types"
                                        class="sr-only"
                                    >

                                    <div
                                        class="flex items-center gap-3 p-3 rounded-xl border transition"
                                        :class="types.includes('bookings')
                                            ? 'bg-emerald-50 border-emerald-500 text-emerald-700'
                                            : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300'"
                                    >
                                        <div
                                            class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                            :class="types.includes('bookings')
                                                ? 'bg-emerald-100'
                                                : 'bg-slate-100'"
                                        >
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </div>

                                        <div class="flex-1">
                                            <p class="text-xs font-bold">Bookings Report</p>
                                            <p class="text-[10px] text-slate-400">
                                                Completed machinery bookings and income
                                            </p>
                                        </div>

                                        <i
                                            class="fa-solid shrink-0"
                                            :class="types.includes('bookings')
                                                ? 'fa-circle-check text-emerald-600'
                                                : 'fa-circle text-slate-300'"
                                        ></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                                    <i class="fa-solid fa-chart-column"></i>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        Sales Reports
                                    </h3>
                                    <p class="text-[11px] text-slate-400">
                                        Inventory sales and existing stock
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="block cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="types[]"
                                        value="sales"
                                        x-model="types"
                                        class="sr-only"
                                    >

                                    <div
                                        class="flex items-center gap-3 p-3 rounded-xl border transition"
                                        :class="types.includes('sales')
                                            ? 'bg-blue-50 border-blue-500 text-blue-700'
                                            : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300'"
                                    >
                                        <div
                                            class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                            :class="types.includes('sales')
                                                ? 'bg-blue-100'
                                                : 'bg-slate-100'"
                                        >
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </div>

                                        <div class="flex-1">
                                            <p class="text-xs font-bold">Sales Report</p>
                                            <p class="text-[10px] text-slate-400">
                                                Sales transactions and income
                                            </p>
                                        </div>

                                        <i
                                            class="fa-solid shrink-0"
                                            :class="types.includes('sales')
                                                ? 'fa-circle-check text-blue-600'
                                                : 'fa-circle text-slate-300'"
                                        ></i>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="types[]"
                                        value="inventory"
                                        x-model="types"
                                        class="sr-only"
                                    >

                                    <div
                                        class="flex items-center gap-3 p-3 rounded-xl border transition"
                                        :class="types.includes('inventory')
                                            ? 'bg-blue-50 border-blue-500 text-blue-700'
                                            : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300'"
                                    >
                                        <div
                                            class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                            :class="types.includes('inventory')
                                                ? 'bg-blue-100'
                                                : 'bg-slate-100'"
                                        >
                                            <i class="fa-solid fa-boxes-stacked"></i>
                                        </div>

                                        <div class="flex-1">
                                            <p class="text-xs font-bold">Inventory Report</p>
                                            <p class="text-[10px] text-slate-400">
                                                Existing inventory and stock levels
                                            </p>
                                        </div>

                                        <i
                                            class="fa-solid shrink-0"
                                            :class="types.includes('inventory')
                                                ? 'fa-circle-check text-blue-600'
                                                : 'fa-circle text-slate-300'"
                                        ></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mt-6 pt-5 border-t border-slate-100">
                        <button
                            type="button"
                            @click="preview()"
                            :disabled="loading"
                            class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 disabled:opacity-50 text-slate-700 font-semibold text-xs py-2.5 px-5 rounded-xl border border-slate-200 shadow-sm transition sm:min-w-[160px]"
                        >
                            <i
                                class="fa-solid"
                                :class="loading ? 'fa-spinner fa-spin' : 'fa-eye'"
                            ></i>

                            <span x-text="loading ? 'Loading...' : 'Preview Reports'"></span>
                        </button>

                        <button
                            type="submit"
                            :disabled="types.length === 0"
                            class="inline-flex flex-1 items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold text-xs py-2.5 px-5 rounded-xl shadow-sm transition"
                        >
                            <i class="fa-solid fa-file-word"></i>
                            Generate Selected Reports (.docx)
                        </button>
                    </div>

                    <p
                        x-show="types.length === 0"
                        x-cloak
                        class="mt-3 text-[11px] text-amber-600 flex items-center gap-2"
                    >
                        <i class="fa-solid fa-circle-info"></i>
                        Select at least one report to continue.
                    </p>
                </form>
            </div>

            <div class="p-5">
                <div
                    x-show="!previewed"
                    class="text-center py-16"
                >
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-file-lines text-2xl text-slate-300"></i>
                    </div>

                    <p class="text-sm font-semibold text-slate-500">
                        No preview yet
                    </p>

                    <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                        Select the reports you want to generate, choose a date range,
                        then click Preview Reports.
                    </p>
                </div>

                <div x-show="previewed" x-cloak class="space-y-5">

                    <div x-show="types.includes('machinery')" x-cloak
                         class="border border-slate-100 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 bg-slate-50/70 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-tractor text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Machinery Report</h3>
                                    <p class="text-[11px] text-slate-400">Existing machinery and equipment</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400" x-text="machinery.length + ' item(s)'"></span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 text-left">
                                        <th class="py-3 px-3 font-semibold">Machinery</th>
                                        <th class="py-3 px-3 font-semibold">Model</th>
                                        <th class="py-3 px-3 font-semibold">Serial Number</th>
                                        <th class="py-3 px-3 font-semibold text-right">Price</th>
                                        <th class="py-3 px-3 font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in machinery" :key="row.machinery_name + row.serial_number + index">
                                        <tr class="border-b border-slate-50 last:border-b-0">
                                            <td class="py-2.5 px-3 font-semibold text-slate-700" x-text="row.machinery_name"></td>
                                            <td class="py-2.5 px-3 text-slate-600" x-text="row.model || '-'"></td>
                                            <td class="py-2.5 px-3 text-slate-600" x-text="row.serial_number || '-'"></td>
                                            <td class="py-2.5 px-3 text-right" x-text="'₱' + row.price"></td>
                                            <td class="py-2.5 px-3">
                                                <span class="inline-flex px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-[10px] font-semibold" x-text="row.status || '-'"></span>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="machinery.length === 0">
                                        <td colspan="5" class="py-8 text-center text-slate-400">No machinery found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="types.includes('bookings')" x-cloak
                         class="border border-slate-100 rounded-2xl overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 bg-slate-50/70 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-calendar-check text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Bookings Report</h3>
                                    <p class="text-[11px] text-slate-400">Completed machinery bookings</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-[10px] text-slate-400">Booking Income</p>
                                <p class="text-sm font-bold text-emerald-600" x-text="'₱' + bookingsTotal"></p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 text-left">
                                        <th class="py-3 px-3 font-semibold">Machinery</th>
                                        <th class="py-3 px-3 font-semibold">Customer</th>
                                        <th class="py-3 px-3 font-semibold">Start</th>
                                        <th class="py-3 px-3 font-semibold">End</th>
                                        <th class="py-3 px-3 font-semibold text-right">Days</th>
                                        <th class="py-3 px-3 font-semibold text-right">Hours</th>
                                        <th class="py-3 px-3 font-semibold text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in bookings" :key="row.machinery_name + row.start_date + row.customer + index">
                                        <tr class="border-b border-slate-50 last:border-b-0">
                                            <td class="py-2.5 px-3 font-semibold text-slate-700" x-text="row.machinery_name"></td>
                                            <td class="py-2.5 px-3" x-text="row.customer"></td>
                                            <td class="py-2.5 px-3" x-text="row.start_date"></td>
                                            <td class="py-2.5 px-3" x-text="row.end_date"></td>
                                            <td class="py-2.5 px-3 text-right" x-text="row.days"></td>
                                            <td class="py-2.5 px-3 text-right" x-text="row.total_hours"></td>
                                            <td class="py-2.5 px-3 text-right font-semibold" x-text="'₱' + row.total_amount"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="bookings.length === 0">
                                        <td colspan="7" class="py-8 text-center text-slate-400">No completed bookings found for this period.</td>
                                    </tr>
                                </tbody>
                                <tfoot x-show="bookings.length > 0">
                                    <tr class="bg-emerald-50 font-semibold text-slate-700">
                                        <td colspan="6" class="py-3 px-3 text-right">Total Booking Income</td>
                                        <td class="py-3 px-3 text-right text-emerald-700" x-text="'₱' + bookingsTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div x-show="types.includes('sales')" x-cloak
                         class="border border-slate-100 rounded-2xl overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 bg-slate-50/70 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Sales Report</h3>
                                    <p class="text-[11px] text-slate-400">Inventory sales and income</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-[10px] text-slate-400">Income from Sales</p>
                                <p class="text-sm font-bold text-blue-600" x-text="'₱' + salesTotal"></p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 text-left">
                                        <th class="py-3 px-3 font-semibold">Date</th>
                                        <th class="py-3 px-3 font-semibold">Product</th>
                                        <th class="py-3 px-3 font-semibold">Buyer</th>
                                        <th class="py-3 px-3 font-semibold text-right">Quantity</th>
                                        <th class="py-3 px-3 font-semibold text-right">Price</th>
                                        <th class="py-3 px-3 font-semibold text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in sales" :key="row.sale_date + row.product_name + index">
                                        <tr class="border-b border-slate-50 last:border-b-0">
                                            <td class="py-2.5 px-3" x-text="row.sale_date"></td>
                                            <td class="py-2.5 px-3 font-semibold text-slate-700" x-text="row.product_name"></td>
                                            <td class="py-2.5 px-3" x-text="row.buyer_name"></td>
                                            <td class="py-2.5 px-3 text-right" x-text="row.quantity"></td>
                                            <td class="py-2.5 px-3 text-right" x-text="'₱' + row.price"></td>
                                            <td class="py-2.5 px-3 text-right font-semibold" x-text="'₱' + row.total"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="sales.length === 0">
                                        <td colspan="6" class="py-8 text-center text-slate-400">No sales transactions found for this period.</td>
                                    </tr>
                                </tbody>
                                <tfoot x-show="sales.length > 0">
                                    <tr class="bg-blue-50 font-semibold text-slate-700">
                                        <td colspan="5" class="py-3 px-3 text-right">Total Income from Sales</td>
                                        <td class="py-3 px-3 text-right text-blue-700" x-text="'₱' + salesTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div x-show="types.includes('inventory')" x-cloak
                         class="border border-slate-100 rounded-2xl overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 bg-slate-50/70 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-boxes-stacked text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Inventory Report</h3>
                                    <p class="text-[11px] text-slate-400">Existing inventory and current stock levels</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-[10px] text-slate-400">Current Inventory Value</p>
                                <p class="text-sm font-bold text-blue-600" x-text="'₱' + inventoryValue"></p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 text-left">
                                        <th class="py-3 px-3 font-semibold">Name</th>
                                        <th class="py-3 px-3 font-semibold">Type</th>
                                        <th class="py-3 px-3 font-semibold text-right">Quantity</th>
                                        <th class="py-3 px-3 font-semibold">Unit</th>
                                        <th class="py-3 px-3 font-semibold text-right">Price</th>
                                        <th class="py-3 px-3 font-semibold text-right">Value</th>
                                        <th class="py-3 px-3 font-semibold text-right">Reorder Level</th>
                                        <th class="py-3 px-3 font-semibold">Expiration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in inventory" :key="row.name + row.expiration + index">
                                        <tr class="border-b border-slate-50 last:border-b-0" :class="row.low_stock ? 'bg-red-50/40' : ''">
                                            <td class="py-2.5 px-3 font-semibold text-slate-700" x-text="row.name"></td>
                                            <td class="py-2.5 px-3" x-text="row.type || '-'"></td>
                                            <td class="py-2.5 px-3 text-right font-semibold" :class="row.low_stock ? 'text-red-600' : 'text-slate-700'" x-text="row.quantity"></td>
                                            <td class="py-2.5 px-3" x-text="row.unit || '-'"></td>
                                            <td class="py-2.5 px-3 text-right" x-text="'₱' + row.price"></td>
                                            <td class="py-2.5 px-3 text-right font-semibold" x-text="'₱' + row.inventory_value"></td>
                                            <td class="py-2.5 px-3 text-right" x-text="row.reorder_level"></td>
                                            <td class="py-2.5 px-3" x-text="row.expiration || '-'"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="inventory.length === 0">
                                        <td colspan="8" class="py-8 text-center text-slate-400">No existing inventory found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="text-[10px] text-slate-400 px-4 py-3 border-t border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                            Items shown in red are at or below their reorder level.
                        </p>
                    </div>

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
            types: [],
            loading: false,
            previewed: false,
            dateError: '',
            machinery: [],
            bookings: [],
            sales: [],
            inventory: [],
            bookingsTotal: '0.00',
            salesTotal: '0.00',
            inventoryValue: '0.00',
            income: null,

            setRange(preset) {
                const today = new Date();
                let start, end;

                if (preset === 'thisWeek') {
                    // Monday of the current week through today
                    const day = today.getDay(); // 0 = Sun ... 6 = Sat
                    const diffToMonday = (day === 0 ? -6 : 1) - day;
                    start = new Date(today);
                    start.setDate(start.getDate() + diffToMonday);
                    end = today;
                } else if (preset === 'last2Weeks') {
                    // rolling 14-day window ending today
                    start = new Date(today);
                    start.setDate(start.getDate() - 13);
                    end = today;
                } else if (preset === 'month') {
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                } else if (preset === 'lastMonth') {
                    start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    end = new Date(today.getFullYear(), today.getMonth(), 0);
                } else if (preset === 'quarter') {
                    start = new Date(today.getFullYear(), today.getMonth() - 2, 1);
                    end = today;
                } else if (preset === 'year') {
                    start = new Date(today.getFullYear(), 0, 1);
                    end = new Date(today.getFullYear(), 11, 31);
                }

                // Format using LOCAL date parts — never toISOString(), which
                // converts to UTC first and silently shifts the date back
                // a day for any timezone ahead of UTC (e.g. UTC+8).
                const pad = (n) => String(n).padStart(2, '0');
                const toInputDate = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

                this.startDate = toInputDate(start);
                this.endDate = toInputDate(end);
                this.validateDates();
            },

            validateDates() {
                this.dateError = '';

                if (!this.startDate || !this.endDate) {
                    return true;
                }

                if (this.endDate < this.startDate) {
                    this.dateError = 'End date cannot be earlier than the start date.';
                    return false;
                }

                return true;
            },

            async preview() {
                if (!this.startDate || !this.endDate) {
                    alert('Please select a start date and end date.');
                    return;
                }

                if (!this.validateDates()) {
                    return;
                }

                if (this.types.length === 0) {
                    alert('Please select at least one report.');
                    return;
                }

                this.loading = true;

                const params = new URLSearchParams();

                params.append('start_date', this.startDate);
                params.append('end_date', this.endDate);

                this.types.forEach(type => {
                    params.append('types[]', type);
                });

                try {
                    const response = await fetch(
                        `{{ route('reports.preview') }}?${params.toString()}`,
                        {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );

                    if (!response.ok) {
                        throw new Error('Preview request failed.');
                    }

                    const data = await response.json();

                    this.machinery = data.machinery ?? [];
                    this.bookings = data.bookings ?? [];
                    this.sales = data.sales ?? [];
                    this.inventory = data.inventory ?? [];

                    this.bookingsTotal = data.bookings_total ?? '0.00';
                    this.salesTotal = data.sales_total ?? '0.00';
                    this.inventoryValue = data.inventory_value ?? '0.00';

                    this.income = data.income ?? null;
                    this.previewed = true;
                } catch (error) {
                    console.error(error);
                    alert('Could not load the report preview. Please try again.');
                } finally {
                    this.loading = false;
                }
            }
        };
    }
</script>
@endpush
