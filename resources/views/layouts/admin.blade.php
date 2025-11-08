<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SAPRAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        .sidebar-link { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-link.active { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; }
        .sidebar-link:hover:not(.active) { background: rgba(99, 102, 241, 0.08); transform: translateX(4px); }
        
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-in { animation: slideIn 0.3s ease-out; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true, mobileMenu: false }">
        
        <!-- Sidebar Desktop -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
               class="hidden lg:flex fixed left-0 top-0 h-full bg-white shadow-xl z-50 flex-col transition-all duration-300 border-r border-slate-200">
            
            <!-- Logo -->
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <div x-show="sidebarOpen" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tools text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">SAPRAS</h1>
                        <p class="text-xs text-slate-500">Admin Panel</p>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <i class="fas fa-bars text-slate-600"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5 text-center"></i>
                        <span x-show="sidebarOpen" class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.pengaduan.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list w-5 text-center"></i>
                        <span x-show="sidebarOpen" class="font-medium">Pengaduan</span>
                    </a>

                    <a href="{{ route('admin.laporan.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt w-5 text-center"></i>
                        <span x-show="sidebarOpen" class="font-medium">Laporan</span>
                    </a>

                    <!-- Dropdown: Manajemen Sarpras -->
                    <div x-data="{ open: {{ request()->routeIs('admin.sarpras.*') || request()->routeIs('admin.master-*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="sidebar-link flex items-center justify-between w-full px-4 py-3 rounded-xl text-slate-700 {{ request()->routeIs('admin.sarpras.*') || request()->routeIs('admin.master-*') ? 'active' : '' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-boxes w-5 text-center"></i>
                                <span x-show="sidebarOpen" class="font-medium">Manajemen</span>
                            </div>
                            <i x-show="sidebarOpen" class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open && sidebarOpen" x-collapse class="ml-8 mt-1 space-y-1">
                            <a href="{{ route('admin.sarpras.index') }}" 
                               class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition {{ request()->routeIs('admin.sarpras.index') ? 'bg-slate-100 font-semibold' : '' }}">
                                <i class="fas fa-clipboard-check w-4"></i>
                                <span>Daftar Permintaan</span>
                            </a>

                            <div class="my-2 border-t border-slate-200"></div>
                            
                            <a href="{{ route('admin.master-lokasi.index') }}" 
                               class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition {{ request()->routeIs('admin.master-lokasi.*') ? 'bg-slate-100 font-semibold' : '' }}">
                                <i class="fas fa-map-marker-alt w-4"></i>
                                <span>Lokasi</span>
                            </a>
                            
                            <a href="{{ route('admin.master-barang.index') }}" 
                               class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition {{ request()->routeIs('admin.master-barang.*') ? 'bg-slate-100 font-semibold' : '' }}">
                                <i class="fas fa-box w-4"></i>
                                <span>Barang</span>
                            </a>
                            
                            <a href="{{ route('admin.relasi.index') }}" 
                               class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition {{ request()->routeIs('admin.relasi.*') ? 'bg-slate-100 font-semibold' : '' }}">
                                <i class="fas fa-link w-4"></i>
                                <span>Relasi</span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span x-show="sidebarOpen" class="font-medium">Users</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile Compact -->
            <div class="p-3 border-t border-slate-100">
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" 
                            class="w-full flex items-center space-x-3 p-3 hover:bg-slate-50 rounded-xl transition-colors">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" 
                                 class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-200">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center ring-2 ring-slate-200">
                                <span class="text-white font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->nama_pengguna, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <div x-show="sidebarOpen" class="flex-1 text-left">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ auth()->user()->nama_pengguna }}</p>
                            <p class="text-xs text-slate-500">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <i x-show="sidebarOpen" class="fas fa-ellipsis-v text-slate-400"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" 
                         @click.away="profileOpen = false"
                         x-transition
                         class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl shadow-xl border border-slate-200 py-2 animate-slide-in">
                        <a href="{{ route('profile.index') }}" 
                           class="flex items-center space-x-3 px-4 py-2 hover:bg-slate-50 transition-colors">
                            <i class="fas fa-user-circle text-slate-400"></i>
                            <span class="text-sm text-slate-700">Profil</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center space-x-3 px-4 py-2 hover:bg-red-50 transition-colors text-left">
                                <i class="fas fa-sign-out-alt text-red-500"></i>
                                <span class="text-sm text-red-600">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenu = true" 
                class="lg:hidden fixed top-4 left-4 z-50 p-3 bg-white rounded-xl shadow-lg">
            <i class="fas fa-bars text-slate-700"></i>
        </button>

        <!-- Mobile Sidebar -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenu = false"
             class="lg:hidden fixed inset-0 bg-black/50 z-40">
        </div>

        <aside x-show="mobileMenu"
               x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="lg:hidden fixed left-0 top-0 h-full w-64 bg-white shadow-2xl z-50 flex flex-col">
            <!-- Same content as desktop sidebar -->
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tools text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">SAPRAS</h1>
                        <p class="text-xs text-slate-500">Admin Panel</p>
                    </div>
                </div>
                <button @click="mobileMenu = false" class="p-2">
                    <i class="fas fa-times text-slate-600"></i>
                </button>
            </div>
            <!-- Navigation (same as desktop) -->
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden" 
             :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
            
            <!-- Header -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
                <div class="px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 ml-16 lg:ml-0">
                            <h2 class="text-2xl font-bold text-slate-800">@yield('header', 'Dashboard')</h2>
                            <p class="text-sm text-slate-500 mt-0.5">@yield('subheader', 'Welcome back')</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button class="relative p-2.5 hover:bg-slate-100 rounded-xl transition-colors">
                                <i class="fas fa-bell text-slate-600"></i>
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div class="hidden md:block text-right px-3 py-1.5 bg-slate-50 rounded-lg">
                                <p class="text-xs text-slate-500">{{ date('l') }}</p>
                                <p class="text-sm font-semibold text-slate-700">{{ date('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-6 lg:p-8">
                    @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm animate-slide-in">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-emerald-500 text-xl mr-3"></i>
                            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm animate-slide-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between text-sm text-slate-600 space-y-2 md:space-y-0">
                    <p>&copy; {{ date('Y') }} SAPRAS - Sistem Manajemen Sarana Prasarana</p>
                    <p class="text-slate-500">v1.0.0</p>
                </div>
            </footer>
        </div>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: 'top-end',
            timerProgressBar: true
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: '#EF4444'
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>