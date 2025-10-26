@extends('layouts.petugas')

@section('title', 'Detail Riwayat')
@section('header', 'Detail Riwayat Pengaduan #' . $pengaduan->id_pengaduan)
@section('subheader', 'Informasi lengkap riwayat pekerjaan')

@section('content')
<div class="mb-6">
    <a href="{{ route('petugas.riwayat.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 transition-all">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Riwayat
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Pengaduan
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">ID Pengaduan</label>
                        <p class="text-lg font-bold text-gray-800 mt-1">#{{ $pengaduan->id_pengaduan }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Tanggal Pengajuan</label>
                        <p class="text-lg font-bold text-gray-800 mt-1 flex items-center">
                            <i class="far fa-calendar text-green-500 mr-2"></i>
                            {{ date('d/m/Y H:i', strtotime($pengaduan->tgl_pengajuan)) }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Pengadu</label>
                        <div class="flex items-center mt-2">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-green-600 font-bold text-sm">{{ strtoupper(substr($pengaduan->user->nama_pengguna, 0, 2)) }}</span>
                            </div>
                            <p class="text-lg font-bold text-gray-800">{{ $pengaduan->user->nama_pengguna }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Status</label>
                        <div class="mt-2">
                            <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full
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

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Detail Pengaduan
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Judul Pengaduan</label>
                    <p class="text-base font-bold text-gray-800 mt-1">{{ $pengaduan->nama_pengaduan }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Lokasi</label>
                    <p class="text-base text-gray-800 mt-1 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        {{ $pengaduan->lokasi }}
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Deskripsi</label>
                    <p class="text-base text-gray-800 mt-1 leading-relaxed">{{ $pengaduan->deskripsi }}</p>
                </div>
                @if($pengaduan->foto)
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase mb-2 block">Foto Pendukung</label>
                    <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                         alt="Foto Pengaduan" 
                         class="w-full max-w-md rounded-lg shadow-md hover:shadow-xl transition-shadow cursor-pointer"
                         onclick="window.open(this.src, '_blank')">
                </div>
                @endif
                @if($pengaduan->catatan_admin)
                <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <label class="text-xs font-semibold text-blue-700 uppercase">Catatan Admin</label>
                    <p class="text-sm text-gray-800 mt-1">{{ $pengaduan->catatan_admin }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($pengaduan->saran_petugas)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-comment-dots mr-2"></i>
                    Saran Petugas
                </h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-800 leading-relaxed">{{ $pengaduan->saran_petugas }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-history mr-2"></i>
                    Timeline
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-paper-plane text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-800">Pengaduan Diajukan</p>
                            <p class="text-xs text-gray-600 mt-1">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_pengajuan)) }}</p>
                        </div>
                    </div>

                    @if($pengaduan->tgl_verifikasi)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-800">Diverifikasi Admin</p>
                            <p class="text-xs text-gray-600 mt-1">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_verifikasi)) }}</p>
                        </div>
                    </div>
                    @endif

                    @if($pengaduan->status == 'Diproses')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-cog text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-800">Sedang Diproses</p>
                            <p class="text-xs text-gray-600 mt-1">Dikerjakan oleh petugas</p>
                        </div>
                    </div>
                    @endif

                    @if($pengaduan->tgl_selesai)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-flag-checkered text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-800">Selesai</p>
                            <p class="text-xs text-gray-600 mt-1">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_selesai)) }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-gray-700 to-gray-900">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('petugas.riwayat.index') }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-list text-gray-500 mr-2"></i>
                        Lihat Semua Riwayat
                    </span>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <button onclick="window.print()" 
                   class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-print text-gray-500 mr-2"></i>
                        Cetak Detail
                    </span>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
