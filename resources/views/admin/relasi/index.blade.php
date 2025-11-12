@extends('layouts.admin')

@section('title', 'Sarpras Barang-Ruangan')
@section('header', 'Sarpras Barang-Ruangan')
@section('subheader', 'Kelola distribusi barang ke lokasi/ruangan')

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
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Total Sarpras</p>
                    <p class="text-4xl font-bold mt-2">{{ $statistics['total'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Barang terdistribusi</p>
                </div>
                <div class="bg-blue-400 rounded-full p-4">
                    <i class="fas fa-link text-4xl opacity-80"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Total Lokasi</p>
                    <p class="text-4xl font-bold mt-2">{{ $statistics['total_lokasi'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Ruangan tersedia</p>
                </div>
                <div class="bg-purple-400 rounded-full p-4">
                    <i class="fas fa-map-marked-alt text-4xl opacity-80"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Total Barang</p>
                    <p class="text-4xl font-bold mt-2">{{ $statistics['total_barang'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Item terdaftar</p>
                </div>
                <div class="bg-indigo-400 rounded-full p-4">
                    <i class="fas fa-cube text-4xl opacity-80"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-sitemap mr-2"></i>
                        Daftar Sarpras Barang-Ruangan
                    </h3>
                    <p class="text-purple-100 text-sm mt-1">Kelola distribusi barang ke setiap lokasi</p>
                </div>
                    <a href="{{ route('admin.relasi.create') }}" 
                   class="bg-white hover:bg-purple-50 text-purple-600 font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-200 flex items-center space-x-2 w-fit">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Sarpras Baru</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 bg-gray-50 border-b">
            <form method="GET" action="{{ route('admin.relasi.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="🔍 Cari nama barang atau lokasi..." 
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                            <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <select name="id_lokasi" 
                                class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                            <option value="">🏢 Semua Lokasi</option>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}" {{ request('id_lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                        
                        <button type="submit" 
                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition shadow-lg">
                            <i class="fas fa-filter"></i>
                        </button>
                        
                        @if(request('search') || request('id_lokasi'))
                            <a href="{{ route('admin.relasi.index') }}" 
                               class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition shadow-lg">
                                <i class="fas fa-redo"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-1"></i>
                            No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Lokasi
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-box mr-1"></i>
                            Barang
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-1"></i>
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($relasis as $index => $relasi)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $relasis->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-door-open text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $relasi->lokasi->nama_lokasi ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">ID Lokasi: {{ $relasi->id_lokasi }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-cube text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $relasi->item->nama_item ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">ID Barang: {{ $relasi->id_item }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                <a href="{{ route('admin.relasi.edit', $relasi->id_item) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition shadow" 
                                   title="Edit Distribusi Barang">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        onclick="confirmDelete('{{ $relasi->id_item }}_{{ $relasi->id_lokasi }}', '{{ $relasi->lokasi->nama_lokasi ?? '-' }}', '{{ $relasi->item->nama_item ?? '-' }}')"
                                        class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $relasi->id_item }}_{{ $relasi->id_lokasi }}" 
                                      action="{{ route('admin.relasi.destroy', $relasi->id_item . '_' . $relasi->id_lokasi) }}" 
                                      method="POST" 
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Tidak ada sarpras</p>
                                    <p class="text-gray-400 text-sm mt-2">Mulai distribusikan barang ke lokasi</p>
                                    <a href="{{ route('admin.relasi.create') }}" 
                                       class="mt-4 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                        <i class="fas fa-plus mr-2"></i>Tambah Sarpras
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($relasis->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $relasis->links() }}
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

<script>
function confirmDelete(id, lokasi, barang) {
    Swal.fire({
        title: 'Hapus Sarpras?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-2">Anda akan menghapus sarpras:</p>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-sm"><strong>Lokasi:</strong> ${lokasi}</p>
                    <p class="text-sm"><strong>Barang:</strong> ${barang}</p>
                </div>
                <p class="text-red-600 text-sm mt-3">⚠️ Aksi ini tidak dapat dibatalkan!</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i> Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection
