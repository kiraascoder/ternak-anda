<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pakeng-Ternak - Sistem Manajemen Peternakan Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16a34a',
                        secondary: '#065f46',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #16a34a 0%, #065f46 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Hamburger menu animations */
        .hamburger-line {
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .hamburger-active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .hamburger-active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .hamburger-active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile menu animations */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        /* Backdrop overlay */
        .menu-backdrop {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .menu-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .livestock-tab.active {
            background-color: #16a34a;
            color: white;
        }

        .livestock-timeline {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="bg-primary rounded-lg p-2">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span class="ml-3 text-xl font-bold text-gray-900">Pakeng-Ternak</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-primary transition-colors">Beranda</a>
                    <a href="#about" class="text-gray-700 hover:text-primary transition-colors">Tentang</a>
                    <a href="#contact" class="text-gray-700 hover:text-primary transition-colors">Kontak</a>
                    <a href="{{ route('informasi.index') }}"
                        class="text-gray-700 hover:text-primary transition-colors">Informasi</a>
                    <a href="{{ route('pakan.index') }}"
                        class="text-gray-700 hover:text-primary transition-colors">Pakan</a>
                    <div class="flex space-x-4">
                        <button
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                            <a href="{{ route('admin.login') }}">Masuk</a>
                        </button>
                        <button
                            class="border border-primary text-primary px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <a href="{{ route('admin.register') }}">
                                Daftar
                            </a>
                        </button>
                    </div>
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="md:hidden flex items-center">
                    <button id="hamburger-btn" class="hamburger-btn p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-6 h-6 flex flex-col justify-center space-y-1">
                            <div class="hamburger-line w-6 h-0.5 bg-gray-600"></div>
                            <div class="hamburger-line w-6 h-0.5 bg-gray-600"></div>
                            <div class="hamburger-line w-6 h-0.5 bg-gray-600"></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu md:hidden fixed top-16 left-0 w-full h-full bg-white z-40 shadow-lg">
            <div class="px-4 py-6 space-y-6">
                <!-- Navigation Links -->
                <div class="space-y-4">
                    <a href="#home"
                        class="block text-lg text-gray-700 hover:text-primary transition-colors py-2 border-b border-gray-100">
                        Beranda
                    </a>
                    <a href="#about"
                        class="block text-lg text-gray-700 hover:text-primary transition-colors py-2 border-b border-gray-100">
                        Tentang
                    </a>
                    <a href="#contact"
                        class="block text-lg text-gray-700 hover:text-primary transition-colors py-2 border-b border-gray-100">
                        Kontak
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4 pt-6">
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                        <a href="{{ route('admin.login') }}">Masuk</a>
                    </button>
                    <button
                        class="w-full border border-primary text-primary px-4 py-3 rounded-lg hover:bg-primary hover:text-white transition-colors font-semibold">
                        <a href="{{ route('admin.register') }}">
                            Daftar
                        </a>
                    </button>
                </div>

                <!-- Contact Info -->
                <div class="pt-8 border-t border-gray-100">
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            support@pakeng-ternak.com
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            +62 812-3456-7890
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop Overlay -->
        <div id="menu-backdrop" class="menu-backdrop fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-bg min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Sistem Manajemen <span class="text-yellow-300">Peternakan</span>
                    </h1>
                    <p class="text-lg sm:text-xl mb-8 text-green-100">
                        Kelola ternak Anda dengan mudah, pantau kesehatan, dapatkan konsultasi ahli, dan tingkatkan
                        produktivitas peternakan dengan teknologi terdepan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button
                            class="bg-yellow-400 text-gray-900 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold hover:bg-yellow-300 transition-colors">
                            <a href="{{ route('admin.register') }}">
                                Mulai Sekarang
                            </a>
                        </button>
                        <button
                            class="border-2 border-white text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            <a href="{{ route('admin.login') }}">
                                Kelola Ternak Anda
                            </a>
                        </button>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="floating-animation">
                        <svg class="w-full h-96 text-green-200" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="livestock-age-info" class="py-20 bg-gradient-to-br from-green-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-primary/10 px-4 py-2 rounded-full mb-6">
                    <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-primary font-semibold">Panduan Umur Ternak</span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    Tahapan Pertumbuhan <span class="text-primary">Ternak</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Panduan lengkap mengenai tahapan umur ternak dari lahir hingga siap panen.
                    Ketahui kapan waktu yang tepat untuk setiap fase perawatan.
                </p>
            </div>

            <!-- Livestock Type Tabs -->
            <div class="flex justify-center mb-12">
                <div class="bg-white rounded-lg shadow-lg p-2">
                    <div class="flex space-x-2">
                        <button
                            class="livestock-tab active bg-primary text-white px-6 py-3 rounded-lg font-semibold transition-all"
                            data-type="sapi">
                            🐄 Sapi
                        </button>
                        <button
                            class="livestock-tab text-gray-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all"
                            data-type="kambing">
                            🐐 Kambing
                        </button>
                        <button
                            class="livestock-tab text-gray-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all"
                            data-type="ayam">
                            🐓 Ayam
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cattle Timeline -->
            <div id="sapi-timeline" class="livestock-timeline">
                <div class="relative">
                    <!-- Timeline Line -->
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-gradient-to-b from-primary to-secondary h-full">
                    </div>

                    <!-- Timeline Items -->
                    <div class="space-y-12">
                        <!-- Birth - 3 Months -->
                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8 text-right">
                                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                                    <div class="flex items-center justify-end mb-4">
                                        <div class="bg-red-100 p-3 rounded-full">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pedet (0-3 Bulan)</h3>
                                    <p class="text-gray-600 mb-4">Fase kritis pembentukan sistem kekebalan tubuh</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center justify-end">
                                            <span class="text-gray-700">• Pemberian kolostrum 6 jam pertama</span>
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <span class="text-gray-700">• Susu induk/formula khusus pedet</span>
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <span class="text-gray-700">• Vaksinasi dasar</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                1
                            </div>
                            <div class="flex-1 pl-8"></div>
                        </div>

                        <!-- 3-8 Months -->
                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8"></div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                2
                            </div>
                            <div class="flex-1 pl-8">
                                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                                    <div class="flex items-center mb-4">
                                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Muda (3-8 Bulan)</h3>
                                    <p class="text-gray-600 mb-4">Masa pertumbuhan pesat dan penyapihan</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center">
                                            <span class="text-gray-700">• Mulai konsumsi rumput dan konsentrat</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-gray-700">• Penyapihan bertahap</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-gray-700">• Pertumbuhan 0.8-1.2 kg/hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 8-15 Months -->
                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8 text-right">
                                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                                    <div class="flex items-center justify-end mb-4">
                                        <div class="bg-blue-100 p-3 rounded-full">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Remaja (8-15 Bulan)</h3>
                                    <p class="text-gray-600 mb-4">Fase penggemukan dan persiapan reproduksi</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center justify-end">
                                            <span class="text-gray-700">• Pemberian pakan berkualitas tinggi</span>
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <span class="text-gray-700">• Program penggemukan intensif</span>
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <span class="text-gray-700">• Berat 250-350 kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                3
                            </div>
                            <div class="flex-1 pl-8"></div>
                        </div>

                        <!-- 15-24 Months -->
                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8"></div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                ✓
                            </div>
                            <div class="flex-1 pl-8">
                                <div
                                    class="bg-gradient-to-r from-green-400 to-green-600 rounded-xl shadow-lg p-6 text-white card-hover">
                                    <div class="flex items-center mb-4">
                                        <div class="bg-white/20 p-3 rounded-full mr-4">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-bold mb-2">Siap Panen (15-24 Bulan)</h3>
                                    <p class="mb-4 opacity-90">Periode optimal untuk penjualan atau breeding</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center">
                                            <span>• Berat hidup 350-450 kg</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span>• Karkas berkualitas tinggi</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span>• Siap untuk breeding (betina)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Goat Timeline (Hidden by default) -->
            <div id="kambing-timeline" class="livestock-timeline hidden">
                <div class="relative">
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-gradient-to-b from-primary to-secondary h-full">
                    </div>

                    <div class="space-y-12">
                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8 text-right">
                                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Cempe (0-3 Bulan)</h3>
                                    <p class="text-gray-600 mb-4">Fase pemberian susu induk dan adaptasi</p>
                                    <div class="space-y-2 text-sm text-gray-700">
                                        <div>• ASI eksklusif 2 bulan pertama</div>
                                        <div>• Mulai konsumsi rumput halus</div>
                                        <div>• Vaksinasi dan vitamin</div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                1</div>
                            <div class="flex-1 pl-8"></div>
                        </div>

                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8"></div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                2</div>
                            <div class="flex-1 pl-8">
                                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Muda (3-8 Bulan)</h3>
                                    <p class="text-gray-600 mb-4">Masa pertumbuhan aktif dan penyapihan</p>
                                    <div class="space-y-2 text-sm text-gray-700">
                                        <div>• Penyapihan di bulan ke-3</div>
                                        <div>• Pakan rumput dan konsentrat</div>
                                        <div>• Berat 15-25 kg</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8 text-right">
                                <div
                                    class="bg-gradient-to-r from-green-400 to-green-600 rounded-xl shadow-lg p-6 text-white card-hover">
                                    <h3 class="text-xl font-bold mb-2">Siap Panen (8-12 Bulan)</h3>
                                    <p class="mb-4 opacity-90">Periode optimal untuk penjualan</p>
                                    <div class="space-y-2 text-sm">
                                        <div>• Berat hidup 25-40 kg</div>
                                        <div>• Kualitas daging prima</div>
                                        <div>• ROI maksimal</div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                ✓</div>
                            <div class="flex-1 pl-8"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chicken Timeline (Hidden by default) -->
            <div id="ayam-timeline" class="livestock-timeline hidden">
                <div class="relative">
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-gradient-to-b from-primary to-secondary h-full">
                    </div>

                    <div class="space-y-12">
                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8 text-right">
                                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">DOC - Chick (0-21 Hari)</h3>
                                    <p class="text-gray-600 mb-4">Fase starter dengan perawatan intensif</p>
                                    <div class="space-y-2 text-sm text-gray-700">
                                        <div>• Suhu brooder 32-35°C</div>
                                        <div>• Pakan starter protein tinggi</div>
                                        <div>• Vaksinasi ND + IB</div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                1</div>
                            <div class="flex-1 pl-8"></div>
                        </div>

                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8"></div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                2</div>
                            <div class="flex-1 pl-8">
                                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Grower (22-35 Hari)</h3>
                                    <p class="text-gray-600 mb-4">Masa pertumbuhan pesat</p>
                                    <div class="space-y-2 text-sm text-gray-700">
                                        <div>• Pakan grower</div>
                                        <div>• Suhu normal 28-30°C</div>
                                        <div>• Berat 1.2-1.5 kg</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8 text-right">
                                <div
                                    class="bg-gradient-to-r from-green-400 to-green-600 rounded-xl shadow-lg p-6 text-white card-hover">
                                    <h3 class="text-xl font-bold mb-2">Siap Panen (35-42 Hari)</h3>
                                    <p class="mb-4 opacity-90">Periode panen untuk broiler</p>
                                    <div class="space-y-2 text-sm">
                                        <div>• Berat hidup 1.8-2.2 kg</div>
                                        <div>• FCR optimal 1.5-1.7</div>
                                        <div>• Kualitas karkas terbaik</div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                ✓</div>
                            <div class="flex-1 pl-8"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="mt-20 bg-white rounded-2xl shadow-xl p-8 lg:p-12">
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">💡 Tips Penting Manajemen Umur Ternak</h3>
                    <p class="text-gray-600">Faktor-faktor yang mempengaruhi keberhasilan di setiap fase</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-blue-50 rounded-xl">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Nutrisi Seimbang</h4>
                        <p class="text-gray-600 text-sm">Pemberian pakan sesuai kebutuhan di setiap fase pertumbuhan
                        </p>
                    </div>

                    <div class="text-center p-6 bg-green-50 rounded-xl">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Vaksinasi Rutin</h4>
                        <p class="text-gray-600 text-sm">Program vaksinasi tepat waktu untuk mencegah penyakit</p>
                    </div>

                    <div class="text-center p-6 bg-yellow-50 rounded-xl">
                        <div
                            class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Lingkungan Ideal</h4>
                        <p class="text-gray-600 text-sm">Kontrol suhu, kelembaban, dan ventilasi kandang</p>
                    </div>

                    <div class="text-center p-6 bg-purple-50 rounded-xl">
                        <div
                            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Monitoring Rutin</h4>
                        <p class="text-gray-600 text-sm">Pencatatan berat badan dan kondisi kesehatan berkala</p>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center mt-16">
                <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-4">Siap Mengelola Ternak dengan Lebih Baik?</h3>
                    <p class="text-lg mb-6 opacity-90">Gunakan Pakeng-Ternak untuk memantau dan mengelola setiap fase
                        pertumbuhan ternak Anda</p>
                    <button
                        class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <a href="#" class="text-primary">Mulai Sekarang</a>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Mengapa Memilih Pakeng-Ternak?</h2>
                    <p class="text-base sm:text-lg text-gray-600 mb-8">
                        Kami memahami tantangan yang dihadapi peternak modern. Pakeng-Ternak hadir sebagai solusi
                        teknologi yang mengintegrasikan semua aspek manajemen peternakan dalam satu platform yang mudah
                        digunakan.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div
                                class="bg-primary w-6 h-6 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Multi-Role Dashboard</h3>
                                <p class="text-gray-600">Dashboard khusus untuk peternak, admin, dan penyuluh dengan
                                    fitur sesuai kebutuhan</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="bg-primary w-6 h-6 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Monitoring Kesehatan Real-time</h3>
                                <p class="text-gray-600">Pantau kondisi kesehatan ternak dan dapatkan rekomendasi
                                    perawatan tepat waktu</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="bg-primary w-6 h-6 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Laporan Komprehensif</h3>
                                <p class="text-gray-600">Generate laporan detail untuk analisis bisnis dan pengambilan
                                    keputusan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl">
                    <div class="text-center">
                        <div
                            class="bg-primary/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Siap Memulai?</h3>
                        <p class="text-gray-600 mb-6">Bergabunglah dengan ribuan peternak yang telah merasakan
                            kemudahan Pakeng-Ternak</p>
                        <button
                            class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors w-full">
                            Daftar Gratis Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
                <p class="text-lg sm:text-xl text-gray-600">Tim support kami siap membantu Anda 24/7</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600">support@pakeng-ternak.com</p>
                </div>
                <div class="text-center">
                    <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Telepon</h3>
                    <p class="text-gray-600">+62 812-3456-7890</p>
                </div>
                <div class="text-center">
                    <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Alamat</h3>
                    <p class="text-gray-600">Makassar, Sulawesi Selatan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-6">
                        <div class="bg-primary rounded-lg p-2">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span class="ml-3 text-xl font-bold">Pakeng-Ternak</span>
                    </div>
                    <p class="text-gray-300 mb-6 max-w-md">
                        Solusi teknologi terdepan untuk manajemen peternakan modern. Tingkatkan produktivitas dan
                        kesehatan ternak Anda dengan mudah.
                    </p>
                    <div class="flex space-x-4">
                        <button class="bg-gray-800 p-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </button>
                        <button class="bg-gray-800 p-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                            </svg>
                        </button>
                        <button class="bg-gray-800 p-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378 0 0-.599 2.282-.744 2.840-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24.001 12.017 24.001c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-6">Produk</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-colors">Dashboard Personal</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Dashboard Admin</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Dashboard Penyuluh</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Mobile App</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-6">Dukungan</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tutorial</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Pakeng-Ternak. Semua hak dilindungi. Built with Laravel 12.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu functionality
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuBackdrop = document.getElementById('menu-backdrop');
        let isMenuOpen = false;

        function toggleMobileMenu() {
            isMenuOpen = !isMenuOpen;

            // Toggle hamburger animation
            hamburgerBtn.classList.toggle('hamburger-active', isMenuOpen);

            // Toggle mobile menu
            mobileMenu.classList.toggle('active', isMenuOpen);

            // Toggle backdrop
            menuBackdrop.classList.toggle('active', isMenuOpen);

            // Prevent body scroll when menu is open
            document.body.style.overflow = isMenuOpen ? 'hidden' : '';
        }

        function closeMobileMenu() {
            isMenuOpen = false;
            hamburgerBtn.classList.remove('hamburger-active');
            mobileMenu.classList.remove('active');
            menuBackdrop.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Event listeners
        hamburgerBtn.addEventListener('click', toggleMobileMenu);
        menuBackdrop.addEventListener('click', closeMobileMenu);

        // Close menu when clicking on navigation links
        document.querySelectorAll('#mobile-menu a[href^="#"]').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMenuOpen) {
                closeMobileMenu();
            }
        });

        // Close menu when window is resized to desktop size
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768 && isMenuOpen) {
                closeMobileMenu();
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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

        // Animation on scroll (simple implementation)
        function animateOnScroll() {
            const elements = document.querySelectorAll('.card-hover');
            elements.forEach(el => {
                const elementTop = el.getBoundingClientRect().top;
                const elementVisible = 150;

                if (elementTop < window.innerHeight - elementVisible) {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }
            });
        }

        // Initialize animations
        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('load', animateOnScroll);
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.livestock-tab');
            const timelines = document.querySelectorAll('.livestock-timeline');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetType = this.dataset.type;

                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('active', 'bg-primary', 'text-white');
                        t.classList.add('text-gray-600', 'hover:bg-gray-100');
                    });

                    // Add active class to clicked tab
                    this.classList.add('active', 'bg-primary', 'text-white');
                    this.classList.remove('text-gray-600', 'hover:bg-gray-100');

                    // Hide all timelines
                    timelines.forEach(timeline => {
                        timeline.classList.add('hidden');
                    });

                    // Show target timeline
                    const targetTimeline = document.getElementById(`${targetType}-timeline`);
                    if (targetTimeline) {
                        targetTimeline.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</body>

</html>
