@extends('layouts.app')

@section('title', 'Rekomendasi Pakan')
@section('page-title', 'Rekomendasi Pakan')
@section('page-description', 'Daftar rekomendasi pakan untuk ternak Anda')

@push('styles')
    <style>
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
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Filter dan Search Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <!-- Search Bar -->
                <div class="search-container flex-1 lg:max-w-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput"
                        class="search-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Cari jenis pakan atau deskripsi..." onkeyup="searchPakan()">
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($pakans ?? [] as $pakan)
                <div class="pakan-card card-hover pakan-item" data-jenis="{{ strtolower($pakan->jenisPakan) }}"
                    data-kategori="{{ strtolower($pakan->kategori) }}" data-id="{{ $pakan->idRekomendasi }}"
                    data-ternak="{{ $pakan->ternak ? $pakan->ternak->namaTernak : 'Ternak tidak ditemukan' }}"
                    data-penyuluh="{{ $pakan->penyuluh ? $pakan->penyuluh->nama : 'Penyuluh tidak ditemukan' }}"
                    data-tanggal="{{ $pakan->tanggalRekomendasi }}" data-jumlah="{{ $pakan->jumlah }}"
                    data-satuan="{{ $pakan->satuan }}" data-deskripsi="{{ $pakan->deskripsi }}"
                    data-jenisTernak="{{ $pakan->ternak->jenis }}" data-status="{{ $pakan->ternak->status }}">

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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
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
                                    <p class="text-sm text-gray-500">{{ ucfirst($pakan->ternak->jenis ?? 'Sapi') }}
                                    </p>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ $pakan->penyuluh ? $pakan->penyuluh->nama : 'Penyuluh tidak ditemukan' }}</span>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-4 pb-4">
                        <button onclick="openDetailModal({{ $pakan->idRekomendasi }})"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Lihat Detail
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Belum Ada Rekomendasi Pakan</h3>
                        <p class="mt-1 text-gray-500">Belum ada rekomendasi pakan yang tersedia untuk ternak Anda.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if (!empty($pakans) && count($pakans) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Menampilkan {{ $pakans->firstItem() ?? 1 }}-{{ $pakans->lastItem() ?? count($pakans) }}
                        dari {{ $pakans->total() ?? count($pakans) }} rekomendasi
                    </p>
                    @if (method_exists($pakans, 'links'))
                        {{ $pakans->links() }}
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Detail Rekomendasi Pakan -->
    <div id="detailModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Detail Rekomendasi Pakan</h3>
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
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Deskripsi & Cara Pemberian
                            </h5>
                            <div id="detailDeskripsi" class="text-gray-700 leading-relaxed"></div>
                        </div>

                        <!-- Petunjuk Pemberian -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                Petunjuk Penting
                            </h5>
                            <ul class="text-sm text-gray-700 space-y-2">
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Berikan pakan sesuai dengan dosis yang direkomendasikan
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Pastikan pakan dalam kondisi segar dan tidak berjamur
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Sediakan air minum yang cukup untuk ternak
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Konsultasikan dengan penyuluh jika ada perubahan kondisi ternak
                                </li>
                            </ul>
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

                        <!-- Actions -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h5 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h5>
                            <div class="space-y-3">
                                <button onclick="printRekomendasi()"
                                    class="w-full px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition-colors flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    Cetak Rekomendasi
                                </button>
                                <button onclick="shareRekomendasi()"
                                    class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                        </path>
                                    </svg>
                                    Bagikan
                                </button>
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
            const jenisTernak = pakanItem.getAttribute('data-jenisTernak');
            const status = pakanItem.getAttribute('data-status');
            const penyuluh = pakanItem.getAttribute('data-penyuluh');
            const tanggal = pakanItem.getAttribute('data-tanggal');
            const jumlah = pakanItem.getAttribute('data-jumlah');
            const satuan = pakanItem.getAttribute('data-satuan');
            const deskripsi = pakanItem.getAttribute('data-deskripsi');

            // Populate modal header
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

            // Populate description
            document.getElementById('detailDeskripsi').innerHTML = `<p class="whitespace-pre-wrap">${deskripsi}</p>`;

            // Populate penyuluh info
            const penyuluhContainer = document.getElementById('detailPenyuluh');
            penyuluhContainer.innerHTML = `
                <div class="flex items-center space-x-3">                    
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
            currentRecommendationId = null;
        }

        function getTernakEmoji(ternakName) {
            const lowercaseName = ternakName.toLowerCase();
            if (lowercaseName.includes('kambing')) return '🐐';
            if (lowercaseName.includes('domba')) return '🐑';
            if (lowercaseName.includes('kerbau')) return '🐃';
            return '🐄'; // default sapi
        }

        function printRekomendasi() {
            if (!currentRecommendationId) return;

            // Create print content
            const jenisPakan = document.getElementById('detailJenisPakan').textContent;
            const kategori = document.getElementById('detailKategori').textContent;
            const tanggal = document.getElementById('detailTanggal').textContent;
            const jumlah = document.getElementById('detailJumlah').textContent;
            const satuan = document.getElementById('detailSatuan').textContent;
            const deskripsi = document.getElementById('detailDeskripsi').textContent;

            const printContent = `
                <div style="padding: 20px; font-family: Arial, sans-serif;">
                    <h1 style="text-align: center; color: #333;">Rekomendasi Pakan Ternak</h1>
                    <hr>
                    <div style="margin: 20px 0;">
                        <h2>${jenisPakan}</h2>
                        <p><strong>Kategori:</strong> ${kategori}</p>
                        <p><strong>Tanggal:</strong> ${tanggal}</p>
                        <p><strong>Jumlah:</strong> ${jumlah} ${satuan}</p>
                        <h3>Deskripsi & Cara Pemberian:</h3>
                        <p style="line-height: 1.6;">${deskripsi}</p>
                    </div>
                </div>
            `;

            const newWindow = window.open('', '_blank');
            newWindow.document.write(printContent);
            newWindow.document.close();
            newWindow.print();
        }

        function shareRekomendasi() {
            if (!currentRecommendationId) return;

            const jenisPakan = document.getElementById('detailJenisPakan').textContent;
            const shareText = `Rekomendasi Pakan: ${jenisPakan}`;

            if (navigator.share) {
                navigator.share({
                    title: shareText,
                    text: shareText,
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const shareUrl = window.location.href;
                navigator.clipboard.writeText(`${shareText} - ${shareUrl}`)
                    .then(() => alert('Link berhasil disalin ke clipboard!'))
                    .catch(() => alert('Gagal menyalin link'));
            }
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
        });
    </script>
@endpush
