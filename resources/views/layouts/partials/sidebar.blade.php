{{-- resources/views/layouts/partials/sidebar.blade.php --}}

<div class="flex flex-col h-full">
    <!-- Logo Section -->
    <div class="flex items-center justify-center h-16 px-4"
        style="background: linear-gradient(135deg, #16a34a 0%, #065f46 100%);">
        <div class="flex items-center">
            <div class="rounded-lg p-2" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <span class="ml-3 text-xl font-bold text-white">Pakeng-Ternak</span>
        </div>
    </div>

    <!-- User Info Section -->
    <div class="p-4 bg-gray-50 border-b">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full flex items-center justify-center" style="background-color: #16a34a;">
                <span class="text-sm font-medium text-white">
                    {{ substr(Auth::user()->name ?? (Auth::user()->nama ?? 'U'), 0, 1) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ Auth::user()->name ?? (Auth::user()->nama ?? 'User') }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->role ?? 'Peternak' }}</p>
            </div>
            <div class="flex-shrink-0">
                @php
                    $userRole = Auth::user()->role ?? 'Peternak';
                    $badgeClasses = '';
                    if ($userRole === 'Admin') {
                        $badgeClasses = 'bg-purple-100 text-purple-800';
                    } elseif ($userRole === 'Penyuluh') {
                        $badgeClasses = 'bg-green-100 text-green-800';
                    } else {
                        $badgeClasses = 'bg-blue-100 text-blue-800';
                    }
                @endphp
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }}">
                    {{ $userRole }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @php
            $user = Auth::user();
            $userRole = $user->role ?? 'Peternak';

            // Debug: untuk memastikan role yang benar
            // dd('User Role: ' . $userRole); // Uncomment untuk debug

            // Perbaikan: hapus strtolower dan gunakan exact match
            $dashboardRoute = match ($userRole) {
                'Penyuluh' => 'penyuluh.dashboard',
                'Admin' => 'admin.dashboard',
                'Peternak' => 'peternak.dashboard',
                default => 'peternak.dashboard',
            };

            // Check apakah route ada
            try {
                $dashboardUrl = route($dashboardRoute);
            } catch (\Illuminate\Routing\Exceptions\RouteNotFoundException $e) {
                // Fallback jika route tidak ada
                $dashboardRoute = 'peternak.dashboard';
                $dashboardUrl = route($dashboardRoute);
            }

            // Perbaikan: check semua kemungkinan dashboard active
            $isDashboardActive = request()->routeIs(['peternak.dashboard', 'penyuluh.dashboard', 'admin.dashboard']);
        @endphp

        <!-- Dashboard Link dengan URL yang benar -->
        <a href="{{ $dashboardUrl }}"
            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $isDashboardActive ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z M3 7l9 6 9-6" />
            </svg>
            <span>Dashboard {{ ucfirst($userRole) }}</span>
            {{-- Debug info - hapus setelah testing --}}
            {{-- <small class="ml-2 text-xs opacity-50">({{ $dashboardRoute }})</small> --}}
        </a>

        @if ($userRole === 'Peternak' || $userRole === 'Personal')
            <!-- Peternak/Personal Menu -->
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Manajemen Ternak</p>

                <a href="{{ route('peternak.ternak') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('peternak.ternak*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Data Ternak</span>
                </a>

                <a href="{{ route('peternak.konsultasi') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('peternak.konsultasi*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Konsultasi</span>
                </a>
            </div>
            <div class="space-y-1 pt-4">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rekomendasi Pakan</p>
                <a href="{{ route('peternak.pakan') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('peternak.pakan*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span>Rekomendasi Pakan</span>
                </a>
            </div>
        @elseif($userRole === 'Admin')
            <!-- Admin Menu -->
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Manajemen User</p>

                <a href=""
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <span>Kelola User</span>
                </a>

                <a href=""
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.ternak.*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Lihat Semua Data</span>
                </a>
            </div>

            <div class="space-y-1 pt-4">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan & Analytics</p>

                <a href=""
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Generate Reports</span>
                </a>

                <a href=""
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Analytics</span>
                </a>
            </div>

            <div class="space-y-1 pt-4">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sistem</p>

                <a href=""
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </div>
        @elseif($userRole === 'Penyuluh')
            <!-- Penyuluh Menu -->
            <div class="space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Konsultasi dan Ternak</p>

                <a href="{{ route('penyuluh.konsultasi') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('penyuluh.konsultasi*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Konsultasi Kesehatan</span>
                </a>
                <a href="{{ route('penyuluh.ternak') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('penyuluh.ternak') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Data Ternak</span>
                </a>
            </div>

            <div class="space-y-1 pt-4">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan & Rekomendasi</p>

                <a href="{{ route('penyuluh.laporan') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('penyuluh.laporan*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Buat Laporan Kesehatan</span>
                </a>

                <a href="{{ route('penyuluh.pakan') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('penyuluh.pakan*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span>Rekomendasi Pakan</span>
                </a>
            </div>
        @endif

        @if (in_array($userRole, ['Peternak', 'Penyuluh']))
            @php
                // Perbaikan: simplify profile route logic
                $profileRoute = match ($userRole) {
                    'Penyuluh' => 'penyuluh.profile',
                    'Peternak' => 'peternak.profile',
                    default => 'peternak.profile',
                };

                $isProfileActive = request()->routeIs(['penyuluh.profile', 'peternak.profile']);
            @endphp

            <div class="space-y-1 pt-4 border-t border-gray-200">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lainnya</p>

                <a href="{{ route($profileRoute) }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $isProfileActive ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profil</span>
                </a>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors text-red-600 hover:bg-red-50 hover:text-red-700">
                        <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        @endif
    </nav>
</div>
