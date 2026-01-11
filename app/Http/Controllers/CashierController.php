<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    /**
     * Display cashier dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Statistics for cashier
        $stats = [
            'total_orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'Pending')->count(),
            'completed_today' => Order::whereDate('created_at', today())->where('status', 'Completed')->count(),
            'income_today' => Transaction::whereDate('created_at', today())
                ->where('payment_status', 'Paid')
                ->sum('amount'),
        ];

        // Pending orders
        $pendingOrders = Order::with(['orderItems.menu'])
            ->where('status', 'Pending')
            ->latest()
            ->take(10)
            ->get();

        // Recent transactions today
        $recentTransactions = Transaction::with(['order.orderItems.menu'])
            ->whereDate('created_at', today())
            ->where('payment_status', 'Paid')
            ->latest()
            ->take(5)
            ->get();

        return view('cashier.dashboard', compact('stats', 'pendingOrders', 'recentTransactions'));
    }

    /**
     * Display POS (Point of Sale) page.
     */
    public function pos()
    {
        $categories = Category::all();
        $menus = Menu::where('stock', '>', 0)
            ->where('is_available', true)
            ->with('category')
            ->get();

        return view('cashier.pos', compact('categories', 'menus'));
    }

    /**
     * Store new order from POS.
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,transfer,qris',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'total_price' => 0,
                'status' => 'Pending',
            ]);

            $totalPrice = 0;

            // Create order items
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $quantity = $item['quantity'];

                // Check stock
                if ($menu->stock < $quantity) {
                    throw new \Exception("Stok {$menu->name} tidak mencukupi. Tersisa: {$menu->stock}");
                }

                $subtotal = $menu->price * $quantity;
                $totalPrice += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                ]);

                // Reduce stock
                $menu->decrement('stock', $quantity);
            }

            // Update total price
            $order->update(['total_price' => $totalPrice]);

            // Check if amount paid is sufficient
            if ($request->amount_paid < $totalPrice) {
                throw new \Exception('Jumlah pembayaran kurang dari total harga.');
            }

            $change = $request->amount_paid - $totalPrice;

            // Create transaction
            $transaction = Transaction::create([
                'order_id' => $order->id,
                'transaction_number' => Transaction::generateTransactionNumber(),
                'amount' => $totalPrice,
                'payment_status' => 'Paid',
                'paid_at' => now(),
            ]);

            // Update order status to Paid
            $order->update(['status' => 'Paid']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order' => $order->load('orderItems.menu'),
                'transaction' => $transaction,
                'change' => $change,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get order details for receipt.
     */
    public function getOrderReceipt(Order $order)
    {
        $order->load(['orderItems.menu', 'transaction']);
        
        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Paid,Completed',
        ]);

        $order->update(['status' => $request->status]);

        // Update transaction if status is Paid
        if ($request->status === 'Paid' && $order->transaction) {
            $order->transaction->update([
                'payment_status' => 'Paid',
                'paid_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'order' => $order->fresh(),
        ]);
    }

    /**
     * Display transaction history for cashier.
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['order.orderItems.menu']);

        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        } else {
            // Default to today
            $query->whereDate('created_at', today());
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        $transactions = $query->latest()->paginate(15);

        // Summary
        $summary = [
            'total_transactions' => $transactions->total(),
            'total_amount' => Transaction::whereDate('created_at', $request->date ?? today())
                ->where('payment_status', 'Paid')
                ->sum('amount'),
        ];

        return view('cashier.transactions', compact('transactions', 'summary'));
    }

    /**
     * Display pending orders for cashier.
     */
    public function pendingOrders()
    {
        $orders = Order::with(['orderItems.menu'])
            ->whereIn('status', ['Pending', 'Paid'])
            ->latest()
            ->paginate(15);

        return view('cashier.pending-orders', compact('orders'));
    }

    /**
     * Complete an order.
     */
    public function completeOrder(Order $order)
    {
        if ($order->status !== 'Paid') {
            return back()->with('error', 'Hanya pesanan yang sudah dibayar yang dapat diselesaikan.');
        }

        $order->update(['status' => 'Completed']);

        return back()->with('success', 'Pesanan berhasil diselesaikan.');
    }

    /**
     * Get menu list for API.
     */
    public function getMenus(Request $request)
    {
        $query = Menu::where('stock', '>', 0)
            ->where('is_available', true)
            ->with('category');

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $menus = $query->get();

        return response()->json([
            'success' => true,
            'menus' => $menus,
        ]);
    }

    /**
     * Get categories list for API.
     */
    public function getCategories()
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'categories' => $categories,
        ]);
    }
}
