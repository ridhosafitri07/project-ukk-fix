@extends('layouts.petugas')

@section('title', 'Ajukan Permintaan Barang')
@section('header', 'Ajukan Permintaan Barang Baru')
@section('subheader', 'Ajukan permintaan barang karena kerusakan')

@section('content')
<div class="mb-4 md:mb-6">
    <a href="{{ route('petugas.pengaduan.show', $pengaduan) }}" 
       class="inline-flex items-center px-3 py-2 md:px-4 md:py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm md:text-base text-gray-700 hover:bg-gray-50 transition-all">
        <i class="fas fa-arrow-left mr-2 text-sm"></i>
        <span class="hidden sm:inline">Kembali ke Pengaduan</span>
        <span class="sm:hidden">Kembali</span>
    </a>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-0">
    <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
        <!-- Header -->
        <div class="p-4 md:p-6 bg-gradient-to-r from-green-500 to-emerald-600">
            <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                <i class="fas fa-box mr-2 text-sm md:text-base"></i>
                Form Permintaan Barang Baru
            </h3>
            <p class="text-green-100 text-xs md:text-sm mt-1">Untuk pengaduan: {{ $pengaduan->nama_pengaduan }}</p>
        </div>

        <form action="{{ route('petugas.item-request.store', $pengaduan) }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-6">
            @csrf

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="mb-4 md:mb-6 bg-red-50 border-l-4 border-red-400 p-3 md:p-4 rounded">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-400 mr-2 mt-0.5 text-sm flex-shrink-0"></i>
                    <div class="flex-1">
                        <h3 class="text-xs md:text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                        <ul class="mt-2 text-xs md:text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="space-y-4 md:space-y-5">
                <!-- Nama Barang -->
                <div>
                    <label for="nama_barang_baru" class="block text-xs md:text-sm font-bold text-gray-700 mb-2">
                        Nama Barang yang Diminta <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_barang_baru" 
                           id="nama_barang_baru" 
                           value="{{ old('nama_barang_baru') }}"
                           class="w-full px-3 py-2 md:px-4 md:py-3 text-sm md:text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="Contoh: Kursi Kantor, Proyektor, AC"
                           required>
                </div>

                <!-- Lokasi (Read Only) -->
                <div>
                    <label for="lokasi_barang_baru" class="block text-xs md:text-sm font-bold text-gray-700 mb-2">
                        Lokasi Penempatan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="lokasi_barang_baru" 
                               id="lokasi_barang_baru" 
                               value="{{ old('lokasi_barang_baru', $pengaduan->lokasi) }}"
                               class="w-full px-3 py-2 md:px-4 md:py-3 text-sm md:text-base bg-gray-100 border-2 border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                               readonly
                               required>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            <i class="fas fa-lock text-gray-400 text-xs md:text-sm"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Lokasi otomatis diambil dari lokasi pengaduan
                    </p>
                </div>

                <!-- Alasan Permintaan -->
                <div>
                    <label for="alasan_permintaan" class="block text-xs md:text-sm font-bold text-gray-700 mb-2">
                        Alasan Permintaan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_permintaan" 
                              id="alasan_permintaan" 
                              rows="4"
                              class="w-full px-3 py-2 md:px-4 md:py-3 text-sm md:text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none"
                              placeholder="Jelaskan mengapa barang ini dibutuhkan (kondisi kerusakan, urgensi, dll)"
                              required>{{ old('alasan_permintaan') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1 flex items-start">
                        <i class="fas fa-lightbulb mr-1 mt-0.5 flex-shrink-0"></i>
                        <span>Jelaskan detail kondisi kerusakan dan urgensi kebutuhan barang</span>
                    </p>
                </div>

                <!-- Foto Kerusakan -->
                <div>
                    <label for="foto_kerusakan" class="block text-xs md:text-sm font-bold text-gray-700 mb-2">
                        Foto Bukti Kerusakan <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-4 py-6 md:px-6 md:pt-5 md:pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors bg-gray-50">
                        <div class="space-y-2 text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl md:text-4xl text-gray-400"></i>
                            <div class="flex flex-col sm:flex-row text-xs md:text-sm text-gray-600 items-center justify-center">
                                <label for="foto_kerusakan" class="relative cursor-pointer bg-white px-2 py-1 rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                    <span>Upload foto</span>
                                    <input id="foto_kerusakan" 
                                           name="foto_kerusakan" 
                                           type="file" 
                                           class="sr-only" 
                                           accept="image/*"
                                           required
                                           onchange="previewImage(this)">
                                </label>
                                <p class="sm:pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG max 2MB</p>
                        </div>
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <div class="relative inline-block">
                            <img src="" alt="Preview" class="max-w-full md:max-w-sm rounded-lg shadow-md mx-auto">
                            <button type="button" 
                                    onclick="removeImage()" 
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="p-3 md:p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2 md:mr-3 text-sm flex-shrink-0"></i>
                        <div class="flex-1">
                            <h4 class="text-xs md:text-sm font-bold text-blue-900">Informasi Penting</h4>
                            <ul class="text-xs text-blue-800 mt-2 space-y-1">
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Permintaan akan ditinjau oleh admin</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Pastikan foto kerusakan jelas dan detail</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Jelaskan alasan dengan lengkap untuk mempercepat approval</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Status permintaan bisa dicek di halaman detail pengaduan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-2 md:gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('petugas.pengaduan.show', $pengaduan) }}"
                       class="w-full sm:w-auto px-4 py-2 md:px-6 md:py-3 bg-gray-100 text-gray-700 text-sm md:text-base rounded-lg font-semibold hover:bg-gray-200 transition-colors text-center">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 md:px-6 md:py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm md:text-base rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2 text-sm"></i>
                        Ajukan Permintaan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            input.value = '';
            return;
        }
        
        // Validasi tipe file
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar!');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    const input = document.getElementById('foto_kerusakan');
    const preview = document.getElementById('imagePreview');
    
    input.value = '';
    preview.classList.add('hidden');
}
</script>
@endsection