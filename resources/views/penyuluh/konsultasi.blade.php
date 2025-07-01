@extends('layouts.app')

@section('title', 'Dashboard Konsultasi')
@section('page-title', 'Dashboard Konsultasi')
@section('page-description', 'Kelola konsultasi kesehatan ternak sebagai penyuluh')

@push('styles')
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .priority-critical {
            border-left: 4px solid #dc2626;
            background: linear-gradient(90deg, #fef2f2 0%, #ffffff 100%);
        }

        .priority-high {
            border-left: 4px solid #f59e0b;
            background: linear-gradient(90deg, #fffbeb 0%, #ffffff 100%);
        }

        .priority-medium {
            border-left: 4px solid #3b82f6;
            background: linear-gradient(90deg, #eff6ff 0%, #ffffff 100%);
        }

        .priority-low {
            border-left: 4px solid #10b981;
            background: linear-gradient(90deg, #ecfdf5 0%, #ffffff 100%);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .status-new {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-waiting {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-completed {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .chat-message {
            max-width: 80%;
            word-wrap: break-word;
            margin-bottom: 1rem;
        }

        .chat-message.sent {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: auto;
            border-radius: 18px 18px 4px 18px;
        }

        .chat-message.received {
            background: #f3f4f6;
            color: #374151;
            margin-right: auto;
            border-radius: 18px 18px 18px 4px;
        }

        .chat-container {
            height: 500px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .chat-container::-webkit-scrollbar {
            width: 4px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .chat-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
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

        .notification-dot {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: white;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .expert-status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-online {
            background: #dcfce7;
            color: #166534;
        }

        .status-busy {
            background: #fef3c7;
            color: #92400e;
        }

        .status-offline {
            background: #f3f4f6;
            color: #6b7280;
        }

        .template-item {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .template-item:hover {
            background: #f8fafc;
            border-color: #3b82f6;
        }

        .diagnosis-tool {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
        }

        .symptom-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.125rem;
        }

        .knowledge-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .knowledge-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }

        .time-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .urgent-blink {
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0.7;
            }
        }

        .rating-star {
            transition: all 0.2s ease;
        }

        .rating-star.filled {
            color: #fbbf24;
        }

        .rating-star.empty {
            color: #d1d5db;
        }

        .consultation-queue {
            max-height: 600px;
            overflow-y: auto;
        }

        .queue-item {
            transition: all 0.3s ease;
        }

        .queue-item:hover {
            background: #f8fafc;
        }

        .quick-action-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-accept {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .btn-accept:hover {
            background: #bbf7d0;
        }

        .btn-defer {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .btn-defer:hover {
            background: #fde68a;
        }

        .btn-reject {
            background: #fecaca;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .btn-reject:hover {
            background: #fca5a5;
        }

        .stats-ring {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#10b981 0deg 216deg,
                    #f59e0b 216deg 288deg,
                    #ef4444 288deg 360deg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-ring::before {
            content: '';
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            position: absolute;
        }

        .stats-percentage {
            position: relative;
            z-index: 10;
            font-size: 1.5rem;
            font-weight: bold;
            color: #374151;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Dr. {{ auth()->user()->name ?? 'Budi Santoso' }}</h1>
                    <p class="text-green-100 mb-3">Dokter Hewan Spesialis • ID: {{ auth()->user()->id ?? 'VET001' }}</p>
                    <div class="flex items-center space-x-4">
                        <span class="expert-status status-online">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Online
                        </span>
                        <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">
                            Rating: 4.8/5 ⭐
                        </span>
                        <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">
                            1,250+ Konsultasi
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center mt-4 md:mt-0">
                    <div>
                        <div class="text-2xl font-bold">8</div>
                        <div class="text-sm text-green-100">Antrian</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">3</div>
                        <div class="text-sm text-green-100">Aktif</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">15</div>
                        <div class="text-sm text-green-100">Hari Ini</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">98%</div>
                        <div class="text-sm text-green-100">Kepuasan</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <h3 class="text-lg font-semibold text-gray-900">Antrian Konsultasi</h3>
                            <div class="relative">
                                <span class="notification-dot">8</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="priorityFilter" onchange="filterByPriority()"
                                class="text-sm px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Semua Prioritas</option>
                                <option value="critical">Kritis</option>
                                <option value="high">Tinggi</option>
                                <option value="medium">Sedang</option>
                                <option value="low">Rendah</option>
                            </select>
                            <button onclick="refreshQueue()" class="p-2 text-gray-500 hover:text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="consultation-queue">
                        @php
                            $consultations = [
                                [
                                    'id' => 1,
                                    'farmer' => 'Ahmad Suherman',
                                    'farm' => 'Peternakan Maju Jaya',
                                    'animal' => 'Sapi Limosin #003',
                                    'issue' => 'Demam tinggi dan nafsu makan menurun drastis',
                                    'priority' => 'critical',
                                    'time_waiting' => '15 menit',
                                    'symptoms' => ['Demam 40.2°C', 'Nafsu makan hilang', 'Lesu', 'Tremor'],
                                    'images' => 2,
                                    'location' => 'Bandung, Jawa Barat',
                                ],
                                [
                                    'id' => 2,
                                    'farmer' => 'Siti Rahayu',
                                    'farm' => 'Ternak Sejahtera',
                                    'animal' => 'Kambing Boer #007',
                                    'issue' => 'Pembengkakan di bagian leher, terlihat kesakitan',
                                    'priority' => 'high',
                                    'time_waiting' => '23 menit',
                                    'symptoms' => ['Pembengkakan leher', 'Kesulitan menelan', 'Gelisah'],
                                    'images' => 3,
                                    'location' => 'Subang, Jawa Barat',
                                ],
                                [
                                    'id' => 3,
                                    'farmer' => 'Budi Santoso',
                                    'farm' => 'Peternakan Berkah',
                                    'animal' => 'Domba Garut #012',
                                    'issue' => 'Konsultasi rutin program vaksinasi',
                                    'priority' => 'medium',
                                    'time_waiting' => '35 menit',
                                    'symptoms' => ['Pemeriksaan rutin', 'Vaksinasi', 'Vitamin'],
                                    'images' => 0,
                                    'location' => 'Garut, Jawa Barat',
                                ],
                                [
                                    'id' => 4,
                                    'farmer' => 'Rina Mulyani',
                                    'farm' => 'Mandiri Farm',
                                    'animal' => 'Sapi Brahman #005',
                                    'issue' => 'Pertanyaan tentang pakan optimal untuk pertumbuhan',
                                    'priority' => 'low',
                                    'time_waiting' => '1 jam 12 menit',
                                    'symptoms' => ['Konsultasi nutrisi', 'Pertumbuhan optimal'],
                                    'images' => 1,
                                    'location' => 'Bogor, Jawa Barat',
                                ],
                            ];
                        @endphp

                        <div class="p-6 space-y-4">
                            @foreach ($consultations as $consultation)
                                <div class="queue-item p-4 border border-gray-200 rounded-lg priority-{{ $consultation['priority'] }} consultation-item"
                                    data-priority="{{ $consultation['priority'] }}" data-id="{{ $consultation['id'] }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="font-semibold text-gray-900">{{ $consultation['farmer'] }}</h4>
                                                <span class="text-sm text-gray-600">• {{ $consultation['farm'] }}</span>
                                                <span
                                                    class="time-badge {{ $consultation['priority'] === 'critical' ? 'urgent-blink' : '' }}">
                                                    {{ $consultation['time_waiting'] }}
                                                </span>
                                                @if ($consultation['priority'] === 'critical')
                                                    <span
                                                        class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">
                                                        URGENT
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                                    <span class="text-lg mr-2">🐄</span>
                                                    <span class="font-medium">{{ $consultation['animal'] }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span>📍 {{ $consultation['location'] }}</span>
                                                    @if ($consultation['images'] > 0)
                                                        <span class="mx-2">•</span>
                                                        <span>📷 {{ $consultation['images'] }} foto</span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-700 font-medium">{{ $consultation['issue'] }}</p>
                                            </div>

                                            <div class="flex flex-wrap gap-1 mb-3">
                                                @foreach ($consultation['symptoms'] as $symptom)
                                                    <span class="symptom-tag">{{ $symptom }}</span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="flex flex-col space-y-2 ml-4">
                                            <button onclick="acceptConsultation({{ $consultation['id'] }})"
                                                class="quick-action-btn btn-accept">
                                                ✓ Terima
                                            </button>
                                            <button onclick="deferConsultation({{ $consultation['id'] }})"
                                                class="quick-action-btn btn-defer">
                                                ⏰ Tunda
                                            </button>
                                            <button onclick="rejectConsultation({{ $consultation['id'] }})"
                                                class="quick-action-btn btn-reject">
                                                ✗ Tolak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Konsultasi Aktif</h3>
                    </div>

                    <div class="p-6">
                        @php
                            $activeConsultations = [
                                [
                                    'id' => 5,
                                    'farmer' => 'Joko Susilo',
                                    'animal' => 'Sapi Angus #001',
                                    'issue' => 'Mastitis akut',
                                    'duration' => '25 menit',
                                    'last_message' => 'Baik dok, saya akan berikan obat sesuai resep',
                                    'unread' => 0,
                                ],
                                [
                                    'id' => 6,
                                    'farmer' => 'Maria Gonzalez',
                                    'animal' => 'Kambing Etawa #009',
                                    'issue' => 'Gangguan pencernaan',
                                    'duration' => '12 menit',
                                    'last_message' => 'Dokter, kondisinya sudah mulai membaik',
                                    'unread' => 2,
                                ],
                            ];
                        @endphp

                        <div class="space-y-4">
                            @foreach ($activeConsultations as $active)
                                <div
                                    class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $active['farmer'] }} •
                                                {{ $active['animal'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ $active['issue'] }} •
                                                {{ $active['duration'] }}</p>
                                            <p class="text-sm text-gray-700 italic">"{{ $active['last_message'] }}"</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if ($active['unread'] > 0)
                                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                {{ $active['unread'] }}
                                            </span>
                                        @endif
                                        <button onclick="openChatModal({{ $active['id'] }})"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                            💬 Chat
                                        </button>
                                        <button onclick="endConsultation({{ $active['id'] }})"
                                            class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                                            🏁 Selesai
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Hari Ini</h3>
                    <div class="stats-ring mx-auto mb-4">
                        <div class="stats-percentage">95%</div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Selesai</span>
                            <span class="font-semibold text-green-600">12 (60%)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Aktif</span>
                            <span class="font-semibold text-yellow-600">3 (15%)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Antrian</span>
                            <span class="font-semibold text-blue-600">8 (40%)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rating Rata-rata</span>
                            <span class="font-semibold text-gray-900">4.8/5</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tools Diagnosis</h3>
                    <div class="space-y-3">
                        <button onclick="openDiagnosisModal()"
                            class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors border">
                            <div class="p-2 bg-blue-100 rounded-full mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Diagnosis Helper</p>
                                <p class="text-xs text-gray-500">AI-powered diagnosis tool</p>
                            </div>
                        </button>

                        <button onclick="openKnowledgeModal()"
                            class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors border">
                            <div class="p-2 bg-green-100 rounded-full mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Knowledge Base</p>
                                <p class="text-xs text-gray-500">Referensi penyakit & treatment</p>
                            </div>
                        </button>

                        <button onclick="openTemplateModal()"
                            class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors border">
                            <div class="p-2 bg-purple-100 rounded-full mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Template Respon</p>
                                <p class="text-xs text-gray-500">Respon cepat tersimpan</p>
                            </div>
                        </button>

                        <button onclick="openCalendarModal()"
                            class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors border">
                            <div class="p-2 bg-yellow-100 rounded-full mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Jadwal Konsultasi</p>
                                <p class="text-xs text-gray-500">Atur ketersediaan waktu</p>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Reminder Follow-up</h3>
                    <div class="space-y-3">
                        <div
                            class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <div>
                                <p class="font-medium text-gray-900">Ahmad S. - Sapi #003</p>
                                <p class="text-sm text-gray-600">Kontrol demam (3 hari)</p>
                            </div>
                            <button class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                Hubungi
                            </button>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div>
                                <p class="font-medium text-gray-900">Siti R. - Kambing #007</p>
                                <p class="text-sm text-gray-600">Cek pembengkakan (1 minggu)</p>
                            </div>
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Hubungi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chat -->
    <div id="chatModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-5 mx-auto p-5 border w-full max-w-5xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-4">
                <div class="flex items-center space-x-4">
                    <img src="https://ui-avatars.io/api/?name=Joko+Susilo&background=667eea&color=ffffff" alt="Farmer"
                        class="w-12 h-12 rounded-full">
                    <div>
                        <h3 id="chatFarmerName" class="text-lg font-semibold text-gray-900">Joko Susilo</h3>
                        <p id="chatAnimalInfo" class="text-sm text-gray-600">Sapi Angus #001 • Mastitis akut</p>
                        <p class="text-xs text-gray-500">📍 Bandung, Jawa Barat • ⏱️ 25 menit berlangsung</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="openDiagnosisPanel()"
                        class="px-3 py-2 bg-blue-100 text-blue-700 rounded text-sm font-medium hover:bg-blue-200 transition-colors">
                        🔍 Diagnosis
                    </button>
                    <button onclick="openPrescriptionPanel()"
                        class="px-3 py-2 bg-green-100 text-green-700 rounded text-sm font-medium hover:bg-green-200 transition-colors">
                        💊 Resep
                    </button>
                    <button onclick="closeChatModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-3">
                    <div class="chat-container bg-gray-50 rounded-lg p-4 mb-4" id="chatContainer">
                        <div class="space-y-4" id="chatMessages">
                            <div class="flex justify-start">
                                <div class="chat-message received px-4 py-2 text-sm">
                                    <p>Dok, sapi saya mengalami pembengkakan di bagian ambing. Sudah 2 hari seperti ini dan
                                        terlihat kesakitan.</p>
                                    <span class="text-xs opacity-75 mt-1 block">14:30</span>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <div class="chat-message sent px-4 py-2 text-sm">
                                    <p>Selamat siang Pak Joko. Dari gejala yang Bapak sampaikan, kemungkinan besar ini
                                        adalah kasus mastitis. Bisakah Bapak kirim foto kondisi ambing sapi tersebut?</p>
                                    <span class="text-xs opacity-75 mt-1 block">14:32</span>
                                </div>
                            </div>

                            <div class="flex justify-start">
                                <div class="chat-message received px-4 py-2 text-sm">
                                    <img src="https://via.placeholder.com/200x150?text=Foto+Ambing+Sapi"
                                        alt="Foto kondisi" class="rounded-lg mb-2 max-w-xs">
                                    <p>Ini foto kondisinya dok. Pembengkakan di bagian kiri dan terasa panas.</p>
                                    <span class="text-xs opacity-75 mt-1 block">14:35</span>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <div class="chat-message sent px-4 py-2 text-sm">
                                    <p>Terima kasih atas fotonya. Ini memang kasus mastitis akut. Saya akan berikan
                                        treatment plan yang tepat untuk sapi Bapak.</p>
                                    <span class="text-xs opacity-75 mt-1 block">14:38</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="flex-1 relative">
                            <input type="text" id="chatInput" placeholder="Ketik respon Anda..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent pr-32">

                            <div class="absolute right-2 top-2 flex items-center space-x-1">
                                <label for="chatAttachment" class="p-1 text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                </label>
                                <input type="file" id="chatAttachment" class="hidden" accept="image/*">

                                <button onclick="openTemplateSelector()" class="p-1 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                        </path>
                                    </svg>
                                </button>

                                <button onclick="openEmojiPicker()" class="p-1 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button onclick="sendMessage()" id="sendBtn"
                            class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Info Pasien</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Peternak:</span>
                                <span class="font-medium">Joko Susilo</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ternak:</span>
                                <span class="font-medium">Sapi Angus #001</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Umur:</span>
                                <span class="font-medium">3 tahun</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Berat:</span>
                                <span class="font-medium">420 kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Riwayat:</span>
                                <span class="font-medium text-green-600">Baik</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Quick Actions</h4>
                        <div class="space-y-2">
                            <button onclick="addPrescription()"
                                class="w-full px-3 py-2 bg-green-100 text-green-700 rounded text-sm font-medium hover:bg-green-200 transition-colors">
                                💊 Tambah Resep
                            </button>
                            <button onclick="scheduleFollowUp()"
                                class="w-full px-3 py-2 bg-blue-100 text-blue-700 rounded text-sm font-medium hover:bg-blue-200 transition-colors">
                                📅 Jadwalkan Follow-up
                            </button>
                            <button onclick="referToSpecialist()"
                                class="w-full px-3 py-2 bg-purple-100 text-purple-700 rounded text-sm font-medium hover:bg-purple-200 transition-colors">
                                👨‍⚕️ Rujuk ke Spesialis
                            </button>
                            <button onclick="endConsultationFromChat()"
                                class="w-full px-3 py-2 bg-gray-100 text-gray-700 rounded text-sm font-medium hover:bg-gray-200 transition-colors">
                                🏁 Akhiri Konsultasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Diagnosis Helper -->
    <div id="diagnosisModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">AI Diagnosis Helper</h3>
                <button onclick="closeDiagnosisModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="diagnosis-tool p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Input Gejala</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Hewan</label>
                            <select
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="sapi">Sapi</option>
                                <option value="kambing">Kambing</option>
                                <option value="domba">Domba</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gejala Utama</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-sm">Demam</span>
                                </label>
                                <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-sm">Nafsu makan menurun</span>
                                </label>
                                <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-sm">Pembengkakan</span>
                                </label>
                                <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-sm">Kesulitan bernapas</span>
                                </label>
                                <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-sm">Diare</span>
                                </label>
                                <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-sm">Lemas</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Suhu Tubuh (°C)</label>
                            <input type="number" step="0.1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="38.5">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Gejala</label>
                            <select
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="1-3">1-3 hari</option>
                                <option value="4-7">4-7 hari</option>
                                <option value="1-2minggu">1-2 minggu</option>
                                <option value="lebih">Lebih dari 2 minggu</option>
                            </select>
                        </div>

                        <button onclick="runDiagnosis()"
                            class="w-full px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-secondary transition-colors">
                            🔍 Analisis Diagnosis
                        </button>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Hasil Diagnosis</h4>
                    <div id="diagnosisResults" class="space-y-4">
                        <div class="text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                            <p>Masukkan gejala untuk mendapatkan diagnosis AI</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Knowledge Base -->
    <div id="knowledgeModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Knowledge Base</h3>
                <button onclick="closeKnowledgeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-1">
                    <div class="space-y-2">
                        <h4 class="font-semibold text-gray-900 mb-3">Kategori</h4>
                        <button onclick="filterKnowledge('all')"
                            class="w-full text-left px-3 py-2 rounded bg-primary text-white">
                            📚 Semua
                        </button>
                        <button onclick="filterKnowledge('diseases')"
                            class="w-full text-left px-3 py-2 rounded hover:bg-gray-100">
                            🦠 Penyakit
                        </button>
                        <button onclick="filterKnowledge('treatments')"
                            class="w-full text-left px-3 py-2 rounded hover:bg-gray-100">
                            💊 Pengobatan
                        </button>
                        <button onclick="filterKnowledge('nutrition')"
                            class="w-full text-left px-3 py-2 rounded hover:bg-gray-100">
                            🥗 Nutrisi
                        </button>
                        <button onclick="filterKnowledge('emergency')"
                            class="w-full text-left px-3 py-2 rounded hover:bg-gray-100">
                            🚨 Darurat
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <div class="mb-4">
                        <input type="text" placeholder="Cari dalam knowledge base..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                        <div class="knowledge-card p-4 cursor-pointer" onclick="openKnowledgeDetail('mastitis')">
                            <h5 class="font-semibold text-gray-900 mb-2">Mastitis pada Sapi</h5>
                            <p class="text-sm text-gray-600 mb-2">Peradangan kelenjar susu yang umum terjadi pada sapi
                                perah...</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Penyakit</span>
                                <span class="text-xs text-gray-500">Updated: 2 hari lalu</span>
                            </div>
                        </div>

                        <div class="knowledge-card p-4 cursor-pointer" onclick="openKnowledgeDetail('pneumonia')">
                            <h5 class="font-semibold text-gray-900 mb-2">Pneumonia Ternak</h5>
                            <p class="text-sm text-gray-600 mb-2">Infeksi paru-paru yang dapat berakibat fatal jika tidak
                                ditangani...</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Penyakit</span>
                                <span class="text-xs text-gray-500">Updated: 1 minggu lalu</span>
                            </div>
                        </div>

                        <div class="knowledge-card p-4 cursor-pointer" onclick="openKnowledgeDetail('antibiotics')">
                            <h5 class="font-semibold text-gray-900 mb-2">Penggunaan Antibiotik</h5>
                            <p class="text-sm text-gray-600 mb-2">Panduan dosis dan cara pemberian antibiotik yang tepat...
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Pengobatan</span>
                                <span class="text-xs text-gray-500">Updated: 3 hari lalu</span>
                            </div>
                        </div>

                        <div class="knowledge-card p-4 cursor-pointer" onclick="openKnowledgeDetail('nutrition')">
                            <h5 class="font-semibold text-gray-900 mb-2">Nutrisi Optimal Sapi</h5>
                            <p class="text-sm text-gray-600 mb-2">Panduan lengkap nutrisi untuk pertumbuhan maksimal...</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Nutrisi</span>
                                <span class="text-xs text-gray-500">Updated: 5 hari lalu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Template Respon -->
    <div id="templateModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Template Respon</h3>
                <button onclick="closeTemplateModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="space-y-3 max-h-80 overflow-y-auto">
                <div class="template-item" onclick="useTemplate('greeting')">
                    <h5 class="font-medium text-gray-900">Salam Pembuka</h5>
                    <p class="text-sm text-gray-600">Selamat [waktu], terima kasih telah menghubungi layanan konsultasi...
                    </p>
                </div>

                <div class="template-item" onclick="useTemplate('ask_symptoms')">
                    <h5 class="font-medium text-gray-900">Tanya Gejala Detail</h5>
                    <p class="text-sm text-gray-600">Untuk diagnosis yang tepat, bisakah Anda jelaskan gejala yang
                        diamati...</p>
                </div>

                <div class="template-item" onclick="useTemplate('request_photo')">
                    <h5 class="font-medium text-gray-900">Minta Foto</h5>
                    <p class="text-sm text-gray-600">Bisakah Anda kirim foto kondisi ternak untuk membantu diagnosis...</p>
                </div>

                <div class="template-item" onclick="useTemplate('prescription')">
                    <h5 class="font-medium text-gray-900">Resep Obat</h5>
                    <p class="text-sm text-gray-600">Berdasarkan diagnosa, berikut resep pengobatan yang perlu diberikan...
                    </p>
                </div>

                <div class="template-item" onclick="useTemplate('follow_up')">
                    <h5 class="font-medium text-gray-900">Follow-up</h5>
                    <p class="text-sm text-gray-600">Silakan laporkan perkembangan kondisi ternak dalam 3 hari...</p>
                </div>

                <div class="template-item" onclick="useTemplate('emergency')">
                    <h5 class="font-medium text-gray-900">Kondisi Darurat</h5>
                    <p class="text-sm text-gray-600">Kondisi ini memerlukan penanganan segera. Silakan bawa ke klinik...
                    </p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <button onclick="createCustomTemplate()"
                    class="w-full px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition-colors">
                    + Buat Template Baru
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentChatId = null;

        function filterByPriority() {
            const priorityFilter = document.getElementById('priorityFilter').value;
            const items = document.querySelectorAll('.consultation-item');

            items.forEach(item => {
                const priority = item.getAttribute('data-priority');
                item.style.display = (!priorityFilter || priority === priorityFilter) ? 'block' : 'none';
            });
        }

        function refreshQueue() {
            const btn = event.target;
            btn.classList.add('animate-spin');

            setTimeout(() => {
                btn.classList.remove('animate-spin');
                alert('Antrian konsultasi diperbarui!');
            }, 1000);
        }

        function acceptConsultation(id) {
            if (confirm('Terima konsultasi ini?')) {
                alert(`Konsultasi #${id} diterima. Mulai chat dengan peternak.`);
                openChatModal(id);
            }
        }

        function deferConsultation(id) {
            const reason = prompt('Alasan menunda konsultasi:');
            if (reason) {
                alert(`Konsultasi #${id} ditunda. Peternak akan diberitahu.`);
            }
        }

        function rejectConsultation(id) {
            const reason = prompt('Alasan menolak konsultasi:');
            if (reason) {
                if (confirm('Yakin ingin menolak konsultasi ini?')) {
                    alert(`Konsultasi #${id} ditolak. Peternak akan diberitahu.`);
                }
            }
        }

        function openChatModal(id) {
            currentChatId = id;
            document.getElementById('chatModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            scrollChatToBottom();
        }

        function closeChatModal() {
            document.getElementById('chatModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentChatId = null;
        }

        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();

            if (!message) return;

            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex justify-end';

            const now = new Date();
            const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

            messageDiv.innerHTML = `
                <div class="chat-message sent px-4 py-2 text-sm">
                    <p>${message}</p>
                    <span class="text-xs opacity-75 mt-1 block">${time}</span>
                </div>
            `;

            chatMessages.appendChild(messageDiv);
            input.value = '';
            scrollChatToBottom();

            setTimeout(() => {
                const replyDiv = document.createElement('div');
                replyDiv.className = 'flex justify-start';
                replyDiv.innerHTML = `
                    <div class="chat-message received px-4 py-2 text-sm">
                        <p>Terima kasih dok atas saranya. Saya akan lakukan sesuai instruksi.</p>
                        <span class="text-xs opacity-75 mt-1 block">${time}</span>
                    </div>
                `;
                chatMessages.appendChild(replyDiv);
                scrollChatToBottom();
            }, 1500);
        }

        function scrollChatToBottom() {
            const chatContainer = document.getElementById('chatContainer');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function endConsultation(id) {
            if (confirm('Akhiri konsultasi ini?')) {
                closeChatModal();
                alert(`Konsultasi #${id} telah selesai. Peternak akan diminta memberikan rating.`);
            }
        }

        function endConsultationFromChat() {
            if (currentChatId) {
                endConsultation(currentChatId);
            }
        }

        function openDiagnosisModal() {
            document.getElementById('diagnosisModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDiagnosisModal() {
            document.getElementById('diagnosisModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function runDiagnosis() {
            const resultsDiv = document.getElementById('diagnosisResults');
            resultsDiv.innerHTML = `
                <div class="space-y-4">
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h5 class="font-semibold text-red-800 mb-2">Diagnosis Utama: Mastitis Akut (85% confidence)</h5>
                        <p class="text-sm text-red-700">Peradangan kelenjar susu yang memerlukan pengobatan segera.</p>
                    </div>
                    
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h5 class="font-semibold text-yellow-800 mb-2">Diagnosis Alternatif: Trauma Ambing (15% confidence)</h5>
                        <p class="text-sm text-yellow-700">Kemungkinan cedera fisik pada area ambing.</p>
                    </div>
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h5 class="font-semibold text-blue-800 mb-2">Rekomendasi Treatment:</h5>
                        <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                            <li>Antibiotik: Ampicillin 10mg/kg BB, 2x sehari</li>
                            <li>Anti-inflamasi: Dexamethasone 0.1mg/kg BB</li>
                            <li>Kompres hangat 3x sehari</li>
                            <li>Isolasi dari ternak lain</li>
                        </ul>
                    </div>
                </div>
            `;
        }

        function openKnowledgeModal() {
            document.getElementById('knowledgeModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeKnowledgeModal() {
            document.getElementById('knowledgeModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function filterKnowledge(category) {
            alert(`Filter knowledge base: ${category}`);
        }

        function openKnowledgeDetail(topic) {
            alert(`Membuka detail: ${topic}`);
        }

        function openTemplateModal() {
            document.getElementById('templateModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeTemplateModal() {
            document.getElementById('templateModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function useTemplate(templateType) {
            const templates = {
                greeting: 'Selamat siang, terima kasih telah menghubungi layanan konsultasi kesehatan ternak. Saya siap membantu Anda.',
                ask_symptoms: 'Untuk memberikan diagnosis yang tepat, bisakah Anda jelaskan secara detail gejala yang diamati pada ternak Anda? Termasuk durasi gejala dan kondisi lingkungan kandang.',
                request_photo: 'Bisakah Anda kirim foto kondisi ternak yang sedang bermasalah? Foto akan sangat membantu proses diagnosis.',
                prescription: 'Berdasarkan diagnosa, berikut resep pengobatan yang perlu diberikan kepada ternak Anda:',
                follow_up: 'Silakan laporkan perkembangan kondisi ternak dalam 3 hari ke depan. Jika ada perburukan kondisi, segera hubungi kembali.',
                emergency: 'Kondisi ini memerlukan penanganan segera. Silakan bawa ternak ke klinik hewan terdekat atau hubungi dokter hewan di area Anda.'
            };

            const input = document.getElementById('chatInput');
            if (input) {
                input.value = templates[templateType] || '';
                closeTemplateModal();
                input.focus();
            }
        }

        function createCustomTemplate() {
            const text = prompt('Masukkan template respon baru:');
            if (text) {
                alert('Template berhasil disimpan!');
                closeTemplateModal();
            }
        }

        function openTemplateSelector() {
            openTemplateModal();
        }

        function openEmojiPicker() {
            alert('Emoji picker akan segera tersedia!');
        }

        function openDiagnosisPanel() {
            openDiagnosisModal();
        }

        function openPrescriptionPanel() {
            alert('Panel resep akan dibuka dalam versi selanjutnya');
        }

        function addPrescription() {
            alert('Fitur tambah resep akan segera tersedia');
        }

        function scheduleFollowUp() {
            alert('Fitur jadwal follow-up akan segera tersedia');
        }

        function referToSpecialist() {
            alert('Fitur rujuk ke spesialis akan segera tersedia');
        }

        function openCalendarModal() {
            alert('Kalender jadwal konsultasi akan segera tersedia');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const chatInput = document.getElementById('chatInput');
            if (chatInput) {
                chatInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        sendMessage();
                    }
                });
            }

            ['chatModal', 'diagnosisModal', 'knowledgeModal', 'templateModal'].forEach(modalId => {
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
                ['chatModal', 'diagnosisModal', 'knowledgeModal', 'templateModal'].forEach(modalId => {
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

        setInterval(() => {
            const notification = document.querySelector('.notification-dot');
            if (notification) {
                const currentCount = parseInt(notification.textContent);
                if (Math.random() > 0.8) {
                    notification.textContent = currentCount + 1;
                }
            }
        }, 30000);
    </script>
@endpush
