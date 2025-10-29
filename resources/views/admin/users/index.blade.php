@extends('layouts.admin')

@section('title', 'Manajemen Users')
@section('header', 'Manajemen Users')
@section('subheader', 'Kelola akun pengguna sistem')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-100">Total Users</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['total'] }}</p>
            </div>
            <i class="fas fa-users text-4xl text-blue-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-purple-100">Admin</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['admin'] }}</p>
            </div>
            <i class="fas fa-user-shield text-4xl text-purple-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-green-100">Petugas</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['petugas'] }}</p>
            </div>
            <i class="fas fa-user-cog text-4xl text-green-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-yellow-100">Pengguna</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['pengguna'] }}</p>
            </div>
            <i class="fas fa-user text-4xl text-yellow-200"></i>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Header with Actions -->
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-users text-blue-500 mr-2"></i>
                    Daftar Users
                </h3>
                <p class="text-sm text-gray-600 mt-1">Kelola semua akun pengguna</p>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center justify-center shadow-md transform hover:scale-105 transition-all">
                <i class="fas fa-plus mr-2"></i>
                Tambah User
            </a>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="p-6 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Box -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nama atau username..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            
            <!-- Role Filter -->
            <div class="w-full md:w-48">
                <select name="role" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        onchange="this.form.submit()">
                    <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                </select>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                
                @if(request('search') || request('role') != 'all')
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        User
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Username
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Role
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Tanggal Dibuat
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold
                                    {{ $user->role == 'admin' ? 'bg-purple-500' : ($user->role == 'petugas' ? 'bg-green-500' : 'bg-blue-500') }}">
                                    {{ strtoupper(substr($user->nama_pengguna, 0, 2)) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->nama_pengguna }}</div>
                                @if($user->id_user === auth()->id())
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-circle mr-1"></i> Anda
                                </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 flex items-center">
                            <i class="fas fa-at text-gray-400 mr-2"></i>
                            {{ $user->username }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->role == 'admin') bg-purple-100 text-purple-800
                                @elseif($user->role == 'petugas') bg-green-100 text-green-800
                                @else bg-blue-100 text-blue-800 @endif">
                                <i class="fas fa-{{ $user->role == 'admin' ? 'user-shield' : ($user->role == 'petugas' ? 'user-cog' : 'user') }} mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->role == 'petugas' && $user->petugas)
                            <span class="mt-1 text-xs text-gray-600 flex items-center">
                                <i class="fas fa-briefcase mr-1"></i>
                                {{ $user->petugas->pekerjaan }}
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <i class="far fa-calendar text-gray-400 mr-2"></i>
                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded transition-colors"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id_user !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded transition-colors"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400 p-2" title="Tidak dapat menghapus akun sendiri">
                                <i class="fas fa-lock"></i>
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">
                            @if(request('search') || request('role') != 'all')
                                Tidak ada user yang sesuai dengan filter
                            @else
                                Belum ada user yang terdaftar
                            @endif
                        </p>
                        <p class="text-gray-400 text-sm mt-2">
                            @if(request('search') || request('role') != 'all')
                                Coba ubah filter atau kata kunci pencarian
                            @else
                                Tambahkan user baru untuk memulai
                            @endif
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection