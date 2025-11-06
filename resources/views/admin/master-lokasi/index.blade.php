@extends('layouts.admin')

@section('title', 'Master Lokasi')
@section('header', 'Master Lokasi/Ruangan')
@section('subheader', 'Kelola data lokasi dan ruangan')

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
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Total Lokasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total'] }}</p>
                </div>
                <i class="fas fa-map-marked-alt text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Kelas</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['kelas'] }}</p>
                </div>
                <i class="fas fa-chalkboard text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Lab</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['lab'] }}</p>
                </div>
                <i class="fas fa-flask text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Kantor</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['kantor'] }}</p>
                </div>
                <i class="fas fa-building text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Umum</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['umum'] }}</p>
                </div>
                <i class="fas fa-door-open text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Area Luar</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['area_luar'] }}</p>
                </div>
                <i class="fas fa-tree text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-list mr-2"></i>
                        Daftar Lokasi
                    </h3>
                    <p class="text-blue-100 text-sm mt-1">Kelola semua lokasi dan ruangan</p>
                </div>
                <a href="{{ route('admin.master-lokasi.create') }}" 
                   class="bg-white hover:bg-blue-50 text-blue-600 font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-200 flex items-center space-x-2 w-fit">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Lokasi Baru</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 bg-gray-50 border-b">
            <form method="GET" action="{{ route('admin.master-lokasi.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="🔍 Cari nama lokasi..." 
                               class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <select name="kategori" 
                            class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        <option value="">🏢 Semua Kategori</option>
                        <option value="kelas" {{ request('kategori') == 'kelas' ? 'selected' : '' }}>Kelas</option>
                        <option value="lab" {{ request('kategori') == 'lab' ? 'selected' : '' }}>Lab</option>
                        <option value="kantor" {{ request('kategori') == 'kantor' ? 'selected' : '' }}>Kantor</option>
                        <option value="umum" {{ request('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="area_luar" {{ request('kategori') == 'area_luar' ? 'selected' : '' }}>Area Luar</option>
                    </select>
                    
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-lg">
                        <i class="fas fa-filter"></i>
                    </button>
                    
                    @if(request('search') || request('kategori'))
                        <a href="{{ route('admin.master-lokasi.index') }}" 
                           class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition shadow-lg">
                            <i class="fas fa-redo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Barang</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lokasis as $index => $lokasi)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $lokasis->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-{{ $lokasi->kategori_badge['color'] }}-100 flex items-center justify-center mr-3">
                                        <i class="fas {{ $lokasi->kategori_badge['icon'] }} text-{{ $lokasi->kategori_badge['color'] }}-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $lokasi->nama_lokasi }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $lokasi->id_lokasi }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $lokasi->kategori_badge['color'] }}-100 text-{{ $lokasi->kategori_badge['color'] }}-800">
                                    <i class="fas {{ $lokasi->kategori_badge['icon'] }} mr-1"></i>
                                    {{ $lokasi->kategori_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full {{ $lokasi->items_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas fa-box mr-1"></i>
                                    {{ $lokasi->items_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.master-lokasi.edit', $lokasi->id_lokasi) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition shadow" 
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
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Tidak ada data lokasi</p>
                                    <p class="text-gray-400 text-sm mt-2">Mulai tambahkan lokasi baru</p>
                                    <a href="{{ route('admin.master-lokasi.create') }}" 
                                       class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                        <i class="fas fa-plus mr-2"></i>Tambah Lokasi
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lokasis->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $lokasis->links() }}
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
