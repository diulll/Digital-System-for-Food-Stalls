<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display all orders (Admin only).
     * 
     * GET /api/v1/admin/orders
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user', 'orderItems.menu', 'transaction']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        $perPage = $request->get('per_page', 15);
        $orders = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pesanan berhasil diambil',
            'data' => $orders,
        ], 200);
    }

    /**
     * Display orders for current user (Cashier).
     * 
     * GET /api/v1/orders
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function myOrders(Request $request): JsonResponse
    {
        $query = Order::with(['orderItems.menu', 'transaction'])
            ->where('user_id', auth()->id());

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $perPage = $request->get('per_page', 15);
        $orders = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pesanan berhasil diambil',
            'data' => $orders,
        ], 200);
    }

    /**
     * Store a newly created order.
     * 
     * POST /api/v1/orders
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,transfer,qris'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

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

                // Check stock availability
                if ($menu->stock < $quantity) {
                    throw new \Exception("Stok {$menu->name} tidak mencukupi. Tersisa: {$menu->stock}");
                }

                // Check if menu is available
                if (!$menu->is_available) {
                    throw new \Exception("Menu {$menu->name} sedang tidak tersedia");
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
                throw new \Exception('Jumlah pembayaran kurang dari total harga. Total: Rp ' . number_format($totalPrice, 0, ',', '.'));
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

            // Update order status
            $order->update(['status' => 'Paid']);

            DB::commit();

            // Load relationships for response
            $order->load(['orderItems.menu', 'transaction']);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'order' => $order,
                    'transaction' => $transaction,
                    'payment' => [
                        'total' => $totalPrice,
                        'paid' => $request->amount_paid,
                        'change' => $change,
                    ],
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display the specified order.
     * 
     * GET /api/v1/orders/{id}
     * 
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        // Check authorization for non-admin
        $user = auth()->user();
        if ($user->role?->name !== 'Admin' && $order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $order->load(['user', 'orderItems.menu', 'transaction']);

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil diambil',
            'data' => $order,
        ], 200);
    }

    /**
     * Update order status.
     * 
     * PATCH /api/v1/orders/{id}/status
     * 
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:Pending,Paid,Completed,Cancelled'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order->update(['status' => $request->status]);

        // Update transaction if status is Paid
        if ($request->status === 'Paid' && $order->transaction) {
            $order->transaction->update([
                'payment_status' => 'Paid',
                'paid_at' => now(),
            ]);
        }

        // If cancelled, restore stock
        if ($request->status === 'Cancelled') {
            foreach ($order->orderItems as $item) {
                $item->menu->increment('stock', $item->quantity);
            }
            
            if ($order->transaction) {
                $order->transaction->update([
                    'payment_status' => 'Refunded',
                ]);
            }
        }

        $order->load(['orderItems.menu', 'transaction']);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui',
            'data' => $order,
        ], 200);
    }

    /**
     * Complete an order.
     * 
     * POST /api/v1/orders/{id}/complete
     * 
     * @param Order $order
     * @return JsonResponse
     */
    public function complete(Order $order): JsonResponse
    {
        if ($order->status !== 'Paid') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pesanan yang sudah dibayar yang dapat diselesaikan',
            ], 422);
        }

        $order->update(['status' => 'Completed']);
        $order->load(['orderItems.menu', 'transaction']);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diselesaikan',
            'data' => $order,
        ], 200);
    }
}
