<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SAPRAS Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #10b981;
            --accent: #f59e0b;
            --neutral: #6b7280;
            --background: #f9fafb;
            --surface: #ffffff;
            --border: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
        }

        body {
            background-color: var(--background);
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .sidebar-link:hover:not(.active) {
            background: rgba(59, 130, 246, 0.08);
            transform: translateX(4px);
            color: var(--primary);
        }

        .card {
            background: var(--surface);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            transform: translate(50%, -50%);
        }

        .progress-bar {
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.5s ease;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .mobile-only {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-only {
                display: block;
            }

            .desktop-only {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-link span {
                display: none;
            }

            .sidebar-link i {
                margin: 0;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-delay-1 {
            animation-delay: 0.1s;
        }

        .animate-delay-2 {
            animation-delay: 0.2s;
        }

        .animate-delay-3 {
            animation-delay: 0.3s;
        }

        .animate-delay-4 {
            animation-delay: 0.4s;
        }

        .animate-delay-5 {
            animation-delay: 0.5s;
        }
    </style>
</head>
<body class="bg-background min-h-screen">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true, mobileMenu: false }">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-16'" 
               class="hidden lg:flex fixed left-0 top-0 h-full bg-white shadow-xl z-50 flex-col transition-all duration-300 border-r border-gray-200">
            
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <div x-show="sidebarOpen" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-sky-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">SAPRAS</h1>
                        <p class="text-xs text-gray-500">Panel Pengguna</p>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bars text-gray-600"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <div class="space-y-1">
                    <a href="{{ route('pengguna.dashboard') }}" 
                       class="sidebar-link {{ request()->routeIs('pengguna.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span x-show="sidebarOpen">Dashboard</span>
                    </a>

                    <a href="{{ route('pengaduan.index') }}" 
                       class="sidebar-link {{ request()->routeIs('pengaduan.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span x-show="sidebarOpen">Pengaduan Saya</span>
                    </a>

                    <a href="{{ route('pengguna.riwayat.index') }}" 
                       class="sidebar-link {{ request()->routeIs('pengguna.riwayat.*') ? 'active' : '' }}">
                        <i class="fas fa-history w-5"></i>
                        <span x-show="sidebarOpen">Riwayat</span>
                    </a>

                    <div x-show="sidebarOpen" class="my-4 border-t border-gray-200"></div>

                    <a href="{{ route('profile.index') }}" 
                       class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle w-5"></i>
                        <span x-show="sidebarOpen">Profil Saya</span>
                    </a>
                </div>
            </nav>

            <div class="p-3 border-t border-gray-100">
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" 
                            class="w-full flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" 
                                 class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-200">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-sky-600 rounded-full flex items-center justify-center ring-2 ring-gray-200">
                                <span class="text-white font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->nama_pengguna, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <div x-show="sidebarOpen" class="flex-1 text-left">
                            <p class="text-sm font-semibold text-gray-700 truncate">{{ auth()->user()->nama_pengguna }}</p>
                            <p class="text-xs text-gray-500">Pengguna</p>
                        </div>
                        <i x-show="sidebarOpen" class="fas fa-ellipsis-v text-gray-400"></i>
                    </button>

                    <div x-show="profileOpen" 
                         @click.away="profileOpen = false"
                         x-transition
                         class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl shadow-xl border border-gray-200 py-2 animate-fade-in-up">
                        <a href="{{ route('profile.index') }}" 
                           class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-user-circle text-gray-400"></i>
                            <span class="text-sm text-gray-700">Profil</span>
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

        <!-- Mobile Menu Toggle -->
        <button @click="mobileMenu = true" 
                class="lg:hidden fixed top-4 left-4 z-50 p-3 bg-white rounded-xl shadow-lg">
            <i class="fas fa-bars text-gray-700"></i>
        </button>

        <!-- Mobile Overlay -->
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

        <!-- Mobile Sidebar -->
        <aside x-show="mobileMenu"
               x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="lg:hidden fixed left-0 top-0 h-full w-64 bg-white shadow-2xl z-50 flex flex-col">
            
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-sky-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">SAPRAS</h1>
                        <p class="text-xs text-gray-500">Panel Pengguna</p>
                    </div>
                </div>
                <button @click="mobileMenu = false" class="p-2">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <div class="space-y-1">
                    <a href="{{ route('pengguna.dashboard') }}" 
                       class="sidebar-link {{ request()->routeIs('pengguna.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('pengaduan.index') }}" 
                       class="sidebar-link {{ request()->routeIs('pengaduan.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span>Pengaduan Saya</span>
                    </a>

                    <div class="my-4 border-t border-gray-200"></div>

                    <a href="{{ route('profile.index') }}" 
                       class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle w-5"></i>
                        <span>Profil Saya</span>
                    </a>
                </div>
            </nav>

            <div class="p-3 border-t border-gray-100">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" 
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-sky-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->nama_pengguna, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->nama_pengguna }}</p>
                            <p class="text-xs text-gray-500">Pengguna</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-600" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden" 
             :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'">
            
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
                <div class="px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 ml-16 lg:ml-0">
                            <h2 class="text-xl font-bold text-gray-800">@yield('header')</h2>
                            <p class="text-sm text-gray-500 mt-0.5">@yield('subheader')</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button class="relative p-2.5 hover:bg-gray-100 rounded-xl transition-colors">
                                <i class="fas fa-bell text-gray-600"></i>
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div class="hidden md:block text-right px-3 py-1.5 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">{{ date('l') }}</p>
                                <p class="text-sm font-semibold text-gray-700">{{ date('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="p-6 lg:p-8">
                    @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm animate-fade-in-up">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-emerald-500 text-xl mr-3"></i>
                            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm animate-fade-in-up">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <footer class="bg-white border-t border-gray-200 py-4 px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between text-sm text-gray-600 space-y-2 md:space-y-0">
                    <p>&copy; {{ date('Y') }} SAPRAS - Sistem Manajemen Sarana Prasarana</p>
                    <p class="text-gray-500">v1.0.0</p>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>