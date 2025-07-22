@extends('layouts.public')

@section('title', 'Informasi Umum')
@section('meta-description',
    'Dapatkan informasi lengkap tentang berbagai jenis pakan ternak untuk meningkatkan
    produktivitas peternakan Anda')

    @push('styles')
        <style>
            .hero-gradient {
                background: linear-gradient(135deg, #065f46 0%, #059669 100%);
            }

            .search-container {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
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

            .skeleton {
                animation: skeleton-loading 1s linear infinite alternate;
            }

            @keyframes skeleton-loading {
                0% {
                    background-color: hsl(200, 20%, 80%);
                }

                100% {
                    background-color: hsl(200, 20%, 95%);
                }
            }

            .floating-search {
                position: sticky;
                top: 80px;
                z-index: 30;
            }

            .stats-card {
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            #mobile-menu {
                display: none;
            }

            /* Tampilkan hanya pada mobile dan ketika ada class 'show' */
            @media (max-width: 767px) {
                #mobile-menu.show {
                    display: block;
                }
            }

            /* Pastikan pada desktop selalu tersembunyi */
            @media (min-width: 768px) {
                #mobile-menu {
                    display: none !important;
                }
            }

            /* Filter tag styling */
            .filter-tag {
                cursor: pointer;
                border: 1px solid #e5e7eb;
            }

            .filter-tag.active {
                background-color: #059669 !important;
                color: white !important;
            }

            .filter-tag:not(.active) {
                background-color: #f3f4f6;
                color: #374151;
            }

            .filter-tag:not(.active):hover {
                background-color: #e5e7eb;
            }

            /* View button styling */
            .view-btn {
                cursor: pointer;
                border: 1px solid #e5e7eb;
            }

            .view-btn.active {
                background-color: #059669 !important;
                color: white !important;
            }

            .view-btn:not(.active) {
                background-color: #f3f4f6;
                color: #6b7280;
            }

            .view-btn:not(.active):hover {
                background-color: #e5e7eb;
            }

            /* Line clamp utilities */
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-4 {
                display: -webkit-box;
                -webkit-line-clamp: 4;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    @endpush

@section('content')
    <section class="py-12 bg-gray-50 pt-32">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Jelajahi Informasi Umum</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Temukan berbagai informasi tentang pakan ternak yang telah terpercaya dan teruji untuk meningkatkan
                    produktivitas ternak Anda
                </p>
            </div>

            <!-- Search and Filter Section -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row gap-4 mb-6">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Cari informasi umum..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                onkeyup="searchPakan()">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select id="jenisPakanFilter" onchange="filterByJenisPakan()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Jenis</option>
                            <option value="hijauan">Hijauan</option>
                            <option value="konsentrat">Konsentrat</option>
                            <option value="fermentasi">Fermentasi</option>
                            <option value="organik">Organik</option>
                            <option value="limbah">Limbah Pertanian</option>
                        </select>

                        <select id="sortFilter" onchange="sortPakan()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="terbaru">Terbaru</option>
                            <option value="terlama">Terlama</option>
                            <option value="az">A-Z</option>
                            <option value="za">Z-A</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Tags -->
            <div class="flex flex-wrap gap-2 mb-8 justify-center">
                <button onclick="filterByTag('')"
                    class="filter-tag active px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Semua
                </button>
                <button onclick="filterByTag('hijauan')"
                    class="filter-tag px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Hijauan
                </button>
                <button onclick="filterByTag('konsentrat')"
                    class="filter-tag px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Konsentrat
                </button>
                <button onclick="filterByTag('fermentasi')"
                    class="filter-tag px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Fermentasi
                </button>
                <button onclick="filterByTag('organik')"
                    class="filter-tag px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Organik
                </button>
                <button onclick="filterByTag('limbah')"
                    class="filter-tag px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Limbah Pertanian
                </button>
            </div>

            <!-- Results Counter -->
            <div class="flex justify-between items-center mb-8">
                <p class="text-gray-600">
                    Menampilkan <span id="resultCount">{{ isset($informasiList) ? $informasiList->count() : 12 }}</span> dari
                    {{ $totalPakan ?? 28 }}
                    informasi umum
                </p>
                <div class="flex items-center space-x-2">
                    <button onclick="toggleView('grid')" id="gridViewBtn" class="p-2 rounded-lg view-btn active">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                    </button>
                    <button onclick="toggleView('list')" id="listViewBtn" class="p-2 rounded-lg view-btn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div id="loadingSkeleton" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @for ($i = 0; $i < 6; $i++)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="skeleton h-48 bg-gray-200"></div>
                        <div class="p-6">
                            <div class="skeleton h-4 bg-gray-200 rounded mb-2"></div>
                            <div class="skeleton h-6 bg-gray-200 rounded mb-4"></div>
                            <div class="skeleton h-20 bg-gray-200 rounded mb-4"></div>
                            <div class="flex justify-between">
                                <div class="skeleton h-6 w-20 bg-gray-200 rounded"></div>
                                <div class="skeleton h-8 w-24 bg-gray-200 rounded"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Grid View -->
            <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $jenisPakanTypes = ['hijauan', 'konsentrat', 'fermentasi', 'organik', 'limbah'];
                    $jenisPakanNames = ['Hijauan', 'Konsentrat', 'Fermentasi', 'Organik', 'Limbah Pertanian'];
                @endphp

                @forelse($informasiList ?? [] as $index => $pakan)
                    <article
                        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden card-hover pakan-card"
                        data-title="{{ $pakan->judul ?? 'Informasi Umum ' . sprintf('%03d', $index + 1) }}"
                        data-jenis-pakan="{{ $pakan->jenis_pakan ?? $jenisPakanTypes[$index % 5] }}"
                        data-created="{{ isset($pakan->created_at) ? $pakan->created_at : date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) }}">

                        <!-- Image -->
                        <div class="relative overflow-hidden">
                            @if (isset($pakan->foto) && $pakan->foto)
                                <img src="{{ asset('storage/informasi-pakan/' . $pakan->foto) }}" alt="{{ $pakan->judul }}"
                                    class="w-full h-48 object-cover transition-transform duration-300 hover:scale-105">
                            @else
                                <div
                                    class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Badge Jenis Pakan -->
                            <div class="absolute top-4 left-4">
                                <span
                                    class="jenis-pakan-badge jenis-{{ $pakan->jenis_pakan ?? $jenisPakanTypes[$index % 5] }}">
                                    {{ $pakan->jenis_pakan_display ?? $jenisPakanNames[$index % 5] }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2 hover:text-green-600 transition-colors">
                                <a
                                    href="{{ route('public.pakan.show', $pakan->slug ?? Str::slug($pakan->judul ?? 'informasi-pakan-' . $index)) }}">
                                    {{ $pakan->judul ?? 'Informasi Umum ' . sprintf('%03d', $index + 1) }}
                                </a>
                            </h3>

                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ isset($pakan->deskripsi) ? Str::limit(strip_tags($pakan->deskripsi), 120) : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}
                            </p>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    @if (isset($pakan->created_at))
                                        {{ \Carbon\Carbon::parse($pakan->created_at)->format('d M Y') }}
                                    @else
                                        {{ date('d M Y', strtotime('-' . rand(1, 30) . ' days')) }}
                                    @endif
                                </div>

                                <a href="{{ route('public.pakan.show', $pakan->slug ?? Str::slug($pakan->judul ?? 'informasi-pakan-' . $index)) }}"
                                    class="inline-flex items-center text-green-600 hover:text-green-700 font-medium text-sm transition-colors">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada informasi umum</h3>
                        <p class="text-gray-500">Belum ada informasi umum yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- List View -->
            <div id="listView" class="hidden space-y-6">
                @forelse($informasiList ?? [] as $index => $pakan)
                    <article
                        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden card-hover pakan-card"
                        data-title="{{ $pakan->judul ?? 'Informasi Umum ' . sprintf('%03d', $index + 1) }}"
                        data-jenis-pakan="{{ $pakan->jenis_pakan ?? $jenisPakanTypes[$index % 5] }}"
                        data-created="{{ isset($pakan->created_at) ? $pakan->created_at : date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) }}">

                        <div class="flex flex-col md:flex-row">
                            <!-- Image -->
                            <div class="md:w-1/3 relative overflow-hidden">
                                @if (isset($pakan->foto) && $pakan->foto)
                                    <img src="{{ asset('storage/informasi-pakan/' . $pakan->foto) }}"
                                        alt="{{ $pakan->judul }}" class="w-full h-48 md:h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-48 md:h-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Badge -->
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="jenis-pakan-badge jenis-{{ $pakan->jenis_pakan ?? $jenisPakanTypes[$index % 5] }}">
                                        {{ $pakan->jenis_pakan_display ?? $jenisPakanNames[$index % 5] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="md:w-2/3 p-6 flex flex-col justify-between">
                                <div>
                                    <h3
                                        class="text-2xl font-semibold text-gray-900 mb-3 hover:text-green-600 transition-colors">
                                        <a
                                            href="{{ route('public.pakan.show', $pakan->slug ?? Str::slug($pakan->judul ?? 'informasi-pakan-' . $index)) }}">
                                            {{ $pakan->judul ?? 'Informasi Umum ' . sprintf('%03d', $index + 1) }}
                                        </a>
                                    </h3>

                                    <p class="text-gray-600 mb-4 line-clamp-4">
                                        {{ isset($pakan->deskripsi) ? Str::limit(strip_tags($pakan->deskripsi), 200) : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.' }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        @if (isset($pakan->created_at))
                                            {{ \Carbon\Carbon::parse($pakan->created_at)->format('d M Y') }}
                                        @else
                                            {{ date('d M Y', strtotime('-' . rand(1, 30) . ' days')) }}
                                        @endif
                                    </div>

                                    <a href="{{ route('public.pakan.show', $pakan->slug ?? Str::slug($pakan->judul ?? 'informasi-pakan-' . $index)) }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        Baca Selengkapnya
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada informasi umum</h3>
                        <p class="text-gray-500">Belum ada informasi umum yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- No Results -->
            <div id="noResults" class="hidden text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil ditemukan</h3>
                <p class="text-gray-500">Coba gunakan kata kunci atau filter yang berbeda.</p>
            </div>

            <!-- Pagination -->
            @if (isset($informasiList) && method_exists($informasiList, 'links'))
                <div class="flex justify-center mt-12">
                    {{ $informasiList->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-green-600 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Butuh Konsultasi Lebih Lanjut?</h2>
            <p class="text-green-100 mb-8 max-w-2xl mx-auto">
                Tim ahli kami siap membantu Anda dalam memilih dan mengelola pakan ternak yang tepat untuk usaha peternakan
                Anda.
            </p>
            <a href="#"
                class="inline-flex items-center px-8 py-3 bg-white text-green-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Hubungi Konsultan
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
            </a>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        // Global variables
        let currentView = 'grid';
        let allCards = [];
        let filteredCards = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            allCards = Array.from(document.querySelectorAll('.pakan-card'));
            filteredCards = [...allCards];
            updateResultCounter();
        });

        // Search functionality
        function searchPakan() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            filteredCards = allCards.filter(card => {
                const title = card.getAttribute('data-title').toLowerCase();
                const content = card.textContent.toLowerCase();
                return title.includes(searchTerm) || content.includes(searchTerm);
            });

            applyFilters();
        }

        // Filter by jenis pakan
        function filterByJenisPakan() {
            const jenisPakan = document.getElementById('jenisPakanFilter').value;

            if (jenisPakan === '') {
                filteredCards = [...allCards];
            } else {
                filteredCards = allCards.filter(card => {
                    return card.getAttribute('data-jenis-pakan') === jenisPakan;
                });
            }

            applyFilters();
        }

        // Filter by tag (same as jenis pakan but for buttons)
        function filterByTag(tag) {
            // Update active tag
            document.querySelectorAll('.filter-tag').forEach(btn => {
                btn.classList.remove('active', 'bg-green-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            });

            event.target.classList.add('active', 'bg-green-600', 'text-white');
            event.target.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');

            // Update select
            document.getElementById('jenisPakanFilter').value = tag;

            // Apply filter
            filterByJenisPakan();
        }

        // Sort functionality
        function sortPakan() {
            const sortType = document.getElementById('sortFilter').value;

            filteredCards.sort((a, b) => {
                switch (sortType) {
                    case 'terbaru':
                        return new Date(b.getAttribute('data-created')) - new Date(a.getAttribute('data-created'));
                    case 'terlama':
                        return new Date(a.getAttribute('data-created')) - new Date(b.getAttribute('data-created'));
                    case 'az':
                        return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
                    case 'za':
                        return b.getAttribute('data-title').localeCompare(a.getAttribute('data-title'));
                    default:
                        return 0;
                }
            });

            applyFilters();
        }

        // Toggle view
        function toggleView(view) {
            currentView = view;

            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');

            if (view === 'grid') {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                gridBtn.classList.add('active', 'bg-green-600', 'text-white');
                gridBtn.classList.remove('bg-gray-200', 'text-gray-600');
                listBtn.classList.remove('active', 'bg-green-600', 'text-white');
                listBtn.classList.add('bg-gray-200', 'text-gray-600');
            } else {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                listBtn.classList.add('active', 'bg-green-600', 'text-white');
                listBtn.classList.remove('bg-gray-200', 'text-gray-600');
                gridBtn.classList.remove('active', 'bg-green-600', 'text-white');
                gridBtn.classList.add('bg-gray-200', 'text-gray-600');
            }

            applyFilters();
        }

        // Apply filters and show/hide cards
        function applyFilters() {
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const noResults = document.getElementById('noResults');

            // Hide all cards first
            allCards.forEach(card => {
                card.style.display = 'none';
            });

            // Show filtered cards
            filteredCards.forEach(card => {
                card.style.display = 'block';
            });

            // Show/hide no results message
            if (filteredCards.length === 0) {
                gridView.classList.add('hidden');
                listView.classList.add('hidden');
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
                if (currentView === 'grid') {
                    gridView.classList.remove('hidden');
                    listView.classList.add('hidden');
                } else {
                    gridView.classList.add('hidden');
                    listView.classList.remove('hidden');
                }
            }

            updateResultCounter();
        }

        // Update result counter
        function updateResultCounter() {
            const counter = document.getElementById('resultCount');
            if (counter) {
                counter.textContent = filteredCards.length;
            }
        }

        // Initialize filter tags styling
        document.addEventListener('DOMContentLoaded', function() {
            const filterTags = document.querySelectorAll('.filter-tag');
            filterTags.forEach((tag, index) => {
                if (index === 0) {
                    tag.classList.add('active', 'bg-green-600', 'text-white');
                } else {
                    tag.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                }
            });

            const viewBtns = document.querySelectorAll('.view-btn');
            viewBtns.forEach((btn, index) => {
                if (index === 0) {
                    btn.classList.add('active', 'bg-green-600', 'text-white');
                } else {
                    btn.classList.add('bg-gray-200', 'text-gray-600', 'hover:bg-gray-300');
                }
            });
        });

        // Smooth scroll for floating search
        window.addEventListener('scroll', function() {
            const floatingSearch = document.querySelector('.floating-search');
            if (window.scrollY > 100) {
                floatingSearch.classList.add('shadow-xl');
            } else {
                floatingSearch.classList.remove('shadow-xl');
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    // Toggle visibility
                    mobileMenu.classList.toggle('hidden');

                    // Optional: Change hamburger icon to X when open
                    const svg = mobileMenuButton.querySelector('svg');
                    if (mobileMenu.classList.contains('hidden')) {
                        // Show hamburger icon
                        svg.innerHTML =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    } else {
                        // Show X icon
                        svg.innerHTML =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                    }
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                        // Reset hamburger icon
                        const svg = mobileMenuButton.querySelector('svg');
                        svg.innerHTML =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    }
                });

                // Close mobile menu when window is resized to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) { // md breakpoint
                        mobileMenu.classList.add('hidden');
                        // Reset hamburger icon
                        const svg = mobileMenuButton.querySelector('svg');
                        svg.innerHTML =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    }
                });
            }
        });
    </script>
@endpush
