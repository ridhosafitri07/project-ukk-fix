@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Admin')
@section('subheader', 'Selamat datang kembali, ' . auth()->user()->nama_pengguna)

@section('content')
<style>
    .table-header-gradient {
        background: linear-gradient(to right, #1f2937 0%, #374151 50%, #4b5563 100%);
    }
    
    .stat-card-modern {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
    }
    
    .stat-card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.15);
    }
    
    .activity-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .activity-item:hover {
        background: linear-gradient(to right, rgba(59, 130, 246, 0.05), transparent);
        border-left-color: #3b82f6;
        transform: translateX(4px);
    }
</style>

<div class="space-y-6">
    <!-- Modern Hero Section -->
    <div class="relative overflow-hidden rounded-2xl animate-fade-in-up">
        <!-- Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-800 via-gray-700 to-blue-900"></div>
        
        <!-- Decorative Blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gray-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -ml-32 -mb-32"></div>
        
        <!-- Content -->
        <div class="relative p-8 lg:p-12 text-white">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="flex-1">
                    <div class="inline-flex items-center px-4 py-2 bg-white/15 backdrop-blur-md rounded-full border border-white/20 mb-4 text-sm font-medium">
                        <span class="flex w-2 h-2 bg-blue-300 rounded-full mr-2">
                            <span class="animate-ping absolute w-2 h-2 bg-blue-300 rounded-full"></span>
                        </span>
                        Sistem Aktif
                    </div>
                    
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                        Halo, <span class="text-gray-100">{{ auth()->user()->nama_pengguna }}</span>
                    </h1>
                    <p class="text-gray-200 text-lg mb-6 max-w-xl leading-relaxed">
                        Kelola pengaduan, sarana prasarana, dan aktivitas sistem dengan mudah.
                    </p>
                    
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.pengaduan.index') }}" 
                           class="group relative inline-flex items-center gap-2 bg-white text-gray-800 font-semibold py-3 px-6 rounded-xl hover:bg-gray-100 transition-all hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-clipboard-list"></i>
                            Kelola Pengaduan
                        </a>
                        <a href="{{ route('admin.master-lokasi.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 border-2 border-white text-white rounded-xl hover:bg-white/10 transition-all backdrop-blur-sm font-semibold">
                            <i class="fas fa-boxes"></i>
                            Manajemen Sarpras
                        </a>
                    </div>
                </div>
                
                <!-- Floating Stats Card -->
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/20 backdrop-blur-xl rounded-2xl blur-xl"></div>
                        <div class="relative bg-white/15 backdrop-blur-2xl border-2 border-white/30 rounded-2xl p-8 min-w-[220px]">
                            <div class="text-center">
                                <i class="fas fa-chart-line text-5xl mb-3 text-blue-100"></i>
                                <div class="text-4xl font-black text-white mb-2">{{ $totalPengaduan ?? 0 }}</div>
                                <div class="text-gray-200 font-semibold text-sm">Total Pengaduan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Stats Grid - Simplified to 3 Columns -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up animate-delay-1">
        <!-- Total Pengaduan -->
        <div class="stat-card-modern group cursor-pointer">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Total Pengaduan</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ $totalPengaduan ?? 0 }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Semua laporan</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-lg">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="stat-card-modern group cursor-pointer">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Total Pengguna</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ $totalUsers ?? 0 }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Terdaftar</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white text-lg">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Total Items -->
        <div class="stat-card-modern group cursor-pointer">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">Total Barang</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ $totalItems ?? 0 }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Dalam sistem</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white text-lg">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Simplified Manajemen Sarpras Section -->
    <div class="bg-white rounded-2xl overflow-hidden shadow-lg border border-slate-200 animate-fade-in-up animate-delay-2">
        <div class="table-header-gradient p-6 text-white">
            <h2 class="text-2xl font-bold flex items-center gap-3">
                <i class="fas fa-map-marked-alt text-lg"></i>
                Statistik Sarpras
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Lokasi -->
                <div class="stat-card-modern">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Total Lokasi</p>
                            <h3 class="text-3xl font-black text-gray-900">{{ $totalLokasi ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Ruangan terdaftar</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-lg">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Item per Lokasi -->
                <div class="stat-card-modern">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Total Barang</p>
                            <h3 class="text-3xl font-black text-gray-900">{{ $totalBarang ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Item terdaftar</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center text-white text-lg">
                            <i class="fas fa-cube"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Relasi -->
                <div class="stat-card-modern">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Barang Terdistribusi</p>
                            <h3 class="text-3xl font-black text-gray-900">{{ $totalRelasi ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Lokasi dengan barang</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white text-lg">
                            <i class="fas fa-link"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section - Only Pengaduan Chart -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-slate-200 animate-fade-in-up animate-delay-3">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                Statistik Pengaduan Per Bulan
            </h3>
            <span class="text-xs font-bold text-blue-600 bg-blue-100 px-3 py-1 rounded-full">2025</span>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="pengaduanChart"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-2xl overflow-hidden shadow-lg border border-slate-200 animate-fade-in-up animate-delay-4">
        <!-- Header -->
        <div class="table-header-gradient p-6 text-white">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <i class="fas fa-history"></i>
                    Aktivitas Terbaru
                </h2>
            </div>
        </div>

        <!-- List -->
        <div class="divide-y divide-gray-100">
            @forelse($recentActivities ?? [] as $activity)
                <div class="activity-item p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-gray-700 flex items-center justify-center text-white text-lg flex-shrink-0">
                            <i class="fas fa-{{ $activity->type == 'pengaduan' ? 'clipboard-list' : ($activity->type == 'approval' ? 'check-circle' : 'info-circle') }}"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 mb-1">
                                {{ $activity->description ?? 'Aktivitas' }}
                            </h3>
                            
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-user"></i>
                                    {{ $activity->user->nama_pengguna ?? 'System' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ isset($activity->created_at) ? \Carbon\Carbon::parse($activity->created_at)->format('d M Y H:i') : now()->format('d M Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Belum Ada Aktivitas</h3>
                    <p class="text-gray-500 text-sm">Aktivitas akan muncul di sini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pengaduan Chart
    const pengaduanCtx = document.getElementById('pengaduanChart');
    if (pengaduanCtx) {
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const pengaduanData = @json($pengaduanStats ?? []);
        
        new Chart(pengaduanCtx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Pengaduan per Bulan',
                    data: monthNames.map((_, index) => pengaduanData[index + 1] || 0),
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(168, 85, 247)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                family: 'Inter, sans-serif'
                            },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush