<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPRAS - Sistem Manajemen Pengaduan Sarana & Prasarana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-light: #3b82f6;
            --accent-green: #10b981;
            --accent-orange: #f59e0b;
            --accent-purple: #8b5cf6;
            --neutral-50: #f9fafb;
            --neutral-100: #f3f4f6;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Navbar Glassmorphism */
        .nav-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        }

        /* Modern Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-purple) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.3);
        }

        .btn-outline {
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
        }

        /* Feature Cards */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e5e7eb;
            background: white;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-blue);
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-icon {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hero Background */
        .hero-bg {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.03) 0%, rgba(139, 92, 246, 0.03) 100%);
        }

        /* Process Step Animation */
        .process-step {
            transition: all 0.3s ease;
        }

        .process-step:hover {
            transform: scale(1.05);
        }

        .process-step:hover .step-number {
            background: linear-gradient(135deg, var(--accent-green) 0%, var(--primary-blue) 100%);
        }

        /* Stats Counter Animation */
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stat-item {
            animation: countUp 0.6s ease-out;
        }

        /* FAQ Accordion */
        details summary {
            list-style: none;
        }

        details summary::-webkit-details-marker {
            display: none;
        }

        details[open] summary .chevron {
            transform: rotate(180deg);
        }

        .chevron {
            transition: transform 0.3s ease;
        }

        /* Scroll Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mobile Menu */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .mobile-menu.active {
            max-height: 500px;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        /* Pulse Dot */
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        .pulse-dot {
            animation: pulse-dot 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-white antialiased">
    <!-- Navbar -->
    <nav class="nav-glass fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-gray-900">SAPRAS</span>
                        <p class="text-xs text-gray-500 hidden sm:block">Pengaduan Digital</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-blue-600 transition font-medium">Fitur</a>
                    <a href="#process" class="text-gray-700 hover:text-blue-600 transition font-medium">Proses</a>
                    <a href="#stats" class="text-gray-700 hover:text-blue-600 transition font-medium">Statistik</a>
                    <a href="#faq" class="text-gray-700 hover:text-blue-600 transition font-medium">FAQ</a>
                </div>

                <!-- CTA Buttons -->
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg">
                        Daftar Gratis
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu lg:hidden">
                <div class="py-6 space-y-4">
                    <a href="#features" class="block text-gray-700 hover:text-blue-600 transition font-medium">Fitur</a>
                    <a href="#process" class="block text-gray-700 hover:text-blue-600 transition font-medium">Proses</a>
                    <a href="#stats" class="block text-gray-700 hover:text-blue-600 transition font-medium">Statistik</a>
                    <a href="#faq" class="block text-gray-700 hover:text-blue-600 transition font-medium">FAQ</a>
                    <div class="pt-4 space-y-3">
                        <a href="{{ route('login') }}" class="block text-center text-gray-700 hover:text-blue-600 transition font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="block btn-primary text-white px-6 py-3 rounded-xl font-semibold text-center shadow-lg">
                            Daftar Gratis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg pt-32 pb-20 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="fade-in">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold mb-6">
                        <span class="pulse-dot w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                        Platform Pengaduan #1 di Indonesia
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Kelola Pengaduan <br>
                        <span class="gradient-text">Sarana & Prasarana</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-xl">
                        Platform digital yang memudahkan pelaporan, pemantauan, dan penyelesaian pengaduan dengan sistem yang transparan dan efisien.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#" class="btn-primary text-white px-8 py-4 rounded-xl font-semibold text-center shadow-lg inline-flex items-center justify-center">
                            <i class="fas fa-rocket mr-2"></i>
                            Mulai Sekarang
                        </a>
                        <a href="#process" class="btn-outline px-8 py-4 rounded-xl font-semibold text-center inline-flex items-center justify-center">
                            <i class="fas fa-play-circle mr-2"></i>
                            Lihat Demo
                        </a>
                    </div>
                </div>

                <!-- Right Illustration -->
                <div class="relative lg:block">
                    <div class="animate-float">
                        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                            <!-- Process Preview Card -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-100">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white flex-shrink-0">
                                        <i class="fas fa-check-circle text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">Lapor Pengaduan</div>
                                        <div class="text-sm text-gray-600">Mudah & cepat</div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-100">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white flex-shrink-0">
                                        <i class="fas fa-clock text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">Pantau Real-time</div>
                                        <div class="text-sm text-gray-600">Status terkini</div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl border border-purple-100">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white flex-shrink-0">
                                        <i class="fas fa-check-double text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">Selesai & Tertutup</div>
                                        <div class="text-sm text-gray-600">Transparansi penuh</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats Mini -->
                            <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">95%</div>
                                    <div class="text-xs text-gray-600">Selesai</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">24h</div>
                                    <div class="text-xs text-gray-600">Avg. Time</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">A+</div>
                                    <div class="text-xs text-gray-600">Rating</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-blue-100 rounded-full blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-purple-100 rounded-full blur-3xl opacity-50"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 px-6 lg:px-8 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 fade-in">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold mb-4">
                    <i class="fas fa-star mr-2"></i>
                    Fitur Unggulan
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Semua yang Anda Butuhkan
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Dilengkapi dengan fitur-fitur canggih untuk mengelola pengaduan dengan mudah dan efisien
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card rounded-2xl p-8 fade-in">
                    <div class="feature-icon w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white mb-6 shadow-lg">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pelaporan Mudah</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Laporkan masalah dalam hitungan detik dengan form intuitif, upload foto, dan pilih lokasi otomatis.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card rounded-2xl p-8 fade-in">
                    <div class="feature-icon w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white mb-6 shadow-lg">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pantau Real-time</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Lihat status pengaduan secara real-time dengan notifikasi instant setiap ada update terbaru.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card rounded-2xl p-8 fade-in">
                    <div class="feature-icon w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white mb-6 shadow-lg">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kolaborasi Tim</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Tim dapat bekerja sama secara transparan untuk menyelesaikan pengaduan lebih cepat dan efisien.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card rounded-2xl p-8 fade-in">
                    <div class="feature-icon w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white mb-6 shadow-lg">
                        <i class="fas fa-database text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Master Data</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola data barang, lokasi, dan petugas dengan sistem yang terstruktur dan mudah diakses.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card rounded-2xl p-8 fade-in">
                    <div class="feature-icon w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white mb-6 shadow-lg">
                        <i class="fas fa-chart-bar text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Laporan & Analitik</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dashboard analitik lengkap dengan visualisasi data yang membantu pengambilan keputusan.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card rounded-2xl p-8 fade-in">
                    <div class="feature-icon w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-white mb-6 shadow-lg">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Aman & Terpercaya</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Keamanan data tingkat enterprise dengan enkripsi SSL dan backup otomatis setiap hari.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-24 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 fade-in">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-50 text-green-700 text-sm font-semibold mb-4">
                    <i class="fas fa-route mr-2"></i>
                    Alur Kerja
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Proses yang Sederhana
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Hanya 4 langkah mudah untuk menyelesaikan pengaduan Anda
                </p>
            </div>

            <!-- Process Steps -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="process-step text-center fade-in">
                    <div class="step-number w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white mx-auto mb-6 text-2xl font-bold shadow-lg">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Lapor</h3>
                    <p class="text-gray-600">
                        Buat laporan dengan detail lengkap dan dokumentasi foto
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="process-step text-center fade-in">
                    <div class="step-number w-20 h-20 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white mx-auto mb-6 text-2xl font-bold shadow-lg">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Verifikasi</h3>
                    <p class="text-gray-600">
                        Admin meninjau dan memvalidasi laporan Anda
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="process-step text-center fade-in">
                    <div class="step-number w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white mx-auto mb-6 text-2xl font-bold shadow-lg">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tindakan</h3>
                    <p class="text-gray-600">
                        Tim petugas melakukan perbaikan sesuai prioritas
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="process-step text-center fade-in">
                    <div class="step-number w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white mx-auto mb-6 text-2xl font-bold shadow-lg">
                        4
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Selesai</h3>
                    <p class="text-gray-600">
                        Pengaduan ditutup dengan dokumentasi hasil kerja
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-24 px-6 lg:px-8 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-4">
                    Bergabunglah dengan Komunitas Kami
                </h2>
                <p class="text-lg text-blue-100 max-w-2xl mx-auto">
                    Mulai gunakan SAPRAS sekarang dan rasakan kemudahan dalam mengelola pengaduan
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 px-6 lg:px-8 bg-gray-50">
        <div class="max-w-4xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 fade-in">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-purple-50 text-purple-700 text-sm font-semibold mb-4">
                    <i class="fas fa-question-circle mr-2"></i>
                    Pertanyaan Umum
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Ada Pertanyaan?
                </h2>
                <p class="text-lg text-gray-600">
                    Temukan jawaban untuk pertanyaan yang sering diajukan
                </p>
            </div>

            <!-- FAQ Accordion -->
            <div class="space-y-4">
                <details class="bg-white rounded-2xl p-6 cursor-pointer group border-2 border-gray-200 hover:border-blue-300 transition fade-in">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        <span>Apa itu SAPRAS?</span>
                        <span class="chevron text-blue-600"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        SAPRAS adalah sistem manajemen pengaduan sarana dan prasarana yang memungkinkan pengguna melaporkan masalah tentang kondisi bangunan, peralatan, dan fasilitas secara digital dengan mudah dan transparan.
                    </p>
                </details>

                <details class="bg-white rounded-2xl p-6 cursor-pointer group border-2 border-gray-200 hover:border-blue-300 transition fade-in">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        <span>Bagaimana cara melaporkan pengaduan?</span>
                        <span class="chevron text-blue-600"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Sangat mudah! Login ke sistem, klik tombol "Buat Laporan", isi detail pengaduan dengan lengkap, upload foto dokumentasi jika ada, pilih lokasi kejadian, lalu klik "Kirim". Anda akan mendapat notifikasi konfirmasi segera.
                    </p>
                </details>

                <details class="bg-white rounded-2xl p-6 cursor-pointer group border-2 border-gray-200 hover:border-blue-300 transition fade-in">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        <span>Berapa lama waktu penyelesaian pengaduan?</span>
                        <span class="chevron text-blue-600"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Waktu penyelesaian bervariasi tergantung tingkat prioritas. Pengaduan mendesak biasanya ditangani dalam 1-2 hari kerja, sedangkan pengaduan dengan prioritas normal diselesaikan dalam 3-5 hari kerja. Anda dapat memantau progress secara real-time.
                    </p>
                </details>

                <details class="bg-white rounded-2xl p-6 cursor-pointer group border-2 border-gray-200 hover:border-blue-300 transition fade-in">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        <span>Apakah data saya aman?</span>
                        <span class="chevron text-blue-600"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Tentu saja! Kami sangat serius dengan keamanan data. Semua data Anda dilindungi dengan enkripsi SSL tingkat enterprise, disimpan di server yang aman, dan di-backup secara otomatis setiap hari. Privasi Anda adalah prioritas kami.
                    </p>
                </details>

                <details class="bg-white rounded-2xl p-6 cursor-pointer group border-2 border-gray-200 hover:border-blue-300 transition fade-in">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        <span>Bisakah saya melihat status pengaduan saya?</span>
                        <span class="chevron text-blue-600"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Ya! Anda dapat memantau status pengaduan Anda secara real-time melalui dashboard. Sistem akan mengirimkan notifikasi instant setiap kali ada update atau perubahan status pada pengaduan Anda.
                    </p>
                </details>

                <details class="bg-white rounded-2xl p-6 cursor-pointer group border-2 border-gray-200 hover:border-blue-300 transition fade-in">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        <span>Apakah ada biaya untuk menggunakan layanan ini?</span>
                        <span class="chevron text-blue-600"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        SAPRAS menyediakan paket gratis untuk pengguna individu dengan fitur dasar yang lengkap. Untuk organisasi atau institusi yang membutuhkan fitur advanced, kami menyediakan paket premium dengan harga yang terjangkau.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-3xl p-12 lg:p-16 relative overflow-hidden shadow-2xl">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                </div>

                <div class="relative z-10">
                    <h2 class="text-4xl lg:text-5xl font-bold text-white mb-4">
                        Siap untuk Memulai?
                    </h2>
                    <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                        Bergabunglah dengan ribuan pengguna yang sudah mempercayai SAPRAS untuk mengelola pengaduan mereka dengan lebih efisien
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition shadow-lg inline-flex items-center justify-center">
                            <i class="fas fa-rocket mr-2"></i>
                            Daftar Gratis Sekarang
                        </a>
                        <a href="#" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 transition inline-flex items-center justify-center">
                            <i class="fas fa-comments mr-2"></i>
                            Hubungi Kami
                        </a>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-12 flex flex-wrap items-center justify-center gap-8 text-white/80">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-2"></i>
                            <span>Gratis untuk mulai</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-2"></i>
                            <span>Tanpa kartu kredit</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-2"></i>
                            <span>Setup 5 menit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
                <!-- Brand -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <div>
                            <span class="font-bold text-xl">SAPRAS</span>
                            <p class="text-sm text-gray-400">Pengaduan Digital</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-6 max-w-sm">
                        Platform modern untuk mengelola pengaduan sarana dan prasarana dengan efisien, transparan, dan mudah digunakan.
                    </p>
                    <!-- Social Media -->
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-lg bg-gray-800 hover:bg-blue-600 flex items-center justify-center transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-gray-800 hover:bg-blue-600 flex items-center justify-center transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-gray-800 hover:bg-blue-600 flex items-center justify-center transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-gray-800 hover:bg-blue-600 flex items-center justify-center transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="font-bold mb-4 text-lg">Produk</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition">Fitur</a></li>
                        <li><a href="#process" class="hover:text-white transition">Cara Kerja</a></li>
                        <li><a href="#" class="hover:text-white transition">Harga</a></li>
                        <li><a href="#" class="hover:text-white transition">Update</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="font-bold mb-4 text-lg">Perusahaan</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="font-bold mb-4 text-lg">Dukungan</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#faq" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition">Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm">
                    &copy; 2025 SAPRAS. All rights reserved. Made with <i class="fas fa-heart text-red-500"></i> in Indonesia
                </p>
                <div class="flex items-center space-x-6 text-sm text-gray-400">
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition">Syarat Layanan</a>
                    <a href="#" class="hover:text-white transition">Cookie</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            const icon = mobileMenuBtn.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    mobileMenu.classList.remove('active');
                    mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                    mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                }
            });
        });

        // Scroll Animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Navbar Background on Scroll
        const navbar = document.querySelector('nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
            } else {
                navbar.style.boxShadow = 'none';
            }
        });

        // Stats Counter Animation
        const statsSection = document.querySelector('#stats');
        let hasAnimated = false;

        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasAnimated) {
                    hasAnimated = true;
                    animateCounters();
                }
            });
        }, { threshold: 0.5 });

        if (statsSection) {
            statsObserver.observe(statsSection);
        }

        function animateCounters() {
            const counters = document.querySelectorAll('.stat-item div:first-child');
            counters.forEach(counter => {
                const target = counter.textContent.replace(/[^0-9]/g, '');
                const isPercentage = counter.textContent.includes('%');
                const duration = 2000;
                const step = Math.ceil(target / (duration / 16));
                let current = 0;

                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = isPercentage ? current + '%' : current.toLocaleString();
                }, 16);
            });
        }

        // Add parallax effect to hero
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroContent = document.querySelector('.hero-bg');
            if (heroContent) {
                heroContent.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>