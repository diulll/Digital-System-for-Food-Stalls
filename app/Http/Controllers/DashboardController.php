<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard for Admin and Cashier
     */
    public function index()
    {
        $user = auth()->user();

        // Statistics
        $stats = [
            'total_categories' => Category::count(),
            'total_menus' => Menu::count(),
            'total_orders' => Order::count(),
            'total_transactions' => Transaction::where('payment_status', 'Paid')->count(),
            'total_income' => Transaction::where('payment_status', 'Paid')->sum('amount'),
            'pending_orders' => Order::where('status', 'Pending')->count(),
        ];

        // Add user count for Admin only
        if ($user->role && $user->role->name === 'Admin') {
            $stats['total_users'] = User::count();
        }

        // Recent orders
        $recentOrders = Order::with(['user', 'orderItems.menu'])
            ->latest()
            ->take(5)
            ->get();

        // Low stock alerts
        $lowStockMenus = Menu::where('stock', '<', 10)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // Top selling menus
        $topMenus = Menu::select('menus.name', DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'))
            ->leftJoin('order_items', 'menus.id', '=', 'order_items.menu_id')
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Sales per category
        $salesByCategory = Category::select('categories.name', DB::raw('COALESCE(COUNT(DISTINCT orders.id), 0) as total_orders'))
            ->leftJoin('menus', 'categories.id', '=', 'menus.category_id')
            ->leftJoin('order_items', 'menus.id', '=', 'order_items.menu_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Monthly revenue (last 6 months)
        $monthlyRevenue = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->where('payment_status', 'Paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentOrders',
            'lowStockMenus',
            'topMenus',
            'salesByCategory',
            'monthlyRevenue'
        ));
    }
}
