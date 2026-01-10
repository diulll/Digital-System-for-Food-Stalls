<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    /**
     * Display cashier dashboard
     */
    public function dashboard()
    {
        // Get all available menus with stock > 0
        $menus = Menu::with('category')
            ->where('stock', '>', 0)
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        // Get today's orders created by this cashier
        $todayOrders = Order::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cashier.dashboard', compact('menus', 'todayOrders'));
    }

    /**
     * Process checkout
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_number' => 'required|string|max:50',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);

        // Filter only items with quantity > 0
        $orderItems = [];
        $totalAmount = 0;

        foreach ($request->quantities as $menuId => $quantity) {
            if ($quantity > 0) {
                $menu = Menu::findOrFail($menuId);
                
                // Check stock availability
                if ($menu->stock < $quantity) {
                    return back()->with('error', "Stok {$menu->name} tidak cukup! Tersedia: {$menu->stock}");
                }

                $subtotal = $menu->price * $quantity;
                $orderItems[] = [
                    'menu_id' => $menuId,
                    'quantity' => $quantity,
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                ];
                
                $totalAmount += $subtotal;
            }
        }

        // Validate at least one item
        if (empty($orderItems)) {
            return back()->with('error', 'Pilih minimal 1 menu untuk pesanan!');
        }

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'table_number' => $request->table_number,
                'total_amount' => $totalAmount,
                'status' => 'completed', // Langsung completed karena kasir
            ]);

            // Create order items and update stock
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Decrease stock
                $menu = Menu::find($item['menu_id']);
                $menu->decrement('stock', $item['quantity']);
            }

            // Create transaction
            Transaction::create([
                'order_id' => $order->id,
                'transaction_number' => 'TRX-' . date('YmdHis') . '-' . $order->id,
                'amount' => $totalAmount,
                'payment_status' => 'Paid',
                'paid_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('cashier.receipt', $order->id)
                ->with('success', 'Pesanan berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display receipt
     */
    public function receipt($id)
    {
        $order = Order::with(['orderItems.menu.category', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id()) // Only show own orders
            ->firstOrFail();

        return view('cashier.receipt', compact('order'));
    }
}
