@extends('layouts.app')

@section('title', 'Buat Laporan Kesehatan')
@section('page-title', 'Buat Laporan Kesehatan')
@section('page-description', 'Dokumentasi pemeriksaan dan diagnosis kesehatan ternak')

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

        .form-section {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .form-section:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
        }

        .health-indicator {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .health-indicator:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .health-indicator.selected {
            border-color: #3b82f6;
            background: #3b82f6;
            color: white;
        }

        .health-indicator input[type="radio"] {
            margin-right: 0.5rem;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .floating-save {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
        }

        .success-message {
            background: #d1fae5;
            border: 1px solid #86efac;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Laporan Kesehatan Ternak</h1>
                    <p class="text-blue-100">Buat Laporan Kesehatan Ternak</p>
                </div>
            </div>
        </div>

        <div class="tab-container">
            <!-- Tab Navigation -->
            <div class="tab-nav">
                <button class="tab-button active" onclick="switchTab('form')" id="tab-form">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Laporan
                    </div>
                </button>
                <button class="tab-button" onclick="switchTab('list')" id="tab-list">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Daftar Laporan
                    </div>
                </button>
            </div>

            <!-- Tab Content: Form Laporan -->
            <div id="content-form" class="tab-content active">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="healthReportForm" action="{{ route('penyuluh.laporan.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Section 1: Informasi Dasar -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-blue-600 text-sm font-bold">1</span>
                                    </span>
                                    Informasi Dasar Pemeriksaan
                                </h3>
                            </div>

                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="idPeternak" class="block text-sm font-medium text-gray-700 mb-2">
                                            Peternak <span class="text-red-500">*</span>
                                        </label>
                                        <select id="idPeternak" name="idPeternak" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Peternak</option>
                                            @foreach ($peternaks ?? [] as $peternak)
                                                <option value="{{ $peternak->idUser }}">{{ $peternak->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="idTernak" class="block text-sm font-medium text-gray-700 mb-2">
                                            Ternak <span class="text-red-500">*</span>
                                        </label>
                                        <select id="idTernak" name="idTernak" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Ternak</option>
                                            @foreach ($ternaks ?? [] as $ternak)
                                                <option value="{{ $ternak->idTernak }}"
                                                    data-peternak="{{ $ternak->idPemilik }}">
                                                    {{ $ternak->namaTernak }} - {{ $ternak->jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="tanggal_pemeriksaan"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Tanggal Pemeriksaan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="datetime-local" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                                            required value="{{ date('Y-m-d\TH:i') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="berat_badan" class="block text-sm font-medium text-gray-700 mb-2">
                                            Berat Badan (kg)
                                        </label>
                                        <input type="number" id="berat_badan" name="berat_badan" min="0"
                                            step="0.1"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                            placeholder="Contoh: 450.5">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Pemeriksaan Fisik -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <span class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-green-600 text-sm font-bold">2</span>
                                    </span>
                                    Pemeriksaan Fisik
                                </h3>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Suhu Tubuh -->
                                <div>
                                    <label for="suhu_tubuh" class="block text-sm font-medium text-gray-700 mb-3">
                                        Suhu Tubuh (°C) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="suhu_tubuh" name="suhu_tubuh" step="0.1" min="35"
                                        max="45" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Contoh: 38.5">
                                    <p class="text-xs text-gray-500 mt-1">Normal: 37.5°C - 39.5°C untuk sapi</p>
                                </div>

                                <!-- Nafsu Makan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Nafsu Makan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="status-grid">
                                        <label class="health-indicator">
                                            <input type="radio" name="nafsu_makan" value="baik" required>
                                            <span>Baik (Makan normal)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="nafsu_makan" value="menurun" required>
                                            <span>Menurun (Makan sedikit)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="nafsu_makan" value="tidak_ada" required>
                                            <span>Tidak Ada (Tidak mau makan)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Pernafasan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Pernafasan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="status-grid">
                                        <label class="health-indicator">
                                            <input type="radio" name="pernafasan" value="normal" required>
                                            <span>Normal (12-20 x/menit)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="pernafasan" value="cepat" required>
                                            <span>Cepat (>20 x/menit)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="pernafasan" value="lambat" required>
                                            <span>Lambat (<12 x/menit)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="pernafasan" value="sesak" required>
                                            <span>Sesak/Sulit</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Kulit & Bulu -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Kondisi Kulit & Bulu <span class="text-red-500">*</span>
                                    </label>
                                    <div class="status-grid">
                                        <label class="health-indicator">
                                            <input type="radio" name="kulit_bulu" value="normal" required>
                                            <span>Normal (Halus, bersih)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="kulit_bulu" value="kusam" required>
                                            <span>Kusam/Kering</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="kulit_bulu" value="luka" required>
                                            <span>Ada Luka/Lecet</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="kulit_bulu" value="parasit" required>
                                            <span>Ada Parasit</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Mata & Hidung -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Kondisi Mata & Hidung <span class="text-red-500">*</span>
                                    </label>
                                    <div class="status-grid">
                                        <label class="health-indicator">
                                            <input type="radio" name="mata_hidung" value="normal" required>
                                            <span>Normal (Bersih, tidak berair)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="mata_hidung" value="berair" required>
                                            <span>Berair/Keluar Cairan</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="mata_hidung" value="bengkak" required>
                                            <span>Bengkak/Merah</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="mata_hidung" value="bernanah" required>
                                            <span>Bernanah</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Feses -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Kondisi Feses/Kotoran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="status-grid">
                                        <label class="health-indicator">
                                            <input type="radio" name="feses" value="normal" required>
                                            <span>Normal (Padat, warna coklat)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="feses" value="encer" required>
                                            <span>Encer/Diare</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="feses" value="keras" required>
                                            <span>Keras/Konstipasi</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="feses" value="berdarah" required>
                                            <span>Berdarah/Berlendir</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Aktivitas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Aktivitas/Tingkah Laku <span class="text-red-500">*</span>
                                    </label>
                                    <div class="status-grid">
                                        <label class="health-indicator">
                                            <input type="radio" name="aktivitas" value="aktif" required>
                                            <span>Aktif (Bergerak normal)</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="aktivitas" value="lesu" required>
                                            <span>Lesu/Kurang Aktif</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="aktivitas" value="gelisah" required>
                                            <span>Gelisah/Tidak Tenang</span>
                                        </label>
                                        <label class="health-indicator">
                                            <input type="radio" name="aktivitas" value="lemas" required>
                                            <span>Lemas/Tidak Bisa Berdiri</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Tindakan & Rekomendasi -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <span class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-purple-600 text-sm font-bold">3</span>
                                    </span>
                                    Tindakan & Rekomendasi
                                </h3>
                            </div>

                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tindakan yang Dilakukan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="tindakan" name="tindakan" rows="4" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                        placeholder="Jelaskan tindakan medis yang telah dilakukan, seperti pemberian obat, vaksin, dll..."></textarea>
                                </div>

                                <div>
                                    <label for="rekomendasi" class="block text-sm font-medium text-gray-700 mb-2">
                                        Rekomendasi Perawatan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="rekomendasi" name="rekomendasi" rows="4" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                        placeholder="Berikan rekomendasi perawatan selanjutnya, pola makan, obat-obatan, dll..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between items-center pt-6 border-t border-gray-200 no-print">
                            <button type="submit" id="submitBtn"
                                class="flex px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Laporan Kesehatan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab Content: Daftar Laporan -->
            <div id="content-list" class="tab-content">
                <!-- Search Bar -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="search-container flex-1 lg:max-w-md">
                            <input type="text" id="searchInput"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Cari laporan..." onkeyup="searchReports()">
                        </div>
                    </div>
                </div>

                <!-- Reports Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="reportsGrid">
                    @forelse ($laporans ?? [] as $laporan)
                        <div
                            class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $laporan->ternak->namaTernak ?? 'Ternak' }}
                                    </h3>
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal_pemeriksaan)->format('d M Y') }}
                                    </span>
                                </div>

                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <p><strong>Peternak:</strong> {{ $laporan->peternak->name ?? '-' }}</p>
                                    <p><strong>Suhu:</strong> {{ $laporan->suhu_tubuh }}°C</p>
                                    <p><strong>Nafsu Makan:</strong> {{ ucfirst($laporan->nafsu_makan) }}</p>
                                </div>

                                <div class="flex space-x-2">
                                    <button onclick="viewReport({{ $laporan->id }})"
                                        class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">Belum Ada Laporan</h3>
                            <p class="mt-1 text-gray-500">Belum ada laporan kesehatan yang tersedia.</p>
                            <button onclick="switchTab('form')"
                                class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Buat Laporan Pertama
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
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

        // Filter ternak berdasarkan peternak yang dipilih
        document.getElementById('idPeternak').addEventListener('change', function() {
            const peternakId = this.value;
            const ternakSelect = document.getElementById('idTernak');
            const ternakOptions = ternakSelect.querySelectorAll('option');

            ternakOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }

                const ternakPeternakId = option.getAttribute('data-peternak');
                if (!peternakId || ternakPeternakId === peternakId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            // Reset pilihan ternak
            ternakSelect.value = '';
        });

        // Handle radio button selection styling
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all indicators in the same group
                const groupName = this.name;
                document.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                    r.closest('.health-indicator').classList.remove('selected');
                });

                // Add selected class to current indicator
                this.closest('.health-indicator').classList.add('selected');
            });
        });

        // Form validation and submission
        document.getElementById('healthReportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
            submitBtn.disabled = true;

            // Simulate form submission (replace with actual submission)
            setTimeout(() => {
                alert('Laporan kesehatan berhasil disimpan!');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                // this.submit(); // Uncomment this line for actual submission
            }, 2000);
        });

        function searchReports() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const reportCards = document.querySelectorAll('#reportsGrid > div');

            reportCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function viewReport(reportId) {
            // Implementation for viewing report details
            alert(`Viewing report ID: ${reportId}`);
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set default values
            document.getElementById('tanggal_pemeriksaan').value = new Date().toISOString().slice(0, 16);
        });
        // Improved JavaScript for Health Report Form

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

        // Filter ternak berdasarkan peternak yang dipilih
        document.getElementById('idPeternak').addEventListener('change', function() {
            const peternakId = this.value;
            const ternakSelect = document.getElementById('idTernak');
            const ternakOptions = ternakSelect.querySelectorAll('option');

            ternakOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }

                const ternakPeternakId = option.getAttribute('data-peternak');
                if (!peternakId || ternakPeternakId === peternakId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            // Reset pilihan ternak
            ternakSelect.value = '';
        });

        // Handle radio button selection styling
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all indicators in the same group
                const groupName = this.name;
                document.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                    r.closest('.health-indicator').classList.remove('selected');
                });

                // Add selected class to current indicator
                this.closest('.health-indicator').classList.add('selected');
            });
        });

        // Form validation and submission
        document.getElementById('healthReportForm').addEventListener('submit', function(e) {
            // Prevent default temporarily for validation
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;

            // Validate required fields
            if (!validateForm()) {
                return false;
            }

            // Show loading state
            submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;
            submitBtn.disabled = true;

            // Submit the form
            this.submit();
        });

        // Form validation function
        function validateForm() {
            const requiredFields = [
                'idPeternak',
                'idTernak',
                'tanggal_pemeriksaan',
                'suhu_tubuh',
                'tindakan',
                'rekomendasi'
            ];

            const requiredRadios = [
                'nafsu_makan',
                'pernafasan',
                'kulit_bulu',
                'mata_hidung',
                'feses',
                'aktivitas'
            ];

            let isValid = true;
            let firstErrorField = null;

            // Clear previous error styling
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });
            document.querySelectorAll('.error-message').forEach(el => {
                el.remove();
            });

            // Validate regular fields
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field || !field.value.trim()) {
                    isValid = false;
                    if (field) {
                        field.classList.add('border-red-500');
                        if (!firstErrorField) firstErrorField = field;
                        showFieldError(field, 'Field ini wajib diisi');
                    }
                }
            });

            // Validate radio button groups
            requiredRadios.forEach(radioName => {
                const radioGroup = document.querySelectorAll(`input[name="${radioName}"]`);
                const isChecked = Array.from(radioGroup).some(radio => radio.checked);

                if (!isChecked) {
                    isValid = false;
                    const container = radioGroup[0]?.closest('.form-section');
                    if (container && !firstErrorField) {
                        firstErrorField = container;
                        showSectionError(container, `Pilih salah satu opsi untuk ${getFieldLabel(radioName)}`);
                    }
                }
            });

            // Validate specific field values
            const suhuTubuh = document.getElementById('suhu_tubuh');
            if (suhuTubuh.value && (suhuTubuh.value < 35 || suhuTubuh.value > 45)) {
                isValid = false;
                suhuTubuh.classList.add('border-red-500');
                if (!firstErrorField) firstErrorField = suhuTubuh;
                showFieldError(suhuTubuh, 'Suhu tubuh harus antara 35°C - 45°C');
            }

            const beratBadan = document.getElementById('berat_badan');
            if (beratBadan.value && beratBadan.value < 0) {
                isValid = false;
                beratBadan.classList.add('border-red-500');
                if (!firstErrorField) firstErrorField = beratBadan;
                showFieldError(beratBadan, 'Berat badan tidak boleh kurang dari 0');
            }

            // Scroll to first error
            if (!isValid && firstErrorField) {
                firstErrorField.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                firstErrorField.focus();
            }

            return isValid;
        }

        // Show field error message
        function showFieldError(field, message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-red-500 text-xs mt-1';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        // Show section error message
        function showSectionError(section, message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4';
            errorDiv.textContent = message;
            section.querySelector('.p-6').insertBefore(errorDiv, section.querySelector('.p-6').firstChild);
        }

        // Get field label for error messages
        function getFieldLabel(fieldName) {
            const labels = {
                'nafsu_makan': 'Nafsu Makan',
                'pernafasan': 'Pernafasan',
                'kulit_bulu': 'Kondisi Kulit & Bulu',
                'mata_hidung': 'Kondisi Mata & Hidung',
                'feses': 'Kondisi Feses',
                'aktivitas': 'Aktivitas/Tingkah Laku'
            };
            return labels[fieldName] || fieldName;
        }

        // Search reports function
        function searchReports() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const reportCards = document.querySelectorAll('#reportsGrid > div');

            reportCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // View report function
        function viewReport(reportId) {
            window.location.href = `/penyuluh/laporan/${reportId}`;
        }

        // Real-time suhu tubuh validation
        document.getElementById('suhu_tubuh').addEventListener('input', function() {
            const value = parseFloat(this.value);
            const warningDiv = document.getElementById('suhu-warning');

            // Remove existing warning
            if (warningDiv) {
                warningDiv.remove();
            }

            if (value && (value < 37.5 || value > 39.5)) {
                const warning = document.createElement('div');
                warning.id = 'suhu-warning';
                warning.className = 'text-orange-600 text-xs mt-1 font-medium';

                if (value < 37.5) {
                    warning.textContent = '⚠️ Suhu di bawah normal (mungkin hipotermia)';
                } else if (value > 39.5) {
                    warning.textContent = '⚠️ Suhu di atas normal (mungkin demam)';
                }

                this.parentNode.appendChild(warning);
            }
        });

        // Auto-save draft (optional feature)
        function saveDraft() {
            const formData = new FormData(document.getElementById('healthReportForm'));
            const draftData = {};

            for (let [key, value] of formData.entries()) {
                draftData[key] = value;
            }

            localStorage.setItem('healthReportDraft', JSON.stringify(draftData));
        }

        // Load draft on page load
        function loadDraft() {
            const draftData = localStorage.getItem('healthReportDraft');
            if (draftData) {
                const data = JSON.parse(draftData);

                Object.keys(data).forEach(key => {
                    const field = document.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === 'radio') {
                            const radioButton = document.querySelector(`[name="${key}"][value="${data[key]}"]`);
                            if (radioButton) {
                                radioButton.checked = true;
                                radioButton.dispatchEvent(new Event('change'));
                            }
                        } else {
                            field.value = data[key];
                        }
                    }
                });
            }
        }

        // Clear draft after successful submission
        function clearDraft() {
            localStorage.removeItem('healthReportDraft');
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set default values
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('tanggal_pemeriksaan').value = now.toISOString().slice(0, 16);

            // Load draft if exists
            loadDraft();

            // Auto-save draft every 30 seconds
            setInterval(saveDraft, 30000);

            // Handle successful form submission
            if (window.location.search.includes('success=1')) {
                clearDraft();
            }
        });
    </script>
@endpush
