@extends('layouts.admin')

@section('title', 'Detail Lokasi')
@section('header', 'Detail Lokasi')
@section('subheader', $masterLokasi->nama_lokasi)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.master-lokasi.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition shadow">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Lokasi
        </a>
    </div>

    <!-- Main Info Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-{{ $masterLokasi->kategori_badge['color'] }}-500 to-{{ $masterLokasi->kategori_badge['color'] }}-600 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-white flex items-center">
                        <i class="fas {{ $masterLokasi->kategori_badge['icon'] }} mr-3"></i>
                        {{ $masterLokasi->nama_lokasi }}
                    </h2>
                    <p class="text-{{ $masterLokasi->kategori_badge['color'] }}-100 mt-2 text-lg">
                        ID Lokasi: #{{ $masterLokasi->id_lokasi }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.master-lokasi.edit', $masterLokasi->id_lokasi) }}" 
                       class="px-5 py-3 bg-white hover:bg-gray-100 text-gray-800 font-bold rounded-lg shadow-lg transition transform hover:scale-105">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Lokasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Details -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kategori -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 font-medium mb-2">Kategori</p>
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-{{ $masterLokasi->kategori_badge['color'] }}-100 text-{{ $masterLokasi->kategori_badge['color'] }}-800">
                                <i class="fas {{ $masterLokasi->kategori_badge['icon'] }} mr-2"></i>
                                {{ $masterLokasi->kategori_badge['label'] }}
                            </span>
                        </div>
                        <div class="bg-purple-200 rounded-full p-4">
                            <i class="fas fa-tags text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Barang -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 font-medium mb-2">Total Barang</p>
                            <p class="text-4xl font-bold text-blue-800">
                                {{ $masterLokasi->items()->count() }}
                            </p>
                            <p class="text-xs text-blue-600 mt-1">Item terdaftar</p>
                        </div>
                        <div class="bg-blue-200 rounded-full p-4">
                            <i class="fas fa-box text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Created At -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border-2 border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 font-medium mb-2">Dibuat Pada</p>
                            <p class="text-lg font-bold text-green-800">
                                {{ $masterLokasi->created_at ? $masterLokasi->created_at->format('d M Y') : '-' }}
                            </p>
                            <p class="text-xs text-green-600 mt-1">
                                {{ $masterLokasi->created_at ? $masterLokasi->created_at->format('H:i') : '' }}
                            </p>
                        </div>
                        <div class="bg-green-200 rounded-full p-4">
                            <i class="fas fa-calendar-plus text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Info -->
            <div class="mt-6 bg-gray-50 rounded-lg p-6 border-2 border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-100 rounded-full p-2">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-600">Dibuat</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $masterLokasi->created_at ? $masterLokasi->created_at->format('d M Y, H:i') : 'Tidak tersedia' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-yellow-100 rounded-full p-2">
                            <i class="fas fa-sync text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-600">Terakhir Update</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $masterLokasi->updated_at ? $masterLokasi->updated_at->format('d M Y, H:i') : 'Tidak tersedia' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List Barang di Lokasi Ini -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 px-8 py-5 border-b-4 border-indigo-700">
            <h3 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-boxes mr-3"></i>
                Daftar Barang di {{ $masterLokasi->nama_lokasi }}
            </h3>
            <p class="text-indigo-100 mt-1">
                Menampilkan semua barang yang terdaftar di lokasi ini
            </p>
        </div>

        <!-- Content -->
        <div class="p-8">
            @if($masterLokasi->items()->count() > 0)
                <!-- Statistics -->
                <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5 border-2 border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-indigo-500 rounded-full p-3">
                                <i class="fas fa-box-open text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-indigo-600 font-medium">Total Barang Terdaftar</p>
                                <p class="text-3xl font-bold text-indigo-800">
                                    {{ $masterLokasi->items()->count() }} Item
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-lg border-2 border-gray-200">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-1"></i>
                                    No
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-box mr-1"></i>
                                    Nama Barang
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Kondisi
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-link mr-1"></i>
                                    ID Relasi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($masterLokasi->items as $index => $item)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="bg-indigo-100 rounded-full p-2 mr-3">
                                                <i class="fas fa-cube text-indigo-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $item->nama_barang }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    ID Barang: #{{ $item->id_item }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($item->pivot->kondisi == 'baik')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Baik
                                            </span>
                                        @elseif($item->pivot->kondisi == 'rusak ringan')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Rusak Ringan
                                            </span>
                                        @elseif($item->pivot->kondisi == 'rusak berat')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Rusak Berat
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <i class="fas fa-question-circle mr-1"></i>
                                                {{ ucfirst($item->pivot->kondisi ?? 'Unknown') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700 font-mono">
                                        #{{ $item->pivot->id_list }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="inline-block bg-gray-100 rounded-full p-8 mb-6">
                        <i class="fas fa-box-open text-gray-400 text-6xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">
                        Belum Ada Barang
                    </h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        Lokasi ini belum memiliki barang yang terdaftar. Tambahkan barang melalui menu 
                        <span class="font-bold text-indigo-600">Relasi Barang-Ruangan</span>.
                    </p>
                    <div class="flex items-center justify-center space-x-3">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        <p class="text-sm text-gray-600">
                            Gunakan menu <span class="font-semibold">Manajemen Sarpras → Relasi Barang-Ruangan</span> untuk menambahkan barang ke lokasi ini
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between bg-white rounded-xl shadow-lg p-6">
        <a href="{{ route('admin.master-lokasi.index') }}" 
           class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition shadow">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.master-lokasi.edit', $masterLokasi->id_lokasi) }}" 
               class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition">
                <i class="fas fa-edit mr-2"></i>
                Edit Lokasi
            </a>
            <form action="{{ route('admin.master-lokasi.destroy', $masterLokasi->id_lokasi) }}" 
                  method="POST" 
                  onsubmit="return confirm('⚠️ PERHATIAN!\n\nAnda yakin ingin menghapus lokasi ini?\n\n{{ $masterLokasi->nama_lokasi }}\n\n{{ $masterLokasi->items()->count() > 0 ? 'Lokasi ini memiliki ' . $masterLokasi->items()->count() . ' barang terdaftar!' : '' }}\n\nData yang sudah dihapus tidak bisa dikembalikan!')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition"
                        {{ $masterLokasi->items()->count() > 0 ? 'disabled title="Tidak dapat menghapus lokasi yang memiliki barang terdaftar"' : '' }}>
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Lokasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
