@extends('layouts.pengguna')

@section('title', 'Buat Pengaduan')
@section('header', 'Buat Pengaduan')
@section('subheader', 'Laporkan masalah sarana dan prasarana di sekolah')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Success/Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-5 rounded-r-2xl shadow-lg animate-fade-in-up">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-black text-red-800 text-lg mb-2">Terdapat Kesalahan</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border-2 border-purple-100 animate-fade-in-up">
        <div class="p-8 bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600">
            <h3 class="text-3xl font-black text-white flex items-center">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                    <i class="fas fa-file-alt text-white text-2xl"></i>
                </div>
                Form Pengaduan Baru
            </h3>
            <p class="text-purple-100 mt-2 ml-18">Isi formulir dengan lengkap dan jelas</p>
        </div>

        <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf

            <!-- Judul Pengaduan -->
            <div class="space-y-3">
                <label for="nama_pengaduan" class="block text-sm font-black text-gray-800 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-heading text-white"></i>
                    </div>
                    Judul Pengaduan <span class="text-red-500 ml-1">*</span>
                </label>
                <input type="text" name="nama_pengaduan" id="nama_pengaduan" required
                    class="w-full rounded-2xl border-2 border-purple-200 shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 px-5 py-4 transition font-medium"
                    value="{{ old('nama_pengaduan') }}"
                    placeholder="Contoh: Kursi rusak di ruang kelas">
                @error('nama_pengaduan')
                    <p class="mt-2 text-sm text-red-600 flex items-center font-semibold">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Lokasi -->
            <div class="space-y-3">
                <label for="id_lokasi" class="block text-sm font-black text-gray-800 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    Lokasi / Ruangan <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="id_lokasi" id="id_lokasi" required
                    class="w-full rounded-2xl border-2 border-purple-200 shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 px-5 py-4 transition font-medium">
                    <option value="">🏢 -- Pilih Lokasi --</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                            {{ $lokasi->nama_lokasi }}
                        </option>
                    @endforeach
                </select>
                @error('id_lokasi')
                    <p class="mt-2 text-sm text-red-600 flex items-center font-semibold">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Item/Barang -->
            <div class="space-y-3">
                <label for="id_item" class="block text-sm font-black text-gray-800 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-box text-white"></i>
                    </div>
                    Item / Barang <span class="text-red-500 ml-1">*</span>
                </label>
                <div class="relative">
                    <select name="id_item" id="id_item" required
                        class="w-full rounded-2xl border-2 border-purple-200 shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 px-5 py-4 pr-12 transition font-medium disabled:bg-gray-100 disabled:cursor-not-allowed"
                        disabled>
                        <option value="">📦 -- Pilih lokasi terlebih dahulu --</option>
                    </select>
                    <div id="loading-spinner" class="hidden absolute inset-y-0 right-0 flex items-center pr-4">
                        <i class="fas fa-spinner fa-spin text-purple-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 bg-purple-50 border-2 border-purple-200 rounded-2xl p-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="other-item-checkbox" class="form-checkbox h-5 w-5 text-purple-600 rounded-lg border-2 border-purple-300">
                        <span class="ml-3 text-sm text-gray-800 font-bold">✨ Barang Lainnya (tidak ada di daftar)</span>
                    </label>
                </div>
                <div id="other-item-input" class="mt-4 hidden">
                    <label class="block text-xs text-purple-600 mb-2 font-bold uppercase tracking-wide">Nama Barang Baru</label>
                    <input type="text" name="nama_barang_baru" id="nama_barang_baru" value="{{ old('nama_barang_baru') }}" 
                           class="w-full rounded-2xl border-2 border-purple-200 shadow-sm px-5 py-3 font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-100" 
                           placeholder="Ketik nama barang baru...">
                </div>
                @error('id_item')
                    <p class="mt-2 text-sm text-red-600 flex items-center font-semibold">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
                <p id="item-info" class="mt-3 text-xs text-gray-500 flex items-center bg-gray-50 rounded-xl px-4 py-2">
                    <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                    Pilih lokasi terlebih dahulu untuk melihat barang yang tersedia
                </p>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-3">
                <label for="deskripsi" class="block text-sm font-black text-gray-800 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-align-left text-white"></i>
                    </div>
                    Deskripsi Masalah <span class="text-red-500 ml-1">*</span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="5" required
                    class="w-full rounded-2xl border-2 border-purple-200 shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 px-5 py-4 transition font-medium"
                    placeholder="Jelaskan detail masalah yang Anda temukan...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-2 text-sm text-red-600 flex items-center font-semibold">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
                <p class="mt-3 text-xs text-gray-500 flex items-center bg-gray-50 rounded-xl px-4 py-2">
                    <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                    Jelaskan dengan detail agar dapat ditangani dengan tepat
                </p>
            </div>

            <!-- Foto -->
            <div class="space-y-3">
                <label for="foto" class="block text-sm font-black text-gray-800 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-camera text-white"></i>
                    </div>
                    Foto Bukti <span class="text-red-500 ml-1">*</span>
                </label>
                
                <div id="drop-area" class="mt-2 relative">
                    <!-- Drag & Drop Area -->
                    <div class="border-4 border-dashed border-purple-300 rounded-3xl bg-gradient-to-br from-purple-50 via-violet-50 to-indigo-50 px-8 py-16 text-center transition-all duration-300 cursor-pointer hover:border-purple-500 hover:shadow-lg" id="dropZone">
                        <input type="file" name="foto" id="foto" class="hidden" accept="image/*" required>
                        
                        <div class="space-y-4">
                            <!-- Icon Animation -->
                            <div class="flex justify-center">
                                <div class="relative w-24 h-24 bg-gradient-to-br from-purple-100 to-violet-100 rounded-full flex items-center justify-center" id="uploadIcon">
                                    <i class="fas fa-cloud-upload-alt text-purple-500 text-4xl"></i>
                                </div>
                            </div>
                            
                            <!-- Text -->
                            <div class="space-y-3">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-gray-800 font-black text-lg">Drag file di sini atau</span>
                                    <label for="foto" class="relative cursor-pointer">
                                        <span class="text-purple-600 font-black hover:text-purple-700 hover:underline inline-block mt-2 text-lg">
                                            <i class="fas fa-folder-open mr-2"></i>Pilih File
                                        </span>
                                    </label>
                                </div>
                                <p class="text-sm text-gray-600 font-semibold">
                                    <i class="fas fa-info-circle mr-1 text-purple-500"></i>
                                    PNG, JPG, JPEG - Maksimal 2MB
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- File Info -->
                    <div id="fileInfo" class="hidden mt-4 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 text-2xl mt-1 mr-4"></i>
                            <div class="flex-1">
                                <p class="text-sm font-black text-green-800">File Terpilih</p>
                                <p id="fileName" class="text-xs text-green-700 mt-1 font-semibold"></p>
                                <p id="fileSize" class="text-xs text-green-700 font-semibold"></p>
                            </div>
                            <button type="button" id="clearFile" class="text-green-600 hover:text-green-700 font-bold transition text-xl">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Image Preview -->
                <div id="image-preview" class="mt-5 hidden">
                    <p class="text-sm font-black text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-eye mr-2 text-purple-500"></i>Preview Gambar
                    </p>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-purple-200 bg-gray-100">
                        <img id="preview-img" class="w-full h-auto max-h-96 object-contain" alt="Preview">
                        <div class="absolute top-0 right-0 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 m-3 rounded-xl text-xs font-bold shadow-lg">
                            Preview
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                <div id="error-message" class="hidden mt-4 p-5 bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-2xl">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 text-2xl mt-1 mr-4"></i>
                        <div>
                            <p class="text-sm font-black text-red-800">Gagal Upload File</p>
                            <p id="error-text" class="text-xs text-red-700 mt-1 font-semibold"></p>
                        </div>
                    </div>
                </div>

                @error('foto')
                    <p class="mt-2 text-sm text-red-600 flex items-center font-semibold">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Submit & Cancel Buttons -->
            <div class="flex flex-col sm:flex-row justify-between gap-4 pt-8 border-t-2 border-purple-100">
                <a href="{{ route('pengaduan.index') }}" 
                   class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-4 px-8 rounded-2xl transition-all shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 hover:from-purple-700 hover:via-violet-700 hover:to-indigo-700 text-white font-bold py-4 px-8 rounded-2xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Ajukan Pengaduan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ============ DRAG & DROP FUNCTIONALITY ============
    const dropZone = document.getElementById('dropZone');
    const fotoInput = document.getElementById('foto');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    const uploadIcon = document.getElementById('uploadIcon');

    // File validation constants
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
    const ALLOWED_TYPES = ['image/png', 'image/jpeg', 'image/jpg'];
    const ALLOWED_EXTENSIONS = ['.png', '.jpg', '.jpeg'];

    // Validate file
    function validateFile(file) {
        if (!ALLOWED_TYPES.includes(file.type)) {
            return {
                valid: false,
                error: `Format file tidak didukung. Hanya ${ALLOWED_EXTENSIONS.join(', ')} yang diizinkan.`
            };
        }
        if (file.size > MAX_FILE_SIZE) {
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            return {
                valid: false,
                error: `Ukuran file terlalu besar (${sizeMB}MB). Maksimal 2MB.`
            };
        }
        return { valid: true };
    }

    // Show error message
    function showError(message) {
        errorText.textContent = message;
        errorMessage.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        fileInfo.classList.add('hidden');
        dropZone.classList.remove('border-green-300', 'bg-green-50');
        dropZone.classList.add('border-red-300');
    }

    // Handle file selection
    function handleFileSelect(file) {
        const validation = validateFile(file);
        if (!validation.valid) {
            showError(validation.error);
            fotoInput.value = '';
            return;
        }

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fotoInput.files = dataTransfer.files;

        errorMessage.classList.add('hidden');
        fileInfo.classList.remove('hidden');
        fileName.textContent = `📄 ${file.name}`;
        fileSize.textContent = `Ukuran: ${(file.size / 1024).toFixed(2)} KB`;

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);

        dropZone.classList.add('border-green-400');
    }

    // Drag & drop events
    dropZone.addEventListener('click', () => fotoInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('border-purple-500', 'shadow-lg', 'scale-105');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-purple-500', 'shadow-lg', 'scale-105');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-purple-500', 'shadow-lg', 'scale-105');
        const files = e.dataTransfer.files;
        if (files.length > 0) handleFileSelect(files[0]);
    });

    fotoInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) handleFileSelect(e.target.files[0]);
    });

    document.getElementById('clearFile').addEventListener('click', (e) => {
        e.preventDefault();
        fotoInput.value = '';
        fileInfo.classList.add('hidden');
        imagePreview.classList.add('hidden');
        errorMessage.classList.add('hidden');
        dropZone.classList.remove('border-green-400');
    });

    // ============ LOKASI & ITEM FUNCTIONALITY ============
    const itemsByLokasi = @json($itemsByLokasi);
    document.getElementById('id_lokasi').addEventListener('change', function() {
        const lokasiId = this.value;
        const itemSelect = document.getElementById('id_item');
        const itemInfo = document.getElementById('item-info');
        const spinner = document.getElementById('loading-spinner');

        itemSelect.innerHTML = '';
        itemSelect.disabled = true;

        if (!lokasiId) {
            itemSelect.innerHTML = '<option value="">📦 -- Pilih lokasi terlebih dahulu --</option>';
            itemInfo.innerHTML = '<i class="fas fa-info-circle mr-2 text-purple-500"></i> Pilih lokasi terlebih dahulu';
            itemInfo.className = 'mt-3 text-xs text-gray-500 flex items-center bg-gray-50 rounded-xl px-4 py-2';
            return;
        }

        spinner.classList.remove('hidden');

        setTimeout(() => {
            const items = itemsByLokasi[lokasiId] || [];

            if (items.length > 0) {
                itemSelect.innerHTML = '<option value="">✅ -- Pilih Item/Barang --</option>';
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_item;
                    option.textContent = '📦 ' + item.nama_item;
                    itemSelect.appendChild(option);
                });
                itemSelect.disabled = false;
                itemInfo.innerHTML = '<i class="fas fa-check-circle mr-2 text-green-500"></i> Ditemukan ' + items.length + ' barang';
                itemInfo.className = 'mt-3 text-xs text-green-600 font-bold flex items-center bg-green-50 rounded-xl px-4 py-2';
            } else {
                itemSelect.innerHTML = '<option value="">❌ -- Tidak ada barang --</option>';
                itemInfo.innerHTML = '<i class="fas fa-exclamation-triangle mr-2 text-amber-500"></i> Belum ada barang di lokasi ini';
                itemInfo.className = 'mt-3 text-xs text-amber-600 font-bold flex items-center bg-amber-50 rounded-xl px-4 py-2';
            }
            spinner.classList.add('hidden');
        }, 300);
    });

    const oldLokasiId = '{{ old('id_lokasi') }}';
    const oldItemId = '{{ old('id_item') }}';
    if (oldLokasiId) {
        document.getElementById('id_lokasi').value = oldLokasiId;
        document.getElementById('id_lokasi').dispatchEvent(new Event('change'));
        setTimeout(() => {
            if (oldItemId) document.getElementById('id_item').value = oldItemId;
        }, 500);
    }

    // Toggle other item input
    const otherCheckbox = document.getElementById('other-item-checkbox');
    const otherInput = document.getElementById('other-item-input');
    const itemSelect = document.getElementById('id_item');
    if (otherCheckbox) {
        otherCheckbox.addEventListener('change', function() {
            if (this.checked) {
                otherInput.classList.remove('hidden');
                itemSelect.disabled = true;
                itemSelect.removeAttribute('required');
            } else {
                otherInput.classList.add('hidden');
                itemSelect.disabled = false;
                itemSelect.setAttribute('required', 'required');
            }
        });
    }
</script>
@endsection