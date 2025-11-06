@extends('layouts.admin')

@section('title', 'Tambah Barang')
@section('header', 'Tambah Barang Baru')
@section('subheader', 'Tambahkan barang/item baru ke sistem')

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
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                Form Tambah Barang
            </h3>
            <p class="text-indigo-100 text-sm mt-1">Isi form di bawah untuk menambahkan barang baru</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.master-barang.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Informasi:</p>
                        <p class="text-sm text-blue-600 mt-1">
                            Setelah barang ditambahkan, Anda dapat mendistribusikannya ke lokasi melalui menu 
                            <span class="font-bold">Relasi Barang-Ruangan</span>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Nama Barang -->
            <div class="space-y-2">
                <label for="nama_item" class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-box text-indigo-600 mr-2"></i>
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_item" 
                       id="nama_item" 
                       value="{{ old('nama_item') }}"
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition @error('nama_item') border-red-500 @enderror"
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
                    <i class="fas fa-align-left text-indigo-600 mr-2"></i>
                    Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                </label>
                <textarea name="deskripsi" 
                          id="deskripsi" 
                          rows="4"
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition @error('deskripsi') border-red-500 @enderror"
                          placeholder="Deskripsi lengkap barang, spesifikasi, catatan, dll...">{{ old('deskripsi') }}</textarea>
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

            <!-- Preview Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-lg p-6">
                <p class="text-sm font-bold text-indigo-800 mb-3 flex items-center">
                    <i class="fas fa-eye mr-2"></i>
                    Preview Data Barang:
                </p>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-start space-x-4">
                        <div class="bg-indigo-100 rounded-lg p-3 flex-shrink-0">
                            <i class="fas fa-cube text-indigo-600 text-2xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-500 mb-1">Nama Barang:</p>
                            <p id="preview-nama" class="text-lg font-bold text-gray-900 mb-3">
                                -
                            </p>
                            <p class="text-sm text-gray-500 mb-1">Deskripsi:</p>
                            <p id="preview-deskripsi" class="text-sm text-gray-700 italic">
                                Tidak ada deskripsi
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t-2 border-gray-200">
                <a href="{{ route('admin.master-barang.index') }}" 
                   class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const namaInput = document.getElementById('nama_item');
    const deskripsiInput = document.getElementById('deskripsi');
    const previewNama = document.getElementById('preview-nama');
    const previewDeskripsi = document.getElementById('preview-deskripsi');

    // Update preview nama barang
    namaInput.addEventListener('input', function() {
        previewNama.textContent = this.value || '-';
    });

    // Update preview deskripsi
    deskripsiInput.addEventListener('input', function() {
        previewDeskripsi.textContent = this.value || 'Tidak ada deskripsi';
    });
});
</script>
@endsection
