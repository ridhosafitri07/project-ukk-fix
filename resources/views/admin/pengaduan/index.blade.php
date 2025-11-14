@extends('layouts.admin')

@section('title', 'Manajemen Pengaduan')
@section('header', 'Manajemen Pengaduan')
@section('subheader', 'Kelola dan pantau semua pengaduan')

@section('content')
<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-100">Total Pengaduan</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['total'] }}</p>
            </div>
            <i class="fas fa-clipboard-list text-4xl text-blue-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-yellow-100">Diajukan</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['diajukan'] }}</p>
            </div>
            <i class="fas fa-paper-plane text-4xl text-yellow-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-100">Diproses</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['diproses'] }}</p>
            </div>
            <i class="fas fa-cog text-4xl text-indigo-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-green-100">Selesai</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['selesai'] }}</p>
            </div>
            <i class="fas fa-check-circle text-4xl text-green-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-red-100">Ditolak</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['ditolak'] }}</p>
            </div>
            <i class="fas fa-times-circle text-4xl text-red-200"></i>
        </div>
    </div>
</div>

<!-- Pengaduan List -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-list text-blue-500 mr-2"></i>
                Daftar Pengaduan
            </h3>
            <div class="flex flex-wrap gap-2">
                <!-- Filter Button -->
                <button onclick="openFilterModal()" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 flex items-center transition-colors shadow-md">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                    @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'lokasi', 'petugas']))
                    <span class="ml-2 px-2 py-0.5 bg-white text-purple-600 rounded-full text-xs font-bold">
                        {{ collect([request('status'), request('tanggal_dari'), request('tanggal_sampai'), request('lokasi'), request('petugas')])->filter()->count() }}
                    </span>
                    @endif
                </button>
                
                <!-- Reset Filter Button (only show if filter active) -->
                @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'lokasi', 'petugas']))
                <a href="{{ route('admin.pengaduan.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 flex items-center transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
                @endif
                
                <!-- Export Button with Dropdown -->
                <div class="relative" x-data="{ exportOpen: false }">
                    <button @click="exportOpen = !exportOpen" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center transition-colors shadow-md">
                        <i class="fas fa-download mr-2"></i>
                        Export
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="exportOpen" 
                         @click.away="exportOpen = false"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-10">
                        <a href="{{ route('admin.pengaduan.export-excel', request()->query()) }}" 
                           class="flex items-center px-4 py-2 hover:bg-green-50 transition-colors">
                            <i class="fas fa-file-excel text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-700">Export ke Excel</span>
                        </a>
                        <a href="{{ route('admin.pengaduan.export-pdf', request()->query()) }}" 
                           class="flex items-center px-4 py-2 hover:bg-red-50 transition-colors">
                            <i class="fas fa-file-pdf text-red-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-700">Export ke PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'lokasi', 'petugas']))
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="text-sm text-gray-600 font-medium">Filter aktif:</span>
            @if(request('status'))
            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium flex items-center">
                <i class="fas fa-tag mr-1"></i>
                Status: {{ request('status') }}
            </span>
            @endif
            @if(request('tanggal_dari') || request('tanggal_sampai'))
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium flex items-center">
                <i class="fas fa-calendar mr-1"></i>
                {{ request('tanggal_dari') ?? '...' }} - {{ request('tanggal_sampai') ?? '...' }}
            </span>
            @endif
            @if(request('lokasi'))
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium flex items-center">
                <i class="fas fa-map-marker-alt mr-1"></i>
                Lokasi: {{ request('lokasi') }}
            </span>
            @endif
            @if(request('petugas'))
            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium flex items-center">
                <i class="fas fa-user mr-1"></i>
                Petugas: {{ request('petugas') }}
            </span>
            @endif
        </div>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Pengadu
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Judul
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Sumber Barang
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Lokasi
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Petugas
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($pengaduan as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-gray-900">#{{ $item->id_pengaduan }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600 flex items-center">
                            <i class="far fa-calendar text-gray-400 mr-2"></i>
                            {{ date('d/m/Y', strtotime($item->tgl_pengajuan)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-bold text-xs">{{ strtoupper(substr($item->user->nama_pengguna, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->user->nama_pengguna }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 font-medium">{{ Str::limit($item->nama_pengaduan, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($item->id_item)
                            <span class="text-sm text-green-700">Dari Data Existing</span>
                        @elseif($item->temporary_items && $item->temporary_items->count())
                            @php $tmp = $item->temporary_items->first(); @endphp
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-orange-700">Barang Baru</span>
                                <form method="POST" action="{{ route('admin.pengaduan.approve-temporary', $tmp->id_item ?? $tmp->id) }}">
                                    @csrf
                                    <input type="hidden" name="catatan_admin" value="Disetujui dan dipromosikan">
                                    <button type="submit" class="ml-2 px-2 py-1 bg-green-500 text-white rounded text-xs">Approve</button>
                                </form>
                            </div>
                        @else
                            <span class="text-sm text-gray-500 italic">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            {{ Str::limit($item->lokasi, 20) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->petugas)
                        {{-- Ditangani oleh Petugas --}}
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900 flex items-center">
                                <i class="fas fa-user-cog text-green-500 mr-1 text-xs"></i>
                                {{ Str::limit($item->petugas->nama, 20) }}
                            </span>
                            @if($item->petugas->pekerjaan)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full mt-1 inline-flex items-center w-fit">
                                <i class="fas fa-briefcase mr-1"></i>
                                {{ $item->petugas->pekerjaan }}
                            </span>
                            @endif
                        </div>
                        @else
                        {{-- Belum Ditugaskan --}}
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 flex items-center">
                                <i class="fas fa-user-clock text-gray-400 mr-1 text-xs"></i>
                                Belum Ditugaskan
                            </span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full mt-1 inline-flex items-center w-fit">
                                <i class="fas fa-hourglass-half mr-1"></i>
                                Menunggu
                            </span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($item->status === 'Diajukan') bg-yellow-100 text-yellow-800
                            @elseif($item->status === 'Disetujui') bg-green-100 text-green-800
                            @elseif($item->status === 'Ditolak') bg-red-100 text-red-800
                            @elseif($item->status === 'Diproses') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.pengaduan.show', $item) }}" 
                           class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">Tidak ada pengaduan</p>
                        <p class="text-gray-400 text-sm mt-2">Pengaduan akan muncul di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $pengaduan->appends(request()->query())->links() }}
    </div>
</div>

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