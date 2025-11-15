@extends('layouts.pengguna')

@section('title', 'Edit Pengaduan')
@section('header', 'Edit Pengaduan')
@section('subheader', 'Perbarui informasi pengaduan Anda')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border-2 border-purple-100 animate-fade-in-up">
        @if ($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-6 m-6 rounded-2xl">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-red-800 mb-2">Terdapat Kesalahan</h3>
                        <ul class="space-y-1 text-sm text-red-700 font-semibold">
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

        <div class="bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 px-8 py-6">
            <h3 class="text-3xl font-black text-white flex items-center space-x-3">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-pen-to-square text-2xl"></i>
                </div>
                <span>Form Edit Pengaduan</span>
            </h3>
        </div>

        <form action="{{ route('pengaduan.update', $pengaduan) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Judul Pengaduan -->
            <div class="space-y-3">
                <label for="nama_pengaduan" class="block text-sm font-black text-gray-800 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-heading text-white"></i>
                    </div>
                    <span>Judul Pengaduan <span class="text-red-500">*</span></span>
                </label>
                <input type="text" name="nama_pengaduan" id="nama_pengaduan" required
                    class="block w-full rounded-2xl border-2 border-purple-200 shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 px-5 py-4 text-base transition font-medium"
                    value="{{ old('nama_pengaduan', $pengaduan->nama_pengaduan) }}"
                    placeholder="Masukkan judul pengaduan">
            </div>

            <!-- Lokasi -->
            <div class="space-y-3">
                <label for="id_lokasi" class="block text-sm font-black text-gray-800 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <span>Lokasi / Ruangan <span class="text-red-500">*</span></span>
                </label>
                <select name="id_lokasi" id="id_lokasi" required
                    class="block w-full rounded-2xl border-2 border-purple-200 shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 px-5 py-4 text-base transition font-medium">
                    <option value="">🏢 Pilih Lokasi / Ruangan</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->id_lokasi }}" 
                            {{ (old('id_lokasi', $currentLokasi->id_lokasi ?? null) == $lokasi->id_lokasi) ? 'selected' : '' }}>
                            {{ $lokasi->nama_lokasi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Item/Barang -->
            <div class="space-y-3">
                <label for="id_item" class="block text-sm font-black text-gray-800 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-box text-white"></i>
                    </div>
                    <span>Item / Barang <span class="text-red-500">*</span></span>
                </label>
                <select name="id_item" id="id_item" required
                    class="block w-full rounded-2xl border-2 border-purple-200 shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 px-5 py-4 text-base transition font-medium">
                    <option value="">📦 Pilih lokasi terlebih dahulu</option>
                    @if($pengaduan->id_item && $pengaduan->item)
                        <option value="{{ $pengaduan->item->id_item }}" selected>{{ $pengaduan->item->nama_item }}</option>
                    @endif
                </select>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-3">
                <label for="deskripsi" class="block text-sm font-black text-gray-800 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-align-left text-white"></i>
                    </div>
                    <span>Deskripsi Masalah <span class="text-red-500">*</span></span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="5" required
                    class="block w-full rounded-2xl border-2 border-purple-200 shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-100 px-5 py-4 text-base transition font-medium"
                    placeholder="Jelaskan detail pengaduan Anda">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
            </div>

            <!-- Foto -->
            <div class="space-y-3">
                <label for="foto" class="block text-sm font-black text-gray-800 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-camera text-white"></i>
                    </div>
                    <span>Foto Bukti</span>
                </label>
                
                @if($pengaduan->foto)
                    <div class="mb-4 bg-gradient-to-br from-purple-50 to-violet-50 p-6 rounded-2xl border-2 border-purple-200">
                        <p class="text-sm font-black text-gray-800 mb-3 flex items-center space-x-2">
                            <i class="fas fa-image text-purple-600"></i>
                            <span>Foto saat ini:</span>
                        </p>
                        <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                             alt="Foto Pengaduan" 
                             class="max-w-lg w-full rounded-2xl shadow-2xl border-4 border-white">
                    </div>
                @endif
                
                <div class="mt-2 flex justify-center px-8 pt-12 pb-12 border-4 border-dashed border-purple-300 rounded-3xl bg-gradient-to-br from-purple-50 to-violet-50 hover:border-purple-500 transition">
                    <div class="space-y-4 text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-cloud-upload-alt text-purple-600 text-4xl"></i>
                        </div>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="foto" class="relative cursor-pointer bg-white rounded-2xl font-black text-purple-600 hover:text-purple-700 px-6 py-3 shadow-lg hover:shadow-xl transition">
                                <span><i class="fas fa-file-upload mr-2"></i>Pilih File Baru</span>
                                <input type="file" name="foto" id="foto" class="sr-only" accept="image/*">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 font-bold">PNG, JPG, JPEG (Max. 2MB)</p>
                        <p class="text-xs text-purple-600 font-black">Biarkan kosong jika tidak ingin mengubah foto</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center pt-8 border-t-2 border-purple-100 space-y-4 sm:space-y-0">
                <a href="{{ route('pengaduan.show', $pengaduan) }}" 
                   class="text-gray-600 hover:text-gray-800 font-black flex items-center space-x-2 px-8 py-4 rounded-2xl hover:bg-gray-100 transition">
                    <i class="fas fa-arrow-left"></i>
                    <span>Batal</span>
                </a>
                
                <button type="submit" 
                        class="bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 hover:from-purple-700 hover:via-violet-700 hover:to-indigo-700 text-white font-black py-4 px-10 rounded-2xl shadow-2xl transform hover:scale-105 transition duration-300 flex items-center space-x-3">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

            <!-- Delete Button -->
            <div class="pt-8 border-t-2 border-red-200">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 p-6 rounded-2xl border-2 border-red-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="text-lg font-black text-red-800 mb-2 flex items-center space-x-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Zona Bahaya</span>
                            </h4>
                            <p class="text-sm text-red-700 font-semibold">Menghapus pengaduan ini akan menghapus semua data terkait secara permanen.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Delete Form -->
        <div class="px-8 pb-8">
            <form action="{{ route('pengaduan.destroy', $pengaduan) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-black py-4 px-8 rounded-2xl shadow-xl transition flex items-center justify-center space-x-3"
                        onclick="return confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus pengaduan ini?\nTindakan ini tidak dapat dibatalkan!')">
                    <i class="fas fa-trash-alt"></i>
                    <span>Hapus Pengaduan Ini</span>
                </button>
            </form>
        </div>
    </div>
</div>

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
            .then(response => response.json())
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
                } else {
                    itemSelect.innerHTML = '<option value="">ℹ️ Tidak ada item</option>';
                }
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