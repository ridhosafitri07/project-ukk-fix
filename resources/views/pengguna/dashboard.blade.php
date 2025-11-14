@extends('layouts.pengguna')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Kelola pengaduan sarana dan prasarana sekolah Anda')

@section('content')
<div class="space-y-8">

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl overflow-hidden shadow-xl text-white p-8 animate-fade-in-up">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <div class="flex-1">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-4">
                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                    <span class="text-white/90 text-sm font-medium">Sistem Aktif</span>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-bold mb-3 leading-tight">
                    Selamat Datang, <span class="text-yellow-200">{{ auth()->user()->nama_pengguna }}</span>!
                </h1>
                <p class="text-blue-100 text-lg mb-6">
                    Pantau dan kelola pengaduan sarana prasarana sekolah Anda dengan cepat dan efisien.
                </p>
                
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('pengaduan.create') }}" 
                       class="btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Buat Pengaduan
                    </a>
                    <a href="{{ route('pengaduan.index') }}" 
                       class="bg-white text-blue-600 hover:bg-blue-50 font-semibold py-3 px-6 rounded-xl transition flex items-center gap-2">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:flex justify-center">
                <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-clipboard-check text-4xl mb-2 text-white/80"></i>
                        <div class="text-2xl font-bold">{{ $totalPengaduan ?? 0 }}</div>
                        <div class="text-sm text-blue-200">Total Pengaduan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pengaduan -->
        <div class="stat-card card p-6 animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-gray-900">{{ $totalPengaduan ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total</div>
                </div>
            </div>
            <div class="text-sm text-gray-600">Semua status pengaduan</div>
        </div>

        <!-- Selesai -->
        <div class="stat-card card p-6 animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-gray-900">{{ $selesaiCount ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Selesai</div>
                </div>
            </div>
            <div class="text-sm text-gray-600">Telah ditangani</div>
        </div>

        <!-- Diproses -->
        <div class="stat-card card p-6 animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cog text-amber-600 text-xl animate-spin"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-gray-900">{{ $prosesCount ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Proses</div>
                </div>
            </div>
            <div class="text-sm text-gray-600">Sedang ditangani</div>
        </div>

        <!-- Menunggu -->
        <div class="stat-card card p-6 animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-gray-600 text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-gray-900">{{ $diajukanCount ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Menunggu</div>
                </div>
            </div>
            <div class="text-sm text-gray-600">Perlu verifikasi</div>
        </div>
    </div>

    <!-- Recent Pengaduan -->
    <div class="card animate-fade-in-up animate-delay-5">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Pengaduan Terbaru</h2>
                    <p class="text-gray-600 text-sm">Riwayat pengaduan terbaru Anda</p>
                </div>
                <a href="{{ route('pengaduan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium desktop-only">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($recentPengaduans ?? [] as $pengaduan)
                <a href="{{ route('pengaduan.show', $pengaduan) }}" class="block p-6 hover:bg-blue-50 transition-colors">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-blue-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">
                                {{ $pengaduan->nama_pengaduan }}
                            </h3>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-blue-500"></i>
                                    {{ $pengaduan->lokasi }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-calendar text-blue-500"></i>
                                    {{ \Carbon\Carbon::parse($pengaduan->tgl_pengajuan)->format('d M Y') }}
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            @php
                                $progress = 0;
                                $color = 'blue';
                                switch($pengaduan->status) {
                                    case 'Diajukan': $progress = 25; $color = 'yellow'; break;
                                    case 'Disetujui': $progress = 50; $color = 'green'; break;
                                    case 'Diproses': $progress = 75; $color = 'indigo'; break;
                                    case 'Selesai': $progress = 100; $color = 'emerald'; break;
                                    case 'Ditolak': $progress = 100; $color = 'red'; break;
                                }
                            @endphp
                            
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-500">Progress: {{ $progress }}%</span>
                                <span class="status-badge bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ $pengaduan->status }}
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill bg-{{ $color }}-500" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Belum Ada Pengaduan</h3>
                    <p class="text-gray-600 mb-4">Buat pengaduan pertama Anda untuk memulai.</p>
                    <a href="{{ route('pengaduan.create') }}" class="btn-primary">
                        Buat Pengaduan
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mobile-only p-4 border-t border-gray-200">
            <a href="{{ route('pengaduan.index') }}" class="btn-primary w-full text-center">
                Lihat Semua Pengaduan
            </a>
        </div>
    </div>
</div>
@endsection