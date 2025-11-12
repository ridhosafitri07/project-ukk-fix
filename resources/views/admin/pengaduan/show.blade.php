@extends('layouts.admin')

@section('title', 'Detail Pengaduan')
@section('header', 'Detail Pengaduan #' . $pengaduan->id_pengaduan)
@section('subheader', 'Informasi lengkap pengaduan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.pengaduan.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 transition-all">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Pengaduan -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600">
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
                            <i class="far fa-calendar text-blue-500 mr-2"></i>
                            {{ date('d/m/Y H:i', strtotime($pengaduan->tgl_pengajuan)) }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Pengadu</label>
                        <div class="flex items-center mt-2">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-bold text-sm">{{ strtoupper(substr($pengaduan->user->nama_pengguna, 0, 2)) }}</span>
                            </div>
                            <p class="text-lg font-bold text-gray-800">{{ $pengaduan->user->nama_pengguna }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Status</label>
                        <div class="mt-2">
                            <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full
                                @if($pengaduan->status === 'Diajukan') bg-yellow-100 text-yellow-800
                                @elseif($pengaduan->status === 'Disetujui') bg-green-100 text-green-800
                                @elseif($pengaduan->status === 'Ditolak') bg-red-100 text-red-800
                                @elseif($pengaduan->status === 'Diproses') bg-blue-100 text-blue-800
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
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600">
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
                @if($pengaduan->temporary_items && $pengaduan->temporary_items->count())
                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-600">Permintaan Barang Baru</h4>
                    <div class="mt-2 space-y-3">
                        @foreach($pengaduan->temporary_items as $tmp)
                        <div class="p-3 border rounded-lg flex items-start justify-between">
                            <div>
                                <p class="font-semibold">{{ $tmp->nama_barang_baru }}</p>
                                <p class="text-xs text-gray-500">Lokasi: {{ $tmp->lokasi_barang_baru }}</p>
                                <p class="text-sm text-gray-700 mt-2">{{ Str::limit($tmp->alasan_permintaan, 200) }}</p>
                            </div>
                            <div class="ml-4 text-right">
                                <form method="POST" action="{{ route('admin.pengaduan.approve-temporary', $tmp->id_item ?? $tmp->id) }}">
                                    @csrf
                                    <input type="hidden" name="catatan_admin" value="Disetujui dan ditambahkan ke master item">
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Approve</button>
                                </form>
                                <p class="text-xs text-gray-500 mt-2">Status: {{ $tmp->status_permintaan }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Form Update Status - Admin hanya bisa action di Diajukan & Disetujui -->
        @if(in_array($pengaduan->status, ['Diajukan', 'Disetujui']))
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Update Status Pengaduan
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.pengaduan.update-status', $pengaduan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- STAGE 1: Status Diajukan - Show only Setujui & Tolak -->
                        @if($pengaduan->status === 'Diajukan')
                        <div class="mb-6">
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Tahap Persetujuan:</strong> Pengaduan ini menunggu keputusan untuk disetujui atau ditolak.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Keputusan</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative flex items-center p-5 bg-green-50 border-2 border-green-300 rounded-lg cursor-pointer hover:bg-green-100 hover:border-green-400 transition-all group">
                                    <input type="radio" name="status" value="Disetujui" class="form-radio text-green-600 h-6 w-6" required onchange="togglePetugasSection(true)">
                                    <span class="ml-3 flex items-center">
                                        <i class="fas fa-check-circle text-green-600 text-2xl mr-3 group-hover:scale-110 transition-transform"></i>
                                        <div>
                                            <span class="text-base font-bold text-green-800 block">Setujui Pengaduan</span>
                                            <span class="text-xs text-green-600">Pengaduan akan diproses lebih lanjut</span>
                                        </div>
                                    </span>
                                </label>
                                <label class="relative flex items-center p-5 bg-red-50 border-2 border-red-300 rounded-lg cursor-pointer hover:bg-red-100 hover:border-red-400 transition-all group">
                                    <input type="radio" name="status" value="Ditolak" class="form-radio text-red-600 h-6 w-6" required onchange="togglePetugasSection(false)">
                                    <span class="ml-3 flex items-center">
                                        <i class="fas fa-times-circle text-red-600 text-2xl mr-3 group-hover:scale-110 transition-transform"></i>
                                        <div>
                                            <span class="text-base font-bold text-red-800 block">Tolak Pengaduan</span>
                                            <span class="text-xs text-red-600">Pengaduan tidak akan diproses</span>
                                        </div>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Pilih Petugas (Opsional - Only when Disetujui) -->
                        <div id="petugas-section" style="display: none;" class="bg-blue-50 p-6 rounded-lg border-2 border-blue-200">
                            <h4 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                                <i class="fas fa-user-cog mr-2"></i>
                                Penugasan Petugas (Opsional)
                            </h4>
                            <p class="text-sm text-blue-600 mb-4">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Pilih petugas tertentu atau kosongkan agar semua petugas dapat mengambil tugas ini.
                            </p>
                            <div>
                                <label for="id_petugas" class="block text-sm font-bold text-gray-700 mb-2">
                                    Petugas yang Ditugaskan
                                </label>
                                <select id="id_petugas" name="id_petugas"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Tidak Assign (Semua Petugas Dapat Mengambil) --</option>
                                    @foreach($petugasList as $petugas)
                                    <option value="{{ $petugas->id_petugas }}">
                                        {{ $petugas->nama_pengguna }} - {{ $petugas->pekerjaan ?? 'Tidak Ditentukan' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <!-- Status Disetujui atau Diproses: Admin hanya bisa Tolak (batalkan) -->
                        @if($pengaduan->status === 'Disetujui')
                        <div class="mb-6">
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            <strong>Pengaduan Disetujui:</strong> Pengaduan ini telah ditugaskan ke petugas. 
                                            Petugas akan memproses lebih lanjut.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <label class="block text-sm font-bold text-gray-700 mb-3">Tindakan (Opsional)</label>
                            <div class="max-w-md">
                                <label class="relative flex items-center p-5 bg-red-50 border-2 border-red-300 rounded-lg cursor-pointer hover:bg-red-100 hover:border-red-400 transition-all group">
                                    <input type="radio" name="status" value="Ditolak" class="form-radio text-red-600 h-6 w-6" required>
                                    <span class="ml-3 flex items-center">
                                        <i class="fas fa-times-circle text-red-600 text-2xl mr-3 group-hover:scale-110 transition-transform"></i>
                                        <div>
                                            <span class="text-base font-bold text-red-800 block">Batalkan/Tolak</span>
                                            <span class="text-xs text-red-600">Batalkan persetujuan jika ada kesalahan</span>
                                        </div>
                                    </span>
                                </label>
                            </div>
                        </div>
                        @endif

                        <div>
                            <label for="catatan_admin" class="block text-sm font-bold text-gray-700 mb-2">
                                Catatan Admin *
                            </label>
                            <textarea id="catatan_admin" name="catatan_admin" rows="4"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Tambahkan catatan untuk pengaduan ini..." required></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="window.history.back()"
                                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @else
        <!-- Riwayat Status -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-history mr-2"></i>
                    Riwayat Status
                </h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4">
                    @if($pengaduan->ditangani_admin)
                    {{-- Ditangani oleh Admin --}}
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ditangani Oleh</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-user-shield text-purple-500 mr-2"></i>
                                <span class="font-semibold">Admin: {{ $pengaduan->nama_admin }}</span>
                            </div>
                            <div class="flex items-center mt-1 ml-6">
                                <i class="fas fa-crown text-purple-500 mr-2 text-xs"></i>
                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full font-medium">
                                    Administrator
                                </span>
                            </div>
                        </dd>
                    </div>
                    @elseif($pengaduan->id_petugas && $pengaduan->petugas)
                    {{-- Ditangani oleh Petugas --}}
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Petugas yang Ditugaskan</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-user-hard-hat text-green-500 mr-2"></i>
                                <span class="font-semibold">{{ $pengaduan->petugas->nama }}</span>
                            </div>
                            @if($pengaduan->petugas->pekerjaan)
                            <div class="flex items-center mt-1 ml-6">
                                <i class="fas fa-briefcase text-blue-500 mr-2 text-xs"></i>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                    {{ $pengaduan->petugas->pekerjaan }}
                                </span>
                            </div>
                            @endif
                            @if($pengaduan->petugas->telp)
                            <div class="flex items-center mt-1 ml-6">
                                <i class="fas fa-phone text-gray-400 mr-2 text-xs"></i>
                                <span class="text-xs text-gray-600">{{ $pengaduan->petugas->telp }}</span>
                            </div>
                            @endif
                        </dd>
                    </div>
                    @endif
                    @if($pengaduan->tgl_verifikasi)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Verifikasi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_verifikasi)) }}</dd>
                    </div>
                    @endif
                    @if($pengaduan->tgl_selesai)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_selesai)) }}</dd>
                    </div>
                    @endif
                    @if($pengaduan->catatan_admin)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Catatan Admin</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->catatan_admin }}</dd>
                    </div>
                    @endif
                    @if($pengaduan->saran_petugas)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Saran Petugas</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->saran_petugas }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Timeline -->
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
                            <p class="text-sm font-semibold text-gray-800">Diverifikasi</p>
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

        <!-- Catatan Admin -->
        @if($pengaduan->catatan_admin)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-yellow-500 to-orange-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-sticky-note mr-2"></i>
                    Catatan Admin
                </h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-800 leading-relaxed">{{ $pengaduan->catatan_admin }}</p>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-gray-700 to-gray-900">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('admin.pengaduan.index') }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-list text-gray-500 mr-2"></i>
                        Lihat Semua Pengaduan
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

<script>
// Toggle Petugas Section (hanya untuk status Diajukan)
function togglePetugasSection(show) {
    const petugasSection = document.getElementById('petugas-section');
    if (petugasSection) {
        petugasSection.style.display = show ? 'block' : 'none';
    }
}
</script>
@endsection