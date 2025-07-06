<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar - Pakeng-Ternak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#16a34a",
                        secondary: "#065f46",
                    },
                },
            },
        };
    </script>
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #16a34a 0%, #065f46 100%);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
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

        .slide-in {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-indicator {
            transition: all 0.3s ease;
        }

        .step-active {
            background: #16a34a;
            color: white;
        }

        .step-completed {
            background: #065f46;
            color: white;
        }
    </style>
</head>

<body class="min-h-screen hero-bg flex items-center justify-center p-4">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
                <g fill="#ffffff" fill-opacity="0.1">
                    <circle cx="7" cy="7" r="2" />
                    <circle cx="20" cy="7" r="2" />
                    <circle cx="33" cy="7" r="2" />
                    <circle cx="46" cy="7" r="2" />
                    <circle cx="7" cy="20" r="2" />
                    <circle cx="20" cy="20" r="2" />
                    <circle cx="33" cy="20" r="2" />
                    <circle cx="46" cy="20" r="2" />
                    <circle cx="7" cy="33" r="2" />
                    <circle cx="20" cy="33" r="2" />
                    <circle cx="33" cy="33" r="2" />
                    <circle cx="46" cy="33" r="2" />
                    <circle cx="7" cy="46" r="2" />
                    <circle cx="20" cy="46" r="2" />
                    <circle cx="33" cy="46" r="2" />
                    <circle cx="46" cy="46" r="2" />
                </g>
            </g>
        </svg>
    </div>

    <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <!-- Left Side - Branding & Info -->
        <div class="text-white text-center lg:text-left slide-in">
            <!-- Logo -->
            <div class="flex items-center justify-center lg:justify-start mb-8">
                <div class="bg-white/20 backdrop-filter backdrop-blur-lg rounded-xl p-3">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <span class="ml-4 text-3xl font-bold">Pakeng-Ternak</span>
            </div>

            <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                Bergabunglah dengan
                <span class="text-yellow-300">Komunitas</span> Peternak Modern
            </h1>
            <p class="text-xl text-green-100 mb-8 max-w-md mx-auto lg:mx-0">
                Daftarkan diri Anda dan mulai kelola peternakan dengan teknologi
                terdepan. Gratis untuk semua peternak Indonesia!
            </p>

            <!-- Benefits -->
            <div class="space-y-4 max-w-md mx-auto lg:mx-0">
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="bg-yellow-400/20 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-green-100">Gratis Selamanya</span>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="bg-yellow-400/20 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <span class="text-green-100">Konsultasi dengan Ahli</span>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="bg-yellow-400/20 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.25-1.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"></path>
                        </svg>
                    </div>
                    <span class="text-green-100">Support 24/7</span>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="bg-yellow-400/20 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-green-100">Komunitas Peternak</span>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-12 pt-8 border-t border-white/20">
                <p class="text-green-200 text-sm mb-4">Dipercaya oleh</p>
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6">
                    <div class="text-white/60 text-sm">1000+ Peternak</div>
                    <div class="w-1 h-1 bg-white/40 rounded-full"></div>
                    <div class="text-white/60 text-sm">50+ Penyuluh</div>
                    <div class="w-1 h-1 bg-white/40 rounded-full"></div>
                    <div class="text-white/60 text-sm">10+ Provinsi</div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full max-w-lg mx-auto">
            <div class="glass-effect rounded-2xl shadow-2xl p-8 slide-in" style="animation-delay: 0.2s">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Daftar Akun</h2>
                    <p class="text-gray-600">
                        Mulai perjalanan digital peternakan Anda
                    </p>
                </div>


                <!-- Error Messages -->
                <div id="error-messages"
                    class="hidden mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium mb-1">Terdapat kesalahan:</p>
                            <ul id="error-list" class="list-disc list-inside text-sm space-y-1"></ul>
                        </div>
                    </div>
                </div>

                <!-- Registration Form -->
                <form id="registerForm" method="POST" action="{{ route('admin.register.submit') }}">
                    @csrf

                    <!-- Step 1: Personal Info -->
                    <div id="step-1-content" class="space-y-4">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="text" id="nama" name="nama" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                    placeholder="Masukkan nama lengkap Anda" />
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                        </path>
                                    </svg>
                                </div>
                                <input type="email" id="email" name="email" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                    placeholder="nama@email.com" />
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                HP</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="tel" id="phone" name="phone" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                    placeholder="08123456789" />
                            </div>
                        </div>

                        <button type="button" onclick="nextStep(1)"
                            class="w-full bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-secondary transition-colors">
                            Lanjut
                        </button>
                    </div>

                    <!-- Step 2: Role Selection -->
                    <div id="step-2-content" class="space-y-6 hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">Pilih Role Anda</label>
                            <div class="space-y-3">
                                <div class="role-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition-colors"
                                    onclick="selectRole('Peternak')">
                                    <div class="flex items-center">
                                        <input type="radio" id="role-peternak" name="role" value="Peternak"
                                            class="sr-only" />
                                        <div
                                            class="role-radio w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex-shrink-0">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">
                                                        Peternak
                                                    </h3>
                                                    <p class="text-sm text-gray-600">
                                                        Kelola ternak Anda, monitor kesehatan, dan
                                                        konsultasi dengan ahli
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="role-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition-colors"
                                    onclick="selectRole('Penyuluh')">
                                    <div class="flex items-center">
                                        <input type="radio" id="role-penyuluh" name="role" value="Penyuluh"
                                            class="sr-only" />
                                        <div
                                            class="role-radio w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex-shrink-0">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                                    <svg class="w-6 h-6 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">
                                                        Penyuluh
                                                    </h3>
                                                    <p class="text-sm text-gray-600">
                                                        Berikan konsultasi, buat laporan, dan rekomendasi
                                                        untuk peternak
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <button type="button" onclick="prevStep(2)"
                                class="flex-1 bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                                Kembali
                            </button>
                            <button type="button" onclick="nextStep(2)"
                                class="flex-1 bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-secondary transition-colors">
                                Lanjut
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Password -->
                    <div id="step-3-content" class="space-y-4 hidden">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="password" id="password" name="password" required
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                    placeholder="Minimal 8 karakter" />
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePassword('password')">
                                    <svg id="password-eye" class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2">
                                <div class="flex space-x-2 text-xs">
                                    <span id="length-check" class="text-gray-400">✗ Min 8 karakter</span>
                                    <span id="letter-check" class="text-gray-400">✗ Huruf</span>
                                    <span id="number-check" class="text-gray-400">✗ Angka</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    required
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                    placeholder="Ketik ulang password" />
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePassword('password_confirmation')">
                                    <svg id="confirm-password-eye" class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <div id="password-match" class="mt-2 text-xs text-gray-400"></div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start">
                            <input id="terms" name="terms" type="checkbox" required
                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mt-1" />
                            <label for="terms" class="ml-3 block text-sm text-gray-700">
                                Saya setuju dengan
                                <a href="#" class="text-primary hover:text-secondary">Syarat & Ketentuan</a>
                                dan
                                <a href="#" class="text-primary hover:text-secondary">Kebijakan Privasi</a>
                            </label>
                        </div>

                        <div class="flex space-x-3">
                            <button type="button" onclick="prevStep(3)"
                                class="flex-1 bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                                Kembali
                            </button>
                            <button type="submit"
                                class="flex-1 bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-secondary transition-colors transform hover:scale-105 transition-transform">
                                <span id="register-text">Daftar Sekarang</span>
                                <svg id="loading-spinner"
                                    class="hidden animate-spin -mr-1 ml-3 h-5 w-5 text-white inline" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('admin.login') }}"
                            class="text-primary hover:text-secondary font-semibold transition-colors">
                            Login di sini
                        </a>
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="mt-4 text-center">
                    <a href="/"
                        class="text-gray-500 hover:text-gray-700 text-sm transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-20 right-10 floating-animation opacity-20">
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" />
        </svg>
    </div>
    <div class="absolute bottom-20 left-20 floating-animation opacity-20" style="animation-delay: 3s">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
        </svg>
    </div>

    <script>
        let currentStep = 1;
        let selectedRole = "";

        function nextStep(step) {
            if (validateStep(step)) {
                currentStep = step + 1;
                updateStepDisplay();
            }
        }

        function prevStep(step) {
            currentStep = step - 1;
            updateStepDisplay();
        }

        function updateStepDisplay() {
            // Hide all content
            document.querySelectorAll('[id$="-content"]').forEach((content) => {
                content.classList.add("hidden");
            });

            // Show current step content
            document
                .getElementById(`step-${currentStep}-content`)
                .classList.remove("hidden");

            // Update step indicators
            for (let i = 1; i <= 3; i++) {
                const stepEl = document.getElementById(`step-${i}`);
                stepEl.classList.remove("step-active", "step-completed");

                if (i < currentStep) {
                    stepEl.classList.add("step-completed");
                } else if (i === currentStep) {
                    stepEl.classList.add("step-active");
                } else {
                    stepEl.classList.add("bg-gray-200", "text-gray-500");
                }
            }
        }

        function validateStep(step) {
            clearErrors();
            const errors = [];

            if (step === 1) {
                const nama = document.getElementById("nama").value.trim();
                const email = document.getElementById("email").value.trim();
                const phone = document.getElementById("phone").value.trim();

                if (!nama) errors.push("Nama lengkap harus diisi");
                if (!email) errors.push("Email harus diisi");
                else if (!isValidEmail(email))
                    errors.push("Format email tidak valid");
                if (!phone) errors.push("Nomor HP harus diisi");
                else if (!isValidPhone(phone))
                    errors.push("Format nomor HP tidak valid");
            } else if (step === 2) {
                if (!selectedRole) errors.push("Pilih role terlebih dahulu");
            }

            if (errors.length > 0) {
                showErrors(errors);
                return false;
            }
            return true;
        }

        function selectRole(role) {
            selectedRole = role;

            // Remove selection from all options
            document.querySelectorAll(".role-option").forEach((option) => {
                option.classList.remove("border-primary", "bg-primary/5");
                option.classList.add("border-gray-200");
                option
                    .querySelector(".role-radio")
                    .classList.remove("bg-primary", "border-primary");
                option.querySelector(".role-radio").classList.add("border-gray-300");
            });

            // Add selection to clicked option
            const selectedOption = event.currentTarget;
            selectedOption.classList.remove("border-gray-200");
            selectedOption.classList.add("border-primary", "bg-primary/5");
            const radio = selectedOption.querySelector(".role-radio");
            radio.classList.remove("border-gray-300");
            radio.classList.add("bg-primary", "border-primary");

            // Set form value
            document.querySelector(`input[value="${role}"]`).checked = true;
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(
                fieldId === "password" ? "password-eye" : "confirm-password-eye"
            );

            if (field.type === "password") {
                field.type = "text";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                field.type = "password";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function isValidPhone(phone) {
            return /^(\+62|62|0)8[1-9][0-9]{6,10}$/.test(phone.replace(/\s/g, ""));
        }

        function showErrors(errors) {
            const errorDiv = document.getElementById("error-messages");
            const errorList = document.getElementById("error-list");

            errorList.innerHTML = "";
            errors.forEach((error) => {
                const li = document.createElement("li");
                li.textContent = error;
                errorList.appendChild(li);
            });

            errorDiv.classList.remove("hidden");
            errorDiv.scrollIntoView({
                behavior: "smooth",
                block: "nearest"
            });
        }

        function clearErrors() {
            document.getElementById("error-messages").classList.add("hidden");
        }

        // Password strength checker
        document
            .getElementById("password")
            .addEventListener("input", function() {
                const password = this.value;
                const lengthCheck = document.getElementById("length-check");
                const letterCheck = document.getElementById("letter-check");
                const numberCheck = document.getElementById("number-check");

                // Length check
                if (password.length >= 8) {
                    lengthCheck.className = "text-green-600";
                    lengthCheck.textContent = "✓ Min 8 karakter";
                } else {
                    lengthCheck.className = "text-gray-400";
                    lengthCheck.textContent = "✗ Min 8 karakter";
                }

                // Letter check
                if (/[a-zA-Z]/.test(password)) {
                    letterCheck.className = "text-green-600";
                    letterCheck.textContent = "✓ Huruf";
                } else {
                    letterCheck.className = "text-gray-400";
                    letterCheck.textContent = "✗ Huruf";
                }

                // Number check
                if (/\d/.test(password)) {
                    numberCheck.className = "text-green-600";
                    numberCheck.textContent = "✓ Angka";
                } else {
                    numberCheck.className = "text-gray-400";
                    numberCheck.textContent = "✗ Angka";
                }

                checkPasswordMatch();
            });

        // Password confirmation checker
        document
            .getElementById("password_confirmation")
            .addEventListener("input", checkPasswordMatch);

        function checkPasswordMatch() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById(
                "password_confirmation"
            ).value;
            const matchDiv = document.getElementById("password-match");

            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchDiv.className = "mt-2 text-xs text-green-600";
                    matchDiv.textContent = "✓ Password cocok";
                } else {
                    matchDiv.className = "mt-2 text-xs text-red-600";
                    matchDiv.textContent = "✗ Password tidak cocok";
                }
            } else {
                matchDiv.textContent = "";
            }
        }

        // Form submission
        document
            .getElementById("registerForm")
            .addEventListener("submit", function(e) {
                e.preventDefault();

                // Final validation
                const errors = [];
                const password = document.getElementById("password").value;
                const confirmPassword = document.getElementById(
                    "password_confirmation"
                ).value;
                const terms = document.getElementById("terms").checked;

                if (password.length < 8) errors.push("Password minimal 8 karakter");
                if (!/[a-zA-Z]/.test(password))
                    errors.push("Password harus mengandung huruf");
                if (!/\d/.test(password))
                    errors.push("Password harus mengandung angka");
                if (password !== confirmPassword)
                    errors.push("Password dan konfirmasi password tidak cocok");
                if (!terms) errors.push("Anda harus menyetujui syarat dan ketentuan");

                if (errors.length > 0) {
                    showErrors(errors);
                    return;
                }

                // Show loading state
                const registerText = document.getElementById("register-text");
                const loadingSpinner = document.getElementById("loading-spinner");
                const submitBtn = this.querySelector('button[type="submit"]');

                registerText.textContent = "Mendaftar...";
                loadingSpinner.classList.remove("hidden");
                submitBtn.disabled = true;
                this.submit();
            });

        // Auto-focus on first input
        window.addEventListener("load", function() {
            document.getElementById("nama").focus();
        });
    </script>
</body>

</html>
