@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Laporan Penjualan</h1>
    <p class="text-gray-600">Analisis dan statistik penjualan</p>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('reports.index') }}" method="GET" class="flex gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-md hover:bg-blue-700">Filter</button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Total Pendapatan</h3>
        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Total Transaksi</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $totalTransactions }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm text-gray-600 mb-1">Total Pesanan</h3>
        <p class="text-3xl font-bold text-purple-600">{{ $totalOrders }}</p>
    </div>
</div>

<!-- Daily Sales -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold">Penjualan Harian</h2>
    </div>
    <div class="p-6">
        @if($dailySales->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah Transaksi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($dailySales as $sale)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $sale->total_transactions }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-green-600">
                            Rp {{ number_format($sale->total_income, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Tidak ada data penjualan pada periode ini.</p>
        @endif
    </div>
</div>

<!-- Top Selling Menus -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold">Menu Terlaris</h2>
    </div>
    <div class="p-6">
        @if($topMenus->count() > 0)
        <div class="space-y-4">
            @foreach($topMenus as $index => $menu)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="text-2xl font-bold text-gray-400">#{{ $index + 1 }}</div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $menu->name }}</p>
                        <p class="text-sm text-gray-600">Terjual: {{ $menu->total_quantity }} porsi</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-600">Total Pendapatan</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Tidak ada data menu terlaris.</p>
        @endif
    </div>
</div>
@endsection
