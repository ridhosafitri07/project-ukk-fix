<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPRAS - Sistem Manajemen Pengaduan Sarana & Prasarana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .gradient-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }
        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navbar -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-md shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-lg gradient-btn flex items-center justify-center text-white font-bold">
                        <i class="fas fa-building"></i>
                    </div>
                    <span class="font-bold text-xl text-gray-900">SAPRAS</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-gray-900 transition">Fitur</a>
                    <a href="#process" class="text-gray-600 hover:text-gray-900 transition">Proses</a>
                    <a href="#faq" class="text-gray-600 hover:text-gray-900 transition">FAQ</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="gradient-btn text-white px-4 py-2 rounded-lg font-medium">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-16 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                        Manajemen Pengaduan <span class="gradient-text">Sarana & Prasarana</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Platform digital untuk melapor, mengelola, dan menyelesaikan pengaduan tentang kondisi sarana dan prasarana dengan efisien dan transparan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="gradient-btn text-white px-6 py-2 rounded-lg font-semibold text-center inline-block text-sm">
                                Buka Dashboard <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="gradient-btn text-white px-6 py-2 rounded-lg font-semibold text-center inline-block text-sm">
                                Mulai Sekarang <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                            <a href="{{ route('login') }}" class="border-2 border-gray-300 text-gray-900 px-6 py-2 rounded-lg font-semibold text-center hover:border-gray-400 transition inline-block text-sm">
                                Sudah Punya Akun?
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="relative h-64 md:h-80 flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl blur-2xl opacity-50"></div>
                    <div class="relative">
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 shadow-lg">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3 p-2 bg-white rounded-lg">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600 text-sm">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="text-gray-700 font-medium text-sm">Lapor Pengaduan</span>
                                </div>
                                <div class="flex items-center space-x-3 p-2 bg-white rounded-lg">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 text-sm">
                                        <i class="fas fa-spinner"></i>
                                    </div>
                                    <span class="text-gray-700 font-medium text-sm">Pantau Proses</span>
                                </div>
                                <div class="flex items-center space-x-3 p-2 bg-white rounded-lg">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 text-sm">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <span class="text-gray-700 font-medium text-sm">Selesai & Tertutup</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Fitur Unggulan</h2>
                <p class="text-lg text-gray-600">Semua yang Anda butuhkan untuk mengelola pengaduan dengan mudah</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-xl p-6">
                    <div class="w-12 h-12 rounded-lg gradient-btn flex items-center justify-center text-white mb-4">
                        <i class="fas fa-file-alt text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Pelaporan Mudah</h3>
                    <p class="text-gray-600 text-sm">Buat laporan pengaduan dengan detail lengkap, upload foto, dan pilih lokasi dengan mudah.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-xl p-6">
                    <div class="w-12 h-12 rounded-lg gradient-btn flex items-center justify-center text-white mb-4">
                        <i class="fas fa-tracking text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Pantau Real-time</h3>
                    <p class="text-gray-600 text-sm">Pantau status pengaduan Anda secara real-time dan dapatkan notifikasi instant.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-xl p-6">
                    <div class="w-12 h-12 rounded-lg gradient-btn flex items-center justify-center text-white mb-4">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Kolaborasi Tim</h3>
                    <p class="text-gray-600 text-sm">Tim dapat berkolaborasi secara transparan untuk menyelesaikan pengaduan lebih cepat.</p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white rounded-xl p-6">
                    <div class="w-12 h-12 rounded-lg gradient-btn flex items-center justify-center text-white mb-4">
                        <i class="fas fa-database text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Master Data</h3>
                    <p class="text-gray-600 text-sm">Kelola data master barang, lokasi, dan petugas dengan sistem yang terstruktur.</p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white rounded-xl p-6">
                    <div class="w-12 h-12 rounded-lg gradient-btn flex items-center justify-center text-white mb-4">
                        <i class="fas fa-chart-bar text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Laporan & Analitik</h3>
                    <p class="text-gray-600 text-sm">Dapatkan insight mendalam dengan laporan lengkap dan visualisasi data yang mudah.</p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white rounded-xl p-6">
                    <div class="w-12 h-12 rounded-lg gradient-btn flex items-center justify-center text-white mb-4">
                        <i class="fas fa-shield-alt text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Aman & Terpercaya</h3>
                    <p class="text-gray-600 text-sm">Data dilindungi dengan enkripsi tingkat enterprise dan backup otomatis.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Alur Kerja</h2>
                <p class="text-lg text-gray-600">Proses pengaduan yang sederhana dan efisien</p>
            </div>

            <div class="grid md:grid-cols-4 gap-6 md:gap-4">
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full gradient-btn flex items-center justify-center text-white mx-auto mb-4 text-lg font-bold">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Lapor</h3>
                    <p class="text-gray-600 text-sm">Buat laporan pengaduan dengan detail dan foto dokumentasi</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-full gradient-btn flex items-center justify-center text-white mx-auto mb-4 text-lg font-bold">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Verifikasi</h3>
                    <p class="text-gray-600 text-sm">Admin meninjau dan memverifikasi laporan Anda</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-full gradient-btn flex items-center justify-center text-white mx-auto mb-4 text-lg font-bold">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Tindakan</h3>
                    <p class="text-gray-600 text-sm">Petugas melakukan perbaikan atau penanganan</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-full gradient-btn flex items-center justify-center text-white mx-auto mb-4 text-lg font-bold">
                        4
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Selesai</h3>
                    <p class="text-gray-600 text-sm">Laporan ditandai selesai dengan dokumentasi hasil</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 px-4 bg-gradient-to-br from-purple-600 to-pink-600">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-6 text-white">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-1">{{ \App\Models\Pengaduan::count() }}</div>
                    <p class="text-purple-100 text-sm">Pengaduan Tercatat</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-1">{{ \App\Models\Pengaduan::where('status', 'Selesai')->count() }}</div>
                    <p class="text-purple-100 text-sm">Pengaduan Selesai</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-1">{{ \App\Models\User::count() }}</div>
                    <p class="text-purple-100 text-sm">Pengguna Aktif</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-1">100%</div>
                    <p class="text-purple-100 text-sm">Transparansi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 px-4 bg-gray-50">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Pertanyaan Umum</h2>
                <p class="text-lg text-gray-600">Cari jawaban untuk pertanyaan Anda</p>
            </div>

            <div class="space-y-3">
                <details class="bg-white rounded-lg p-5 cursor-pointer group border border-gray-200">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-sm">
                        Apa itu SAPRAS?
                        <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-3 text-sm">SAPRAS adalah sistem manajemen pengaduan sarana dan prasarana yang memungkinkan pengguna melaporkan masalah tentang kondisi bangunan, peralatan, dan fasilitas secara digital.</p>
                </details>

                <details class="bg-white rounded-lg p-5 cursor-pointer group border border-gray-200">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-sm">
                        Bagaimana cara melaporkan pengaduan?
                        <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-3 text-sm">Login ke sistem, klik "Buat Laporan", isi detail pengaduan, upload foto jika ada, pilih lokasi, lalu klik "Kirim".</p>
                </details>

                <details class="bg-white rounded-lg p-5 cursor-pointer group border border-gray-200">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-sm">
                        Berapa lama waktu penyelesaian pengaduan?
                        <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-3 text-sm">Waktu penyelesaian tergantung jenis pengaduan. Mendesak ditangani 1-2 hari kerja, biasa dalam 3-5 hari kerja.</p>
                </details>

                <details class="bg-white rounded-lg p-5 cursor-pointer group border border-gray-200">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-sm">
                        Apakah data saya aman?
                        <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-3 text-sm">Ya, data Anda dilindungi dengan enkripsi tingkat enterprise dan backup otomatis setiap hari.</p>
                </details>

                <details class="bg-white rounded-lg p-5 cursor-pointer group border border-gray-200">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-sm">
                        Bisakah saya melihat status pengaduan saya?
                        <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-3 text-sm">Tentu saja! Lihat status real-time di dashboard dengan notifikasi instant untuk setiap update.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Siap Memulai?</h2>
            <p class="text-lg text-gray-600 mb-6">Bergabunglah dengan pengguna lain untuk mengelola pengaduan dengan lebih efisien</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="gradient-btn text-white px-6 py-2 rounded-lg font-semibold text-center inline-block text-sm">
                        Buka Dashboard <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="gradient-btn text-white px-6 py-2 rounded-lg font-semibold text-center inline-block text-sm">
                        Daftar Sekarang <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-gray-300 text-gray-900 px-8 py-4 rounded-lg font-semibold text-center hover:border-gray-400 transition inline-block text-lg">
                        Sudah Punya Akun?
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-10 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-6 mb-6">
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <div class="w-10 h-10 rounded-lg gradient-btn flex items-center justify-center text-sm">
                            <i class="fas fa-building"></i>
                        </div>
                        <span class="font-bold">SAPRAS</span>
                    </div>
                    <p class="text-gray-400 text-sm">Sistem manajemen pengaduan sarana dan prasarana.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3 text-sm">Navigasi</h4>
                    <ul class="space-y-1 text-gray-400 text-sm">
                        <li><a href="#features" class="hover:text-white transition">Fitur</a></li>
                        <li><a href="#process" class="hover:text-white transition">Proses</a></li>
                        <li><a href="#faq" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3 text-sm">Layanan</h4>
                    <ul class="space-y-1 text-gray-400 text-sm">
                        <li><a href="@auth {{ url('/dashboard') }} @else {{ route('login') }} @endauth" class="hover:text-white transition">Dashboard</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3 text-sm">Kontak</h4>
                    <ul class="space-y-1 text-gray-400 text-sm">
                        <li><i class="fas fa-phone mr-2"></i> +62 XXX-XXXX-XXXX</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@sapras.id</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; 2025 SAPRAS. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, details').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>
