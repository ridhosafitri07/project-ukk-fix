@extends('layouts.pengguna')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Kelola pengaduan sarana dan prasarana sekolah Anda')

@section('content')
<style>
    .gradient-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .gradient-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .stat-card-modern {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
    }
    
    .stat-card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
    }
    
    .step-card {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
    }
    
    .step-card:hover {
        border-color: #8b5cf6;
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(139, 92, 246, 0.3);
    }
    
    .step-number {
        width: 60px;
        height: 60px;
        border-radius: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        color: white;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .step-card:hover .step-number {
        transform: rotate(360deg) scale(1.1);
    }
    
    .pengaduan-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .pengaduan-item:hover {
        background: linear-gradient(to right, rgba(139, 92, 246, 0.05), transparent);
        border-left-color: #8b5cf6;
        transform: translateX(8px);
    }
    
    .progress-modern {
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-modern-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 1s ease;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }
</style>

<div class="space-y-8">
    <!-- Hero Section with Modern Gradient -->
    <div class="gradient-card rounded-3xl p-8 lg:p-12 text-white animate-fade-in-up shadow-2xl">
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                <div class="flex-1">
                    <!-- Status Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-xl rounded-full mb-6 border border-white/30">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-400"></span>
                        </span>
                        <span class="text-sm font-semibold">Sistem Online</span>
                    </div>
                    
                    <h1 class="text-4xl lg:text-5xl font-extrabold mb-4 leading-tight">
                        Halo, 👋<br>
                        <span class="bg-gradient-to-r from-yellow-200 to-pink-200 bg-clip-text text-transparent">
                            {{ auth()->user()->nama_pengguna }}
                        </span>
                    </h1>
                    
                    <p class="text-purple-100 text-lg mb-8 max-w-2xl leading-relaxed">
                        Selamat datang kembali! Pantau status pengaduan Anda dan laporkan masalah sarana prasarana dengan mudah.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('pengaduan.create') }}" 
                           class="group relative inline-flex items-center gap-3 bg-white text-purple-700 font-bold py-4 px-8 rounded-2xl hover:shadow-2xl transition-all hover:-translate-y-1">
                            <i class="fas fa-plus-circle text-xl"></i>
                            <span>Buat Pengaduan Baru</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </a>
                        <a href="{{ route('pengaduan.index') }}" 
                           class="inline-flex items-center gap-3 px-8 py-4 border-2 border-white/50 text-white rounded-2xl hover:bg-white/20 transition-all backdrop-blur-sm font-bold">
                            <i class="fas fa-list-ul"></i>
                            <span>Lihat Semua</span>
                        </a>
                    </div>
                </div>
                
                <!-- Floating Stats Card -->
                <div class="lg:block">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/20 backdrop-blur-xl rounded-3xl blur-xl"></div>
                        <div class="relative bg-white/15 backdrop-blur-2xl border-2 border-white/30 rounded-3xl p-8 min-w-[240px]">
                            <div class="text-center">
                                <i class="fas fa-chart-line text-6xl mb-4 text-yellow-200"></i>
                                <div class="text-5xl font-black text-white mb-2">{{ $totalPengaduan ?? 0 }}</div>
                                <div class="text-purple-100 font-semibold">Total Pengaduan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up animate-delay-1">
        <!-- Total Pengaduan -->
        <div class="stat-card-modern group cursor-pointer">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-500 mb-2 uppercase tracking-wide">Total Pengaduan</p>
                    <h3 class="text-4xl font-black text-gray-900 mb-1">{{ $totalPengaduan ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Semua laporan Anda</p>
                </div>
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-2xl shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>
            <div class="h-2 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full"></div>
        </div>

        <!-- Selesai -->
        <div class="stat-card-modern group cursor-pointer">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-500 mb-2 uppercase tracking-wide">Selesai</p>
                    <h3 class="text-4xl font-black text-gray-900 mb-1">{{ $selesaiCount ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Telah ditangani</p>
                </div>
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center text-white text-2xl shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
            <div class="h-2 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full"></div>
        </div>

        <!-- Diproses -->
        <div class="stat-card-modern group cursor-pointer">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-500 mb-2 uppercase tracking-wide">Sedang Diproses</p>
                    <h3 class="text-4xl font-black text-gray-900 mb-1">{{ $prosesCount ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Dalam penanganan</p>
                </div>
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white text-2xl shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-cog fa-spin"></i>
                </div>
            </div>
            <div class="h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
        </div>
    </div>

    <!-- Panduan Section -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100 animate-fade-in-up animate-delay-2">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 p-8 text-white">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-xl">
                    <i class="fas fa-rocket text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black">Cara Mengajukan Pengaduan</h2>
                    <p class="text-purple-100">Mudah dan cepat, hanya 4 langkah!</p>
                </div>
            </div>
        </div>

        <!-- Steps -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="step-card">
                    <div class="step-number">
                        <i class="fas fa-mouse-pointer"></i>
                    </div>
                    <div class="w-12 h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mb-4"></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Klik Tombol</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Tekan tombol "Buat Pengaduan" di dashboard atau sidebar untuk memulai</p>
                </div>

                <!-- Step 2 -->
                <div class="step-card">
                    <div class="step-number">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="w-12 h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mb-4"></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Isi Detail</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Masukkan nama laporan, deskripsi lengkap, lokasi, dan upload foto masalah</p>
                </div>

                <!-- Step 3 -->
                <div class="step-card">
                    <div class="step-number">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="w-12 h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mb-4"></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Pilih Item</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Pilih barang yang bermasalah atau skip jika item tidak ada dalam daftar</p>
                </div>

                <!-- Step 4 -->
                <div class="step-card">
                    <div class="step-number">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="w-12 h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mb-4"></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Kirim!</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Tekan kirim dan tunggu verifikasi dari petugas dalam 1-2 hari kerja</p>
                </div>
            </div>

            <!-- Tips Box -->
            <div class="mt-8 bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50 rounded-2xl p-6 border-2 border-purple-200">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white">
                            <i class="fas fa-lightbulb text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2 text-lg">💡 Tips Pro</h4>
                        <p class="text-gray-700 leading-relaxed">Sertakan foto yang jelas dan deskripsi detail. Semakin lengkap informasi, semakin cepat penanganannya!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Pengaduan -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100 animate-fade-in-up animate-delay-3">
        <!-- Header -->
        <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 mb-1">📋 Pengaduan Terbaru</h2>
                    <p class="text-gray-600">Pantau status laporan terkini Anda</p>
                </div>
                <a href="{{ route('pengaduan.index') }}" 
                   class="hidden lg:inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all hover:-translate-y-1">
                    <span>Lihat Semua</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- List -->
        <div class="divide-y divide-gray-100">
            @forelse($recentPengaduans ?? [] as $pengaduan)
                <a href="{{ route('pengaduan.show', $pengaduan) }}" class="pengaduan-item block p-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl shadow-lg flex-shrink-0">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 mb-2 text-lg truncate">
                                {{ $pengaduan->nama_pengaduan }}
                            </h3>
                            
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-map-pin text-purple-500"></i>
                                    {{ $pengaduan->lokasi }}
                                </span>
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-purple-500"></i>
                                    {{ \Carbon\Carbon::parse($pengaduan->tgl_pengajuan)->format('d M Y') }}
                                </span>
                            </div>
                            
                            @php
                                $progress = 0;
                                $statusText = '';
                                switch($pengaduan->status) {
                                    case 'Diajukan': 
                                        $progress = 25; 
                                        $statusText = '🟡 Menunggu';
                                        break;
                                    case 'Disetujui': 
                                        $progress = 50;
                                        $statusText = '🟢 Disetujui';
                                        break;
                                    case 'Diproses': 
                                        $progress = 75;
                                        $statusText = '🔵 Diproses';
                                        break;
                                    case 'Selesai': 
                                        $progress = 100;
                                        $statusText = '✅ Selesai';
                                        break;
                                    case 'Ditolak': 
                                        $progress = 100;
                                        $statusText = '🔴 Ditolak';
                                        break;
                                }
                            @endphp
                            
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-gray-700">{{ $statusText }}</span>
                                <span class="text-xs font-bold text-purple-600">{{ $progress }}%</span>
                            </div>
                            
                            <div class="progress-modern">
                                <div class="progress-modern-fill" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                        
                        <i class="fas fa-chevron-right text-gray-300 text-xl"></i>
                    </div>
                </a>
            @empty
                <div class="p-16 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-inbox text-purple-400 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Belum Ada Pengaduan</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">Mulai laporkan masalah sarana prasarana sekolah Anda sekarang!</p>
                    <a href="{{ route('pengaduan.create') }}" 
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-4 px-8 rounded-2xl hover:shadow-xl transition-all hover:-translate-y-1">
                        <i class="fas fa-plus-circle"></i>
                        <span>Buat Pengaduan Pertama</span>
                    </a>
                </div>
            @endforelse
        </div>

        @if(isset($recentPengaduans) && $recentPengaduans->count() > 0)
        <div class="p-6 bg-gray-50 border-t border-gray-100 lg:hidden">
            <a href="{{ route('pengaduan.index') }}" 
               class="flex items-center justify-center gap-3 w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-4 px-6 rounded-2xl hover:shadow-xl transition-all">
                <span>Lihat Semua Pengaduan</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection