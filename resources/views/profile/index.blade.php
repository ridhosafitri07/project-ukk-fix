@extends('layouts.pengguna')

@section('title', 'Profil Saya')
@section('header', 'Profil Saya')
@section('subheader', 'Kelola informasi akun Anda')

@section('content')
<style>
    .profile-hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .profile-hero-gradient::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 20s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .stat-card-modern {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 12px -2px rgba(0,0,0,0.03);
    }
    
    .stat-card-modern:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 12px 24px -8px rgba(139, 92, 246, 0.2);
    }
    
    .activity-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .activity-item:hover {
        background: linear-gradient(to right, rgba(102, 126, 234, 0.05), transparent);
        border-left-color: #667eea;
        transform: translateX(4px);
    }
</style>

<div class="space-y-8">

    <!-- ✨ Hero Profile Section — Matching Hero from Dashboard -->
    <div class="profile-hero-gradient rounded-3xl overflow-hidden shadow-xl text-white p-8 lg:p-10 animate-fade-in-up">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
            <!-- Avatar & Status -->
            <div class="flex flex-col lg:flex-row items-center gap-6">
                <div class="relative">
                    @if($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" 
                             alt="Foto Profil"
                             class="w-32 h-32 lg:w-40 lg:h-40 rounded-2xl object-cover ring-4 ring-white/30 shadow-xl hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-32 h-32 lg:w-40 lg:h-40 rounded-2xl flex items-center justify-center text-white font-black text-4xl lg:text-5xl ring-4 ring-white/30 shadow-xl bg-gradient-to-br from-purple-500 via-pink-500 to-indigo-600">
                            {{ strtoupper(substr($user->nama_pengguna, 0, 1)) }}
                        </div>
                    @endif
                    <!-- Online Status -->
                    <div class="absolute bottom-2 right-2 w-6 h-6 bg-emerald-400 rounded-full border-2 border-white animate-ping"></div>
                    <div class="absolute bottom-2 right-2 w-6 h-6 bg-emerald-400 rounded-full border-2 border-white"></div>
                </div>

                <div class="text-center lg:text-left mt-4 lg:mt-0">
                    <h1 class="text-3xl lg:text-4xl font-black mb-2 leading-tight">
                        {{ $user->nama_pengguna }}
                    </h1>
                    <p class="text-purple-100 text-lg mb-4">@{{ $user->username }}</p>

                    <div class="flex flex-wrap justify-center lg:justify-start gap-2 mb-5">
                        <span class="px-4 py-2 rounded-full text-xs font-bold bg-white/15 text-white backdrop-blur-sm border border-white/20 flex items-center gap-1.5">
                            <i class="fas fa-user-shield text-sm"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->role === 'petugas' && $user->petugas && $user->petugas->pekerjaan)
                        <span class="px-4 py-2 rounded-full text-xs font-bold bg-white/15 text-white backdrop-blur-sm border border-white/20 flex items-center gap-1.5">
                            <i class="fas fa-briefcase text-sm"></i>
                            {{ $user->petugas->pekerjaan }}
                        </span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        <a href="{{ route('profile.edit') }}" 
                           class="group relative inline-flex items-center gap-2 bg-white text-purple-700 font-bold py-3 px-6 rounded-xl hover:bg-purple-50 transition-all hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-edit"></i>
                            Edit Profil
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-3 border-2 border-white/50 text-white rounded-xl hover:bg-white/20 transition-all backdrop-blur-sm font-semibold">
                                <i class="fas fa-sign-out-alt"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats Mini Card (Right Side for Large) -->
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-white/20 backdrop-blur-xl rounded-3xl blur-xl"></div>
                    <div class="relative bg-white/15 backdrop-blur-2xl border-2 border-white/30 rounded-3xl p-6 min-w-[200px]">
                        <div class="text-center">
                            <i class="fas fa-user-circle text-4xl text-yellow-200 mb-3"></i>
                            <div class="text-3xl font-black text-white">{{ $pengaduanCount ?? 0 }}</div>
                            <div class="text-purple-100 text-sm font-medium">Pengaduan Diajukan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bio Section -->
        @if($user->bio)
        <div class="mt-8 pt-6 border-t border-white/20 max-w-4xl mx-auto lg:mx-0">
            <div class="flex items-start gap-3">
                <i class="fas fa-quote-left text-purple-200 text-xl mt-1 opacity-80"></i>
                <p class="text-purple-100 leading-relaxed text-lg italic">
                    “{{ $user->bio }}”
                </p>
            </div>
        </div>
        @endif
    </div>

    <!-- 📊 Stats Grid — Matching Dashboard Style -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- ID User -->
        <div class="stat-card-modern group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1 uppercase tracking-wide">ID Pengguna</p>
                    <h3 class="text-3xl font-black text-gray-900">#{{ $user->id_user }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-fingerprint"></i>
                </div>
            </div>
        </div>

        <!-- Join Date -->
        <div class="stat-card-modern group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1 uppercase tracking-wide">Bergabung</p>
                    <h3 class="text-3xl font-black text-gray-900">
                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>

        <!-- Phone -->
        @if($user->telp_user)
        <div class="stat-card-modern group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1 uppercase tracking-wide">Telepon</p>
                    <h3 class="text-3xl font-black text-gray-900 break-all">{{ $user->telp_user }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-mobile-alt"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- Status -->
        <div class="stat-card-modern group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1 uppercase tracking-wide">Status</p>
                    <h3 class="text-3xl font-black text-emerald-600">Aktif</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center text-white text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 📜 Activity Timeline — Modernized -->
    <div class="stat-card-modern animate-fade-in-up animate-delay-1">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <i class="fas fa-history text-purple-500"></i>
                Aktivitas Profil
            </h2>
        </div>

        <div class="space-y-5">
            <!-- Account Created -->
            <div class="activity-item p-5 rounded-xl hover:rounded-xl">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex-shrink-0 flex items-center justify-center text-white text-lg shadow-sm">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-1">Akun Dibuat</h3>
                        <p class="text-gray-600 text-sm mb-2">
                            {{ $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') : 'Tidak tersedia' }}
                        </p>
                        @if($user->created_at)
                        <span class="inline-flex items-center gap-2 text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">
                            <i class="far fa-clock"></i>
                            {{ $user->created_at->diffForHumans() }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Updated -->
            @if($user->updated_at && $user->updated_at != $user->created_at)
            <div class="activity-item p-5 rounded-xl">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-500 flex-shrink-0 flex items-center justify-center text-white text-lg shadow-sm">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-1">Profil Diperbarui</h3>
                        <p class="text-gray-600 text-sm mb-2">
                            {{ $user->updated_at->translatedFormat('d F Y, H:i') }}
                        </p>
                        <span class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            <i class="far fa-clock"></i>
                            {{ $user->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection