@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="p-6" x-data="{ activeTab: '{{ $type ?? 'pengaduan' }}' }">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">📊 Laporan</h1>
        <p class="text-slate-600 text-sm mt-1">Laporan pengaduan dan permintaan barang baru</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pengaduan</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_pengaduan'] }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Permintaan</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_permintaan'] }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Disetujui</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['disetujui'] }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Ditolak</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['ditolak'] }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="border-b border-slate-200">
            <div class="flex">
                <button @click="activeTab = 'pengaduan'; window.location.href = '{{ route('admin.laporan.index', ['type' => 'pengaduan']) }}'" 
                        :class="activeTab === 'pengaduan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-6 py-3 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Laporan Pengaduan
                </button>
                <button @click="activeTab = 'permintaan'; window.location.href = '{{ route('admin.laporan.index', ['type' => 'permintaan']) }}'"
                        :class="activeTab === 'permintaan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-6 py-3 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-box mr-2"></i>
                    Laporan Permintaan Barang
                </button>
            </div>
        </div>

        <!-- Filter & Export -->
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap gap-3 items-end">
                <input type="hidden" name="type" :value="activeTab">
                
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        @if($type == 'pengaduan')
                        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        @else
                        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        @endif
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.laporan.index', ['type' => $type]) }}" 
                       class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors font-medium">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                    <a href="{{ route('admin.laporan.export', array_merge(['type' => $type], request()->all())) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <i class="fas fa-file-excel mr-2"></i>Export
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div class="p-6">
            @if($type == 'pengaduan')
                @include('admin.laporan.partials.pengaduan-table')
            @else
                @include('admin.laporan.partials.permintaan-table')
            @endif
        </div>
    </div>
</div>
@endsection
