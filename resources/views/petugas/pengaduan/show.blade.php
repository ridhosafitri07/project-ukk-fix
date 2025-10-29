@extends('layouts.petugas')

@section('title', 'Detail Pengaduan')
@section('header', 'Detail Tugas Pengaduan #' . $pengaduan->id_pengaduan)
@section('subheader', 'Informasi lengkap pengaduan')

@section('content')
<div class="mb-4 md:mb-6">
    <a href="{{ route('petugas.pengaduan.index') }}" 
       class="inline-flex items-center px-3 py-2 md:px-4 md:py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm md:text-base text-gray-700 hover:bg-gray-50 transition-all">
        <i class="fas fa-arrow-left mr-2 text-sm"></i>
        <span class="hidden sm:inline">Kembali ke Daftar</span>
        <span class="sm:hidden">Kembali</span>
    </a>
</div>

@if(session('success'))
<div class="mb-4 md:mb-6 bg-green-50 border-l-4 border-green-400 p-3 md:p-4">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-400 mr-2 md:mr-3 text-sm md:text-base"></i>
        <p class="text-xs md:text-sm text-green-700">{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-4 md:mb-6 bg-red-50 border-l-4 border-red-400 p-3 md:p-4">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-400 mr-2 md:mr-3 text-sm md:text-base"></i>
        <p class="text-xs md:text-sm text-red-700">{{ session('error') }}</p>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-4 md:space-y-6">
        <!-- Informasi Pengaduan -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 md:p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-info-circle mr-2 text-sm md:text-base"></i>
                    Informasi Pengaduan
                </h3>
            </div>
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">ID Pengaduan</label>
                        <p class="text-base md:text-lg font-bold text-gray-800 mt-1">#{{ $pengaduan->id_pengaduan }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Tanggal Pengajuan</label>
                        <p class="text-sm md:text-base font-bold text-gray-800 mt-1 flex items-center">
                            <i class="far fa-calendar text-green-500 mr-2 text-xs md:text-sm"></i>
                            <span class="text-xs md:text-base">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_pengajuan)) }}</span>
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Pengadu</label>
                        <div class="flex items-center mt-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-green-100 rounded-full flex items-center justify-center mr-2 md:mr-3 flex-shrink-0">
                                <span class="text-green-600 font-bold text-xs md:text-sm">{{ strtoupper(substr($pengaduan->user->nama_pengguna, 0, 2)) }}</span>
                            </div>
                            <p class="text-sm md:text-base font-bold text-gray-800 truncate">{{ $pengaduan->user->nama_pengguna }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Status</label>
                        <div class="mt-2">
                            <span class="px-3 py-1 md:px-4 md:py-2 inline-flex text-xs md:text-sm font-bold rounded-full
                                @if($pengaduan->status === 'Disetujui') bg-yellow-100 text-yellow-800
                                @elseif($pengaduan->status === 'Diproses') bg-green-100 text-green-800
                                @elseif($pengaduan->status === 'Selesai') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $pengaduan->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Pengaduan -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 md:p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-file-alt mr-2 text-sm md:text-base"></i>
                    Detail Pengaduan
                </h3>
            </div>
            <div class="p-4 md:p-6 space-y-3 md:space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Judul Pengaduan</label>
                    <p class="text-sm md:text-base font-bold text-gray-800 mt-1">{{ $pengaduan->nama_pengaduan }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Lokasi</label>
                    <p class="text-sm md:text-base text-gray-800 mt-1 flex items-start">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2 mt-0.5 flex-shrink-0 text-xs md:text-sm"></i>
                        <span class="break-words">{{ $pengaduan->lokasi }}</span>
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Deskripsi</label>
                    <p class="text-sm md:text-base text-gray-800 mt-1 leading-relaxed break-words">{{ $pengaduan->deskripsi }}</p>
                </div>
                @if($pengaduan->foto)
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase mb-2 block">Foto Pendukung</label>
                    <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                         alt="Foto Pengaduan" 
                         class="w-full max-w-full md:max-w-md rounded-lg shadow-md hover:shadow-xl transition-shadow cursor-pointer"
                         onclick="window.open(this.src, '_blank')">
                </div>
                @endif
                @if($pengaduan->catatan_admin)
                <div class="p-3 md:p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <label class="text-xs font-semibold text-blue-700 uppercase">Catatan Admin</label>
                    <p class="text-xs md:text-sm text-gray-800 mt-1 break-words">{{ $pengaduan->catatan_admin }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Permintaan Barang -->
        @if($pengaduan->temporary_items && $pengaduan->temporary_items->count() > 0)
        <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 md:p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-box mr-2 text-sm md:text-base"></i>
                    Permintaan Barang
                </h3>
            </div>
            <div class="p-4 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    @foreach($pengaduan->temporary_items as $item)
                    <div class="bg-gray-50 p-3 md:p-4 rounded-lg border-2 
                        @if($item->status_permintaan === 'Menunggu Persetujuan') border-yellow-200
                        @elseif($item->status_permintaan === 'Disetujui') border-green-200
                        @else border-red-200
                        @endif">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-box text-purple-500 mr-2 text-xs md:text-sm flex-shrink-0"></i>
                                    <p class="font-bold text-sm md:text-base text-gray-900 break-words">{{ $item->nama_barang_baru }}</p>
                                </div>
                                <p class="text-xs md:text-sm text-gray-600 mb-1 break-words">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    {{ $item->lokasi_barang_baru }}
                                </p>
                                <p class="text-xs md:text-sm text-gray-600 mb-2 break-words">{{ Str::limit($item->alasan_permintaan, 100) }}</p>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2 py-1 md:px-3 md:py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($item->status_permintaan === 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800
                                        @elseif($item->status_permintaan === 'Disetujui') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $item->status_permintaan }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ date('d/m/Y', strtotime($item->tanggal_permintaan)) }}
                                    </span>
                                </div>
                                @if($item->catatan_admin)
                                <div class="mt-2 p-2 bg-white rounded border border-gray-200">
                                    <p class="text-xs font-semibold text-gray-700">Catatan Admin:</p>
                                    <p class="text-xs text-gray-600 break-words">{{ $item->catatan_admin }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Form Update Status -->
        @if($pengaduan->status !== 'Selesai')
        <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 md:p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-tasks mr-2 text-sm md:text-base"></i>
                    Update Status Pekerjaan
                </h3>
            </div>
            <div class="p-4 md:p-6">
                <form action="{{ route('petugas.pengaduan.update-status', $pengaduan) }}" method="POST" class="space-y-4 md:space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4 md:space-y-6">
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-gray-700 mb-2 md:mb-3">Pilih Status</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                                <label class="relative flex items-center p-3 md:p-4 bg-blue-50 border-2 border-blue-200 rounded-lg cursor-pointer hover:bg-blue-100 transition-colors">
                                    <input type="radio" name="status" value="Diproses" class="form-radio text-blue-600 h-4 w-4 md:h-5 md:w-5 flex-shrink-0" required 
                                           {{ $pengaduan->status == 'Diproses' ? 'checked' : '' }}>
                                    <span class="ml-2 md:ml-3 flex items-center">
                                        <i class="fas fa-cog text-blue-600 mr-2 text-sm md:text-base"></i>
                                        <span class="text-xs md:text-sm font-semibold text-blue-800">Sedang Diproses</span>
                                    </span>
                                </label>
                                <label class="relative flex items-center p-3 md:p-4 bg-green-50 border-2 border-green-200 rounded-lg cursor-pointer hover:bg-green-100 transition-colors">
                                    <input type="radio" name="status" value="Selesai" class="form-radio text-green-600 h-4 w-4 md:h-5 md:w-5 flex-shrink-0" required>
                                    <span class="ml-2 md:ml-3 flex items-center">
                                        <i class="fas fa-check-circle text-green-600 mr-2 text-sm md:text-base"></i>
                                        <span class="text-xs md:text-sm font-semibold text-green-800">Selesai</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="saran_petugas" class="block text-xs md:text-sm font-bold text-gray-700 mb-2">
                                Saran/Keterangan Petugas
                            </label>
                            <textarea id="saran_petugas" name="saran_petugas" rows="4"
                                    class="w-full px-3 py-2 md:px-4 md:py-3 text-sm md:text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                    placeholder="Tambahkan saran atau keterangan pekerjaan...">{{ old('saran_petugas', $pengaduan->saran_petugas) }}</textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-2 md:gap-3">
                            <button type="button" onclick="window.history.back()"
                                    class="w-full sm:w-auto px-4 py-2 md:px-6 md:py-3 bg-gray-100 text-gray-700 rounded-lg text-sm md:text-base font-semibold hover:bg-gray-200 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 md:px-6 md:py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg text-sm md:text-base font-semibold hover:shadow-lg transform hover:scale-105 transition-all flex items-center justify-center">
                                <i class="fas fa-save mr-2 text-sm md:text-base"></i>
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-4 md:space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 md:p-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-bolt mr-2 text-sm md:text-base"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="p-3 md:p-4 space-y-2">
                @if($pengaduan->status !== 'Selesai')
                <a href="{{ route('petugas.item-request.create', $pengaduan) }}" 
                   class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-lg hover:bg-purple-100 transition-colors group">
                    <span class="text-xs md:text-sm font-medium text-purple-700 flex items-center">
                        <i class="fas fa-plus-circle text-purple-500 mr-2 text-sm"></i>
                        <span class="hidden sm:inline">Ajukan Permintaan Barang</span>
                        <span class="sm:hidden">Ajukan Barang</span>
                    </span>
                    <i class="fas fa-arrow-right text-purple-400 group-hover:translate-x-1 transition-transform text-xs md:text-sm"></i>
                </a>
                @endif
                
                <a href="{{ route('petugas.pengaduan.index') }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-xs md:text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-list text-gray-500 mr-2 text-sm"></i>
                        Lihat Semua Tugas
                    </span>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:translate-x-1 transition-transform text-xs md:text-sm"></i>
                </a>
                <button onclick="window.print()" 
                   class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-xs md:text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-print text-gray-500 mr-2 text-sm"></i>
                        Cetak Detail
                    </span>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:translate-x-1 transition-transform text-xs md:text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 md:p-6 bg-gradient-to-r from-green-500 to-emerald-600">
                <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-history mr-2 text-sm md:text-base"></i>
                    Timeline
                </h3>
            </div>
            <div class="p-4 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-paper-plane text-white text-xs md:text-sm"></i>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <p class="text-xs md:text-sm font-semibold text-gray-800">Pengaduan Diajukan</p>
                            <p class="text-xs text-gray-600 mt-1">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_pengajuan)) }}</p>
                        </div>
                    </div>

                    @if($pengaduan->tgl_verifikasi)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs md:text-sm"></i>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <p class="text-xs md:text-sm font-semibold text-gray-800">Diverifikasi Admin</p>
                            <p class="text-xs text-gray-600 mt-1">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_verifikasi)) }}</p>
                        </div>
                    </div>
                    @endif

                    @if($pengaduan->status == 'Diproses')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-cog text-white text-xs md:text-sm"></i>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <p class="text-xs md:text-sm font-semibold text-gray-800">Sedang Diproses</p>
                            <p class="text-xs text-gray-600 mt-1">Dikerjakan oleh petugas</p>
                        </div>
                    </div>
                    @endif

                    @if($pengaduan->tgl_selesai)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-flag-checkered text-white text-xs md:text-sm"></i>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <p class="text-xs md:text-sm font-semibold text-gray-800">Selesai</p>
                            <p class="text-xs text-gray-600 mt-1">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_selesai)) }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection