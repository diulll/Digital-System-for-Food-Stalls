@extends('layouts.master')

@section('title', 'Nota Pembayaran')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Receipt Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
            <h1 class="text-3xl font-bold text-center">🧾 NOTA PEMBAYARAN</h1>
            <p class="text-center text-blue-100 mt-2">Warung Makan Digital</p>
        </div>

        <!-- Receipt Body -->
        <div class="p-8">
            <!-- Order Info -->
            <div class="border-b pb-4 mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">No. Pesanan</p>
                        <p class="font-bold text-lg">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Tanggal</p>
                        <p class="font-bold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Pelanggan</p>
                        <p class="font-semibold">{{ $order->customer_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Nomor Meja</p>
                        <p class="font-semibold">{{ $order->table_number }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-sm text-gray-600">Kasir</p>
                    <p class="font-semibold">{{ $order->user->name }}</p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-6">
                <h3 class="font-bold text-lg mb-3">Detail Pesanan</h3>
                <table class="w-full">
                    <thead class="border-b">
                        <tr>
                            <th class="text-left py-2">Menu</th>
                            <th class="text-center py-2">Qty</th>
                            <th class="text-right py-2">Harga</th>
                            <th class="text-right py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr class="border-b">
                            <td class="py-3">
                                <p class="font-medium">{{ $item->menu->name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->menu->category->name }}</p>
                            </td>
                            <td class="text-center py-3">{{ $item->quantity }}</td>
                            <td class="text-right py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right py-3 font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="border-t-2 border-gray-800 pt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-2xl font-bold mt-3">
                    <span>TOTAL</span>
                    <span class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Status -->
            <div class="mt-6 text-center">
                @if($order->status == 'completed')
                <div class="inline-flex items-center bg-green-100 text-green-800 px-6 py-3 rounded-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-bold">LUNAS</span>
                </div>
                @else
                <div class="inline-flex items-center bg-yellow-100 text-yellow-800 px-6 py-3 rounded-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">PENDING</span>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center border-t pt-6">
                <p class="text-gray-600 text-sm">Terima kasih atas kunjungan Anda!</p>
                <p class="text-gray-500 text-xs mt-1">Silakan datang kembali 😊</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gray-50 px-8 py-4 flex gap-3">
            <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-center transition">
                ← Kembali
            </a>
            <button onclick="window.print()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                🖨️ Cetak Nota
            </button>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-3xl, .max-w-3xl * {
        visibility: visible;
    }
    .max-w-3xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .bg-gray-50 {
        display: none !important;
    }
}
</style>
@endsection
