@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan</h1>
        <p class="text-gray-600">{{ $order->order_number }}</p>
    </div>
    <a href="{{ route('orders.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Status Pesanan</h3>
        <span class="px-3 py-1 text-sm rounded-full inline-block
            @if($order->status === 'Pending') bg-yellow-100 text-yellow-800
            @elseif($order->status === 'Paid') bg-blue-100 text-blue-800
            @else bg-green-100 text-green-800
            @endif">
            {{ $order->status }}
        </span>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Pelanggan</h3>
        <p class="text-xl font-semibold">{{ $order->user->name }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Total</h3>
        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold">Detail Item</h2>
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
                @foreach($order->orderItems as $item)
                <tr class="border-b">
                    <td class="py-3">{{ $item->menu->name }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="py-3 text-right font-bold">Total:</td>
                    <td class="text-right font-bold text-green-600 text-xl">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@if($order->transaction)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold">Informasi Transaksi</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nomor Transaksi</p>
                <p class="font-semibold">{{ $order->transaction->transaction_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status Pembayaran</p>
                <p class="font-semibold">{{ $order->transaction->payment_status }}</p>
            </div>
            @if($order->transaction->paid_at)
            <div>
                <p class="text-sm text-gray-600">Tanggal Bayar</p>
                <p class="font-semibold">{{ $order->transaction->paid_at->format('d/m/Y H:i') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Update Status -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Update Status</h2>
    <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="flex gap-4 items-end">
        @csrf
        @method('PATCH')
        
        <div class="flex-1">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
            <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Paid" {{ $order->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            Update Status
        </button>
    </form>
</div>
@endsection
