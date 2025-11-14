@extends('layouts.petugas')

@section('title', 'Pengaduan Saya')
@section('header', 'Pengaduan Saya')
@section('subheader', 'Daftar pengaduan yang ditugaskan kepada Anda')

@section('content')
<div class="space-y-8">

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <!-- Baru Diajukan -->
        <div class="stat-card card p-4 md:p-6 animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-inbox text-yellow-600 text-lg md:text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-xl md:text-2xl font-bold text-gray-900">-</div>
                    <div class="text-xs text-gray-500">Baru</div>
                </div>
            </div>
            <div class="text-xs md:text-sm text-gray-600">Baru Diajukan</div>
        </div>

        <!-- Siap Diproses -->
        <div class="stat-card card p-4 md:p-6 animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clipboard-check text-green-600 text-lg md:text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-xl md:text-2xl font-bold text-gray-900">-</div>
                    <div class="text-xs text-gray-500">Siap</div>
                </div>
            </div>
            <div class="text-xs md:text-sm text-gray-600">Siap Diproses</div>
        </div>

        <!-- Tugas Saya -->
        <div class="stat-card card p-4 md:p-6 animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-check text-blue-600 text-lg md:text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-xl md:text-2xl font-bold text-gray-900">{{ $statistics['tugas_saya'] }}</div>
                    <div class="text-xs text-gray-500">Tugas</div>
                </div>
            </div>
            <div class="text-xs md:text-sm text-gray-600">Tugas Saya</div>
        </div>

        <!-- Sedang Diproses -->
        <div class="stat-card card p-4 md:p-6 animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-cog text-purple-600 text-lg md:text-xl animate-spin"></i>
                </div>
                <div class="text-right">
                    <div class="text-xl md:text-2xl font-bold text-gray-900">{{ $statistics['diproses'] }}</div>
                    <div class="text-xs text-gray-500">Proses</div>
                </div>
            </div>
            <div class="text-xs md:text-sm text-gray-600">Sedang Diproses</div>
        </div>
    </div>

    <!-- Pengaduan Saya Table -->
    <div class="card animate-fade-in-up animate-delay-5">
        <div class="p-4 md:p-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clipboard-list text-blue-600 text-lg md:text-xl"></i>
                </div>
                <div>
                    <h4 class="text-base md:text-lg font-bold text-blue-800">Pengaduan Saya</h4>
                    <p class="text-xs md:text-sm text-blue-700 mt-1">Daftar pengaduan yang ditugaskan kepada Anda. Klik Detail untuk mengambil atau menyelesaikan tugas.</p>
                </div>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">Pengadu</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($pengaduanSaya as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-slate-900">#{{ $item->id_pengaduan }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <i class="fas fa-calendar text-blue-500"></i>
                                {{ date('d/m/Y', strtotime($item->tgl_pengajuan)) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-xs">
                                        {{ strtoupper(substr($item->user->nama_pengguna, 0, 2)) }}
                                    </span>
                                </div>
                                <span class="text-sm text-slate-900">{{ Str::limit($item->user->nama_pengguna, 20) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-900">{{ Str::limit($item->nama_pengaduan, 40) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <i class="fas fa-map-marker-alt text-green-500"></i>
                                {{ Str::limit($item->lokasi, 20) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = '';
                                switch($item->status) {
                                    case 'Diajukan': $statusColor = 'yellow'; break;
                                    case 'Disetujui': $statusColor = 'green'; break;
                                    case 'Diproses': $statusColor = 'blue'; break;
                                    case 'Selesai': $statusColor = 'purple'; break;
                                    case 'Ditolak': $statusColor = 'red'; break;
                                }
                            @endphp
                            <span class="status-badge bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('petugas.pengaduan.show', $item) }}" 
                               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-900 font-medium transition-colors">
                                <i class="fas fa-eye"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-inbox text-slate-400 text-2xl"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2">Tidak Ada Pengaduan</h3>
                                <p class="text-slate-600 mb-4">Belum ada pengaduan yang ditugaskan kepada Anda</p>
                                <p class="text-slate-400 text-sm">Pengaduan yang ditugaskan akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse ($pengaduanSaya as $item)
            <a href="{{ route('petugas.pengaduan.show', $item) }}" class="block p-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-blue-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-900">#{{ $item->id_pengaduan }}</span>
                            @php
                                $statusColor = '';
                                switch($item->status) {
                                    case 'Diajukan': $statusColor = 'yellow'; break;
                                    case 'Disetujui': $statusColor = 'green'; break;
                                    case 'Diproses': $statusColor = 'blue'; break;
                                    case 'Selesai': $statusColor = 'purple'; break;
                                    case 'Ditolak': $statusColor = 'red'; break;
                                }
                            @endphp
                            <span class="status-badge bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                {{ $item->status }}
                            </span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                            {{ $item->nama_pengaduan }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar text-blue-500"></i>
                                {{ date('d/m/Y', strtotime($item->tgl_pengajuan)) }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-map-marker-alt text-green-500"></i>
                                {{ Str::limit($item->lokasi, 15) }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-user text-purple-500"></i>
                                {{ Str::limit($item->user->nama_pengguna, 15) }}
                            </span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 mt-2"></i>
                </div>
            </a>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-slate-400 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Tidak Ada Pengaduan</h3>
                <p class="text-slate-600 text-sm">Belum ada pengaduan yang ditugaskan</p>
            </div>
            @endforelse
        </div>

        @if($pengaduanSaya->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $pengaduanSaya->links() }}
        </div>
        @endif
    </div>
</div>
@endsection