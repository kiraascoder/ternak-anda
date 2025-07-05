<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>


    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16a34a',
                        secondary: '#065f46',
                        accent: '#84cc16',
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }

        .content-transition {
            transition: margin-left 0.3s ease-in-out;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #16a34a 0%, #065f46 100%);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .notification-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .bg-primary {
            background-color: #16a34a !important;
        }

        .bg-secondary {
            background-color: #065f46 !important;
        }

        .text-primary {
            color: #16a34a !important;
        }

        .text-secondary {
            color: #065f46 !important;
        }

        .border-primary {
            border-color: #16a34a !important;
        }

        .hover\:bg-primary:hover {
            background-color: #16a34a !important;
        }

        .hover\:bg-secondary:hover {
            background-color: #065f46 !important;
        }

        .hover\:text-primary:hover {
            color: #16a34a !important;
        }

        .hover\:text-secondary:hover {
            color: #065f46 !important;
        }

        .focus\:ring-primary:focus {
            --tw-ring-color: #16a34a !important;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">

        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl sidebar-transition transform -translate-x-full lg:translate-x-0">
            @include('layouts.partials.sidebar')
        </aside>


        <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden hidden"
            onclick="toggleSidebar()"></div>


        <div class="lg:ml-64 content-transition">

            <nav class="bg-white shadow-sm border-b border-gray-200 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">

                    <button onclick="toggleSidebar()"
                        class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>


                    <div class="flex-1 min-w-0 ml-4 lg:ml-0">
                        <h1 class="text-2xl font-bold text-gray-900 truncate">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">
                            @yield('page-description', 'Selamat datang di dashboard Pakeng-Ternak')
                        </p>
                    </div>


                    <div class="flex items-center space-x-4">
                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button id="user-menu-button"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary">
                                <div class="h-8 w-8 bg-primary rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ substr(Auth::user()->nama ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->role ?? 'Peternak' }}</p>
                                </div>
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User dropdown -->
                            <div id="user-dropdown"
                                class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <div class="px-4 py-3 border-b">
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama ?? 'User' }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'user@example.com' }}
                                        </p>
                                    </div>
                                    <a href="{{ Auth::user()->role == 'peternak' ? route('peternak.profile') : route('penyuluh.profile') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profil Saya
                                    </a>
                                    <div class="border-t">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                <svg class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Breadcrumb (Optional) -->
            @if (isset($breadcrumbs))
                <nav class="px-4 py-3 sm:px-6 lg:px-8 bg-gray-50 border-b">
                    <ol class="flex items-center space-x-2 text-sm">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!$loop->last)
                                <li>
                                    <a href="{{ $breadcrumb['url'] }}"
                                        class="text-gray-500 hover:text-gray-700">{{ $breadcrumb['title'] }}</a>
                                </li>
                                <li>
                                    <svg class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </li>
                            @else
                                <li class="text-gray-900 font-medium">{{ $breadcrumb['title'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            @endif

            <!-- Page Content -->
            <main class="px-4 py-6 sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"
                        id="success-alert">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                            <button onclick="closeAlert('success-alert')" class="ml-auto">
                                <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg"
                        id="error-alert">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-500 mr-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                            <button onclick="closeAlert('error-alert')" class="ml-auto">
                                <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Toggle dropdowns
        function toggleDropdown(buttonId, dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            const notifButton = document.getElementById('notifications-button');
            const notifDropdown = document.getElementById('notifications-dropdown');

            if (!userButton.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }

            if (!notifButton.contains(event.target)) {
                notifDropdown.classList.add('hidden');
            }
        });

        // User menu button click
        document.getElementById('user-menu-button').addEventListener('click', function() {
            toggleDropdown('user-menu-button', 'user-dropdown');
        });

        // Notifications button click
        document.getElementById('notifications-button').addEventListener('click', function() {
            toggleDropdown('notifications-button', 'notifications-dropdown');
        });

        // Close alert messages
        function closeAlert(alertId) {
            document.getElementById(alertId).style.display = 'none';
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[id$="-alert"]');
            alerts.forEach(alert => alert.style.display = 'none');
        }, 5000);

        // Close sidebar when clicking on main content (mobile)
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth < 1024 && !sidebar.contains(event.target) && !event.target.closest(
                    'button[onclick="toggleSidebar()"]')) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
