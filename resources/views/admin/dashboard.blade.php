@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header', 'Dashboard Overview')
@section('subheader', 'Welcome back, ' . auth()->user()->nama_pengguna)

@section('content')
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Pengaduan Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <i class="fas fa-clipboard-list text-2xl text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-400">Total Pengaduan</h3>
                            <p class="text-2xl font-semibold text-gray-800">{{ $totalPengaduan ?? 0 }}</p>
                            <p class="text-xs text-green-500 mt-1">
                                <i class="fas fa-arrow-up"></i>
                                <span>12% dari bulan lalu</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pending Items Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                            <i class="fas fa-clock text-2xl text-yellow-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-400">Menunggu Approval</h3>
                            <p class="text-2xl font-semibold text-gray-800">{{ $pendingItems ?? 0 }}</p>
                            <p class="text-xs text-yellow-500 mt-1">
                                <i class="fas fa-clock"></i>
                                <span>Butuh perhatian</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Users Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <i class="fas fa-users text-2xl text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-400">Total Users</h3>
                            <p class="text-2xl font-semibold text-gray-800">{{ $totalUsers ?? 0 }}</p>
                            <p class="text-xs text-green-500 mt-1">
                                <i class="fas fa-user-plus"></i>
                                <span>5 users baru</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Items Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <i class="fas fa-boxes text-2xl text-purple-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-400">Total Items</h3>
                            <p class="text-2xl font-semibold text-gray-800">{{ $totalItems ?? 0 }}</p>
                            <p class="text-xs text-purple-500 mt-1">
                                <i class="fas fa-box"></i>
                                <span>Inventory aktif</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Pengaduan Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Pengaduan</h3>
                    <canvas id="pengaduanChart" height="300"></canvas>
                </div>

                <!-- Items Status Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Item</h3>
                    <canvas id="itemStatusChart" height="300"></canvas>
                </div>
            </div>

            <!-- Recent Activities -->
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-900">Kelola Items</a>
                        </div>
                    </div>
                </div>

                <!-- Locations Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Locations</dt>
                                    <dd class="text-lg font-medium text-gray-900">0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-green-600 hover:text-green-900">Kelola Locations</a>
                        </div>
                    </div>
                </div>

                <!-- Reports Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    <a href="#" class="text-blue-500 hover:text-blue-600 text-sm">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <span class="inline-block p-3 rounded-full 
                                @if($activity->type == 'pengaduan') bg-blue-100 text-blue-500
                                @elseif($activity->type == 'approval') bg-green-100 text-green-500
                                @else bg-gray-100 text-gray-500 @endif">
                                <i class="fas fa-{{ $activity->type == 'pengaduan' ? 'clipboard-list' : ($activity->type == 'approval' ? 'check' : 'info-circle') }}"></i>
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $activity->description ?? 'Aktivitas' }}</p>
                            <p class="text-xs text-gray-500">{{ isset($activity->created_at) ? \Carbon\Carbon::parse($activity->created_at)->diffForHumans() : now()->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Belum ada aktivitas terbaru</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample data for charts
        const pengaduanCtx = document.getElementById('pengaduanChart').getContext('2d');
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const pengaduanData = @json($pengaduanStats);
        
        new Chart(pengaduanCtx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Pengaduan per Bulan',
                    data: monthNames.map((_, index) => pengaduanData[index + 1] || 0),
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        const itemStatusCtx = document.getElementById('itemStatusChart').getContext('2d');
        const itemStatusData = @json($itemStats);
        
        new Chart(itemStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'],
                datasets: [{
                    data: [
                        itemStatusData['Menunggu Persetujuan'] || 0,
                        itemStatusData['Disetujui'] || 0,
                        itemStatusData['Ditolak'] || 0
                    ],
                    backgroundColor: [
                        'rgb(234, 179, 8)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>
</html>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-yellow-600 hover:text-yellow-900">View Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>