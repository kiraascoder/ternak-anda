@extends('layouts.app')

@section('title', 'Pilih Ternak Rawatan')
@section('page-title', 'Pilih Ternak Rawatan')
@section('page-description', 'Pilih ternak yang memerlukan perawatan dan treatment')

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

        .status-critical {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            animation: pulse 2s infinite;
        }

        .status-urgent {
            background-color: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        .status-moderate {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .status-stable {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .status-recovering {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .priority-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .priority-critical {
            background-color: #dc2626;
            animation: blink 1s infinite;
        }

        .priority-urgent {
            background-color: #ea580c;
        }

        .priority-moderate {
            background-color: #d97706;
        }

        .priority-low {
            background-color: #059669;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }

        .treatment-card {
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .treatment-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.2);
        }

        .treatment-card.selected {
            border-color: #10b981;
            background: linear-gradient(135deg, #ecfdf5 0%, #ffffff 100%);
            box-shadow: 0 8px 25px -5px rgba(16, 185, 129, 0.3);
        }

        .health-meter {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.75rem;
            color: white;
        }

        .health-critical {
            background: conic-gradient(#dc2626 0deg 90deg, #f3f4f6 90deg 360deg);
        }

        .health-urgent {
            background: conic-gradient(#ea580c 0deg 180deg, #f3f4f6 180deg 360deg);
        }

        .health-moderate {
            background: conic-gradient(#d97706 0deg 270deg, #f3f4f6 270deg 360deg);
        }

        .health-stable {
            background: conic-gradient(#059669 0deg 360deg);
        }

        .filter-button {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .filter-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateY(-1px);
        }

        .filter-button:not(.active) {
            background: white;
            color: #6b7280;
        }

        .filter-button:not(.active):hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .modal-backdrop {
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        .timeline-dot.critical {
            background-color: #dc2626;
        }

        .timeline-dot.urgent {
            background-color: #ea580c;
        }

        .timeline-dot.moderate {
            background-color: #d97706;
        }

        .timeline-dot.stable {
            background-color: #059669;
        }

        .search-highlight {
            background-color: #fef3c7;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
        }

        .selection-panel {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e5e7eb;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 40;
        }

        .selection-panel.visible {
            transform: translateY(0);
            opacity: 1;
        }

        .batch-action-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
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

        .distance-badge {
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .treatment-history {
            max-height: 300px;
            overflow-y: auto;
        }

        .treatment-history::-webkit-scrollbar {
            width: 4px;
        }

        .treatment-history::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .treatment-history::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.25rem;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .floating-action {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            z-index: 40;
        }

        .emergency-alert {
            background: linear-gradient(135deg, #fef2f2 0%, #fff5f5 100%);
            border: 2px solid #fecaca;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            animation: emergencyPulse 2s infinite;
        }

        @keyframes emergencyPulse {
            0%, 100% { 
                border-color: #fecaca;
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
            }
            50% { 
                border-color: #dc2626;
                box-shadow: 0 0 0 8px rgba(220, 38, 38, 0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex flex-col md:flex-row items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Ternak Memerlukan Rawatan</h1>
                    <p class="text-red-100 mb-4">Pilih ternak yang memerlukan perawatan medis segera</p>
                    <div class="flex items-center space-x-4">
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                            🚨 {{ $criticalCount ?? 3 }} Kritis
                        </span>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                            ⚠️ {{ $urgentCount ?? 5 }} Urgent
                        </span>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                            📍 Dalam radius 50km
                        </span>
                    </div>
                </div>

                <div class="quick-stats mt-4 md:mt-0">
                    <div class="stat-card bg-white bg-opacity-20 border-white border-opacity-30">
                        <div class="stat-value text-white">{{ $totalTreatment ?? 24 }}</div>
                        <div class="stat-label text-red-100">Total Rawatan</div>
                    </div>
                    <div class="stat-card bg-white bg-opacity-20 border-white border-opacity-30">
                        <div class="stat-value text-white">{{ $activeTreatment ?? 8 }}</div>
                        <div class="stat-label text-red-100">Aktif</div>
                    </div>
                    <div class="stat-card bg-white bg-opacity-20 border-white border-opacity-30">
                        <div class="stat-value text-white">{{ $completedToday ?? 5 }}</div>
                        <div class="stat-label text-red-100">Selesai Hari Ini</div>
                    </div>
                    <div class="stat-card bg-white bg-opacity-20 border-white border-opacity-30">
                        <div class="stat-value text-white">{{ $recoveryRate ?? 92 }}%</div>
                        <div class="stat-label text-red-100">Tingkat Kesembuhan</div>
                    </div>
                </div>
            </div>
        </div>

        @if(($criticalCount ?? 3) > 0)
            <div class="emergency-alert">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-red-800">PERINGATAN: {{ $criticalCount ?? 3 }} Ternak Dalam Kondisi Kritis!</h3>
                        <p class="text-red-700">Ternak berikut memerlukan penanganan medis segera dalam 24 jam</p>
                    </div>
                    <button onclick="showCriticalOnly()" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                        Lihat Kritis
                    </button>
                </div>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row items-start justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Cari ternak, peternak, atau kondisi..." onkeyup="searchAnimals()">
                </div>

                <div class="flex items-center space-x-2">
                    <button onclick="filterByStatus('all')" id="filter-all" class="filter-button active">
                        Semua ({{ $totalTreatment ?? 24 }})
                    </button>
                    <button onclick="filterByStatus('critical')" id="filter-critical" class="filter-button">
                        🚨 Kritis ({{ $criticalCount ?? 3 }})
                    </button>
                    <button onclick="filterByStatus('urgent')" id="filter-urgent" class="filter-button">
                        ⚠️ Urgent ({{ $urgentCount ?? 5 }})
                    </button>
                    <button onclick="filterByStatus('moderate')" id="filter-moderate" class="filter-button">
                        📋 Moderate ({{ $moderateCount ?? 8 }})
                    </button>
                    <button onclick="filterByStatus('recovering')" id="filter-recovering" class="filter-button">
                        💚 Pemulihan ({{ $recoveringCount ?? 8 }})
                    </button>
                </div>

                <select id="locationFilter" onchange="filterByLocation()"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua Lokasi</option>
                    <option value="bandung">Bandung</option>
                    <option value="subang">Subang</option>
                    <option value="garut">Garut</option>
                    <option value="bogor">Bogor</option>
                </select>

                <select id="sortBy" onchange="sortAnimals()"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="priority">Urutkan: Prioritas</option>
                    <option value="distance">Urutkan: Jarak</option>
                    <option value="time">Urutkan: Waktu</option>
                    <option value="name">Urutkan: Nama</option>
                </select>
            </div>

            <div class="flex items-center space-x-3">
                <div class="view-toggle flex items-center">
                    <button onclick="switchView('grid')" id="gridViewBtn"
                        class="px-3 py-2 text-sm font-medium rounded transition-colors active">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Grid
                    </button>
                    <button onclick="switchView('list')" id="listViewBtn"
                        class="px-3 py-2 text-sm font-medium rounded transition-colors text-gray-600">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        List
                    </button>
                </div>

                <button onclick="openMapView()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Peta
                </button>
            </div>
        </div>

        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $treatmentAnimals = [
                    [
                        'id' => 1,
                        'name' => 'Sapi Limosin #003',
                        'farmer' => 'Ahmad Suherman',
                        'farm' => 'Peternakan Maju Jaya',
                        'condition' => 'Mastitis akut dengan komplikasi',
                        'status' => 'critical',
                        'priority' => 'critical',
                        'location' => 'Bandung, Jawa Barat',
                        'distance' => '12 km',
                        'last_checkup' => '2 jam lalu',
                        'temperature' => 40.2,
                        'symptoms' => ['Demam tinggi', 'Pembengkakan ambing', 'Nafsu makan hilang', 'Produksi susu turun drastis'],
                        'treatment_history' => 'Antibiotik hari ke-2, belum ada perbaikan signifikan',
                        'urgency_level' => 95,
                        'estimated_treatment_time' => '2-3 hari',
                        'last_treatment' => '6 jam lalu',
                        'next_checkup' => 'Segera',
                        'vet_notes' => 'Memerlukan perawatan intensif, kemungkinan perlu rujuk ke spesialis'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Kambing Boer #007',
                        'farmer' => 'Siti Rahayu',
                        'farm' => 'Ternak Sejahtera',
                        'condition' => 'Pneumonia dengan sesak napas',
                        'status' => 'critical',
                        'priority' => 'critical',
                        'location' => 'Subang, Jawa Barat',
                        'distance' => '28 km',
                        'last_checkup' => '4 jam lalu',
                        'temperature' => 41.0,
                        'symptoms' => ['Sesak napas berat', 'Demam tinggi', 'Batuk berdarah', 'Lemas ekstrem'],
                        'treatment_history' => 'Oxygen therapy, antibiotik spektrum luas',
                        'urgency_level' => 98,
                        'estimated_treatment_time' => '1-2 hari',
                        'last_treatment' => '3 jam lalu',
                        'next_checkup' => 'Segera',
                        'vet_notes' => 'Kondisi sangat kritis, monitoring 24/7 diperlukan'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Sapi Brahman #005',
                        'farmer' => 'Budi Santoso',
                        'farm' => 'Peternakan Berkah',
                        'condition' => 'Luka terinfeksi di kaki',
                        'status' => 'urgent',
                        'priority' => 'urgent',
                        'location' => 'Garut, Jawa Barat',
                        'distance' => '45 km',
                        'last_checkup' => '1 hari lalu',
                        'temperature' => 39.5,
                        'symptoms' => ['Luka bernanah', 'Pincang', 'Demam ringan', 'Nafsu makan menurun'],
                        'treatment_history' => 'Pembersihan luka, antibiotik topikal dan sistemik',
                        'urgency_level' => 75,
                        'estimated_treatment_time' => '3-5 hari',
                        'last_treatment' => '12 jam lalu',
                        'next_checkup' => '6 jam lagi',
                        'vet_notes' => 'Respon treatment baik, lanjutkan protokol current'
                    ],
                    [
                        'id' => 4,
                        'name' => 'Domba Garut #012',
                        'farmer' => 'Rina Mulyani',
                        'farm' => 'Mandiri Farm',
                        'condition' => 'Gangguan pencernaan akut',
                        'status' => 'urgent',
                        'priority' => 'urgent',
                        'location' => 'Bogor, Jawa Barat',
                        'distance' => '32 km',
                        'last_checkup' => '6 jam lalu',
                        'temperature' => 39.8,
                        'symptoms' => ['Diare berdarah', 'Dehidrasi', 'Perut kembung', 'Gelisah'],
                        'treatment_history' => 'Fluid therapy, probiotik, diet khusus',
                        'urgency_level' => 80,
                        'estimated_treatment_time' => '2-4 hari',
                        'last_treatment' => '4 jam lalu',
                        'next_checkup' => '8 jam lagi',
                        'vet_notes' => 'Monitoring dehidrasi, adjust fluid therapy sesuai kondisi'
                    ],
                    [
                        'id' => 5,
                        'name' => 'Sapi Angus #008',
                        'farmer' => 'Joko Susilo',
                        'farm' => 'Tani Maju',
                        'condition' => 'Mastitis ringan',
                        'status' => 'moderate',
                        'priority' => 'moderate',
                        'location' => 'Bandung, Jawa Barat',
                        'distance' => '18 km',
                        'last_checkup' => '1 hari lalu',
                        'temperature' => 38.8,
                        'symptoms' => ['Pembengkakan ringan', 'Susu agak kental', 'Demam ringan'],
                        'treatment_history' => 'Antibiotik oral, anti-inflamasi',
                        'urgency_level' => 45,
                        'estimated_treatment_time' => '5-7 hari',
                        'last_treatment' => '18 jam lalu',
                        'next_checkup' => '2 hari lagi',
                        'vet_notes' => 'Progress baik, lanjutkan treatment current'
                    ],
                    [
                        'id' => 6,
                        'name' => 'Kambing Etawa #015',
                        'farmer' => 'Maria Gonzalez',
                        'farm' => 'Harapan Sejahtera',
                        'condition' => 'Pemulihan dari operasi',
                        'status' => 'recovering',
                        'priority' => 'low',
                        'location' => 'Subang, Jawa Barat',
                        'distance' => '25 km',
                        'last_checkup' => '2 hari lalu',
                        'temperature' => 38.2,
                        'symptoms' => ['Luka operasi sembuh', 'Nafsu makan baik', 'Aktivitas normal'],
                        'treatment_history' => 'Post-operative care, antibiotik profilaksis',
                        'urgency_level' => 20,
                        'estimated_treatment_time' => '7-10 hari',
                        'last_treatment' => '1 hari lalu',
                        'next_checkup' => '3 hari lagi',
                        'vet_notes' => 'Recovery excellent, monitoring rutin saja'
                    ]
                ];
            @endphp

            @foreach ($treatmentAnimals as $animal)
                <div class="treatment-card rounded-xl p-6 bg-white shadow-sm animal-card" 
                     data-status="{{ $animal['status'] }}"
                     data-priority="{{ $animal['priority'] }}"
                     data-location="{{ strtolower(explode(',', $animal['location'])[0]) }}"
                     data-name="{{ strtolower($animal['name'] . ' ' . $animal['farmer']) }}"
                     data-id="{{ $animal['id'] }}"
                     onclick="selectAnimal({{ $animal['id'] }}, this)">
                    
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="health-meter health-{{ $animal['status'] }}">
                                {{ $animal['urgency_level'] }}%
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $animal['name'] }}</h3>
                                <p class="text-sm text-gray-600">{{ $animal['farmer'] }} • {{ $animal['farm'] }}</p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <span class="status-badge status-{{ $animal['status'] }}">
                                <span class="priority-indicator priority-{{ $animal['priority'] }}"></span>
                                {{ ucfirst($animal['status']) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $animal['last_checkup'] }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">{{ $animal['condition'] }}</h4>
                            <div class="flex flex-wrap gap-1">
                                @foreach (array_slice($animal['symptoms'], 0, 3) as $symptom)
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $symptom }}</span>
                                @endforeach
                                @if (count($animal['symptoms']) > 3)
                                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">+{{ count($animal['symptoms']) - 3 }} lainnya</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Lokasi:</span>
                                <div class="font-medium text-gray-900">{{ $animal['location'] }}</div>
                                <span class="distance-badge">📍 {{ $animal['distance'] }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Suhu:</span>
                                <div class="font-medium {{ $animal['temperature'] > 39.5 ? 'text-red-600' : ($animal['temperature'] > 38.8 ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $animal['temperature'] }}°C
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600">Estimasi Treatment:</span>
                                <div class="font-medium text-gray-900">{{ $animal['estimated_treatment_time'] }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Next Checkup:</span>
                                <div class="font-medium {{ $animal['next_checkup'] === 'Segera' ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $animal['next_checkup'] }}
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-3">
                            <p class="text-sm text-gray-600 mb-2">
                                <span class="font-medium">Treatment History:</span> {{ $animal['treatment_history'] }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Vet Notes:</span> {{ $animal['vet_notes'] }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <div class="flex space-x-2">
                                <button onclick="event.stopPropagation(); viewDetails({{ $animal['id'] }})" 
                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm font-medium hover:bg-blue-200 transition-colors">
                                    📋 Detail
                                </button>
                                <button onclick="event.stopPropagation(); viewHistory({{ $animal['id'] }})" 
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm font-medium hover:bg-gray-200 transition-colors">
                                    📊 Riwayat
                                </button>
                            </div>
                            
                            @if ($animal['status'] === 'critical')
                                <button onclick="event.stopPropagation(); emergencyTreatment({{ $animal['id'] }})" 
                                    class="px-3 py-1 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700 transition-colors">
                                    🚨 Emergency
                                </button>
                            @else
                                <button onclick="event.stopPropagation(); startTreatment({{ $animal['id'] }})" 
                                    class="px-3 py-1 bg-green-600 text-white rounded text-sm font-medium hover:bg-green-700 transition-colors">
                                    🏥 Rawat
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="listView" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Ternak Rawatan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="mr-2">
                                Ternak
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checkup</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                        @foreach ($treatmentAnimals as $animal)
                            <tr class="hover:bg-gray-50 animal-row" 
                                data-status="{{ $animal['status'] }}"
                                data-priority="{{ $animal['priority'] }}"
                                data-location="{{ strtolower(explode(',', $animal['location'])[0]) }}"
                                data-name="{{ strtolower($animal['name'] . ' ' . $animal['farmer']) }}"
                                data-id="{{ $animal['id'] }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="animal-checkbox mr-3" value="{{ $animal['id'] }}" onchange="updateSelection()">
                                        <div class="health-meter health-{{ $animal['status'] }}" style="width: 40px; height: 40px; font-size: 0.625rem;">
                                            {{ $animal['urgency_level'] }}%
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $animal['name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $animal['farmer'] }} • {{ $animal['farm'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-medium">{{ $animal['condition'] }}</div>
                                    <div class="text-sm text-gray-500">Suhu: {{ $animal['temperature'] }}°C</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge status-{{ $animal['status'] }}">
                                        <span class="priority-indicator priority-{{ $animal['priority'] }}"></span>
                                        {{ ucfirst($animal['status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $animal['location'] }}</div>
                                    <span class="distance-badge">📍 {{ $animal['distance'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>Last: {{ $animal['last_checkup'] }}</div>
                                    <div class="{{ $animal['next_checkup'] === 'Segera' ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                        Next: {{ $animal['next_checkup'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewDetails({{ $animal['id'] }})" 
                                            class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200 transition-colors">
                                            Detail
                                        </button>
                                        @if ($animal['status'] === 'critical')
                                            <button onclick="emergencyTreatment({{ $animal['id'] }})" 
                                                class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 transition-colors">
                                                Emergency
                                            </button>
                                        @else
                                            <button onclick="startTreatment({{ $animal['id'] }})" 
                                                class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 transition-colors">
                                                Rawat
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="floating-action">
        <button onclick="openBatchTreatment()" class="px-4 py-3 bg-primary text-white rounded-full shadow-lg hover:bg-secondary transition-all transform hover:scale-105">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Batch Treatment
        </button>
    </div>

    <div id="selectionPanel" class="selection-panel">
        <div class="p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="font-medium text-gray-900">
                    <span id="selectedCount">0</span> ternak dipilih
                </span>
                <button onclick="clearSelection()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex space-x-2">
                <button onclick="batchTreatment()" class="batch-action-btn bg-green-600 text-white hover:bg-green-700">
                    🏥 Rawat Semua
                </button>
                <button onclick="batchSchedule()" class="batch-action-btn bg-blue-600 text-white hover:bg-blue-700">
                    📅 Jadwalkan
                </button>
                <button onclick="batchReport()" class="batch-action-btn bg-purple-600 text-white hover:bg-purple-700">
                    📊 Laporan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Detail Ternak -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Detail Ternak Rawatan</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div id="detailContent" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Content will be populated by JavaScript -->
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                    Tutup
                </button>
                <button onclick="startTreatmentFromDetail()" id="treatmentFromDetailBtn"
                    class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    🏥 Mulai Treatment
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Riwayat Treatment -->
    <div id="historyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Riwayat Treatment</h3>
                <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div id="historyContent" class="treatment-history">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedAnimals = new Set();
        let currentView = 'grid';
        let currentDetailId = null;

        const animalData = {
            1: { name: 'Sapi Limosin #003', farmer: 'Ahmad Suherman', condition: 'Mastitis akut dengan komplikasi', status: 'critical' },
            2: { name: 'Kambing Boer #007', farmer: 'Siti Rahayu', condition: 'Pneumonia dengan sesak napas', status: 'critical' },
            3: { name: 'Sapi Brahman #005', farmer: 'Budi Santoso', condition: 'Luka terinfeksi di kaki', status: 'urgent' },
            4: { name: 'Domba Garut #012', farmer: 'Rina Mulyani', condition: 'Gangguan pencernaan akut', status: 'urgent' },
            5: { name: 'Sapi Angus #008', farmer: 'Joko Susilo', condition: 'Mastitis ringan', status: 'moderate' },
            6: { name: 'Kambing Etawa #015', farmer: 'Maria Gonzalez', condition: 'Pemulihan dari operasi', status: 'recovering' }
        };

        function switchView(view) {
            currentView = view;
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');

            if (view === 'grid') {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
            } else {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
            }
        }

        function filterByStatus(status) {
            document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`filter-${status}`).classList.add('active');

            const cards = document.querySelectorAll('.animal-card');
            const rows = document.querySelectorAll('.animal-row');

            [...cards, ...rows].forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                item.style.display = (status === 'all' || itemStatus === status) ? '' : 'none';
            });
        }

        function filterByLocation() {
            const locationFilter = document.getElementById('locationFilter').value;
            const cards = document.querySelectorAll('.animal-card');
            const rows = document.querySelectorAll('.animal-row');

            [...cards, ...rows].forEach(item => {
                const location = item.getAttribute('data-location');
                item.style.display = (!locationFilter || location === locationFilter) ? '' : 'none';
            });
        }

        function searchAnimals() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.animal-card');
            const rows = document.querySelectorAll('.animal-row');

            [...cards, ...rows].forEach(item => {
                const name = item.getAttribute('data-name');
                const shouldShow = name.includes(searchTerm);
                item.style.display = shouldShow ? '' : 'none';

                if (shouldShow && searchTerm) {
                    const nameElements = item.querySelectorAll('h3, .text-sm');
                    nameElements.forEach(el => {
                        if (el.textContent.toLowerCase().includes(searchTerm)) {
                            el.innerHTML = el.textContent.replace(
                                new RegExp(searchTerm, 'gi'),
                                match => `<span class="search-highlight">${match}</span>`
                            );
                        }
                    });
                }
            });
        }

        function sortAnimals() {
            const sortBy = document.getElementById('sortBy').value;
            alert(`Mengurutkan berdasarkan: ${sortBy}`);
        }

        function showCriticalOnly() {
            filterByStatus('critical');
        }

        function selectAnimal(id, element) {
            if (selectedAnimals.has(id)) {
                selectedAnimals.delete(id);
                element.classList.remove('selected');
            } else {
                selectedAnimals.add(id);
                element.classList.add('selected');
            }
            updateSelectionPanel();
        }

        function updateSelection() {
            const checkboxes = document.querySelectorAll('.animal-checkbox:checked');
            selectedAnimals.clear();
            checkboxes.forEach(cb => selectedAnimals.add(parseInt(cb.value)));
            updateSelectionPanel();
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll').checked;
            const checkboxes = document.querySelectorAll('.animal-checkbox');
            
            checkboxes.forEach(cb => {
                cb.checked = selectAll;
                const id = parseInt(cb.value);
                if (selectAll) {
                    selectedAnimals.add(id);
                } else {
                    selectedAnimals.delete(id);
                }
            });
            updateSelectionPanel();
        }

        function updateSelectionPanel() {
            const panel = document.getElementById('selectionPanel');
            const count = document.getElementById('selectedCount');
            
            count.textContent = selectedAnimals.size;
            
            if (selectedAnimals.size > 0) {
                panel.classList.add('visible');
            } else {
                panel.classList.remove('visible');
            }
        }

        function clearSelection() {
            selectedAnimals.clear();
            document.querySelectorAll('.animal-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.treatment-card').forEach(card => card.classList.remove('selected'));
            document.getElementById('selectAll').checked = false;
            updateSelectionPanel();
        }

        function viewDetails(id) {
            currentDetailId = id;
            const animal = animalData[id];
            
            const detailContent = document.getElementById('detailContent');
            detailContent.innerHTML = `
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Ternak</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600">Nama:</span>
                                <p class="font-medium text-gray-900">${animal.name}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Peternak:</span>
                                <p class="font-medium text-gray-900">${animal.farmer}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Kondisi:</span>
                                <p class="font-medium text-gray-900">${animal.condition}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Status:</span>
                                <span class="status-badge status-${animal.status}">${animal.status.charAt(0).toUpperCase() + animal.status.slice(1)}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Vital Signs</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-white rounded-lg">
                                <div class="text-2xl font-bold text-red-600">40.2°C</div>
                                <div class="text-sm text-gray-600">Suhu Tubuh</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">420kg</div>
                                <div class="text-sm text-gray-600">Berat Badan</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-lg">
                                <div class="text-2xl font-bold text-green-600">Normal</div>
                                <div class="text-sm text-gray-600">Respirasi</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Gejala & Diagnosis</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="font-medium text-gray-700">Gejala Utama:</span>
                                <p class="text-gray-900">Demam tinggi, pembengkakan ambing, nafsu makan hilang, produksi susu turun drastis</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Diagnosis:</span>
                                <p class="text-gray-900">Mastitis akut dengan kemungkinan komplikasi sepsis</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Rencana Treatment:</span>
                                <p class="text-gray-900">Antibiotik spektrum luas, anti-inflamasi, monitoring intensif</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h5 class="font-semibold text-yellow-800 mb-2">⚠️ Peringatan</h5>
                        <p class="text-yellow-700 text-sm">Kondisi kritis, memerlukan monitoring 24/7. Kemungkinan perlu rujuk ke spesialis jika tidak ada perbaikan dalam 24 jam.</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3">📍 Lokasi</h5>
                        <p class="text-gray-700 text-sm">Bandung, Jawa Barat</p>
                        <p class="text-gray-600 text-sm">Jarak: 12 km dari lokasi Anda</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3">⏰ Timeline</h5>
                        <div class="space-y-2 text-sm">
                            <div>Last checkup: 2 jam lalu</div>
                            <div>Last treatment: 6 jam lalu</div>
                            <div class="text-red-600 font-medium">Next checkup: Segera</div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3">📊 Treatment Progress</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Recovery Progress</span>
                                <span>25%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentDetailId = null;
        }

        function viewHistory(id) {
            const historyContent = document.getElementById('historyContent');
            historyContent.innerHTML = `
                <div class="space-y-6">
                    <div class="timeline-item">
                        <div class="timeline-dot critical"></div>
                        <div class="ml-4">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="font-semibold text-gray-900">Diagnosis: Mastitis Akut</h5>
                                <span class="text-sm text-gray-500">2 jam lalu</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Dr. Budi Santoso melakukan pemeriksaan lengkap dan menetapkan diagnosis mastitis akut dengan komplikasi.</p>
                            <div class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded inline-block">Status: Critical</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot urgent"></div>
                        <div class="ml-4">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="font-semibold text-gray-900">Treatment Antibiotik</h5>
                                <span class="text-sm text-gray-500">6 jam lalu</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Pemberian Ampicillin 10mg/kg BB intramuskular. Respon awal belum signifikan.</p>
                            <div class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded inline-block">Treatment Started</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot moderate"></div>
                        <div class="ml-4">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="font-semibold text-gray-900">Pemeriksaan Awal</h5>
                                <span class="text-sm text-gray-500">1 hari lalu</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Pemeriksaan rutin mengidentifikasi gejala awal mastitis. Suhu 39.2°C, pembengkakan ringan.</p>
                            <div class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded inline-block">Initial Checkup</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot stable"></div>
                        <div class="ml-4">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="font-semibold text-gray-900">Konsultasi Peternak</h5>
                                <span class="text-sm text-gray-500">2 hari lalu</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Peternak melaporkan penurunan produksi susu dan perubahan konsistensi susu.</p>
                            <div class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded inline-block">Consultation</div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('historyModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function emergencyTreatment(id) {
            if (confirm('Mulai emergency treatment untuk ternak ini? Tim emergency akan segera diberitahu.')) {
                alert(`Emergency treatment dimulai untuk ternak ID: ${id}. Tim emergency telah diberitahu dan sedang dalam perjalanan.`);
            }
        }

        function startTreatment(id) {
            if (confirm('Mulai treatment untuk ternak ini?')) {
                alert(`Treatment dimulai untuk ternak ID: ${id}. Silakan lanjutkan dengan protokol treatment.`);
            }
        }

        function startTreatmentFromDetail() {
            if (currentDetailId) {
                startTreatment(currentDetailId);
                closeDetailModal();
            }
        }

        function openMapView() {
            alert('Map view akan segera tersedia untuk menampilkan lokasi ternak dalam peta');
        }

        function openBatchTreatment() {
            alert('Fitur batch treatment akan segera tersedia');
        }

        function batchTreatment() {
            if (selectedAnimals.size === 0) {
                alert('Pilih ternak terlebih dahulu');
                return;
            }
            
            if (confirm(`Mulai treatment untuk ${selectedAnimals.size} ternak yang dipilih?`)) {
                alert(`Batch treatment dimulai untuk ${selectedAnimals.size} ternak`);
                clearSelection();
            }
        }

        function batchSchedule() {
            if (selectedAnimals.size === 0) {
                alert('Pilih ternak terlebih dahulu');
                return;
            }
            
            alert(`Jadwalkan treatment untuk ${selectedAnimals.size} ternak yang dipilih`);
        }

        function batchReport() {
            if (selectedAnimals.size === 0) {
                alert('Pilih ternak terlebih dahulu');
                return;
            }
            
            alert(`Generate laporan untuk ${selectedAnimals.size} ternak yang dipilih`);
        }

        document.addEventListener('DOMContentLoaded', function() {
            ['detailModal', 'historyModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            const closeFunction = window[`close${modalId.replace('Modal', '').charAt(0).toUpperCase() + modalId.replace('Modal', '').slice(1)}Modal`];
                            if (closeFunction) closeFunction();
                        }
                    });
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['detailModal', 'historyModal'].forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains('hidden')) {
                        const closeFunction = window[`close${modalId.replace('Modal', '').charAt(0).toUpperCase() + modalId.replace('Modal', '').slice(1)}Modal`];
                        if (closeFunction) closeFunction();
                    }
                });
            }
        });

        setInterval(() => {
            const criticalCards = document.querySelectorAll('[data-status="critical"]');
            criticalCards.forEach(card => {
                const urgencyLevel = card.querySelector('.health-meter');
                if (urgencyLevel) {
                    const currentLevel = parseInt(urgencyLevel.textContent);
                    if (Math.random() > 0.7) {
                        urgencyLevel.textContent = Math.min(100, currentLevel + 1) + '%';
                    }
                }
            });
        }, 10000);
    </script>
@endpush