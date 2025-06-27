<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Footer Content -->
        <div class="py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand Section -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary rounded-lg p-2">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span class="ml-3 text-lg font-bold text-gray-900">Pakeng-Ternak</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4 max-w-md">
                        Sistem manajemen peternakan modern yang membantu peternak Indonesia mengelola ternak dengan
                        teknologi terdepan untuk meningkatkan produktivitas dan kesejahteraan hewan.
                    </p>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-lg font-bold text-primary">1000+</div>
                            <div class="text-xs text-gray-500">Peternak</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-lg font-bold text-primary">50+</div>
                            <div class="text-xs text-gray-500">Penyuluh</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-lg font-bold text-primary">10+</div>
                            <div class="text-xs text-gray-500">Provinsi</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Menu Utama</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="text-sm text-gray-600 hover:text-primary transition-colors">
                                Dashboard
                            </a>
                        </li>
                        @php
                            $userRole = Auth::user()->role ?? 'Peternak';
                        @endphp

                        @if ($userRole === 'Peternak' || $userRole === 'Personal')
                            <li>
                                <a href="{{ route('ternak.index') }}"
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Data Ternak
                                </a>
                            </li>
                            <li>
                                <a href=""
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Kesehatan Ternak
                                </a>
                            </li>
                            <li>
                                <a href=""
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Konsultasi
                                </a>
                            </li>
                        @elseif($userRole === 'Admin')
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Kelola User
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.index') }}"
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Generate Reports
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics') }}"
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Analytics
                                </a>
                            </li>
                        @elseif($userRole === 'Penyuluh')
                            <li>
                                <a href="{{ route('penyuluh.konsultasi.index') }}"
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Konsultasi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('penyuluh.laporan.create') }}"
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Buat Laporan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('penyuluh.rekomendasi.index') }}"
                                    class="text-sm text-gray-600 hover:text-primary transition-colors">
                                    Rekomendasi
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Support & Contact -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Dukungan</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href=""
                                class="text-sm text-gray-600 hover:text-primary transition-colors">
                                Bantuan
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="text-sm text-gray-600 hover:text-primary transition-colors">
                                FAQ
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="text-sm text-gray-600 hover:text-primary transition-colors">
                                Hubungi Kami
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="text-sm text-gray-600 hover:text-primary transition-colors">
                                Syarat & Ketentuan
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="text-sm text-gray-600 hover:text-primary transition-colors">
                                Kebijakan Privasi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="py-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-primary/10 p-2 rounded-lg">
                        <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Email</p>
                        <p class="text-sm text-gray-600">support@pakeng-ternak.com</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="bg-primary/10 p-2 rounded-lg">
                        <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Telepon</p>
                        <p class="text-sm text-gray-600">+62 812-3456-7890</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="bg-primary/10 p-2 rounded-lg">
                        <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Alamat</p>
                        <p class="text-sm text-gray-600">Makassar, Sulawesi Selatan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="py-4 border-t border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <!-- Left side - Version & Status -->
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500">Versi</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            v2.1.0
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                        <span class="text-xs text-gray-500">Sistem Normal</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500">Laravel</span>
                        <span class="text-xs font-medium text-gray-700">12.0</span>
                    </div>
                </div>

                <!-- Right side - Social Links -->
                <div class="flex items-center space-x-4">
                    <span class="text-xs text-gray-500">Ikuti kami:</span>
                    <div class="flex space-x-3">
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378 0 0-.599 2.282-.744 2.840-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24.001 12.017 24.001c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <span class="sr-only">YouTube</span>
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="py-4 border-t border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between text-xs text-gray-500">
                <div class="flex items-center space-x-4">
                    <p>&copy; {{ date('Y') }} Pakeng-Ternak. Semua hak dilindungi.</p>
                    <span class="hidden md:inline">•</span>
                    <p class="hidden md:inline">Dibuat dengan ❤️ untuk peternak Indonesia</p>
                </div>
                <div class="flex items-center space-x-4 mt-2 md:mt-0">
                    <p>Powered by Laravel {{ app()->version() }}</p>
                    <span>•</span>
                    <p>Hosted with 💚</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="back-to-top"
    class="hidden fixed bottom-6 right-6 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-secondary transition-all hover:scale-110 z-50">
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<script>
    // Back to top functionality
    window.addEventListener('scroll', function() {
        const backToTopButton = document.getElementById('back-to-top');
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('hidden');
        } else {
            backToTopButton.classList.add('hidden');
        }
    });

    document.getElementById('back-to-top').addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
