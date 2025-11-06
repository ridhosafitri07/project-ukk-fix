@extends('layouts.pengguna')

@section('title', 'Edit Pengaduan')
@section('header', 'Edit Pengaduan')
@section('subheader', 'Perbarui informasi pengaduan Anda')

@section('content')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .input-group {
        transition: all 0.3s ease;
    }
    
    .input-group:hover {
        transform: translateY(-2px);
    }
</style>

<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 animate-fade-in-up">
        @if ($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-6 m-6 rounded-xl">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-red-800 mb-2">Terdapat Kesalahan</h3>
                        <ul class="space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-circle text-xs mt-1"></i>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
            <h3 class="text-2xl font-bold text-white flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-pen-to-square"></i>
                </div>
                <span>Form Edit Pengaduan</span>
            </h3>
        </div>

        <form action="{{ route('pengaduan.update', $pengaduan) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Judul Pengaduan -->
            <div class="input-group">
                <label for="nama_pengaduan" class="block text-sm font-bold text-gray-700 mb-3 flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heading text-white text-sm"></i>
                    </div>
                    <span>Judul Pengaduan <span class="text-red-500">*</span></span>
                </label>
                <input type="text" name="nama_pengaduan" id="nama_pengaduan" required
                    class="block w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 px-4 py-4 text-base transition"
                    value="{{ old('nama_pengaduan', $pengaduan->nama_pengaduan) }}"
                    placeholder="Masukkan judul pengaduan">
                @error('nama_pengaduan')
                    <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Lokasi -->
            <div class="input-group">
                <label for="id_lokasi" class="block text-sm font-bold text-gray-700 mb-3 flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white text-sm"></i>
                    </div>
                    <span>Lokasi / Ruangan <span class="text-red-500">*</span></span>
                </label>
                <div class="relative">
                    <select name="id_lokasi" id="id_lokasi" required
                        class="block w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 px-4 py-4 text-base transition appearance-none">
                        <option value="">🏢 Pilih Lokasi / Ruangan</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id_lokasi }}" 
                                {{ (old('id_lokasi', $currentLokasi->id_lokasi ?? null) == $lokasi->id_lokasi) ? 'selected' : '' }}>
                                {{ $lokasi->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                @error('id_lokasi')
                    <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Item/Barang -->
            <div class="input-group">
                <label for="id_item" class="block text-sm font-bold text-gray-700 mb-3 flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-white text-sm"></i>
                    </div>
                    <span>Item / Barang <span class="text-red-500">*</span></span>
                </label>
                <div class="relative">
                    <select name="id_item" id="id_item" required
                        class="block w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 px-4 py-4 text-base transition appearance-none">
                        <option value="">📦 Pilih lokasi terlebih dahulu</option>
                        @if($pengaduan->id_item && $pengaduan->item)
                            <option value="{{ $pengaduan->item->id_item }}" selected>{{ $pengaduan->item->nama_item }}</option>
                        @endif
                    </select>
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                @error('id_item')
                    <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="input-group">
                <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-3 flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-align-left text-white text-sm"></i>
                    </div>
                    <span>Deskripsi Masalah <span class="text-red-500">*</span></span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="5" required
                    class="block w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 px-4 py-4 text-base transition"
                    placeholder="Jelaskan detail pengaduan Anda">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Foto -->
            <div class="input-group">
                <label for="foto" class="block text-sm font-bold text-gray-700 mb-3 flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-camera text-white text-sm"></i>
                    </div>
                    <span>Foto Bukti</span>
                </label>
                
                @if($pengaduan->foto)
                    <div class="mb-4 bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border-2 border-gray-200">
                        <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center space-x-2">
                            <i class="fas fa-image text-blue-600"></i>
                            <span>Foto saat ini:</span>
                        </p>
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                                 alt="Foto Pengaduan" 
                                 class="max-w-lg w-full rounded-2xl shadow-xl border-4 border-white">
                        </div>
                    </div>
                @endif
                
                <div class="mt-2 flex justify-center px-6 pt-8 pb-8 border-3 border-dashed border-gray-300 rounded-2xl bg-gray-50 hover:border-blue-400 transition">
                    <div class="space-y-3 text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-cloud-upload-alt text-blue-600 text-3xl"></i>
                        </div>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="foto" class="relative cursor-pointer bg-white rounded-xl font-semibold text-blue-600 hover:text-blue-500 px-4 py-2 shadow-md hover:shadow-lg transition">
                                <span><i class="fas fa-file-upload mr-2"></i>Pilih File Baru</span>
                                <input type="file" name="foto" id="foto" class="sr-only" accept="image/*">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG (Max. 2MB)</p>
                        <p class="text-xs text-blue-600 font-medium">Biarkan kosong jika tidak ingin mengubah foto</p>
                    </div>
                </div>
                @error('foto')
                    <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t-2 border-gray-200 space-y-4 sm:space-y-0">
                <a href="{{ route('pengaduan.show', $pengaduan) }}" 
                   class="text-gray-600 hover:text-gray-800 font-semibold flex items-center space-x-2 px-6 py-3 rounded-xl hover:bg-gray-100 transition">
                    <i class="fas fa-arrow-left"></i>
                    <span>Batal</span>
                </a>
                
                <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-8 rounded-2xl shadow-2xl transform hover:scale-105 transition duration-300 flex items-center space-x-3">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

            <!-- Delete Button -->
            <div class="pt-6 border-t-2 border-red-200">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 p-6 rounded-xl border-2 border-red-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-red-800 mb-2 flex items-center space-x-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Zona Bahaya</span>
                            </h4>
                            <p class="text-sm text-red-700">Menghapus pengaduan ini akan menghapus semua data terkait secara permanen.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Delete Form (Outside main form) -->
        <div class="px-8 pb-8">
            <form action="{{ route('pengaduan.destroy', $pengaduan) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-bold py-4 px-8 rounded-2xl shadow-xl transition flex items-center justify-center space-x-3"
                        onclick="return confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus pengaduan ini?\nTindakan ini tidak dapat dibatalkan!')">
                    <i class="fas fa-trash"></i>
                    <span>Hapus Pengaduan Ini</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('id_lokasi').addEventListener('change', function() {
        const lokasiId = this.value;
        const itemSelect = document.getElementById('id_item');
        
        itemSelect.innerHTML = '<option value="">⏳ Memuat data...</option>';
        itemSelect.disabled = true;
        
        if (!lokasiId) {
            itemSelect.innerHTML = '<option value="">📦 Pilih lokasi terlebih dahulu</option>';
            return;
        }
        
        const url = `{{ route('api.lokasi.items', ['id_lokasi' => ':id']) }}`.replace(':id', lokasiId);
        
        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.success && data.items && data.items.length > 0) {
                    itemSelect.innerHTML = '<option value="">✅ Pilih Item/Barang</option>';
                    data.items.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id_item;
                        option.textContent = `📦 ${item.nama_item}`;
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
                } else {
                    itemSelect.innerHTML = '<option value="">ℹ️ Tidak ada item di lokasi ini</option>';
                    itemSelect.disabled = true;
                    itemSelect.classList.add('border-orange-300');
                }
            })
            .catch(error => {
                itemSelect.innerHTML = '<option value="">⚠️ Gagal memuat data</option>';
                itemSelect.disabled = true;
                itemSelect.classList.add('border-red-300');
            });
    });

    window.addEventListener('DOMContentLoaded', function() {
        const lokasiSelect = document.getElementById('id_lokasi');
        if (lokasiSelect.value) {
            lokasiSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection