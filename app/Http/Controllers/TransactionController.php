<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['order.user', 'order.orderItems']);

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['order.user', 'order.orderItems.menu']);
        return view('transactions.show', compact('transaction'));
    }
}
