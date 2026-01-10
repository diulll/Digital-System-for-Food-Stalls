<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Pelanggan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <h3 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                        <p class="mt-2 text-green-100">Pesan makanan favorit Anda hari ini.</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- My Orders -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pesanan Saya</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['my_total_orders'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pesanan Pending</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['my_pending_orders'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Orders -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pesanan Selesai</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['my_completed_orders'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pengeluaran</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['my_total_spent'], 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action -->
            <div class="mb-6">
                <a href="{{ route('orders.create') }}" class="block w-full">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 overflow-hidden shadow-lg sm:rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all">
                        <div class="p-6 text-white text-center">
                            <svg class="h-12 w-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <h3 class="text-2xl font-bold">Buat Pesanan Baru</h3>
                            <p class="mt-1 text-orange-100">Klik untuk memesan makanan</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- My Recent Orders -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan Terakhir Saya</h3>
                    </div>
                    <div class="p-6">
                        @if($myOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach($myOrders as $order)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            @if($order->status === 'Completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'Paid') bg-blue-100 text-blue-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        @foreach($order->orderItems->take(2) as $item)
                                            <p>• {{ $item->menu->name }} ({{ $item->quantity }}x)</p>
                                        @endforeach
                                        @if($order->orderItems->count() > 2)
                                            <p class="text-gray-400">...dan {{ $order->orderItems->count() - 2 }} item lainnya</p>
                                        @endif
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                        <p class="font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Lihat Detail →
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <p class="mt-2 text-gray-500">Belum ada pesanan.</p>
                                <a href="{{ route('orders.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium">
                                    Buat Pesanan Pertama →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Available Categories -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Menu Tersedia</h3>
                    </div>
                    <div class="p-6">
                        @if($categories->count() > 0)
                            <div class="space-y-4">
                                @foreach($categories as $category)
                                    @if($category->menus->count() > 0)
                                    <div class="border-b border-gray-200 pb-4 last:border-0">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $category->name }}</h4>
                                        <div class="space-y-2">
                                            @foreach($category->menus as $menu)
                                            <div class="flex justify-between items-center">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-700">{{ $menu->name }}</p>
                                                    <p class="text-xs text-gray-500">Stok: {{ $menu->stock }}</p>
                                                </div>
                                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('menus.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Lihat Semua Menu →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">Belum ada menu tersedia.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Navigasi Cepat</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('menus.index') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="h-8 w-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Lihat Menu</span>
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="h-8 w-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Riwayat Pesanan</span>
                        </a>
                        <a href="{{ route('transactions.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <svg class="h-8 w-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Transaksi</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                            <svg class="h-8 w-8 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
