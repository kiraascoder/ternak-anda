@extends('layouts.app')

@section('title', 'Dashboard Peternak')
@section('page-title', 'Dashboard Peternak')
@section('page-description',
    'Selamat datang kembali, ' .
    (Auth::user()->nama ?? Auth::user()->name) .
    '! Berikut
    ringkasan peternakan Anda hari ini.')

    @push('styles')
        <style>
            .stats-card {
                transition: all 0.3s ease;
            }

            .stats-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            }

            .weather-gradient {
                background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            }

            .health-gradient {
                background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            }

            .alert-gradient {
                background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
            }

            .chart-container {
                position: relative;
                height: 300px;
            }

            .pulse-animation {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.7;
                }
            }

            .ternak-card {
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }

            .ternak-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                border-color: #16a34a;
            }

            .weather-info {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.1);
            }
        </style>
    @endpush

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->nama ?? Auth::user()->name }}! 👋
                    </h2>
                    <p class="text-green-100">Hari ini adalah {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
                    <p class="text-sm text-green-200 mt-1">Peternakan Anda dalam kondisi baik dan siap produktif!</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                        <div class="text-3xl mb-2">🐄</div>
                        <div class="text-sm">Total Ternak</div>
                        <div class="text-2xl font-bold">{{ $totalTernak ?? 25 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Ternak -->
            <div class="stats-card bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Ternak</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalTernak ?? 25 }}</p>
                        <p class="text-green-600 text-sm mt-1">
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Ternak Sehat -->
            <div class="stats-card bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Ternak Sehat</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $ternakSehat ?? 23 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Perlu Perhatian -->
            <div class="stats-card bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Perlu Perhatian</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $ternakSakit ?? 2 }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full pulse-animation">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Konsultasi Aktif -->
            <div class="stats-card bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Konsultasi Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $konsultasiSaya ?? 3 }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid: Ternak Terbaru (Kiri) dan Cuaca (Kanan) -->
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Ternak Terbaru - Kiri (2 kolom) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 h-full">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="mr-2">🐄</span>
                            Ternak Terbaru
                        </h3>
                        <a href="{{ route('peternak.ternak') }}"
                            class="text-sm text-primary hover:text-secondary font-medium transition-colors">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($recentTernakList ?? [] as $ternak)
                            <div class="ternak-card border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xl">🐄</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $ternak->status === 'sehat' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($ternak->status ?? 'Sehat') }}
                                    </span>
                                </div>
                                <h4 class="font-semibold text-gray-900 truncate">
                                    {{ $ternak->namaTernak ?? 'Sapi #' . sprintf('%03d', rand(1, 999)) }}
                                </h4>
                                <p class="text-sm text-gray-600">{{ $ternak->jenis ?? 'Tidak diketahui' }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Umur: {{ $ternak ? $ternak->umur_text : 'Belum diketahui' }}

                                </p>
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="text-xs text-gray-400">
                                        ID: {{ $ternak->idTernak ?? 'TRN-' . sprintf('%03d', rand(1, 999)) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (count($recentTernakList ?? []) == 0)
                        <div class="mt-4 text-center py-4">
                            <p class="text-sm text-gray-500">
                                Belum ada data ternak.
                                <a href="{{ route('peternak.ternak') }}" endforea
                                    class="text-primary hover:text-secondary font-medium">
                                    Tambah ternak pertama Anda →
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cuaca - Kanan (1 kolom) -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Widget Cuaca -->
                    <div class="weather-gradient rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Cuaca Hari Ini</h3>
                            <div class="text-3xl">☀️</div>
                        </div>
                        <div class="text-3xl font-bold mb-2">28°C</div>
                        <p class="text-blue-100 text-sm">Cerah berawan</p>
                        <p class="text-blue-200 text-xs mt-1">Makassar, Sulawesi Selatan</p>

                        <div class="mt-4 pt-4 border-t border-blue-300/30">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="weather-info rounded-lg p-3">
                                    <div class="flex items-center mb-1">
                                        <span class="mr-2">💧</span>
                                        <span class="text-xs">Kelembaban</span>
                                    </div>
                                    <div class="font-semibold">65%</div>
                                </div>
                                <div class="weather-info rounded-lg p-3">
                                    <div class="flex items-center mb-1">
                                        <span class="mr-2">💨</span>
                                        <span class="text-xs">Angin</span>
                                    </div>
                                    <div class="font-semibold">12 km/h</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-blue-300/30">
                            <h4 class="text-sm font-medium mb-2">Prakiraan 3 Hari</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span>Besok</span>
                                    <div class="flex items-center">
                                        <span class="mr-2">🌤️</span>
                                        <span>26° - 30°</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span>Lusa</span>
                                    <div class="flex items-center">
                                        <span class="mr-2">🌧️</span>
                                        <span>24° - 28°</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span>3 Hari</span>
                                    <div class="flex items-center">
                                        <span class="mr-2">☀️</span>
                                        <span>27° - 31°</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                        <div class="space-y-3">
                            <a href="{{ route('peternak.ternak') }}"
                                class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-200">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Tambah Ternak</p>
                                    <p class="text-xs text-gray-500">Daftarkan ternak baru</p>
                                </div>
                            </a>

                            <a href="{{ route('peternak.konsultasi') }}"
                                class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-200">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Konsultasi</p>
                                    <p class="text-xs text-gray-500">Tanya ahli peternakan</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Auto refresh data every 5 minutes
        setInterval(function() {
            // Simulate data refresh
            console.log('Refreshing dashboard data...');
            // You can add AJAX call here to refresh data
        }, 300000); // 5 minutes

        // Quick stats animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });

            // Animate ternak cards
            const ternakCards = document.querySelectorAll('.ternak-card');
            ternakCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    card.style.transition = 'all 0.4s ease';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 50);
                }, (index * 50) + 500);
            });
        });

        // Real-time clock update
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            console.log('Current time:', timeString);
        }
        setInterval(updateTime, 1000);
    </script>
@endpush
