@extends('layouts.app')

@section('title', 'Laporan Kesehatan')
@section('page-title', 'Laporan Kesehatan')
@section('page-description', 'Monitor dan kelola kesehatan ternak Anda')

@push('styles')
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

        .status-observasi {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .status-rujuk {
            background-color: #fce7f3;
            color: #be185d;
        }

        .priority-critical {
            background-color: #fef2f2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .priority-high {
            background-color: #fffbeb;
            color: #d97706;
            border-left: 4px solid #d97706;
        }

        .priority-medium {
            background-color: #f0f9ff;
            color: #0284c7;
            border-left: 4px solid #0284c7;
        }

        .priority-low {
            background-color: #f0fdf4;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item:last-child::before {
            background: linear-gradient(to bottom, #e5e7eb 0%, transparent 100%);
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 0.75rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #e5e7eb;
        }

        .timeline-dot.sehat {
            background-color: #10b981;
        }

        .timeline-dot.sakit {
            background-color: #ef4444;
        }

        .timeline-dot.perawatan {
            background-color: #f59e0b;
        }

        .modal-backdrop {
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .health-chart {
            height: 300px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .health-meter {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#ef4444 0deg 36deg,
                    #f59e0b 36deg 108deg,
                    #10b981 108deg 360deg);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .health-meter::before {
            content: '';
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            position: absolute;
        }

        .health-score {
            position: relative;
            z-index: 10;
            font-size: 1.5rem;
            font-weight: bold;
            color: #374151;
        }

        .attachment-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .document-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-toggle {
            background: #f8fafc;
            border-radius: 0.5rem;
            padding: 0.25rem;
        }

        .view-toggle button.active {
            background: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .health-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .temperature-bar {
            height: 8px;
            border-radius: 4px;
            background: linear-gradient(90deg, #3b82f6 0%, #10b981 50%, #ef4444 100%);
            position: relative;
        }

        .temperature-indicator {
            position: absolute;
            top: -2px;
            width: 12px;
            height: 12px;
            background: white;
            border: 2px solid #374151;
            border-radius: 50%;
            transform: translateX(-50%);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Cari laporan..." onkeyup="searchLaporan()">
                </div>

                <select id="statusFilter" onchange="filterByStatus()"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="sehat">Sehat</option>
                    <option value="sakit">Sakit</option>
                    <option value="perawatan">Perawatan</option>
                    <option value="observasi">Observasi</option>
                    <option value="rujuk">Rujuk</option>
                </select>

                <select id="ternakFilter" onchange="filterByTernak()"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Ternak</option>
                    <option value="1">Sapi Limosin #001</option>
                    <option value="2">Sapi Brahman #002</option>
                    <option value="3">Kambing Boer #003</option>
                    <option value="4">Domba Garut #004</option>
                </select>

                <input type="date" id="dateFilter" onchange="filterByDate()"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div class="flex items-center space-x-3">
                <div class="view-toggle flex items-center">
                    <button onclick="switchView('timeline')" id="timelineViewBtn"
                        class="px-3 py-2 text-sm font-medium rounded transition-colors active">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Timeline
                    </button>
                    <button onclick="switchView('cards')" id="cardsViewBtn"
                        class="px-3 py-2 text-sm font-medium rounded transition-colors text-gray-600">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        Cards
                    </button>
                </div>

                <button onclick="exportData()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export
                </button>

                <button onclick="openLaporanModal()"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transform hover:scale-105 transition-all">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Laporan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalLaporan ?? 48 }}</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Ternak Sehat</p>
                                <p class="text-2xl font-bold text-green-600">{{ $ternakSehat ?? 35 }}</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                <p class="text-sm font-medium text-gray-600">Dalam Perawatan</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $ternakPerawatan ?? 8 }}</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                <p class="text-sm font-medium text-gray-600">Perlu Perhatian</p>
                                <p class="text-2xl font-bold text-red-600">{{ $ternakSakit ?? 5 }}</p>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Kesehatan</h3>
                    </div>

                    <div id="timelineView" class="p-6">
                        @php
                            $laporanData = [
                                [
                                    'id' => 1,
                                    'tanggal' => '2025-06-29 08:30',
                                    'ternak' => 'Sapi Limosin #001',
                                    'ternak_id' => 1,
                                    'status' => 'sehat',
                                    'prioritas' => 'low',
                                    'diagnosa' => 'Pemeriksaan rutin bulanan',
                                    'gejala' => 'Kondisi baik, nafsu makan normal',
                                    'suhu' => 38.5,
                                    'berat' => 385,
                                    'tindakan' => 'Vaksinasi dan vitamin',
                                    'dokter' => 'Dr. Budi Santoso',
                                    'biaya' => 150000,
                                    'follow_up' => '2025-07-29',
                                ],
                                [
                                    'id' => 2,
                                    'tanggal' => '2025-06-27 14:15',
                                    'ternak' => 'Kambing Boer #003',
                                    'ternak_id' => 3,
                                    'status' => 'perawatan',
                                    'prioritas' => 'medium',
                                    'diagnosa' => 'Infeksi saluran pernapasan ringan',
                                    'gejala' => 'Batuk ringan, sedikit demam',
                                    'suhu' => 39.2,
                                    'berat' => 45,
                                    'tindakan' => 'Antibiotik dan istirahat',
                                    'dokter' => 'Dr. Sari Widiarti',
                                    'biaya' => 250000,
                                    'follow_up' => '2025-07-02',
                                ],
                                [
                                    'id' => 3,
                                    'tanggal' => '2025-06-25 10:00',
                                    'ternak' => 'Sapi Brahman #002',
                                    'ternak_id' => 2,
                                    'status' => 'sakit',
                                    'prioritas' => 'high',
                                    'diagnosa' => 'Mastitis akut',
                                    'gejala' => 'Pembengkakan ambing, demam tinggi',
                                    'suhu' => 40.1,
                                    'berat' => 420,
                                    'tindakan' => 'Pengobatan intensif, isolasi',
                                    'dokter' => 'Dr. Agus Permana',
                                    'biaya' => 500000,
                                    'follow_up' => '2025-06-30',
                                ],
                            ];
                        @endphp

                        <div class="space-y-6">
                            @foreach ($laporanData as $index => $laporan)
                                <div class="timeline-item laporan-item" data-ternak="{{ $laporan['ternak'] }}"
                                    data-status="{{ $laporan['status'] }}" data-id="{{ $laporan['id'] }}">
                                    <div class="timeline-dot {{ $laporan['status'] }}"></div>
                                    <div class="bg-gray-50 rounded-lg p-4 ml-4 priority-{{ $laporan['prioritas'] }}">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h4 class="text-lg font-semibold text-gray-900">
                                                        {{ $laporan['ternak'] }}</h4>
                                                    <span class="status-badge status-{{ $laporan['status'] }}">
                                                        {{ ucfirst($laporan['status']) }}
                                                    </span>
                                                    @if ($laporan['prioritas'] === 'high')
                                                        <span
                                                            class="text-xs font-medium text-red-600 bg-red-100 px-2 py-1 rounded">
                                                            URGENT
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-600 mb-2">{{ $laporan['tanggal'] }} • Dr.
                                                    {{ $laporan['dokter'] }}</p>
                                                <p class="font-medium text-gray-900 mb-2">{{ $laporan['diagnosa'] }}</p>
                                                <p class="text-sm text-gray-600 mb-3">{{ $laporan['gejala'] }}</p>

                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="font-medium text-gray-700">Suhu:</span>
                                                        <div class="flex items-center mt-1">
                                                            <span
                                                                class="health-indicator bg-{{ $laporan['suhu'] > 39 ? 'red' : ($laporan['suhu'] > 38.8 ? 'yellow' : 'green') }}-500"></span>
                                                            <span
                                                                class="text-{{ $laporan['suhu'] > 39 ? 'red' : ($laporan['suhu'] > 38.8 ? 'yellow' : 'green') }}-600 font-medium">
                                                                {{ $laporan['suhu'] }}°C
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Berat:</span>
                                                        <p class="text-gray-900">{{ $laporan['berat'] }} kg</p>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Biaya:</span>
                                                        <p class="text-gray-900">Rp {{ number_format($laporan['biaya']) }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Follow-up:</span>
                                                        <p class="text-gray-900">{{ $laporan['follow_up'] }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex space-x-2 ml-4">
                                                <button onclick="openDetailModal({{ $laporan['id'] }})"
                                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm font-medium hover:bg-blue-200 transition-colors">
                                                    Detail
                                                </button>
                                                <button onclick="openEditModal({{ $laporan['id'] }})"
                                                    class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm font-medium hover:bg-green-200 transition-colors">
                                                    Edit
                                                </button>
                                                <button onclick="openDeleteModal({{ $laporan['id'] }})"
                                                    class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm font-medium hover:bg-red-200 transition-colors">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="cardsView" class="hidden p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($laporanData as $laporan)
                                <div class="bg-white border border-gray-200 rounded-lg p-6 card-hover laporan-card"
                                    data-ternak="{{ $laporan['ternak'] }}" data-status="{{ $laporan['status'] }}"
                                    data-id="{{ $laporan['id'] }}">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $laporan['ternak'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ $laporan['tanggal'] }}</p>
                                        </div>
                                        <span class="status-badge status-{{ $laporan['status'] }}">
                                            {{ ucfirst($laporan['status']) }}
                                        </span>
                                    </div>

                                    <div class="space-y-3">
                                        <div>
                                            <h5 class="font-medium text-gray-900">{{ $laporan['diagnosa'] }}</h5>
                                            <p class="text-sm text-gray-600">{{ $laporan['gejala'] }}</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-700">Suhu:</span>
                                                <span
                                                    class="text-{{ $laporan['suhu'] > 39 ? 'red' : ($laporan['suhu'] > 38.8 ? 'yellow' : 'green') }}-600 font-medium">
                                                    {{ $laporan['suhu'] }}°C
                                                </span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-700">Dokter:</span>
                                                <span class="text-gray-900">{{ $laporan['dokter'] }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                            <span class="text-sm font-medium text-gray-700">
                                                Biaya: <span class="text-gray-900">Rp
                                                    {{ number_format($laporan['biaya']) }}</span>
                                            </span>
                                            <div class="flex space-x-2">
                                                <button onclick="openDetailModal({{ $laporan['id'] }})"
                                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    Detail
                                                </button>
                                                <button onclick="openEditModal({{ $laporan['id'] }})"
                                                    class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                    Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Health Score</h3>
                    <div class="health-chart">
                        <div class="health-meter">
                            <div class="health-score">85%</div>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Sehat</span>
                            <span class="font-medium text-green-600">72%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Perawatan</span>
                            <span class="font-medium text-yellow-600">18%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Sakit</span>
                            <span class="font-medium text-red-600">10%</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Follow-up Mendatang</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Sapi Brahman #002</p>
                                <p class="text-sm text-gray-600">Kontrol mastitis</p>
                            </div>
                            <span class="text-xs font-medium text-yellow-700 bg-yellow-200 px-2 py-1 rounded">
                                30 Jun
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Kambing Boer #003</p>
                                <p class="text-sm text-gray-600">Cek pernapasan</p>
                            </div>
                            <span class="text-xs font-medium text-blue-700 bg-blue-200 px-2 py-1 rounded">
                                02 Jul
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Sapi Limosin #001</p>
                                <p class="text-sm text-gray-600">Vaksinasi rutin</p>
                            </div>
                            <span class="text-xs font-medium text-green-700 bg-green-200 px-2 py-1 rounded">
                                29 Jul
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Laporan -->
    <div id="laporanModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 id="laporanModalTitle" class="text-xl font-semibold text-gray-900">Tambah Laporan Kesehatan</h3>
                <button onclick="closeLaporanModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="laporanForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="laporanId" name="id">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label for="ternakSelect" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Ternak <span class="text-red-500">*</span>
                            </label>
                            <select id="ternakSelect" name="ternak_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Ternak</option>
                                <option value="1">Sapi Limosin #001 - Makmur</option>
                                <option value="2">Sapi Brahman #002 - Sejahtera</option>
                                <option value="3">Kambing Boer #003 - Berkah</option>
                                <option value="4">Domba Garut #004 - Rezeki</option>
                                <option value="5">Sapi Angus #005 - Jaya</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tanggalPemeriksaan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Pemeriksaan <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="tanggalPemeriksaan" name="tanggal_pemeriksaan" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label for="dokterPemeriksa" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dokter/Pemeriksa <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="dokterPemeriksa" name="dokter_pemeriksa" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Dr. Budi Santoso">
                            </div>
                        </div>

                        <div>
                            <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">
                                Diagnosa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="diagnosa" name="diagnosa" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Contoh: Pemeriksaan rutin bulanan">
                        </div>

                        <div>
                            <label for="gejala" class="block text-sm font-medium text-gray-700 mb-2">
                                Gejala & Observasi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="gejala" name="gejala" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                placeholder="Jelaskan gejala yang diamati dan kondisi ternak..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="suhuTubuh" class="block text-sm font-medium text-gray-700 mb-2">
                                    Suhu Tubuh (°C) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="suhuTubuh" name="suhu_tubuh" step="0.1" min="35"
                                    max="45" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="38.5">
                                <div class="temperature-bar mt-2">
                                    <div id="temperatureIndicator" class="temperature-indicator" style="left: 50%;">
                                    </div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>35°C</span>
                                    <span class="text-green-600">Normal</span>
                                    <span>45°C</span>
                                </div>
                            </div>

                            <div>
                                <label for="beratTernak" class="block text-sm font-medium text-gray-700 mb-2">
                                    Berat Ternak (kg)
                                </label>
                                <input type="number" id="beratTernak" name="berat_ternak" step="0.1"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="385">
                            </div>
                        </div>

                        <div>
                            <label for="statusKesehatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Kesehatan <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label
                                    class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="status_kesehatan" value="sehat"
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
                                    <input type="radio" name="status_kesehatan" value="observasi"
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                            <span class="text-sm font-medium text-gray-900">Observasi</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-2">
                                Tindakan & Pengobatan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="tindakan" name="tindakan" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                placeholder="Jelaskan tindakan yang dilakukan, obat yang diberikan, dosis, dll..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="prioritas" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prioritas <span class="text-red-500">*</span>
                                </label>
                                <select id="prioritas" name="prioritas" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="">Pilih Prioritas</option>
                                    <option value="low">Rendah - Rutin</option>
                                    <option value="medium">Sedang - Perhatian</option>
                                    <option value="high">Tinggi - Urgent</option>
                                    <option value="critical">Kritis - Emergency</option>
                                </select>
                            </div>

                            <div>
                                <label for="biayaPengobatan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Biaya Pengobatan (Rp)
                                </label>
                                <input type="number" id="biayaPengobatan" name="biaya_pengobatan" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="150000">
                            </div>
                        </div>

                        <div>
                            <label for="followUp" class="block text-sm font-medium text-gray-700 mb-2">
                                Jadwal Follow-up
                            </label>
                            <input type="date" id="followUp" name="follow_up" min="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label for="dokumenPendukung" class="block text-sm font-medium text-gray-700 mb-2">
                                Dokumen Pendukung
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dokumenPendukung"
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                                upload</span> atau drag & drop</p>
                                        <p class="text-xs text-gray-500">Foto, PDF, DOC (MAX. 5MB per file)</p>
                                    </div>
                                    <input id="dokumenPendukung" name="dokumen[]" type="file" class="hidden"
                                        accept="image/*,.pdf,.doc,.docx" multiple onchange="previewDokumen(this)">
                                </label>
                            </div>
                            <div id="dokumentPreview" class="mt-3 hidden grid grid-cols-4 gap-3"></div>
                        </div>

                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Tambahan
                            </label>
                            <textarea id="catatan" name="catatan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                placeholder="Catatan khusus, rekomendasi, atau informasi penting lainnya..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeLaporanModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="laporanSubmitBtn"
                        class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                        <span id="laporanSubmitText">Simpan Laporan</span>
                        <svg id="laporanSubmitLoading" class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
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

    <!-- Modal Detail Laporan -->
    <div id="detailModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Detail Laporan Kesehatan</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="detailContent">
                <!-- Content will be populated by JavaScript -->
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                    Tutup
                </button>
                <button onclick="editFromDetail()" id="editFromDetailBtn"
                    class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
                    Edit Laporan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Delete Konfirmasi -->
    <div id="deleteModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white modal-content">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Laporan Kesehatan</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus laporan kesehatan ini?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="items-center px-4 py-3 mt-4">
                    <button id="confirmDeleteBtn"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <span id="deleteText">Hapus</span>
                        <svg id="deleteLoading" class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-900 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentView = 'timeline';
        let deleteId = null;
        let currentLaporanData = null;

        const laporanData = {
            1: {
                id: 1,
                tanggal: '2025-06-29 08:30',
                ternak: 'Sapi Limosin #001',
                ternak_id: 1,
                status: 'sehat',
                prioritas: 'low',
                diagnosa: 'Pemeriksaan rutin bulanan',
                gejala: 'Kondisi baik, nafsu makan normal, aktivitas normal',
                suhu: 38.5,
                berat: 385,
                tindakan: 'Vaksinasi dan pemberian vitamin',
                dokter: 'Dr. Budi Santoso',
                biaya: 150000,
                follow_up: '2025-07-29',
                catatan: 'Ternak dalam kondisi sangat baik, pertahankan pola makan'
            },
            2: {
                id: 2,
                tanggal: '2025-06-27 14:15',
                ternak: 'Kambing Boer #003',
                ternak_id: 3,
                status: 'perawatan',
                prioritas: 'medium',
                diagnosa: 'Infeksi saluran pernapasan ringan',
                gejala: 'Batuk ringan, sedikit demam, nafsu makan menurun',
                suhu: 39.2,
                berat: 45,
                tindakan: 'Pemberian antibiotik Amoxicillin 500mg 2x sehari, istirahat total',
                dokter: 'Dr. Sari Widiarti',
                biaya: 250000,
                follow_up: '2025-07-02',
                catatan: 'Pantau suhu tubuh setiap hari, isolasi dari ternak lain'
            },
            3: {
                id: 3,
                tanggal: '2025-06-25 10:00',
                ternak: 'Sapi Brahman #002',
                ternak_id: 2,
                status: 'sakit',
                prioritas: 'high',
                diagnosa: 'Mastitis akut',
                gejala: 'Pembengkakan ambing, demam tinggi, produksi susu menurun drastis',
                suhu: 40.1,
                berat: 420,
                tindakan: 'Pengobatan intensif dengan antibiotik, isolasi, perawatan khusus ambing',
                dokter: 'Dr. Agus Permana',
                biaya: 500000,
                follow_up: '2025-06-30',
                catatan: 'Kondisi serius, perlu monitoring ketat 24 jam'
            }
        };

        function switchView(view) {
            currentView = view;
            const timelineView = document.getElementById('timelineView');
            const cardsView = document.getElementById('cardsView');
            const timelineBtn = document.getElementById('timelineViewBtn');
            const cardsBtn = document.getElementById('cardsViewBtn');

            if (view === 'timeline') {
                timelineView.classList.remove('hidden');
                cardsView.classList.add('hidden');
                timelineBtn.classList.add('active');
                cardsBtn.classList.remove('active');
            } else {
                timelineView.classList.add('hidden');
                cardsView.classList.remove('hidden');
                cardsBtn.classList.add('active');
                timelineBtn.classList.remove('active');
            }
        }

        function searchLaporan() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const items = document.querySelectorAll('.laporan-item, .laporan-card');

            items.forEach(item => {
                const ternak = item.getAttribute('data-ternak').toLowerCase();
                item.style.display = ternak.includes(searchTerm) ? 'block' : 'none';
            });
        }

        function filterByStatus() {
            const statusFilter = document.getElementById('statusFilter').value;
            const items = document.querySelectorAll('.laporan-item, .laporan-card');

            items.forEach(item => {
                const status = item.getAttribute('data-status');
                item.style.display = (!statusFilter || status === statusFilter) ? 'block' : 'none';
            });
        }

        function filterByTernak() {
            const ternakFilter = document.getElementById('ternakFilter').value;
            const items = document.querySelectorAll('.laporan-item, .laporan-card');

            items.forEach(item => {
                const ternakId = item.getAttribute('data-id');
                item.style.display = (!ternakFilter || ternakId === ternakFilter) ? 'block' : 'none';
            });
        }

        function filterByDate() {
            const dateFilter = document.getElementById('dateFilter').value;
            const items = document.querySelectorAll('.laporan-item, .laporan-card');

            if (!dateFilter) {
                items.forEach(item => item.style.display = 'block');
                return;
            }

            items.forEach(item => {
                const itemDate = laporanData[item.getAttribute('data-id')]?.tanggal?.split(' ')[0];
                item.style.display = (itemDate === dateFilter) ? 'block' : 'none';
            });
        }

        function exportData() {
            const exportBtn = event.target;
            const originalText = exportBtn.innerHTML;
            exportBtn.innerHTML =
                '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Exporting...';

            setTimeout(() => {
                exportBtn.innerHTML = originalText;
                alert('Data laporan kesehatan berhasil diexport!');
            }, 2000);
        }

        function openLaporanModal() {
            document.getElementById('laporanModalTitle').textContent = 'Tambah Laporan Kesehatan';
            document.getElementById('laporanForm').reset();
            document.getElementById('laporanId').value = '';
            document.getElementById('tanggalPemeriksaan').value = new Date().toISOString().slice(0, 16);
            document.getElementById('laporanModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLaporanModal() {
            document.getElementById('laporanModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('dokumentPreview').classList.add('hidden');
        }

        function openEditModal(id) {
            const data = laporanData[id];
            if (!data) return;

            document.getElementById('laporanModalTitle').textContent = 'Edit Laporan Kesehatan';
            document.getElementById('laporanId').value = data.id;
            document.getElementById('ternakSelect').value = data.ternak_id;
            document.getElementById('tanggalPemeriksaan').value = data.tanggal.replace(' ', 'T');
            document.getElementById('dokterPemeriksa').value = data.dokter;
            document.getElementById('diagnosa').value = data.diagnosa;
            document.getElementById('gejala').value = data.gejala;
            document.getElementById('suhuTubuh').value = data.suhu;
            document.getElementById('beratTernak').value = data.berat;
            document.getElementById('tindakan').value = data.tindakan;
            document.getElementById('prioritas').value = data.prioritas;
            document.getElementById('biayaPengobatan').value = data.biaya;
            document.getElementById('followUp').value = data.follow_up;
            document.getElementById('catatan').value = data.catatan;

            document.querySelector(`input[name="status_kesehatan"][value="${data.status}"]`).checked = true;

            updateTemperatureIndicator(data.suhu);
            document.getElementById('laporanModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openDetailModal(id) {
            const data = laporanData[id];
            if (!data) return;

            currentLaporanData = data;

            const detailContent = document.getElementById('detailContent');
            detailContent.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Informasi Umum</h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ternak:</span>
                                <span class="font-medium">${data.ternak}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal:</span>
                                <span class="font-medium">${data.tanggal}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dokter:</span>
                                <span class="font-medium">${data.dokter}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="status-badge status-${data.status}">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Diagnosa & Gejala</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="font-medium text-gray-900 mb-2">${data.diagnosa}</p>
                            <p class="text-gray-600">${data.gejala}</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Data Vital</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <span class="text-gray-600">Suhu Tubuh</span>
                                <p class="text-lg font-semibold text-${data.suhu > 39 ? 'red' : data.suhu > 38.8 ? 'yellow' : 'green'}-600">
                                    ${data.suhu}°C
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <span class="text-gray-600">Berat</span>
                                <p class="text-lg font-semibold text-gray-900">${data.berat} kg</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Tindakan & Pengobatan</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">${data.tindakan}</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Informasi Lainnya</h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Prioritas:</span>
                                <span class="category-tag priority-${data.prioritas}">${data.prioritas.charAt(0).toUpperCase() + data.prioritas.slice(1)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Biaya:</span>
                                <span class="font-medium">Rp ${data.biaya.toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Follow-up:</span>
                                <span class="font-medium">${data.follow_up}</span>
                            </div>
                        </div>
                    </div>

                    ${data.catatan ? `
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Catatan</h4>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-700">${data.catatan}</p>
                                </div>
                            </div>
                            ` : ''}
                </div>
            `;

            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentLaporanData = null;
        }

        function editFromDetail() {
            if (currentLaporanData) {
                closeDetailModal();
                openEditModal(currentLaporanData.id);
            }
        }

        function openDeleteModal(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            deleteId = null;
        }

        function confirmDelete() {
            if (deleteId) {
                const deleteBtn = document.getElementById('confirmDeleteBtn');
                const deleteText = document.getElementById('deleteText');
                const deleteLoading = document.getElementById('deleteLoading');

                deleteBtn.disabled = true;
                deleteText.textContent = 'Menghapus...';
                deleteLoading.classList.remove('hidden');

                setTimeout(() => {
                    closeDeleteModal();
                    alert('Laporan kesehatan berhasil dihapus!');
                    window.location.reload();
                }, 1500);
            }
        }

        function previewDokumen(input) {
            const preview = document.getElementById('dokumentPreview');
            preview.innerHTML = '';

            if (input.files && input.files.length > 0) {
                preview.classList.remove('hidden');

                Array.from(input.files).forEach((file, index) => {
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'flex flex-col items-center p-2 border border-gray-300 rounded-lg';

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            fileDiv.innerHTML = `
                                <img src="${e.target.result}" alt="Preview" class="attachment-preview mb-2">
                                <span class="text-xs text-gray-600 text-center">${file.name}</span>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        fileDiv.innerHTML = `
                            <div class="document-icon mb-2">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600 text-center">${file.name}</span>
                        `;
                    }

                    preview.appendChild(fileDiv);
                });
            } else {
                preview.classList.add('hidden');
            }
        }

        function updateTemperatureIndicator(temperature) {
            const indicator = document.getElementById('temperatureIndicator');
            if (indicator) {
                const percentage = ((temperature - 35) / (45 - 35)) * 100;
                indicator.style.left = Math.min(Math.max(percentage, 0), 100) + '%';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const laporanForm = document.getElementById('laporanForm');
            const suhuInput = document.getElementById('suhuTubuh');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            if (suhuInput) {
                suhuInput.addEventListener('input', function() {
                    updateTemperatureIndicator(parseFloat(this.value) || 38.5);
                });
            }

            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', confirmDelete);
            }

            if (laporanForm) {
                laporanForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const required = ['ternakSelect', 'tanggalPemeriksaan', 'dokterPemeriksa', 'diagnosa',
                        'gejala', 'suhuTubuh', 'tindakan', 'prioritas'
                    ];
                    let isValid = true;

                    required.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (!field.value.trim()) {
                            field.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });

                    const statusSelected = document.querySelector('input[name="status_kesehatan"]:checked');
                    if (!statusSelected) {
                        alert('Silakan pilih status kesehatan');
                        isValid = false;
                    }

                    if (!isValid) {
                        alert('Mohon lengkapi semua field yang wajib diisi');
                        return;
                    }

                    const submitBtn = document.getElementById('laporanSubmitBtn');
                    const submitText = document.getElementById('laporanSubmitText');
                    const submitLoading = document.getElementById('laporanSubmitLoading');

                    submitBtn.disabled = true;
                    submitText.textContent = 'Menyimpan...';
                    submitLoading.classList.remove('hidden');

                    setTimeout(() => {
                        closeLaporanModal();
                        alert('Laporan kesehatan berhasil disimpan!');
                        window.location.reload();
                    }, 2000);
                });
            }

            ['laporanModal', 'detailModal', 'deleteModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            const closeFunction = window[
                                `close${modalId.replace('Modal', '').charAt(0).toUpperCase() + modalId.replace('Modal', '').slice(1)}Modal`
                            ];
                            if (closeFunction) closeFunction();
                        }
                    });
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['laporanModal', 'detailModal', 'deleteModal'].forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains('hidden')) {
                        const closeFunction = window[
                            `close${modalId.replace('Modal', '').charAt(0).toUpperCase() + modalId.replace('Modal', '').slice(1)}Modal`
                        ];
                        if (closeFunction) closeFunction();
                    }
                });
            }
        });

        updateTemperatureIndicator(38.5);
    </script>
@endpush
