@extends('layouts.master')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
        <p class="mt-2 text-blue-100">Anda login sebagai Kasir - Sistem Kasir Warung Makan</p>
    </div>

    <!-- Quick Action: Buat Pesanan Baru -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">🛒 Buat Pesanan Baru</h2>
            
            <form action="{{ route('cashier.checkout') }}" method="POST" id="checkoutForm">
                @csrf
                
                <!-- Customer Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan</label>
                        <input type="text" name="customer_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama pelanggan" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Meja</label>
                        <input type="text" name="table_number" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Meja 5" required>
                    </div>
                </div>

                <!-- Menu Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Menu</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="menuContainer">
                        @foreach($menus as $menu)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">{{ $menu->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $menu->category->name }}</p>
                                    <p class="text-lg font-bold text-blue-600 mt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                </div>
                                @if($menu->stock > 0)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Tersedia</span>
                                @else
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Habis</span>
                                @endif
                            </div>
                            
                            @if($menu->stock > 0)
                            <div class="flex items-center gap-2 mt-3">
                                <input type="number" 
                                       name="quantities[{{ $menu->id }}]" 
                                       min="0" 
                                       max="{{ $menu->stock }}"
                                       value="0"
                                       class="w-20 border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 text-center"
                                       data-price="{{ $menu->price }}"
                                       data-name="{{ $menu->name }}"
                                       onchange="updateTotal()">
                                <span class="text-sm text-gray-600">porsi</span>
                            </div>
                            @else
                            <div class="mt-3 text-center text-sm text-red-600 font-medium">Stok Habis</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total and Submit -->
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xl font-semibold text-gray-700">Total Pembayaran:</span>
                        <span id="totalAmount" class="text-3xl font-bold text-blue-600">Rp 0</span>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                        🛒 Proses Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Today's Orders -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">📋 Pesanan Hari Ini</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Meja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($todayOrders as $index => $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->table_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->status == 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($order->status == 'completed')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('cashier.receipt', $order->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Lihat Nota</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan hari ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateTotal() {
    let total = 0;
    const inputs = document.querySelectorAll('input[name^="quantities"]');
    
    inputs.forEach(input => {
        const quantity = parseInt(input.value) || 0;
        const price = parseFloat(input.dataset.price) || 0;
        total += quantity * price;
    });
    
    document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Validate form before submit
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const inputs = document.querySelectorAll('input[name^="quantities"]');
    let hasItems = false;
    
    inputs.forEach(input => {
        if (parseInt(input.value) > 0) {
            hasItems = true;
        }
    });
    
    if (!hasItems) {
        e.preventDefault();
        alert('Pilih minimal 1 menu untuk pesanan!');
    }
});
</script>
@endsection
