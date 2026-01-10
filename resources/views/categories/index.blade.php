@extends('layouts.master')

@section('title', 'Kategori')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Kategori</h1>
        <p class="text-gray-600">Kelola kategori menu</p>
    </div>
    <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        + Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        @if($categories->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $category->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->menus_count }} menu</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('categories.show', $category) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                            <a href="{{ route('categories.edit', $category) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
            {{ $categories->links() }}
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Belum ada kategori. Silakan tambah kategori baru.</p>
        @endif
    </div>
</div>
@endsection
