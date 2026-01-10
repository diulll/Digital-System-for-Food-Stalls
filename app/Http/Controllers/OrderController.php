<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.menu'])->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::where('stock', '>', 0)->with('category')->get();
        return view('orders.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
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
            foreach ($request->menu_id as $index => $menuId) {
                $menu = Menu::findOrFail($menuId);
                $quantity = $request->quantity[$index];

                // Check stock
                if ($menu->stock < $quantity) {
                    throw new \Exception("Stok {$menu->name} tidak mencukupi.");
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

            // Create transaction
            Transaction::create([
                'order_id' => $order->id,
                'transaction_number' => Transaction::generateTransactionNumber(),
                'amount' => $totalPrice,
                'payment_status' => 'Pending',
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['orderItems.menu', 'transaction']);
        return view('orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
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

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Only allow deletion if order is still pending
        if ($order->status !== 'Pending') {
            return back()->with('error', 'Hanya pesanan dengan status Pending yang dapat dihapus.');
        }

        // Return stock
        foreach ($order->orderItems as $item) {
            $item->menu->increment('stock', $item->quantity);
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
}
