@extends('layouts.pengguna')

@section('title', 'Riwayat Pengaduan')
@section('header', 'Riwayat Pengaduan')
@section('subheader', 'Daftar pengaduan yang telah selesai atau diproses')

@section('content')
<style>
    .status-badge {
        @apply inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full;
    }
    .status-badge.draft     { @apply bg-yellow-100 text-yellow-800; }
    .status-badge.process   { @apply bg-blue-100 text-blue-800; }
    .status-badge.approved  { @apply bg-green-100 text-green-800; }
    .status-badge.rejected  { @apply bg-red-100 text-red-800; }
    .status-badge.completed { @apply bg-emerald-100 text-emerald-800; }

    .status-dot {
        @apply w-1.5 h-1.5 rounded-full mr-1;
    }
    .status-dot.draft     { @apply bg-yellow-500; }
    .status-dot.process   { @apply bg-blue-500; }
    .status-dot.approved  { @apply bg-green-500; }
    .status-dot.rejected  { @apply bg-red-500; }
    .status-dot.completed { @apply bg-emerald-500; }
</style>

<div class="space-y-6">
    <!-- Header & Filter Toggle -->
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200">
        <div class="px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-history text-purple-600 mr-2"></i>
                    Riwayat Pengaduan
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $riwayat->total() }} pengaduan tercatat
                </p>
            </div>
            <button id="toggle-filter" type="button"
                class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                <i class="fas fa-filter text-purple-600"></i>
                <span class="hidden sm:inline">Filter</span>
            </button>
        </div>

        <!-- Filter Panel -->
        <div id="filter-panel" class="hidden p-6 border-t border-gray-100">
            <form method="GET" action="{{ route('pengguna.riwayat.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm focus:ring-2 focus:ring-purple-200 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm focus:ring-2 focus:ring-purple-200 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ request('lokasi') }}"
                        placeholder="Cari lokasi..."
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm focus:ring-2 focus:ring-purple-200 focus:border-purple-500">
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kata Kunci</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Judul/deskripsi..."
                        class="w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm focus:ring-2 focus:ring-purple-200 focus:border-purple-500">
                </div>
                <div class="lg:col-span-4 flex flex-wrap gap-3 pt-1">
                    <button type="submit"
                        class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-sm hover:shadow transition-colors">
                        <i class="fas fa-check mr-1.5"></i> Terapkan
                    </button>
                    <a href="{{ route('pengguna.riwayat.index') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        <i class="fas fa-undo mr-1.5"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            @if($riwayat->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">ID</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide w-32">Tanggal</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Judul & Deskripsi</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide w-32">Lokasi</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide w-28">Status</th>
                            <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide w-20">Bukti</th>
                            <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($riwayat as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-700">#{{ $item->id_pengaduan }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <i class="far fa-calendar-alt text-gray-400 mr-1.5"></i>
                                    {{ \Carbon\Carbon::parse($item->tgl_pengajuan)->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900 text-sm max-w-md truncate">{{ $item->nama_pengaduan }}</p>
                                    <p class="text-gray-600 text-xs mt-1 line-clamp-2">{{ $item->deskripsi }}</p>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ Str::limit($item->lokasi, 20) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusClass = match($item->status) {
                                            'Diajukan' => 'draft',
                                            'Disetujui' => 'approved',
                                            'Diproses' => 'process',
                                            'Selesai' => 'completed',
                                            'Ditolak' => 'rejected',
                                            default => 'draft',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        <span class="status-dot {{ $statusClass }}"></span>
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($item->foto)
                                        <button type="button"
                                            data-modal-toggle="modal-{{ $item->id_pengaduan }}"
                                            class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center justify-center gap-1 mx-auto"
                                            title="Lihat bukti foto">
                                            <i class="fas fa-image"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div id="modal-{{ $item->id_pengaduan }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                                            @click.self="$el.classList.add('hidden')">
                                            <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[80vh] flex flex-col">
                                                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                                                    <h3 class="font-semibold text-gray-900">Bukti Foto Pengaduan</h3>
                                                    <button type="button" class="text-gray-500 hover:text-gray-700">
                                                        <i class="fas fa-times text-xl" onclick="this.closest('.fixed').classList.add('hidden')"></i>
                                                    </button>
                                                </div>
                                                <div class="p-4 overflow-auto flex-1 flex items-center justify-center bg-gray-50">
                                                    <img src="{{ asset('storage/' . $item->foto) }}"
                                                         alt="Bukti Pengaduan"
                                                         class="max-w-full max-h-[70vh] rounded-xl object-contain shadow">
                                                </div>
                                                <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-end">
                                                    <a href="{{ asset('storage/' . $item->foto) }}" target="_blank"
                                                       class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg flex items-center gap-1.5">
                                                        <i class="fas fa-download"></i> Unduh
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">–</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('pengguna.riwayat.show', $item) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
                                       title="Lihat detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center">
                    <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Belum Ada Riwayat Pengaduan</h3>
                    <p class="text-gray-500 mt-1 max-w-md mx-auto">
                        Pengaduan yang telah selesai akan muncul di sini.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('pengaduan.create') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-sm hover:shadow transition-colors">
                            <i class="fas fa-plus"></i> Buat Pengaduan Baru
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($riwayat->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium text-gray-900">{{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }}</span>
                    dari <span class="font-medium text-gray-900">{{ $riwayat->total() }}</span> pengaduan
                </p>
                <div>
                    {{ $riwayat->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-filter');
    const filterPanel = document.getElementById('filter-panel');

    toggleBtn?.addEventListener('click', () => {
        filterPanel.classList.toggle('hidden');
        if (!filterPanel.classList.contains('hidden')) {
            filterPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });

    // Modal toggle
    document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-modal-toggle');
            document.getElementById(id)?.classList.remove('hidden');
        });
    });

    // Close modals on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                modal.classList.add('hidden');
            });
        }
    });
});
</script>
@endpush
@endsection