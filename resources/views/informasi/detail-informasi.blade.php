@extends('layouts.public')

@section('title', $pakan->judul ?? 'Detail Informasi Pakan')
@section('meta-description', Str::limit(strip_tags($pakan->deskripsi ?? 'Informasi lengkap tentang pakan ternak'), 160))

@push('styles')
    <style>
        .breadcrumb-item+.breadcrumb-item::before {
            content: "/";
            color: #6b7280;
            margin: 0 8px;
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

        .content-prose {
            line-height: 1.8;
        }

        .content-prose h1,
        .content-prose h2,
        .content-prose h3,
        .content-prose h4,
        .content-prose h5,
        .content-prose h6 {
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .content-prose h1 {
            font-size: 2rem;
        }

        .content-prose h2 {
            font-size: 1.75rem;
        }

        .content-prose h3 {
            font-size: 1.5rem;
        }

        .content-prose h4 {
            font-size: 1.25rem;
        }

        .content-prose p {
            margin-bottom: 1.5rem;
        }

        .content-prose ul,
        .content-prose ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .content-prose li {
            margin-bottom: 0.5rem;
        }

        .content-prose blockquote {
            border-left: 4px solid #10b981;
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            background-color: #f0fdf4;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .content-prose img {
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .related-card {
            transition: all 0.3s ease;
        }

        .related-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .share-btn {
            transition: all 0.2s ease;
        }

        .share-btn:hover {
            transform: scale(1.05);
        }

        .toc {
            position: sticky;
            top: 100px;
        }

        .toc-item {
            padding: 0.5rem 0;
            border-left: 2px solid transparent;
            padding-left: 1rem;
            transition: all 0.2s ease;
        }

        .toc-item:hover,
        .toc-item.active {
            border-left-color: #10b981;
            background-color: #f0fdf4;
            border-radius: 0 0.25rem 0.25rem 0;
        }

        .print-hide {
            display: block;
        }

        @media print {
            .print-hide {
                display: none !important;
            }

            .content-prose {
                color: #000 !important;
            }

            .bg-gray-50 {
                background-color: #fff !important;
            }
        }

        .zoom-image {
            cursor: zoom-in;
        }

        .image-modal {
            backdrop-filter: blur(4px);
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4 print-hide">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm">
                <li class="breadcrumb-item">
                    <a href="" class="text-green-600 hover:text-green-700">Beranda</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="" class="text-green-600 hover:text-green-700">Informasi
                        Pakan</a>
                </li>
                <li class="breadcrumb-item text-gray-500">
                    {{ Str::limit($pakan->judul ?? 'Detail Pakan', 50) }}
                </li>
            </ol>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Article Content -->
                <article class="lg:col-span-3">
                    <!-- Header -->
                    <header class="mb-8">
                        <div class="flex items-center space-x-4 mb-4">
                            <span class="jenis-pakan-badge jenis-{{ $pakan->jenis_pakan ?? 'hijauan' }}">
                                {{ ucfirst($pakan->jenis_pakan ?? 'Hijauan') }}
                            </span>
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

                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                            {{ $pakan->judul ?? 'Judul Informasi Pakan' }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                @if ($pakan->created_at)
                                    Dipublikasi: {{ \Carbon\Carbon::parse($pakan->created_at)->format('d F Y') }}
                                @else
                                    Dipublikasi: {{ date('d F Y') }}
                                @endif
                            </div>

                            @if ($pakan->updated_at && $pakan->updated_at != $pakan->created_at)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Diperbarui: {{ \Carbon\Carbon::parse($pakan->updated_at)->format('d F Y') }}
                                </div>
                            @endif

                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Waktu baca: {{ ceil(str_word_count(strip_tags($pakan->deskripsi ?? '')) / 200) }} menit
                            </div>
                        </div>

                        <!-- Share Buttons -->
                        <div class="flex items-center space-x-3 print-hide">
                            <span class="text-sm font-medium text-gray-700">Bagikan:</span>
                            <button onclick="shareWhatsApp()"
                                class="share-btn flex items-center px-3 py-2 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                </svg>
                                WhatsApp
                            </button>

                            <button onclick="shareFacebook()"
                                class="share-btn flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                Facebook
                            </button>

                            <button onclick="copyLink()"
                                class="share-btn flex items-center px-3 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Salin Link
                            </button>

                            <button onclick="printPage()"
                                class="share-btn flex items-center px-3 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                                Cetak
                            </button>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    @if (isset($pakan->foto) && $pakan->foto)
                        <div class="mb-8">
                            <img src="{{ asset('storage/informasi-pakan/' . $pakan->foto) }}" alt="{{ $pakan->judul }}"
                                class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg zoom-image"
                                onclick="openImageModal(this.src, '{{ $pakan->judul }}')">
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="content-prose text-gray-800">
                        {!! $pakan->deskripsi ?? '<p>Konten informasi pakan tidak tersedia.</p>' !!}
                    </div>

                    <!-- Tags/Categories -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Kategori:</span>
                            <span class="jenis-pakan-badge jenis-{{ $pakan->jenis_pakan ?? 'hijauan' }}">
                                {{ ucfirst($pakan->jenis_pakan ?? 'Hijauan') }}
                            </span>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="mt-12 pt-8 border-t border-gray-200 print-hide">
                        <div class="flex justify-between">
                            @if (isset($previousPakan))
                                <a href="{{ route('public.pakan.show', $previousPakan->slug) }}"
                                    class="flex items-center text-green-600 hover:text-green-700 group">
                                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-500">Sebelumnya</div>
                                        <div class="font-medium">{{ Str::limit($previousPakan->judul, 40) }}</div>
                                    </div>
                                </a>
                            @else
                                <div></div>
                            @endif

                            @if (isset($nextPakan))
                                <a href="{{ route('public.pakan.show', $nextPakan->slug) }}"
                                    class="flex items-center text-green-600 hover:text-green-700 group text-right">
                                    <div>
                                        <div class="text-sm text-gray-500">Selanjutnya</div>
                                        <div class="font-medium">{{ Str::limit($nextPakan->judul, 40) }}</div>
                                    </div>
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </nav>
                </article>

                <!-- Sidebar -->
                <aside class="lg:col-span-1 print-hide">
                    <div class="bg-green-50 rounded-lg p-6 mb-8">
                        <h3 class="font-semibold text-green-900 mb-4">Informasi Ringkas</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-green-700 font-medium">Jenis Pakan:</span>
                                <span class="text-green-800">{{ ucfirst($pakan->jenis_pakan ?? 'Hijauan') }}</span>
                            </div>
                            @if ($pakan->sumber)
                                <div>
                                    <span class="text-green-700 font-medium">Sumber:</span>
                                    <span class="text-green-800">{{ $pakan->sumber }}</span>
                                </div>
                            @endif
                            <div>
                                <span class="text-green-700 font-medium">Dipublikasi:</span>
                                <span class="text-green-800">
                                    @if ($pakan->created_at)
                                        {{ \Carbon\Carbon::parse($pakan->created_at)->format('d F Y') }}
                                    @else
                                        {{ date('d F Y') }}
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-green-700 font-medium">Estimasi Baca:</span>
                                <span
                                    class="text-green-800">{{ ceil(str_word_count(strip_tags($pakan->deskripsi ?? '')) / 200) }}
                                    menit</span>
                            </div>
                        </div>
                    </div>

                    <!-- Related Articles -->
                    @if (isset($relatedPakan) && $relatedPakan->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                Artikel Terkait
                            </h3>
                            <div class="space-y-4">
                                @foreach ($relatedPakan as $related)
                                    <a href="{{ route('public.pakan.show', $related->slug ?? Str::slug($related->judul)) }}"
                                        class="block related-card p-3 rounded-lg border border-gray-100 hover:border-green-200 hover:bg-green-50">
                                        <div class="flex space-x-3">
                                            @if ($related->foto)
                                                <img src="{{ asset('storage/informasi-pakan/' . $related->foto) }}"
                                                    alt="{{ $related->judul }}"
                                                    class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            @else
                                                <div
                                                    class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-8 h-8 text-green-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1">
                                                    {{ $related->judul }}
                                                </h4>
                                                <p class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($related->created_at)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href=""
                                    class="inline-flex items-center text-green-600 hover:text-green-700 text-sm font-medium">
                                    Lihat Semua Artikel
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </main>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 image-modal">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative max-w-4xl max-h-full">
                <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt=""
                    class="max-w-full max-h-[80vh] object-contain rounded-lg">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Generate Table of Contents
        document.addEventListener('DOMContentLoaded', function() {
            generateTableOfContents();
            setupScrollSpy();
        });

        function generateTableOfContents() {
            const content = document.querySelector('.content-prose');
            const tocContainer = document.getElementById('tableOfContents');

            if (!content || !tocContainer) return;

            const headings = content.querySelectorAll('h1, h2, h3, h4, h5, h6');

            if (headings.length === 0) {
                tocContainer.innerHTML = '<p class="text-gray-500 text-sm">Tidak ada heading ditemukan</p>';
                return;
            }

            let tocHTML = '';
            headings.forEach((heading, index) => {
                const id = `heading-${index}`;
                heading.id = id;

                const level = parseInt(heading.tagName.charAt(1));
                const marginLeft = (level - 1) * 12;

                tocHTML += `
                <a href="#${id}" class="toc-item block text-gray-600 hover:text-green-600 transition-colors" style="margin-left: ${marginLeft}px;">
                    ${heading.textContent}
                </a>
            `;
            });

            tocContainer.innerHTML = tocHTML;
        }

        function setupScrollSpy() {
            const tocItems = document.querySelectorAll('.toc-item');
            const headings = document.querySelectorAll(
                '.content-prose h1, .content-prose h2, .content-prose h3, .content-prose h4, .content-prose h5, .content-prose h6'
            );

            function updateActiveHeading() {
                let current = '';
                headings.forEach(heading => {
                    const rect = heading.getBoundingClientRect();
                    if (rect.top <= 100) {
                        current = heading.id;
                    }
                });

                tocItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('href') === `#${current}`) {
                        item.classList.add('active');
                    }
                });
            }

            window.addEventListener('scroll', updateActiveHeading);
            updateActiveHeading(); // Initial call
        }

        // Share Functions
        function shareWhatsApp() {
            const title = document.title;
            const url = window.location.href;
            const text = `${title} - ${url}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
        }

        function shareFacebook() {
            const url = window.location.href;
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
        }

        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showNotification('Link berhasil disalin ke clipboard!', 'success');
            }).catch(() => {
                showNotification('Gagal menyalin link', 'error');
            });
        }

        function printPage() {
            window.print();
        }

        // Image Modal
        function openImageModal(src, alt) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = src;
            modalImage.alt = alt;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('imageModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Notification function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } transform translate-x-full transition-transform`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Smooth scrolling for TOC links
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('toc-item')) {
                e.preventDefault();
                const targetId = e.target.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });

        // Add zoom cursor to images
        document.querySelectorAll('.zoom-image').forEach(img => {
            img.style.cursor = 'zoom-in';
        });
    </script>
@endpush
