@extends('layouts.pengguna')

@section('title', 'Dashboard')
@section('header', 'Dashboard Siswa')
@section('subheader', 'Selamat datang! Kelola pengaduan sarana prasarana sekolah Anda')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-500 to-sky-600 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Halo, {{ auth()->user()->nama_pengguna }}! 👋</h1>
                    <p class="text-blue-100 mb-6">Laporkan masalah sarana dan prasarana di sekolah dengan mudah</p>
                    <a href="{{ route('pengaduan.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:shadow-xl transform hover:scale-105 transition-all">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Buat Pengaduan Baru
                    </a>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-clipboard-list text-6xl text-white/80"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Pengaduan -->
        <a href="{{ route('pengaduan.index') }}" 
           class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105 border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-white text-xl"></i>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-blue-100 text-blue-700 rounded-full">Semua</span>
            </div>
            <div class="mb-1">
                <p class="text-3xl font-bold text-slate-800">{{ $totalPengaduan ?? 0 }}</p>
            </div>
            <p class="text-sm text-slate-600 font-medium">Total Pengaduan</p>
            <div class="mt-3 flex items-center text-blue-600 text-sm font-semibold">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>

        <!-- Selesai -->
        <a href="{{ route('pengaduan.index', ['status' => 'Selesai']) }}" 
           class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105 border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full">Selesai</span>
            </div>
            <div class="mb-1">
                <p class="text-3xl font-bold text-slate-800">{{ $selesaiCount ?? 0 }}</p>
            </div>
            <p class="text-sm text-slate-600 font-medium">Pengaduan Selesai</p>
            <div class="mt-3 flex items-center text-emerald-600 text-sm font-semibold">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>

        <!-- Dalam Proses -->
        <a href="{{ route('pengaduan.index', ['status' => 'Diproses']) }}" 
           class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105 border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cog fa-spin text-white text-xl"></i>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-amber-100 text-amber-700 rounded-full">Proses</span>
            </div>
            <div class="mb-1">
                <p class="text-3xl font-bold text-slate-800">{{ $prosesCount ?? 0 }}</p>
            </div>
            <p class="text-sm text-slate-600 font-medium">Dalam Proses</p>
            <div class="mt-3 flex items-center text-amber-600 text-sm font-semibold">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>

        <!-- Menunggu -->
        <a href="{{ route('pengaduan.index', ['status' => 'Diajukan']) }}" 
           class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105 border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-700 rounded-full">Menunggu</span>
            </div>
            <div class="mb-1">
                <p class="text-3xl font-bold text-slate-800">{{ $diajukanCount ?? 0 }}</p>
            </div>
            <p class="text-sm text-slate-600 font-medium">Menunggu Verifikasi</p>
            <div class="mt-3 flex items-center text-slate-600 text-sm font-semibold">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </div>
        </a>
    </div>

    <!-- Recent Pengaduan -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-blue-600 mr-2"></i>
                        Pengaduan Terbaru
                    </h2>
                    <p class="text-sm text-slate-600 mt-1">Daftar pengaduan yang baru saja Anda ajukan</p>
                </div>
                <a href="{{ route('pengaduan.index') }}" 
                   class="hidden sm:flex items-center px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors font-semibold text-sm">
                    <span>Lihat Semua</span>
                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

        @forelse($recentPengaduans ?? [] as $pengaduan)
            <a href="{{ route('pengaduan.show', $pengaduan) }}" 
               class="block p-6 border-b border-slate-100 hover:bg-slate-50 transition-colors group">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <h3 class="text-base font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                                {{ $pengaduan->nama_pengaduan }}
                            </h3>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600 mb-2">
                            <span class="flex items-center">
                                <i class="fas fa-map-marker-alt text-slate-400 mr-1.5"></i>
                                {{ $pengaduan->lokasi }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar text-slate-400 mr-1.5"></i>
                                {{ \Carbon\Carbon::parse($pengaduan->tgl_pengajuan)->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4 flex flex-col items-end">
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full mb-2
                            @if($pengaduan->status === 'Diajukan') bg-slate-100 text-slate-700
                            @elseif($pengaduan->status === 'Disetujui') bg-green-100 text-green-700
                            @elseif($pengaduan->status === 'Ditolak') bg-red-100 text-red-700
                            @elseif($pengaduan->status === 'Diproses') bg-amber-100 text-amber-700
                            @elseif($pengaduan->status === 'Selesai') bg-emerald-100 text-emerald-700
                            @else bg-slate-100 text-slate-700
                            @endif">
                            {{ $pengaduan->status }}
                        </span>
                        <i class="fas fa-chevron-right text-slate-400 text-sm group-hover:text-blue-600 transition-colors"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-slate-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum Ada Pengaduan</h3>
                <p class="text-slate-600 mb-4">Anda belum mengajukan pengaduan apapun</p>
                <a href="{{ route('pengaduan.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-sky-600 text-white rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Buat Pengaduan
                </a>
            </div>
        @endforelse

        @if(count($recentPengaduans ?? []) > 0)
        <div class="p-4 bg-slate-50 sm:hidden">
            <a href="{{ route('pengaduan.index') }}" 
               class="flex items-center justify-center px-4 py-3 text-blue-600 hover:bg-white rounded-xl transition-colors font-semibold text-sm">
                <span>Lihat Semua Pengaduan</span>
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection