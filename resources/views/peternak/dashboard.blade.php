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
            <div class="stats-card bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Ternak</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalTernak ?? 25 }}</p>
                        <p class="text-green-600 text-sm mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                +2 minggu ini
                            </span>
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
            <div class="stats-card bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Ternak Sehat</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $ternakSehat ?? 23 }}</p>
                        <p class="text-green-600 text-sm mt-1">92% kondisi baik</p>
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
            <div class="stats-card bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Perlu Perhatian</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $ternakSakit ?? 2 }}</p>
                        <p class="text-red-600 text-sm mt-1">Butuh pemeriksaan</p>
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
            <div class="stats-card bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Konsultasi Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $konsultasiSaya ?? 3 }}</p>
                        <p class="text-blue-600 text-sm mt-1">1 menunggu respon</p>
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

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-green-100 p-2 rounded-full">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Vaksinasi sapi #007 berhasil</p>
                                <p class="text-xs text-gray-500">2 jam yang lalu</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-green-100 p-2 rounded-full">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Vaksinasi sapi #007 berhasil</p>
                                <p class="text-xs text-gray-500">2 jam yang lalu</p>
                            </div>
                        </div>
                        

                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-blue-100 p-2 rounded-full">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Konsultasi dengan Dr. Budi dijawab</p>
                                <p class="text-xs text-gray-500">5 jam yang lalu</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-yellow-100 p-2 rounded-full">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Sapi #003 memerlukan pemeriksaan</p>
                                <p class="text-xs text-gray-500">1 hari yang lalu</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="#" class="text-sm text-primary hover:text-secondary font-medium">Lihat semua
                            aktivitas</a>
                    </div>
                </div>
            </div>

            
            <div class="space-y-6">                
                <div class="weather-gradient rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Cuaca Hari Ini</h3>
                        <div class="text-3xl">☀️</div>
                    </div>
                    <div class="text-3xl font-bold mb-2">28°C</div>
                    <p class="text-blue-100 text-sm">Cerah berawan</p>
                    <p class="text-blue-200 text-xs mt-1">Makassar, Sulawesi Selatan</p>
                    <div class="mt-4 pt-4 border-t border-blue-300/30">
                        <div class="flex justify-between text-sm">
                            <span>Kelembaban: 65%</span>
                            <span>Angin: 12 km/h</span>
                        </div>
                    </div>
                </div>                            
            </div>
        </div>

        <!-- Bottom Section - Ternak Overview -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Ternak Terbaru</h3>
                <a href="{{ route('peternak.ternak') }}" class="text-sm text-primary hover:text-secondary font-medium">Lihat
                    Semua</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse($recentTernakList ?? [] as $ternak)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg">🐄</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $ternak->status === 'sehat' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($ternak->status ?? 'Sehat') }}
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-900">
                            {{ $ternak->namaTernak ?? 'Sapi #' . sprintf('%03d', rand(1, 999)) }}
                        </h4>
                        <p class="text-sm text-gray-600">{{ $ternak->jenis ?? 'Tidak diketahui' }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            Umur: {{ $ternak->umur ? $ternak->umur . ' tahun' : 'Belum diketahui' }}
                        </p>
                    </div>
                @empty
                    <!-- Sample data jika tidak ada data -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg">🐄</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Sehat
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-900">Sapi #001</h4>
                        <p class="text-sm text-gray-600">Sapi Limosin</p>
                        <p class="text-xs text-gray-500 mt-2">Umur: 3 tahun</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg">🐄</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Sehat
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-900">Sapi #002</h4>
                        <p class="text-sm text-gray-600">Sapi Brahman</p>
                        <p class="text-xs text-gray-500 mt-2">Umur: 2 tahun</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg">🐄</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Sakit
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-900">Sapi #003</h4>
                        <p class="text-sm text-gray-600">Sapi Angus</p>
                        <p class="text-xs text-gray-500 mt-2">Umur: 4 tahun</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg">🐄</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Sehat
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-900">Sapi #004</h4>
                        <p class="text-sm text-gray-600">Sapi Simental</p>
                        <p class="text-xs text-gray-500 mt-2">Umur: 1 tahun</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        const ctx = document.getElementById('healthChart').getContext('2d');
        const healthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Ternak Sehat',
                    data: [20, 22, 23, 21, 24, 23, 23],
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Ternak Sakit',
                    data: [5, 3, 2, 4, 1, 2, 2],
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });

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
        });
    </script>
@endpush
