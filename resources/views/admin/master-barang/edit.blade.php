@extends('layouts.admin')

@section('title', 'Edit Barang')
@section('header', 'Edit Barang')
@section('subheader', 'Update data barang/item')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.master-barang.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition shadow">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Barang
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Form Edit Barang
            </h3>
            <p class="text-yellow-100 text-sm mt-1">Update informasi barang: {{ $masterBarang->nama_item }}</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.master-barang.update', $masterBarang->id_item) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Informasi Barang:</p>
                        <p class="text-sm text-blue-600 mt-1">
                            ID Barang: <span class="font-bold">#{{ $masterBarang->id_item }}</span><br>
                            Terdaftar di: <span class="font-bold">{{ $masterBarang->lokasis()->count() }} lokasi</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Nama Barang -->
            <div class="space-y-2">
                <label for="nama_item" class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-box text-yellow-600 mr-2"></i>
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_item" 
                       id="nama_item" 
                       value="{{ old('nama_item', $masterBarang->nama_item) }}"
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition @error('nama_item') border-red-500 @enderror"
                       placeholder="Contoh: Kursi Kayu, Meja Lipat, Proyektor LCD">
                @error('nama_item')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Nama barang harus unik dan belum terdaftar (maksimal 200 karakter)
                </p>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-2">
                <label for="deskripsi" class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-align-left text-yellow-600 mr-2"></i>
                    Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                </label>
                <textarea name="deskripsi" 
                          id="deskripsi" 
                          rows="4"
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition @error('deskripsi') border-red-500 @enderror"
                          placeholder="Deskripsi lengkap barang, spesifikasi, catatan, dll...">{{ old('deskripsi', $masterBarang->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Tambahkan detail barang seperti spesifikasi, kondisi, atau catatan penting
                </p>
            </div>

            <!-- Current Data Display -->
            <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6">
                <p class="text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-database mr-1"></i>
                    Data Saat Ini:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">Nama Barang</p>
                        <p class="text-sm font-bold text-gray-900">{{ $masterBarang->nama_item }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">Jumlah Lokasi</p>
                        <p class="text-sm font-bold text-gray-900">
                            <i class="fas fa-map-marker-alt text-green-600 mr-1"></i>
                            {{ $masterBarang->lokasis()->count() }} Lokasi
                        </p>
                    </div>
                </div>
            </div>

            <!-- Warning jika ada di lokasi -->
            @if($masterBarang->lokasis()->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3 mt-0.5"></i>
                        <div>
                            <p class="text-sm text-yellow-700 font-medium">Perhatian!</p>
                            <p class="text-sm text-yellow-600 mt-1">
                                Barang ini terdaftar di <span class="font-bold">{{ $masterBarang->lokasis()->count() }} lokasi</span>.
                                Pastikan perubahan tidak memengaruhi data relasi.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t-2 border-gray-200">
                <a href="{{ route('admin.master-barang.index') }}" 
                   class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Update Barang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
