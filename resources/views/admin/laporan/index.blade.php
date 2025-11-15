@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan</h1>
        <p class="text-gray-600 text-sm mt-1">Laporan pengaduan yang telah diproses</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs font-semibold">TOTAL PENGADUAN</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_pengaduan'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-list text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs font-semibold">DISETUJUI</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['disetujui'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-check text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs font-semibold">DITOLAK</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['ditolak'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-times text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation & Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <!-- Filter & Export -->
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Semua Status</option>
                        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium text-sm">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.laporan.index') }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors font-medium text-sm">
                        <i class="fas fa-redo mr-1"></i>Reset
                    </a>
                    <a href="{{ route('admin.laporan.export', request()->all()) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors font-medium text-sm">
                        <i class="fas fa-download mr-1"></i>Export
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div class="p-6">
            @include('admin.laporan.partials.pengaduan-table')
        </div>
    </div>
</div>
@endsection
