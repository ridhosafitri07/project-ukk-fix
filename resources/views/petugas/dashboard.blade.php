@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('header', 'Dashboard Petugas')
@section('subheader', 'Selamat datang, ' . auth()->user()->nama_pengguna)

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
    <!-- Total Tugas Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-4 md:p-6 border-l-4 border-blue-500 transition-all">
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs md:text-sm font-medium text-gray-500 uppercase">Total Tugas</p>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-lg md:text-2xl text-blue-500"></i>
                </div>
            </div>
            <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $totalTugas ?? 0 }}</p>
            <p class="text-xs text-blue-600 mt-1">
                <i class="fas fa-tasks mr-1"></i>
                Semua pengaduan
            </p>
        </div>
    </div>

    <!-- Tugas Aktif Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-4 md:p-6 border-l-4 border-blue-500 transition-all">
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs md:text-sm font-medium text-gray-500 uppercase">Aktif</p>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cog text-lg md:text-2xl text-blue-500"></i>
                </div>
            </div>
            <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $tugasAktif ?? 0 }}</p>
            <p class="text-xs text-blue-600 mt-1">
                <i class="fas fa-spinner mr-1"></i>
                Dikerjakan
            </p>
        </div>
    </div>

    <!-- Tugas Selesai Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-4 md:p-6 border-l-4 border-purple-500 transition-all">
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs md:text-sm font-medium text-gray-500 uppercase">Selesai</p>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-lg md:text-2xl text-purple-500"></i>
                </div>
            </div>
            <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $tugasSelesai ?? 0 }}</p>
            <p class="text-xs text-purple-600 mt-1">
                <i class="fas fa-check-double mr-1"></i>
                Bulan ini
            </p>
        </div>
    </div>

    <!-- Pending Review Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-4 md:p-6 border-l-4 border-yellow-500 transition-all">
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs md:text-sm font-medium text-gray-500 uppercase">Pending</p>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bell text-lg md:text-2xl text-yellow-500"></i>
                </div>
            </div>
            <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $perluTindakan ?? 0 }}</p>
            <p class="text-xs text-yellow-600 mt-1">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Segera tangani
            </p>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
    <!-- Tugas Terbaru -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 md:p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base md:text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-clipboard-check text-green-500 mr-2"></i>
                        Tugas Terbaru
                    </h3>
                    <p class="text-xs md:text-sm text-gray-500 mt-1">Pengaduan yang perlu ditangani</p>
                </div>
                <a href=\"{{ route('petugas.pengaduan.index') }}\" 
                   class=\"px-3 py-2 md:px-4 md:py-2 bg-blue-500 text-white text-xs md:text-sm rounded-lg hover:bg-blue-600 flex items-center transition-colors\">
                    <i class="fas fa-list mr-1 md:mr-2"></i>
                    <span class="hidden sm:inline">Lihat Semua</span>
                    <span class="sm:hidden">Semua</span>
                </a>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <div class="space-y-3 md:space-y-4">
                @forelse($tugasTerbaru ?? [] as $tugas)
                <div class="flex items-start p-3 md:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 border border-gray-100 transition-all">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-10 h-10 md:w-12 md:h-12 rounded-lg
                            @if($tugas->status == 'Disetujui') bg-blue-500
                            @elseif($tugas->status == 'Diproses') bg-green-500
                            @else bg-gray-500 @endif text-white">
                            <i class="fas fa-{{ $tugas->status == 'Disetujui' ? 'check' : ($tugas->status == 'Diproses' ? 'cog' : 'info-circle') }} text-base md:text-lg"></i>
                        </span>
                    </div>
                    <div class="ml-3 md:ml-4 flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="text-sm md:text-base font-bold text-gray-800 truncate">{{ $tugas->nama_pengaduan }}</h4>
                            <span class="px-2 md:px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap flex-shrink-0
                                @if($tugas->status == 'Disetujui') bg-blue-100 text-blue-800
                                @elseif($tugas->status == 'Diproses') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $tugas->status }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1 truncate">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $tugas->lokasi }}
                        </p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="far fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($tugas->tgl_pengajuan)->diffForHumans() }}
                            </p>
                            <a href="{{ route('petugas.pengaduan.show', $tugas) }}" 
                               class="text-xs text-green-600 hover:text-green-800 font-semibold">
                                Detail →
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 md:py-12">
                    <i class="fas fa-inbox text-gray-300 text-4xl md:text-5xl mb-3"></i>
                    <p class="text-gray-500 font-medium text-sm md:text-base">Belum ada tugas terbaru</p>
                    <p class="text-gray-400 text-xs md:text-sm mt-1">Tugas akan muncul di sini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-4 md:space-y-6">
        <!-- Performance Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 md:p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    Performa
                </h3>
                <p class="text-purple-100 text-xs md:text-sm mt-1">Statistik pekerjaan</p>
            </div>
            <div class="p-4 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-2 md:mr-3">
                                <i class="fas fa-check text-blue-600 text-sm md:text-base"></i>
                            </div>
                            <span class="text-xs md:text-sm font-medium text-gray-700">Selesai</span>
                        </div>
                        <span class="text-base md:text-lg font-bold text-blue-600">{{ $tugasSelesai ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-2 md:mr-3">
                                <i class="fas fa-spinner text-blue-600 text-sm md:text-base"></i>
                            </div>
                            <span class="text-xs md:text-sm font-medium text-gray-700">Proses</span>
                        </div>
                        <span class="text-base md:text-lg font-bold text-blue-600">{{ $tugasAktif ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-2 md:mr-3">
                                <i class="fas fa-clock text-yellow-600 text-sm md:text-base"></i>
                            </div>
                            <span class="text-xs md:text-sm font-medium text-gray-700">Pending</span>
                        </div>
                        <span class="text-base md:text-lg font-bold text-yellow-600">{{ $perluTindakan ?? 0 }}</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs md:text-sm font-medium text-gray-700">Completion Rate</span>
                        <span class="text-xs md:text-sm font-bold text-blue-600">{{ $completionRate ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 md:h-3">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 md:h-3 rounded-full transition-all" 
                             style="width: {{ $completionRate ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection