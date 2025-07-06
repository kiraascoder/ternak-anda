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

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-berlangsung {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-selesai {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .status-dibatalkan {
            background-color: #fecaca;
            color: #991b1b;
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

        .status-dropdown {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .status-dropdown:focus {
            outline: none;
            ring: 2px;
            ring-color: #3b82f6;
            border-color: #3b82f6;
        }

        .status-dropdown:disabled {
            background-color: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        .notification-toast {
            min-width: 300px;
            max-width: 500px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .ternak-photo {
            max-width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
        }

        .photo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 120px;
            background: #f9fafb;
            border-radius: 0.5rem;
            border: 2px dashed #d1d5db;
        }

        .berlangsung-info {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border: 2px solid #10b981;
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
        }

        .loading-spinner {
            border: 2px solid #f3f4f6;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        @media (max-width: 768px) {
            .notification-toast {
                left: 1rem;
                right: 1rem;
                min-width: auto;
            }

            .status-dropdown {
                font-size: 0.8rem;
                padding: 0.4rem 2rem 0.4rem 0.75rem;
            }

            .modal-content {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
                overflow-y: auto;
            }
        }
    </style>
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Dr. {{ auth()->user()->nama }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="expert-status status-online">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Online
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center mt-4 md:mt-0">
                    <div>
                        <div class="text-2xl font-bold">{{ $konsultasiPending }}</div>
                        <div class="text-sm text-green-100">Antrian</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">{{ $konsultasiBerlangsung }}</div>
                        <div class="text-sm text-green-100">Aktif</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">{{ $totalKonsultasiHariIni }}</div>
                        <div class="text-sm text-green-100">Hari Ini</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">{{ $konsultasiSelesai }}</div>
                        <div class="text-sm text-green-100">Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="">
            <div class="lg:col-span-2 space-y-6">
                {{-- Queue Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <h3 class="text-lg font-semibold text-gray-900">Antrian Konsultasi</h3>
                            @if ($konsultasiPending > 0)
                                <div class="relative">
                                    <span class="notification-dot">{{ $konsultasiPending }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="statusFilter" onchange="filterByStatus()"
                                class="text-sm px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="berlangsung">Berlangsung</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
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
                        <div class="p-6 space-y-4">
                            @forelse ($konsultasis as $konsultasi)
                                @php
                                    $hoursSinceCreated = now()->diffInHours($konsultasi->created_at);
                                    $priority = 'medium';

                                    if (
                                        str_contains(strtolower($konsultasi->kategori), 'darurat') ||
                                        str_contains(strtolower($konsultasi->deskripsi), 'darurat')
                                    ) {
                                        $priority = 'critical';
                                    } elseif ($hoursSinceCreated > 24) {
                                        $priority = 'high';
                                    } elseif ($hoursSinceCreated < 2) {
                                        $priority = 'low';
                                    }
                                @endphp

                                <div class="queue-item p-4 border border-gray-200 rounded-lg priority-{{ $priority }} consultation-item"
                                    data-priority="{{ $priority }}" data-status="{{ $konsultasi->status }}"
                                    data-id="{{ $konsultasi->idKonsultasi }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="font-semibold text-gray-900">
                                                    {{ $konsultasi->peternak->nama ?? 'Peternak' }}
                                                </h4>
                                                @if ($konsultasi->ternak)
                                                    <span class="text-sm text-gray-600">•
                                                        {{ $konsultasi->ternak->jenis ?? ($konsultasi->ternak->nama_ternak ?? 'Ternak') }}</span>
                                                @endif
                                                <span class="status-badge status-{{ $konsultasi->status }}">
                                                    {{ ucfirst($konsultasi->status) }}
                                                </span>
                                                @if ($priority === 'critical')
                                                    <span
                                                        class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">
                                                        URGENT
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                                    @if ($konsultasi->ternak)
                                                        <span class="text-lg mr-2">
                                                            @switch(strtolower($konsultasi->ternak->jenis ?? ''))
                                                                @case('sapi')
                                                                    🐄
                                                                @break

                                                                @case('kambing')
                                                                    🐐
                                                                @break

                                                                @case('domba')
                                                                    🐑
                                                                @break

                                                                @default
                                                                    🐄
                                                            @endswitch
                                                        </span>
                                                        <span
                                                            class="font-medium">{{ $konsultasi->ternak->nama_ternak ?? $konsultasi->ternak->jenis }}</span>
                                                        <span class="mx-2">•</span>
                                                    @endif
                                                    <span>📋 {{ $konsultasi->kategori }}</span>
                                                    @if ($konsultasi->foto_ternak)
                                                        <span class="mx-2">•</span>
                                                        <span>📷 Ada foto</span>
                                                    @endif
                                                    <span class="mx-2">•</span>
                                                    <span>⏰ {{ $konsultasi->created_at->format('H:i d/m/Y') }}</span>
                                                </div>
                                                <h5 class="text-gray-900 font-medium mb-1">
                                                    {{ $konsultasi->judul_konsultasi }}</h5>
                                                <p class="text-gray-700 text-sm">
                                                    {{ Str::limit($konsultasi->deskripsi, 150) }}</p>

                                                {{-- Info khusus untuk status berlangsung --}}
                                                @if ($konsultasi->status === 'berlangsung')
                                                    <div class="berlangsung-info mt-3">
                                                        <div class="flex items-center justify-center space-x-2">
                                                            <svg class="w-5 h-5 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                            </svg>
                                                            <span class="text-sm font-medium text-green-800">
                                                                🩺 Penyuluh sedang berada di lokasi ternak
                                                            </span>
                                                        </div>
                                                        <p class="text-xs text-green-700 mt-1">
                                                            Konsultasi sedang berlangsung secara langsung
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex flex-col space-y-2 ml-4">
                                            <button onclick="viewConsultationDetail({{ $konsultasi->idKonsultasi }})"
                                                class="quick-action-btn bg-blue-100 text-blue-700 border border-blue-200 hover:bg-blue-200">
                                                📋 Detail
                                            </button>

                                            <div class="relative">
                                                <select
                                                    onchange="updateKonsultasiStatus({{ $konsultasi->idKonsultasi }}, this.value)"
                                                    class="quick-action-btn status-dropdown bg-gray-100 text-gray-700 border border-gray-300 hover:bg-gray-200 cursor-pointer">
                                                    <option value="">Ubah Status</option>
                                                    <option value="pending"
                                                        {{ $konsultasi->status === 'pending' ? 'selected' : '' }}>
                                                        ⏳ Pending
                                                    </option>
                                                    <option value="berlangsung"
                                                        {{ $konsultasi->status === 'berlangsung' ? 'selected' : '' }}>
                                                        ▶️ Berlangsung
                                                    </option>
                                                    <option value="selesai"
                                                        {{ $konsultasi->status === 'selesai' ? 'selected' : '' }}>
                                                        ✅ Selesai
                                                    </option>
                                                    <option value="dibatalkan"
                                                        {{ $konsultasi->status === 'dibatalkan' ? 'selected' : '' }}>
                                                        ❌ Dibatalkan
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                            </path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada konsultasi</h3>
                                        <p class="text-gray-500">Konsultasi baru akan muncul di sini</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Active Consultations Section --}}
                    @if ($konsultasiBerlangsung > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Konsultasi Aktif</h3>
                            </div>

                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach ($konsultasis->where('status', 'berlangsung') as $activeConsultation)
                                        <div
                                            class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">
                                                        {{ $activeConsultation->peternak->nama ?? 'Peternak' }} •
                                                        {{ $activeConsultation->ternak->nama_ternak ?? ($activeConsultation->ternak->jenis ?? 'Ternak') }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $activeConsultation->judul_konsultasi }}
                                                    </p>
                                                    <p class="text-sm text-gray-700 italic">
                                                        {{ $activeConsultation->kategori }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="berlangsung-info">
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-4 h-4 text-green-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <span class="text-sm font-medium text-green-800">Di Lokasi</span>
                                                    </div>
                                                </div>
                                                <button
                                                    onclick="updateKonsultasiStatus({{ $activeConsultation->idKonsultasi }}, 'selesai')"
                                                    class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                                                    🏁 Selesai
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detail Modal --}}
            <div id="detailModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
                <div class="relative top-10 mx-auto p-5 border w-full max-w-5xl shadow-lg rounded-lg bg-white modal-content">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Detail Konsultasi</h3>
                                <p id="detailConsultationId" class="text-sm text-gray-600"></p>
                            </div>
                        </div>
                        <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div id="modalLoading" class="flex items-center justify-center py-12 hidden">
                        <div class="text-center">
                            <div class="loading-spinner mx-auto mb-4"></div>
                            <p class="text-gray-600">Memuat detail konsultasi...</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div id="modalContent" class="hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            {{-- Left Column - Info Konsultasi --}}
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <span class="w-2 h-4 bg-blue-500 rounded mr-2"></span>
                                        Informasi Konsultasi
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">ID:</span>
                                            <span id="modalKonsultasiId" class="font-medium"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Judul:</span>
                                            <span id="modalJudul" class="font-medium text-right ml-4"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kategori:</span>
                                            <span id="modalKategori" class="font-medium capitalize"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Status:</span>
                                            <span id="modalStatus" class="font-medium"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tanggal:</span>
                                            <span id="modalTanggal" class="font-medium"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <span class="w-2 h-4 bg-green-500 rounded mr-2"></span>
                                        Informasi Peternak
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Nama:</span>
                                            <span id="modalPeternak" class="font-medium"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Email:</span>
                                            <span id="modalPeternakEmail" class="font-medium"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">No. HP:</span>
                                            <span id="modalPeternakPhone" class="font-medium"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <span class="w-2 h-4 bg-purple-500 rounded mr-2"></span>
                                        Aksi Cepat
                                    </h4>
                                    <div id="detailActions" class="space-y-2"></div>
                                </div>
                            </div>

                            {{-- Middle Column - Info Ternak --}}
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <span class="w-2 h-4 bg-orange-500 rounded mr-2"></span>
                                        Informasi Ternak
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Nama:</span>
                                            <span id="modalNamaTernak" class="font-medium"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Jenis:</span>
                                            <span id="modalJenisTernak" class="font-medium"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Umur:</span>
                                            <span id="modalUmurTernak" class="font-medium"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <span class="w-2 h-4 bg-red-500 rounded mr-2"></span>
                                        Deskripsi Masalah
                                    </h4>
                                    <div id="modalDeskripsi"
                                        class="text-gray-700 text-sm leading-relaxed bg-white p-3 rounded border"></div>
                                </div>
                            </div>

                            {{-- Right Column - Foto Ternak --}}
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <span class="w-2 h-4 bg-yellow-500 rounded mr-2"></span>
                                        Foto Ternak
                                    </h4>
                                    <div id="modalFotoContainer" class="photo-container">
                                        <div class="text-center text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <p class="text-sm">Memuat foto...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="modalError" class="text-center py-12 hidden">
                        <svg class="w-16 h-16 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Gagal Memuat Data</h3>
                        <p id="modalErrorMessage" class="text-gray-500 mb-4"></p>
                        <button onclick="closeDetailModal()"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                            Tutup
                        </button>
                    </div>

                    <div id="modalFooter" class="mt-6 flex justify-end space-x-3 pt-4 border-t border-gray-200 hidden">
                        <button onclick="closeDetailModal()"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            let currentDetailId = null;

            /**
             * Tampilkan detail konsultasi menggunakan AJAX
             */
            function viewConsultationDetail(id) {
                currentDetailId = id;

                // Tampilkan modal dan loading state
                showModal();
                showLoadingState();

                // Kirim request AJAX
                fetch(`/penyuluh/konsultasi/${id}/detail`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            populateModalWithData(data.data);
                            showContentState();
                        } else {
                            throw new Error(data.message || 'Failed to load consultation data');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading consultation detail:', error);
                        showErrorState(error.message);
                    });
            }

            /**
             * Tampilkan modal
             */
            function showModal() {
                const modal = document.getElementById('detailModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            /**
             * Tampilkan loading state
             */
            function showLoadingState() {
                document.getElementById('modalLoading').classList.remove('hidden');
                document.getElementById('modalContent').classList.add('hidden');
                document.getElementById('modalError').classList.add('hidden');
                document.getElementById('modalFooter').classList.add('hidden');
            }

            /**
             * Tampilkan content state
             */
            function showContentState() {
                document.getElementById('modalLoading').classList.add('hidden');
                document.getElementById('modalContent').classList.remove('hidden');
                document.getElementById('modalError').classList.add('hidden');
                document.getElementById('modalFooter').classList.remove('hidden');
            }

            /**
             * Tampilkan error state
             */
            function showErrorState(message) {
                document.getElementById('modalLoading').classList.add('hidden');
                document.getElementById('modalContent').classList.add('hidden');
                document.getElementById('modalError').classList.remove('hidden');
                document.getElementById('modalFooter').classList.add('hidden');
                document.getElementById('modalErrorMessage').textContent = message;
            }

            /**
             * Populate modal dengan data dari AJAX
             */
            function populateModalWithData(data) {
                // Info Konsultasi
                document.getElementById('detailConsultationId').textContent = `ID: ${data.idKonsultasi}`;
                document.getElementById('modalKonsultasiId').textContent = data.idKonsultasi;
                document.getElementById('modalJudul').textContent = data.judul_konsultasi || '-';
                document.getElementById('modalKategori').textContent = data.kategori || '-';
                document.getElementById('modalStatus').textContent = capitalizeFirst(data.status || '-');
                document.getElementById('modalTanggal').textContent = data.created_at || '-';
                document.getElementById('modalDeskripsi').textContent = data.deskripsi || '-';

                // Info Peternak
                document.getElementById('modalPeternak').textContent = data.peternak?.nama || '-';
                document.getElementById('modalPeternakEmail').textContent = data.peternak?.email || '-';
                document.getElementById('modalPeternakPhone').textContent = data.peternak?.phone || '-';

                // Info Ternak
                document.getElementById('modalNamaTernak').textContent = data.ternak?.nama_ternak || '-';
                document.getElementById('modalJenisTernak').textContent = data.ternak?.jenis || '-';
                document.getElementById('modalUmurTernak').textContent = data.ternak?.umur || '-';

                // Foto Ternak
                displayFotoTernak(data.ternak?.fotoTernak, data.foto_ternak);

                // Actions
                populateDetailActions(data.status, data.idKonsultasi);
            }

            /**
             * Tampilkan foto ternak
             */
            function displayFotoTernak(ternakFoto, konsultasiFoto) {
                const container = document.getElementById('modalFotoContainer');

                // Prioritas: foto dari konsultasi, lalu foto ternak
                let fotoPath = konsultasiFoto || ternakFoto;

                if (fotoPath) {
                    // Pastikan path benar
                    if (!fotoPath.startsWith('/') && !fotoPath.startsWith('http')) {
                        fotoPath = `/storage/foto_ternak/${fotoPath}`;
                    }

                    container.innerHTML = `
                        <img src="${fotoPath}" 
                             alt="Foto Ternak" 
                             class="ternak-photo cursor-pointer"
                             onerror="this.onerror=null; this.parentNode.innerHTML=getNoFotoHTML();"
                             onclick="openImageModal('${fotoPath}')">
                    `;
                } else {
                    container.innerHTML = getNoFotoHTML();
                }
            }

            /**
             * HTML untuk tidak ada foto
             */
            function getNoFotoHTML() {
                return `
                    <div class="text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm">Tidak ada foto</p>
                    </div>
                `;
            }

            /**
             * Buka modal gambar full screen
             */
            function openImageModal(imagePath) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                modal.onclick = (e) => {
                    if (e.target === modal) modal.remove();
                };

                modal.innerHTML = `
                    <div class="max-w-4xl max-h-screen p-4 relative">
                        <img src="${imagePath}" alt="Foto Ternak" class="max-w-full max-h-full object-contain rounded-lg">
                        <button onclick="this.parentElement.parentElement.remove()" 
                            class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;

                document.body.appendChild(modal);
            }

            /**
             * Populate action buttons
             */
            function populateDetailActions(status, id) {
                const actionsContainer = document.getElementById('detailActions');

                let actions = `
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Ubah Status:</label>
                        <select onchange="updateKonsultasiStatus(${id}, this.value)" 
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Status</option>
                            <option value="pending" ${status === 'pending' ? 'selected' : ''}>⏳ Pending</option>
                            <option value="berlangsung" ${status === 'berlangsung' ? 'selected' : ''}>▶️ Berlangsung</option>
                            <option value="selesai" ${status === 'selesai' ? 'selected' : ''}>✅ Selesai</option>
                            <option value="dibatalkan" ${status === 'dibatalkan' ? 'selected' : ''}>❌ Dibatalkan</option>
                        </select>
                    </div>
                `;

                // Info khusus untuk status berlangsung
                if (status === 'berlangsung') {
                    actions += `
                        <div class="berlangsung-info mt-3">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">
                                    Konsultasi Berlangsung
                                </span>
                            </div>
                            <p class="text-xs text-green-700 mt-1 text-center">
                                Penyuluh sedang berada di lokasi ternak
                            </p>
                        </div>
                    `;
                }

                actionsContainer.innerHTML = actions;
            }

            /**
             * Tutup modal detail
             */
            function closeDetailModal() {
                document.getElementById('detailModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentDetailId = null;
            }

            /**
             * Update status konsultasi
             */
            function updateKonsultasiStatus(konsultasiId, newStatus) {
                if (!newStatus) return;

                const statusLabels = {
                    'pending': 'Pending',
                    'berlangsung': 'Berlangsung',
                    'selesai': 'Selesai',
                    'dibatalkan': 'Dibatalkan'
                };

                let confirmMessage = `Apakah Anda yakin ingin mengubah status konsultasi menjadi "${statusLabels[newStatus]}"?`;

                if (newStatus === 'berlangsung') {
                    confirmMessage +=
                        '\n\nDengan status "Berlangsung", artinya Anda sedang melakukan konsultasi langsung di lokasi ternak.';
                }

                if (!confirm(confirmMessage)) {
                    // Reset dropdown jika ada
                    const dropdown = event.target;
                    if (dropdown) {
                        const currentBadge = dropdown.closest('.queue-item, .consultation-item')?.querySelector(
                        '.status-badge');
                        const currentStatus = currentBadge ? currentBadge.textContent.toLowerCase().trim() : '';
                        dropdown.value = currentStatus;
                    }
                    return;
                }

                // Show loading
                const dropdown = event.target;
                if (dropdown) {
                    const originalHTML = dropdown.innerHTML;
                    dropdown.disabled = true;
                    dropdown.innerHTML = '<option>Loading...</option>';
                }

                // Kirim request
                fetch(`/penyuluh/konsultasi/update-status/${konsultasiId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateKonsultasiUI(konsultasiId, newStatus);
                            showNotification('success', data.message);

                            // Update modal jika masih terbuka
                            if (currentDetailId === konsultasiId) {
                                document.getElementById('modalStatus').textContent = capitalizeFirst(newStatus);
                                populateDetailActions(newStatus, konsultasiId);
                            }

                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            throw new Error(data.message || 'Gagal mengupdate status');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating status:', error);

                        // Reset dropdown
                        if (dropdown) {
                            dropdown.innerHTML = originalHTML;
                            dropdown.disabled = false;

                            const currentBadge = dropdown.closest('.queue-item, .consultation-item')?.querySelector(
                                '.status-badge');
                            const currentStatus = currentBadge ? currentBadge.textContent.toLowerCase().trim() : '';
                            dropdown.value = currentStatus;
                        }

                        showNotification('error', error.message || 'Terjadi kesalahan saat mengupdate status');
                    });
            }

            /**
             * Update UI konsultasi
             */
            function updateKonsultasiUI(konsultasiId, newStatus) {
                const consultationElement = document.querySelector(`[data-id="${konsultasiId}"]`);
                if (!consultationElement) return;

                // Update status badge
                const statusBadge = consultationElement.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = `status-badge status-${newStatus}`;
                    statusBadge.textContent = capitalizeFirst(newStatus);
                }

                // Update data-status attribute
                consultationElement.setAttribute('data-status', newStatus);

                // Update berlangsung info
                updateBerlangsungInfo(consultationElement, newStatus);
            }

            /**
             * Update info berlangsung
             */
            function updateBerlangsungInfo(element, status) {
                const existingInfo = element.querySelector('.berlangsung-info');

                if (existingInfo) {
                    existingInfo.remove();
                }

                if (status === 'berlangsung') {
                    const infoContainer = element.querySelector('.mb-3');
                    const infoHTML = `
                        <div class="berlangsung-info mt-3">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">
                                    🩺 Penyuluh sedang berada di lokasi ternak
                                </span>
                            </div>
                            <p class="text-xs text-green-700 mt-1">
                                Konsultasi sedang berlangsung secara langsung
                            </p>
                        </div>
                    `;
                    infoContainer.insertAdjacentHTML('beforeend', infoHTML);
                }
            }

            /**
             * Tampilkan notifikasi
             */
            function showNotification(type, message) {
                const existingNotification = document.querySelector('.notification-toast');
                if (existingNotification) {
                    existingNotification.remove();
                }

                const notification = document.createElement('div');
                notification.className =
                    `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg text-white font-medium transition-all duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                notification.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <span>${type === 'success' ? '✅' : '❌'}</span>
                        <span>${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';

                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }, 3000);
            }

            /**
             * Utility functions
             */
            function capitalizeFirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            function filterByStatus() {
                const filter = document.getElementById('statusFilter').value;
                const items = document.querySelectorAll('.consultation-item');

                items.forEach(item => {
                    const status = item.getAttribute('data-status');
                    if (!filter || status === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            function refreshQueue() {
                window.location.reload();
            }

            // Event listeners
            document.addEventListener('DOMContentLoaded', function() {
                const detailModal = document.getElementById('detailModal');
                if (detailModal) {
                    detailModal.addEventListener('click', function(e) {
                        if (e.target === detailModal) {
                            closeDetailModal();
                        }
                    });
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const detailModal = document.getElementById('detailModal');
                    if (detailModal && !detailModal.classList.contains('hidden')) {
                        closeDetailModal();
                    }
                }
            });
        </script>
    @endpush
