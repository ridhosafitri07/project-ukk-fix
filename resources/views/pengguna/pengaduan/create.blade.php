<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengaduan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <nav class="bg-white shadow-lg border-b-4 border-indigo-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-tools text-indigo-600 text-2xl mr-3"></i>
                    <h1 class="text-2xl font-bold text-indigo-600">SAPRAS</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->nama_pengguna, 0, 1)) }}</span>
                        </div>
                        <span class="text-gray-700 font-medium">{{ auth()->user()->nama_pengguna }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-r from-indigo-600 to-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Buat Pengaduan Baru
                    </h2>
                    <p class="text-indigo-100 mt-2">Laporkan masalah sarana dan prasarana di sekolah</p>
                </div>
                <a href="{{ route('pengguna.dashboard') }}" class="bg-white hover:bg-gray-100 text-indigo-600 font-bold py-3 px-6 rounded-lg shadow-lg transition duration-200 flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-6 m-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-red-800 mb-2">
                                <i class="fas fa-times-circle"></i> Terdapat Kesalahan
                            </h3>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-gradient-to-r from-indigo-500 to-blue-500 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Form Pengaduan
                </h3>
            </div>

                <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    
                    <!-- Judul Pengaduan -->
                    <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200 hover:border-indigo-300 transition">
                        <label for="nama_pengaduan" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-heading text-indigo-600 mr-2"></i>
                            Judul Pengaduan <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="nama_pengaduan" id="nama_pengaduan" required
                            class="block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 px-4 py-3 text-base transition"
                            value="{{ old('nama_pengaduan') }}"
                            placeholder="Contoh: Kursi rusak di ruang kelas">
                        @error('nama_pengaduan')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200 hover:border-indigo-300 transition">
                        <label for="id_lokasi" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                            Lokasi / Ruangan <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="id_lokasi" id="id_lokasi" required
                            class="block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 px-4 py-3 text-base transition">
                            <option value="">🏢 -- Pilih Lokasi / Ruangan --</option>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_lokasi')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-indigo-600 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pilih lokasi terlebih dahulu untuk melihat daftar barang yang tersedia
                        </p>
                    </div>

                    <!-- Item/Barang -->
                    <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200 hover:border-indigo-300 transition">
                        <label for="id_item" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-box text-indigo-600 mr-2"></i>
                            Item / Barang <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="id_item" id="id_item" required
                                class="block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 px-4 py-3 text-base transition disabled:bg-gray-100 disabled:cursor-not-allowed"
                                disabled>
                                <option value="">📦 -- Pilih lokasi terlebih dahulu --</option>
                            </select>
                            <div id="loading-spinner" class="hidden absolute right-3 top-3">
                                <i class="fas fa-spinner fa-spin text-indigo-600 text-xl"></i>
                            </div>
                        </div>
                        @error('id_item')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p id="item-help-text" class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Daftar barang akan muncul setelah memilih lokasi
                        </p>
                    </div>                    <!-- Deskripsi -->
                    <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200 hover:border-indigo-300 transition">
                        <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-align-left text-indigo-600 mr-2"></i>
                            Deskripsi Masalah <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="5" required
                            class="block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 px-4 py-3 text-base transition"
                            placeholder="Jelaskan detail masalah yang Anda temukan, seperti kondisi kerusakan, dampak yang ditimbulkan, dan informasi penting lainnya...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-pen mr-1"></i>
                            Jelaskan dengan detail agar dapat ditangani dengan tepat
                        </p>
                    </div>

                    <!-- Foto -->
                    <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200 hover:border-indigo-300 transition">
                        <label for="foto" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-camera text-indigo-600 mr-2"></i>
                            Foto Bukti <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-400 transition">
                            <div class="space-y-2 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-5xl mb-3"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="foto" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none px-3 py-2">
                                        <span><i class="fas fa-file-upload mr-1"></i>Pilih File</span>
                                        <input type="file" name="foto" id="foto" required class="sr-only" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                    <p class="pl-1 pt-2">atau drag & drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                            </div>
                        </div>
                        <div id="image-preview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                            <img id="preview-img" class="rounded-lg shadow-md max-h-64 mx-auto" alt="Preview">
                        </div>
                        @error('foto')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-between items-center pt-6 border-t-2 border-gray-200">
                        <a href="{{ route('pengguna.dashboard') }}" class="text-gray-600 hover:text-gray-800 font-medium flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition duration-200 flex items-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Ajukan Pengaduan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Image preview function
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Handle lokasi change untuk load items
        document.getElementById('id_lokasi').addEventListener('change', function() {
            const lokasiId = this.value;
            const itemSelect = document.getElementById('id_item');
            const spinner = document.getElementById('loading-spinner');
            
            // Show loading spinner
            spinner.classList.remove('hidden');
            
            // Reset dan disable item select
            itemSelect.innerHTML = '<option value="">⏳ Memuat data...</option>';
            itemSelect.disabled = true;
            
            if (!lokasiId) {
                itemSelect.innerHTML = '<option value="">📦 -- Pilih lokasi terlebih dahulu --</option>';
                spinner.classList.add('hidden');
                return;
            }
            
            // URL yang benar sesuai dengan route
            const url = `{{ route('api.lokasi.items', ['id_lokasi' => ':id']) }}`.replace(':id', lokasiId);
            
            console.log('🔍 Fetching URL:', url);
            console.log('📍 Lokasi ID:', lokasiId);
            
            // Fetch items berdasarkan lokasi
            fetch(url)
                .then(response => {
                    console.log('📡 Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Data received:', data);
                    spinner.classList.add('hidden');
                    
                    const helpText = document.getElementById('item-help-text');
                    
                    if (data.success && data.items && data.items.length > 0) {
                        // Ada items di lokasi ini
                        itemSelect.innerHTML = '<option value="">✅ -- Pilih Item/Barang --</option>';
                        data.items.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id_item;
                            option.textContent = `📦 ${item.nama_item}`;
                            if (item.deskripsi) {
                                option.title = item.deskripsi;
                            }
                            itemSelect.appendChild(option);
                        });
                        itemSelect.disabled = false;
                        itemSelect.classList.remove('border-red-300');
                        itemSelect.classList.add('border-green-300');
                        
                        // Success message
                        helpText.innerHTML = `<i class="fas fa-check-circle mr-1 text-green-600"></i><span class="text-green-600 font-medium">Ditemukan ${data.items.length} barang di lokasi ini</span>`;
                        console.log('✅ Items loaded successfully');
                        
                    } else {
                        // Tidak ada items di lokasi ini
                        itemSelect.innerHTML = '<option value="">-- Tidak ada item di lokasi ini --</option>';
                        itemSelect.disabled = true;
                        itemSelect.classList.remove('border-green-300');
                        itemSelect.classList.add('border-orange-300');
                        
                        // Info message - bukan error
                        helpText.innerHTML = `
                            <div class="flex items-start space-x-2 bg-orange-50 p-3 rounded-lg border border-orange-200">
                                <i class="fas fa-info-circle text-orange-600 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-orange-700 font-medium text-sm">Belum ada barang yang terdaftar di lokasi ini</p>
                                    <p class="text-orange-600 text-xs mt-1">Silakan pilih lokasi lain atau hubungi admin untuk menambahkan barang</p>
                                </div>
                            </div>
                        `;
                        console.log('ℹ️ No items found for this location');
                    }
                })
                .catch(error => {
                    console.error('❌ Error details:', error);
                    spinner.classList.add('hidden');
                    itemSelect.innerHTML = '<option value="">⚠️ -- Gagal memuat data --</option>';
                    itemSelect.disabled = true;
                    itemSelect.classList.add('border-red-300');
                    
                    const helpText = document.getElementById('item-help-text');
                    
                    // Error message - masalah koneksi/server
                    helpText.innerHTML = `
                        <div class="flex items-start space-x-2 bg-red-50 p-3 rounded-lg border border-red-200">
                            <i class="fas fa-exclamation-triangle text-red-600 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-red-700 font-medium text-sm">Gagal memuat data barang</p>
                                <p class="text-red-600 text-xs mt-1">Terjadi kesalahan koneksi. Silakan refresh halaman atau coba lagi.</p>
                                <p class="text-red-500 text-xs mt-1 font-mono">${error.message}</p>
                            </div>
                        </div>
                    `;
                });
        });
    </script>
</body>
</html>