@extends('layouts.pengguna')

@section('title', 'Riwayat Pengaduan')
@section('header', 'Riwayat Pengaduan')
@section('subheader', 'Lihat semua pengaduan yang telah diselesaikan')

@section('content')
<style>
    .table-row-hover {
        transition: all 0.2s ease;
    }
    .table-row-hover:hover {
        background-color: #f9fafb;
        border-left: 4px solid #3b82f6;
    }
    .badge-completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .badge-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    .status-dot {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }
    .shimmer {
        animation: shimmer 2s infinite;
    }
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
</style>

<div class="space-y-6 animate-fade-in-up">
    <!-- Filter & Actions Section -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-history text-blue-600 mr-3"></i>
                    Daftar Riwayat Pengaduan
                </h3>
                <p class="text-sm text-gray-500 mt-1">{{ $riwayat->total() }} pengaduan tercatat</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button id="toggle-filter" type="button" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 font-medium text-sm hover:bg-gray-50 transition-all duration-200 shadow-sm">
                    <i class="fas fa-sliders-h mr-2"></i>
                    Filter
                </button>
            </div>
        </div>

        <!-- Filter Panel -->
        <div id="filter-panel" class="hidden pt-4 border-t border-gray-100 animate-fade-in-up">
            <form method="GET" action="{{ route('pengguna.riwayat.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i>
                        Dari Tanggal
                    </label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check text-blue-500 mr-1"></i>
                        Sampai Tanggal
                    </label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>
                        Lokasi
                    </label>
                    <input type="text" name="lokasi" value="{{ request('lokasi') }}" 
                           placeholder="Cari lokasi..." 
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search text-blue-500 mr-1"></i>
                        Kata Kunci
                    </label>
                    <input type="text" name="q" value="{{ request('q') }}" 
                           placeholder="Cari judul..." 
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                </div>
                <div class="lg:col-span-4 flex flex-wrap gap-3 mt-2">
                    <button type="submit" class="inline-flex items-center px-6 py-2 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-check mr-2"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('pengguna.riwayat.index') }}" class="inline-flex items-center px-6 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-sm hover:bg-gray-300 transition-all duration-200">
                        <i class="fas fa-redo mr-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-widest">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-widest">Judul Pengaduan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-widest">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-widest">Selesai</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($riwayat as $item)
                    <tr class="table-row-hover bg-white hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                #{{ $item->id_pengaduan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-blue-500 mr-2 text-xs"></i>
                                {{ \Carbon\Carbon::parse($item->tgl_pengajuan)->translatedFormat('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 max-w-xs">
                            <div class="truncate" title="{{ $item->nama_pengaduan }}">
                                {{ Str::limit($item->nama_pengaduan, 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                <i class="fas fa-map-marker-alt mr-1 text-orange-500"></i>
                                {{ Str::limit($item->lokasi, 20) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center">
                                <span class="status-dot w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                <span class="text-gray-600">{{ \Carbon\Carbon::parse($item->tgl_selesai)->translatedFormat('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('pengguna.riwayat.show', $item) }}" 
                               class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold text-xs hover:bg-blue-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-inbox text-gray-400 text-4xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Belum Ada Riwayat</h3>
                                    <p class="text-gray-500 text-sm mt-1">Pengaduan yang selesai akan muncul di sini</p>
                                </div>
                                <a href="{{ route('pengaduan.create') }}" class="inline-flex items-center px-4 py-2 mt-3 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition-all">
                                    <i class="fas fa-plus mr-2"></i>
                                    Buat Pengaduan Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <i class="fas fa-database mr-1 text-blue-500"></i>
                Menampilkan <span class="font-semibold">{{ $riwayat->count() }}</span> dari <span class="font-semibold">{{ $riwayat->total() }}</span> pengaduan
            </div>
            <div class="pagination">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggle-filter');
    const filterPanel = document.getElementById('filter-panel');
    
    if (toggleBtn && filterPanel) {
        toggleBtn.addEventListener('click', function () {
            filterPanel.classList.toggle('hidden');
            // Smooth animation
            if (!filterPanel.classList.contains('hidden')) {
                filterPanel.style.animation = 'slideDown 0.3s ease-out';
            }
        });
    }
});

// Add animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection