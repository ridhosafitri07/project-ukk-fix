@extends('layouts.petugas')

@section('title', 'Tugas Pengaduan')
@section('header', 'Tugas Pengaduan')
@section('subheader', 'Kelola dan tangani pengaduan yang masuk')

@section('content')
<!-- Statistics -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6 md:mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg md:rounded-xl shadow-lg p-3 md:p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between gap-2 md:gap-0">
            <div class="min-w-0">
                <p class="text-xs md:text-sm font-medium text-blue-100 truncate">Total Tugas</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $statistics['total'] }}</p>
            </div>
            <i class="fas fa-clipboard-list text-3xl md:text-4xl text-blue-200 flex-shrink-0"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg md:rounded-xl shadow-lg p-3 md:p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between gap-2 md:gap-0">
            <div class="min-w-0">
                <p class="text-xs md:text-sm font-medium text-yellow-100 truncate">Perlu Dikerjakan</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $statistics['disetujui'] }}</p>
            </div>
            <i class="fas fa-exclamation-circle text-3xl md:text-4xl text-yellow-200 flex-shrink-0"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg md:rounded-xl shadow-lg p-3 md:p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between gap-2 md:gap-0">
            <div class="min-w-0">
                <p class="text-xs md:text-sm font-medium text-green-100 truncate">Sedang Diproses</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $statistics['diproses'] }}</p>
            </div>
            <i class="fas fa-cog text-3xl md:text-4xl text-green-200 flex-shrink-0"></i>
        </div>
    </div>
</div>

<!-- Pengaduan List -->
<div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden flex flex-col">
    <div class="p-4 md:p-6 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 md:gap-0">
            <h3 class="text-base md:text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-list text-green-500"></i>
                <span>Daftar Tugas Pengaduan</span>
            </h3>
            <div class="flex gap-2 flex-shrink-0">
                <button class="px-3 md:px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 flex items-center gap-1 md:gap-2 text-xs md:text-sm transition-colors">
                    <i class="fas fa-filter"></i>
                    <span class="hidden sm:inline">Filter</span>
                </button>
            </div>
        </div>
    </div>
    
    <div class="flex-1 overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr class="text-left">
                    <th scope="col" class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap">
                        ID
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap hidden sm:table-cell">
                        Tanggal
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap hidden md:table-cell">
                        Pengadu
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">
                        Judul
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap hidden lg:table-cell">
                        Lokasi
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap">
                        Status
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse ($pengaduan as $item)
                <tr class="hover:bg-slate-50 transition-colors text-sm md:text-base">
                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                        <span class="text-xs md:text-sm font-bold text-slate-900">#{{ $item->id_pengaduan }}</span>
                    </td>
                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap hidden sm:table-cell">
                        <span class="text-xs md:text-sm text-slate-600 flex items-center gap-1">
                            <i class="far fa-calendar text-slate-400"></i>
                            {{ date('d/m/Y', strtotime($item->tgl_pengajuan)) }}
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap hidden md:table-cell">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-green-600 font-bold text-xs">{{ strtoupper(substr($item->user->nama_pengguna, 0, 2)) }}</span>
                            </div>
                            <p class="text-xs md:text-sm font-medium text-slate-900 truncate hidden lg:inline">{{ Str::limit($item->user->nama_pengguna, 15) }}</p>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-3 md:py-4">
                        <div class="text-xs md:text-sm text-slate-900 font-medium truncate">{{ Str::limit($item->nama_pengaduan, 30) }}</div>
                    </td>
                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap hidden lg:table-cell">
                        <span class="text-xs md:text-sm text-slate-600 flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-slate-400"></i>
                            {{ Str::limit($item->lokasi, 15) }}
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                        <span class="px-2 md:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full whitespace-nowrap
                            @if($item->status === 'Disetujui') bg-yellow-100 text-yellow-800
                            @elseif($item->status === 'Diproses') bg-green-100 text-green-800
                            @else bg-slate-100 text-slate-800
                            @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                        <a href="{{ route('petugas.pengaduan.show', $item) }}" 
                           class="text-green-600 hover:text-green-900 inline-flex items-center gap-1 text-xs md:text-sm font-medium">
                            <i class="fas fa-eye"></i>
                            <span class="hidden sm:inline">Detail</span>
                            <span class="sm:hidden">Lihat</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 md:py-12 text-center">
                        <i class="fas fa-inbox text-slate-300 text-4xl md:text-5xl mb-3 md:mb-4"></i>
                        <p class="text-slate-500 font-medium text-sm md:text-base">Tidak ada tugas pengaduan</p>
                        <p class="text-slate-400 text-xs md:text-sm mt-2">Tugas akan muncul di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-3 md:px-6 py-3 md:py-4 border-t border-slate-200 bg-slate-50 text-sm">
        {{ $pengaduan->links() }}
    </div>
</div>
@endsection
