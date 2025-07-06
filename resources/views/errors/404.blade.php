<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | Pakeng-Ternak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .error-code {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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

        .spin-slow {
            animation: spin 20s linear infinite;
        }

        .search-pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Search Icon with Animation -->
        <div class="floating-animation mb-8">
            <div class="w-32 h-32 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                <svg class="w-16 h-16 text-white search-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Error Content -->
        <div class="text-white">
            <!-- Error Code -->
            <h1 class="text-8xl md:text-9xl font-black mb-4 bounce-in error-code">
                404
            </h1>

            <!-- Main Message -->
            <h2 class="text-3xl md:text-4xl font-bold mb-4 slide-up">
                Halaman Tidak Ditemukan
            </h2>

            <!-- Description -->
            <p class="text-xl md:text-2xl text-white text-opacity-90 mb-8 slide-up">
                Halaman yang Anda cari sepertinya tidak ada atau telah dipindahkan
            </p>

            <!-- Additional Info -->
            <div class="bg-white bg-opacity-10 rounded-xl p-6 mb-8 backdrop-blur-sm slide-up">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-blue-300">Kemungkinan Penyebab</h3>
                </div>
                <div class="text-white text-opacity-80 space-y-2">
                    <ul class="text-sm space-y-1 text-left max-w-md mx-auto">
                        <li>• URL mungkin salah ketik</li>
                        <li>• Halaman telah dipindahkan atau dihapus</li>
                        <li>• Link yang Anda klik sudah kedaluwarsa</li>
                        <li>• Sedang dalam proses pemeliharaan</li>
                    </ul>
                </div>
            </div>

            <!-- Search Box -->
            

            <!-- Quick Links -->
            <div class="bg-white bg-opacity-10 rounded-xl p-6 mb-8 backdrop-blur-sm slide-up">
                <h4 class="text-lg font-semibold mb-4 text-yellow-300">Halaman Populer</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <a href="/peternak/dashboard" class="p-3 bg-white bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard Peternak</span>
                    </a>
                    <a href="/penyuluh/dashboard" class="p-3 bg-white bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <span>Dashboard Penyuluh</span>
                    </a>
                    <a href="/peternak/ternak" class="p-3 bg-white bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-all duration-300 flex items-center space-x-2">
                        <span class="text-lg">🐄</span>
                        <span>Kelola Ternak</span>
                    </a>
                    <a href="/penyuluh/konsultasi" class="p-3 bg-white bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>Konsultasi</span>
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center slide-up">
                <a href="/" class="px-8 py-4 bg-white text-purple-600 font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </div>
                </a>
                
                <button onclick="history.back()" class="px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-purple-600 transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Halaman Sebelumnya</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full floating-animation"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-white bg-opacity-5 rounded-full floating-animation spin-slow"></div>
        <div class="absolute top-1/3 right-5 w-12 h-12 bg-white bg-opacity-5 rounded-full floating-animation" style="animation-delay: 3s;"></div>
        <div class="absolute bottom-1/3 left-5 w-8 h-8 bg-white bg-opacity-10 rounded-full floating-animation" style="animation-delay: 1s;"></div>
    </div>

    <script>
        function handleSearch(event) {
            if (event.key === 'Enter') {
                const searchTerm = event.target.value.toLowerCase();
                
                // Simple search logic - redirect to relevant pages
                if (searchTerm.includes('ternak') || searchTerm.includes('sapi') || searchTerm.includes('kambing')) {
                    window.location.href = '/peternak/ternak';
                } else if (searchTerm.includes('konsultasi') || searchTerm.includes('penyuluh')) {
                    window.location.href = '/penyuluh/konsultasi';
                } else if (searchTerm.includes('pakan') || searchTerm.includes('nutrisi')) {
                    window.location.href = '/penyuluh/pakan';
                } else if (searchTerm.includes('laporan') || searchTerm.includes('report')) {
                    window.location.href = '/penyuluh/laporan';
                } else if (searchTerm.includes('dashboard') || searchTerm.includes('beranda')) {
                    window.location.href = '/';
                } else {
                    // If no match, show a simple alert
                    alert('Pencarian tidak ditemukan. Silakan coba kata kunci lain atau gunakan navigasi di bawah.');
                }
            }
        }
    </script>
</body>
</html>