@extends('layouts.app')

@section('title', 'Rekomendasi Pakan')
@section('page-title', 'Rekomendasi Pakan')
@section('page-description', 'Optimalisasi nutrisi dan manajemen pakan ternak')

@push('styles')
    <style>
        .tab-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .tab-nav {
            display: flex;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .tab-button {
            flex: 1;
            padding: 1rem 1.5rem;
            background: transparent;
            border: none;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .tab-button:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .tab-button.active {
            background: white;
            color: #059669;
            border-bottom: 3px solid #059669;
        }

        .tab-content {
            display: none;
            padding: 2rem;
        }

        .tab-content.active {
            display: block;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .kategori-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .kategori-hijauan {
            background-color: #d1fae5;
            color: #065f46;
        }

        .kategori-konsentrat {
            background-color: #fef3c7;
            color: #92400e;
        }

        .kategori-suplemen {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .kategori-vitamin {
            background-color: #fce7f3;
            color: #be185d;
        }

        .kategori-limbah {
            background-color: #f3e8ff;
            color: #7c3aed;
        }

        .kategori-mineral {
            background-color: #ecfdf5;
            color: #047857;
        }

        .kategori-additive {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .kategori-complete {
            background-color: #eff6ff;
            color: #1d4ed8;
        }

        .pakan-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
        }

        .pakan-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .pakan-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
        }

        .quantity-display {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 0.75rem;
            text-align: center;
            border: 2px dashed #d1d5db;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-chip:hover,
        .filter-chip.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .search-container {
            position: relative;
        }

        .search-container svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .search-input {
            padding-left: 2.5rem;
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

        .recommendation-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #86efac;
            border-radius: 12px;
            padding: 1.5rem;
            position: relative;
        }

        .recommendation-card::before {
            content: '🎯';
            position: absolute;
            top: -10px;
            right: -10px;
            background: #10b981;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .success-message {
            background: #d1fae5;
            border: 1px solid #86efac;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .form-section {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Rekomendasi Pakan Ternak</h1>
                    <p class="text-green-100">Optimalisasi nutrisi untuk produktivitas maksimal</p>
                </div>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $totalRekomendasi ?? 0 }}</div>
                        <div class="text-sm text-green-100">Total Rekomendasi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $ternakAktif ?? 0 }}</div>
                        <div class="text-sm text-green-100">Ternak Aktif</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="success-message">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Tabs Container -->
        <div class="tab-container">
            <!-- Tab Navigation -->
            <div class="tab-nav">
                <button class="tab-button active" onclick="switchTab('form')" id="tab-form">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Rekomendasi
                    </div>
                </button>
                <button class="tab-button" onclick="switchTab('list')" id="tab-list">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Daftar Rekomendasi
                    </div>
                </button>
            </div>

            <!-- Tab Content: Form Rekomendasi -->
            <div id="content-form" class="tab-content active">
                <div class="form-section">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            📝
                        </span>
                        Form Rekomendasi Pakan
                    </h3>

                    <form id="feedRecommendationForm" action="{{ route('pakan.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="idTernak" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Ternak <span class="text-red-500">*</span>
                                </label>
                                <select id="idTernak" name="idTernak" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->has('idTernak') ? 'border-red-500' : '' }}">
                                    <option value="">Pilih Ternak</option>
                                    @foreach ($ternaks ?? [] as $ternak)
                                        <option value="{{ $ternak->idTernak }}" {{ old('idTernak') == $ternak->idTernak ? 'selected' : '' }}>
                                            {{ $ternak->nama_ternak ?? $ternak->namaTernak }} 
                                            ({{ ucfirst($ternak->jenis_ternak ?? $ternak->jenis) }} - {{ $ternak->berat_badan ?? $ternak->berat }}kg)
                                        </option>
                                    @endforeach
                                </select>
                                @error('idTernak')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggalRekomendasi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Rekomendasi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="tanggalRekomendasi" name="tanggalRekomendasi" 
                                       value="{{ old('tanggalRekomendasi', date('Y-m-d')) }}" required
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->has('tanggalRekomendasi') ? 'border-red-500' : '' }}">
                                @error('tanggalRekomendasi')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="jenisPakan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Pakan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="jenisPakan" name="jenisPakan" 
                                       value="{{ old('jenisPakan') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->has('jenisPakan') ? 'border-red-500' : '' }}"
                                       placeholder="Contoh: Rumput Gajah, Konsentrat Sapi, dll"
                                       list="jenisPakanList">
                                <datalist id="jenisPakanList">
                                    <option value="Konsentrat Premium">
                                    <option value="Rumput Gajah">
                                    <option value="Rumput Raja">
                                    <option value="Silase Jagung">
                                    <option value="Dedak Padi">
                                    <option value="Bungkil Kelapa">
                                    <option value="Mineral Mix">
                                    <option value="Complete Feed">
                                    <option value="Pakan Fermentasi">
                                    <option value="Vitamin B-Complex">
                                    <option value="Probiotik">
                                </datalist>
                                @error('jenisPakan')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select id="kategori" name="kategori" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->has('kategori') ? 'border-red-500' : '' }}">
                                    <option value="">Pilih Kategori</option>
                                    <option value="hijauan" {{ old('kategori') == 'hijauan' ? 'selected' : '' }}>Hijauan</option>
                                    <option value="konsentrat" {{ old('kategori') == 'konsentrat' ? 'selected' : '' }}>Konsentrat</option>
                                    <option value="suplemen" {{ old('kategori') == 'suplemen' ? 'selected' : '' }}>Suplemen</option>
                                    <option value="vitamin" {{ old('kategori') == 'vitamin' ? 'selected' : '' }}>Vitamin</option>
                                </select>
                                @error('kategori')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="jumlah" name="jumlah" min="0.1" step="0.1" 
                                       value="{{ old('jumlah') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->has('jumlah') ? 'border-red-500' : '' }}"
                                       placeholder="8.5">
                                @error('jumlah')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Satuan <span class="text-red-500">*</span>
                                </label>
                                <select id="satuan" name="satuan" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->has('satuan') ? 'border-red-500' : '' }}">
                                    <option value="">Pilih Satuan</option>
                                    <option value="kg/hari" {{ old('satuan') == 'kg/hari' ? 'selected' : '' }}>kg/hari</option>
                                    <option value="gram/hari" {{ old('satuan') == 'gram/hari' ? 'selected' : '' }}>gram/hari</option>
                                    <option value="ml/hari" {{ old('satuan') == 'ml/hari' ? 'selected' : '' }}>ml/hari</option>
                                    <option value="liter/hari" {{ old('satuan') == 'liter/hari' ? 'selected' : '' }}>liter/hari</option>
                                    <option value="kg/minggu" {{ old('satuan') == 'kg/minggu' ? 'selected' : '' }}>kg/minggu</option>
                                </select>
                                @error('satuan')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi & Cara Pemberian <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 {{ $errors->has('deskripsi') ? 'border-red-500' : '' }}"
                                placeholder="Jelaskan cara pemberian pakan, waktu pemberian, hal-hal yang perlu diperhatikan, dan tips khusus...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="flex space-x-4">
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                                💾 Simpan Rekomendasi
                            </button>
                            <button type="button" onclick="resetForm()"
                                class="px-6 py-3 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition-colors">
                                🔄 Reset
                            </button>                         
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab Content: Daftar Rekomendasi -->
            <div id="content-list" class="tab-content">
                <!-- Filter dan Search Bar -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <!-- Search Bar -->
                        <div class="search-container flex-1 lg:max-w-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" id="searchInput" 
                                   class="search-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Cari jenis pakan atau deskripsi..."
                                   onkeyup="searchPakan()">
                        </div>

                        <!-- Filter Chips -->
                        <div class="flex flex-wrap gap-2">
                            <button class="filter-chip active" onclick="filterByKategori('')">Semua</button>
                            <button class="filter-chip" onclick="filterByKategori('hijauan')">Hijauan</button>
                            <button class="filter-chip" onclick="filterByKategori('konsentrat')">Konsentrat</button>
                            <button class="filter-chip" onclick="filterByKategori('suplemen')">Suplemen</button>
                            <button class="filter-chip" onclick="filterByKategori('vitamin')">Vitamin</button>
                        </div>
                    </div>
                </div>

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="pakanGrid">
                    @forelse ($pakans ?? [] as $pakan)
                        <div class="pakan-card card-hover pakan-item" 
                             data-jenis="{{ strtolower($pakan->jenis) }}"
                             data-kategori="{{ strtolower($pakan->kategori) }}"
                             data-id="{{ $pakan->idRekomendasi }}"
                             data-ternak="{{ $pakan->ternak ? $pakan->ternak->namaTernak : 'Ternak tidak ditemukan' }}"
                             data-penyuluh="{{ $pakan->penyuluh ? $pakan->penyuluh->nama : 'Penyuluh tidak ditemukan' }}"
                             data-tanggal="{{ $pakan->tanggalRekomendasi }}"
                             data-jumlah="{{ $pakan->jumlah }}"
                             data-jenisTernak="{{ $pakan->ternak->jenis }}"
                             data-satuan="{{ $pakan->satuan }}"
                             data-deskripsi="{{ $pakan->deskripsi }}">
                            
                            <!-- Card Header -->
                            <div class="pakan-header">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-bold truncate">{{ $pakan->jenisPakan }}</h3>
                                    <span class="kategori-badge kategori-{{ strtolower($pakan->kategori) }}">
                                        {{ ucfirst($pakan->kategori) }}
                                    </span>
                                </div>
                                <div class="flex items-center text-sm opacity-90">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($pakan->tanggalRekomendasi)->format('d M Y') }}
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 space-y-4">
                                <!-- Informasi Ternak -->
                                <div class="flex items-center space-x-3">
                                    @if ($pakan->ternak)
                                        @php
                                            $animalEmoji = match ($pakan->ternak->jenis ?? 'sapi') {
                                                'sapi' => '🐄',
                                                'kambing' => '🐐',
                                                'domba' => '🐑',
                                                'kerbau' => '🐃',
                                                default => '🐄',
                                            };
                                        @endphp
                                        <span class="text-2xl">{{ $animalEmoji }}</span>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $pakan->ternak->namaTernak }}</p>
                                            <p class="text-sm text-gray-500">{{ ucfirst($pakan->ternak->jenis ?? 'Sapi') }}</p>
                                        </div>
                                    @else
                                        <span class="text-2xl">🐄</span>
                                        <div>
                                            <p class="font-medium text-gray-900">Ternak tidak ditemukan</p>
                                            <p class="text-sm text-gray-500">-</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Jumlah Pakan -->
                                <div class="quantity-display">
                                    <div class="text-2xl font-bold text-gray-900">{{ $pakan->jumlah }}</div>
                                    <div class="text-sm font-medium text-gray-600">{{ $pakan->satuan }}</div>
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <p class="text-sm text-gray-600 line-clamp-3">
                                        {{ Str::limit($pakan->deskripsi, 100) }}
                                    </p>
                                </div>

                                <!-- Penyuluh Info -->
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>{{ $pakan->penyuluh ? $pakan->penyuluh->nama : 'Penyuluh tidak ditemukan' }}</span>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-4 pb-4">
                                <div class="flex space-x-2">
                                    <button onclick="openDetailModal({{ $pakan->idRekomendasi }})"
                                            class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </button>
                                    
                                    @auth
                                        @if(Auth::id() === $pakan->idPenyuluh)
                                            <a href="{{ route('pakan.edit', $pakan) }}"
                                               class="px-3 py-2 bg-yellow-500 text-white rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">Belum Ada Rekomendasi Pakan</h3>
                                <p class="mt-1 text-gray-500">Belum ada rekomendasi pakan yang tersedia untuk ternak Anda.</p>
                                <button onclick="switchTab('form')" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    Buat Rekomendasi Pertama
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if(!empty($pakans) && count($pakans) > 0)
                    <div class="mt-6 bg-gray-50 rounded-lg px-6 py-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600">
                                Menampilkan {{ $pakans->firstItem() ?? 1 }}-{{ $pakans->lastItem() ?? count($pakans) }} 
                                dari {{ $pakans->total() ?? count($pakans) }} rekomendasi
                            </p>
                            @if(method_exists($pakans, 'links'))
                                {{ $pakans->links() }}
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail Rekomendasi Pakan -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Detail Rekomendasi Pakan</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-6">
                <!-- Header Info -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 id="detailJenisPakan" class="text-xl font-bold text-gray-900 mb-2"></h4>
                            <div class="flex items-center space-x-4 mb-3">
                                <span id="detailKategori" class="kategori-badge"></span>
                                <span id="detailTanggal" class="text-sm text-gray-600"></span>
                            </div>
                            <p id="detailIdRekomendasi" class="text-xs text-gray-500 font-mono"></p>
                        </div>
                        <div class="text-right">
                            <div id="detailJumlah" class="text-3xl font-bold text-blue-600"></div>
                            <div id="detailSatuan" class="text-sm font-medium text-gray-600"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Deskripsi -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Deskripsi & Cara Pemberian
                            </h5>
                            <div id="detailDeskripsi" class="text-gray-700 leading-relaxed"></div>
                        </div>
                    </div>

                    <!-- Right Column - Sidebar Info -->
                    <div class="space-y-6">
                        <!-- Info Penyuluh -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Penyuluh
                            </h5>
                            <div id="detailPenyuluh" class="space-y-3">
                                <!-- Penyuluh info will be populated here -->
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
        let currentRecommendationId = null;

        // Tab switching function
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(`content-${tabName}`).classList.add('active');
            document.getElementById(`tab-${tabName}`).classList.add('active');
        }

        // Search function
        function searchPakan() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const items = document.querySelectorAll('.pakan-item');

            items.forEach(item => {
                const jenis = item.getAttribute('data-jenis');
                const deskripsi = item.getAttribute('data-deskripsi').toLowerCase();
                const ternak = item.getAttribute('data-ternak').toLowerCase();
                
                const isVisible = jenis.includes(searchTerm) || 
                                deskripsi.includes(searchTerm) || 
                                ternak.includes(searchTerm);
                
                item.style.display = isVisible ? 'block' : 'none';
            });
        }

        // Filter function
        function filterByKategori(kategori) {
            const items = document.querySelectorAll('.pakan-item');
            const chips = document.querySelectorAll('.filter-chip');

            // Update active chip
            chips.forEach(chip => chip.classList.remove('active'));
            event.target.classList.add('active');

            items.forEach(item => {
                const itemKategori = item.getAttribute('data-kategori');
                item.style.display = (!kategori || itemKategori === kategori) ? 'block' : 'none';
            });
        }

        // Form functions
        function resetForm() {
            document.getElementById('feedRecommendationForm').reset();
            document.getElementById('tanggalRekomendasi').value = new Date().toISOString().split('T')[0];
        }

        function previewRecommendation() {
            const formData = new FormData(document.getElementById('feedRecommendationForm'));
            const data = Object.fromEntries(formData);

            if (!data.jenisPakan || !data.jumlah || !data.satuan) {
                alert('Mohon isi minimal Jenis Pakan, Jumlah, dan Satuan untuk preview');
                return;
            }

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-backdrop';
            modal.innerHTML = `
                <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white modal-content">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Preview Rekomendasi Pakan</h3>
                        <button onclick="this.closest('.modal-backdrop').remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-green-800 mb-4">${data.jenisPakan}</h4>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="text-sm text-gray-600">Jumlah:</span>
                                <div class="font-medium">${data.jumlah} ${data.satuan}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Kategori:</span>
                                <div class="font-medium">${data.kategori}</div>
                            </div>
                        </div>
                        
                        ${data.deskripsi ? `
                            <div class="bg-blue-50 rounded-lg p-3">
                                <h5 class="font-medium text-blue-900 mb-1">Deskripsi:</h5>
                                <p class="text-sm text-blue-700">${data.deskripsi}</p>
                            </div>
                            ` : ''}
                    </div>
                    
                    <div class="mt-6 flex space-x-3">
                        <button onclick="document.getElementById('feedRecommendationForm').submit()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Simpan Rekomendasi
                        </button>
                        <button onclick="this.closest('.modal-backdrop').remove()" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Detail modal functions
        function openDetailModal(recommendationId) {
            const pakanItem = document.querySelector(`[data-id="${recommendationId}"]`);

            if (!pakanItem) {
                alert('Data rekomendasi pakan tidak ditemukan');
                return;
            }

            currentRecommendationId = recommendationId;

            // Extract data from the item
            const jenisPakan = pakanItem.querySelector('.pakan-header h3').textContent;
            const kategori = pakanItem.getAttribute('data-kategori');
            const ternak = pakanItem.getAttribute('data-ternak');
            const penyuluh = pakanItem.getAttribute('data-penyuluh');
            const tanggal = pakanItem.getAttribute('data-tanggal');
            const jumlah = pakanItem.getAttribute('data-jumlah');
            const satuan = pakanItem.getAttribute('data-satuan');
            const jenisTernak = pakanItem.getAttribute('data-jenisTernak');
            const deskripsi = pakanItem.getAttribute('data-deskripsi');

            // Populate modal
            document.getElementById('detailJenisPakan').textContent = jenisPakan;
            document.getElementById('detailKategori').textContent = kategori.charAt(0).toUpperCase() + kategori.slice(1);
            document.getElementById('detailKategori').className = `kategori-badge kategori-${kategori}`;
            document.getElementById('detailTanggal').textContent = new Date(tanggal).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('detailIdRekomendasi').textContent = `ID Rekomendasi: #${recommendationId}`;
            document.getElementById('detailJumlah').textContent = jumlah;
            document.getElementById('detailSatuan').textContent = satuan;
            document.getElementById('detailDeskripsi').innerHTML = `<p class="whitespace-pre-wrap">${deskripsi}</p>`;

            // Populate penyuluh info
            const penyuluhContainer = document.getElementById('detailPenyuluh');
            penyuluhContainer.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h6 class="font-semibold text-gray-900">${penyuluh}</h6>
                        <p class="text-sm text-gray-600">Penyuluh Pertanian</p>
                    </div>
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
                        <span class="text-gray-600">Jenis:</span>
                        <span class="font-medium">${jenisTernak}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="text-green-600 font-medium">Sehat</span>
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
            currentRecommendationId = null;
        }

        function getTernakEmoji(ternakName) {
            const lowercaseName = ternakName.toLowerCase();
            if (lowercaseName.includes('kambing')) return '🐐';
            if (lowercaseName.includes('domba')) return '🐑';
            if (lowercaseName.includes('kerbau')) return '🐃';
            return '🐄'; // default sapi
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Modal backdrop click to close
            const modal = document.getElementById('detailModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeDetailModal();
                    }
                });
            }

            // Escape key to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('detailModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeDetailModal();
                    }
                }
            });

            // Auto-switch to form tab if there are validation errors
            @if($errors->any())
                switchTab('form');
            @endif
        });
    </script>
@endpush