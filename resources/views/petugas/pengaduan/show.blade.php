@extends('layouts.petugas')

@section('title', 'Detail Pengaduan #' . $pengaduan->id_pengaduan)
@section('header', 'Detail Pengaduan #' . $pengaduan->id_pengaduan)
@section('subheader', 'Informasi lengkap dan pengelolaan pengaduan')

@section('content')
<div class="space-y-8">
    
    <!-- Back Button -->
    <a href="{{ route('petugas.pengaduan.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar
    </a>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg animate-fade-in-up">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
            <p class="text-sm text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg animate-fade-in-up">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
            <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Header Info Card -->
    <div class="card p-8 animate-fade-in-up">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div class="flex-1">
                <h2 class="text-3xl font-bold text-slate-900 mb-2">{{ $pengaduan->nama_pengaduan }}</h2>
                <p class="text-slate-600 mb-4">{{ $pengaduan->deskripsi }}</p>
                
                <div class="flex flex-wrap gap-4 mt-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase">ID Pengaduan</p>
                        <p class="text-lg font-bold text-slate-900">#{{ $pengaduan->id_pengaduan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase">Tanggal</p>
                        <p class="text-sm text-slate-700 flex items-center">
                            <i class="far fa-calendar text-blue-600 mr-2"></i>
                            {{ date('d M Y H:i', strtotime($pengaduan->tgl_pengajuan)) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase">Lokasi</p>
                        <p class="text-sm text-slate-700 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            {{ $pengaduan->lokasi }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-3 md:items-end">
                @php
                    $statusColors = [
                        'Diajukan' => 'yellow',
                        'Disetujui' => 'green',
                        'Diproses' => 'blue',
                        'Selesai' => 'purple',
                        'Ditolak' => 'red'
                    ];
                    $color = $statusColors[$pengaduan->status] ?? 'gray';
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-{{ $color }}-100 text-{{ $color }}-800">
                    <i class="fas fa-circle text-{{ $color }}-600 mr-2 text-xs"></i>
                    {{ $pengaduan->status }}
                </span>
                
                <div class="text-right text-sm">
                    <p class="text-slate-600">Pengadu</p>
                    <p class="font-medium text-slate-900">{{ $pengaduan->user->nama_pengguna }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Update Status & Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Status Update Form (Priority - Left/Full) -->
        @if(in_array($pengaduan->status, ['Diajukan', 'Disetujui', 'Diproses']))
        <div class="lg:col-span-2 card p-8 animate-fade-in-up animate-delay-1 bg-gradient-to-br from-blue-50 to-white border-l-4 border-blue-600">
            <h3 class="text-2xl font-bold text-slate-900 mb-2 flex items-center">
                <i class="fas fa-tasks text-blue-600 mr-3"></i>
                Update Status Pekerjaan
            </h3>
            <p class="text-slate-600 text-sm mb-6">Kelola status pengaduan dan berikan keterangan pekerjaan</p>

            <form id="updateStatusForm" action="{{ route('petugas.pengaduan.update-status', $pengaduan) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="status" id="status-input" value="">
                
                <!-- Status Progression -->
                <div class="space-y-4">
                    @if($pengaduan->status === 'Diajukan')
                    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Tahap Persetujuan:</strong> Pengaduan menunggu keputusan Admin untuk dilanjutkan.
                        </p>
                    </div>
                    @elseif($pengaduan->status === 'Disetujui')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button type="button" onclick="confirmAction('Diproses')"
                                class="group relative p-6 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center text-center">
                            <i class="fas fa-play text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-lg">Mulai Proses</span>
                            <span class="text-xs opacity-90 mt-2">Ambil tugas ini dan mulai bekerja</span>
                        </button>

                        <button type="button" onclick="confirmAction('Selesai')"
                                class="group relative p-6 bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center text-center">
                            <i class="fas fa-check-double text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-lg">Selesai Langsung</span>
                            <span class="text-xs opacity-90 mt-2">Pengaduan sudah selesai ditangani</span>
                        </button>
                    </div>
                    @elseif($pengaduan->status === 'Diproses')
                    <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-cog fa-spin mr-2"></i>
                            <strong>Sedang Diproses:</strong> Setelah perbaikan selesai, tandai pengaduan sebagai selesai.
                        </p>
                    </div>
                    <button type="button" onclick="confirmAction('Selesai')"
                            class="group w-full p-6 bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center text-center">
                        <i class="fas fa-check-circle text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-lg">Selesaikan Tugas</span>
                        <span class="text-xs opacity-90 mt-2">Perbaikan telah selesai dilakukan</span>
                    </button>
                    @endif
                </div>

                <!-- Notes Section -->
                <div>
                    <label for="saran_petugas" class="block text-sm font-semibold text-slate-800 mb-3">
                        <i class="fas fa-sticky-note text-blue-600 mr-2"></i>
                        Catatan Pekerjaan (Opsional)
                    </label>
                    <textarea id="saran_petugas" name="saran_petugas" rows="4"
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                              placeholder="Tambahkan catatan, saran, atau keterangan pekerjaan yang telah dilakukan...">{{ old('saran_petugas', $pengaduan->saran_petugas) }}</textarea>
                    <p class="text-xs text-slate-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Catatan ini akan tersimpan dalam riwayat pengaduan
                    </p>
                </div>
            </form>
        </div>
        @endif

        <!-- Details Sidebar -->
        <div class="space-y-6 animate-fade-in-up animate-delay-2">
            
            <!-- Details Card -->
            <div class="card p-6">
                <h4 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                    Detail Lengkap
                </h4>
                
                <div class="space-y-4">
                    @if($pengaduan->foto)
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Foto Pendukung</p>
                        <img src="{{ asset('storage/' . $pengaduan->foto) }}" 
                             alt="Foto Pengaduan" 
                             class="w-full rounded-lg shadow-md hover:shadow-lg cursor-pointer transition-shadow"
                             onclick="window.open(this.src, '_blank')">
                    </div>
                    @endif

                    @if($pengaduan->catatan_admin)
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Catatan Admin</p>
                        <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                            <p class="text-sm text-slate-700">{{ $pengaduan->catatan_admin }}</p>
                        </div>
                    </div>
                    @endif

                    @if($pengaduan->temporary_items && $pengaduan->temporary_items->count() > 0)
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Permintaan Barang</p>
                        <div class="p-3 bg-purple-50 border-l-4 border-purple-500 rounded-r-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-box text-purple-600 flex-shrink-0 mt-1"></i>
                                <div class="text-sm">
                                    <p class="font-medium text-slate-900">{{ $pengaduan->temporary_items->first()->nama_barang_baru }}</p>
                                    <p class="text-xs text-slate-600 mt-1">Status: <strong>{{ $pengaduan->temporary_items->first()->status_permintaan }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h4 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                    <i class="fas fa-lightning-bolt text-indigo-600 mr-2"></i>
                    Aksi Cepat
                </h4>
                
                <div class="space-y-2">
                    @if($pengaduan->status !== 'Selesai')
                    <a href="{{ route('petugas.item-request.create', $pengaduan) }}" 
                       class="flex items-center justify-between p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                        <span class="text-sm font-medium text-purple-700 flex items-center">
                            <i class="fas fa-plus-circle text-purple-600 mr-2"></i>
                            Ajukan Permintaan Barang
                        </span>
                        <i class="fas fa-arrow-right text-purple-400 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    @endif
                    
                    <button onclick="window.print()" 
                       class="w-full flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                        <span class="text-sm font-medium text-slate-700 flex items-center">
                            <i class="fas fa-print text-slate-600 mr-2"></i>
                            Cetak Detail
                        </span>
                        <i class="fas fa-arrow-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card p-6">
                <h4 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                    <i class="fas fa-timeline text-emerald-600 mr-2"></i>
                    Timeline
                </h4>
                
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-blue-600 text-sm"></i>
                            </div>
                            <div class="w-0.5 bg-slate-200 flex-1 my-2"></div>
                        </div>
                        <div class="pb-4">
                            <p class="font-semibold text-slate-900">Diajukan</p>
                            <p class="text-xs text-slate-600 mt-1">{{ date('d M Y H:i', strtotime($pengaduan->tgl_pengajuan)) }}</p>
                        </div>
                    </div>

                    @if($pengaduan->tgl_verifikasi)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div class="w-0.5 bg-slate-200 flex-1 my-2"></div>
                        </div>
                        <div class="pb-4">
                            <p class="font-semibold text-slate-900">Diverifikasi</p>
                            <p class="text-xs text-slate-600 mt-1">{{ date('d M Y H:i', strtotime($pengaduan->tgl_verifikasi)) }}</p>
                        </div>
                    </div>
                    @endif

                    @if($pengaduan->status === 'Diproses')
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-cog text-yellow-600 text-sm animate-spin"></i>
                            </div>
                            <div class="w-0.5 bg-slate-300 flex-1 my-2"></div>
                        </div>
                        <div class="pb-4">
                            <p class="font-semibold text-slate-900">Sedang Diproses</p>
                            <p class="text-xs text-slate-600 mt-1">Dikerjakan</p>
                        </div>
                    </div>
                    @endif

                    @if($pengaduan->tgl_selesai)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-flag-checkered text-purple-600 text-sm"></i>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Selesai</p>
                            <p class="text-xs text-slate-600 mt-1">{{ date('d M Y H:i', strtotime($pengaduan->tgl_selesai)) }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmAction(status) {
    let message = '';
    let icon = '';
    let confirmButtonText = '';
    let confirmButtonColor = '';
    
    if (status === 'Diproses') {
        message = 'Anda akan mengambil tugas ini dan memulai proses perbaikan. Lanjutkan?';
        icon = 'question';
        confirmButtonText = 'Ya, Mulai Proses!';
        confirmButtonColor = '#3B82F6';
    } else if (status === 'Selesai') {
        message = 'Tandai pengaduan ini sebagai selesai?';
        icon = 'question';
        confirmButtonText = 'Ya, Sudah Selesai!';
        confirmButtonColor = '#10B981';
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
            document.getElementById('status-input').value = status;
            document.getElementById('updateStatusForm').submit();
            
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