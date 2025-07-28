@extends('layouts.app')

@section('title', 'Kelola Informasi Pakan')
@section('page-title', 'Kelola Informasi Pakan')
@section('page-description', 'Kelola dan publikasikan informasi pakan untuk petani')

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

        .jenis-pakan-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .jenis-hijauan {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .jenis-konsentrat {
            background-color: #fef3c7;
            color: #d97706;
        }

        .jenis-fermentasi {
            background-color: #f3e8ff;
            color: #7c3aed;
        }

        .jenis-organik {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .jenis-limbah {
            background-color: #fecaca;
            color: #dc2626;
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
                        placeholder="Cari informasi pakan..." onkeyup="searchPakan()">
                </div>

                <!-- Jenis Pakan Filter -->
                <select id="jenisPakanFilter"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    onchange="filterByJenisPakan()">
                    <option value="">Semua Jenis Pakan</option>
                    <option value="hijauan">Hijauan</option>
                    <option value="konsentrat">Konsentrat</option>
                    <option value="a">Fermentasi</option>
                    <option value="organik">Organik</option>
                    <option value="limbah">Limbah Pertanian</option>
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
                    Tambah Informasi Pakan
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Informasi Pakan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $informasiPakan ?? 28 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Published</p>
                        <p class="text-2xl font-bold text-green-600">{{ $publishedPakan ?? 22 }}</p>
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
                        <p class="text-2xl font-bold text-yellow-600">{{ $draftPakan ?? 6 }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Jenis Pakan</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $jenisPakanCount ?? 5 }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
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
                Menampilkan {{ $pakanList->count() ?? 10 }} dari {{ $totalPakan ?? 28 }} informasi pakan
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($pakanList as $index => $pakan)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover pakan-card"
                    data-title="{{ $pakan->judul ?? 'Informasi Pakan ' . sprintf('%03d', $index + 1) }}"
                    data-status="{{ $pakan->is_published ? 'published' : 'draft' }}"
                    data-id="{{ $pakan->idPakan ?? $index + 1 }}"
                    data-jenis-pakan="{{ $pakan->jenis_pakan ?? ['hijauan', 'konsentrat', 'fermentasi', 'organik', 'limbah'][$index % 5] }}"
                    data-created="{{ $pakan->created_at ?? date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) }}"
                    data-content="{{ $pakan->deskripsi ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}"
                    data-excerpt="{{ Str::limit(strip_tags($pakan->deskripsi), 150) ?? 'Excerpt singkat dari informasi pakan ini...' }}"
                    data-featured-image="{{ $pakan->foto }}" data-sumber="{{ $pakan->sumber }}">

                    <div class="relative">
                        @if (isset($pakan->foto) && $pakan->foto)
                            <img src="{{ asset('storage/informasi-pakan/' . $pakan->foto) }}" alt="Featured Image"
                                class="w-full h-48 object-cover rounded-t-xl">
                        @else
                            <div
                                class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 rounded-t-xl flex items-center justify-center">
                                <svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span
                                class="jenis-pakan-badge jenis-{{ $pakan->jenis_pakan ?? ['hijauan', 'konsentrat', 'fermentasi', 'organik', 'limbah'][$index % 5] }}">
                                {{ ucfirst($pakan->jenis_pakan ?? ['Hijauan', 'Konsentrat', 'Fermentasi', 'Organik', 'Limbah'][$index % 5]) }}
                            </span>
                            <span class="status-badge status-{{ $pakan->is_published ? 'published' : 'draft' }}">
                                {{ $pakan->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                {{ $pakan->judul ?? 'Informasi Pakan ' . sprintf('%03d', $index + 1) }}
                            </h3>
                            <p class="text-sm text-gray-600 content-preview">
                                {{ Str::limit(strip_tags($pakan->deskripsi), 150) ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.' }}
                            </p>
                            @if ($pakan->sumber)
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                    Sumber: {{ $pakan->sumber }}
                                </div>
                            @endif
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
                                <button onclick="openDeleteModal('{{ $pakan->idPakan ?? $index + 1 }}')"
                                    class="action-btn bg-red-100 text-red-600 hover:bg-red-200" title="Hapus"
                                    type="button">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                {{ ucfirst($pakan->jenis_pakan ?? 'Pakan') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">
                    Tidak ada data informasi pakan tersedia.
                </div>
            @endforelse
        </div>

        <!-- Table View -->
        <div id="tableView" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="table-header px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Informasi Pakan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Informasi Pakan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis Pakan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sumber</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                        @forelse($pakanList as $index => $pakan)
                            <tr class="hover:bg-gray-50 pakan-row" data-title="{{ $pakan->judul }}"
                                data-status="{{ $pakan->is_published ? 'published' : 'draft' }}"
                                data-id="{{ $pakan->idPakan ?? $index + 1 }}"
                                data-jenis-pakan="{{ $pakan->jenis_pakan ?? ['hijauan', 'konsentrat', 'fermentasi', 'organik', 'limbah'][$index % 5] }}"
                                data-created="{{ $pakan->created_at ?? date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) }}"
                                data-content="{{ $pakan->deskripsi ?? 'Lorem ipsum content...' }}"
                                data-excerpt="{{ Str::limit(strip_tags($pakan->deskripsi), 150) ?? 'Excerpt singkat...' }}"
                                data-featured-image="{{ $pakan->foto }}" data-sumber="{{ $pakan->sumber }}">

                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if (isset($pakan->foto))
                                                <img class="h-12 w-12 rounded-lg object-cover"
                                                    src="{{ asset('storage/informasi-pakan/' . $pakan->foto) }}" alt="">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-lg bg-green-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-green-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 line-clamp-1">
                                                {{ $pakan->judul }}</div>
                                            <div class="text-sm text-gray-500 line-clamp-1">
                                                {{ Str::limit(strip_tags($pakan->deskripsi), 50) ?? 'Deskripsi tidak tersedia' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="jenis-pakan-badge jenis-{{ $pakan->jenis_pakan ?? ['hijauan', 'konsentrat', 'fermentasi', 'organik', 'limbah'][$index % 5] }}">
                                        {{ ucfirst($pakan->jenis_pakan ?? ['Hijauan', 'Konsentrat', 'Fermentasi', 'Organik', 'Limbah'][$index % 5]) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge status-{{ $pakan->is_published ? 'published' : 'draft' }}">
                                        {{ $pakan->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pakan->sumber ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($pakan->created_at)
                                        {{ \Carbon\Carbon::parse($pakan->created_at)->format('d M Y') }}
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
                                        <button onclick="openDeleteModal('{{ $pakan->idPakan ?? $index + 1 }}')"
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
                                    pakan
                                    tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if (isset($pakanList) && method_exists($pakanList, 'links'))
            <div class="flex justify-center">
                {{ $pakanList->links() }}
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

    <!-- Add/Edit Pakan Modal -->
    <div id="addModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-5 mx-auto p-5 border w-full max-w-5xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Tambah Informasi Pakan Baru</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="pakanForm" action="{{ route('pakan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="pakanId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Informasi Pakan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Contoh: Cara Membuat Pakan Fermentasi Jerami">
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="15"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Tulis deskripsi lengkap tentang informasi pakan di sini..."></textarea>
                        </div>
                    </div>

                    <!-- Sidebar Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="jenis_pakan" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Pakan <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_pakan" name="jenis_pakan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Jenis Pakan</option>
                                <option value="hijauan">Hijauan</option>
                                <option value="konsentrat">Konsentrat</option>
                                <option value="fermentasi">Fermentasi</option>
                                <option value="organik">Organik</option>
                                <option value="limbah">Limbah Pertanian</option>
                            </select>
                        </div>

                        <div>
                            <label for="sumber" class="block text-sm font-medium text-gray-700 mb-2">
                                Sumber Informasi
                            </label>
                            <input type="text" id="sumber" name="sumber"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Contoh: Balai Penelitian Ternak, BPTP">
                        </div>

                        <div>
                            <label for="is_published" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Publikasi <span class="text-red-500">*</span>
                            </label>
                            <select id="is_published" name="is_published" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="1">Publish</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Pakan
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
                            <span id="submitText">Simpan Informasi Pakan</span>
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
                <h3 class="text-xl font-semibold text-gray-900">Detail Informasi Pakan</h3>
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
                        <h1 class="text-2xl font-bold text-gray-900 mb-2" id="detailTitle">Judul Informasi Pakan</h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                            <span id="detailJenisPakan" class="jenis-pakan-badge">Jenis Pakan</span>
                            <span id="detailStatus" class="status-badge">Status</span>
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
                            <div class="flex items-center" id="detailSumberContainer">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                                Sumber: <span id="detailSumber">-</span>
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
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Informasi Pakan</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus informasi pakan <span id="deleteJudulPakan"
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
        let currentPakanData = null;
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
            searchPakan() {
                const searchTerm = document.getElementById("searchInput")?.value.toLowerCase() || '';
                this.filterElements('.pakan-card', (card) => {
                    const title = card.getAttribute("data-title")?.toLowerCase() || '';
                    return title.includes(searchTerm);
                });
                this.filterElements('.pakan-row', (row) => {
                    const title = row.getAttribute("data-title")?.toLowerCase() || '';
                    return title.includes(searchTerm);
                });
            },

            filterByJenisPakan() {
                const jenisPakanFilter = document.getElementById("jenisPakanFilter")?.value || '';
                this.filterElements('.pakan-card', (card) => {
                    const jenisPakan = card.getAttribute("data-jenis-pakan");
                    return !jenisPakanFilter || jenisPakan === jenisPakanFilter;
                });
                this.filterElements('.pakan-row', (row) => {
                    const jenisPakan = row.getAttribute("data-jenis-pakan");
                    return !jenisPakanFilter || jenisPakan === jenisPakanFilter;
                });
            },

            filterElements(selector, predicate) {
                const elements = document.querySelectorAll(selector);
                elements.forEach((element) => {
                    const show = predicate(element);
                    element.style.display = show ?
                        (element.classList.contains('pakan-card') ? 'block' : 'table-row') : 'none';
                });
            }
        };

        // Modal Management
        const ModalManager = {
            openAddModal() {
                try {
                    const modalTitle = document.getElementById("modalTitle");
                    const form = document.getElementById("pakanForm");
                    const formMethod = document.getElementById("formMethod");
                    const pakanId = document.getElementById("pakanId");
                    const addModal = document.getElementById("addModal");

                    if (!addModal) {
                        console.error('Add modal not found');
                        AppUtils.showNotification('Modal tidak ditemukan', 'error');
                        return;
                    }

                    if (modalTitle) modalTitle.textContent = "Tambah Informasi Pakan Baru";
                    if (form) form.action = "/admin/store-pakan";
                    if (formMethod) formMethod.value = "POST";
                    if (pakanId) pakanId.value = "";

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
                document.getElementById("pakanForm")?.reset();
                const imagePreview = document.getElementById("imagePreview");
                if (imagePreview) imagePreview.classList.add("hidden");
            },

            openDetailModal(element) {
                const data = this.getPakanDataFromElement(element);
                if (!data) {
                    AppUtils.showNotification('Data informasi pakan tidak ditemukan', 'error');
                    return;
                }

                const detailModal = document.getElementById("detailModal");
                if (!detailModal) {
                    console.error('Detail modal not found');
                    AppUtils.showNotification('Modal detail tidak ditemukan', 'error');
                    return;
                }

                currentPakanData = data;

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
                currentPakanData = null;
            },

            openEditModal(element) {
                const data = this.getPakanDataFromElement(element);
                if (!data) {
                    AppUtils.showNotification('Data informasi pakan tidak ditemukan', 'error');
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
                const form = document.getElementById("pakanForm");
                const formMethod = document.getElementById("formMethod");
                const pakanId = document.getElementById("pakanId");

                if (modalTitle) modalTitle.textContent = "Edit Informasi Pakan";
                if (form) form.action = `/admin/pakan/${data.id}/update`;
                if (formMethod) formMethod.value = "PUT";
                if (pakanId) pakanId.value = data.id;

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
                const title = element ? element.getAttribute("data-title") : "informasi pakan ini";
                const deleteTitle = document.getElementById("deleteJudulPakan");
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

            getPakanDataFromElement(element) {
                let parent = element.closest('[data-id]');
                if (!parent) {
                    parent = element.closest('.pakan-card, .pakan-row');
                }

                if (!parent) {
                    console.error('Parent element dengan data tidak ditemukan');
                    return null;
                }

                return {
                    id: parent.getAttribute("data-id"),
                    title: parent.getAttribute("data-title"),
                    jenisPakan: parent.getAttribute("data-jenis-pakan"),
                    status: parent.getAttribute("data-status") || 'published',
                    created: parent.getAttribute("data-created"),
                    content: parent.getAttribute("data-content"),
                    excerpt: parent.getAttribute("data-excerpt"),
                    featuredImage: parent.getAttribute("data-featured-image"),
                    sumber: parent.getAttribute("data-sumber")
                };
            },

            setDetailContent(data) {
                const elements = {
                    detailTitle: data.title || '-',
                    detailId: "#" + (data.id || '-'),
                    detailContent: data.content || 'Konten tidak tersedia',
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
                this.setBadgeContent('detailJenisPakan', data.jenisPakan, 'jenis-pakan-badge jenis-');
                this.setBadgeContent('detailStatus', data.status, 'status-badge status-');

                // Set date
                if (data.created && data.created !== '-') {
                    const dateElement = document.getElementById("detailDate");
                    if (dateElement) {
                        dateElement.textContent = AppUtils.formatDate(data.created);
                    }
                }

                // Set sumber
                const sumberContainer = document.getElementById("detailSumberContainer");
                const sumberElement = document.getElementById("detailSumber");
                if (data.sumber && data.sumber !== '-') {
                    if (sumberElement) sumberElement.textContent = data.sumber;
                    if (sumberContainer) sumberContainer.style.display = 'flex';
                } else {
                    if (sumberContainer) sumberContainer.style.display = 'none';
                }

                // Set featured image
                const featuredImageContainer = document.getElementById("detailFeaturedImage");
                if (featuredImageContainer) {
                    if (data.featuredImage) {
                        featuredImageContainer.innerHTML =
                            `<img src="/storage/informasi-pakan/${data.featuredImage}" alt="Featured Image" class="w-full h-64 object-cover rounded-lg mb-4">`;
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
                    jenis_pakan: data.jenisPakan || '',
                    sumber: data.sumber || '',
                    is_published: data.status === 'published' ? '1' : '0'
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
                        AppUtils.showNotification("Data informasi pakan berhasil disimpan!", 'success');
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
                const required = ["judul", "jenis_pakan", "is_published"];
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
                    AppUtils.showNotification('Harap isi deskripsi informasi pakan', 'error');
                }

                if (!isValid) {
                    AppUtils.showNotification("Mohon lengkapi semua field yang wajib diisi (bertanda *)", 'error');
                }

                return isValid;
            },

            setSubmitState(loading, submitBtn, submitText, submitLoading) {
                if (submitBtn) submitBtn.disabled = loading;
                if (submitText) submitText.textContent = loading ? "Menyimpan..." : "Simpan Informasi Pakan";
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
                    AppUtils.showNotification('ID informasi pakan tidak ditemukan', 'error');
                    return;
                }

                const deleteBtn = document.getElementById("confirmDeleteBtn");
                const deleteText = document.getElementById("deleteText");
                const deleteLoading = document.getElementById("deleteLoading");

                this.setDeleteState(true, deleteBtn, deleteText, deleteLoading);

                try {
                    console.log('Deleting pakan with ID:', deleteId); // Debug log

                    const response = await fetch(`/admin/pakan/${deleteId}`, {
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

        // Event Management
        const EventManager = {
            init() {
                this.setupFormEvents();
                this.setupModalEvents();
                this.setupKeyboardEvents();
            },

            setupFormEvents() {
                const pakanForm = document.getElementById("pakanForm");
                if (pakanForm) {
                    pakanForm.addEventListener("submit", function(e) {
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

        function searchPakan() {
            FilterManager.searchPakan();
        }

        function filterByJenisPakan() {
            FilterManager.filterByJenisPakan();
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

                console.log('Informasi Pakan Admin Panel initialized successfully');
            } catch (error) {
                console.error('Error initializing admin panel:', error);
            }
        });
    </script>
@endpush
