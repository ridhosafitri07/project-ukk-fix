@extends('layouts.admin')

@section('title', 'Master Barang')
@section('header', 'Master Barang/Item')
@section('subheader', 'Kelola data barang dan item')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-purple-600 to-pink-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Total Barang</p>
                    <p class="text-4xl font-bold mt-2">{{ $statistics['total'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Item terdaftar</p>
                </div>
                <div class="bg-purple-500 rounded-full p-4">
                    <i class="fas fa-boxes text-4xl opacity-80"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Sudah di Lokasi</p>
                    <p class="text-4xl font-bold mt-2">{{ $statistics['dengan_lokasi'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Barang terdistribusi</p>
                </div>
                <div class="bg-emerald-400 rounded-full p-4">
                    <i class="fas fa-check-circle text-4xl opacity-80"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Belum di Lokasi</p>
                    <p class="text-4xl font-bold mt-2">{{ $statistics['tanpa_lokasi'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Perlu distribusi</p>
                </div>
                <div class="bg-amber-400 rounded-full p-4">
                    <i class="fas fa-exclamation-triangle text-4xl opacity-80"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-500 px-6 py-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-list mr-2"></i>
                        Daftar Barang
                    </h3>
                    <p class="text-purple-100 text-sm mt-1">Kelola semua barang dan item</p>
                </div>
                <a href="{{ route('admin.master-barang.create') }}" 
                   class="bg-white hover:bg-pink-50 text-purple-600 font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-200 flex items-center space-x-2 w-fit">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Barang Baru</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 bg-purple-50 border-b border-purple-200">
            <form method="GET" action="{{ route('admin.master-barang.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="🔍 Cari nama barang atau deskripsi..." 
                               class="w-full pl-12 pr-4 py-3 border-2 border-purple-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        <i class="fas fa-search absolute left-4 top-4 text-purple-400"></i>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition shadow-lg">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('admin.master-barang.index') }}" 
                           class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition shadow-lg">
                            <i class="fas fa-redo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-purple-200">
                <thead class="bg-gradient-to-r from-purple-600 to-pink-500 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-1"></i>
                            No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-box mr-1"></i>
                            Nama Barang
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-1"></i>
                            Deskripsi
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Jumlah Lokasi
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-1"></i>
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-purple-100">
                    @forelse($items as $index => $item)
                        <tr class="hover:bg-purple-50/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-900 font-medium">
                                {{ $items->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-cube text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-purple-900">{{ $item->nama_item }}</p>
                                        <p class="text-xs text-purple-600">ID: {{ $item->id_item }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-purple-700 max-w-md truncate">
                                    {{ $item->deskripsi ?? '-' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full {{ $item->lokasis_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-600' }}">
                                    <i class="fas fa-map-marked-alt mr-1"></i>
                                    {{ $item->lokasis_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.master-barang.edit', $item->id_item) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition shadow" 
                                   title="Edit">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-purple-300 mb-4"></i>
                                    <p class="text-purple-700 text-lg font-medium">Tidak ada data barang</p>
                                    <p class="text-purple-500 text-sm mt-2">Mulai tambahkan barang baru</p>
                                    <a href="{{ route('admin.master-barang.create') }}" 
                                       class="mt-4 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                        <i class="fas fa-plus mr-2"></i>Tambah Barang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="px-6 py-4 bg-purple-50 border-t border-purple-200">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection
