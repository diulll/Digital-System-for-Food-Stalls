<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <h3 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                        <p class="mt-2 text-blue-100">Berikut adalah ringkasan sistem warung makan Anda hari ini.</p>
                    </div>
                </div>
            </div>

            <!-- Today's Summary Card -->
            <div class="mb-6">
                <div class="bg-green-500 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-4">📊 Statistik Hari Ini</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-4 shadow">
                                <p class="text-gray-500 text-sm">Pesanan Hari Ini</p>
                                <p class="text-3xl font-bold text-green-600">{{ $stats['orders_today'] ?? 0 }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow">
                                <p class="text-gray-500 text-sm">Transaksi Sukses</p>
                                <p class="text-3xl font-bold text-blue-600">{{ $stats['transactions_today'] ?? 0 }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow">
                                <p class="text-gray-500 text-sm">Pendapatan Hari Ini</p>
                                <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($stats['income_today'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Menus -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Menu</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_menus'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pesanan</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] }}</dd>
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
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_orders'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Income -->
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
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pendapatan</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role && auth()->user()->role->name === 'Admin')
                <!-- Total Categories -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Kategori</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_categories'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-pink-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-teal-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Transaksi</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_transactions'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                    </div>
                    <div class="p-6">
                        @if($recentOrders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentOrders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($order->status === 'Completed') bg-green-100 text-green-800
                                                    @elseif($order->status === 'Processing') bg-blue-100 text-blue-800
                                                    @elseif($order->status === 'Pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada pesanan.</p>
                        @endif
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Peringatan Stok Rendah</h3>
                    </div>
                    <div class="p-6">
                        @if($lowStockMenus->count() > 0)
                            <div class="space-y-4">
                                @foreach($lowStockMenus as $menu)
                                <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $menu->name }}</h4>
                                        <p class="text-xs text-gray-500">{{ $menu->category->name }}</p>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 text-sm font-bold text-red-700 bg-red-200 rounded-full">
                                            Stok: {{ $menu->stock }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Semua menu memiliki stok yang cukup.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Top Selling Menus Chart -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Menu Terlaris</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="topMenusChart"></canvas>
                    </div>
                </div>

                <!-- Sales by Category Chart -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Penjualan per Kategori</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="salesByCategoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pendapatan Bulanan (6 Bulan Terakhir)</h3>
                </div>
                <div class="p-6">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('orders.index') }}" class="flex items-center justify-center p-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Lihat Pesanan
                        </a>
                        <a href="{{ route('menus.index') }}" class="flex items-center justify-center p-4 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Kelola Menu
                        </a>
                        <a href="{{ route('transactions.index') }}" class="flex items-center justify-center p-4 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Transaksi
                        </a>
                        <a href="{{ route('categories.index') }}" class="flex items-center justify-center p-4 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Top Menus Chart
        const topMenusData = @json($topMenus);
        const topMenusCtx = document.getElementById('topMenusChart').getContext('2d');
        new Chart(topMenusCtx, {
            type: 'bar',
            data: {
                labels: topMenusData.map(item => item.name),
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: topMenusData.map(item => item.total_sold),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(139, 92, 246)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Sales by Category Chart
        const salesByCategoryData = @json($salesByCategory);
        const salesByCategoryCtx = document.getElementById('salesByCategoryChart').getContext('2d');
        new Chart(salesByCategoryCtx, {
            type: 'doughnut',
            data: {
                labels: salesByCategoryData.map(item => item.name),
                datasets: [{
                    label: 'Total Pesanan',
                    data: salesByCategoryData.map(item => item.total_orders),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Revenue Chart
        const monthlyRevenueData = @json($monthlyRevenue);
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'line',
            data: {
                labels: monthlyRevenueData.map(item => monthNames[item.month - 1] + ' ' + item.year),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: monthlyRevenueData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(context.parsed.y);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
