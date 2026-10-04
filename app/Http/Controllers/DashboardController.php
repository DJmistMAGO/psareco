<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Inventory;
use App\Models\Machinery;
use App\Models\Sales;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data for Admin & Officer Roles
        $totalInventory = Inventory::count();
        $expiringCount = Inventory::whereNotNull('expiration_date')->whereBetween('expiration_date', [
            now()->startOfDay(),
            now()->addDays(30)->endOfDay(),
        ])->count();

        $lowStockCount = Inventory::whereColumn('quantity', '<=', 'reorder_level')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $upcomingBookings = Booking::with(['user', 'machine'])
            ->whereDate('start_date', '>=', now()->toDateString())
            ->whereNotIn('status', ['cancelled', 'rejected', 'declined'])
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        $totalSales = Sales::sum('total');
        $recentSales = Sales::with('product')->latest('sale_date')->take(5)->get();

        $lowStockItems = Inventory::whereColumn('quantity', '<=', 'reorder_level')->orderBy('quantity', 'asc')->take(5)->get();

        $expiringItems = Inventory::whereNotNull('expiration_date')->whereBetween('expiration_date', [
            now()->startOfDay(),
            now()->addDays(30)->endOfDay(),
        ])->orderBy('expiration_date', 'asc')->take(5)->get();

        $monthlySales = Sales::selectRaw('MONTH(sale_date) as month, SUM(total) as total')
            ->whereYear('sale_date', now()->year)
            ->groupByRaw('MONTH(sale_date)')
            ->orderByRaw('MONTH(sale_date)')
            ->pluck('total', 'month');

        $adminMetrics = [];
        if ($user->hasRole('admin')) {
            $adminMetrics = [
                'totalUsers' => User::count(),
                'farmerUsers' => User::role('farmer')->count(),
                'officerUsers' => User::role('officer')->count(),
                'totalMachinery' => Machinery::count(),
                'totalArchivedItems' => Inventory::onlyTrashed()->count() + Machinery::onlyTrashed()->count(),
                'totalSalesTransactions' => Sales::count(),
                'totalUnitsSold' => Sales::sum('quantity') ?? 0,
                'totalUnitsInStock' => Inventory::sum('quantity') ?? 0,
                'inventoryValue' => Inventory::query()
                    ->selectRaw('COALESCE(SUM(quantity * COALESCE(price, 0)), 0) as inventory_value')
                    ->value('inventory_value'),
                'inventoryStockItems' => Inventory::orderByDesc('quantity')
                    ->take(5)
                    ->get(['name', 'quantity', 'unit']),
                'topSellingProducts' => Sales::query()
                    ->leftJoin('inventories', 'sales.product_id', '=', 'inventories.id')
                    ->selectRaw("COALESCE(NULLIF(sales.product_name, ''), inventories.name, ?) as product_name", ['Unknown product'])
                    ->selectRaw('SUM(COALESCE(sales.quantity, 0)) as units_sold')
                    ->selectRaw('SUM(COALESCE(sales.total, 0)) as revenue')
                    ->groupBy('sales.product_id', 'sales.product_name', 'inventories.name')
                    ->orderByDesc('units_sold')
                    ->orderByDesc('revenue')
                    ->take(5)
                    ->get(),
                'topBookedMachinery' => Booking::query()
                    ->join('machineries', 'bookings.machine_id', '=', 'machineries.id')
                    ->select('machineries.id', 'machineries.machinery_name')
                    ->selectRaw('COUNT(bookings.id) as booking_count')
                    ->whereNotIn('bookings.status', ['Declined', 'declined', 'Cancelled', 'cancelled', 'Rejected', 'rejected'])
                    ->groupBy('machineries.id', 'machineries.machinery_name')
                    ->orderByDesc('booking_count')
                    ->orderBy('machineries.machinery_name')
                    ->take(5)
                    ->get(),
            ];
        }

        $salesLabels = collect(range(1, 12))->map(fn($month) => Carbon::create()->month($month)->format('M'))->toArray();
        $salesData = collect(range(1, 12))->map(fn($month) => (float) ($monthlySales[$month] ?? 0))->toArray();

        // Data specifically for Farmer Role
        $myActiveBookingsCount = Booking::where('user_id', $user->id)
            ->whereIn('status', ['Approved'])
            ->whereDate('start_date', '>=', now()->toDateString())
            ->count();


        $myPendingBookingsCount = Booking::where('user_id', $user->id)
            ->where('status', 'Pending')
            ->count();

        $myBookings = Booking::with('machine')
            ->where('user_id', $user->id)
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalInventory',
            'expiringCount',
            'lowStockCount',
            'pendingBookings',
            'upcomingBookings',
            'recentSales',
            'lowStockItems',
            'expiringItems',
            'totalSales',
            'salesLabels',
            'salesData',
            'myActiveBookingsCount',
            'myPendingBookingsCount',
            'myBookings',
        ))->with($adminMetrics);
    }
}
