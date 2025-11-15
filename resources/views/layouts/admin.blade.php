<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SAPRAS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * { font-family: 'Inter', sans-serif; }
        
        :root {
            --primary-gray: #1f2937;
            --secondary-gray: #374151;
            --light-gray: #9ca3af;
            --primary-blue: #3b82f6;
            --soft-blue: #60a5fa;
        }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f3f4f6; }
        ::-webkit-scrollbar-thumb { background: #9ca3af; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        
        body { background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); }
        
        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: linear-gradient(180deg, #3b82f6, #1f2937);
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }
        
        .sidebar-link.active::before {
            height: 70%;
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
            color: #1f2937;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(31, 41, 55, 0.1);
        }
        
        .sidebar-link:hover:not(.active) {
            background: linear-gradient(135deg, rgba(229, 231, 235, 0.5), rgba(209, 213, 219, 0.5));
            transform: translateX(4px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-slide-in { animation: slideIn 0.3s ease-out; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        .animate-slide-in-right { animation: slideInRight 0.4s ease-out; }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #1f2937 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
        }
        
        .dropdown-menu {
            animation: slideIn 0.2s ease-out;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[9999] space-y-2"></div>
    
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true, mobileMenu: false, notifOpen: false }">
        
        <!-- Sidebar Desktop -->
        <aside :class="sidebarOpen ? 'w-72' : 'w-20'" 
               class="hidden lg:flex fixed left-0 top-0 h-full glass-effect shadow-2xl z-50 flex-col transition-all duration-300">
            
            <!-- Logo Section -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div x-show="sidebarOpen" class="flex items-center space-x-3 animate-fade-in">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-700 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg transform rotate-3">
                            <i class="fas fa-tools text-white text-xl"></i>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-blue-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold gradient-text">SAPRAS</h1>
                        <p class="text-xs text-gray-500 font-medium">Admin Panel</p>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="p-2.5 hover:bg-gray-100 rounded-xl transition-all transform hover:scale-110">
                    <i class="fas fa-bars text-gray-600"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-6 px-4">
                <div class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl text-gray-700 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen" class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.pengaduan.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl text-gray-700 {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen" class="font-medium">Pengaduan</span>
                        <span x-show="sidebarOpen" class="ml-auto px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full">
                            {{ session('pending_count', 0) }}
                        </span>
                    </a>

                    <a href="{{ route('admin.laporan.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl text-gray-700 {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen" class="font-medium">Laporan</span>
                    </a>

                    <!-- Divider -->
                    <div x-show="sidebarOpen" class="my-4 border-t border-gray-200"></div>
                    <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Manajemen Sarpras
                    </p>

                    <!-- Dropdown: Manajemen Sarpras -->
                    <div x-data="{ open: {{ request()->routeIs('admin.sarpras.*') || request()->routeIs('admin.master-*') || request()->routeIs('admin.relasi.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="sidebar-link flex items-center justify-between w-full px-4 py-3.5 rounded-xl text-gray-700 {{ request()->routeIs('admin.sarpras.*') || request()->routeIs('admin.master-*') || request()->routeIs('admin.relasi.*') ? 'active' : '' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-boxes w-5 text-center text-lg"></i>
                                <span x-show="sidebarOpen" class="font-medium">Data Master</span>
                            </div>
                            <i x-show="sidebarOpen" 
                               class="fas fa-chevron-down text-xs transition-transform duration-300" 
                               :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open && sidebarOpen" 
                             x-collapse 
                             class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('admin.sarpras.index') }}" 
                               class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition {{ request()->routeIs('admin.sarpras.index') ? 'bg-gray-100 text-gray-800 font-semibold' : '' }}">
                                <i class="fas fa-clipboard-check w-4"></i>
                                <span>Permintaan Sarpras</span>
                            </a>

                            <div class="my-2 border-t border-gray-200"></div>
                            
                            <a href="{{ route('admin.master-lokasi.index') }}" 
                               class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition {{ request()->routeIs('admin.master-lokasi.*') ? 'bg-gray-100 text-gray-800 font-semibold' : '' }}">
                                <i class="fas fa-map-marker-alt w-4"></i>
                                <span>Master Lokasi</span>
                            </a>
                            
                            <a href="{{ route('admin.master-barang.index') }}" 
                               class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition {{ request()->routeIs('admin.master-barang.*') ? 'bg-gray-100 text-gray-800 font-semibold' : '' }}">
                                <i class="fas fa-box w-4"></i>
                                <span>Master Barang</span>
                            </a>
                            
                            <a href="{{ route('admin.relasi.index') }}" 
                               class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition {{ request()->routeIs('admin.relasi.*') ? 'bg-gray-100 text-gray-800 font-semibold' : '' }}">
                                <i class="fas fa-link w-4"></i>
                                <span>Relasi Sarpras</span>
                            </a>
                        </div>
                    </div>

                    <div x-show="sidebarOpen" class="my-4 border-t border-gray-200"></div>

                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-link flex items-center space-x-3 px-4 py-3.5 rounded-xl text-gray-700 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-5 text-center text-lg"></i>
                        <span x-show="sidebarOpen" class="font-medium">Manajemen Users</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200">
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" 
                            class="w-full flex items-center space-x-3 p-3 hover:bg-gray-100 rounded-xl transition-all">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" 
                                 class="w-11 h-11 rounded-xl object-cover ring-2 ring-gray-300">
                        @else
                            <div class="w-11 h-11 bg-gradient-to-br from-gray-600 to-blue-500 rounded-xl flex items-center justify-center ring-2 ring-gray-300 shadow-lg">
                                <span class="text-white font-bold">
                                    {{ strtoupper(substr(auth()->user()->nama_pengguna, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <div x-show="sidebarOpen" class="flex-1 text-left">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->nama_pengguna }}</p>
                            <p class="text-xs text-gray-500 font-medium">Administrator</p>
                        </div>
                        <i x-show="sidebarOpen" class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" 
                         @click.away="profileOpen = false"
                         x-transition
                         class="dropdown-menu absolute bottom-full left-0 right-0 mb-2 glass-effect rounded-xl shadow-xl py-2">
                        <a href="{{ route('profile.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-user-circle text-blue-500"></i>
                            <span class="text-sm text-gray-700 font-medium">Profil Saya</span>
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-red-50 transition-colors text-left">
                                <i class="fas fa-sign-out-alt text-red-500"></i>
                                <span class="text-sm text-red-600 font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenu = true" 
                class="lg:hidden fixed top-4 left-4 z-50 p-3 glass-effect rounded-xl shadow-lg">
            <i class="fas fa-bars text-gray-600"></i>
        </button>

        <!-- Mobile Sidebar (sama seperti desktop) -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             @click="mobileMenu = false"
             class="lg:hidden fixed inset-0 bg-black/50 z-40">
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden" 
             :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-20'">
            
            <!-- Header -->
            <header class="header-gradient border-b border-gray-200 sticky top-0 z-30 shadow-sm">
                <div class="px-6 lg:px-8 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 ml-16 lg:ml-0">
                            <h2 class="text-3xl font-bold text-gray-800">@yield('header', 'Dashboard')</h2>
                            <p class="text-sm text-gray-500 mt-1 font-medium">@yield('subheader', 'Selamat datang kembali di panel admin')</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Notification Bell -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="relative p-3 hover:bg-gray-100 rounded-xl transition-all transform hover:scale-110">
                                    <i class="fas fa-bell text-gray-600 text-lg"></i>
                                    <span class="notification-badge absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                                </button>
                                
                                <!-- Notification Dropdown -->
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition
                                     class="dropdown-menu absolute right-0 mt-2 w-80 glass-effect rounded-xl shadow-2xl py-2 max-h-96 overflow-y-auto">
                                    <div class="px-4 py-3 border-b border-gray-200">
                                        <h3 class="font-bold text-gray-800">Notifikasi</h3>
                                        <p class="text-xs text-gray-500">Anda memiliki {{ isset($notifications) ? count($notifications) : 2 }} notifikasi baru</p>
                                    </div>
                                    <div class="p-2">
                                        @if(isset($notifications))
                                            @foreach($notifications as $notification)
                                            <a href="#" class="flex items-start space-x-3 p-3 hover:bg-gray-100 rounded-lg transition">
                                                <div class="w-10 h-10 bg-{{ $notification['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <i class="{{ $notification['icon'] }} text-{{ $notification['color'] }}-600"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-800">{{ $notification['title'] }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $notification['message'] }}</p>
                                                    <p class="text-xs text-blue-600 mt-1">{{ $notification['time'] }}</p>
                                                </div>
                                            </a>
                                            @endforeach
                                        @else
                                        <a href="#" class="flex items-start space-x-3 p-3 hover:bg-gray-100 rounded-lg transition">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-clipboard-list text-blue-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">Sistem Normal</p>
                                                <p class="text-xs text-gray-500 mt-1">Semua sistem berjalan normal</p>
                                                <p class="text-xs text-blue-600 mt-1">Baru saja</p>
                                            </div>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Date Display -->
                            <div class="hidden md:block text-right px-4 py-2.5 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                                <p class="text-xs text-gray-500 font-medium">{{ date('l') }}</p>
                                <p class="text-sm font-bold text-gray-700">{{ date('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="glass-effect border-t border-gray-200 py-4 px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between text-sm text-gray-600 space-y-2 md:space-y-0">
                    <p class="font-medium">&copy; {{ date('Y') }} <span class="gradient-text font-bold">SAPRAS</span> - Sistem Manajemen Sarana Prasarana</p>
                    <p class="text-gray-500">Version 1.0.0</p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Notification System -->
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            const colors = {
                success: 'from-blue-400 to-blue-600',
                error: 'from-red-400 to-rose-500',
                warning: 'from-yellow-400 to-amber-500',
                info: 'from-blue-400 to-cyan-500'
            };
            
            toast.className = `animate-slide-in-right glass-effect rounded-xl shadow-2xl p-4 max-w-sm border-l-4 border-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'blue'}-500`;
            toast.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br ${colors[type]} rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                        <i class="fas ${icons[type]} text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'fadeIn 0.3s ease-out reverse';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
        
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    </script>

    @stack('scripts')
</body>
</html>