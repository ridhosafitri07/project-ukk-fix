@extends('layouts.admin')

@section('title', 'Manajemen Pengaduan')
@section('header', 'Manajemen Pengaduan')
@section('subheader', 'Kelola dan pantau semua pengaduan')

@section('content')
<style>
    .stat-card-professional {
        background: white;
        border-radius: 0.5rem;
        padding: 1.25rem;
        border: 1px solid #d1d5db;
        transition: all 0.2s ease;
    }
    
    .stat-card-professional:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .table-header-professional {
        background: linear-gradient(to right, #1f2937 0%, #374151 100%);
    }
</style>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="stat-card-professional">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-600 text-xs font-semibold mb-1">TOTAL PENGADUAN</p>
                <p class="text-3xl font-bold text-gray-900">{{ $statistics['total'] }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-lg">
                <i class="fas fa-list"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card-professional">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-600 text-xs font-semibold mb-1">SELESAI</p>
                <p class="text-3xl font-bold text-gray-900">{{ $statistics['selesai'] }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-lg">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card-professional">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-600 text-xs font-semibold mb-1">DITOLAK</p>
                <p class="text-3xl font-bold text-gray-900">{{ $statistics['ditolak'] }}</p>
            </div>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-lg">
                <i class="fas fa-times"></i>
            </div>
        </div>
    </div>
</div>

<!-- Pengaduan List -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="table-header-professional p-5 text-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h3 class="font-semibold text-sm tracking-wide">
                DAFTAR PENGADUAN
            </h3>
            <div class="flex flex-wrap gap-2">
                <button onclick="openFilterModal()" 
                        class="inline-flex items-center px-3 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-all text-sm font-medium border border-white/20">
                    <i class="fas fa-filter mr-2 text-xs"></i>
                    Filter
                    @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'lokasi', 'petugas']))
                    <span class="ml-1 px-2 py-0.5 bg-yellow-300 text-gray-800 rounded-full text-xs font-bold">
                        {{ collect([request('status'), request('tanggal_dari'), request('tanggal_sampai'), request('lokasi'), request('petugas')])->filter()->count() }}
                    </span>
                    @endif
                </button>
                
                @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'lokasi', 'petugas']))
                <a href="{{ route('admin.pengaduan.index') }}" 
                   class="inline-flex items-center px-3 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-all text-sm font-medium border border-white/20">
                    <i class="fas fa-times mr-1 text-xs"></i>
                    Reset
                </a>
                @endif
                
                <div class="relative" x-data="{ exportOpen: false }">
                    <button @click="exportOpen = !exportOpen" 
                            class="inline-flex items-center px-3 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-all text-sm font-medium border border-white/20">
                        <i class="fas fa-download mr-2 text-xs"></i>
                        Export
                    </button>
                    
                    <div x-show="exportOpen" 
                         @click.away="exportOpen = false"
                         x-transition
                         class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                        <a href="{{ route('admin.pengaduan.export-excel', request()->query()) }}" 
                           class="flex items-center px-4 py-2 hover:bg-green-50 transition-colors text-gray-700 text-sm">
                            <i class="fas fa-file-excel text-green-600 mr-2"></i>
                            Excel
                        </a>
                        <a href="{{ route('admin.pengaduan.export-pdf', request()->query()) }}" 
                           class="flex items-center px-4 py-2 hover:bg-red-50 transition-colors text-gray-700 text-sm">
                            <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                            PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Filters -->
    @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'lokasi', 'petugas']))
    <div class="px-6 py-4 bg-purple-50 border-b border-purple-100 flex flex-wrap gap-2">
        @if(request('status'))
        <span class="px-3 py-1 bg-purple-200 text-purple-900 rounded-full text-xs font-bold">Status: {{ request('status') }}</span>
        @endif
        @if(request('tanggal_dari') || request('tanggal_sampai'))
        <span class="px-3 py-1 bg-blue-200 text-blue-900 rounded-full text-xs font-bold">{{ request('tanggal_dari') ?? '...' }} - {{ request('tanggal_sampai') ?? '...' }}</span>
        @endif
        @if(request('lokasi'))
        <span class="px-3 py-1 bg-green-200 text-green-900 rounded-full text-xs font-bold">Lokasi: {{ request('lokasi') }}</span>
        @endif
        @if(request('petugas'))
        <span class="px-3 py-1 bg-orange-200 text-orange-900 rounded-full text-xs font-bold">Petugas: {{ request('petugas') }}</span>
        @endif
    </div>
    @endif
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">Pengadu</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">Judul</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">Sumber</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">Lokasi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengaduan as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-purple-600">#{{ $item->id_pengaduan }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ date('d/m/Y', strtotime($item->tgl_pengajuan)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">{{ Str::limit($item->user->nama_pengguna, 15) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900 max-w-xs truncate">{{ Str::limit($item->nama_pengaduan, 30) }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($item->id_item)
                            <span class="text-emerald-600 font-medium">Existing</span>
                        @elseif($item->temporary_items && $item->temporary_items->count())
                            <span class="text-orange-600 font-medium">Baru</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ Str::limit($item->lokasi, 15) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            @if($item->status === 'Diajukan') bg-yellow-100 text-yellow-700
                            @elseif($item->status === 'Disetujui') bg-green-100 text-green-700
                            @elseif($item->status === 'Ditolak') bg-red-100 text-red-700
                            @elseif($item->status === 'Diproses') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.pengaduan.show', $item) }}" 
                           class="inline-flex items-center px-3 py-2 text-xs font-medium text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <p class="text-gray-500">Tidak ada pengaduan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
        {{ $pengaduan->appends(request()->query())->links() }}
    </div>
</div>

<!-- Filter Modal -->
<div id="filterModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="table-header-professional px-6 py-4 flex items-center justify-between text-white">
            <h3 class="font-bold">Filter Pengaduan</h3>
            <button onclick="closeFilterModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('admin.pengaduan.index') }}" method="GET" class="p-6 space-y-4">
            <!-- Filter Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>

            <!-- Filter Lokasi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi</label>
                <select name="lokasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasis as $lokasi)
                    <option value="{{ $lokasi }}" {{ request('lokasi') == $lokasi ? 'selected' : '' }}>{{ $lokasi }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Petugas -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Petugas</label>
                <select name="petugas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Semua Petugas</option>
                    <option value="belum_ditugaskan" {{ request('petugas') == 'belum_ditugaskan' ? 'selected' : '' }}>Belum Ditugaskan</option>
                    @foreach($petugas as $p)
                    <option value="petugas_{{ $p->id_petugas }}" {{ request('petugas') == 'petugas_'.$p->id_petugas ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <button type="button" onclick="closeFilterModal()" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Terapkan
                </button>
            </div>
        </form>
    </div>


<script>
function openFilterModal() {
    document.getElementById('filterModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeFilterModal() {
    document.getElementById('filterModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('filterModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeFilterModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFilterModal();
    }
});
</script>

<!-- Filter Modal -->
<div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4 flex items-center justify-between rounded-t-xl">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-filter mr-2"></i>
                Filter Pengaduan
            </h3>
            <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('admin.pengaduan.index') }}" method="GET" class="p-6 space-y-6">
            <!-- 1. Filter Status -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-tags text-purple-500 mr-2"></i>
                    Status Pengaduan
                </label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all">
                    <option value="">-- Semua Status --</option>
                    <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- 2. Filter Tanggal -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                    Rentang Tanggal
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" 
                               name="tanggal_dari" 
                               value="{{ request('tanggal_dari') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" 
                               name="tanggal_sampai" 
                               value="{{ request('tanggal_sampai') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Kosongkan untuk menampilkan semua tanggal
                </p>
            </div>

            <!-- 3. Filter Lokasi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                    Lokasi
                </label>
                <select name="lokasi" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                    <option value="">-- Semua Lokasi --</option>
                    @foreach($lokasis as $lokasi)
                    <option value="{{ $lokasi }}" {{ request('lokasi') == $lokasi ? 'selected' : '' }}>
                        {{ $lokasi }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- 4. Filter Petugas -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-user-cog text-orange-500 mr-2"></i>
                    Petugas / Admin
                </label>
                <select name="petugas" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all">
                    <option value="">-- Semua Petugas --</option>
                    <option value="belum_ditugaskan" {{ request('petugas') == 'belum_ditugaskan' ? 'selected' : '' }}>
                        Belum Ditugaskan
                    </option>
                    <optgroup label="Petugas">
                        @foreach($petugas as $p)
                        <option value="petugas_{{ $p->id_petugas }}" {{ request('petugas') == 'petugas_'.$p->id_petugas ? 'selected' : '' }}>
                            {{ $p->nama }} @if($p->pekerjaan) ({{ $p->pekerjaan }}) @endif
                        </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between pt-6 border-t-2 border-gray-200">
                <button type="button" 
                        onclick="closeFilterModal()" 
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </button>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition duration-200">
                    <i class="fas fa-check mr-2"></i>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openFilterModal() {
    document.getElementById('filterModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeFilterModal() {
    document.getElementById('filterModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('filterModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeFilterModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFilterModal();
    }
});
</script>

@endsection