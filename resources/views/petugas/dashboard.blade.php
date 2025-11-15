@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('header', 'Dashboard Petugas')
@section('subheader', 'Kelola pengaduan dan pantau tugas Anda')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 rounded-3xl overflow-hidden shadow-xl text-white p-8 md:p-12 animate-fade-in-up">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">
            <div class="flex-1">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 leading-tight">
                    Halo, {{ auth()->user()->nama_pengguna }}! 👋
                </h1>
                <p class="text-blue-100 text-lg md:text-xl mb-6 max-w-2xl">
                    Anda memiliki {{ $totalTugas ?? 0 }} pengaduan yang perlu ditangani. Mari kita selesaikan dengan baik!
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('petugas.pengaduan.index') }}" 
                       class="px-6 py-3 bg-white text-blue-600 font-bold rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl flex items-center justify-center">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Pengaduan
                    </a>
                    <a href="{{ route('petugas.pengaduan.index', ['status' => 'Diproses']) }}" 
                       class="px-6 py-3 bg-white/20 text-white font-bold rounded-xl hover:bg-white/30 transition-all border-2 border-white/40 flex items-center justify-center">
                        <i class="fas fa-spinner mr-2"></i>
                        Sedang Diproses
                    </a>
                </div>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div class="relative w-48 h-48">
                    <div class="absolute inset-0 bg-white/10 rounded-3xl blur-3xl"></div>
                    <div class="relative w-full h-full rounded-3xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                        <i class="fas fa-tasks text-7xl text-white/40"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
        <!-- Total Pengaduan Card -->
        <div class="group card p-6 md:p-8 animate-fade-in-up hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-blue-50 to-white border-l-4 border-blue-600">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-gray-600 font-medium text-sm">Total Pengaduan</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalTugas ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-inbox text-2xl text-blue-600"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-blue-600 font-semibold">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>Semua masuk</span>
            </div>
        </div>

        <!-- Sedang Diproses Card -->
        <div class="group card p-6 md:p-8 animate-fade-in-up animate-delay-1 hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-orange-50 to-white border-l-4 border-orange-500">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-gray-600 font-medium text-sm">Sedang Diproses</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $tugasAktif ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-spinner text-2xl text-orange-600 animate-spin"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-orange-600 font-semibold">
                <i class="fas fa-clock mr-1"></i>
                <span>Aktif sekarang</span>
            </div>
        </div>

        <!-- Selesai Card -->
        <div class="group card p-6 md:p-8 animate-fade-in-up animate-delay-2 hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-emerald-50 to-white border-l-4 border-emerald-500">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-gray-600 font-medium text-sm">Telah Selesai</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $tugasSelesai ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-double text-2xl text-emerald-600"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-emerald-600 font-semibold">
                <i class="fas fa-trophy mr-1"></i>
                <span>Pencapaian</span>
            </div>
        </div>

        <!-- Perlu Tindakan Card -->
        <div class="group card p-6 md:p-8 animate-fade-in-up animate-delay-3 hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-red-50 to-white border-l-4 border-red-500">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-gray-600 font-medium text-sm">Perlu Tindakan</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $perluTindakan ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-red-600 font-semibold">
                <i class="fas fa-bell mr-1"></i>
                <span>Urgent</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- Pengaduan Terbaru -->
        <div class="lg:col-span-2 card animate-fade-in-up animate-delay-4 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-fire text-blue-600 mr-3"></i>
                            Pengaduan Terbaru
                        </h2>
                        <p class="text-gray-600 text-sm mt-2">Daftar pengaduan yang baru masuk</p>
                    </div>
                    <a href="{{ route('petugas.pengaduan.index') }}" 
                       class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors flex items-center whitespace-nowrap">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="space-y-4">
                    @forelse($tugasTerbaru ?? [] as $tugas)
                    <a href="{{ route('petugas.pengaduan.show', $tugas) }}" 
                       class="group block p-5 md:p-6 border border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all duration-300">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <div class="flex-shrink-0 mt-1">
                                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl
                                        @if($tugas->status == 'Disetujui') bg-blue-100 text-blue-600
                                        @elseif($tugas->status == 'Diproses') bg-orange-100 text-orange-600
                                        @elseif($tugas->status == 'Selesai') bg-emerald-100 text-emerald-600
                                        @else bg-gray-100 text-gray-600 @endif">
                                        <i class="fas fa-{{ $tugas->status == 'Disetujui' ? 'check' : ($tugas->status == 'Diproses' ? 'cog' : ($tugas->status == 'Selesai' ? 'check-double' : 'info-circle')) }}"></i>
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base md:text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                                        {{ $tugas->nama_pengaduan }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1 truncate flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                        {{ $tugas->lokasi ?? 'Lokasi tidak ditentukan' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="far fa-clock mr-2"></i>
                                        {{ \Carbon\Carbon::parse($tugas->tgl_pengajuan)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                    @if($tugas->status == 'Disetujui') bg-blue-100 text-blue-800
                                    @elseif($tugas->status == 'Diproses') bg-orange-100 text-orange-800
                                    @elseif($tugas->status == 'Selesai') bg-emerald-100 text-emerald-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $tugas->status }}
                                </span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-16">
                        <i class="fas fa-inbox text-gray-300 text-6xl mb-4 block"></i>
                        <p class="text-gray-500 font-semibold text-lg">Belum ada pengaduan</p>
                        <p class="text-gray-400 text-sm mt-2">Pengaduan baru akan muncul di sini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar: Performance & Info -->
        <div class="space-y-6 md:space-y-8">
            
            <!-- Performance Card -->
            <div class="card animate-fade-in-up animate-delay-5 overflow-hidden">
                <div class="p-6 md:p-8 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Performa Anda
                    </h3>
                    <p class="text-blue-100 text-sm mt-1">Statistik pekerjaan bulan ini</p>
                </div>
                <div class="p-6 md:p-8 space-y-4">
                    <!-- Completion Rate -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Tingkat Penyelesaian</span>
                            <span class="text-lg font-bold text-blue-600">{{ $completionRate ?? 0 }}%</span>
                        </div>
                        <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500" 
                                 style="width: {{ $completionRate ?? 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Stats List -->
                    <div class="pt-4 border-t border-gray-100 space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-transparent rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-blue-600"></i>
                                </div>
                                <span class="font-medium text-gray-700">Selesai</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $tugasSelesai ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-transparent rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-spinner text-orange-600"></i>
                                </div>
                                <span class="font-medium text-gray-700">Diproses</span>
                            </div>
                            <span class="text-lg font-bold text-orange-600">{{ $tugasAktif ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-red-50 to-transparent rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-exclamation text-red-600"></i>
                                </div>
                                <span class="font-medium text-gray-700">Perlu Tindakan</span>
                            </div>
                            <span class="text-lg font-bold text-red-600">{{ $perluTindakan ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card animate-fade-in-up animate-delay-6 overflow-hidden">
                <div class="p-6 md:p-8 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-lightning-bolt text-indigo-600 mr-3"></i>
                        Akses Cepat
                    </h3>
                </div>
                <div class="p-6 md:p-8 space-y-3">
                    <a href="{{ route('petugas.pengaduan.index', ['status' => 'Diproses']) }}" 
                       class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all group">
                        <span class="font-medium text-gray-700 group-hover:text-blue-600">Pengaduan Aktif</span>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600"></i>
                    </a>
                    <a href="{{ route('petugas.pengaduan.index', ['status' => 'Selesai']) }}" 
                       class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-emerald-400 hover:bg-emerald-50 transition-all group">
                        <span class="font-medium text-gray-700 group-hover:text-emerald-600">Yang Selesai</span>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-emerald-600"></i>
                    </a>
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 transition-all group">
                        <span class="font-medium text-gray-700 group-hover:text-indigo-600">Edit Profil</span>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-indigo-600"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .animate-delay-4 { animation-delay: 0.4s; }
    .animate-delay-5 { animation-delay: 0.5s; }
    .animate-delay-6 { animation-delay: 0.6s; }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
        opacity: 0;
    }
</style>
@endsection