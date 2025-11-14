<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SAPRAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .gradient-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .input-field {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            background: #fff;
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .feature-list {
            animation: slideInLeft 0.6s ease;
        }
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .form-container {
            animation: slideInRight 0.6s ease;
        }
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Section - Features -->
        <div class="hidden lg:flex lg:w-1/2 gradient-bg text-white items-center justify-center p-12">
            <div class="feature-list max-w-md">
                <div class="mb-12">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="icon-circle">
                            <i class="fas fa-building"></i>
                        </div>
                        <span class="text-3xl font-bold">SAPRAS</span>
                    </div>
                    <p class="text-purple-100 text-lg">Sistem Manajemen Pengaduan Sarana & Prasarana</p>
                </div>

                <div class="space-y-6">
                    <div class="flex space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Laporan Mudah</h3>
                            <p class="text-purple-100 text-sm">Buat laporan pengaduan dengan detail lengkap</p>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-tracking text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Pantau Real-time</h3>
                            <p class="text-purple-100 text-sm">Lihat status pengaduan Anda setiap saat</p>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lock text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Data Aman</h3>
                            <p class="text-purple-100 text-sm">Enkripsi tingkat enterprise untuk data Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-8">
            <div class="form-container w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="flex items-center justify-center space-x-2 mb-2">
                        <div class="icon-circle">
                            <i class="fas fa-building"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">SAPRAS</span>
                    </div>
                </div>

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Masuk ke SAPRAS</h1>
                    <p class="text-gray-600">Kelola pengaduan Anda dengan efisien</p>
                </div>

                @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p class="mb-1">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-900 mb-2">
                            <i class="fas fa-user mr-2 text-gray-600"></i>Username
                        </label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            autocomplete="username"
                            required
                            class="input-field w-full px-4 py-3 rounded-lg text-gray-900 placeholder-gray-400"
                            placeholder="Masukkan username Anda"
                            value="{{ old('username') }}"
                        >
                        @error('username')
                            <p class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-circle text-xs mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-600"></i>Password
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="input-field w-full px-4 py-3 rounded-lg text-gray-900 placeholder-gray-400"
                            placeholder="Masukkan password Anda"
                        >
                        @error('password')
                            <p class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-circle text-xs mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="gradient-btn w-full py-3 px-4 text-white font-semibold rounded-lg flex items-center justify-center space-x-2"
                    >
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-center text-gray-600 text-sm">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-purple-600 hover:text-purple-700 transition">
                            Daftar sekarang
                        </a>
                    </p>
                </div>

                <!-- Demo Info -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-700 mb-2">
                        <i class="fas fa-info-circle mr-2"></i><strong>Demo Account:</strong>
                    </p>
                    <p class="text-xs text-blue-700">Username: admin / Password: password</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>