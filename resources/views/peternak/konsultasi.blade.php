@extends('layouts.app')

@section('title', 'Konsultasi Ternak')
@section('page-title', 'Konsultasi Ternak')
@section('page-description', 'Konsultasi dengan ahli dan dokter hewan untuk ternak Anda')

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

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-berlangsung {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-selesai {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .status-dibatalkan {
            background-color: #fecaca;
            color: #991b1b;
        }

        .expert-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .chat-message {
            max-width: 70%;
            word-wrap: break-word;
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

        .rating-star {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .rating-star:hover,
        .rating-star.active {
            color: #fbbf24;
            transform: scale(1.1);
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

        .category-tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .chat-container {
            height: 400px;
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

        .expert-card {
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .expert-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .expert-card.selected {
            border-color: #667eea;
            background: #f8faff;
        }

        .attachment-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
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
                        placeholder="Cari konsultasi..." onkeyup="searchKonsultasi()">
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <select id="statusFilter" onchange="filterByStatus()"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="berlangsung">Berlangsung</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>

                <select id="categoryFilter" onchange="filterByCategory()"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    <option value="kesehatan">Kesehatan</option>
                    <option value="nutrisi">Nutrisi</option>
                    <option value="breeding">Breeding</option>
                    <option value="perawatan">Perawatan</option>
                    <option value="umum">Umum</option>
                </select>

                <button onclick="openKonsultasiModal()"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transform hover:scale-105 transition-all">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    Konsultasi Baru
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Konsultasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalKonsultasi ?? 24 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Berlangsung</p>
                        <p class="text-2xl font-bold text-green-600">{{ $konsultasiBerlangsung ?? 3 }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Selesai</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $konsultasiSelesai ?? 18 }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $konsultasiPending ?? 3 }}</p>
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
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Konsultasi</h3>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse ($konsultasis ?? [] as $item)
                    <div class="p-6 hover:bg-gray-50 transition-colors konsultasi-item"
                        data-judul="{{ $item->judul_konsultasi }}" data-kategori="{{ $item->kategori }}"
                        data-jenis="{{ $item->ternak->jenis }}" data-status="{{ $item->ternak->status }}"
                        data-status="{{ $item->status }}" data-id="{{ $item->idKonsultasi }}"
                        data-penyuluh="{{ $item->penyuluh ? $item->penyuluh->nama : 'Belum ditentukan' }}"
                        data-ternak="{{ $item->ternak ? $item->ternak->namaTernak : 'Ternak tidak ditemukan' }}"
                        data-ternak-id="{{ $item->ternak ? $item->ternak->idTernak : 'N/A' }}"
                        data-deskripsi="{{ $item->deskripsi }}" data-foto="{{ $item->foto_ternak }}"
                        data-created="{{ $item->created_at->format('d M Y H:i') }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $item->judul_konsultasi }}</h4>
                                    <span class="category-tag">{{ ucfirst($item->kategori) }}</span>
                                    <span class="status-badge status-{{ $item->status }}">
                                        @switch($item->status)
                                            @case('pending')
                                                Pending
                                            @break

                                            @case('berlangsung')
                                                Berlangsung
                                            @break

                                            @case('selesai')
                                                Selesai
                                            @break

                                            @case('dibatalkan')
                                                Dibatalkan
                                            @break
                                        @endswitch
                                    </span>
                                </div>

                                <div class="flex items-center space-x-6 text-sm text-gray-600 mb-3">
                                    <div class="flex items-center space-x-2">
                                        @if ($item->penyuluh)
                                        @else
                                            <div class="flex items-center space-x-2">
                                                <div
                                                    class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <span class="font-medium text-gray-500">Belum ada penyuluh</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        @if ($item->ternak)
                                            @php
                                                $animalEmoji = match ($item->ternak->jenis_ternak ?? 'sapi') {
                                                    'sapi' => '🐄',
                                                    'kambing' => '🐐',
                                                    'domba' => '🐑',
                                                    'kerbau' => '🐃',
                                                    default => '🐄',
                                                };
                                            @endphp
                                            <span class="text-2xl">{{ $animalEmoji }}</span>
                                            <span>{{ $item->ternak->namaTernak ?? 'Ternak #' . $item->idTernak }}</span>
                                        @else
                                            <span class="text-2xl">🐄</span>
                                            <span>Ternak tidak ditemukan</span>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-gray-600 text-sm">{{ Str::limit($item->deskripsi, 100) }}</p>
                            </div>

                            <div class="flex items-center space-x-3">
                                {{-- Placeholder untuk unread count - bisa ditambahkan logic jika ada tabel chat --}}
                                @php $unreadCount = 0; @endphp
                                @if ($unreadCount > 0)
                                    <span
                                        class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif

                                <div class="flex space-x-2">
                                    @if ($item->status === 'berlangsung')
                                        <button onclick="openChatModal({{ $item->idKonsultasi }})"
                                            class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition-colors">
                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                </path>
                                            </svg>
                                            Chat
                                        </button>
                                    @elseif ($item->status === 'selesai')
                                        <button onclick="openRatingModal({{ $item->idKonsultasi }})"
                                            class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg text-sm font-medium hover:bg-purple-200 transition-colors">
                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                </path>
                                            </svg>
                                            Rating
                                        </button>
                                    @endif

                                    <button onclick="openDetailModal({{ $item->idKonsultasi }})"
                                        class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <p class="text-lg font-medium">Belum ada konsultasi</p>
                            <p class="text-sm">Mulai konsultasi pertama Anda dengan ahli ternak</p>
                            <button onclick="openKonsultasiModal()"
                                class="mt-4 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition-colors">
                                Mulai Konsultasi
                            </button>
                        </div>
                    @endforelse
                </div>

                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Menampilkan 1-3 dari 24 konsultasi</p>
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
                </div>
            </div>
        </div>

        <!-- Modal Konsultasi Baru -->
        <div id="konsultasiModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white modal-content">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">Konsultasi Baru</h3>
                    <button onclick="closeKonsultasiModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form id="konsultasiForm" action="{{ route('konsultasi.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden field untuk idPeternak (dari user yang login) -->
                    <input type="hidden" name="idPeternak" value="{{ auth()->user()->idUser }}">
                    <!-- Hidden field untuk status (default pending) -->
                    <input type="hidden" name="status" value="pending">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2">
                            <label for="judulKonsultasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Konsultasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judulKonsultasi" name="judul_konsultasi" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Contoh: Sapi mengalami demam tinggi dan nafsu makan menurun">
                        </div>

                        <div>
                            <label for="kategoriKonsultasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategoriKonsultasi" name="kategori" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Kategori</option>
                                <option value="kesehatan">Kesehatan</option>
                                <option value="nutrisi">Nutrisi</option>
                                <option value="breeding">Breeding</option>
                                <option value="perawatan">Perawatan</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>

                        <div>
                            <label for="ternakTerkait" class="block text-sm font-medium text-gray-700 mb-2">
                                Ternak Terkait <span class="text-red-500">*</span>
                            </label>
                            <select id="ternakTerkait" name="idTernak" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Ternak</option>
                                @if (isset($ternakList))
                                    @foreach ($ternakList as $ternak)
                                        <option value="{{ $ternak->idTernak }}">{{ $ternak->namaTernak }}                                            
                                        </option>
                                    @endforeach
                                @else
                                    <!-- Sample data jika $ternakList tidak tersedia -->
                                    <option value="1">Sapi Limosin #001 - Makmur</option>
                                    <option value="2">Sapi Brahman #002 - Sejahtera</option>
                                    <option value="3">Kambing Boer #003 - Berkah</option>
                                    <option value="4">Domba Garut #004 - Rezeki</option>
                                    <option value="5">Sapi Angus #005 - Jaya</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label for="deskripsiKonsultasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Detail <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsiKonsultasi" name="deskripsi" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                placeholder="Jelaskan detail masalah, gejala yang diamati, sudah berapa lama terjadi, dan hal-hal yang sudah dilakukan..."></textarea>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label for="fotoKonsultasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Pendukung
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="fotoKonsultasi"
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
                                    <input id="fotoKonsultasi" name="foto_ternak" type="file" class="hidden"
                                        accept="image/*" onchange="previewKonsultasiImage(this)">
                                </label>
                            </div>
                            <div id="konsultasiImagePreview" class="mt-3 hidden"></div>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Penyuluh/Ahli <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="expertSelection">
                                @if (isset($penyuluhList))
                                    @foreach ($penyuluhList as $penyuluh)
                                        <div class="expert-card p-4 border rounded-lg cursor-pointer"
                                            onclick="selectExpert({{ $penyuluh->idUser }}, this)">
                                            <input type="radio" name="idPenyuluh" value="{{ $penyuluh->idUser }}"
                                                class="hidden">
                                            <div class="flex items-center space-x-3">                                                
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900">{{ $penyuluh->name }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $penyuluh->role }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $penyuluh->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Sample data jika $penyuluhList tidak tersedia -->
                                    <div class="expert-card p-4 border rounded-lg cursor-pointer"
                                        onclick="selectExpert(1, this)">
                                        <input type="radio" name="idPenyuluh" value="1" class="hidden">
                                        <div class="flex items-center space-x-3">
                                            <img src="https://ui-avatars.io/api/?name=Dr+Budi+Santoso&background=667eea&color=ffffff"
                                                alt="Dr. Budi Santoso" class="w-12 h-12 rounded-full">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">Dr. Budi Santoso</h4>
                                                <p class="text-sm text-gray-600">Dokter Hewan Spesialis</p>
                                                <p class="text-xs text-gray-500 mt-1">Spesialis Kesehatan & Penyakit</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="expert-card p-4 border rounded-lg cursor-pointer"
                                        onclick="selectExpert(2, this)">
                                        <input type="radio" name="idPenyuluh" value="2" class="hidden">
                                        <div class="flex items-center space-x-3">
                                            <img src="https://ui-avatars.io/api/?name=Prof+Dr+Sari+Widiarti&background=10b981&color=ffffff"
                                                alt="Prof. Dr. Sari Widiarti" class="w-12 h-12 rounded-full">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">Prof. Dr. Sari Widiarti</h4>
                                                <p class="text-sm text-gray-600">Ahli Nutrisi Ternak</p>
                                                <p class="text-xs text-gray-500 mt-1">Spesialis Nutrisi & Pakan</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeKonsultasiModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="konsultasiSubmitBtn"
                            class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                            <span id="konsultasiSubmitText">Kirim Konsultasi</span>
                            <svg id="konsultasiSubmitLoading" class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
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

        <!-- Modal Detail Konsultasi -->
        <div id="detailModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">Detail Konsultasi</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Header Info -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 id="detailJudul" class="text-xl font-bold text-gray-900 mb-2"></h4>
                                <div class="flex items-center space-x-4 mb-3">
                                    <span id="detailKategori" class="category-tag"></span>
                                    <span id="detailStatus" class="status-badge"></span>
                                </div>
                                <p id="detailIdKonsultasi" class="text-xs text-gray-500 font-mono"></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Main Content -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Deskripsi -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Deskripsi Masalah
                                </h5>
                                <div id="detailDeskripsi" class="text-gray-700 leading-relaxed"></div>
                            </div>

                            <!-- Foto Ternak -->
                            <div id="detailFotoContainer" class="bg-white border border-gray-200 rounded-lg p-6 hidden">
                                <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Foto Pendukung
                                </h5>
                                <div class="flex justify-center">
                                    <img id="detailFoto" src="" alt="Foto Ternak"
                                        class="max-w-full max-h-96 rounded-lg shadow-md object-cover">
                                </div>
                            </div>

                        </div>

                        <!-- Right Column - Sidebar Info -->
                        <div class="space-y-6">
                            <!-- Info Penyuluh -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Penyuluh/Ahli
                                </h5>
                                <div id="detailPenyuluh" class="flex items-center space-x-3 mb-4">

                                </div>
                            </div>

                            <!-- Info Ternak -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <span id="detailTernakEmoji" class="text-2xl mr-2">🐄</span>
                                    Info Ternak
                                </h5>
                                <div id="detailTernak" class="space-y-3">
                                    <!-- Ternak info will be populated here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            let currentRating = 0;
            let currentConsultationId = null;
            let chatAttachments = [];

            function searchKonsultasi() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const items = document.querySelectorAll('.konsultasi-item');

                items.forEach(item => {
                    const judul = item.getAttribute('data-judul').toLowerCase();
                    item.style.display = judul.includes(searchTerm) ? 'block' : 'none';
                });
            }

            function filterByStatus() {
                const statusFilter = document.getElementById('statusFilter').value;
                const items = document.querySelectorAll('.konsultasi-item');

                items.forEach(item => {
                    const status = item.getAttribute('data-status');
                    item.style.display = (!statusFilter || status === statusFilter) ? 'block' : 'none';
                });
            }

            function filterByCategory() {
                const categoryFilter = document.getElementById('categoryFilter').value;
                const items = document.querySelectorAll('.konsultasi-item');

                items.forEach(item => {
                    const kategori = item.getAttribute('data-kategori');
                    item.style.display = (!categoryFilter || kategori === categoryFilter) ? 'block' : 'none';
                });
            }

            function openKonsultasiModal() {
                document.getElementById('konsultasiModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeKonsultasiModal() {
                document.getElementById('konsultasiModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                document.getElementById('konsultasiForm').reset();
                document.getElementById('konsultasiImagePreview').classList.add('hidden');
                document.querySelectorAll('.expert-card').forEach(card => card.classList.remove('selected'));
            }

            function previewKonsultasiImage(input) {
                const preview = document.getElementById('konsultasiImagePreview');
                preview.innerHTML = '';

                if (input.files && input.files[0]) {
                    preview.classList.remove('hidden');

                    const file = input.files[0];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'attachment-preview border border-gray-300';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add('hidden');
                }
            }

            function selectExpert(expertId, element) {
                document.querySelectorAll('.expert-card').forEach(card => card.classList.remove('selected'));
                element.classList.add('selected');
                element.querySelector('input[type="radio"]').checked = true;
            }

            function openChatModal(consultationId) {
                currentConsultationId = consultationId;

                const experts = {
                    1: {
                        name: 'Dr. Budi Santoso',
                        avatar: 'https://ui-avatars.io/api/?name=Dr+Budi+Santoso&background=667eea&color=ffffff',
                        judul: 'Sapi mengalami demam tinggi'
                    }
                };

                const expert = experts[consultationId] || experts[1];

                document.getElementById('chatExpertName').textContent = expert.name;
                document.getElementById('chatExpertAvatar').src = expert.avatar;
                document.getElementById('chatKonsultasiJudul').textContent = expert.judul;

                loadChatMessages(consultationId);
                document.getElementById('chatModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeChatModal() {
                document.getElementById('chatModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentConsultationId = null;
            }

            function openDetailModal(consultationId) {
                const konsultasiItem = document.querySelector(`[data-id="${consultationId}"]`);

                if (!konsultasiItem) {
                    alert('Data konsultasi tidak ditemukan');
                    return;
                }

                // Extract data from the item
                const judul = konsultasiItem.getAttribute('data-judul');
                const kategori = konsultasiItem.getAttribute('data-kategori');
                const penyuluh = konsultasiItem.getAttribute('data-penyuluh');
                const ternak = konsultasiItem.getAttribute('data-ternak');
                const jenis = konsultasiItem.getAttribute('data-jenis');
                const status = konsultasiItem.getAttribute('data-status');
                const ternakId = konsultasiItem.getAttribute('data-ternak-id');
                const deskripsi = konsultasiItem.getAttribute('data-deskripsi');
                const foto = konsultasiItem.getAttribute('data-foto');
                const created = konsultasiItem.getAttribute('data-created');

                // Populate modal header
                document.getElementById('detailJudul').textContent = judul;
                document.getElementById('detailKategori').textContent = kategori.charAt(0).toUpperCase() + kategori.slice(1);
                document.getElementById('detailKategori').className = 'category-tag';

                // Set status badge
                const statusBadge = document.getElementById('detailStatus');
                statusBadge.className = `status-badge status-${status}`;
                statusBadge.textContent = getStatusText(status);

                document.getElementById('detailIdKonsultasi').textContent = `ID Konsultasi: #${consultationId}`;

                // Populate description
                document.getElementById('detailDeskripsi').innerHTML = `
                <p class="whitespace-pre-wrap">${deskripsi}</p>
            `;

                // Handle foto ternak
                const fotoContainer = document.getElementById('detailFotoContainer');
                const fotoElement = document.getElementById('detailFoto');

                if (foto && foto !== 'null' && foto !== '') {
                    fotoElement.src = `/storage/foto_ternak/${foto}`;
                    fotoElement.alt = `Foto ${ternak}`;
                    fotoContainer.classList.remove('hidden');
                } else {
                    fotoContainer.classList.add('hidden');
                }

                // Populate penyuluh info
                const penyuluhContainer = document.getElementById('detailPenyuluh');
                penyuluhContainer.innerHTML = `                
                <div>
                    <h6 class="font-semibold text-gray-900">${penyuluh}</h6>
                    <p class="text-sm text-gray-600">Penyuluh</p>
                  
                </div>
            `;



                // Populate ternak info
                const ternakEmoji = getTernakEmoji(ternak);
                document.getElementById('detailTernakEmoji').textContent = ternakEmoji;

                const ternakContainer = document.getElementById('detailTernak');
                ternakContainer.innerHTML = `
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium">${ternak}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Ternak:</span>
                        <span class="font-medium">#${ternakId}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis:</span>
                        <span class="font-medium">${jenis}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="text-green-600 font-medium capitalize">${status}</span>
                    </div>
                </div>
            `;

                // Show modal
                document.getElementById('detailModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeDetailModal() {
                document.getElementById('detailModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function getStatusText(status) {
                const statusMap = {
                    'pending': 'Pending',
                    'berlangsung': 'Berlangsung',
                    'selesai': 'Selesai',
                    'dibatalkan': 'Dibatalkan'
                };
                return statusMap[status] || status;
            }

            function getTernakEmoji(ternakName) {
                const lowercaseName = ternakName.toLowerCase();
                if (lowercaseName.includes('kambing')) return '🐐';
                if (lowercaseName.includes('domba')) return '🐑';
                if (lowercaseName.includes('kerbau')) return '🐃';
                return '🐄'; // default sapi
            }

            function generateStars(rating) {
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    const filled = i <= rating ? 'text-yellow-400' : 'text-gray-300';
                    stars += `<svg class="w-3 h-3 ${filled}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>`;
                }
                return stars;
            }

            function generateTimeline(currentStatus) {
                const timeline = document.getElementById('detailTimeline');
                const statuses = [{
                        key: 'pending',
                        label: 'Konsultasi Dibuat',
                        time: '2 jam yang lalu',
                        completed: true
                    },
                    {
                        key: 'berlangsung',
                        label: 'Konsultasi Berlangsung',
                        time: '1 jam yang lalu',
                        completed: currentStatus !== 'pending'
                    },
                    {
                        key: 'selesai',
                        label: 'Konsultasi Selesai',
                        time: '',
                        completed: currentStatus === 'selesai'
                    }
                ];

                let timelineHTML = '';
                statuses.forEach((item, index) => {
                    const isLast = index === statuses.length - 1;
                    const statusClass = item.completed ? 'text-green-600 bg-green-100' : 'text-gray-400 bg-gray-100';
                    const lineClass = item.completed ? 'bg-green-600' : 'bg-gray-300';

                    timelineHTML += `
                    <div class="flex items-start">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-8 h-8 rounded-full ${statusClass} flex items-center justify-center">
                                ${item.completed ? 
                                    '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' : 
                                    '<div class="w-2 h-2 bg-current rounded-full"></div>'
                                }
                            </div>
                            ${!isLast ? `<div class="w-0.5 h-8 ${lineClass} mt-2"></div>` : ''}
                        </div>
                        <div class="flex-1 pb-8">
                            <h6 class="font-medium ${item.completed ? 'text-gray-900' : 'text-gray-500'}">${item.label}</h6>
                            ${item.time ? `<p class="text-sm text-gray-500 mt-1">${item.time}</p>` : ''}
                        </div>
                    </div>
                `;
                });

                timeline.innerHTML = timelineHTML;
            }

            function generateActionButtons(status, consultationId) {
                const actionsContainer = document.getElementById('detailActions');
                let buttonsHTML = '';

                if (status === 'berlangsung') {
                    buttonsHTML = `
                    <button onclick="openChatModal(${consultationId})" 
                            class="w-full px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Mulai Chat
                    </button>
                    <button onclick="endConsultationFromDetail(${consultationId})" 
                            class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                        Akhiri Konsultasi
                    </button>
                `;
                } else if (status === 'selesai') {
                    buttonsHTML = `
                    <button onclick="downloadReport(${consultationId})" 
                            class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Laporan
                    </button>
                    <button onclick="openRatingModal(${consultationId})" 
                            class="w-full px-4 py-2 bg-purple-100 text-purple-700 rounded-lg text-sm font-medium hover:bg-purple-200 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        Beri Rating
                    </button>
                `;
                } else if (status === 'pending') {
                    buttonsHTML = `
                    <button onclick="cancelConsultation(${consultationId})" 
                            class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                        Batalkan Konsultasi
                    </button>
                    <button onclick="editConsultation(${consultationId})" 
                            class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        Edit Konsultasi
                    </button>
                `;
                }

                actionsContainer.innerHTML = buttonsHTML;
            }

            function endConsultationFromDetail(consultationId) {
                if (confirm('Apakah Anda yakin ingin mengakhiri konsultasi ini?')) {
                    closeDetailModal();
                    alert('Konsultasi telah diakhiri.');
                    // Refresh page or update status
                    window.location.reload();
                }
            }

            function downloadReport(consultationId) {
                alert('Fitur download laporan akan segera tersedia');
            }

            function cancelConsultation(consultationId) {
                if (confirm('Apakah Anda yakin ingin membatalkan konsultasi ini?')) {
                    alert('Konsultasi telah dibatalkan');
                    closeDetailModal();
                    window.location.reload();
                }
            }

            function editConsultation(consultationId) {
                closeDetailModal();
                alert('Fitur edit konsultasi akan segera tersedia');
            }

            document.addEventListener('DOMContentLoaded', function() {
                const konsultasiForm = document.getElementById('konsultasiForm');

                if (konsultasiForm) {
                    konsultasiForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const required = ['judulKonsultasi', 'kategoriKonsultasi', 'ternakTerkait',
                            'deskripsiKonsultasi'
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

                        const expertSelected = document.querySelector('input[name="idPenyuluh"]:checked');
                        if (!expertSelected) {
                            alert('Silakan pilih penyuluh untuk konsultasi');
                            isValid = false;
                        }

                        if (!isValid) {
                            alert('Mohon lengkapi semua field yang wajib diisi');
                            return;
                        }

                        const submitBtn = document.getElementById('konsultasiSubmitBtn');
                        const submitText = document.getElementById('konsultasiSubmitText');
                        const submitLoading = document.getElementById('konsultasiSubmitLoading');

                        submitBtn.disabled = true;
                        submitText.textContent = 'Mengirim...';
                        submitLoading.classList.remove('hidden');

                        // Submit form using actual form submission
                        this.submit();
                    });
                }

                const chatInput = document.getElementById('chatInput');
                if (chatInput) {
                    chatInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            sendMessage();
                        }
                    });
                }

                ['konsultasiModal', 'chatModal', 'ratingModal', 'detailModal'].forEach(modalId => {
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
                    ['konsultasiModal', 'chatModal', 'ratingModal', 'detailModal'].forEach(modalId => {
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
        </script>
    @endpush
