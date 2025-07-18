@extends('layouts.app')

@section('title', 'Kelola Informasi Umum')
@section('page-title', 'Kelola Informasi Umum')
@section('page-description', 'Kelola dan publikasikan informasi untuk halaman public')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- TinyMCE Editor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0/tinymce.min.js"></script>
@endpush

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

        .status-published {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-draft {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-archived {
            background-color: #f3f4f6;
            color: #374151;
        }

        .kategori-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .kategori-berita {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .kategori-pengumuman {
            background-color: #fef3c7;
            color: #d97706;
        }

        .kategori-tips {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .kategori-panduan {
            background-color: #f3e8ff;
            color: #7c3aed;
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

        .border-red-500 {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444 !important;
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

        .view-btn-active {
            background-color: white !important;
            color: #111827 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }

        .detail-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .featured-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
        }

        .content-preview {
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .priority-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .priority-high {
            background-color: #fecaca;
            color: #991b1b;
        }

        .priority-medium {
            background-color: #fef3c7;
            color: #92400e;
        }

        .priority-low {
            background-color: #dcfce7;
            color: #166534;
        }

        .tox-tinymce {
            border-radius: 0.5rem !important;
        }

        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
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
                        placeholder="Cari informasi..." onkeyup="searchInformasi()">
                </div>

                <!-- Category Filter -->
                <select id="kategoriFilter"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    onchange="filterByKategori()">
                    <option value="">Semua Kategori</option>
                    <option value="berita">Berita</option>
                    <option value="pengumuman">Pengumuman</option>
                    <option value="tips">Tips & Trik</option>
                    <option value="panduan">Panduan</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                <button onclick="openAddModal()"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transform hover:scale-105 transition-all">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Informasi
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Informasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalInformasi ?? 32 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Published</p>
                        <p class="text-2xl font-bold text-green-600">{{ $informasiPublished ?? 24 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Draft</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $informasiDraft ?? 6 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Views</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $totalViews ?? '12.5K' }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
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
                Menampilkan {{ $informasiList->count() ?? 10 }} dari {{ $totalInformasi ?? 32 }} informasi
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($informasiList as $index => $info)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover informasi-card"
                    data-title="{{ $info->judul ?? 'Informasi ' . sprintf('%03d', $index + 1) }}" data-status="published"
                    data-id="{{ $info->idInformasi ?? $index + 1 }}"
                    data-kategori="{{ $info->kategori ?? ['berita', 'pengumuman', 'tips', 'panduan'][$index % 4] }}"
                    data-views="{{ rand(100, 1000) }}"
                    data-created="{{ $info->created_at ?? date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) }}"
                    data-priority="medium"
                    data-content="{{ $info->deskripsi ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}"
                    data-excerpt="{{ Str::limit(strip_tags($info->deskripsi), 150) ?? 'Excerpt singkat dari informasi ini...' }}"
                    data-featured-image="{{ $info->foto }}" data-tanggal-kegiatan="{{ $info->tanggal_kegiatan }}"
                    data-lokasi="{{ $info->lokasi }}">

                    <div class="relative">
                        @if (isset($info->foto) && $info->foto)
                            <img src="{{ asset('storage/informasi/' . $info->foto) }}" alt="Featured Image"
                                class="w-full h-48 object-cover rounded-t-xl">
                        @else
                            <div
                                class="w-full h-48 bg-gradient-to-br from-blue-100 to-blue-200 rounded-t-xl flex items-center justify-center">
                                <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span
                                class="kategori-badge kategori-{{ $info->kategori ?? ['berita', 'pengumuman', 'tips', 'panduan'][$index % 4] }}">
                                {{ ucfirst($info->kategori ?? ['Berita', 'Pengumuman', 'Tips', 'Panduan'][$index % 4]) }}
                            </span>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                {{ rand(100, 1000) }}
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                {{ $info->judul ?? 'Informasi ' . sprintf('%03d', $index + 1) }}
                            </h3>
                            <p class="text-sm text-gray-600 content-preview">
                                {{ Str::limit(strip_tags($info->deskripsi), 150) ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.' }}
                            </p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                @if ($info->tanggal_kegiatan)
                                    {{ \Carbon\Carbon::parse($info->tanggal_kegiatan)->format('d M Y') }}
                                @else
                                    {{ date('d M Y', strtotime('-' . rand(1, 30) . ' days')) }}
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <div class="flex space-x-2">
                                <button onclick="openDetailModal(this)"
                                    class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                                <button onclick="openEditModal(this)"
                                    class="action-btn bg-green-100 text-green-600 hover:bg-green-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal('{{ $info->idInformasi ?? $index + 1 }}')"
                                    class="action-btn bg-red-100 text-red-600 hover:bg-red-200" title="Hapus"
                                    type="button">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                                {{ ucfirst($info->kategori ?? 'Info') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">
                    Tidak ada data informasi tersedia.
                </div>
            @endforelse
        </div>

        <!-- Table View -->
        <div id="tableView" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="table-header px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Informasi</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Informasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                        @forelse($informasiList as $index => $info)
                            <tr class="hover:bg-gray-50 informasi-row" data-title="{{ $info->judul }}"
                                data-status="published" data-id="{{ $info->idInformasi ?? $index + 1 }}"
                                data-kategori="{{ $info->kategori ?? ['berita', 'pengumuman', 'tips', 'panduan'][$index % 4] }}"
                                data-views="{{ rand(100, 1000) }}"
                                data-created="{{ $info->created_at ?? date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) }}"
                                data-priority="medium" data-content="{{ $info->deskripsi ?? 'Lorem ipsum content...' }}"
                                data-excerpt="{{ Str::limit(strip_tags($info->deskripsi), 150) ?? 'Excerpt singkat...' }}"
                                data-tanggal-kegiatan="{{ $info->tanggal_kegiatan }}" data-lokasi="{{ $info->lokasi }}">

                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if (isset($info->foto))
                                                <img class="h-12 w-12 rounded-lg object-cover"
                                                    src="{{ asset('storage/informasi/' . $info->foto) }}" alt="">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 line-clamp-1">
                                                {{ $info->judul }}</div>
                                            <div class="text-sm text-gray-500 line-clamp-1">
                                                {{ Str::limit(strip_tags($info->deskripsi), 50) ?? 'Deskripsi tidak tersedia' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="kategori-badge kategori-{{ $info->kategori ?? ['berita', 'pengumuman', 'tips', 'panduan'][$index % 4] }}">
                                        {{ ucfirst($info->kategori ?? ['Berita', 'Pengumuman', 'Tips', 'Panduan'][$index % 4]) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ rand(100, 1000) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($info->tanggal_kegiatan)
                                        {{ \Carbon\Carbon::parse($info->tanggal_kegiatan)->format('d M Y') }}
                                    @else
                                        {{ date('d M Y', strtotime('-' . rand(1, 30) . ' days')) }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="openDetailModal(this)"
                                            class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button onclick="openEditModal(this)"
                                            class="action-btn bg-green-100 text-green-600 hover:bg-green-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button onclick="openDeleteModal('{{ $info->idInformasi ?? $index + 1 }}')"
                                            class="action-btn bg-red-100 text-red-600 hover:bg-red-200" title="Hapus"
                                            type="button">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center px-6 py-4 text-gray-500">Tidak ada data informasi
                                    tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if (isset($informasiList) && method_exists($informasiList, 'links'))
            <div class="flex justify-center">
                {{ $informasiList->links() }}
            </div>
        @else
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

    <!-- Add/Edit Informasi Modal -->
    <div id="addModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-5 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Tambah Informasi Baru</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="informasiForm" action="{{ route('informasi.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="informasiId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Masukkan judul informasi">
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="15"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Tulis deskripsi informasi di sini..."></textarea>
                        </div>
                    </div>

                    <!-- Sidebar Column -->
                    <div class="space-y-6">
                        <!-- Input Tanggal Kegiatan -->
                        <div>
                            <label for="tanggal_kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <!-- Input Lokasi -->
                        <div>
                            <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Lokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="lokasi" name="lokasi" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Contoh: Desa Mekarjaya, Kec. Lembang">
                        </div>

                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori" name="kategori" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Kategori</option>
                                <option value="berita">Berita</option>
                                <option value="pengumuman">Pengumuman</option>
                                <option value="tips">Tips & Trik</option>
                                <option value="panduan">Panduan</option>
                            </select>
                        </div>

                        <div>
                            <label for="idPenyuluh" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Penyuluh <span class="text-red-500">*</span>
                            </label>
                            <select id="idPenyuluh" name="idPenyuluh" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                @foreach ($penyuluhs as $penyuluh)
                                    <option value="{{ $penyuluh->idUser }}">{{ $penyuluh->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto
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
                                                upload</span></p>
                                        <p class="text-xs text-gray-500">PNG, JPG (MAX. 2MB)</p>
                                    </div>
                                    <input id="foto" name="foto" type="file" class="hidden" accept="image/*"
                                        onchange="previewFeaturedImage(this)">
                                </label>
                            </div>
                            <div id="imagePreview" class="mt-3 hidden">
                                <img id="preview" src="" alt="Preview"
                                    class="w-full h-32 object-cover rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeAddModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                            <span id="submitText">Simpan Informasi</span>
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
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Detail Informasi</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <div id="detailFeaturedImage" class="mb-4"></div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2" id="detailTitle">Judul Informasi</h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                            <span id="detailKategori" class="kategori-badge">Kategori</span>
                            <span id="detailStatus" class="status-badge">Status</span>
                            <span id="detailPriority" class="priority-badge">Priority</span>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-6">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span id="detailDate">Tanggal</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <span id="detailViews">0</span> views
                            </div>
                        </div>
                        <div id="detailContent" class="prose max-w-none">
                            <!-- Content will be loaded here -->
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Informasi Tambahan</h4>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-gray-600">ID:</span>
                                    <span id="detailId" class="font-medium">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Slug:</span>
                                    <span id="detailSlug" class="font-mono text-xs">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Author:</span>
                                    <span id="detailAuthor" class="font-medium">Admin</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
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
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Informasi</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus informasi <span id="deleteJudulInformasi"
                        class="font-semibold"></span>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="items-center px-4 py-3 mt-4 flex justify-center space-x-2">
                    <button id="confirmDeleteBtn" type="button" onclick="confirmDelete()"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 min-w-[100px] flex items-center justify-center">
                        <span id="deleteText">Hapus</span>
                        <svg id="deleteLoading" class="hidden animate-spin ml-2 h-4 w-4 text-white" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                    <button onclick="closeDeleteModal()" type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-900 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 min-w-[100px]">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Global variables
        let currentView = "grid";
        let deleteId = null;
        let currentInformasiData = null;
        let tinymceEditor = null;

        // Utility Functions
        const AppUtils = {
            // Show notification
            showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `notification ${type === 'success' ? 'bg-green-500' : 
                    type === 'error' ? 'bg-red-500' : 
                    type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'}`;
                notification.textContent = message;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            },

            // Format date
            formatDate(dateString) {
                try {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                } catch (e) {
                    return dateString;
                }
            },

            // Generate slug from title
            generateSlug(title) {
                return title.toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
            },

            // Get CSRF token
            getCsrfToken() {
                return document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
            }
        };

        // TinyMCE Editor
        const EditorManager = {
            init() {
                if (typeof tinymce !== 'undefined') {
                    tinymce.init({
                        selector: '#deskripsi',
                        height: 400,
                        menubar: false,
                        plugins: [
                            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                            'insertdatetime', 'media', 'table', 'help', 'wordcount'
                        ],
                        toolbar: 'undo redo | blocks | ' +
                            'bold italic forecolor | alignleft aligncenter ' +
                            'alignright alignjustify | bullist numlist outdent indent | ' +
                            'removeformat | help',
                        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                        setup: function(editor) {
                            tinymceEditor = editor;
                        }
                    });
                }
            },

            destroy() {
                if (tinymceEditor) {
                    tinymceEditor.destroy();
                    tinymceEditor = null;
                }
            },

            getContent() {
                return tinymceEditor ? tinymceEditor.getContent() : document.getElementById("deskripsi")?.value || '';
            },

            setContent(content) {
                if (tinymceEditor) {
                    tinymceEditor.setContent(content || '');
                } else {
                    const element = document.getElementById("deskripsi");
                    if (element) {
                        element.value = content || '';
                    }
                }
            }
        };

        // View Management
        const ViewManager = {
            switchView(view) {
                currentView = view;
                const gridView = document.getElementById("gridView");
                const tableView = document.getElementById("tableView");
                const gridBtn = document.getElementById("gridViewBtn");
                const tableBtn = document.getElementById("tableViewBtn");

                if (view === "grid") {
                    gridView?.classList.remove("hidden");
                    tableView?.classList.add("hidden");
                    gridBtn?.classList.add("view-btn-active", "bg-white", "text-gray-900", "shadow-sm");
                    gridBtn?.classList.remove("text-gray-600");
                    tableBtn?.classList.remove("view-btn-active", "bg-white", "text-gray-900", "shadow-sm");
                    tableBtn?.classList.add("text-gray-600");
                } else {
                    gridView?.classList.add("hidden");
                    tableView?.classList.remove("hidden");
                    tableBtn?.classList.add("view-btn-active", "bg-white", "text-gray-900", "shadow-sm");
                    tableBtn?.classList.remove("text-gray-600");
                    gridBtn?.classList.remove("view-btn-active", "bg-white", "text-gray-900", "shadow-sm");
                    gridBtn?.classList.add("text-gray-600");
                }
            }
        };

        // Filter and Search
        const FilterManager = {
            searchInformasi() {
                const searchTerm = document.getElementById("searchInput")?.value.toLowerCase() || '';
                this.filterElements('.informasi-card', (card) => {
                    const title = card.getAttribute("data-title")?.toLowerCase() || '';
                    return title.includes(searchTerm);
                });
                this.filterElements('.informasi-row', (row) => {
                    const title = row.getAttribute("data-title")?.toLowerCase() || '';
                    return title.includes(searchTerm);
                });
            },

            filterByKategori() {
                const kategoriFilter = document.getElementById("kategoriFilter")?.value || '';
                this.filterElements('.informasi-card', (card) => {
                    const kategori = card.getAttribute("data-kategori");
                    return !kategoriFilter || kategori === kategoriFilter;
                });
                this.filterElements('.informasi-row', (row) => {
                    const kategori = row.getAttribute("data-kategori");
                    return !kategoriFilter || kategori === kategoriFilter;
                });
            },

            filterElements(selector, predicate) {
                const elements = document.querySelectorAll(selector);
                elements.forEach((element) => {
                    const show = predicate(element);
                    element.style.display = show ?
                        (element.classList.contains('informasi-card') ? 'block' : 'table-row') : 'none';
                });
            }
        };

        // Modal Management
        const ModalManager = {
            openAddModal() {
                try {
                    const modalTitle = document.getElementById("modalTitle");
                    const form = document.getElementById("informasiForm");
                    const formMethod = document.getElementById("formMethod");
                    const informasiId = document.getElementById("informasiId");
                    const addModal = document.getElementById("addModal");

                    if (!addModal) {
                        console.error('Add modal not found');
                        AppUtils.showNotification('Modal tidak ditemukan', 'error');
                        return;
                    }

                    if (modalTitle) modalTitle.textContent = "Tambah Informasi Baru";
                    if (form) form.action = "/admin/store-informasi";
                    if (formMethod) formMethod.value = "POST";
                    if (informasiId) informasiId.value = "";

                    // Reset form
                    if (form) form.reset();
                    EditorManager.setContent('');

                    addModal.classList.remove("hidden");
                    document.body.style.overflow = "hidden";

                    setTimeout(() => {
                        document.getElementById("judul")?.focus();
                        if (!tinymceEditor) {
                            EditorManager.init();
                        }
                    }, 100);
                } catch (error) {
                    console.error('Error opening modal:', error);
                    AppUtils.showNotification('Terjadi kesalahan saat membuka modal', 'error');
                }
            },

            closeAddModal() {
                const addModal = document.getElementById("addModal");
                if (addModal) {
                    addModal.classList.add("hidden");
                }
                document.body.style.overflow = "auto";

                EditorManager.destroy();

                // Reset form and preview
                document.getElementById("informasiForm")?.reset();
                const imagePreview = document.getElementById("imagePreview");
                if (imagePreview) imagePreview.classList.add("hidden");
            },

            openDetailModal(element) {
                const data = this.getInformasiDataFromElement(element);
                if (!data) {
                    AppUtils.showNotification('Data informasi tidak ditemukan', 'error');
                    return;
                }

                const detailModal = document.getElementById("detailModal");
                if (!detailModal) {
                    console.error('Detail modal not found');
                    AppUtils.showNotification('Modal detail tidak ditemukan', 'error');
                    return;
                }

                currentInformasiData = data;

                // Set content
                this.setDetailContent(data);

                detailModal.classList.remove("hidden");
                document.body.style.overflow = "hidden";
            },

            closeDetailModal() {
                const detailModal = document.getElementById("detailModal");
                if (detailModal) {
                    detailModal.classList.add("hidden");
                }
                document.body.style.overflow = "auto";
                currentInformasiData = null;
            },

            openEditModal(element) {
                const data = this.getInformasiDataFromElement(element);
                if (!data) {
                    AppUtils.showNotification('Data informasi tidak ditemukan', 'error');
                    return;
                }

                const addModal = document.getElementById("addModal");
                if (!addModal) {
                    console.error('Edit modal not found');
                    AppUtils.showNotification('Modal edit tidak ditemukan', 'error');
                    return;
                }

                // Set modal title and form action
                const modalTitle = document.getElementById("modalTitle");
                const form = document.getElementById("informasiForm");
                const formMethod = document.getElementById("formMethod");
                const informasiId = document.getElementById("informasiId");

                if (modalTitle) modalTitle.textContent = "Edit Informasi";
                if (form) form.action = `/admin/informasi/${data.id}/update`;
                if (formMethod) formMethod.value = "PUT";
                if (informasiId) informasiId.value = data.id;

                // Populate form
                this.populateEditForm(data);

                addModal.classList.remove("hidden");
                document.body.style.overflow = "hidden";

                setTimeout(() => {
                    if (!tinymceEditor) {
                        EditorManager.init();
                    }

                    // Set content after TinyMCE is initialized
                    setTimeout(() => {
                        EditorManager.setContent(data.content);
                    }, 500);
                }, 100);
            },

            openDeleteModal(id) {
                const deleteModal = document.getElementById("deleteModal");
                if (!deleteModal) {
                    console.error('Delete modal not found');
                    AppUtils.showNotification('Modal hapus tidak ditemukan', 'error');
                    return;
                }

                deleteId = id;
                const element = document.querySelector(`[data-id="${id}"]`);
                const title = element ? element.getAttribute("data-title") : "informasi ini";
                const deleteTitle = document.getElementById("deleteJudulInformasi");
                if (deleteTitle) deleteTitle.textContent = title;

                deleteModal.classList.remove("hidden");
                document.body.style.overflow = "hidden";
            },

            closeDeleteModal() {
                const deleteModal = document.getElementById("deleteModal");
                if (deleteModal) {
                    deleteModal.classList.add("hidden");
                }
                document.body.style.overflow = "auto";
                deleteId = null;
            },

            getInformasiDataFromElement(element) {
                let parent = element.closest('[data-id]');
                if (!parent) {
                    parent = element.closest('.informasi-card, .informasi-row');
                }

                if (!parent) {
                    console.error('Parent element dengan data tidak ditemukan');
                    return null;
                }

                return {
                    id: parent.getAttribute("data-id"),
                    title: parent.getAttribute("data-title"),
                    kategori: parent.getAttribute("data-kategori"),
                    status: parent.getAttribute("data-status") || 'published',
                    priority: parent.getAttribute("data-priority") || 'medium',
                    views: parent.getAttribute("data-views"),
                    created: parent.getAttribute("data-created"),
                    content: parent.getAttribute("data-content"),
                    excerpt: parent.getAttribute("data-excerpt"),
                    featuredImage: parent.getAttribute("data-featured-image"),
                    tanggalKegiatan: parent.getAttribute("data-tanggal-kegiatan"),
                    lokasi: parent.getAttribute("data-lokasi")
                };
            },

            setDetailContent(data) {
                const elements = {
                    detailTitle: data.title || '-',
                    detailId: "#" + (data.id || '-'),
                    detailContent: data.content || 'Konten tidak tersedia',
                    detailViews: data.views || '0',
                    detailSlug: data.title ? AppUtils.generateSlug(data.title) : '-'
                };

                Object.keys(elements).forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        if (id === 'detailContent') {
                            element.innerHTML = elements[id];
                        } else {
                            element.textContent = elements[id];
                        }
                    }
                });

                // Set badges
                this.setBadgeContent('detailKategori', data.kategori, 'kategori-badge kategori-');
                this.setBadgeContent('detailStatus', data.status, 'status-badge status-');
                this.setBadgeContent('detailPriority', data.priority, 'priority-badge priority-');

                // Set date
                if (data.created && data.created !== '-') {
                    const dateElement = document.getElementById("detailDate");
                    if (dateElement) {
                        dateElement.textContent = AppUtils.formatDate(data.created);
                    }
                }

                // Set featured image
                const featuredImageContainer = document.getElementById("detailFeaturedImage");
                if (featuredImageContainer) {
                    if (data.featuredImage) {
                        featuredImageContainer.innerHTML =
                            `<img src="/storage/informasi/${data.featuredImage}" alt="Featured Image" class="w-full h-64 object-cover rounded-lg mb-4">`;
                    } else {
                        featuredImageContainer.innerHTML = '';
                    }
                }
            },

            setBadgeContent(elementId, value, classPrefix) {
                const element = document.getElementById(elementId);
                if (element && value) {
                    element.textContent = value.charAt(0).toUpperCase() + value.slice(1);
                    element.className = `${classPrefix}${value}`;
                }
            },

            populateEditForm(data) {
                const fields = {
                    judul: data.title || '',
                    deskripsi: data.content || '',
                    kategori: data.kategori || '',
                    tanggal_kegiatan: data.tanggalKegiatan || '',
                    lokasi: data.lokasi || ''
                };

                Object.keys(fields).forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.value = fields[id];
                });
            }
        };

        // Form Management
        const FormManager = {
            async handleFormSubmit(form) {
                if (!this.validateForm()) return;

                const submitBtn = document.getElementById("submitBtn");
                const submitText = document.getElementById("submitText");
                const submitLoading = document.getElementById("submitLoading");

                this.setSubmitState(true, submitBtn, submitText, submitLoading);

                const formData = new FormData(form);

                // Add TinyMCE content to form data
                const content = EditorManager.getContent();
                formData.set('deskripsi', content);

                try {
                    const response = await fetch(form.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": AppUtils.getCsrfToken(),
                        },
                    });

                    const data = await response.json();

                    if (data.success) {
                        ModalManager.closeAddModal();
                        AppUtils.showNotification("Data informasi berhasil disimpan!", 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        AppUtils.showNotification(data.message || "Terjadi kesalahan saat menyimpan data", 'error');
                    }
                } catch (error) {
                    console.error("Error:", error);
                    AppUtils.showNotification("Terjadi kesalahan saat menyimpan data", 'error');
                } finally {
                    this.setSubmitState(false, submitBtn, submitText, submitLoading);
                }
            },

            validateForm() {
                const required = ["judul", "kategori", "tanggal_kegiatan", "lokasi", "idPenyuluh"];
                let isValid = true;

                required.forEach((fieldId) => {
                    const field = document.getElementById(fieldId);
                    if (!field || !field.value.trim()) {
                        field?.classList.add("border-red-500");
                        isValid = false;
                    } else {
                        field?.classList.remove("border-red-500");
                    }
                });

                // Check content
                const content = EditorManager.getContent();
                if (!content || content.trim() === '') {
                    isValid = false;
                    AppUtils.showNotification('Harap isi deskripsi informasi', 'error');
                }

                if (!isValid) {
                    AppUtils.showNotification("Mohon lengkapi semua field yang wajib diisi (bertanda *)", 'error');
                }

                return isValid;
            },

            setSubmitState(loading, submitBtn, submitText, submitLoading) {
                if (submitBtn) submitBtn.disabled = loading;
                if (submitText) submitText.textContent = loading ? "Menyimpan..." : "Simpan Informasi";
                if (submitLoading) {
                    if (loading) {
                        submitLoading.classList.remove("hidden");
                    } else {
                        submitLoading.classList.add("hidden");
                    }
                }
            },

            async confirmDelete() {
                if (!deleteId) {
                    AppUtils.showNotification('ID informasi tidak ditemukan', 'error');
                    return;
                }

                const deleteBtn = document.getElementById("confirmDeleteBtn");
                const deleteText = document.getElementById("deleteText");
                const deleteLoading = document.getElementById("deleteLoading");

                this.setDeleteState(true, deleteBtn, deleteText, deleteLoading);

                try {
                    console.log('Deleting informasi with ID:', deleteId); // Debug log

                    const response = await fetch(`/admin/informasi/${deleteId}`, {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": AppUtils.getCsrfToken(),
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json"
                        }
                    });

                    console.log('Response status:', response.status); // Debug log

                    // Handle different response types
                    let responseData;
                    const contentType = response.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        responseData = await response.json();
                    } else {
                        // Jika response bukan JSON, ambil sebagai text
                        const textResponse = await response.text();
                        console.log('Non-JSON response:', textResponse);

                        // Coba parse sebagai JSON, jika gagal anggap error
                        try {
                            responseData = JSON.parse(textResponse);
                        } catch (e) {
                            throw new Error('Server returned non-JSON response: ' + textResponse);
                        }
                    }

                    console.log('Response data:', responseData); // Debug log

                    if (response.ok && responseData.success) {
                        ModalManager.closeDeleteModal();
                        AppUtils.showNotification(responseData.message || "Data berhasil dihapus!", 'success');

                        // Remove element from DOM immediately for better UX
                        const elementToRemove = document.querySelector(`[data-id="${deleteId}"]`);
                        if (elementToRemove) {
                            elementToRemove.style.opacity = '0.5';
                            setTimeout(() => {
                                elementToRemove.remove();
                            }, 300);
                        }

                        // Reload page after a short delay
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        throw new Error(responseData.message || `Server error: ${response.status}`);
                    }

                } catch (error) {
                    console.error("Delete error details:", error); // Debug log

                    let errorMessage = "Terjadi kesalahan yang tidak diketahui";

                    if (error.message) {
                        errorMessage = error.message;
                    } else if (error.responseJSON && error.responseJSON.message) {
                        errorMessage = error.responseJSON.message;
                    }

                    AppUtils.showNotification("Terjadi kesalahan: " + errorMessage, 'error');
                } finally {
                    this.setDeleteState(false, deleteBtn, deleteText, deleteLoading);
                }
            },

            setDeleteState(loading, deleteBtn, deleteText, deleteLoading) {
                if (deleteBtn) deleteBtn.disabled = loading;
                if (deleteText) deleteText.textContent = loading ? "Menghapus..." : "Hapus";
                if (deleteLoading) {
                    if (loading) {
                        deleteLoading.classList.remove("hidden");
                    } else {
                        deleteLoading.classList.add("hidden");
                    }
                }
            },
            previewFeaturedImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    const preview = document.getElementById("preview");
                    const imagePreview = document.getElementById("imagePreview");

                    reader.onload = function(e) {
                        if (preview) preview.src = e.target.result;
                        if (imagePreview) imagePreview.classList.remove("hidden");
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        };

        // Action Management
        const ActionManager = {
            duplicateInformasi(id) {
                if (!confirm('Apakah Anda yakin ingin menduplikasi informasi ini?')) {
                    return;
                }

                // Call duplicate endpoint
                fetch(`/admin/informasi/${id}/duplicate`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": AppUtils.getCsrfToken(),
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            AppUtils.showNotification('Informasi berhasil diduplikasi!', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            AppUtils.showNotification(data.message || 'Gagal menduplikasi informasi', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        AppUtils.showNotification('Terjadi kesalahan saat menduplikasi', 'error');
                    });
            }
        };

        // Event Management
        const EventManager = {
            init() {
                this.setupFormEvents();
                this.setupModalEvents();
                this.setupKeyboardEvents();
            },

            setupFormEvents() {
                const informasiForm = document.getElementById("informasiForm");
                if (informasiForm) {
                    informasiForm.addEventListener("submit", function(e) {
                        e.preventDefault();
                        FormManager.handleFormSubmit(this);
                    });
                }

                const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
                if (confirmDeleteBtn) {
                    confirmDeleteBtn.addEventListener("click", function(e) {
                        e.preventDefault();
                        FormManager.confirmDelete();
                    });
                }
            },

            setupModalEvents() {
                ["addModal", "detailModal", "deleteModal"].forEach((modalId) => {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.addEventListener("click", function(e) {
                            if (e.target === modal) {
                                // Close modal when clicking outside
                                if (modalId === "addModal") {
                                    ModalManager.closeAddModal();
                                } else if (modalId === "detailModal") {
                                    ModalManager.closeDetailModal();
                                } else if (modalId === "deleteModal") {
                                    ModalManager.closeDeleteModal();
                                }
                            }
                        });
                    }
                });
            },

            setupKeyboardEvents() {
                document.addEventListener("keydown", function(e) {
                    if (e.key === "Escape") {
                        // Check each modal individually and close if open
                        const addModal = document.getElementById("addModal");
                        if (addModal && !addModal.classList.contains("hidden")) {
                            ModalManager.closeAddModal();
                            return;
                        }

                        const detailModal = document.getElementById("detailModal");
                        if (detailModal && !detailModal.classList.contains("hidden")) {
                            ModalManager.closeDetailModal();
                            return;
                        }

                        const deleteModal = document.getElementById("deleteModal");
                        if (deleteModal && !deleteModal.classList.contains("hidden")) {
                            ModalManager.closeDeleteModal();
                            return;
                        }
                    }
                });
            }
        };

        // Global Functions (for onclick handlers)
        function switchView(view) {
            ViewManager.switchView(view);
        }

        function searchInformasi() {
            FilterManager.searchInformasi();
        }

        function filterByKategori() {
            FilterManager.filterByKategori();
        }

        function openAddModal() {
            ModalManager.openAddModal();
        }

        function closeAddModal() {
            ModalManager.closeAddModal();
        }

        function openDetailModal(element) {
            ModalManager.openDetailModal(element);
        }

        function closeDetailModal() {
            ModalManager.closeDetailModal();
        }

        function openEditModal(element) {
            ModalManager.openEditModal(element);
        }

        function openDeleteModal(id) {
            ModalManager.openDeleteModal(id);
        }

        function closeDeleteModal() {
            ModalManager.closeDeleteModal();
        }

        function previewFeaturedImage(input) {
            FormManager.previewFeaturedImage(input);
        }

        function duplicateInformasi(id) {
            ActionManager.duplicateInformasi(id);
        }

        function confirmDelete() {
            FormManager.confirmDelete();
        }

        // Initialize on DOM Content Loaded
        document.addEventListener("DOMContentLoaded", function() {
            try {
                // Initialize all managers
                EventManager.init();

                // Test button functionality
                const addButton = document.querySelector('button[onclick="openAddModal()"]');
                if (addButton) {
                    console.log('Add button found and ready');
                } else {
                    console.warn('Add button not found - this might be normal if not on the main page');
                }

                console.log('Informasi Umum Admin Panel initialized successfully');
            } catch (error) {
                console.error('Error initializing admin panel:', error);
            }
        });
    </script>
@endpush
