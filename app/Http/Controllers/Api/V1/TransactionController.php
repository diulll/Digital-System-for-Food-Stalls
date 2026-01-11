<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display all transactions (Admin only).
     * 
     * GET /api/v1/admin/transactions
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with(['order.user', 'order.orderItems.menu']);

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = $request->get('per_page', 15);
        $transactions = $query->latest()->paginate($perPage);

        // Calculate summary
        $summaryQuery = Transaction::query();
        if ($request->has('date_from') && $request->date_from != '') {
            $summaryQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $summaryQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $summary = [
            'total_transactions' => $summaryQuery->count(),
            'total_revenue' => $summaryQuery->where('payment_status', 'Paid')->sum('amount'),
            'paid_count' => $summaryQuery->clone()->where('payment_status', 'Paid')->count(),
            'pending_count' => Transaction::where('payment_status', 'Pending')->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi berhasil diambil',
            'data' => $transactions,
            'summary' => $summary,
        ], 200);
    }

    /**
     * Display transactions for current user (Cashier).
     * 
     * GET /api/v1/transactions
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function myTransactions(Request $request): JsonResponse
    {
        $query = Transaction::with(['order.orderItems.menu'])
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            });

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date (default today)
        $date = $request->get('date', today()->toDateString());
        $query->whereDate('created_at', $date);

        $perPage = $request->get('per_page', 15);
        $transactions = $query->latest()->paginate($perPage);

        // Summary for the day
        $summary = [
            'date' => $date,
            'total_transactions' => $transactions->total(),
            'total_revenue' => Transaction::whereHas('order', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->whereDate('created_at', $date)
                ->where('payment_status', 'Paid')
                ->sum('amount'),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi berhasil diambil',
            'data' => $transactions,
            'summary' => $summary,
        ], 200);
    }

    /**
     * Display the specified transaction.
     * 
     * GET /api/v1/transactions/{id}
     * 
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $user = auth()->user();
        
        // Check authorization for non-admin
        if ($user->role?->name !== 'Admin' && $transaction->order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $transaction->load(['order.user', 'order.orderItems.menu']);

        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi berhasil diambil',
            'data' => $transaction,
        ], 200);
    }

    /**
     * Get daily sales report (Admin only).
     * 
     * GET /api/v1/admin/reports/daily
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function dailyReport(Request $request): JsonResponse
    {
        $date = $request->get('date', today()->toDateString());

        $transactions = Transaction::with(['order.orderItems.menu'])
            ->whereDate('created_at', $date)
            ->where('payment_status', 'Paid')
            ->get();

        // Group by hour
        $hourlyData = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('H:00');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('amount'),
            ];
        });

        // Top selling menus
        $menuSales = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->order->orderItems as $item) {
                $menuId = $item->menu_id;
                if (!isset($menuSales[$menuId])) {
                    $menuSales[$menuId] = [
                        'menu' => $item->menu->name,
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                $menuSales[$menuId]['quantity'] += $item->quantity;
                $menuSales[$menuId]['revenue'] += $item->subtotal;
            }
        }

        // Sort by quantity
        usort($menuSales, fn($a, $b) => $b['quantity'] - $a['quantity']);
        $topMenus = array_slice($menuSales, 0, 10);

        return response()->json([
            'success' => true,
            'message' => 'Laporan harian berhasil diambil',
            'data' => [
                'date' => $date,
                'summary' => [
                    'total_transactions' => $transactions->count(),
                    'total_revenue' => $transactions->sum('amount'),
                    'average_transaction' => $transactions->count() > 0 
                        ? round($transactions->sum('amount') / $transactions->count(), 2) 
                        : 0,
                ],
                'hourly_breakdown' => $hourlyData,
                'top_menus' => $topMenus,
            ],
        ], 200);
    }

    /**
     * Get monthly sales report (Admin only).
     * 
     * GET /api/v1/admin/reports/monthly
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function monthlyReport(Request $request): JsonResponse
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $transactions = Transaction::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('payment_status', 'Paid')
            ->get();

        // Daily breakdown
        $dailyData = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y-m-d');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('amount'),
            ];
        });

        // Weekly breakdown
        $weeklyData = $transactions->groupBy(function ($transaction) {
            return 'Week ' . $transaction->created_at->weekOfMonth;
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('amount'),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Laporan bulanan berhasil diambil',
            'data' => [
                'month' => $month,
                'year' => $year,
                'summary' => [
                    'total_transactions' => $transactions->count(),
                    'total_revenue' => $transactions->sum('amount'),
                    'average_daily_revenue' => $dailyData->count() > 0 
                        ? round($transactions->sum('amount') / $dailyData->count(), 2) 
                        : 0,
                ],
                'daily_breakdown' => $dailyData,
                'weekly_breakdown' => $weeklyData,
            ],
        ], 200);
    }
}
