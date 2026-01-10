@extends('layouts.master')

@section('title', 'Pesanan Aktif')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pesanan Aktif</h1>
            <p class="text-gray-600">Kelola pesanan yang sedang diproses</p>
        </div>
        <a href="{{ route('cashier.pos') }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Pesanan Baru
        </a>
    </div>
</div>

<!-- Orders Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($orders as $order)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 
            @if($order->status === 'Pending') bg-yellow-50
            @elseif($order->status === 'Paid') bg-blue-50
            @else bg-green-50
            @endif">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">#{{ $order->order_number }}</h3>
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                    @if($order->status === 'Pending') bg-yellow-200 text-yellow-800
                    @elseif($order->status === 'Paid') bg-blue-200 text-blue-800
                    @else bg-green-200 text-green-800
                    @endif">
                    {{ $order->status }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="p-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Item Pesanan:</h4>
            <div class="space-y-2 mb-4 max-h-40 overflow-y-auto">
                @foreach($order->orderItems as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $item->menu->name ?? 'Menu' }} x {{ $item->quantity }}</span>
                    <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-semibold text-gray-700">Total</span>
                    <span class="text-xl font-bold text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>

                @if($order->status === 'Paid')
                <form action="{{ route('cashier.orders.complete', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        Selesaikan Pesanan
                    </button>
                </form>
                @elseif($order->status === 'Pending')
                <div class="flex gap-2">
                    <a href="{{ route('cashier.pos') }}" class="flex-1 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 text-center transition-colors">
                        Proses
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Pesanan Aktif</h3>
            <p class="text-gray-500 mb-4">Semua pesanan telah selesai diproses.</p>
            <a href="{{ route('cashier.pos') }}" class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Pesanan Baru
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($orders->hasPages())
<div class="mt-6">
    {{ $orders->links() }}
</div>
@endif
@endsection
