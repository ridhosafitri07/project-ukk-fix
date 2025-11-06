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
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.3s ease;
        }
        
        /* Sidebar active state */
        .sidebar-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .sidebar-link:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }
        
        .sidebar-link.active:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateX(5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 h-full w-64 bg-gradient-to-br from-blue-600 to-blue-800 shadow-2xl z-50 flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 border-b border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-tools text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-white text-xl font-bold">SAPRAS</h1>
                        <p class="text-blue-200 text-xs">Manajemen Sarpras</p>
                    </div>
                </div>
            </div>

            <!-- Menu Section -->
            <nav class="flex-1 overflow-y-auto py-6 px-3">
                <div class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.pengaduan.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-white {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span class="font-medium">Pengaduan</span>
                    </a>

                    <!-- Dropdown Menu: Manajemen Sarpras -->
                    <div x-data="{ open: {{ request()->routeIs('admin.sarpras.*') || request()->routeIs('admin.master-*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="sidebar-link flex items-center justify-between w-full px-4 py-3 rounded-lg text-white {{ request()->routeIs('admin.sarpras.*') || request()->routeIs('admin.master-*') ? 'active' : '' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-boxes w-5"></i>
                                <span class="font-medium">Manajemen Sarpras</span>
                            </div>
                            <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" x-collapse class="ml-4 mt-2 space-y-1">
                            <a href="{{ route('admin.sarpras.index') }}" 
                               class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition {{ request()->routeIs('admin.sarpras.index') || request()->routeIs('admin.sarpras.permintaan-list') || request()->routeIs('admin.sarpras.show-permintaan') ? 'bg-blue-700 text-white' : '' }}">
                                <i class="fas fa-clipboard-check w-4 text-sm"></i>
                                <span class="text-sm">Daftar Permintaan</span>
                            </a>
                            
                            <a href="{{ route('admin.sarpras.history') }}" 
                               class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition {{ request()->routeIs('admin.sarpras.history') ? 'bg-blue-700 text-white' : '' }}">
                                <i class="fas fa-history w-4 text-sm"></i>
                                <span class="text-sm">Riwayat</span>
                            </a>

                            <div class="my-2 border-t border-blue-700"></div>
                            
                            <a href="{{ route('admin.master-lokasi.index') }}" 
                               class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition {{ request()->routeIs('admin.master-lokasi.*') ? 'bg-blue-700 text-white' : '' }}">
                                <i class="fas fa-map-marker-alt w-4 text-sm"></i>
                                <span class="text-sm">Master Lokasi/Ruangan</span>
                            </a>
                            
                            <a href="{{ route('admin.master-barang.index') }}" 
                               class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition {{ request()->routeIs('admin.master-barang.*') ? 'bg-blue-700 text-white' : '' }}">
                                <i class="fas fa-box w-4 text-sm"></i>
                                <span class="text-sm">Master Barang/Item</span>
                            </a>
                            
                            <a href="{{ route('admin.relasi.index') }}" 
                               class="flex items-center space-x-3 px-4 py-2 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition {{ request()->routeIs('admin.relasi.*') ? 'bg-blue-700 text-white' : '' }}">
                                <i class="fas fa-link w-4 text-sm"></i>
                                <span class="text-sm">Relasi Barang-Ruangan</span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span class="font-medium">Users</span>
                    </a>

                    <div class="my-4 border-t border-blue-700"></div>

                    <a href="{{ route('profile.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-white {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle w-5"></i>
                        <span class="font-medium">Profil Saya</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-blue-700">
                <a href="{{ route('profile.index') }}" class="block hover:bg-blue-700 rounded-lg p-2 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if(auth()->user()->foto_profil)
                                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" 
                                     alt="Profile" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-blue-300">
                            @else
                                <div class="w-10 h-10 bg-blue-300 rounded-full flex items-center justify-center">
                                    <span class="text-blue-800 font-bold text-sm">
                                        {{ strtoupper(substr(auth()->user()->nama_pengguna, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="text-white">
                                <p class="text-sm font-medium">{{ auth()->user()->nama_pengguna }}</p>
                                <p class="text-xs text-blue-200">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" onclick="event.stopPropagation();">
                            @csrf
                            <button type="submit" class="text-blue-200 hover:text-white" title="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-64 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm sticky top-0 z-40">
                <div class="px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">@yield('header', 'Dashboard')</h2>
                            <p class="text-sm text-gray-600 mt-1">@yield('subheader', 'Welcome back')</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Notification Bell -->
                            <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Current Time -->
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ date('l') }}</p>
                                <p class="text-sm font-semibold text-gray-700">{{ date('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-8">
                    @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                            <p class="text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                            <p class="text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-8">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} SAPRAS - Sistem Manajemen Sarana Prasarana</p>
                    <p>Version 1.0.0</p>
                </div>
            </footer>
        </div>
    </div>

    <!-- SweetAlert2 for Flash Messages -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            showConfirmButton: true,
            confirmButtonColor: '#EF4444'
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>