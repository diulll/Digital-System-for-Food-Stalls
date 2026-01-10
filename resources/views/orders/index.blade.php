@extends('layouts.app')

@section('title', 'Pesanan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Pesanan</h1>
        <p class="text-gray-600">Kelola pesanan pelanggan</p>
    </div>
    <a href="{{ route('orders.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        + Buat Pesanan Baru
    </a>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form action="{{ route('orders.index') }}" method="GET" class="flex gap-4">
        <div class="flex-1">
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Filter</button>
        <a href="{{ route('orders.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">Reset</a>
    </form>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                            <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($order->status === 'Pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'Paid') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                            @if($order->status === 'Pending')
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Belum ada pesanan.</p>
        @endif
    </div>
</div>
@endsection
