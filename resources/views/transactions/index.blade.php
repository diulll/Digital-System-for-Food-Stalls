@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
    <p class="text-gray-600">Semua transaksi pembayaran</p>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Failed" {{ request('payment_status') == 'Failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Filter</button>
            <a href="{{ route('transactions.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                            <a href="{{ route('transactions.show', $transaction) }}">{{ $transaction->transaction_number }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->order->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->order->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($transaction->payment_status === 'Pending') bg-yellow-100 text-yellow-800
                                @elseif($transaction->payment_status === 'Paid') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $transaction->payment_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Belum ada transaksi.</p>
        @endif
    </div>
</div>
@endsection
