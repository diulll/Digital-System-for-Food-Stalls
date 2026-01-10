@extends('layouts.master')

@section('title', 'Menu')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Menu</h1>
        <p class="text-gray-600">Kelola menu makanan dan minuman</p>
    </div>
    <a href="{{ route('menus.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        + Tambah Menu
    </a>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form action="{{ route('menus.index') }}" method="GET" class="flex gap-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari menu..." value="{{ request('search') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div class="w-64">
            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Filter</button>
        <a href="{{ route('menus.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">Reset</a>
    </form>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        @if($menus->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($menus as $menu)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $menu->name }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($menu->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $menu->category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm {{ $menu->stock < 10 ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                {{ $menu->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('menus.show', $menu) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                            <a href="{{ route('menus.edit', $menu) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                            <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $menus->links() }}
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Tidak ada menu. Silakan tambah menu baru.</p>
        @endif
    </div>
</div>
@endsection
