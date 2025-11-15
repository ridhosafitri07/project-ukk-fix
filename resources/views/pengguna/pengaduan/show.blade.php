@extends('layouts.pengguna')

@section('title', 'Detail Pengaduan')
@section('header', 'Detail Pengaduan')
@section('subheader', 'Informasi lengkap pengaduan sarana dan prasarana')

@section('content')
<style>
    .status-badge {
        @apply inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold;
    }
    .status-badge.draft     { @apply bg-yellow-100 text-yellow-800; }
    .status-badge.process   { @apply bg-blue-100 text-blue-800; }
    .status-badge.approved  { @apply bg-green-100 text-green-800; }
    .status-badge.rejected  { @apply bg-red-100 text-red-800; }
    .status-badge.completed { @apply bg-emerald-100 text-emerald-800; }

    .timeline-step {
        @apply flex items-start gap-3 pb-4 last:pb-0;
    }
    .timeline-dot {
        @apply w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold;
    }
    .timeline-dot.done { @apply bg-green-500; }
    .timeline-dot.pending { @apply bg-gray-300; }

    .timeline-line {
        @apply absolute left-[15px] top-6 bottom-0 w-0.5 bg-gray-200;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .timeline-line {
            left: 12px !important;
        }
        .timeline-step {
            gap: 1rem !important;
        }
        .timeline-dot {
            width: 20px !important;
            height: 20px !important;
            font-size: 0.75rem !important;
        }
    }
