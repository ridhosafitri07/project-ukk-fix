@extends('layouts.' . auth()->user()->role)

@section('title', 'Profil Saya')
@section('header', 'Profil')
@section('subheader', 'Kelola informasi akun Anda')

@section('content')
<div class="max-w-5xl mx-auto">
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm animate-slide-in">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
            <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
        <!-- Cover & Profile Picture -->
        <div class="relative">
            <div class="h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
            <div class="absolute -bottom-16 left-8">
                @if($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" 
                         class="w-32 h-32 rounded-2xl border-4 border-white shadow-xl object-cover">
                @else
                    <div class="w-32 h-32 rounded-2xl border-4 border-white shadow-xl flex items-center justify-center text-white font-bold text-4xl
                        {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-500 to-purple-700' : 
                           ($user->role === 'petugas' ? 'bg-gradient-to-br from-emerald-500 to-emerald-700' : 
                           'bg-gradient-to-br from-blue-500 to-blue-700') }}">
                        {{ strtoupper(substr($user->nama_pengguna, 0, 2)) }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Profile Info -->
        <div class="pt-20 px-8 pb-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                <div class="flex-1 mb-6 lg:mb-0">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $user->nama_pengguna }}</h1>
                    <p class="text-slate-600 mb-3">@{{ $user->username }}</p>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-4 py-1.5 rounded-full text-sm font-semibold
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 
                               ($user->role === 'petugas' ? 'bg-emerald-100 text-emerald-700' : 
                               'bg-blue-100 text-blue-700') }}">
                            <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'petugas' ? 'user-cog' : 'user') }} mr-1"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        
                        @if($user->role === 'petugas' && $user->petugas && $user->petugas->pekerjaan)
                        <span class="px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-sm font-semibold">
                            <i class="fas fa-briefcase mr-1"></i>
                            {{ $user->petugas->pekerjaan }}
                        </span>
                        @endif
                    </div>

                    @if($user->bio)
                    <p class="text-slate-600 leading-relaxed max-w-2xl">{{ $user->bio }}</p>
                    @endif
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('profile.edit') }}" 
                       class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Profil
                    </a>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 pt-8 border-t border-slate-200">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Bergabung</p>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </p>
                    </div>
                </div>

                @if($user->telp_user)
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-phone text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Telepon</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $user->telp_user }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-id-card text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">ID User</p>
                        <p class="text-sm font-semibold text-slate-800">#{{ $user->id_user }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
        <div class="p-6 bg-gradient-to-r 
            {{ $user->role === 'admin' ? 'from-indigo-500 to-purple-600' : 
               ($user->role === 'petugas' ? 'from-emerald-500 to-green-600' : 
               'from-blue-500 to-sky-600') }}">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-history mr-2"></i>
                Aktivitas Terkini
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-start pb-4 border-b border-slate-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-user-check text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800">Akun dibuat</p>
                        <p class="text-xs text-slate-600 mt-1">
                            {{ $user->created_at ? $user->created_at->diffForHumans() : 'Tidak tersedia' }}
                        </p>
                    </div>
                </div>

                @if($user->updated_at && $user->updated_at != $user->created_at)
                <div class="flex items-start pb-4 border-b border-slate-200">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-edit text-emerald-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800">Profil diperbarui</p>
                        <p class="text-xs text-slate-600 mt-1">
                            {{ $user->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @endif

                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-circle text-emerald-500 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800">Status Aktif</p>
                        <p class="text-xs text-slate-600 mt-1">
                            Saat ini sedang online
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
            <i class="fas fa-bolt text-amber-500 mr-2"></i>
            Quick Actions
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('profile.edit') }}" 
               class="group p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl hover:shadow-md transition-all border border-blue-100">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-edit text-white text-xl"></i>
                </div>
                <p class="font-semibold text-slate-800 mb-1">Edit Profil</p>
                <p class="text-xs text-slate-600">Perbarui informasi</p>
            </a>

            <a href="{{ route('profile.edit') }}#change-password" 
               class="group p-4 bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl hover:shadow-md transition-all border border-emerald-100">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-key text-white text-xl"></i>
                </div>
                <p class="font-semibold text-slate-800 mb-1">Ganti Password</p>
                <p class="text-xs text-slate-600">Ubah kata sandi</p>
            </a>

            <a href="{{ route(auth()->user()->role . '.dashboard') }}" 
               class="group p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl hover:shadow-md transition-all border border-purple-100">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-pie text-white text-xl"></i>
                </div>
                <p class="font-semibold text-slate-800 mb-1">Dashboard</p>
                <p class="text-xs text-slate-600">Kembali ke home</p>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="group w-full text-left p-4 bg-gradient-to-br from-red-50 to-rose-50 rounded-xl hover:shadow-md transition-all border border-red-100">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-sign-out-alt text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-slate-800 mb-1">Logout</p>
                    <p class="text-xs text-slate-600">Keluar akun</p>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes slideIn { 
    from { opacity: 0; transform: translateY(-10px); } 
    to { opacity: 1; transform: translateY(0); } 
}
.animate-slide-in { animation: slideIn 0.3s ease-out; }
</style>
@endsection