@extends('layouts.master')

@section('title', 'Tambah Kategori')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Tambah Kategori</h1>
    <p class="text-gray-600">Tambah kategori menu baru</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
            <textarea name="description" id="description" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Simpan
            </button>
            <a href="{{ route('categories.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
