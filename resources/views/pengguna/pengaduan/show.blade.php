@extends('layouts.pengguna')

@section('title', 'Detail Pengaduan')
@section('header', 'Detail Pengaduan')
@section('subheader', 'Informasi lengkap pengaduan sarana dan prasarana')

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
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .timeline-dot {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Pengaduan Info Card -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 animate-fade-in-up">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $pengaduan->nama_pengaduan }}</h3>
                        <div class="flex items-center space-x-2">
                            <span class="px-4 py-2 text-xs font-bold rounded-full shadow-md
                                @if($pengaduan->status === 'Diajukan') bg-yellow-400 text-white
                                @elseif($pengaduan->status === 'Disetujui') bg-green-400 text-white
                                @elseif($pengaduan->status === 'Ditolak') bg-red-400 text-white
                                @elseif($pengaduan->status === 'Diproses') bg-blue-400 text-white
                                @else bg-gray-200 text-gray-800
                                @endif">
                                {{ $pengaduan->status }}
                            </span>
                        </div>
                    </div>
                    @if($pengaduan->status === 'Diajukan')
                    <div class="flex space-x-2 ml-4">
                        <a href="{{ route('pengaduan.edit', $pengaduan) }}" 
                            class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold px-4 py-2 rounded-xl transition flex items-center space-x-2">
                            <i class="fas fa-edit"></i>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                        <form action="{{ route('pengaduan.destroy', $pengaduan) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-xl transition flex items-center space-x-2"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                <i class="fas fa-trash"></i>
                                <span class="hidden sm:inline">Hapus</span>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Lokasi & Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-4 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase">Lokasi</p>
                            <p class="text-base font-bold text-gray-900">{{ $pengaduan->lokasi }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase">Tanggal</p>
                            <p class="text-base font-bold text-gray-900">{{ \Carbon\Carbon::parse($pengaduan->tgl_pengajuan)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Deskripsi -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border-2 border-gray-200">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-gray-600 to-gray-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-align-left text-white"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">Deskripsi Masalah</h4>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $pengaduan->deskripsi }}</p>
                </div>
                
                <!-- Saran Petugas -->
                @if($pengaduan->saran_petugas)
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border-2 border-blue-200">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-tie text-white"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">Saran Petugas</h4>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $pengaduan->saran_petugas }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Foto Bukti -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 animate-fade-in-up" style="animation-delay: 0.1s">
            <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                <h4 class="text-xl font-bold text-white flex items-center space-x-2">
                    <i class="fas fa-camera"></i>
                    <span>Foto Bukti</span>
                </h4>
            </div>
            <div class="p-6">
                @if($pengaduan->foto)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                             alt="Foto Pengaduan" 
                             class="w-full rounded-2xl shadow-2xl border-4 border-white">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-2xl flex items-center justify-center">
                            <a href="{{ asset('storage/' . $pengaduan->foto) }}" target="_blank" 
                               class="opacity-0 group-hover:opacity-100 bg-white text-blue-600 px-6 py-3 rounded-xl font-bold shadow-xl transform scale-90 group-hover:scale-100 transition-all duration-300 flex items-center space-x-2">
                                <i class="fas fa-expand"></i>
                                <span>Lihat Penuh</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-2xl">
                        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Tidak ada foto bukti</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Temporary Items Section -->
        @if($pengaduan->temporary_items && $pengaduan->temporary_items->count() > 0)
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 animate-fade-in-up" style="animation-delay: 0.15s">
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                <h4 class="text-xl font-bold text-white flex items-center space-x-2">
                    <i class="fas fa-hourglass-half"></i>
                    <span>Permintaan Barang Baru</span>
                    <span class="ml-auto bg-white/20 px-3 py-1 rounded-full text-sm">{{ $pengaduan->temporary_items->count() }}</span>
                </h4>
            </div>
            <div class="p-6 space-y-4">
                @foreach($pengaduan->temporary_items as $temp)
                <div class="border-2 border-purple-100 rounded-xl p-4 bg-gradient-to-br from-purple-50 to-pink-50">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h5 class="text-lg font-bold text-gray-900">{{ $temp->nama_barang_baru }}</h5>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                Lokasi: <strong>{{ $temp->lokasi_barang_baru }}</strong>
                            </p>
                        </div>
                        <span class="px-4 py-2 text-xs font-bold rounded-full whitespace-nowrap ml-2 bg-yellow-100 text-yellow-800">
                            Menunggu Persetujuan
                        </span>
                    </div>

                    @if($temp->foto_kerusakan)
                    <div class="mb-3">
                        <p class="text-xs font-semibold text-gray-600 mb-2">Foto Kerusakan:</p>
                        <div class="relative group inline-block">
                            <img src="{{ asset('storage/' . $temp->foto_kerusakan) }}" 
                                 alt="Foto Kerusakan" 
                                 class="h-24 rounded-lg shadow-md border-2 border-gray-200 cursor-pointer hover:border-purple-400 transition">
                            <a href="{{ asset('storage/' . $temp->foto_kerusakan) }}" target="_blank" 
                               class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <i class="fas fa-expand text-white"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 pt-2 border-t border-purple-200">
                        <div>
                            <span class="font-semibold">Tanggal Permintaan:</span><br>
                            {{ \Carbon\Carbon::parse($temp->tanggal_permintaan)->format('d M Y H:i') }}
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Timeline -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h4 class="text-xl font-bold text-white flex items-center space-x-2">
                    <i class="fas fa-list-check"></i>
                    <span>Status Timeline</span>
                </h4>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Diajukan -->
                    <div class="flex items-start space-x-3">
                        <div class="timeline-dot w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">Diajukan</p>
                            <p class="text-xs text-gray-500">Pengaduan telah diajukan</p>
                        </div>
                    </div>
                    
                    <!-- Disetujui/Ditolak -->
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br {{ in_array($pengaduan->status, ['Disetujui', 'Diproses', 'Selesai']) ? 'from-green-400 to-emerald-400' : ($pengaduan->status === 'Ditolak' ? 'from-red-400 to-pink-400' : 'from-gray-300 to-gray-400') }} rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas {{ in_array($pengaduan->status, ['Disetujui', 'Diproses', 'Selesai']) || $pengaduan->status === 'Ditolak' ? 'fa-check' : 'fa-clock' }} text-white text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">Verifikasi</p>
                            <p class="text-xs text-gray-500">{{ in_array($pengaduan->status, ['Disetujui', 'Diproses', 'Selesai']) ? 'Disetujui' : ($pengaduan->status === 'Ditolak' ? 'Ditolak' : 'Menunggu verifikasi') }}</p>
                        </div>
                    </div>
                    
                    <!-- Diproses -->
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br {{ in_array($pengaduan->status, ['Diproses', 'Selesai']) ? 'from-blue-400 to-indigo-400' : 'from-gray-300 to-gray-400' }} rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas {{ in_array($pengaduan->status, ['Diproses', 'Selesai']) ? 'fa-check' : 'fa-clock' }} text-white text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">Dalam Proses</p>
                            <p class="text-xs text-gray-500">{{ in_array($pengaduan->status, ['Diproses', 'Selesai']) ? 'Sedang ditangani' : 'Belum diproses' }}</p>
                        </div>
                    </div>
                    
                    <!-- Selesai -->
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br {{ $pengaduan->status === 'Selesai' ? 'from-green-500 to-emerald-600' : 'from-gray-300 to-gray-400' }} rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas {{ $pengaduan->status === 'Selesai' ? 'fa-check' : 'fa-clock' }} text-white text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">Selesai</p>
                            <p class="text-xs text-gray-500">{{ $pengaduan->status === 'Selesai' ? 'Pengaduan selesai' : 'Belum selesai' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h4 class="text-xl font-bold text-white flex items-center space-x-2">
                    <i class="fas fa-bolt"></i>
                    <span>Aksi Cepat</span>
                </h4>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('pengaduan.index') }}" class="block w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md hover:shadow-xl text-center">
                    <i class="fas fa-list mr-2"></i> Lihat Semua Pengaduan
                </a>
                <a href="{{ route('pengaduan.create') }}" class="block w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md hover:shadow-xl text-center">
                    <i class="fas fa-plus mr-2"></i> Buat Pengaduan Baru
                </a>
                <a href="{{ route('pengguna.dashboard') }}" class="block w-full bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md hover:shadow-xl text-center">
                    <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection