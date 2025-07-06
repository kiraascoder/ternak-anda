<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden | Pakeng-Ternak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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

        .gradient-bg {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .error-code {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bounce-in {
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .slide-up {
            animation: slideUp 1s ease-out 0.3s both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .shake {
            animation: shake 2s ease-in-out infinite;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Shield Icon with Animation -->
        <div class="floating-animation mb-8">
            <div
                class="w-32 h-32 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                <svg class="w-16 h-16 text-white shake" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364L18.364 5.636">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Error Content -->
        <div class="text-white">
            <!-- Error Code -->
            <h1 class="text-8xl md:text-9xl font-black mb-4 bounce-in error-code">
                403
            </h1>

            <!-- Main Message -->
            <h2 class="text-3xl md:text-4xl font-bold mb-4 slide-up">
                Akses Ditolak
            </h2>

            <!-- Description -->
            <p class="text-xl md:text-2xl text-white text-opacity-90 mb-8 slide-up">
                Anda tidak memiliki izin untuk mengakses halaman ini
            </p>

            <!-- Additional Info -->
            <div class="bg-white bg-opacity-10 rounded-xl p-6 mb-8 backdrop-blur-sm slide-up">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <svg class="w-6 h-6 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.96-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-red-300">Akses Terbatas</h3>
                </div>
                <div class="text-white text-opacity-80 space-y-2">
                    <p>Kemungkinan penyebab:</p>
                    <ul class="text-sm space-y-1 text-left max-w-md mx-auto">
                        <li>• Role/peran akun Anda tidak memiliki akses</li>
                        <li>• Halaman khusus untuk admin/penyuluh</li>
                        <li>• Fitur sedang dalam pemeliharaan</li>
                        <li>• Token akses telah kedaluwarsa</li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center slide-up">
                <button
                    class="px-8 py-4 bg-white text-pink-600 font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <a href="/">
                            Kembali ke Beranda
                        </a>
                    </div>
                </button>

                <a href="/admin/login"
                    class="px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-pink-600 transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span>Login Ulang</span>
                    </div>
                </a>
            </div>

            <!-- Contact Support -->
            <div class="mt-8 slide-up">
                <div class="bg-white bg-opacity-5 rounded-lg p-4 inline-block">
                    <p class="text-white text-opacity-70 text-sm mb-2">Butuh bantuan?</p>
                    <a href="mailto:support@pakeng-ternak.com"
                        class="text-white hover:text-yellow-300 transition-all duration-300 inline-flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Hubungi Support</span>
                    </a>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 slide-up">
                <a href="/"
                    class="text-white text-opacity-70 hover:text-opacity-100 transition-all duration-300 inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full floating-animation"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-white bg-opacity-5 rounded-full floating-animation"
            style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 right-5 w-12 h-12 bg-white bg-opacity-5 rounded-full floating-animation"
            style="animation-delay: 4s;"></div>
    </div>
</body>

</html>
