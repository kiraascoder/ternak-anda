{{-- resources/views/peternak/kesehatan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kesehatan Ternak')
@section('page-title', 'Kesehatan Ternak')
@section('page-description', 'Monitor dan kelola kesehatan ternak Anda')

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .status-sehat {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-sakit {
            background-color: #fecaca;
            color: #991b1b;
        }

        .status-perawatan {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-sembuh {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .health-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid #0ea5e9;
        }

        .health-card.critical {
            background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
            border-left-color: #ef4444;
        }

        .health-card.warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left-color: #f59e0b;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .pulse-dot {
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

        .search-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <!-- Search Box -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        class="search-box block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Cari ternak..." onkeyup="searchKesehatan()">
                </div>

                <!-- Filter Ternak -->
                <select id="ternakFilter"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    onchange="filterByTernak()">
                    <option value="">Semua Ternak</option>
                    @forelse($ternakList ?? [] as $ternak)
                        <option value="{{ $ternak->idTernak }}">{{ $ternak->namaTernak }}</option>
                    @empty
                        <option value="1">Sapi #001</option>
                        <option value="2">Sapi #002</option>
                        <option value="3">Kambing #001</option>
                    @endforelse
                </select>

                <!-- Filter Status -->
                <select id="statusFilter"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    onchange="filterByStatus()">
                    <option value="">Semua Status</option>
                    <option value="sehat">Sehat</option>
                    <option value="sakit">Sakit</option>
                    <option value="perawatan">Perawatan</option>
                    <option value="sembuh">Sembuh</option>
                </select>
            </div>

            <div class="flex items-center space-x-3">
                <!-- Export Button -->
                <button onclick="exportData()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export
                </button>

                <!-- Add Health Check Button -->
                <button onclick="openAddModal()"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transform hover:scale-105 transition-all">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Cek Kesehatan
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pemeriksaan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPemeriksaan ?? 45 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m4-6V7a2 2 0 012-2h2a2 2 0 012 2v2M7 7h.01M17 7h.01M7 17h.01M17 17h.01">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ternak Sehat</p>
                        <p class="text-2xl font-bold text-green-600">{{ $ternakSehat ?? 21 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Perlu Perawatan</p>
                        <p class="text-2xl font-bold text-red-600">{{ $ternakSakit ?? 3 }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full pulse-dot">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dalam Perawatan</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $ternakPerawatan ?? 1 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Alerts -->
        @if (($ternakSakit ?? 3) > 0)
            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-red-800">Peringatan Kesehatan</h3>
                        <p class="mt-1 text-sm text-red-700">
                            {{ $ternakSakit ?? 3 }} ekor ternak memerlukan perhatian medis segera.
                            <a href="#urgent-care" class="font-medium underline hover:no-underline">Lihat detail →</a>
                        </p>
                    </div>
                    <div class="ml-4">
                        <button onclick="this.parentElement.parentElement.parentElement.style.display='none'"
                            class="text-red-400 hover:text-red-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- View Toggle -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Tampilan:</span>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button onclick="switchView('cards')" id="cardsViewBtn"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-colors view-btn-active">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        Cards
                    </button>
                    <button onclick="switchView('timeline')" id="timelineViewBtn"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-gray-600 hover:text-gray-900">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Timeline
                    </button>
                </div>
            </div>

            <div class="text-sm text-gray-600">
                {{-- Menampilkan {{ $kesehatanList->count() ?? 15 }} pemeriksaan --}}
            </div>
        </div>

        <!-- Cards View -->
        <div id="cardsView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kesehatanList ?? [] as $index => $kesehatan)
                <div class="health-card {{ $kesehatan->tingkat_keparahan ?? ($index % 4 == 1 ? 'critical' : ($index % 4 == 2 ? 'warning' : '')) }} rounded-xl p-6 card-hover kesehatan-card"
                    data-ternak="{{ $kesehatan->ternak->namaTernak ?? 'Ternak #' . sprintf('%03d', $index + 1) }}"
                    data-status="{{ $kesehatan->status_kesehatan ?? 'sehat' }}">

                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <div class="text-2xl">
                                @if (isset($kesehatan->ternak->jenisTernak))
                                    @if (str_contains(strtolower($kesehatan->ternak->jenisTernak), 'kambing'))
                                        🐐
                                    @elseif(str_contains(strtolower($kesehatan->ternak->jenisTernak), 'domba'))
                                        🐑
                                    @else
                                        🐄
                                    @endif
                                @else
                                    🐄
                                @endif
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    {{ $kesehatan->ternak->namaTernak ?? 'Ternak #' . sprintf('%03d', $index + 1) }}</h3>
                                <p class="text-xs text-gray-500">
                                    {{ $kesehatan->tanggal_pemeriksaan ?? now()->format('d M Y') }}</p>
                            </div>
                        </div>
                        <span class="status-badge status-{{ $kesehatan->status_kesehatan ?? 'sehat' }}">
                            {{ ucfirst($kesehatan->status_kesehatan ?? 'sehat') }}
                        </span>
                    </div>

                    <!-- Health Info -->
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Suhu:</span>
                                <span class="font-medium ml-1">{{ $kesehatan->suhu ?? '38.5' }}°C</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Berat:</span>
                                <span class="font-medium ml-1">{{ $kesehatan->berat ?? rand(200, 600) }} kg</span>
                            </div>
                        </div>

                        @if (isset($kesehatan->gejala) || $index % 3 == 1)
                            <div class="text-sm">
                                <span class="text-gray-500">Gejala:</span>
                                <p class="text-gray-900 mt-1">
                                    {{ $kesehatan->gejala ?? 'Nafsu makan menurun, terlihat lesu' }}</p>
                            </div>
                        @endif

                        @if (isset($kesehatan->diagnosa) || $index % 4 == 2)
                            <div class="text-sm">
                                <span class="text-gray-500">Diagnosa:</span>
                                <p class="text-gray-900 mt-1">
                                    {{ $kesehatan->diagnosa ?? 'Pemeriksaan rutin - kondisi normal' }}</p>
                            </div>
                        @endif

                        @if (isset($kesehatan->pengobatan) || $index % 3 == 0)
                            <div class="text-sm">
                                <span class="text-gray-500">Pengobatan:</span>
                                <p class="text-gray-900 mt-1">
                                    {{ $kesehatan->pengobatan ?? 'Vitamin dan suplemen nutrisi' }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                        <div class="flex space-x-2">
                            <button onclick="viewDetail({{ $kesehatan->id ?? $index + 1 }})"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat Detail
                            </button>
                            <span class="text-gray-300">•</span>
                            <button onclick="editKesehatan({{ $kesehatan->id ?? $index + 1 }})"
                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Edit
                            </button>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $kesehatan->pemeriksa ?? 'Dr. Veteriner' }}
                        </div>
                    </div>
                </div>
            @empty
                <!-- Sample Data -->
                @for ($i = 1; $i <= 6; $i++)
                    <div class="health-card {{ $i % 4 == 1 ? 'critical' : ($i % 4 == 2 ? 'warning' : '') }} rounded-xl p-6 card-hover kesehatan-card"
                        data-ternak="Ternak #{{ sprintf('%03d', $i) }}"
                        data-status="{{ $i <= 3 ? 'sehat' : ($i == 4 ? 'sakit' : 'perawatan') }}">

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="text-2xl">🐄</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Ternak #{{ sprintf('%03d', $i) }}</h3>
                                    <p class="text-xs text-gray-500">{{ now()->subDays($i)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="status-badge status-{{ $i <= 3 ? 'sehat' : ($i == 4 ? 'sakit' : 'perawatan') }}">
                                {{ $i <= 3 ? 'Sehat' : ($i == 4 ? 'Sakit' : 'Perawatan') }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Suhu:</span>
                                    <span
                                        class="font-medium ml-1">{{ $i <= 3 ? '38.5' : ($i == 4 ? '39.8' : '38.9') }}°C</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Berat:</span>
                                    <span class="font-medium ml-1">{{ rand(200, 600) }} kg</span>
                                </div>
                            </div>

                            @if ($i > 3)
                                <div class="text-sm">
                                    <span class="text-gray-500">Gejala:</span>
                                    <p class="text-gray-900 mt-1">
                                        {{ $i == 4 ? 'Nafsu makan menurun, demam tinggi' : 'Dalam masa pemulihan' }}</p>
                                </div>
                            @endif

                            <div class="text-sm">
                                <span class="text-gray-500">Diagnosa:</span>
                                <p class="text-gray-900 mt-1">
                                    {{ $i <= 3 ? 'Pemeriksaan rutin - kondisi normal' : ($i == 4 ? 'Infeksi saluran pernafasan' : 'Masa pemulihan post-treatment') }}
                                </p>
                            </div>

                            @if ($i > 3)
                                <div class="text-sm">
                                    <span class="text-gray-500">Pengobatan:</span>
                                    <p class="text-gray-900 mt-1">
                                        {{ $i == 4 ? 'Antibiotik dan anti-inflamasi' : 'Vitamin dan suplemen pemulihan' }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                            <div class="flex space-x-2">
                                <button onclick="viewDetail({{ $i }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Lihat Detail
                                </button>
                                <span class="text-gray-300">•</span>
                                <button onclick="editKesehatan({{ $i }})"
                                    class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Edit
                                </button>
                            </div>
                            <div class="text-xs text-gray-500">
                                Dr. {{ ['Budi', 'Sari', 'Ahmad', 'Maya', 'Anto', 'Rina'][$i - 1] }}
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>

        <!-- Timeline View -->
        <div id="timelineView" class="hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Riwayat Kesehatan</h3>

                <div class="space-y-6" id="urgent-care">
                    @forelse($kesehatanList ?? [] as $index => $kesehatan)
                        <div class="timeline-item flex items-start space-x-4 kesehatan-timeline"
                            data-ternak="{{ $kesehatan->ternak->namaTernak ?? 'Ternak #' . sprintf('%03d', $index + 1) }}"
                            data-status="{{ $kesehatan->status_kesehatan ?? 'sehat' }}">

                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 bg-{{ $kesehatan->status_kesehatan === 'sakit' ? 'red' : ($kesehatan->status_kesehatan === 'perawatan' ? 'yellow' : 'green') }}-100 rounded-full flex items-center justify-center border-2 border-{{ $kesehatan->status_kesehatan === 'sakit' ? 'red' : ($kesehatan->status_kesehatan === 'perawatan' ? 'yellow' : 'green') }}-500">
                                    @if ($kesehatan->status_kesehatan === 'sakit')
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                    @elseif($kesehatan->status_kesehatan === 'perawatan')
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                                            </path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ $kesehatan->ternak->namaTernak ?? 'Ternak #' . sprintf('%03d', $index + 1) }}
                                    </h4>
                                    <span
                                        class="text-xs text-gray-500">{{ $kesehatan->tanggal_pemeriksaan ?? now()->subDays($index)->format('d M Y, H:i') }}</span>
                                </div>

                                <div class="text-sm text-gray-600 mb-2">
                                    <strong>Diagnosa:</strong>
                                    {{ $kesehatan->diagnosa ?? 'Pemeriksaan rutin - kondisi normal' }}
                                </div>

                                @if (isset($kesehatan->gejala) || $index % 3 == 1)
                                    <div class="text-sm text-gray-600 mb-2">
                                        <strong>Gejala:</strong> {{ $kesehatan->gejala ?? 'Tidak ada gejala khusus' }}
                                    </div>
                                @endif

                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>Suhu: {{ $kesehatan->suhu ?? '38.5' }}°C</span>
                                    <span>Berat: {{ $kesehatan->berat ?? rand(200, 600) }} kg</span>
                                    <span>Pemeriksa: {{ $kesehatan->pemeriksa ?? 'Dr. Veteriner' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Sample Timeline Data -->
                        @for ($i = 1; $i <= 8; $i++)
                            <div class="timeline-item flex items-start space-x-4 kesehatan-timeline"
                                data-ternak="Ternak #{{ sprintf('%03d', $i) }}"
                                data-status="{{ $i <= 5 ? 'sehat' : ($i == 6 ? 'sakit' : 'perawatan') }}">

                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-{{ $i <= 5 ? 'green' : ($i == 6 ? 'red' : 'yellow') }}-100 rounded-full flex items-center justify-center border-2 border-{{ $i <= 5 ? 'green' : ($i == 6 ? 'red' : 'yellow') }}-500">
                                        @if ($i == 6)
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                </path>
                                            </svg>
                                        @elseif($i > 6)
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                                                </path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-medium text-gray-900">Ternak #{{ sprintf('%03d', $i) }}
                                        </h4>
                                        <span
                                            class="text-xs text-gray-500">{{ now()->subDays($i)->format('d M Y, H:i') }}</span>
                                    </div>

                                    <div class="text-sm text-gray-600 mb-2">
                                        <strong>Diagnosa:</strong>
                                        {{ $i <= 5 ? 'Pemeriksaan rutin - kondisi normal' : ($i == 6 ? 'Infeksi saluran pernafasan' : 'Dalam masa pemulihan') }}
                                    </div>

                                    @if ($i > 5)
                                        <div class="text-sm text-gray-600 mb-2">
                                            <strong>Gejala:</strong>
                                            {{ $i == 6 ? 'Nafsu makan menurun, demam tinggi, batuk' : 'Masa pemulihan, nafsu makan mulai membaik' }}
                                        </div>
                                    @endif

                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span>Suhu: {{ $i <= 5 ? '38.5' : ($i == 6 ? '39.8' : '38.9') }}°C</span>
                                        <span>Berat: {{ rand(200, 600) }} kg</span>
                                        <span>Pemeriksa: Dr.
                                            {{ ['Budi', 'Sari', 'Ahmad', 'Maya', 'Anto', 'Rina', 'Dedi', 'Lila'][$i - 1] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if (isset($kesehatanList) && method_exists($kesehatanList, 'links'))
            <div class="flex justify-center">
                {{ $kesehatanList->links() }}
            </div>
        @else
            <!-- Sample Pagination -->
            <div class="flex justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <a href="#"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-primary text-sm font-medium text-white">1</a>
                    <a href="#"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                    <a href="#"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</a>
                    <a href="#"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </nav>
            </div>
        @endif
    </div>

    <!-- Add Health Check Modal -->
    <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Pemeriksaan Kesehatan Baru</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="addKesehatanForm" action="" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pilih Ternak -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="ternak_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Ternak <span class="text-red-500">*</span>
                        </label>
                        <select id="ternak_id" name="ternak_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Pilih Ternak untuk Diperiksa</option>
                            @forelse($ternakList ?? [] as $ternak)
                                <option value="{{ $ternak->idTernak }}">{{ $ternak->namaTernak }} -
                                    {{ $ternak->jenisTernak }}</option>
                            @empty
                                <option value="1">Sapi #001 - Sapi Limosin</option>
                                <option value="2">Sapi #002 - Sapi Brahman</option>
                                <option value="3">Kambing #001 - Kambing Etawa</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- Tanggal Pemeriksaan -->
                    <div>
                        <label for="tanggal_pemeriksaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pemeriksaan <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" required
                            value="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <!-- Pemeriksa -->
                    <div>
                        <label for="pemeriksa" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pemeriksa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="pemeriksa" name="pemeriksa" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Dr. Nama Dokter Hewan">
                    </div>

                    <!-- Suhu Tubuh -->
                    <div>
                        <label for="suhu" class="block text-sm font-medium text-gray-700 mb-2">
                            Suhu Tubuh (°C) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="suhu" name="suhu" step="0.1" min="35"
                            max="45" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="38.5">
                        <p class="text-xs text-gray-500 mt-1">Normal: 38.0 - 39.5°C</p>
                    </div>

                    <!-- Berat Badan -->
                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700 mb-2">
                            Berat Badan (kg)
                        </label>
                        <input type="number" id="berat" name="berat" min="0" step="0.1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="250.5">
                    </div>

                    <!-- Status Kesehatan -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Status Kesehatan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status_kesehatan" value="sehat" checked
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Sehat</span>
                                    </div>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status_kesehatan" value="sakit"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Sakit</span>
                                    </div>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status_kesehatan" value="perawatan"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Perawatan</span>
                                    </div>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status_kesehatan" value="sembuh"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Sembuh</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Gejala -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="gejala" class="block text-sm font-medium text-gray-700 mb-2">
                            Gejala yang Diamati
                        </label>
                        <textarea id="gejala" name="gejala" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                            placeholder="Deskripsikan gejala yang terlihat..."></textarea>
                    </div>

                    <!-- Diagnosa -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">
                            Diagnosa <span class="text-red-500">*</span>
                        </label>
                        <textarea id="diagnosa" name="diagnosa" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                            placeholder="Hasil diagnosa pemeriksaan..."></textarea>
                    </div>

                    <!-- Pengobatan -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="pengobatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pengobatan/Tindakan
                        </label>
                        <textarea id="pengobatan" name="pengobatan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                            placeholder="Pengobatan yang diberikan dan rekomendasi..."></textarea>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Tambahan
                        </label>
                        <textarea id="catatan" name="catatan" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                            placeholder="Catatan atau observasi lainnya..."></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                        <span id="submitText">Simpan Pemeriksaan</span>
                        <svg id="submitLoading" class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentView = 'cards';

        // Switch between cards and timeline view
        function switchView(view) {
            currentView = view;
            const cardsView = document.getElementById('cardsView');
            const timelineView = document.getElementById('timelineView');
            const cardsBtn = document.getElementById('cardsViewBtn');
            const timelineBtn = document.getElementById('timelineViewBtn');

            if (view === 'cards') {
                cardsView.classList.remove('hidden');
                timelineView.classList.add('hidden');
                cardsBtn.classList.add('view-btn-active', 'bg-white', 'text-gray-900', 'shadow-sm');
                cardsBtn.classList.remove('text-gray-600');
                timelineBtn.classList.remove('view-btn-active', 'bg-white', 'text-gray-900', 'shadow-sm');
                timelineBtn.classList.add('text-gray-600');
            } else {
                cardsView.classList.add('hidden');
                timelineView.classList.remove('hidden');
                timelineBtn.classList.add('view-btn-active', 'bg-white', 'text-gray-900', 'shadow-sm');
                timelineBtn.classList.remove('text-gray-600');
                cardsBtn.classList.remove('view-btn-active', 'bg-white', 'text-gray-900', 'shadow-sm');
                cardsBtn.classList.add('text-gray-600');
            }
        }

        // Search functionality
        function searchKesehatan() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.kesehatan-card');
            const timeline = document.querySelectorAll('.kesehatan-timeline');

            // Filter cards
            cards.forEach(card => {
                const ternak = card.getAttribute('data-ternak').toLowerCase();
                if (ternak.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Filter timeline items
            timeline.forEach(item => {
                const ternak = item.getAttribute('data-ternak').toLowerCase();
                if (ternak.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Filter by ternak
        function filterByTernak() {
            const ternakFilter = document.getElementById('ternakFilter').value;
            const cards = document.querySelectorAll('.kesehatan-card');
            const timeline = document.querySelectorAll('.kesehatan-timeline');

            // Filter cards
            cards.forEach(card => {
                const ternak = card.getAttribute('data-ternak');
                if (!ternakFilter || ternak.includes(ternakFilter) || card.getAttribute('data-ternak-id') ===
                    ternakFilter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Filter timeline items
            timeline.forEach(item => {
                const ternak = item.getAttribute('data-ternak');
                if (!ternakFilter || ternak.includes(ternakFilter) || item.getAttribute('data-ternak-id') ===
                    ternakFilter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Filter by status
        function filterByStatus() {
            const statusFilter = document.getElementById('statusFilter').value;
            const cards = document.querySelectorAll('.kesehatan-card');
            const timeline = document.querySelectorAll('.kesehatan-timeline');

            // Filter cards
            cards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (!statusFilter || status === statusFilter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Filter timeline items
            timeline.forEach(item => {
                const status = item.getAttribute('data-status');
                if (!statusFilter || status === statusFilter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addKesehatanForm').reset();
            document.getElementById('tanggal_pemeriksaan').value = new Date().toISOString().slice(0, 16);
            setTimeout(() => {
                document.getElementById('ternak_id').focus();
            }, 100);
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        // View detail function
        function viewDetail(id) {
            // Redirect to detail page or open detail modal
            window.location.href = `/kesehatan/${id}`;
        }

        // Edit function
        function editKesehatan(id) {
            // Redirect to edit page or open edit modal
            window.location.href = `/kesehatan/${id}/edit`;
        }

        // Export function
        function exportData() {
            alert('Fitur export akan segera tersedia!');
        }

        // Form submission
        document.getElementById('addKesehatanForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate required fields
            const ternakId = document.getElementById('ternak_id').value;
            const tanggal = document.getElementById('tanggal_pemeriksaan').value;
            const pemeriksa = document.getElementById('pemeriksa').value.trim();
            const suhu = document.getElementById('suhu').value;
            const diagnosa = document.getElementById('diagnosa').value.trim();

            if (!ternakId) {
                alert('Pilih ternak yang akan diperiksa');
                document.getElementById('ternak_id').focus();
                return;
            }

            if (!tanggal) {
                alert('Tanggal pemeriksaan harus diisi');
                document.getElementById('tanggal_pemeriksaan').focus();
                return;
            }

            if (!pemeriksa) {
                alert('Nama pemeriksa harus diisi');
                document.getElementById('pemeriksa').focus();
                return;
            }

            if (!suhu || suhu < 35 || suhu > 45) {
                alert('Suhu tubuh harus diisi dengan nilai yang valid (35-45°C)');
                document.getElementById('suhu').focus();
                return;
            }

            if (!diagnosa) {
                alert('Diagnosa harus diisi');
                document.getElementById('diagnosa').focus();
                return;
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');

            submitBtn.disabled = true;
            submitText.textContent = 'Menyimpan...';
            submitLoading.classList.remove('hidden');

            // Submit form (you can add AJAX here if needed)
            setTimeout(() => {
                closeAddModal();
                alert('Data pemeriksaan kesehatan berhasil disimpan!');
                window.location.reload();
            }, 1500);
        });

        // Close modal when clicking outside
        document.getElementById('addModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation on load
            const cards = document.querySelectorAll('.kesehatan-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 50);
            });
        });

        // Add style for view button active state
        const style = document.createElement('style');
        style.textContent = `
        .view-btn-active {
            background-color: white !important;
            color: #111827 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
    `;
        document.head.appendChild(style);
    </script>
@endpush
