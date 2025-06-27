@extends('layouts.app')

@section('title', 'Data Ternak')
@section('page-title', 'Data Ternak')
@section('page-description', 'Kelola dan pantau semua data ternak Anda')

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

        .search-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .table-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        /* CSS yang sudah ada... */

        /* Tambahkan ini: */
        .border-red-500 {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444 !important;
        }

        #addModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
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
                        placeholder="Cari ternak..." onkeyup="searchTernak()">
                </div>

                <!-- Filter Status -->
                <select id="statusFilter"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    onchange="filterByStatus()">
                    <option value="">Semua Status</option>
                    <option value="sehat">Sehat</option>
                    <option value="sakit">Sakit</option>
                    <option value="perawatan">Perawatan</option>
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

                <!-- Add New Button -->
                <button onclick="openAddModal()"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transform hover:scale-105 transition-all">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Ternak
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Ternak</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalTernak ?? 25 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Sehat</p>
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
                        <p class="text-sm font-medium text-gray-600">Sakit</p>
                        <p class="text-2xl font-bold text-red-600">{{ $ternakSakit ?? 2 }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
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
                        <p class="text-sm font-medium text-gray-600">Perawatan</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $ternakPerawatan ?? 2 }}</p>
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

        <!-- View Toggle -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Tampilan:</span>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button onclick="switchView('grid')" id="gridViewBtn"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-colors view-btn-active">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Grid
                    </button>
                    <button onclick="switchView('table')" id="tableViewBtn"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-gray-600 hover:text-gray-900">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 0V4a1 1 0 011-1h3M8 3v2m0 0V3m0 2h8m0-2v2m0-2V3m0 2h3a1 1 0 011 1v16">
                            </path>
                        </svg>
                        Tabel
                    </button>
                </div>
            </div>

            <div class="text-sm text-gray-600">
                Menampilkan {{ $ternakList->count() ?? 10 }} dari {{ $totalTernak ?? 25 }} ternak
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($ternakList ?? [] as $index => $ternak)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover ternak-card"
                    data-name="{{ $ternak->nama ?? 'Sapi #' . sprintf('%03d', $index + 1) }}"
                    data-status="{{ $ternak->status_kesehatan ?? 'sehat' }}">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-3xl">🐄</div>
                            <span class="status-badge status-{{ $ternak->status_kesehatan ?? 'sehat' }}">
                                {{ ucfirst($ternak->status_kesehatan ?? 'sehat') }}
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $ternak->nama ?? 'Sapi #' . sprintf('%03d', $index + 1) }}</h3>
                            <p class="text-sm text-gray-600">{{ $ternak->jenis ?? 'Sapi Limosin' }}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Umur: {{ $ternak->umur ?? rand(1, 5) }} tahun
                            </div>
                            @if (isset($ternak->berat))
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-3m-3 3l-3-3">
                                        </path>
                                    </svg>
                                    Berat: {{ $ternak->berat }} kg
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between mt-6">
                            <div class="flex space-x-2">
                                <a href="" class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200"
                                    title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="" class="action-btn bg-green-100 text-green-600 hover:bg-green-200"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <button onclick="deleteTernak({{ $ternak->id ?? $index + 1 }})"
                                    class="action-btn bg-red-100 text-red-600 hover:bg-red-200" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            @if (($ternak->status_kesehatan ?? 'sehat') !== 'sehat')
                                <a href="{{ route('kesehatan.create', ['ternak_id' => $ternak->id ?? $index + 1]) }}"
                                    class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full hover:bg-yellow-200 transition-colors">
                                    Cek Kesehatan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Sample Data jika kosong -->
                @for ($i = 1; $i <= 8; $i++)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover ternak-card"
                        data-name="Sapi #{{ sprintf('%03d', $i) }}"
                        data-status="{{ $i <= 6 ? 'sehat' : ($i == 7 ? 'sakit' : 'perawatan') }}">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-3xl">🐄</div>
                                <span
                                    class="status-badge status-{{ $i <= 6 ? 'sehat' : ($i == 7 ? 'sakit' : 'perawatan') }}">
                                    {{ $i <= 6 ? 'Sehat' : ($i == 7 ? 'Sakit' : 'Perawatan') }}
                                </span>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-lg font-semibold text-gray-900">Sapi #{{ sprintf('%03d', $i) }}</h3>
                                <p class="text-sm text-gray-600">
                                    {{ ['Sapi Limosin', 'Sapi Brahman', 'Sapi Angus', 'Sapi Simental'][($i - 1) % 4] }}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Umur: {{ rand(1, 5) }} tahun
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-3m-3 3l-3-3">
                                        </path>
                                    </svg>
                                    Berat: {{ rand(200, 600) }} kg
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-6">
                                <div class="flex space-x-2">
                                    <a href="#" class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200"
                                        title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="#" class="action-btn bg-green-100 text-green-600 hover:bg-green-200"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button onclick="deleteTernak({{ $i }})"
                                        class="action-btn bg-red-100 text-red-600 hover:bg-red-200" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                @if ($i > 6)
                                    <a href="#"
                                        class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full hover:bg-yellow-200 transition-colors">
                                        Cek Kesehatan
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>

        <!-- Table View -->
        <div id="tableView" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="table-header px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Ternak</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ternak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Umur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Berat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                        @forelse($ternakList ?? [] as $index => $ternak)
                            <tr class="hover:bg-gray-50 ternak-row"
                                data-name="{{ $ternak->nama ?? 'Sapi #' . sprintf('%03d', $index + 1) }}"
                                data-status="{{ $ternak->status_kesehatan ?? 'sehat' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-2xl mr-3">🐄</div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $ternak->nama ?? 'Sapi #' . sprintf('%03d', $index + 1) }}</div>
                                            <div class="text-sm text-gray-500">ID:
                                                #{{ $ternak->id ?? sprintf('%03d', $index + 1) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ternak->jenis ?? 'Sapi Limosin' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ternak->umur ?? rand(1, 5) }} tahun</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ternak->berat ?? rand(200, 600) }} kg</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge status-{{ $ternak->status_kesehatan ?? 'sehat' }}">
                                        {{ ucfirst($ternak->status_kesehatan ?? 'sehat') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="" class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href=""
                                            class="action-btn bg-green-100 text-green-600 hover:bg-green-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button onclick="deleteTernak({{ $ternak->id ?? $index + 1 }})"
                                            class="action-btn bg-red-100 text-red-600 hover:bg-red-200" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- Sample rows -->
                            @for ($i = 1; $i <= 8; $i++)
                                <tr class="hover:bg-gray-50 ternak-row" data-name="Sapi #{{ sprintf('%03d', $i) }}"
                                    data-status="{{ $i <= 6 ? 'sehat' : ($i == 7 ? 'sakit' : 'perawatan') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-2xl mr-3">🐄</div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Sapi
                                                    #{{ sprintf('%03d', $i) }}</div>
                                                <div class="text-sm text-gray-500">ID: #{{ sprintf('%03d', $i) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ['Sapi Limosin', 'Sapi Brahman', 'Sapi Angus', 'Sapi Simental'][($i - 1) % 4] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ rand(1, 5) }}
                                        tahun</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ rand(200, 600) }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="status-badge status-{{ $i <= 6 ? 'sehat' : ($i == 7 ? 'sakit' : 'perawatan') }}">
                                            {{ $i <= 6 ? 'Sehat' : ($i == 7 ? 'Sakit' : 'Perawatan') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="#"
                                                class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200"
                                                title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="#"
                                                class="action-btn bg-green-100 text-green-600 hover:bg-green-200"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <button onclick="deleteTernak({{ $i }})"
                                                class="action-btn bg-red-100 text-red-600 hover:bg-red-200"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if (isset($ternakList) && method_exists($ternakList, 'links'))
            <div class="flex justify-center">
                {{ $ternakList->links() }}
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Ternak</h3>
                <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus data ternak ini? Tindakan ini tidak
                    dapat dibatalkan.</p>
                <div class="items-center px-4 py-3 mt-4">
                    <button id="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Hapus
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

{{-- 🎯 MODAL TAMBAH TERNAK ADA DI SINI 👇 --}}
{{-- ================================================= --}}

<!-- Add Ternak Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900">Tambah Ternak Baru</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Modal Form -->
        <form id="addTernakForm" action="{{ route('ternak.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Ternak -->
                <div class="col-span-1 md:col-span-2">
                    <label for="namaTernak" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Ternak <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="namaTernak" name="namaTernak" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Contoh: Sapi Makmur 001">
                    <p class="text-xs text-gray-500 mt-1">Berikan nama unik untuk memudah identifikasi</p>
                </div>

                <!-- Jenis Ternak -->
                <div>
                    <label for="jenisTernak" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Ternak <span class="text-red-500">*</span>
                    </label>
                    <select id="jenisTernak" name="jenisTernak" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Pilih Jenis Ternak</option>
                        <option value="Sapi Limosin">Sapi Limosin</option>
                        <option value="Sapi Brahman">Sapi Brahman</option>
                        <option value="Sapi Angus">Sapi Angus</option>
                        <option value="Sapi Simental">Sapi Simental</option>
                        <option value="Sapi Bali">Sapi Bali</option>
                        <option value="Sapi Ongole">Sapi Ongole</option>
                        <option value="Kambing Boer">Kambing Boer</option>
                        <option value="Kambing Etawa">Kambing Etawa</option>
                        <option value="Kambing Jawarandu">Kambing Jawarandu</option>
                        <option value="Domba Garut">Domba Garut</option>
                        <option value="Domba Merino">Domba Merino</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenisKelamin" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select id="jenisKelamin" name="jenisKelamin" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>

                <!-- Umur -->
                <div>
                    <label for="umur" class="block text-sm font-medium text-gray-700 mb-2">
                        Umur (Tahun) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="umur" name="umur" min="0" max="20" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="0">
                </div>

                <!-- Berat -->
                <div>
                    <label for="berat" class="block text-sm font-medium text-gray-700 mb-2">
                        Berat (Kg)
                    </label>
                    <input type="number" id="berat" name="berat" min="0" step="0.1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="0.0">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Lahir
                    </label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" max="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Asal/Sumber -->
                <div>
                    <label for="asal" class="block text-sm font-medium text-gray-700 mb-2">
                        Asal/Sumber Ternak
                    </label>
                    <select id="asal" name="asal"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Pilih Asal Ternak</option>
                        <option value="Pembelian">Pembelian</option>
                        <option value="Kelahiran">Kelahiran (Breeding)</option>
                        <option value="Hibah">Hibah/Bantuan</option>
                        <option value="Warisan">Warisan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Status Kesehatan -->
                <div class="col-span-1 md:col-span-2">
                    <label for="status_kesehatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kesehatan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-4">
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status_kesehatan" value="sehat" checked
                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                    <span class="text-sm font-medium text-gray-900">Sehat</span>
                                </div>
                                <p class="text-xs text-gray-500">Kondisi normal</p>
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
                                <p class="text-xs text-gray-500">Memerlukan perawatan</p>
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
                                <p class="text-xs text-gray-500">Dalam pengobatan</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Foto Ternak -->
                <div class="col-span-1 md:col-span-2">
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Ternak
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="foto"
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
                                <p class="text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 2MB)</p>
                            </div>
                            <input id="foto" name="foto" type="file" class="hidden" accept="image/*"
                                onchange="previewImage(this)">
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="preview" src="" alt="Preview"
                            class="w-full h-32 object-cover rounded-lg">
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="col-span-1 md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan Tambahan
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                        placeholder="Catatan khusus tentang ternak ini..."></textarea>
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
                    <span id="submitText">Simpan Ternak</span>
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

{{-- ================================================= --}}
{{-- 🎯 MODAL TAMBAH TERNAK BERAKHIR DI SINI 👆 --}}


@push('scripts')
    <script>
        let currentView = 'grid';
        let deleteId = null;

        // ===== FUNGSI MODAL =====

        // Fungsi untuk membuka modal tambah ternak
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                document.getElementById('namaTernak').focus();
            }, 100);
        }

        // Fungsi untuk menutup modal tambah ternak
        function closeAddModal() {
            const modal = document.getElementById('addModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Reset form
            document.getElementById('addTernakForm').reset();

            // Hide image preview
            const imagePreview = document.getElementById('imagePreview');
            if (imagePreview) {
                imagePreview.classList.add('hidden');
            }

            // Reset radio button
            document.querySelector('input[name="status_kesehatan"][value="sehat"]').checked = true;
        }

        // Fungsi untuk preview gambar
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const preview = document.getElementById('preview');
                const imagePreview = document.getElementById('imagePreview');

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        // ===== FUNGSI VIEW =====

        // Switch between grid and table view
        function switchView(view) {
            currentView = view;
            const gridView = document.getElementById('gridView');
            const tableView = document.getElementById('tableView');
            const gridBtn = document.getElementById('gridViewBtn');
            const tableBtn = document.getElementById('tableViewBtn');

            if (view === 'grid') {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');
                gridBtn.classList.add('view-btn-active', 'bg-white', 'text-gray-900', 'shadow-sm');
                gridBtn.classList.remove('text-gray-600');
                tableBtn.classList.remove('view-btn-active', 'bg-white', 'text-gray-900', 'shadow-sm');
                tableBtn.classList.add('text-gray-600');
            } else {
                gridView.classList.add('hidden');
                tableView.classList.remove('hidden');
                tableBtn.classList.add('view-btn-active', 'bg-white', 'text-gray-900', 'shadow-sm');
                tableBtn.classList.remove('text-gray-600');
                gridBtn.classList.remove('view-btn-active', 'bg-white', 'text-gray-900', 'shadow-sm');
                gridBtn.classList.add('text-gray-600');
            }
        }

        // ===== FUNGSI SEARCH & FILTER =====

        // Search functionality
        function searchTernak() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.ternak-card');
            const rows = document.querySelectorAll('.ternak-row');

            // Filter grid cards
            cards.forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                if (name.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Filter table rows
            rows.forEach(row => {
                const name = row.getAttribute('data-name').toLowerCase();
                if (name.includes(searchTerm)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Filter by status
        function filterByStatus() {
            const statusFilter = document.getElementById('statusFilter').value;
            const cards = document.querySelectorAll('.ternak-card');
            const rows = document.querySelectorAll('.ternak-row');

            // Filter grid cards
            cards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (!statusFilter || status === statusFilter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Filter table rows
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (!statusFilter || status === statusFilter) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // ===== FUNGSI DELETE =====

        // Delete ternak
        function deleteTernak(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteId = null;
        }

        // ===== FUNGSI EXPORT =====

        // Export data
        function exportData() {
            // Show loading
            const exportBtn = event.target;
            const originalText = exportBtn.innerHTML;
            exportBtn.innerHTML =
                '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Exporting...';

            // Simulate export process
            setTimeout(() => {
                exportBtn.innerHTML = originalText;
                alert('Fitur export akan segera tersedia!');
            }, 2000);
        }

        // ===== EVENT LISTENERS =====

        // DOM Content Loaded
        document.addEventListener('DOMContentLoaded', function() {

            // === FORM SUBMISSION ===
            const form = document.getElementById('addTernakForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validasi form
                    const required = ['namaTernak', 'jenisTernak', 'jenisKelamin', 'umur'];
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

                    if (!isValid) {
                        alert('Mohon lengkapi semua field yang wajib diisi (bertanda *)');
                        return;
                    }

                    // Show loading
                    const submitBtn = document.getElementById('submitBtn');
                    const submitText = document.getElementById('submitText');
                    const submitLoading = document.getElementById('submitLoading');

                    submitBtn.disabled = true;
                    submitText.textContent = 'Menyimpan...';
                    submitLoading.classList.remove('hidden');

                    // Create FormData for file upload
                    const formData = new FormData(this);

                    // Submit form via fetch for better UX
                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content') || '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeAddModal();
                                alert('Data ternak berhasil ditambahkan!');
                                window.location.reload();
                            } else {
                                alert(data.message || 'Terjadi kesalahan saat menyimpan data');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menyimpan data');
                        })
                        .finally(() => {
                            // Reset loading state
                            submitBtn.disabled = false;
                            submitText.textContent = 'Simpan Ternak';
                            submitLoading.classList.add('hidden');
                        });
                });
            }

            // === AUTO-CALCULATE AGE ===
            const tanggalLahir = document.getElementById('tanggal_lahir');
            const umurField = document.getElementById('umur');

            if (tanggalLahir && umurField) {
                tanggalLahir.addEventListener('change', function() {
                    const birthDate = new Date(this.value);
                    const today = new Date();
                    const ageInYears = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));

                    if (ageInYears >= 0 && ageInYears <= 20) {
                        umurField.value = ageInYears;
                    }
                });
            }

            // === MODAL OUTSIDE CLICK ===
            const addModal = document.getElementById('addModal');
            if (addModal) {
                addModal.addEventListener('click', function(e) {
                    if (e.target === addModal) {
                        closeAddModal();
                    }
                });
            }

            // === DELETE CONFIRMATION ===
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', function() {
                    if (deleteId) {
                        // Create form and submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/ternak/${deleteId}`;

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';

                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = '{{ csrf_token() }}';

                        form.appendChild(methodInput);
                        form.appendChild(tokenInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // === CARD ANIMATION ===
            const cards = document.querySelectorAll('.ternak-card');
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

        // === KEYBOARD EVENTS ===
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const addModal = document.getElementById('addModal');
                const deleteModal = document.getElementById('deleteModal');

                if (addModal && !addModal.classList.contains('hidden')) {
                    closeAddModal();
                }

                if (deleteModal && !deleteModal.classList.contains('hidden')) {
                    closeDeleteModal();
                }
            }
        });

        // === ADD STYLE FOR VIEW BUTTON ===
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
