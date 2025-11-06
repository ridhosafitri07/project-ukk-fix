@extends('layouts.admin')

@section('title', 'Tambah Lokasi')
@section('header', 'Tambah Lokasi Baru')
@section('subheader', 'Tambahkan lokasi/ruangan baru ke sistem')

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
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                Form Tambah Lokasi
            </h3>
            <p class="text-blue-100 text-sm mt-1">Isi semua field yang diperlukan</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.master-lokasi.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Nama Lokasi -->
            <div class="space-y-2">
                <label for="nama_lokasi" class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                    Nama Lokasi/Ruangan <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_lokasi" 
                       id="nama_lokasi" 
                       value="{{ old('nama_lokasi') }}"
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition @error('nama_lokasi') border-red-500 @enderror"
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
                    <i class="fas fa-tags text-blue-600 mr-2"></i>
                    Kategori Lokasi <span class="text-red-500">*</span>
                </label>
                <select name="kategori" 
                        id="kategori" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition @error('kategori') border-red-500 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="kelas" {{ old('kategori') == 'kelas' ? 'selected' : '' }}>
                        🎓 Kelas
                    </option>
                    <option value="lab" {{ old('kategori') == 'lab' ? 'selected' : '' }}>
                        🧪 Laboratorium
                    </option>
                    <option value="kantor" {{ old('kategori') == 'kantor' ? 'selected' : '' }}>
                        🏢 Kantor
                    </option>
                    <option value="umum" {{ old('kategori') == 'umum' ? 'selected' : '' }}>
                        🚪 Umum (Aula, Perpustakaan, dll)
                    </option>
                    <option value="area_luar" {{ old('kategori') == 'area_luar' ? 'selected' : '' }}>
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

            <!-- Preview Badge -->
            <div id="preview-section" class="hidden bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-eye mr-1"></i>
                    Preview Badge:
                </p>
                <div id="badge-preview"></div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-6 border-t-2 border-gray-200">
                <a href="{{ route('admin.master-lokasi.index') }}" 
                   class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Lokasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview badge based on selected category
document.getElementById('kategori').addEventListener('change', function() {
    const kategori = this.value;
    const previewSection = document.getElementById('preview-section');
    const badgePreview = document.getElementById('badge-preview');
    
    if (kategori) {
        const badges = {
            'kelas': { color: 'blue', icon: 'fa-chalkboard', label: 'Kelas' },
            'lab': { color: 'purple', icon: 'fa-flask', label: 'Laboratorium' },
            'kantor': { color: 'green', icon: 'fa-building', label: 'Kantor' },
            'umum': { color: 'gray', icon: 'fa-door-open', label: 'Umum' },
            'area_luar': { color: 'yellow', icon: 'fa-tree', label: 'Area Luar' }
        };
        
        const badge = badges[kategori];
        badgePreview.innerHTML = `
            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-${badge.color}-100 text-${badge.color}-800">
                <i class="fas ${badge.icon} mr-2"></i>
                ${badge.label}
            </span>
        `;
        previewSection.classList.remove('hidden');
    } else {
        previewSection.classList.add('hidden');
    }
});
</script>
@endsection
