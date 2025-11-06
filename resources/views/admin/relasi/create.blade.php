@extends('layouts.admin')

@section('title', 'Tambah Relasi')
@section('header', 'Tambah Relasi Baru')
@section('subheader', 'Distribusikan barang ke lokasi/ruangan')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.relasi.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition shadow">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Relasi
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                Form Tambah Relasi Barang-Ruangan
            </h3>
            <p class="text-purple-100 text-sm mt-1">Distribusikan barang ke lokasi yang dipilih</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.relasi.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Informasi:</p>
                        <p class="text-sm text-blue-600 mt-1">
                            Relasi ini akan mencatat distribusi barang ke lokasi tertentu.
                            Pastikan kombinasi lokasi dan barang belum ada.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pilih Lokasi -->
            <div class="space-y-2">
                <label for="id_lokasi" class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                    Pilih Lokasi/Ruangan <span class="text-red-500">*</span>
                </label>
                <select name="id_lokasi" 
                        id="id_lokasi" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition @error('id_lokasi') border-red-500 @enderror">
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                            {{ $lokasi->nama_lokasi }}
                        </option>
                    @endforeach
                </select>
                @error('id_lokasi')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Pilih Barang -->
            <div class="space-y-2">
                <label for="id_item" class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-box text-purple-600 mr-2"></i>
                    Pilih Barang <span class="text-red-500">*</span>
                </label>
                <select name="id_item" 
                        id="id_item" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition @error('id_item') border-red-500 @enderror">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id_item }}" {{ old('id_item') == $item->id_item ? 'selected' : '' }}>
                            {{ $item->nama_item }}
                        </option>
                    @endforeach
                </select>
                @error('id_item')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Preview Card -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-lg p-6">
                <p class="text-sm font-bold text-purple-800 mb-3 flex items-center">
                    <i class="fas fa-eye mr-2"></i>
                    Preview Relasi:
                </p>
                <div class="bg-white rounded-lg p-4 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Lokasi:</span>
                        <span id="preview-lokasi" class="text-sm font-bold text-gray-900">-</span>
                    </div>
                    <div class="h-px bg-gray-200"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Barang:</span>
                        <span id="preview-barang" class="text-sm font-bold text-gray-900">-</span>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t-2 border-gray-200">
                <a href="{{ route('admin.relasi.index') }}" 
                   class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Relasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lokasiSelect = document.getElementById('id_lokasi');
    const barangSelect = document.getElementById('id_item');
    const previewLokasi = document.getElementById('preview-lokasi');
    const previewBarang = document.getElementById('preview-barang');

    // Update preview lokasi
    lokasiSelect.addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        previewLokasi.textContent = this.value ? selectedText : '-';
    });

    // Update preview barang
    barangSelect.addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        previewBarang.textContent = this.value ? selectedText : '-';
    });
});
</script>
@endsection
