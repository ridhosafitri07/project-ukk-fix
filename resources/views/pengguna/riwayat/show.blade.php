@extends('layouts.pengguna')

@section('title', 'Detail Pengaduan')
@section('header', 'Detail Pengaduan')
@section('subheader', 'Informasi lengkap pengaduan sarana dan prasarana')

@section('content')
<style>
    .status-badge {
        @apply inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold;
    }
    .status-badge.draft     { @apply bg-yellow-100 text-yellow-800; }
    .status-badge.process   { @apply bg-blue-100 text-blue-800; }
    .status-badge.approved  { @apply bg-green-100 text-green-800; }
    .status-badge.rejected  { @apply bg-red-100 text-red-800; }
    .status-badge.completed { @apply bg-emerald-100 text-emerald-800; }

    .card-header {
        @apply px-6 py-4 rounded-t-2xl font-semibold text-white;
    }
    .card-header.primary   { @apply bg-gradient-to-r from-purple-600 to-indigo-600; }
    .card-header.secondary { @apply bg-gradient-to-r from-gray-600 to-gray-700; }
    .card-header.orange    { @apply bg-gradient-to-r from-orange-500 to-rose-500; }
    .card-header.pink      { @apply bg-gradient-to-r from-pink-500 to-fuchsia-500; }
    .card-header.blue      { @apply bg-gradient-to-r from-blue-500 to-indigo-500; }
</style>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Pengaduan Info Card -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <div class="card-header primary flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex-1">
                    <h2 class="text-xl sm:text-2xl font-bold">{{ $pengaduan->nama_pengaduan }}</h2>
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
                    <div class="flex gap-2">
                        <a href="{{ route('pengaduan.edit', $pengaduan) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('pengaduan.destroy', $pengaduan) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-500/90 hover:bg-red-600 text-white font-medium rounded-lg transition-colors"
                                onclick="return confirm('Yakin ingin menghapus pengaduan ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="p-6 space-y-6">
                <!-- Info Ringkas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="mt-0.5 w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                            <i class="fas fa-map-marker-alt text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Lokasi</p>
                            <p class="text-gray-900 font-medium">{{ $pengaduan->lokasi }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="mt-0.5 w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
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

                <!-- Deskripsi -->
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="card-header secondary flex items-center gap-2">
                        <i class="fas fa-align-left"></i>
                        <span>Deskripsi Masalah</span>
                    </div>
                    <div class="p-5 bg-white">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $pengaduan->deskripsi }}
                        </p>
                    </div>
                </div>

                <!-- Saran Petugas (jika ada) -->
                @if($pengaduan->saran_petugas)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="card-header blue flex items-center gap-2">
                        <i class="fas fa-user-tie"></i>
                        <span>Saran dari Petugas</span>
                    </div>
                    <div class="p-5 bg-white">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $pengaduan->saran_petugas }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Foto Bukti -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <div class="card-header orange flex items-center gap-2">
                <i class="fas fa-camera"></i>
                <span>Foto Bukti</span>
            </div>
            <div class="p-6">
                @if($pengaduan->foto)
                    <div class="relative group cursor-pointer">
                        <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                             alt="Bukti Pengaduan"
                             class="w-full rounded-xl border border-gray-200 shadow-sm">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                            <span class="text-white font-medium flex items-center gap-1.5">
                                <i class="fas fa-expand"></i> Lihat Penuh
                            </span>
                        </div>
                        <a href="{{ asset('storage/' . $pengaduan->foto) }}" target="_blank"
                           class="absolute inset-0"></a>
                    </div>
                @else
                    <div class="py-12 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                        <i class="fas fa-image text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada foto bukti yang dilampirkan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Permintaan Barang Baru (Temporary Items) -->
        @if($pengaduan->temporary_items?->count() > 0)
        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <div class="card-header pink flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <i class="fas fa-box-open"></i>
                    <span>Permintaan Barang Baru</span>
                </div>
                <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium">
                    {{ $pengaduan->temporary_items->count() }} item
                </span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($pengaduan->temporary_items as $temp)
                <div class="p-5 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $temp->nama_barang_baru }}</h4>
                            <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                <i class="fas fa-map-marker-alt text-gray-500"></i>
                                {{ $temp->lokasi_barang_baru }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                Diajukan: {{ \Carbon\Carbon::parse($temp->tanggal_permintaan)->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="status-badge draft text-xs px-2.5 py-1">
                            <i class="fas fa-clock mr-1"></i> Menunggu
                        </span>
                    </div>

                    @if($temp->foto_kerusakan)
                    <div class="mt-4">
                        <p class="text-xs font-medium text-gray-600 mb-2">Foto Kerusakan</p>
                        <div class="relative group inline-block">
                            <img src="{{ asset('storage/' . $temp->foto_kerusakan) }}"
                                 alt="Kerusakan"
                                 class="h-28 rounded-lg border border-gray-200 object-cover">
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
        <!-- Timeline Status -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <div class="card-header primary flex items-center gap-2">
                <i class="fas fa-route"></i>
                <span>Alur Status</span>
            </div>
            <div class="p-5 space-y-5">
                @php
                    $timeline = [
                        ['label' => 'Diajukan', 'done' => true, 'desc' => 'Pengaduan telah diajukan'],
                        ['label' => 'Verifikasi', 'done' => in_array($pengaduan->status, ['Disetujui', 'Diproses', 'Selesai', 'Ditolak']), 'desc' => $pengaduan->status === 'Ditolak' ? 'Ditolak' : (in_array($pengaduan->status, ['Disetujui', 'Diproses', 'Selesai']) ? 'Disetujui' : 'Menunggu') ],
                        ['label' => 'Diproses', 'done' => in_array($pengaduan->status, ['Diproses', 'Selesai']), 'desc' => in_array($pengaduan->status, ['Diproses', 'Selesai']) ? 'Sedang ditangani' : 'Belum diproses'],
                        ['label' => 'Selesai', 'done' => $pengaduan->status === 'Selesai', 'desc' => $pengaduan->status === 'Selesai' ? 'Pengaduan selesai' : 'Belum selesai'],
                    ];
                @endphp

                @foreach($timeline as $step)
                    <div class="flex gap-3">
                        <div class="relative mt-0.5">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $step['done'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                @if($step['done'])
                                    <i class="fas fa-check"></i>
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </div>
                            @if(!$loop->last)
                                <div class="absolute top-7 bottom-0 left-1/2 w-0.5 -translate-x-1/2
                                    {{ $step['done'] ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $step['label'] }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <div class="card-header primary flex items-center gap-2">
                <i class="fas fa-bolt"></i>
                <span>Aksi Cepat</span>
            </div>
            <div class="p-4 space-y-3">
                <a href="{{ route('pengaduan.index') }}"
                   class="flex items-center gap-2.5 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors">
                    <i class="fas fa-list"></i> Lihat Semua Pengaduan
                </a>
                <a href="{{ route('pengaduan.create') }}"
                   class="flex items-center gap-2.5 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus-circle"></i> Buat Pengaduan Baru
                </a>
                <a href="{{ route('pengguna.dashboard') }}"
                   class="flex items-center gap-2.5 px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-home"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection