@extends('layouts.app')

@section('title', 'Buat Pesanan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Buat Pesanan Baru</h1>
    <p class="text-gray-600">Pilih menu yang dipesan</p>
</div>

<form action="{{ route('orders.store') }}" method="POST" id="orderForm">
    @csrf
    
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Pilih Menu</h2>
        
        <div id="menuItems">
            <div class="menu-item mb-4 p-4 border rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Menu</label>
                        <select name="menu_id[]" required class="menu-select w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Pilih Menu</option>
                            @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" data-price="{{ $menu->price }}" data-stock="{{ $menu->stock }}">
                                {{ $menu->name }} - {{ $menu->category->name }} (Stok: {{ $menu->stock }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" name="quantity[]" value="1" min="1" required 
                            class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
            </div>
        </div>
        
        <button type="button" id="addMenuItem" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
            + Tambah Item
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>
        <div class="text-right">
            <p class="text-3xl font-bold text-green-600">
                Total: Rp <span id="totalPrice">0</span>
            </p>
        </div>
    </div>

    <div class="flex space-x-3">
        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700">
            Buat Pesanan
        </button>
        <a href="{{ route('orders.index') }}" class="bg-gray-300 text-gray-700 px-8 py-3 rounded-md hover:bg-gray-400">
            Batal
        </a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuItemsContainer = document.getElementById('menuItems');
    const addMenuItemBtn = document.getElementById('addMenuItem');
    let itemCount = 1;

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.menu-item').forEach(function(item) {
            const select = item.querySelector('.menu-select');
            const quantity = item.querySelector('.quantity-input').value;
            const price = select.options[select.selectedIndex]?.dataset.price || 0;
            total += parseFloat(price) * parseInt(quantity || 0);
        });
        document.getElementById('totalPrice').textContent = total.toLocaleString('id-ID');
    }

    menuItemsContainer.addEventListener('change', calculateTotal);
    menuItemsContainer.addEventListener('input', calculateTotal);

    addMenuItemBtn.addEventListener('click', function() {
        const newItem = document.querySelector('.menu-item').cloneNode(true);
        newItem.querySelector('.menu-select').value = '';
        newItem.querySelector('.quantity-input').value = 1;
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'mt-2 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700';
        removeBtn.textContent = 'Hapus Item';
        removeBtn.addEventListener('click', function() {
            newItem.remove();
            calculateTotal();
        });
        
        newItem.appendChild(removeBtn);
        menuItemsContainer.appendChild(newItem);
        itemCount++;
    });

    calculateTotal();
});
</script>
@endsection
