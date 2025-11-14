<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SAPRAS</title>
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
        .password-strength {
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            margin-top: 6px;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            width: 0%;
            background: #ef4444;
            transition: all 0.3s ease;
            border-radius: 3px;
        }
        .strength-weak { background: #ef4444; width: 33%; }
        .strength-medium { background: #eab308; width: 66%; }
        .strength-strong { background: #22c55e; width: 100%; }
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
                    <p class="text-purple-100 text-lg">Bergabung dengan ribuan pengguna</p>
                </div>

                <div class="space-y-6">
                    <div class="flex space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-rocket text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Cepat & Mudah</h3>
                            <p class="text-purple-100 text-sm">Daftar hanya dalam beberapa menit</p>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Tim Support</h3>
                            <p class="text-purple-100 text-sm">Kami siap membantu Anda 24/7</p>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-star text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Premium Features</h3>
                            <p class="text-purple-100 text-sm">Akses semua fitur tanpa biaya tambahan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section - Register Form -->
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h1>
                    <p class="text-gray-600">Daftarkan akun Anda untuk memulai</p>
                </div>

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

                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="nama_pengguna" class="block text-sm font-medium text-gray-900 mb-2">
                            <i class="fas fa-user mr-2 text-gray-600"></i>Nama Lengkap
                        </label>
                        <input
                            id="nama_pengguna"
                            name="nama_pengguna"
                            type="text"
                            autocomplete="name"
                            required
                            class="input-field w-full px-4 py-3 rounded-lg text-gray-900 placeholder-gray-400"
                            placeholder="Masukkan nama lengkap Anda"
                            value="{{ old('nama_pengguna') }}"
                        >
                        @error('nama_pengguna')
                            <p class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-circle text-xs mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-900 mb-2">
                            <i class="fas fa-at mr-2 text-gray-600"></i>Username
                        </label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            autocomplete="username"
                            required
                            class="input-field w-full px-4 py-3 rounded-lg text-gray-900 placeholder-gray-400"
                            placeholder="Pilih username unik Anda"
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
                            autocomplete="new-password"
                            required
                            class="input-field w-full px-4 py-3 rounded-lg text-gray-900 placeholder-gray-400"
                            placeholder="Buat password yang kuat"
                            oninput="updatePasswordStrength()"
                        >
                        <div class="password-strength">
                            <div id="passwordStrengthBar" class="password-strength-bar"></div>
                        </div>
                        <p id="passwordStrengthText" class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-circle text-xs mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">
                            <i class="fas fa-check-circle mr-2 text-gray-600"></i>Konfirmasi Password
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="input-field w-full px-4 py-3 rounded-lg text-gray-900 placeholder-gray-400"
                            placeholder="Konfirmasi password Anda"
                        >
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-circle text-xs mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start space-x-2">
                        <input type="checkbox" id="terms" required class="w-4 h-4 rounded border-gray-300 text-purple-600 mt-1">
                        <label for="terms" class="text-sm text-gray-600">
                            Saya setuju dengan <a href="#" class="text-purple-600 font-medium hover:underline">Syarat & Ketentuan</a>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="gradient-btn w-full py-3 px-4 text-white font-semibold rounded-lg flex items-center justify-center space-x-2 mt-6"
                    >
                        <span>Buat Akun</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-center text-gray-600 text-sm">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-purple-600 hover:text-purple-700 transition">
                            Masuk sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updatePasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrengthText');
            
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            
            if (strength <= 1) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = '❌ Lemah - Tambahkan karakter lebih banyak';
                strengthText.className = 'text-xs text-red-500 mt-1';
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = '⚠️ Sedang - Bagus, tapi bisa lebih kuat';
                strengthText.className = 'text-xs text-yellow-600 mt-1';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = '✓ Kuat - Password Anda aman';
                strengthText.className = 'text-xs text-green-600 mt-1';
            }
        }
    </script>
</body>
</html>