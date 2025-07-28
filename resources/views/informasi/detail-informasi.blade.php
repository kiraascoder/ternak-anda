@extends('layouts.public')

@section('title', $informasi->judul ?? 'Detail Informasi informasi')
@section('meta-description',
    Str::limit(
    strip_tags(
    $informasi->deskripsi ??
    'Informasi lengkap tentang informasi
    ternak',
    ),
    160,
    ))

    @push('styles')
        <style>
            .breadcrumb-item+.breadcrumb-item::before {
                content: "/";
                color: #6b7280;
                margin: 0 8px;
            }

            .kategori-badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-weight: 500;
            }

            .jenis-kategori {
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
                        informasi</a>
                </li>
                <li class="breadcrumb-item text-gray-500">
                    {{ Str::limit($informasi->judul ?? 'Detail informasi', 50) }}
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
                            <span class="kategori-badge jenis-kategori{{ $informasi->kategori ?? 'hijauan' }}">
                                {{ ucfirst($informasi->kategori ?? 'Hijauan') }}
                            </span>
                            @if ($informasi->sumber)
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                    Sumber: {{ $informasi->sumber }}
                                </div>
                            @endif
                        </div>

                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                            {{ $informasi->judul ?? 'Judul Informasi informasi' }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                @if ($informasi->created_at)
                                    Dipublikasi: {{ \Carbon\Carbon::parse($informasi->created_at)->format('d F Y') }}
                                @else
                                    Dipublikasi: {{ date('d F Y') }}
                                @endif
                            </div>

                            @if ($informasi->updated_at && $informasi->updated_at != $informasi->created_at)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Diperbarui: {{ \Carbon\Carbon::parse($informasi->updated_at)->format('d F Y') }}
                                </div>
                            @endif

                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Waktu baca: {{ ceil(str_word_count(strip_tags($informasi->deskripsi ?? '')) / 200) }} menit
                            </div>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    @if (isset($informasi->foto) && $informasi->foto)
                        <div class="mb-8">
                            <img src="{{ asset('storage/informasi/' . $informasi->foto) }}" alt="{{ $informasi->judul }}"
                                class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg zoom-image"
                                onclick="openImageModal(this.src, '{{ $informasi->judul }}')">
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="content-prose text-gray-800">
                        {!! $informasi->deskripsi ?? '<p>Konten informasi informasi tidak tersedia.</p>' !!}
                    </div>

                    <!-- Tags/Categories -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Kategori:</span>
                            <span class="kategori-badge jenis-{{ $informasi->kategori ?? 'hijauan' }}">
                                {{ ucfirst($informasi->kategori ?? 'Hijauan') }}
                            </span>
                        </div>
                    </div>

                </article>

                <!-- Sidebar -->
                <aside class="lg:col-span-1 print-hide">
                    <div class="bg-green-50 rounded-lg p-6 mb-8">
                        <h3 class="font-semibold text-green-900 mb-4">Informasi Ringkas</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-green-700 font-medium">Jenis informasi:</span>
                                <span class="text-green-800">{{ ucfirst($informasi->kategori ?? 'Hijauan') }}</span>
                            </div>
                            @if ($informasi->sumber)
                                <div>
                                    <span class="text-green-700 font-medium">Sumber:</span>
                                    <span class="text-green-800">{{ $informasi->sumber }}</span>
                                </div>
                            @endif
                            <div>
                                <span class="text-green-700 font-medium">Dipublikasi:</span>
                                <span class="text-green-800">
                                    @if ($informasi->created_at)
                                        {{ \Carbon\Carbon::parse($informasi->created_at)->format('d F Y') }}
                                    @else
                                        {{ date('d F Y') }}
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-green-700 font-medium">Estimasi Baca:</span>
                                <span
                                    class="text-green-800">{{ ceil(str_word_count(strip_tags($informasi->deskripsi ?? '')) / 200) }}
                                    menit</span>
                            </div>
                        </div>
                    </div>
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
