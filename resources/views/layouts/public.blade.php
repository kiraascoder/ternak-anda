<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Sistem Informasi Peternakan - Platform Terpercaya untuk Peternak Indonesia')</title>
    <meta name="description" content="@yield('meta-description', 'Platform informasi lengkap untuk peternakan modern, termasuk panduan pakan ternak, tips perawatan, dan inovasi terbaru dalam dunia peternakan.')">
    <meta name="keywords" content="@yield('keywords', 'peternakan, pakan ternak, informasi peternakan, panduan ternak, tips peternakan, pakan hijauan, pakan konsentrat, fermentasi pakan')">
    <meta name="author" content="Sistem Informasi Peternakan">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'Sistem Informasi Peternakan')">
    <meta property="og:description" content="@yield('meta-description', 'Platform informasi lengkap untuk peternakan modern')">
    <meta property="og:type" content="@yield('og-type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og-image', asset('images/og-default.jpg'))">
    <meta property="og:site_name" content="Sistem Informasi Peternakan">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Sistem Informasi Peternakan')">
    <meta name="twitter:description" content="@yield('meta-description', 'Platform informasi lengkap untuk peternakan modern')">
    <meta name="twitter:image" content="@yield('og-image', asset('images/og-default.jpg'))">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#059669',
                        secondary: '#047857',
                        tertiary: '#065f46',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'bounce-slow': 'bounce 2s infinite',
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%)',
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        /* Line clamp utilities */
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Backdrop blur fallback */
        .backdrop-blur-fallback {
            background-color: rgba(255, 255, 255, 0.95);
        }

        @supports (backdrop-filter: blur(10px)) {
            .backdrop-blur-fallback {
                background-color: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
            }
        }

        /* Focus visible for accessibility */
        .focus-visible:focus {
            outline: 2px solid #059669;
            outline-offset: 2px;
        }

        /* Print styles */
        @media print {
            .print-hide {
                display: none !important;
            }

            .print-show {
                display: block !important;
            }

            body {
                background: white !important;
                color: black !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans antialiased">
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
        <div id="menu-backdrop" class="menu-backdrop fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>
    </nav>
    <main id="main-content" class="min-h-screen" role="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white print-hide" role="contentinfo">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-gradient-primary p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-xl">Sistem Informasi Peternakan</div>
                            <div class="text-gray-400">Platform informasi terpercaya untuk peternak Indonesia</div>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md leading-relaxed">
                        Menyediakan informasi lengkap dan terpercaya tentang peternakan modern,
                        panduan pakan ternak, dan inovasi terbaru untuk meningkatkan produktivitas peternakan Anda.
                    </p>

                    <!-- Social Media -->
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors focus-visible"
                            aria-label="Follow us on Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors focus-visible"
                            aria-label="Follow us on Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors focus-visible"
                            aria-label="Follow us on Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.223.083.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.747 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.624 0 11.99-5.367 11.99-11.989C24.007 5.367 18.641.001 12.017.001z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors focus-visible"
                            aria-label="Follow us on YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold text-lg mb-4">Navigasi</h3>
                    <ul class="space-y-2">
                        <li><a href=""
                                class="text-gray-400 hover:text-white transition-colors focus-visible">Beranda</a></li>
                        <li><a href=""
                                class="text-gray-400 hover:text-white transition-colors focus-visible">Informasi
                                Pakan</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors focus-visible">Panduan
                                Ternak</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors focus-visible">Konsultasi</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors focus-visible">Tentang Kami</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors focus-visible">Kontak</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="font-semibold text-lg mb-4">Kontak</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <a href="tel:+621234567890" class="hover:text-white transition-colors">+62
                                123-4567-890</a>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <a href="mailto:info@peternakan.id"
                                class="hover:text-white transition-colors">info@peternakan.id</a>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter Subscription -->
            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="max-w-md">
                    <h3 class="font-semibold text-lg mb-2">Newsletter</h3>
                    <p class="text-gray-400 text-sm mb-4">Dapatkan update terbaru tentang informasi pakan dan tips
                        peternakan.</p>
                    <form class="flex" onsubmit="subscribeNewsletter(event)">
                        <input type="email"
                            class="flex-1 px-4 py-2 bg-gray-800 text-white rounded-l-lg border border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Email Anda" required>
                        <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-r-lg hover:bg-secondary transition-colors focus-visible">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} Sistem Informasi Peternakan. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#"
                        class="text-gray-400 hover:text-white text-sm transition-colors focus-visible">Privacy
                        Policy</a>
                    <a href="#"
                        class="text-gray-400 hover:text-white text-sm transition-colors focus-visible">Terms of
                        Service</a>
                    <a href="#"
                        class="text-gray-400 hover:text-white text-sm transition-colors focus-visible">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop"
        class="fixed bottom-6 right-6 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-secondary transition-all duration-300 opacity-0 pointer-events-none transform translate-y-2 print-hide focus-visible"
        onclick="scrollToTop()" aria-label="Back to top">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18">
            </path>
        </svg>
    </button>

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2 print-hide"></div>

    <!-- Scripts -->
    <script>
        // Global variables
        let searchTimeout;
        let isSearching = false;

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const button = document.querySelector('[aria-controls="mobileMenu"]');

            menu.classList.toggle('hidden');
            const isExpanded = !menu.classList.contains('hidden');
            button.setAttribute('aria-expanded', isExpanded);
        }

        // Search overlay toggle
        function toggleSearch() {
            const overlay = document.getElementById('searchOverlay');
            const searchInput = document.getElementById('globalSearch');
            const button = document.querySelector('[aria-controls="searchOverlay"]');

            overlay.classList.toggle('hidden');
            const isExpanded = !overlay.classList.contains('hidden');
            button.setAttribute('aria-expanded', isExpanded);

            if (isExpanded) {
                setTimeout(() => searchInput.focus(), 100);
            } else {
                clearSearch();
            }
        }

        // Clear search
        function clearSearch() {
            const searchInput = document.getElementById('globalSearch');
            const searchResults = document.getElementById('searchResults');

            searchInput.value = '';
            searchResults.classList.add('hidden');
        }

        // Quick search
        function quickSearch(query) {
            const searchInput = document.getElementById('globalSearch');
            searchInput.value = query;
            performSearch(query);
        }

        // Perform search
        function performSearch(query) {
            if (query.length < 2) {
                document.getElementById('searchResults').classList.add('hidden');
                return;
            }

            if (isSearching) return;
            isSearching = true;

            const searchResults = document.getElementById('searchResults');
            const searchContent = document.getElementById('searchContent');
            const searchLoading = document.getElementById('searchLoading');

            searchResults.classList.remove('hidden');
            searchLoading.classList.remove('hidden');
            searchContent.classList.add('hidden');

            // Simulate API call - replace with actual search endpoint
            fetch(`/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data, query);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    displaySearchError();
                })
                .finally(() => {
                    searchLoading.classList.add('hidden');
                    searchContent.classList.remove('hidden');
                    isSearching = false;
                });
        }

        // Display search results
        function displaySearchResults(data, query) {
            const searchContent = document.getElementById('searchContent');

            if (data.results && data.results.length > 0) {
                let html =
                    `<div class="mb-3"><h4 class="font-medium text-gray-900">Hasil pencarian untuk "${query}"</h4></div>`;

                data.results.forEach(item => {
                    html += `
                        <div class="border-b border-gray-200 pb-3 mb-3 last:border-0 last:pb-0 last:mb-0">
                            <a href="${item.url}" class="block hover:text-primary transition-colors">
                                <h5 class="font-medium text-gray-900 mb-1">${item.title}</h5>
                                <p class="text-sm text-gray-600 mb-1">${item.excerpt || ''}</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <span class="bg-gray-100 px-2 py-1 rounded">${item.category || 'Artikel'}</span>
                                    <span class="ml-2">${item.date || ''}</span>
                                </div>
                            </a>
                        </div>
                    `;
                });

                if (data.total > data.results.length) {
                    html += `
                        <div class="text-center pt-3">
                            <a href="/informasi-pakan?search=${encodeURIComponent(query)}" 
                               class="text-primary hover:text-secondary font-medium">
                                Lihat semua ${data.total} hasil
                            </a>
                        </div>
                    `;
                }

                searchContent.innerHTML = html;
            } else {
                searchContent.innerHTML = `
                    <div class="text-center py-4">
                        <p class="text-gray-600 mb-2">Tidak ada hasil untuk "${query}"</p>
                        <p class="text-sm text-gray-500">Coba gunakan kata kunci yang berbeda</p>
                    </div>
                `;
            }
        }

        // Display search error
        function displaySearchError() {
            const searchContent = document.getElementById('searchContent');
            searchContent.innerHTML = `
                <div class="text-center py-4">
                    <p class="text-red-600 mb-2">Terjadi kesalahan saat mencari</p>
                    <p class="text-sm text-gray-500">Silakan coba lagi dalam beberapa saat</p>
                </div>
            `;
        }

        // Newsletter subscription
        function subscribeNewsletter(event) {
            event.preventDefault();
            const form = event.target;
            const email = form.querySelector('input[type="email"]').value;

            // Simulate API call
            showNotification('Terima kasih! Anda telah berlangganan newsletter kami.', 'success');
            form.reset();
        }

        // Back to top functionality
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Show/hide back to top button
        function handleScroll() {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
            } else {
                backToTop.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
            }
        }

        // Show notification
        function showNotification(message, type = 'info', duration = 5000) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');

            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            notification.className =
                `${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300`;
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            container.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, duration);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Search input event
            const searchInput = document.getElementById('globalSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value;

                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (query.length >= 2) {
                            performSearch(query);
                        } else {
                            document.getElementById('searchResults').classList.add('hidden');
                        }
                    }, 300);
                });
            }

            // Scroll event
            window.addEventListener('scroll', handleScroll);

            // Close search on escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const overlay = document.getElementById('searchOverlay');
                    if (!overlay.classList.contains('hidden')) {
                        toggleSearch();
                    }

                    const mobileMenu = document.getElementById('mobileMenu');
                    if (!mobileMenu.classList.contains('hidden')) {
                        toggleMobileMenu();
                    }
                }
            });

            // Close overlays when clicking outside
            document.addEventListener('click', function(e) {
                const searchOverlay = document.getElementById('searchOverlay');
                const mobileMenu = document.getElementById('mobileMenu');

                if (!e.target.closest('#searchOverlay') && !e.target.closest(
                        '[aria-controls="searchOverlay"]')) {
                    if (!searchOverlay.classList.contains('hidden')) {
                        toggleSearch();
                    }
                }

                if (!e.target.closest('#mobileMenu') && !e.target.closest('[aria-controls="mobileMenu"]')) {
                    if (!mobileMenu.classList.contains('hidden')) {
                        toggleMobileMenu();
                    }
                }
            });
        });

        // Page loader
        function showPageLoader() {
            document.getElementById('page-loader').classList.remove('hidden');
        }

        function hidePageLoader() {
            document.getElementById('page-loader').classList.add('hidden');
        }

        // Handle page load
        window.addEventListener('load', function() {
            hidePageLoader();
        });

        // Handle navigation with loading
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href]');
            if (link && link.href && !link.href.startsWith('#') && !link.href.startsWith('mailto:') && !link.href
                .startsWith('tel:') && !link.target) {
                if (link.href.indexOf(window.location.origin) === 0) {
                    showPageLoader();
                }
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
