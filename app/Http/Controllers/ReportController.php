<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display sales report dashboard.
     */
    public function index(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->toDateString();

        // Daily sales
        $dailySales = Transaction::where('payment_status', 'Paid')
            ->whereBetween('paid_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(amount) as total_income')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Total summary
        $totalIncome = Transaction::where('payment_status', 'Paid')
            ->whereBetween('paid_at', [$dateFrom, $dateTo])
            ->sum('amount');

        $totalTransactions = Transaction::where('payment_status', 'Paid')
            ->whereBetween('paid_at', [$dateFrom, $dateTo])
            ->count();

        $totalOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        // Top selling menus
        $topMenus = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->where('orders.status', '!=', 'Pending')
            ->select(
                'menus.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'dailySales',
            'totalIncome',
            'totalTransactions',
            'totalOrders',
            'topMenus',
            'dateFrom',
            'dateTo'
        ));
    }
}
