@extends('layouts.petugas')

@section('title', 'Pengaduan Saya')
@section('header', 'Pengaduan Saya')
@section('subheader', 'Daftar pengaduan yang ditugaskan kepada Anda')

@section('content')
<div class="space-y-8">

    {{-- Statistik Card --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        @foreach([
            ['icon' => 'fa-inbox', 'label' => 'Baru Diajukan', 'value' => '-', 'color' => 'yellow'],
            ['icon' => 'fa-clipboard-check', 'label' => 'Siap Diproses', 'value' => '-', 'color' => 'green'],
            ['icon' => 'fa-user-check', 'label' => 'Tugas Saya', 'value' => $statistics['tugas_saya'], 'color' => 'blue'],
            ['icon' => 'fa-cog', 'label' => 'Sedang Diproses', 'value' => $statistics['diproses'], 'color' => 'purple']
        ] as $stat)
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-{{ $stat['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-lg md:text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-xl md:text-2xl font-bold text-gray-900">{{ $stat['value'] }}</div>
                    <div class="text-xs text-gray-500">{{ $stat['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Daftar Pengaduan --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="p-4 md:p-6 border-b border-gray-100 bg-blue-50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clipboard-list text-blue-600 text-lg md:text-xl"></i>
                </div>
                <div>
                    <h4 class="text-base md:text-lg font-semibold text-blue-800">Pengaduan Saya</h4>
                    <p class="text-xs md:text-sm text-blue-700 mt-1">Klik "Detail" untuk mengambil atau menyelesaikan tugas.</p>
                </div>
            </div>
        </div>

        {{-- Tabel Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-blue-600 text-white">
                    <tr class="text-left text-xs uppercase tracking-wide">
                        <th class="px-6 py-3 font-medium">ID</th>
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3 font-medium">Pengadu</th>
                        <th class="px-6 py-3 font-medium">Judul</th>
                        <th class="px-6 py-3 font-medium">Lokasi</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($pengaduanSaya as $item)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $item->id_pengaduan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar text-blue-500 text-xs"></i>
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
                                <span class="text-sm text-gray-900">{{ Str::limit($item->user->nama_pengguna, 20) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ Str::limit($item->nama_pengaduan, 40) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-green-500 text-xs"></i>
                                {{ Str::limit($item->lokasi, 20) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Diajukan' => 'yellow',
                                    'Disetujui' => 'green',
                                    'Diproses' => 'blue',
                                    'Selesai' => 'purple',
                                    'Ditolak' => 'red'
                                ];
                                $color = $statusColors[$item->status] ?? 'gray';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('petugas.pengaduan.show', $item) }}" 
                               class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                <i class="fas fa-eye text-xs"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center">
                                    <i class="fas fa-inbox text-blue-400 text-xl"></i>
                                </div>
                                <h3 class="font-semibold text-blue-900">Tidak Ada Pengaduan</h3>
                                <p class="text-blue-700 text-sm">Belum ada pengaduan yang ditugaskan kepada Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tampilan Mobile --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse ($pengaduanSaya as $item)
            <a href="{{ route('petugas.pengaduan.show', $item) }}" class="block p-4 hover:bg-blue-50 transition-colors">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-blue-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-gray-900">#{{ $item->id_pengaduan }}</span>
                            @php
                                $color = $statusColors[$item->status] ?? 'gray';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                {{ $item->status }}
                            </span>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">
                            {{ $item->nama_pengaduan }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
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
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-blue-400 text-xl"></i>
                </div>
                <h3 class="font-semibold text-blue-900 mb-2">Tidak Ada Pengaduan</h3>
                <p class="text-blue-700 text-sm">Belum ada pengaduan yang ditugaskan.</p>
            </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        @if($pengaduanSaya->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $pengaduanSaya->links() }}
        </div>
        @endif
    </div>
</div>
@endsection