@extends('layouts.' . auth()->user()->role)

@section('title', 'Profil Saya')
@section('header', 'Profil')
@section('subheader', 'Kelola informasi akun Anda')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-0">
    <!-- Compact Profile Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header with gradient -->
        <div class="relative bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-4 md:px-6 py-6 md:py-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <!-- Profile Picture -->
                <div class="relative">
                    @if($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" 
                             class="w-20 h-20 md:w-24 md:h-24 rounded-xl border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl border-4 border-white shadow-lg flex items-center justify-center text-white font-bold text-2xl md:text-3xl
                            {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-600 to-purple-800' : 
                               ($user->role === 'petugas' ? 'bg-gradient-to-br from-emerald-600 to-green-800' : 
                               'bg-gradient-to-br from-blue-600 to-blue-800') }}">
                            {{ strtoupper(substr($user->nama_pengguna, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl md:text-2xl font-bold text-white mb-1 truncate">{{ $user->nama_pengguna }}</h1>
                    <p class="text-sm text-white/80 mb-2">{{ '@' . $user->username }}</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm">
                            <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'petugas' ? 'user-cog' : 'user') }} mr-1"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->role === 'petugas' && $user->petugas && $user->petugas->pekerjaan)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm">
                            <i class="fas fa-briefcase mr-1"></i>
                            {{ $user->petugas->pekerjaan }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 w-full sm:w-auto">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex-1 sm:flex-none px-4 py-2 bg-white text-indigo-600 rounded-lg text-sm font-semibold hover:bg-white/90 transition-all flex items-center justify-center shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-white/10 text-white rounded-lg text-sm font-semibold hover:bg-white/20 transition-all flex items-center justify-center backdrop-blur-sm border border-white/30">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Compact Info Grid -->
        <div class="p-4 md:p-6">
            @if($user->bio)
            <div class="mb-4 pb-4 border-b border-slate-200">
                <p class="text-sm text-slate-700 leading-relaxed">{{ $user->bio }}</p>
            </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <!-- ID User -->
                <div class="bg-purple-50 rounded-lg p-3">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-id-card text-purple-500 text-sm mr-2"></i>
                        <span class="text-xs font-medium text-slate-600">ID User</span>
                    </div>
                    <p class="text-sm font-bold text-slate-800">#{{ $user->id_user }}</p>
                </div>

                <!-- Bergabung -->
                <div class="bg-blue-50 rounded-lg p-3">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-calendar-alt text-blue-500 text-sm mr-2"></i>
                        <span class="text-xs font-medium text-slate-600">Bergabung</span>
                    </div>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                    </p>
                </div>

                <!-- Telepon -->
                @if($user->telp_user)
                <div class="bg-emerald-50 rounded-lg p-3">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-phone text-emerald-500 text-sm mr-2"></i>
                        <span class="text-xs font-medium text-slate-600">Telepon</span>
                    </div>
                    <p class="text-sm font-bold text-slate-800 truncate">{{ $user->telp_user }}</p>
                </div>
                @endif

                <!-- Status -->
                <div class="bg-green-50 rounded-lg p-3">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-circle text-green-500 text-xs mr-2"></i>
                        <span class="text-xs font-medium text-slate-600">Status</span>
                    </div>
                    <p class="text-sm font-bold text-green-700">Online</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terkini - Compact -->
    <div class="mt-4 bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-4 py-3 bg-gradient-to-r 
            {{ $user->role === 'admin' ? 'from-indigo-500 to-purple-600' : 
               ($user->role === 'petugas' ? 'from-emerald-500 to-green-600' : 
               'from-blue-500 to-sky-600') }}">
            <h3 class="text-sm md:text-base font-bold text-white flex items-center">
                <i class="fas fa-history mr-2"></i>
                Aktivitas Terkini
            </h3>
        </div>
        <div class="p-4">
            <div class="space-y-3">
                <!-- Akun dibuat -->
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-user-check text-blue-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800">Akun dibuat</p>
                        <p class="text-xs text-slate-600">
                            {{ $user->created_at ? $user->created_at->diffForHumans() : 'Tidak tersedia' }}
                        </p>
                    </div>
                </div>

                @if($user->updated_at && $user->updated_at != $user->created_at)
                <!-- Profil diperbarui -->
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-edit text-emerald-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800">Profil diperbarui</p>
                        <p class="text-xs text-slate-600">
                            {{ $user->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
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