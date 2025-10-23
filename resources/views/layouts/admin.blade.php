<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - SAPRAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fungsi untuk mempertahankan sidebar
        document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan path URL saat ini
            const currentPath = window.location.pathname;
            
            // Fungsi untuk mengatur active state pada menu
            const setActiveMenu = () => {
                const menuItems = document.querySelectorAll('.sidebar-menu-item');
                menuItems.forEach(item => {
                    const link = item.getAttribute('href');
                    if (currentPath.includes(link.replace(/^\/?admin/, ''))) {
                        item.classList.add('bg-blue-900');
                    } else {
                        item.classList.remove('bg-blue-900');
                        item.classList.add('hover:bg-blue-700');
                    }
                });
            };

            // Panggil fungsi saat halaman dimuat
            setActiveMenu();
        });
    </script>
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
            background: #666;
        }
        /* Fix sidebar and content layout */
        .sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            width: 256px !important;
            z-index: 50 !important;
            overflow-y: auto !important;
        }
        /* Active menu item */
        .sidebar-menu-item.active {
            background-color: #1e3a8a !important;
        }
        .sidebar-menu-item:not(.active):hover {
            background-color: #1e40af !important;
        }
        .main-content {
            margin-left: 256px;
            min-height: 100vh;
            max-width: calc(100vw - 256px);
            overflow-x: hidden;
        }
        .content-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 2rem;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="sidebar bg-blue-800 text-white">
        <div class="flex items-center justify-center h-16 bg-blue-900">
            <h1 class="text-2xl font-bold">SAPRAS</h1>
        </div>
        <nav class="mt-8">
            <div class="px-4">
                <h2 class="text-xs text-blue-300 uppercase tracking-wider">Menu</h2>
                <div class="mt-3 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-menu-item flex items-center px-4 py-3 rounded-lg transition-colors"
                       data-path="dashboard">
                        <i class="fas fa-home mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.sarpras.index') }}" 
                       class="sidebar-menu-item flex items-center px-4 py-3 rounded-lg transition-colors"
                       data-path="sarpras">
                        <i class="fas fa-boxes mr-3"></i>
                        <span>Manajemen Sarpras</span>
                    </a>
                    <a href="{{ route('admin.pengaduan.index') }}" 
                       class="sidebar-menu-item flex items-center px-4 py-3 rounded-lg transition-colors"
                       data-path="pengaduan">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        <span>Pengaduan</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-menu-item flex items-center px-4 py-3 rounded-lg transition-colors"
                       data-path="users">
                        <i class="fas fa-users mr-3"></i>
                        <span>Users</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="bg-white shadow-sm">
            <div class="content-container">
                <div class="flex justify-between items-center py-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                        <p class="text-gray-600">@yield('subheader')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama_pengguna) }}" class="h-8 w-8 rounded-full">
                                <span>{{ auth()->user()->nama_pengguna }}</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="content-container">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>