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

        <!-- Form Update Status - PROGRESSIVE UI -->
        @if(in_array($pengaduan->status, ['Diajukan', 'Disetujui', 'Diproses']))
        <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 md:p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-base md:text-lg font-bold text-white flex items-center">
                    <i class="fas fa-tasks mr-2 text-sm md:text-base"></i>
                    Update Status Pekerjaan
                </h3>
            </div>
            <div class="p-4 md:p-6">
                <form id="updateStatusForm" action="{{ route('petugas.pengaduan.update-status', $pengaduan) }}" method="POST" class="space-y-4 md:space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="status" id="status-input" value="">

                    <div class="space-y-4 md:space-y-6">
                        <!-- STAGE 1: Status Diajukan - Petugas cannot approve/decline -->
                        @if($pengaduan->status === 'Diajukan')
                        <div class="mb-4 md:mb-6">
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 md:p-4 mb-4 md:mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-yellow-400 text-sm md:text-base"></i>
                                    </div>
                                    <div class="ml-2 md:ml-3">
                                        <p class="text-xs md:text-sm text-yellow-700">
                                            <strong>Tahap Persetujuan:</strong> Pengaduan ini masih menunggu keputusan Admin. Anda tidak dapat menyetujui atau menolak.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- STAGE 2: Status Disetujui - Show Action Buttons -->
                        @if($pengaduan->status === 'Disetujui')
                        <div class="mb-4 md:mb-6">
                            <div class="bg-green-50 border-l-4 border-green-400 p-3 md:p-4 mb-4 md:mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400 text-sm md:text-base"></i>
                                    </div>
                                    <div class="ml-2 md:ml-3">
                                        <p class="text-xs md:text-sm text-green-700">
                                            <strong>Pengaduan Disetujui:</strong> Klik tombol di bawah untuk memulai proses atau menyelesaikan langsung.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Tombol Mulai Proses -->
                                <button type="button" onclick="confirmAction('Diproses')"
                                        class="group relative overflow-hidden p-6 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                                    <div class="relative z-10 text-white">
                                        <div class="flex items-center justify-center mb-3">
                                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                                <i class="fas fa-play text-3xl group-hover:animate-pulse"></i>
                                            </div>
                                        </div>
                                        <h3 class="text-xl font-bold text-center mb-2">Mulai Proses</h3>
                                        <p class="text-sm text-center text-blue-100">
                                            Ambil tugas ini dan mulai kerjakan perbaikan
                                        </p>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-blue-800/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </button>

                                <!-- Tombol Selesai Langsung -->
                                <button type="button" onclick="confirmAction('Selesai')"
                                        class="group relative overflow-hidden p-6 bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                                    <div class="relative z-10 text-white">
                                        <div class="flex items-center justify-center mb-3">
                                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check-double text-3xl group-hover:scale-110 transition-transform"></i>
                                            </div>
                                        </div>
                                        <h3 class="text-xl font-bold text-center mb-2">Tandai Selesai</h3>
                                        <p class="text-sm text-center text-purple-100">
                                            Pengaduan sudah selesai ditangani
                                        </p>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-purple-800/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- STAGE 3: Status Diproses - Show Selesai Button -->
                        @if($pengaduan->status === 'Diproses')
                        <div class="mb-4 md:mb-6">
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-3 md:p-4 mb-4 md:mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-cog fa-spin text-blue-400 text-sm md:text-base"></i>
                                    </div>
                                    <div class="ml-2 md:ml-3">
                                        <p class="text-xs md:text-sm text-blue-700">
                                            <strong>Sedang Diproses:</strong> Setelah perbaikan selesai, klik tombol di bawah.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="max-w-md mx-auto">
                                <!-- Tombol Selesai -->
                                <button type="button" onclick="confirmAction('Selesai')"
                                        class="group relative overflow-hidden w-full p-8 bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                                    <div class="relative z-10 text-white">
                                        <div class="flex items-center justify-center mb-4">
                                            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check-circle text-4xl group-hover:scale-110 transition-transform"></i>
                                            </div>
                                        </div>
                                        <h3 class="text-2xl font-bold text-center mb-2">Selesaikan Tugas</h3>
                                        <p class="text-sm text-center text-purple-100">
                                            Perbaikan telah selesai dilakukan
                                        </p>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-purple-800/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </button>
                            </div>
                        </div>
                        @endif

                        <div>
                            <label for="saran_petugas" class="block text-xs md:text-sm font-bold text-gray-700 mb-2">
                                Saran/Keterangan Petugas (Opsional)
                            </label>
                            <textarea id="saran_petugas" name="saran_petugas" rows="4"
                                    class="w-full px-3 py-2 md:px-4 md:py-3 text-sm md:text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                    placeholder="Tambahkan saran atau keterangan pekerjaan...">{{ old('saran_petugas', $pengaduan->saran_petugas) }}</textarea>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Catatan ini akan disimpan bersama dengan status yang dipilih
                            </p>
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

<script>
function confirmAction(status) {
    let message = '';
    let icon = '';
    let confirmButtonText = '';
    let confirmButtonColor = '';
    
    if (status === 'Disetujui') {
        message = 'Apakah Anda yakin ingin menyetujui pengaduan ini? Pengaduan akan siap untuk diproses.';
        icon = 'question';
        confirmButtonText = 'Ya, Setujui!';
        confirmButtonColor = '#10B981';
    } else if (status === 'Ditolak') {
        message = 'Apakah Anda yakin ingin menolak pengaduan ini? Pengaduan tidak akan diproses.';
        icon = 'warning';
        confirmButtonText = 'Ya, Tolak!';
        confirmButtonColor = '#EF4444';
    } else if (status === 'Diproses') {
        message = 'Anda akan mengambil tugas ini dan memulai proses perbaikan. Lanjutkan?';
        icon = 'question';
        confirmButtonText = 'Ya, Mulai Proses!';
        confirmButtonColor = '#3B82F6';
    } else if (status === 'Selesai') {
        message = 'Tandai pengaduan ini sebagai selesai?';
        icon = 'question';
        confirmButtonText = 'Ya, Sudah Selesai!';
        confirmButtonColor = '#A855F7';
    }
    
    Swal.fire({
        title: 'Konfirmasi',
        text: message,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: '#6B7280',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Set status value
            document.getElementById('status-input').value = status;
            
            // Submit form
            document.getElementById('updateStatusForm').submit();
            
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    });
}
</script>
@endsection