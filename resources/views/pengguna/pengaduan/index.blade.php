@extends('layouts.pengguna')

@section('title', 'Daftar Pengaduan')
@section('header', 'Daftar Pengaduan')
@section('subheader', 'Kelola semua pengaduan sarana dan prasarana Anda')

@section('content')
<style>
    .list-item {
        transition: all 0.2s ease;
    }

    .list-item:hover {
        background: linear-gradient(to right, rgba(59, 130, 246, 0.05), rgba(99, 102, 241, 0.05));
        transform: translateX(4px);
    }

    .badge-temporary {
        background: linear-gradient(135deg, #8b5cf6 0%, #c084fc 100%);
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-weight: 600;
    }

    .stat-mini-card {
        background: white;
        border-radius: 1rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .stat-mini-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 animate-fade-in-up">
    @php
        $statusCounts = [
            'Diajukan' => $pengaduans->where('status', 'Diajukan')->count(),
            'Diproses' => $pengaduans->where('status', 'Diproses')->count(),
            'Selesai' => $pengaduans->where('status', 'Selesai')->count(),
            'Total' => $pengaduans->count()
        ];
    @endphp
    
    <div class="stat-mini-card border-l-4 border-yellow-500 animate-fade-in-up animate-delay-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Menunggu</p>
                <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['Diajukan'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="stat-mini-card border-l-4 border-blue-500 animate-fade-in-up animate-delay-2">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Diproses</p>
                <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['Diproses'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-cog text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="stat-mini-card border-l-4 border-green-500 animate-fade-in-up animate-delay-3">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['Selesai'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="stat-mini-card border-l-4 border-indigo-500 animate-fade-in-up animate-delay-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Total Pengaduan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['Total'] }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-list text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="mb-6 flex flex-wrap gap-3 justify-between items-center animate-fade-in-up">
    <div class="flex items-center gap-3">
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200 flex items-center space-x-2">
            <i class="fas fa-filter text-gray-400"></i>
            <span class="text-sm text-gray-600">Semua Status</span>
        </div>
    </div>
    
    <div class="flex gap-3">
        <a href="{{ route('pengguna.dashboard') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-xl shadow-md transition flex items-center space-x-2 border border-gray-200">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('pengaduan.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            <span>Buat Pengaduan</span>
        </a>
    </div>
</div>

<!-- Pengaduan List -->
<div class="card animate-fade-in-up">
    <ul role="list" class="divide-y divide-gray-100">
        @forelse($pengaduans as $index => $pengaduan)
            <li class="list-item animate-fade-in-up" style="animation-delay: {{ 0.05 * ($index + 1) }}s">
                <a href="{{ route('pengaduan.show', $pengaduan) }}" class="block px-6 py-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-white text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 truncate mb-1">{{ $pengaduan->nama_pengaduan }}</h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center space-x-2">
                                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                                        <span>{{ $pengaduan->lokasi }}</span>
                                    </span>
                                    <span class="flex items-center space-x-2">
                                        <i class="fas fa-calendar text-indigo-500"></i>
                                        <span>{{ \Carbon\Carbon::parse($pengaduan->tgl_pengajuan)->format('d M Y') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <span class="status-badge whitespace-nowrap
                                @if($pengaduan->status === 'Diajukan') bg-yellow-100 text-yellow-800
                                @elseif($pengaduan->status === 'Disetujui') bg-green-100 text-green-800
                                @elseif($pengaduan->status === 'Ditolak') bg-red-100 text-red-800
                                @elseif($pengaduan->status === 'Diproses') bg-indigo-100 text-indigo-800
                                @elseif($pengaduan->status === 'Selesai') bg-emerald-100 text-emerald-800
                                @endif">
                                {{ $pengaduan->status }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Temporary Items Badge -->
                    @if($pengaduan->temporary_items && $pengaduan->temporary_items->count() > 0)
                    <div class="mt-3 mb-3">
                        <span class="badge-temporary">
                            <i class="fas fa-hourglass-half"></i>
                            {{ $pengaduan->temporary_items->count() }} Barang Baru
                        </span>
                    </div>
                    @endif
                    
                    <!-- Progress Bar -->
                    <div class="mt-4">
                        @php
                            $progress = 0;
                            $statusColor = 'gray';
                            switch($pengaduan->status) {
                                case 'Diajukan':
                                    $progress = 25;
                                    $statusColor = 'yellow';
                                    break;
                                case 'Disetujui':
                                    $progress = 50;
                                    $statusColor = 'green';
                                    break;
                                case 'Diproses':
                                    $progress = 75;
                                    $statusColor = 'indigo';
                                    break;
                                case 'Selesai':
                                    $progress = 100;
                                    $statusColor = 'emerald';
                                    break;
                                case 'Ditolak':
                                    $progress = 100;
                                    $statusColor = 'red';
                                    break;
                            }
                        @endphp
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-tasks text-{{ $statusColor }}-500 text-sm"></i>
                                <span class="text-xs font-medium text-gray-600">Progress: {{ $progress }}%</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-{{ $statusColor }}-400 to-{{ $statusColor }}-600 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </a>
            </li>
        @empty
            <li class="px-6 py-16">
                <div class="text-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-inbox text-blue-500 text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Pengaduan</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Mulai buat pengaduan untuk melaporkan masalah sarana prasarana yang perlu diperbaiki</p>
                    <a href="{{ route('pengaduan.create') }}" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Buat Pengaduan Pertama</span>
                    </a>
                </div>
            </li>
        @endforelse
    </ul>
</div>

@if($pengaduans->count() > 0)
    <div class="mt-6 flex justify-center">
        <div class="bg-white px-6 py-3 rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center space-x-3">
                <i class="fas fa-info-circle text-blue-600"></i>
                <span class="text-sm text-gray-700">Menampilkan <span class="font-bold text-blue-600">{{ $pengaduans->count() }}</span> pengaduan</span>
            </div>
        </div>
    </div>
@endif

@endsection