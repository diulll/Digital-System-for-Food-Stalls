@extends('layouts.master')

@section('title', 'Point of Sale')

@section('content')
<div class="flex flex-col lg:flex-row gap-6" x-data="posApp()">
    <!-- Left Side - Menu List -->
    <div class="lg:w-2/3">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h2 class="text-xl font-bold text-gray-900">Pilih Menu</h2>
                
                <!-- Category Filter -->
                <div class="flex flex-wrap gap-2">
                    <button @click="filterCategory = ''" 
                            :class="filterCategory === '' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Semua
                    </button>
                    @foreach($categories as $category)
                    <button @click="filterCategory = '{{ $category->id }}'" 
                            :class="filterCategory === '{{ $category->id }}' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Search -->
            <div class="mb-6">
                <input type="text" 
                       x-model="searchQuery"
                       placeholder="Cari menu..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Menu Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($menus as $menu)
                <div class="menu-item border rounded-lg p-4 cursor-pointer hover:shadow-lg transition-all"
                     :class="{ 'hidden': !isMenuVisible({{ $menu->id }}, '{{ $menu->category_id }}', '{{ strtolower($menu->name) }}') }"
                     @click="addToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }}, {{ $menu->stock }})">
                    @if($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" 
                         class="w-full h-24 object-cover rounded-lg mb-2">
                    @else
                    <div class="w-full h-24 bg-gray-200 rounded-lg mb-2 flex items-center justify-center">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                    <h3 class="font-semibold text-sm text-gray-900 truncate">{{ $menu->name }}</h3>
                    <p class="text-green-600 font-bold text-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Stok: {{ $menu->stock }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Side - Cart -->
    <div class="lg:w-1/3">
        <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Keranjang Pesanan</h2>
            
            <!-- Cart Items -->
            <div class="space-y-4 max-h-80 overflow-y-auto mb-4">
                <template x-if="cart.length === 0">
                    <p class="text-gray-500 text-center py-8">Keranjang kosong</p>
                </template>
                
                <template x-for="item in cart" :key="item.menu_id">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm" x-text="item.name"></h4>
                            <p class="text-green-600 text-sm" x-text="'Rp ' + formatNumber(item.price)"></p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="decreaseQty(item.menu_id)" 
                                    class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center hover:bg-red-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            <span class="w-8 text-center font-semibold" x-text="item.quantity"></span>
                            <button @click="increaseQty(item.menu_id)" 
                                    class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center hover:bg-green-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                            <button @click="removeFromCart(item.menu_id)" 
                                    class="w-8 h-8 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center hover:bg-gray-200 ml-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>

            <!-- Total -->
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold text-gray-700">Total:</span>
                <span class="text-2xl font-bold text-green-600" x-text="'Rp ' + formatNumber(totalPrice)"></span>
            </div>

            <!-- Payment Method -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select x-model="paymentMethod" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <!-- Amount Paid -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Dibayar</label>
                <input type="number" x-model="amountPaid" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                       placeholder="Masukkan jumlah">
            </div>

            <!-- Change -->
            <div class="flex justify-between items-center mb-4 p-3 bg-yellow-50 rounded-lg" x-show="change >= 0 && amountPaid > 0">
                <span class="text-sm font-semibold text-gray-700">Kembalian:</span>
                <span class="text-lg font-bold text-yellow-600" x-text="'Rp ' + formatNumber(change)"></span>
            </div>

            <!-- Quick Amount Buttons -->
            <div class="grid grid-cols-3 gap-2 mb-4">
                <button @click="amountPaid = totalPrice" 
                        class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                    Uang Pas
                </button>
                <button @click="amountPaid = Math.ceil(totalPrice / 10000) * 10000" 
                        class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                    Bulat 10rb
                </button>
                <button @click="amountPaid = Math.ceil(totalPrice / 50000) * 50000" 
                        class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                    Bulat 50rb
                </button>
            </div>

            <!-- Process Button -->
            <button @click="processOrder()" 
                    :disabled="cart.length === 0 || amountPaid < totalPrice || isProcessing"
                    :class="cart.length === 0 || amountPaid < totalPrice ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                    class="w-full py-3 text-white font-bold rounded-lg transition-colors">
                <span x-show="!isProcessing">Proses Pembayaran</span>
                <span x-show="isProcessing">
                    <svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>

            <!-- Clear Cart Button -->
            <button @click="clearCart()" 
                    x-show="cart.length > 0"
                    class="w-full mt-2 py-2 bg-red-100 text-red-600 font-semibold rounded-lg hover:bg-red-200 transition-colors">
                Kosongkan Keranjang
            </button>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccessModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
         x-transition>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="closeModal()">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-gray-600 mb-4">Pesanan telah diproses.</p>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-4 text-left">
                    <p class="text-sm text-gray-600">No. Transaksi: <span class="font-semibold" x-text="lastTransaction?.transaction_number"></span></p>
                    <p class="text-sm text-gray-600">Total: <span class="font-semibold text-green-600" x-text="'Rp ' + formatNumber(lastTransaction?.amount || 0)"></span></p>
                    <p class="text-sm text-gray-600">Kembalian: <span class="font-semibold text-yellow-600" x-text="'Rp ' + formatNumber(lastChange || 0)"></span></p>
                </div>

                <div class="flex gap-2">
                    <button @click="printReceipt()" 
                            class="flex-1 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                        Cetak Struk
                    </button>
                    <button @click="closeModal()" 
                            class="flex-1 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function posApp() {
    return {
        cart: [],
        filterCategory: '',
        searchQuery: '',
        paymentMethod: 'cash',
        amountPaid: 0,
        isProcessing: false,
        showSuccessModal: false,
        lastTransaction: null,
        lastChange: 0,

        get totalPrice() {
            return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        },

        get change() {
            return this.amountPaid - this.totalPrice;
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },

        isMenuVisible(menuId, categoryId, menuName) {
            const categoryMatch = this.filterCategory === '' || categoryId == this.filterCategory;
            const searchMatch = this.searchQuery === '' || menuName.includes(this.searchQuery.toLowerCase());
            return categoryMatch && searchMatch;
        },

        addToCart(menuId, name, price, stock) {
            const existingItem = this.cart.find(item => item.menu_id === menuId);
            
            if (existingItem) {
                if (existingItem.quantity < stock) {
                    existingItem.quantity++;
                } else {
                    alert('Stok tidak mencukupi!');
                }
            } else {
                this.cart.push({
                    menu_id: menuId,
                    name: name,
                    price: price,
                    quantity: 1,
                    stock: stock
                });
            }
        },

        increaseQty(menuId) {
            const item = this.cart.find(i => i.menu_id === menuId);
            if (item && item.quantity < item.stock) {
                item.quantity++;
            } else {
                alert('Stok tidak mencukupi!');
            }
        },

        decreaseQty(menuId) {
            const item = this.cart.find(i => i.menu_id === menuId);
            if (item) {
                if (item.quantity > 1) {
                    item.quantity--;
                } else {
                    this.removeFromCart(menuId);
                }
            }
        },

        removeFromCart(menuId) {
            this.cart = this.cart.filter(item => item.menu_id !== menuId);
        },

        clearCart() {
            if (confirm('Yakin ingin mengosongkan keranjang?')) {
                this.cart = [];
                this.amountPaid = 0;
            }
        },

        async processOrder() {
            if (this.cart.length === 0) {
                alert('Keranjang kosong!');
                return;
            }

            if (this.amountPaid < this.totalPrice) {
                alert('Jumlah pembayaran kurang!');
                return;
            }

            this.isProcessing = true;

            try {
                const response = await fetch('{{ route("cashier.pos.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        items: this.cart.map(item => ({
                            menu_id: item.menu_id,
                            quantity: item.quantity
                        })),
                        payment_method: this.paymentMethod,
                        amount_paid: this.amountPaid
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.lastTransaction = data.transaction;
                    this.lastChange = data.change;
                    this.showSuccessModal = true;
                    this.cart = [];
                    this.amountPaid = 0;
                } else {
                    alert(data.message || 'Terjadi kesalahan!');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses pesanan!');
            } finally {
                this.isProcessing = false;
            }
        },

        closeModal() {
            this.showSuccessModal = false;
            this.lastTransaction = null;
            this.lastChange = 0;
        },

        printReceipt() {
            // You can implement receipt printing here
            window.print();
        }
    }
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .fixed, .fixed * {
        visibility: visible;
    }
}
</style>
@endsection
