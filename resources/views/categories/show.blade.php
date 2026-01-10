@extends('layouts.master')

@section('title', 'Detail Kategori')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Detail Kategori</h1>
        <p class="text-gray-600">{{ $category->name }}</p>
    </div>
    <div class="space-x-2">
        <a href="{{ route('categories.edit', $category) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600">
            Edit
        </a>
        <a href="{{ route('categories.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">Informasi Kategori</h2>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600">Nama Kategori</p>
            <p class="text-lg font-semibold">{{ $category->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Jumlah Menu</p>
            <p class="text-lg font-semibold">{{ $category->menus->count() }} menu</p>
        </div>
        <div class="col-span-2">
            <p class="text-sm text-gray-600">Deskripsi</p>
            <p class="text-lg">{{ $category->description ?? '-' }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold">Menu dalam Kategori Ini</h2>
    </div>
    <div class="p-6">
        @if($category->menus->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($category->menus as $menu)
            <div class="border rounded-lg p-4">
                <h3 class="font-bold text-lg">{{ $menu->name }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ $menu->description }}</p>
                <div class="flex justify-between items-center">
                    <p class="text-green-600 font-semibold">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    <p class="text-gray-600">Stok: {{ $menu->stock }}</p>
                </div>
                <a href="{{ route('menus.show', $menu) }}" class="text-blue-600 text-sm hover:text-blue-800">Lihat Detail →</a>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-4">Belum ada menu dalam kategori ini</p>
        @endif
    </div>
</div>
@endsection
