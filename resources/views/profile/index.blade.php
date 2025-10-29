@extends('layouts.' . auth()->user()->role)

@section('title', 'Profil Saya')
@section('header', 'Profil Pengguna')
@section('subheader', 'Kelola informasi akun Anda')

@section('content')
<div class="max-w-7xl mx-auto">
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar - Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Picture Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 h-32"></div>
                <div class="relative px-6 pb-6">
                    <div class="flex flex-col items-center -mt-16">
                        <!-- Profile Picture -->
                        <div class="relative">
                            @if($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" 
                                     alt="Profile Photo" 
                                     class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                            @else
                                <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white font-bold text-4xl
                                    {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-500 to-purple-700' : 
                                       ($user->role === 'petugas' ? 'bg-gradient-to-br from-green-500 to-green-700' : 
                                       'bg-gradient-to-br from-blue-500 to-blue-700') }}">
                                    {{ strtoupper(substr($user->nama_pengguna, 0, 2)) }}
                                </div>
                            @endif
                            <!-- Status Badge -->
                            <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-4 border-white rounded-full"></div>
                        </div>

                        <!-- Name & Role -->
                        <div class="mt-4 text-center">
                            <h3 class="text-2xl font-bold text-gray-800">{{ $user->nama_pengguna }}</h3>
                            <p class="text-sm text-gray-600 mt-1">@<span>{{ $user->username }}</span></p>
                            <span class="mt-3 px-4 py-1.5 inline-flex text-sm font-semibold rounded-full
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                   ($user->role === 'petugas' ? 'bg-green-100 text-green-800' : 
                                   'bg-blue-100 text-blue-800') }}">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'petugas' ? 'user-cog' : 'user') }} mr-2"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        <!-- Pekerjaan (Jika Petugas) -->
                        @if($user->role === 'petugas' && $user->petugas && $user->petugas->pekerjaan)
                        <div class="mt-3 px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 w-full">
                            <div class="flex items-center justify-center text-green-700">
                                <i class="fas fa-briefcase mr-2"></i>
                                <span class="font-semibold">{{ $user->petugas->pekerjaan }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Bio -->
                        @if($user->bio)
                        <div class="mt-4 w-full">
                            <p class="text-sm text-gray-600 text-center italic leading-relaxed">
                                "{{ $user->bio }}"
                            </p>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-6 flex gap-3 w-full">
                            <a href="{{ route('profile.edit') }}" 
                               class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all flex items-center justify-center">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Info Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-gray-700 to-gray-900">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Akun
                    </h4>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center text-gray-700">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-medium">Bergabung Sejak</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $user->created_at ? $user->created_at->format('d F Y') : 'Tidak tersedia' }}
                            </p>
                        </div>
                    </div>

                    @if($user->telp_user)
                    <div class="flex items-center text-gray-700">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-phone text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-medium">Nomor Telepon</p>
                            <p class="text-sm font-bold text-gray-800">{{ $user->telp_user }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center text-gray-700">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-id-card text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-medium">ID User</p>
                            <p class="text-sm font-bold text-gray-800">#{{ $user->id_user }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistics Cards -->
            @if($user->role === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Total Users</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['total_users'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-users text-5xl text-blue-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-100">Total Pengaduan</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['total_pengaduan'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-clipboard-list text-5xl text-green-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-100">Pengaduan Selesai</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['pengaduan_selesai'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-check-circle text-5xl text-purple-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-100">Pending</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['pengaduan_pending'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-clock text-5xl text-yellow-200"></i>
                    </div>
                </div>
            </div>
            @elseif($user->role === 'petugas')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Total Tugas</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['total_pengaduan'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-tasks text-5xl text-blue-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-100">Selesai</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['pengaduan_selesai'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-check-circle text-5xl text-green-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-100">Sedang Diproses</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['pengaduan_diproses'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-cog fa-spin text-5xl text-indigo-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-100">Pending</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['pengaduan_pending'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-clock text-5xl text-yellow-200"></i>
                    </div>
                </div>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Total Pengaduan</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['total_pengaduan'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-clipboard-list text-5xl text-blue-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-100">Selesai</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['pengaduan_selesai'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-check-circle text-5xl text-green-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-100">Diproses</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['pengaduan_diproses'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-cog text-5xl text-indigo-200"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-100">Pending</p>
                            <p class="text-3xl font-bold mt-2">{{ $statistics['pengaduan_pending'] ?? 0 }}</p>
                        </div>
                        <i class="fas fa-clock text-5xl text-yellow-200"></i>
                    </div>
                </div>
            </div>
            @endif

            <!-- Activity Timeline -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-history mr-2"></i>
                        Aktivitas Terkini
                    </h4>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start pb-4 border-b border-gray-200">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-user-check text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">Akun dibuat</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    {{ $user->created_at ? $user->created_at->diffForHumans() : 'Tidak tersedia' }}
                                </p>
                            </div>
                        </div>

                        @if($user->updated_at && $user->updated_at != $user->created_at)
                        <div class="flex items-start pb-4 border-b border-gray-200">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-edit text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">Profil diperbarui</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    {{ $user->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-chart-line text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">Status Aktif</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    Saat ini sedang online
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-gray-700 to-gray-900">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-bolt mr-2"></i>
                        Quick Actions
                    </h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-user-edit text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 group-hover:text-blue-600">Edit Profil</p>
                                <p class="text-xs text-gray-600">Perbarui informasi pribadi</p>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}#change-password" 
                           class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-key text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 group-hover:text-green-600">Ganti Password</p>
                                <p class="text-xs text-gray-600">Ubah kata sandi akun</p>
                            </div>
                        </a>

                        <a href="{{ route(auth()->user()->role . '.dashboard') }}" 
                           class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
                            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-chart-pie text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 group-hover:text-purple-600">Dashboard</p>
                                <p class="text-xs text-gray-600">Kembali ke dashboard</p>
                            </div>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors group">
                                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-sign-out-alt text-white text-xl"></i>
                                </div>
                                <div class="flex-1 text-left">
                                    <p class="font-semibold text-gray-800 group-hover:text-red-600">Logout</p>
                                    <p class="text-xs text-gray-600">Keluar dari akun</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