</style>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Detail Pengaduan Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm">
            <!-- Judul & Status -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">{{ $pengaduan->nama_pengaduan }}</h2>
                    @php
                        $statusClass = match($pengaduan->status) {
                            'Diajukan' => 'draft',
                            'Disetujui' => 'approved',
                            'Diproses' => 'process',
                            'Selesai' => 'completed',
                            'Ditolak' => 'rejected',
                            default => 'draft',
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }} mt-1 inline-flex">
                        <span class="w-2 h-2 rounded-full bg-current"></span>
                        {{ $pengaduan->status }}
                    </span>
                </div>
                @if($pengaduan->status === 'Diajukan')
                    <div class="flex flex-wrap gap-2 mt-2 sm:mt-0">
                        <a href="{{ route('pengaduan.edit', $pengaduan) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors text-sm">
                            <i class="fas fa-edit text-xs"></i> Edit
                        </a>
                        <form action="{{ route('pengaduan.destroy', $pengaduan) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-red-700 hover:bg-red-50 rounded-lg transition-colors text-sm"
                                onclick="return confirm('Yakin ingin menghapus pengaduan ini?')">
                                <i class="fas fa-trash text-xs"></i> Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Lokasi & Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="flex items-center gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                        <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Lokasi</p>
                        <p class="text-gray-900 font-medium">{{ $pengaduan->lokasi }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-calendar-day text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Tanggal Pengajuan</p>
                        <p class="text-gray-900 font-medium">
                            {{ \Carbon\Carbon::parse($pengaduan->tgl_pengajuan)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Masalah -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2 text-sm sm:text-base">
                    <i class="fas fa-align-left text-sm"></i> Deskripsi Masalah
                </h3>
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line text-sm sm:text-base">
                        {{ $pengaduan->deskripsi }}
                    </p>
                </div>
            </div>

            <!-- Saran Petugas -->
            @if($pengaduan->saran_petugas)
            <div class="mb-6">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2 text-sm sm:text-base">
                    <i class="fas fa-user-tie text-sm"></i> Saran Petugas
                </h3>
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line text-sm sm:text-base">
                        {{ $pengaduan->saran_petugas }}
                    </p>
                </div>
            </div>
            @endif
        </div>

        <!-- Foto Bukti -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-camera text-sm"></i> Foto Bukti
            </h3>
            @if($pengaduan->foto)
                <div class="relative group cursor-pointer">
                    <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                         alt="Bukti Pengaduan"
                         class="w-full rounded-lg border border-gray-200 shadow-sm object-contain max-h-96 sm:max-h-[500px]">
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                        <span class="text-white font-medium flex items-center gap-1.5 text-sm sm:text-base">
                            <i class="fas fa-expand"></i> Lihat Penuh
                        </span>
                    </div>
                    <a href="{{ asset('storage/' . $pengaduan->foto) }}" target="_blank"
                       class="absolute inset-0"></a>
                </div>
            @else
                <div class="py-8 sm:py-12 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-image text-gray-400 text-3xl sm:text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm sm:text-base">Tidak ada foto bukti yang dilampirkan</p>
                </div>
            @endif
        </div>

        <!-- Permintaan Barang Baru -->
        @if($pengaduan->temporary_items?->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-box-open text-sm"></i> Permintaan Barang Baru
                <span class="ml-auto bg-gray-100 text-gray-700 text-xs sm:text-sm font-medium px-2.5 py-1 rounded-full">
                    {{ $pengaduan->temporary_items->count() }} item
                </span>
            </h3>
            <div class="space-y-4">
                @foreach($pengaduan->temporary_items as $temp)
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm sm:text-base">{{ $temp->nama_barang_baru }}</h4>
                            <p class="text-xs sm:text-sm text-gray-600 flex items-center gap-1 mt-1">
                                <i class="fas fa-map-marker-alt text-gray-500"></i>
                                {{ $temp->lokasi_barang_baru }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                Diajukan: {{ \Carbon\Carbon::parse($temp->tanggal_permintaan)->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="status-badge draft text-xs sm:text-sm px-2.5 py-1">
                            <i class="fas fa-clock mr-1"></i> Menunggu
                        </span>
                    </div>
                    @if($temp->foto_kerusakan)
                    <div class="mt-3">
                        <p class="text-xs font-medium text-gray-600 mb-2">Foto Kerusakan:</p>
                        <div class="relative group inline-block">
                            <img src="{{ asset('storage/' . $temp->foto_kerusakan) }}"
                                 alt="Kerusakan"
                                 class="h-20 sm:h-24 rounded-lg border border-gray-200 object-cover">
                            <a href="{{ asset('storage/' . $temp->foto_kerusakan) }}" target="_blank"
                               class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-expand text-white text-lg"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Alur Status -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-route text-sm"></i> Alur Status
            </h3>
            <div class="relative">
                <div class="timeline-line"></div>
                <div class="space-y-4">
                    <!-- Diajukan -->
                    <div class="timeline-step">
                        <div class="timeline-dot done">✓</div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm sm:text-base">Diajukan</p>
                            <p class="text-xs text-gray-500">Pengaduan telah diajukan</p>
                        </div>
                    </div>

                    <!-- Verifikasi -->
                    <div class="timeline-step">
                        <div class="timeline-dot {{ in_array($pengaduan->status, ['Disetujui', 'Diproses', 'Selesai', 'Ditolak']) ? 'done' : 'pending' }}">
                            {{ in_array($pengaduan->status, ['Disetujui', 'Diproses', 'Selesai', 'Ditolak']) ? '✓' : '' }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm sm:text-base">Verifikasi</p>
                            <p class="text-xs text-gray-500">
                                {{ $pengaduan->status === 'Ditolak' ? 'Ditolak' : (in_array($pengaduan->status, ['Disetujui', 'Diproses', 'Selesai']) ? 'Disetujui' : 'Menunggu verifikasi') }}
                            </p>
                        </div>
                    </div>

                    <!-- Diproses -->
                    <div class="timeline-step">
                        <div class="timeline-dot {{ in_array($pengaduan->status, ['Diproses', 'Selesai']) ? 'done' : 'pending' }}">
                            {{ in_array($pengaduan->status, ['Diproses', 'Selesai']) ? '✓' : '' }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm sm:text-base">Diproses</p>
                            <p class="text-xs text-gray-500">
                                {{ in_array($pengaduan->status, ['Diproses', 'Selesai']) ? 'Sedang ditangani' : 'Belum diproses' }}
                            </p>
                        </div>
                    </div>

                    <!-- Selesai -->
                    <div class="timeline-step">
                        <div class="timeline-dot {{ $pengaduan->status === 'Selesai' ? 'done' : 'pending' }}">
                            {{ $pengaduan->status === 'Selesai' ? '✓' : '' }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm sm:text-base">Selesai</p>
                            <p class="text-xs text-gray-500">
                                {{ $pengaduan->status === 'Selesai' ? 'Pengaduan selesai' : 'Belum selesai' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-bolt text-sm"></i> Aksi Cepat
            </h3>
            <div class="space-y-3">
                <a href="{{ route('pengaduan.index') }}"
                   class="block w-full px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-center text-sm sm:text-base transition-colors">
                    <i class="fas fa-list mr-2"></i> Lihat Semua Pengaduan
                </a>
                <a href="{{ route('pengaduan.create') }}"
                   class="block w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium text-center text-sm sm:text-base transition-colors">
                    <i class="fas fa-plus-circle mr-2"></i> Buat Pengaduan Baru
                </a>
                <a href="{{ route('pengguna.dashboard') }}"
                   class="block w-full px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white rounded-lg font-medium text-center text-sm sm:text-base transition-colors">
                    <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection