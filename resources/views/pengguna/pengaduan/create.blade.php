@extends('layouts.pengguna')

@section('title', 'Buat Pengaduan')
@section('header', 'Buat Pengaduan')
@section('subheader', 'Laporkan masalah sarana dan prasarana di sekolah')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Success/Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm animate-fade-in-up">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5 mr-3"></i>
                <div>
                    <h3 class="font-bold text-red-800">Terdapat Kesalahan</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card animate-fade-in-up">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
                Form Pengaduan Baru
            </h3>
        </div>

        <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf

            <!-- Judul Pengaduan -->
            <div class="space-y-2">
                <label for="nama_pengaduan" class="block text-sm font-bold text-gray-700 flex items-center">
                    <i class="fas fa-heading text-blue-500 mr-2"></i>
                    Judul Pengaduan <span class="text-red-500 ml-1">*</span>
                </label>
                <input type="text" name="nama_pengaduan" id="nama_pengaduan" required
                    class="w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-3 transition"
                    value="{{ old('nama_pengaduan') }}"
                    placeholder="Contoh: Kursi rusak di ruang kelas">
                @error('nama_pengaduan')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Lokasi -->
            <div class="space-y-2">
                <label for="id_lokasi" class="block text-sm font-bold text-gray-700 flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                    Lokasi / Ruangan <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="id_lokasi" id="id_lokasi" required
                    class="w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-3 transition">
                    <option value="">🏢 -- Pilih Lokasi --</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                            {{ $lokasi->nama_lokasi }}
                        </option>
                    @endforeach
                </select>
                @error('id_lokasi')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Item/Barang -->
            <div class="space-y-2">
                <label for="id_item" class="block text-sm font-bold text-gray-700 flex items-center">
                    <i class="fas fa-box text-blue-500 mr-2"></i>
                    Item / Barang <span class="text-red-500 ml-1">*</span>
                </label>
                <div class="relative">
                    <select name="id_item" id="id_item" required
                        class="w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-3 pr-10 transition disabled:bg-gray-100 disabled:cursor-not-allowed"
                        disabled>
                        <option value="">📦 -- Pilih lokasi terlebih dahulu --</option>
                    </select>
                    <div id="loading-spinner" class="hidden absolute inset-y-0 right-0 flex items-center pr-3">
                        <i class="fas fa-spinner fa-spin text-blue-500"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="other-item-checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                        <span class="ml-2 text-sm text-gray-700 font-medium">Barang Lainnya (tidak ada di daftar)</span>
                    </label>
                </div>
                <div id="other-item-input" class="mt-3 hidden">
                    <label class="block text-xs text-gray-500 mb-1 font-medium">Nama Barang Baru</label>
                    <input type="text" name="nama_barang_baru" id="nama_barang_baru" value="{{ old('nama_barang_baru') }}" class="w-full rounded-xl border-2 border-gray-200 shadow-sm px-4 py-2" placeholder="Ketik nama barang baru...">
                </div>
                @error('id_item')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p id="item-info" class="mt-2 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Pilih lokasi terlebih dahulu untuk melihat barang yang tersedia
                </p>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-2">
                <label for="deskripsi" class="block text-sm font-bold text-gray-700 flex items-center">
                    <i class="fas fa-align-left text-blue-500 mr-2"></i>
                    Deskripsi Masalah <span class="text-red-500 ml-1">*</span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="5" required
                    class="w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-3 transition"
                    placeholder="Jelaskan detail masalah yang Anda temukan...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-pen mr-1"></i>
                    Jelaskan dengan detail agar dapat ditangani dengan tepat
                </p>
            </div>

            <!-- Foto -->
            <div class="space-y-2">
                <label for="foto" class="block text-sm font-bold text-gray-700 flex items-center">
                    <i class="fas fa-camera text-blue-500 mr-2"></i>
                    Foto Bukti <span class="text-red-500 ml-1">*</span>
                </label>
                
                <div id="drop-area" class="mt-1 relative">
                    <!-- Drag & Drop Area -->
                    <div class="border-3 border-dashed border-blue-300 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 px-6 py-12 text-center transition-all duration-200 cursor-pointer hover:border-blue-500 hover:from-blue-100 hover:to-indigo-100 hover:shadow-md" id="dropZone">
                        <input type="file" name="foto" id="foto" class="hidden" accept="image/*" required>
                        
                        <div class="space-y-4">
                            <!-- Icon Animation -->
                            <div class="flex justify-center">
                                <div class="relative w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center" id="uploadIcon">
                                    <i class="fas fa-cloud-upload-alt text-blue-500 text-3xl"></i>
                                </div>
                            </div>
                            
                            <!-- Text -->
                            <div class="space-y-2">
                                <div class="flex flex-col items-center justify-center text-sm">
                                    <span class="text-gray-700 font-semibold">Drag file di sini atau</span>
                                    <label for="foto" class="relative cursor-pointer">
                                        <span class="text-blue-600 font-bold hover:text-blue-700 hover:underline inline-block mt-1">
                                            <i class="fas fa-folder-open mr-1"></i>Pilih File
                                        </span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    PNG, JPG, JPEG - Maksimal 2MB
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- File Info -->
                    <div id="fileInfo" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 text-xl mt-0.5 mr-3"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-green-800">File Terpilih</p>
                                <p id="fileName" class="text-xs text-green-700 mt-1"></p>
                                <p id="fileSize" class="text-xs text-green-700"></p>
                            </div>
                            <button type="button" id="clearFile" class="text-green-600 hover:text-green-700 font-bold transition">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Image Preview -->
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-eye mr-2 text-blue-500"></i>Preview Gambar
                    </p>
                    <div class="relative rounded-xl overflow-hidden shadow-lg border-2 border-gray-200 bg-gray-100">
                        <img id="preview-img" class="w-full h-auto max-h-96 object-contain" alt="Preview">
                        <div class="absolute top-0 right-0 bg-blue-600 text-white px-3 py-1 m-2 rounded-lg text-xs font-semibold">
                            Preview
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                <div id="error-message" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Gagal Upload File</p>
                            <p id="error-text" class="text-xs text-red-700 mt-1"></p>
                        </div>
                    </div>
                </div>

                @error('foto')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Submit & Cancel Buttons -->
            <div class="flex flex-col sm:flex-row justify-between gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('pengaduan.index') }}" class="btn-primary bg-gray-500 hover:bg-gray-600 text-white justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="btn-primary bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 justify-center">
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
        // Check file type
        if (!ALLOWED_TYPES.includes(file.type)) {
            return {
                valid: false,
                error: `Format file tidak didukung. Hanya ${ALLOWED_EXTENSIONS.join(', ')} yang diizinkan.`
            };
        }

        // Check file size
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
        dropZone.classList.add('border-red-300', 'bg-red-50');
        uploadIcon.classList.remove('bg-green-100');
        uploadIcon.classList.add('bg-red-100');
        setTimeout(() => {
            uploadIcon.classList.remove('bg-red-100');
            uploadIcon.classList.add('bg-blue-100');
            dropZone.classList.remove('border-red-300', 'bg-red-50');
            dropZone.classList.add('border-blue-300', 'bg-blue-50');
        }, 2000);
    }

    // Handle file selection
    function handleFileSelect(file) {
        const validation = validateFile(file);

        if (!validation.valid) {
            showError(validation.error);
            fotoInput.value = '';
            return;
        }

        // Set the file to input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fotoInput.files = dataTransfer.files;

        // Update UI
        errorMessage.classList.add('hidden');
        fileInfo.classList.remove('hidden');
        fileName.textContent = `📄 ${file.name}`;
        fileSize.textContent = `Ukuran: ${(file.size / 1024).toFixed(2)} KB`;

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);

        // Update drop zone color
        dropZone.classList.add('border-green-300', 'bg-green-50');
        uploadIcon.classList.remove('bg-blue-100');
        uploadIcon.classList.add('bg-green-100');
    }

    // Drag & drop events
    dropZone.addEventListener('click', () => fotoInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('border-blue-500', 'bg-blue-100', 'shadow-lg', 'scale-105');
        uploadIcon.style.animation = 'bounce 0.6s infinite';
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-blue-500', 'bg-blue-100', 'shadow-lg', 'scale-105');
        uploadIcon.style.animation = 'none';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-blue-500', 'bg-blue-100', 'shadow-lg', 'scale-105');
        uploadIcon.style.animation = 'none';

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });

    // File input change handler
    fotoInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    // Clear file button
    document.getElementById('clearFile').addEventListener('click', (e) => {
        e.preventDefault();
        fotoInput.value = '';
        fileInfo.classList.add('hidden');
        imagePreview.classList.add('hidden');
        errorMessage.classList.add('hidden');
        dropZone.classList.remove('border-green-300', 'bg-green-50');
        dropZone.classList.add('border-blue-300', 'bg-blue-50');
        uploadIcon.classList.remove('bg-green-100');
        uploadIcon.classList.add('bg-blue-100');
    });

    // Add bounce animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    `;
    document.head.appendChild(style);

    // ============ LOKASI & ITEM FUNCTIONALITY ============
    // Data items per lokasi dari server
    const itemsByLokasi = @json($itemsByLokasi);
    document.getElementById('id_lokasi').addEventListener('change', function() {
        const lokasiId = this.value;
        const itemSelect = document.getElementById('id_item');
        const itemInfo = document.getElementById('item-info');
        const spinner = document.getElementById('loading-spinner');

        // Reset item select
        itemSelect.innerHTML = '';
        itemSelect.disabled = true;

        if (!lokasiId) {
            itemSelect.innerHTML = '<option value="">📦 -- Pilih lokasi terlebih dahulu --</option>';
            itemInfo.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Pilih lokasi terlebih dahulu untuk melihat barang yang tersedia';
            itemInfo.className = 'mt-2 text-xs text-gray-500 flex items-center';
            return;
        }

        // Show spinner
        spinner.classList.remove('hidden');

        // Simulasi loading
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
                itemInfo.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Ditemukan ' + items.length + ' barang di lokasi ini';
                itemInfo.className = 'mt-2 text-xs text-green-600 font-medium flex items-center';
            } else {
                itemSelect.innerHTML = '<option value="">❌ -- Tidak ada barang di lokasi ini --</option>';
                itemInfo.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Belum ada barang yang terdaftar di lokasi ini.';
                itemInfo.className = 'mt-2 text-xs text-amber-600 font-medium flex items-center';
            }

            // Hide spinner
            spinner.classList.add('hidden');
        }, 300);
    });

    // Check if ada old value (dari validation error)
    const oldLokasiId = '{{ old('id_lokasi') }}';
    const oldItemId = '{{ old('id_item') }}';

    if (oldLokasiId) {
        document.getElementById('id_lokasi').value = oldLokasiId;
        document.getElementById('id_lokasi').dispatchEvent(new Event('change'));
        setTimeout(() => {
            if (oldItemId) {
                document.getElementById('id_item').value = oldItemId;
            }
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