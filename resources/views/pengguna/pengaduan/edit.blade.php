<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengaduan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold">SAPRAS</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700">{{ auth()->user()->nama_pengguna }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">Edit Pengaduan</h2>
                <a href="{{ route('pengaduan.show', $pengaduan) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow rounded-lg p-6">
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                                <ul class="mt-2 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('pengaduan.update', $pengaduan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nama_pengaduan" class="block text-sm font-medium text-gray-700">Judul Pengaduan</label>
                        <input type="text" name="nama_pengaduan" id="nama_pengaduan" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            value="{{ old('nama_pengaduan', $pengaduan->nama_pengaduan) }}"
                            placeholder="Masukkan judul pengaduan">
                        @error('nama_pengaduan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <select name="id_lokasi" id="id_lokasi" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}" 
                                    {{ (old('id_lokasi', $currentLokasi->id_lokasi ?? null) == $lokasi->id_lokasi) ? 'selected' : '' }}>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_item" class="block text-sm font-medium text-gray-700">Item/Barang</label>
                        <select name="id_item" id="id_item" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih lokasi terlebih dahulu --</option>
                            @if($pengaduan->id_item && $pengaduan->item)
                                <option value="{{ $pengaduan->item->id_item }}" selected>{{ $pengaduan->item->nama_item }}</option>
                            @endif
                        </select>
                        @error('id_item')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Jelaskan detail pengaduan Anda">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                        @if($pengaduan->foto)
                            <div class="mt-2 mb-4">
                                <p class="text-sm text-gray-500 mb-2">Foto saat ini:</p>
                                <img src="{{ asset('storage/' . $pengaduan->foto) }}" alt="Foto Pengaduan" class="max-w-lg rounded-lg shadow-md">
                            </div>
                        @endif
                        <input type="file" name="foto" id="foto"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG (Max. 2MB). Biarkan kosong jika tidak ingin mengubah foto.</p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Simpan Perubahan
                        </button>

                        <form action="{{ route('pengaduan.destroy', $pengaduan) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                Hapus Pengaduan
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Handle lokasi change untuk load items
        document.getElementById('id_lokasi').addEventListener('change', function() {
            const lokasiId = this.value;
            const itemSelect = document.getElementById('id_item');
            
            // Reset dan disable item select
            itemSelect.innerHTML = '<option value="">⏳ Memuat data...</option>';
            itemSelect.disabled = true;
            
            if (!lokasiId) {
                itemSelect.innerHTML = '<option value="">-- Pilih lokasi terlebih dahulu --</option>';
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
                    
                    if (data.success && data.items && data.items.length > 0) {
                        itemSelect.innerHTML = '<option value="">-- Pilih Item/Barang --</option>';
                        data.items.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id_item;
                            option.textContent = `📦 ${item.nama_item}`;
                            // Restore selection if editing
                            @if($pengaduan->id_item)
                            if (item.id_item == {{ $pengaduan->id_item }}) {
                                option.selected = true;
                            }
                            @endif
                            itemSelect.appendChild(option);
                        });
                        itemSelect.disabled = false;
                        itemSelect.classList.remove('border-red-300', 'border-orange-300');
                        itemSelect.classList.add('border-green-300');
                        console.log('✅ Items loaded:', data.items.length);
                    } else {
                        itemSelect.innerHTML = '<option value="">ℹ️ Tidak ada item di lokasi ini</option>';
                        itemSelect.disabled = true;
                        itemSelect.classList.add('border-orange-300');
                        console.log('ℹ️ No items found');
                    }
                })
                .catch(error => {
                    console.error('❌ Error details:', error);
                    itemSelect.innerHTML = '<option value="">⚠️ Gagal memuat data - Silakan coba lagi</option>';
                    itemSelect.disabled = true;
                    itemSelect.classList.add('border-red-300');
                });
        });

        // Trigger change event on page load if lokasi is already selected
        window.addEventListener('DOMContentLoaded', function() {
            const lokasiSelect = document.getElementById('id_lokasi');
            if (lokasiSelect.value) {
                lokasiSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>