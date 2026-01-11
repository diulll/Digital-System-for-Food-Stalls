@extends('layouts.master')

@section('title', 'Detail Menu')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Detail Menu</h1>
        <p class="text-gray-600">{{ $menu->name }}</p>
    </div>
    <div class="space-x-2">
        <a href="{{ route('menus.edit', $menu) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600">
            Edit
        </a>
        <a href="{{ route('menus.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Gambar Menu -->
        <div class="md:col-span-1">
            <h3 class="text-sm text-gray-600 mb-2">Gambar Menu</h3>
            @if($menu->image)
            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-64 object-cover rounded-lg shadow">
            @else
            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            @endif
        </div>
        
        <!-- Detail Menu -->
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm text-gray-600 mb-1">Nama Menu</h3>
                <p class="text-xl font-bold text-gray-900">{{ $menu->name }}</p>
            </div>
            
            <div>
                <h3 class="text-sm text-gray-600 mb-1">Kategori</h3>
                <p class="text-xl font-semibold text-gray-900">{{ $menu->category->name }}</p>
            </div>
            
            <div>
                <h3 class="text-sm text-gray-600 mb-1">Harga</h3>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
            </div>
            
            <div>
                <h3 class="text-sm text-gray-600 mb-1">Stok</h3>
                <p class="text-2xl font-bold {{ $menu->stock < 10 ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $menu->stock }}
                    @if($menu->stock < 10)
                    <span class="text-sm text-red-500">(Stok Menipis!)</span>
                    @endif
                </p>
            </div>
            
            <div class="col-span-2">
                <h3 class="text-sm text-gray-600 mb-1">Deskripsi</h3>
                <p class="text-gray-900">{{ $menu->description ?? '-' }}</p>
            </div>
            
            <div>
                <h3 class="text-sm text-gray-600 mb-1">Dibuat</h3>
                <p class="text-gray-900">{{ $menu->created_at->format('d/m/Y H:i') }}</p>
            </div>
            
            <div>
                <h3 class="text-sm text-gray-600 mb-1">Terakhir Update</h3>
                <p class="text-gray-900">{{ $menu->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
        </div>
    </div>
</div>
@endsection
