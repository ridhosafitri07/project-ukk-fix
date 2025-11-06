@extends('layouts.admin')

@section('title', 'Edit Lokasi')
@section('header', 'Edit Lokasi')
@section('subheader', 'Update data lokasi/ruangan')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.master-lokasi.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition shadow">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Lokasi
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Form Edit Lokasi
            </h3>
            <p class="text-yellow-100 text-sm mt-1">Update informasi lokasi: {{ $masterLokasi->nama_lokasi }}</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.master-lokasi.update', $masterLokasi->id_lokasi) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Informasi:</p>
                        <p class="text-sm text-blue-600 mt-1">
                            ID Lokasi: <span class="font-bold">#{{ $masterLokasi->id_lokasi }}</span><br>
                            Dibuat: {{ $masterLokasi->created_at ? $masterLokasi->created_at->format('d M Y H:i') : '-' }}<br>
                            Terakhir Update: {{ $masterLokasi->updated_at ? $masterLokasi->updated_at->format('d M Y H:i') : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Nama Lokasi -->
            <div class="space-y-2">
                <label for="nama_lokasi" class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-map-marker-alt text-yellow-600 mr-2"></i>
                    Nama Lokasi/Ruangan <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_lokasi" 
                       id="nama_lokasi" 
                       value="{{ old('nama_lokasi', $masterLokasi->nama_lokasi) }}"
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition @error('nama_lokasi') border-red-500 @enderror"
                       placeholder="Contoh: Ruang Kelas 1, Lab Komputer, Kantin">
                @error('nama_lokasi')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Nama lokasi harus unik dan belum terdaftar
                </p>
            </div>

            <!-- Kategori -->
            <div class="space-y-2">
                <label for="kategori" class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-tags text-yellow-600 mr-2"></i>
                    Kategori Lokasi <span class="text-red-500">*</span>
                </label>
                <select name="kategori" 
                        id="kategori" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition @error('kategori') border-red-500 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="kelas" {{ old('kategori', $masterLokasi->kategori) == 'kelas' ? 'selected' : '' }}>
                        🎓 Kelas
                    </option>
                    <option value="lab" {{ old('kategori', $masterLokasi->kategori) == 'lab' ? 'selected' : '' }}>
                        🧪 Laboratorium
                    </option>
                    <option value="kantor" {{ old('kategori', $masterLokasi->kategori) == 'kantor' ? 'selected' : '' }}>
                        🏢 Kantor
                    </option>
                    <option value="umum" {{ old('kategori', $masterLokasi->kategori) == 'umum' ? 'selected' : '' }}>
                        🚪 Umum (Aula, Perpustakaan, dll)
                    </option>
                    <option value="area_luar" {{ old('kategori', $masterLokasi->kategori) == 'area_luar' ? 'selected' : '' }}>
                        🌳 Area Luar (Lapangan, Taman, dll)
                    </option>
                </select>
                @error('kategori')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Current Badge -->
            <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1"></i>
                    Badge Saat Ini:
                </p>
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-{{ $masterLokasi->kategori_badge['color'] }}-100 text-{{ $masterLokasi->kategori_badge['color'] }}-800">
                    <i class="fas {{ $masterLokasi->kategori_badge['icon'] }} mr-2"></i>
                    {{ $masterLokasi->kategori_badge['label'] }}
                </span>
            </div>

            <!-- Warning jika ada barang -->
            @if($masterLokasi->items()->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3 mt-0.5"></i>
                        <div>
                            <p class="text-sm text-yellow-700 font-medium">Perhatian!</p>
                            <p class="text-sm text-yellow-600 mt-1">
                                Lokasi ini memiliki <span class="font-bold">{{ $masterLokasi->items()->count() }} barang</span> yang terdaftar.
                                Pastikan perubahan tidak memengaruhi data relasi barang.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t-2 border-gray-200">
                <a href="{{ route('admin.master-lokasi.index') }}" 
                   class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Update Lokasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
