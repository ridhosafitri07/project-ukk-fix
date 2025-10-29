@extends('layouts.admin')

@section('title', 'Daftar Permintaan Sarpras')
@section('header', 'Daftar Permintaan Sarana Prasarana')
@section('subheader', 'Kelola semua permintaan sarpras')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>
                    Semua Permintaan
                </h3>
                <p class="text-sm text-gray-600 mt-1">Daftar lengkap permintaan sarpras</p>
            </div>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center transition-all">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <button class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center shadow-md transition-all">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Barang
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Lokasi
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Jumlah
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($permintaan as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-gray-900">#{{ $item->id_item }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="far fa-calendar text-gray-400 mr-2"></i>
                            {{ $item->tanggal_permintaan->format('d/m/Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-box text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->nama_barang_baru ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ Str::limit($item->lokasi_barang_baru ?? '-', 30) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                            unit
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($item->status_permintaan === 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800
                            @elseif($item->status_permintaan === 'Disetujui') bg-green-100 text-green-800
                            @elseif($item->status_permintaan === 'Ditolak') bg-red-100 text-red-800
                            @endif">
                            {{ $item->status_permintaan }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.sarpras.show-permintaan', ['id' => $item->id_item]) }}" 
                           class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">Tidak ada permintaan</p>
                        <p class="text-gray-400 text-sm mt-2">Permintaan akan muncul di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $permintaan->links() }}
    </div>
</div>
@endsection