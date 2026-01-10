@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Detail Transaksi</h1>
        <p class="text-gray-600">{{ $transaction->transaction_number }}</p>
    </div>
    <a href="{{ route('transactions.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Status Pembayaran</h3>
        <span class="px-3 py-1 text-sm rounded-full inline-block
            @if($transaction->payment_status === 'Pending') bg-yellow-100 text-yellow-800
            @elseif($transaction->payment_status === 'Paid') bg-green-100 text-green-800
            @else bg-red-100 text-red-800
            @endif">
            {{ $transaction->payment_status }}
        </span>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Pelanggan</h3>
        <p class="text-xl font-semibold">{{ $transaction->order->user->name }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Total</h3>
        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">Informasi Transaksi</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-sm text-gray-600">Nomor Transaksi</p>
            <p class="text-lg font-semibold">{{ $transaction->transaction_number }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Nomor Pesanan</p>
            <p class="text-lg font-semibold">
                <a href="{{ route('orders.show', $transaction->order) }}" class="text-blue-600 hover:text-blue-800">
                    {{ $transaction->order->order_number }}
                </a>
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Tanggal Transaksi</p>
            <p class="text-lg">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        </div>
        @if($transaction->paid_at)
        <div>
            <p class="text-sm text-gray-600">Tanggal Pembayaran</p>
            <p class="text-lg">{{ $transaction->paid_at->format('d/m/Y H:i') }}</p>
        </div>
        @endif
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold">Detail Pesanan</h2>
    </div>
    <div class="p-6">
        <table class="min-w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Menu</th>
                    <th class="text-right py-2">Harga</th>
                    <th class="text-center py-2">Jumlah</th>
                    <th class="text-right py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->order->orderItems as $item)
                <tr class="border-b">
                    <td class="py-3">{{ $item->menu->name }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="py-3 text-right font-bold">Total:</td>
                    <td class="text-right font-bold text-green-600 text-xl">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
