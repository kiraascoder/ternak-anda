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

        /* Table Styles */
        .reports-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .reports-table th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            font-size: 0.875rem;
        }

        .reports-table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }

        .reports-table tbody tr:hover {
            background: #f8fafc;
        }

        .reports-table tbody tr.hidden-report {
            display: none;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-badge.sehat {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.perlu_perhatian {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.sakit {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-action {
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-view {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-view:hover {
            background: #bfdbfe;
        }

        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #fecaca;
        }

        .checkbox-cell {
            width: 40px;
            text-align: center;
        }

        .checkbox-cell input[type="checkbox"] {
            transform: scale(1.2);
        }

        /* Modal Styles */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-container {
            max-height: 90vh;
            overflow-y: auto;
        }

        .health-badge {
            transition: all 0.3s ease;
        }

        .health-indicator-circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .status-sehat {
            background-color: #10b981;
        }

        .status-perlu_perhatian {
            background-color: #f59e0b;
        }

        .status-sakit {
            background-color: #ef4444;
        }

        .parameter-card {
            transition: all 0.2s ease;
        }

        .parameter-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                font-size: 12px !important;
            }

            .modal-overlay {
                background: white !important;
                backdrop-filter: none !important;
            }

            .modal-container {
                max-height: none !important;
                overflow: visible !important;
                box-shadow: none !important;
            }

            .print-header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
            }

            .print-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .print-table th,
            .print-table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            .print-table th {
                background: #f0f0f0;
                font-weight: bold;
            }

            .farmer-details {
                margin-top: 30px;
                padding: 15px;
                border: 1px solid #000;
            }

            .farmer-details h4 {
                margin: 0 0 10px 0;
                font-weight: bold;
            }

            .farmer-info {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                margin-bottom: 10px;
            }

            .print-footer {
                margin-top: 30px;
                text-align: center;
                font-size: 10px;
            }
        }

        /* Filter Button Styles */
        .filter-btn {
            background: white;
            border-color: #d1d5db;
            color: #6b7280;
        }

        .filter-btn:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            color: #374151;
        }

        .filter-btn.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .filter-btn.active:hover {
            background: #2563eb;
            border-color: #2563eb;
        }

        /* Date input styling */
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(0.5);
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            filter: invert(0.3);
        }

        .hidden-report {
            display: none !important;
        }

        /* Animation for filtering */
        .report-row {
            transition: all 0.3s ease;
        }

        .report-row.filtering-out {
            opacity: 0.3;
            transform: scale(0.95);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6b7280;
        }

        .empty-state svg {
            margin: 0 auto 1rem;
            color: #d1d5db;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            .reports-table {
                font-size: 0.75rem;
            }

            .reports-table th,
            .reports-table td {
                padding: 0.5rem 0.25rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .btn-action {
                padding: 0.375rem;
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
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
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

            <!-- Tab Content: Daftar Laporan (Table Format) -->
            <div id="content-list" class="tab-content">
                <!-- Bulk Actions Bar -->
                <div id="bulkActionsBar" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 hidden">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-blue-800 font-medium" id="selectedCount">0 laporan dipilih</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="bulkPrint()"
                                class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                                Cetak
                            </button>
                            <button onclick="confirmBulkDelete()"
                                class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Hapus
                            </button>
                            <button onclick="clearSelection()"
                                class="px-3 py-1 bg-gray-400 text-white rounded text-sm hover:bg-gray-500">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Bar -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div
                        class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                        <!-- Search Input -->
                        <div class="search-container flex-1 lg:max-w-md">
                            <input type="text" id="searchInput"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Cari laporan..." onkeyup="searchReports()">
                        </div>

                        <!-- Date Filter Buttons -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-700 mr-2">Filter:</span>
                            <button onclick="filterByPeriod('all')" id="filter-all"
                                class="filter-btn active px-3 py-2 text-sm rounded-lg border transition-colors">
                                Semua
                            </button>
                            <button onclick="filterByPeriod('today')" id="filter-today"
                                class="filter-btn px-3 py-2 text-sm rounded-lg border transition-colors">
                                Hari Ini
                            </button>
                            <button onclick="filterByPeriod('week')" id="filter-week"
                                class="filter-btn px-3 py-2 text-sm rounded-lg border transition-colors">
                                Minggu Ini
                            </button>
                            <button onclick="filterByPeriod('month')" id="filter-month"
                                class="filter-btn px-3 py-2 text-sm rounded-lg border transition-colors">
                                Bulan Ini
                            </button>

                            <!-- Custom Date Range -->
                            <div class="flex items-center space-x-2 ml-4">
                                <input type="date" id="dateFrom"
                                    class="px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                    onchange="filterByCustomRange()">
                                <span class="text-gray-500 text-sm">s/d</span>
                                <input type="date" id="dateTo"
                                    class="px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                    onchange="filterByCustomRange()">
                                <button onclick="clearCustomRange()"
                                    class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded hover:bg-gray-300"
                                    title="Clear custom range">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <!-- Results Count -->
                        <div class="text-sm text-gray-600" id="resultsCount">
                            Menampilkan semua laporan
                        </div>
                    </div>
                </div>

                <!-- Reports Table -->
                <div class="overflow-x-auto">
                    <table class="reports-table" id="reportsTable">
                        <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="selectAllReports" onchange="toggleSelectAll()">
                                </th>
                                <th>No</th>
                                <th>Tanggal Pemeriksaan</th>
                                <th>Nama Ternak</th>
                                <th>Jenis</th>
                                <th>Peternak</th>
                                <th>Suhu (°C)</th>
                                <th>Status Kesehatan</th>
                                <th>Tindakan</th>
                                <th class="no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="reportsTableBody">
                            @forelse ($laporans ?? [] as $index => $laporan)
                                <tr class="report-row" data-report-id="{{ $laporan->id }}"
                                    data-date="{{ \Carbon\Carbon::parse($laporan->tanggal_pemeriksaan)->format('Y-m-d') }}">
                                    <td class="checkbox-cell no-print">
                                        <input type="checkbox" class="report-checkbox" value="{{ $laporan->id }}"
                                            onchange="toggleReportSelection(this.value, this.checked)">
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($laporan->tanggal_pemeriksaan)->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($laporan->tanggal_pemeriksaan)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="font-medium text-gray-900">
                                        {{ $laporan->ternak->namaTernak ?? '-' }}
                                    </td>
                                    <td class="text-sm text-gray-600">
                                        {{ $laporan->ternak->jenis ?? '-' }}
                                    </td>
                                    <td>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $laporan->peternak->nama ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="font-mono text-sm">{{ $laporan->suhu_tubuh }}°</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'sehat' => 'sehat',
                                                'perlu_perhatian' => 'perlu_perhatian',
                                                'sakit' => 'sakit',
                                            ];
                                            $statusTexts = [
                                                'sehat' => 'Sehat',
                                                'perlu_perhatian' => 'Perlu Perhatian',
                                                'sakit' => 'Sakit',
                                            ];
                                        @endphp
                                        <span
                                            class="status-badge {{ $statusClasses[$laporan->status_kesehatan] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusTexts[$laporan->status_kesehatan] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-xs text-gray-600 max-w-xs">
                                            {{ Str::limit($laporan->tindakan, 50) }}
                                        </div>
                                    </td>
                                    <td class="no-print">
                                        <div class="action-buttons">
                                            <button onclick="viewReport({{ $laporan->id }})" class="btn-action btn-view"
                                                title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>

                                            <button
                                                onclick="confirmDeleteReport({{ $laporan->id }}, '{{ $laporan->ternak->namaTernak ?? 'Ternak' }}')"
                                                class="btn-action btn-delete" title="Hapus">
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
                                <tr id="emptyRow">
                                    <td colspan="10" class="empty-state">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Laporan</h3>
                                        <p class="text-gray-500 mb-4">Belum ada laporan kesehatan yang tersedia.</p>
                                        <button onclick="switchTab('form')"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            Buat Laporan Pertama
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Laporan Kesehatan (with Print Format) -->
    <div id="reportModal" class="fixed inset-0 z-50 hidden modal-overlay">
        <div class="min-h-screen px-4 py-6 flex items-center justify-center">
            <div class="modal-container bg-white rounded-xl shadow-2xl w-full max-w-6xl fade-in">
                <!-- Print Header (only visible when printing) -->
                <div class="print-header" style="display: none;">
                    <h1 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">LAPORAN KESEHATAN TERNAK</h1>
                    <p style="font-size: 12px; color: #666;">Sistem Manajemen Peternakan</p>
                </div>

                <!-- Modal Header (hidden in print) -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 rounded-t-xl no-print">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold mb-2">Detail Laporan Kesehatan</h2>
                            <p class="text-blue-100" id="modalSubtitle">Hasil pemeriksaan kesehatan ternak</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="printReport()"
                                class="no-print bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                            </button>
                            <button onclick="closeModal()"
                                class="no-print bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="modalLoading" class="flex justify-center items-center p-12">
                    <div class="loading-spinner"></div>
                    <span class="ml-3 text-gray-600">Memuat data...</span>
                </div>

                <!-- Modal Content -->
                <div id="modalContent" class="hidden">
                    <!-- Print Table (only visible when printing) -->
                    <div style="display: none;" class="print-only">
                        <table class="print-table">
                            <tr>
                                <th style="width: 30%;">Informasi</th>
                                <th>Detail</th>
                            </tr>
                            <tr>
                                <td><strong>Nama Ternak</strong></td>
                                <td id="printTernakName">-</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Ternak</strong></td>
                                <td id="printTernakType">-</td>
                            </tr>
                            <tr>
                                <td><strong>Berat Badan</strong></td>
                                <td id="printTernakWeight">-</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pemeriksaan</strong></td>
                                <td id="printExamDate">-</td>
                            </tr>
                            <tr>
                                <td><strong>Pemeriksa</strong></td>
                                <td id="printExaminer">-</td>
                            </tr>
                            <tr>
                                <td><strong>Suhu Tubuh</strong></td>
                                <td id="printTemperature">-</td>
                            </tr>
                            <tr>
                                <td><strong>Status Kesehatan</strong></td>
                                <td id="printHealthStatus">-</td>
                            </tr>
                        </table>

                        <table class="print-table" style="margin-top: 20px;">
                            <tr>
                                <th colspan="2" style="text-align: center; background: #f0f0f0;">HASIL PEMERIKSAAN
                                    FISIK</th>
                            </tr>
                            <tr>
                                <td><strong>Nafsu Makan</strong></td>
                                <td id="printNafsuMakan">-</td>
                            </tr>
                            <tr>
                                <td><strong>Pernafasan</strong></td>
                                <td id="printPernafasan">-</td>
                            </tr>
                            <tr>
                                <td><strong>Kulit & Bulu</strong></td>
                                <td id="printKulitBulu">-</td>
                            </tr>
                            <tr>
                                <td><strong>Mata & Hidung</strong></td>
                                <td id="printMataHidung">-</td>
                            </tr>
                            <tr>
                                <td><strong>Kondisi Feses</strong></td>
                                <td id="printFeses">-</td>
                            </tr>
                            <tr>
                                <td><strong>Aktivitas</strong></td>
                                <td id="printAktivitas">-</td>
                            </tr>
                        </table>

                        <table class="print-table" style="margin-top: 20px;">
                            <tr>
                                <th colspan="2" style="text-align: center; background: #f0f0f0;">TINDAKAN & REKOMENDASI
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 30%;"><strong>Tindakan yang Dilakukan</strong></td>
                                <td id="printTindakan">-</td>
                            </tr>
                            <tr>
                                <td><strong>Rekomendasi Perawatan</strong></td>
                                <td id="printRekomendasi">-</td>
                            </tr>
                        </table>

                        <!-- Farmer Details for Print -->
                        <div class="farmer-details">
                            <h4>INFORMASI PETERNAK</h4>
                            <div class="farmer-info">
                                <div><strong>Nama:</strong> <span id="printPeternakName">-</span></div>
                                <div><strong>Alamat:</strong> <span id="printPeternakAddress">-</span></div>
                            </div>
                        </div>

                        <div class="print-footer">
                            <p>Laporan dicetak pada: <span id="printDate"></span></p>
                            <p>© Sistem Manajemen Peternakan</p>
                        </div>
                    </div>

                    <!-- Regular Modal Content (hidden in print) -->
                    <div class="no-print">
                        <!-- Basic Information -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-blue-900 mb-2">Informasi Peternak</h4>
                                    <p class="text-blue-700" id="peternakName">-</p>
                                    <p class="text-blue-600 text-sm" id="peternakAddress">-</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-green-900 mb-2">Informasi Ternak</h4>
                                    <p class="text-green-700" id="ternakName">-</p>
                                    <p class="text-green-600 text-sm" id="ternakType">-</p>
                                    <p class="text-green-600 text-sm" id="ternakWeight">-</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-purple-900 mb-2">Pemeriksaan</h4>
                                    <p class="text-purple-700" id="examDate">-</p>
                                    <p class="text-purple-600 text-sm" id="examiner">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Health Status Banner -->
                        <div class="p-6 border-b border-gray-200">
                            <div id="healthStatusBanner" class="rounded-lg p-4 text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <div id="statusIndicator" class="health-indicator-circle"></div>
                                    <h3 class="text-xl font-bold" id="healthStatusText">Status Kesehatan</h3>
                                </div>
                                <div class="flex justify-center items-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold" id="healthScore">-</div>
                                        <div class="text-sm opacity-75">Skor Kesehatan</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xl font-semibold" id="temperatureValue">-</div>
                                        <div class="text-sm opacity-75">Suhu Tubuh</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Physical Examination -->
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hasil Pemeriksaan Fisik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Nafsu Makan -->
                                <div class="parameter-card bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <h4 class="font-medium text-gray-900">Nafsu Makan</h4>
                                    </div>
                                    <p id="nafsuMakan" class="text-gray-700 font-semibold">-</p>
                                </div>

                                <!-- Pernafasan -->
                                <div class="parameter-card bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                        <h4 class="font-medium text-gray-900">Pernafasan</h4>
                                    </div>
                                    <p id="pernafasan" class="text-gray-700 font-semibold">-</p>
                                </div>

                                <!-- Kulit & Bulu -->
                                <div class="parameter-card bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                        <h4 class="font-medium text-gray-900">Kulit & Bulu</h4>
                                    </div>
                                    <p id="kulitBulu" class="text-gray-700 font-semibold">-</p>
                                </div>

                                <!-- Mata & Hidung -->
                                <div class="parameter-card bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <h4 class="font-medium text-gray-900">Mata & Hidung</h4>
                                    </div>
                                    <p id="mataHidung" class="text-gray-700 font-semibold">-</p>
                                </div>

                                <!-- Feses -->
                                <div class="parameter-card bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <h4 class="font-medium text-gray-900">Feses</h4>
                                    </div>
                                    <p id="feses" class="text-gray-700 font-semibold">-</p>
                                </div>

                                <!-- Aktivitas -->
                                <div class="parameter-card bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        <h4 class="font-medium text-gray-900">Aktivitas</h4>
                                    </div>
                                    <p id="aktivitas" class="text-gray-700 font-semibold">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions and Recommendations -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Tindakan yang Dilakukan
                                    </h4>
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <p id="data-tindakan" class="text-gray-700 leading-relaxed">-</p>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                        Rekomendasi Perawatan
                                    </h4>
                                    <div class="bg-green-50 rounded-lg p-4">
                                        <p id="data-rekomendasi" class="text-gray-700 leading-relaxed">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-6 py-4 rounded-b-xl">
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">
                                    Laporan dibuat pada: <span id="createdAt">-</span>
                                </p>
                                <div class="flex space-x-3">
                                    <button onclick="printReport()"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                            </path>
                                        </svg>
                                        Cetak
                                    </button>

                                    <button onclick="confirmDeleteCurrentReport()"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>

                                    <button onclick="closeModal()"
                                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden modal-overlay">
        <div class="min-h-screen px-4 py-6 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md fade-in">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-6 rounded-t-xl">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        <h2 class="text-xl font-bold">Konfirmasi Hapus Laporan</h2>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Yakin ingin menghapus laporan ini?</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Laporan kesehatan untuk <strong id="deleteReportName">-</strong> akan dihapus secara permanen.
                        </p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-xs text-yellow-800">
                                ⚠️ <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data akan hilang
                                secara permanen.
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button onclick="cancelDelete()"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Batal
                        </button>
                        <button onclick="executeDelete()" id="confirmDeleteBtn"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <span id="deleteButtonText">Ya, Hapus</span>
                            <span id="deleteButtonLoading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Menghapus...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Global variables
        let currentReportData = null;
        let pendingDeleteId = null;
        let pendingDeleteName = null;
        let selectedReports = new Set();
        let isDeleting = false;
        let currentFilter = 'all';
        let currentSearchTerm = '';
        let customDateRange = {
            from: null,
            to: null
        };

        // ========================================
        // TAB AND FORM FUNCTIONS
        // ========================================

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
        document.getElementById('idPeternak')?.addEventListener('change', function() {
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
        document.getElementById('healthReportForm')?.addEventListener('submit', function(e) {
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
            if (suhuTubuh?.value && (suhuTubuh.value < 35 || suhuTubuh.value > 45)) {
                isValid = false;
                suhuTubuh.classList.add('border-red-500');
                if (!firstErrorField) firstErrorField = suhuTubuh;
                showFieldError(suhuTubuh, 'Suhu tubuh harus antara 35°C - 45°C');
            }

            const beratBadan = document.getElementById('berat_badan');
            if (beratBadan?.value && beratBadan.value < 0) {
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

        // ========================================
        // TABLE AND FILTERING FUNCTIONS
        // ========================================

        // Search reports function
        function searchReports() {
            currentSearchTerm = document.getElementById('searchInput').value.toLowerCase();
            applyFilters();
        }

        // Filter by period
        function filterByPeriod(period) {
            currentFilter = period;

            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(`filter-${period}`)?.classList.add('active');

            // Clear custom date range when using preset filters
            if (period !== 'custom') {
                const dateFrom = document.getElementById('dateFrom');
                const dateTo = document.getElementById('dateTo');
                if (dateFrom) dateFrom.value = '';
                if (dateTo) dateTo.value = '';
                customDateRange = {
                    from: null,
                    to: null
                };
            }

            // Apply filters
            applyFilters();
        }

        // Filter by custom date range
        function filterByCustomRange() {
            const fromDate = document.getElementById('dateFrom')?.value;
            const toDate = document.getElementById('dateTo')?.value;

            if (fromDate && toDate) {
                customDateRange = {
                    from: fromDate,
                    to: toDate
                };
                currentFilter = 'custom';

                // Update button states
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });

                applyFilters();
            } else if (fromDate || toDate) {
                // Partial date range
                customDateRange = {
                    from: fromDate,
                    to: toDate
                };
                currentFilter = 'custom';
                applyFilters();
            }
        }

        // Clear custom date range
        function clearCustomRange() {
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';
            customDateRange = {
                from: null,
                to: null
            };

            // Reset to 'all' filter
            filterByPeriod('all');
        }

        // Apply both search and date filters
        function applyFilters() {
            const reportRows = document.querySelectorAll('#reportsTableBody .report-row');
            let visibleCount = 0;
            const totalCount = reportRows.length;

            reportRows.forEach(row => {
                const shouldShow = passesFilters(row);

                if (shouldShow) {
                    row.classList.remove('hidden-report');
                    row.style.display = 'table-row';
                    visibleCount++;
                } else {
                    row.classList.add('hidden-report');
                    row.style.display = 'none';
                }
            });

            // Update results count
            updateResultsCount(visibleCount, totalCount);

            // Show/hide empty state
            if (visibleCount === 0 && totalCount > 0) {
                showFilterEmptyState();
            } else {
                hideFilterEmptyState();
            }
        }

        // Check if a report row passes all current filters
        function passesFilters(row) {
            // Extract data from row
            const rowText = row.textContent.toLowerCase();
            const dateAttr = row.getAttribute('data-date');

            // Parse the date from the row
            let rowDate = null;
            if (dateAttr) {
                rowDate = new Date(dateAttr);
            }

            // Check search filter
            if (currentSearchTerm && !rowText.includes(currentSearchTerm)) {
                return false;
            }

            // Check date filter
            if (currentFilter !== 'all' && rowDate) {
                if (!passesDateFilter(rowDate, currentFilter)) {
                    return false;
                }
            }

            return true;
        }

        // Check if date passes the current filter
        function passesDateFilter(rowDate, filter) {
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            switch (filter) {
                case 'today':
                    const rowDateOnly = new Date(rowDate.getFullYear(), rowDate.getMonth(), rowDate.getDate());
                    return rowDateOnly.getTime() === today.getTime();

                case 'week':
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay()); // Start of week (Sunday)
                    const weekEnd = new Date(weekStart);
                    weekEnd.setDate(weekStart.getDate() + 6); // End of week (Saturday)
                    weekEnd.setHours(23, 59, 59, 999);

                    return rowDate >= weekStart && rowDate <= weekEnd;

                case 'month':
                    const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                    const monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59, 999);

                    return rowDate >= monthStart && rowDate <= monthEnd;

                case 'custom':
                    if (customDateRange.from && customDateRange.to) {
                        const fromDate = new Date(customDateRange.from);
                        const toDate = new Date(customDateRange.to + 'T23:59:59');
                        return rowDate >= fromDate && rowDate <= toDate;
                    } else if (customDateRange.from) {
                        const fromDate = new Date(customDateRange.from);
                        return rowDate >= fromDate;
                    } else if (customDateRange.to) {
                        const toDate = new Date(customDateRange.to + 'T23:59:59');
                        return rowDate <= toDate;
                    }
                    return true;

                default:
                    return true;
            }
        }

        // Update results count display
        function updateResultsCount(visible, total) {
            const countElement = document.getElementById('resultsCount');
            if (!countElement) return;

            let message = '';

            if (currentFilter === 'all' && !currentSearchTerm) {
                message = `Menampilkan semua ${total} laporan`;
            } else {
                message = `Menampilkan ${visible} dari ${total} laporan`;

                if (currentSearchTerm) {
                    message += ` (pencarian: "${currentSearchTerm}")`;
                }

                if (currentFilter !== 'all') {
                    const filterNames = {
                        'today': 'hari ini',
                        'week': 'minggu ini',
                        'month': 'bulan ini',
                        'custom': 'periode khusus'
                    };
                    message += ` (filter: ${filterNames[currentFilter]})`;
                }
            }

            countElement.textContent = message;
        }

        // Show empty state for filtered results
        function showFilterEmptyState() {
            const existingEmptyState = document.getElementById('filter-empty-state');
            if (existingEmptyState) return;

            const tableBody = document.getElementById('reportsTableBody');
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'filter-empty-state';

            let message = 'Tidak ada laporan yang ditemukan';
            let suggestion = '';

            if (currentSearchTerm && currentFilter !== 'all') {
                message = 'Tidak ada laporan yang cocok dengan pencarian dan filter';
                suggestion = 'Coba ubah kata kunci pencarian atau pilih periode yang berbeda.';
            } else if (currentSearchTerm) {
                message = 'Tidak ada laporan yang cocok dengan pencarian';
                suggestion = 'Coba gunakan kata kunci yang berbeda.';
            } else if (currentFilter !== 'all') {
                const filterNames = {
                    'today': 'hari ini',
                    'week': 'minggu ini',
                    'month': 'bulan ini',
                    'custom': 'periode yang dipilih'
                };
                message = `Tidak ada laporan untuk ${filterNames[currentFilter]}`;
                suggestion = 'Coba pilih periode yang berbeda.';
            }

            emptyRow.innerHTML = `
                <td colspan="10" class="empty-state">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">${message}</h3>
                    <p class="text-gray-500 mb-4">${suggestion}</p>
                    <div class="space-x-2">
                        <button onclick="clearAllFilters()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            Hapus Semua Filter
                        </button>
                        <button onclick="switchTab('form')" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                            Buat Laporan Baru
                        </button>
                    </div>
                </td>
            `;

            tableBody.appendChild(emptyRow);
        }

        // Hide filter empty state
        function hideFilterEmptyState() {
            const emptyState = document.getElementById('filter-empty-state');
            if (emptyState) {
                emptyState.remove();
            }
        }

        // Clear all filters and search
        function clearAllFilters() {
            // Clear search
            const searchInput = document.getElementById('searchInput');
            if (searchInput) searchInput.value = '';
            currentSearchTerm = '';

            // Clear custom date range
            clearCustomRange();

            // Reset to 'all' filter
            filterByPeriod('all');
        }

        // ========================================
        // SELECTION AND BULK ACTIONS
        // ========================================

        // Toggle select all reports
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAllReports');
            const reportCheckboxes = document.querySelectorAll('.report-checkbox');

            reportCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                toggleReportSelection(checkbox.value, checkbox.checked);
            });

            updateBulkActionsBar();
        }

        // Toggle individual report selection
        function toggleReportSelection(reportId, isSelected) {
            if (isSelected) {
                selectedReports.add(parseInt(reportId));
            } else {
                selectedReports.delete(parseInt(reportId));
            }

            // Update select all checkbox state
            updateSelectAllState();
            updateBulkActionsBar();
        }

        // Update select all checkbox state
        function updateSelectAllState() {
            const selectAllCheckbox = document.getElementById('selectAllReports');
            if (!selectAllCheckbox) return;

            const reportCheckboxes = document.querySelectorAll('.report-checkbox');
            const totalCheckboxes = reportCheckboxes.length;
            const checkedCheckboxes = Array.from(reportCheckboxes).filter(cb => cb.checked).length;

            if (checkedCheckboxes === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            } else if (checkedCheckboxes === totalCheckboxes) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = true;
                selectAllCheckbox.checked = false;
            }
        }

        // Update bulk actions bar visibility and count
        function updateBulkActionsBar() {
            const bulkBar = document.getElementById('bulkActionsBar');
            const countElement = document.getElementById('selectedCount');
            if (!bulkBar || !countElement) return;

            const count = selectedReports.size;

            if (count > 0) {
                bulkBar.classList.remove('hidden');
                countElement.textContent = `${count} laporan dipilih`;
            } else {
                bulkBar.classList.add('hidden');
            }
        }

        // Clear all selections
        function clearSelection() {
            selectedReports.clear();

            // Uncheck all checkboxes
            document.querySelectorAll('.report-checkbox').forEach(cb => {
                cb.checked = false;
            });

            const selectAllCheckbox = document.getElementById('selectAllReports');
            if (selectAllCheckbox) selectAllCheckbox.checked = false;

            updateBulkActionsBar();
        }

        // ========================================
        // VIEW REPORT AND MODAL FUNCTIONS
        // ========================================

        // View report function
        async function viewReport(reportId) {
            await showReportModal(reportId);
        }

        // Show report modal with data
        async function showReportModal(reportId) {
            const modal = document.getElementById('reportModal');
            const loading = document.getElementById('modalLoading');
            const content = document.getElementById('modalContent');

            // Show modal and loading state
            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            content.classList.add('hidden');

            try {
                console.log(`Fetching report detail for ID: ${reportId}`);

                // Fetch report data
                const response = await fetch(`/penyuluh/laporan/${reportId}/detail`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || ''
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    let errorMessage = `HTTP ${response.status}`;
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        if (response.status === 404) {
                            errorMessage = 'Endpoint tidak ditemukan. Pastikan route sudah terdaftar.';
                        } else if (response.status === 500) {
                            errorMessage = 'Server error. Periksa Laravel log untuk detail.';
                        } else if (response.status === 302 || response.status === 401) {
                            errorMessage = 'Session expired atau tidak terautentikasi. Silakan login ulang.';
                        }
                    }
                    throw new Error(errorMessage);
                }

                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Failed to load report data');
                }

                currentReportData = result.data;

                // Populate modal with data
                populateModal(result.data);

                // Hide loading and show content
                loading.classList.add('hidden');
                content.classList.remove('hidden');

            } catch (error) {
                console.error('Error fetching report:', error);

                // Show detailed error message
                loading.innerHTML = `
                    <div class="text-center max-w-md mx-auto">
                        <div class="text-red-600 mb-3">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-red-800 mb-2">Gagal Memuat Data</h3>
                        <p class="text-red-600 text-sm mb-3">${error.message}</p>
                        <div class="space-y-2">
                            <button onclick="showReportModal(${reportId})" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                🔄 Coba Lagi
                            </button>
                            <button onclick="closeModal()" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
                                Tutup
                            </button>
                        </div>
                    </div>
                `;
            }
        }

        // Function to populate modal with data
        function populateModal(data) {
            // Basic Information (Screen View)
            const peternakNameEl = document.getElementById('peternakName');
            if (peternakNameEl) peternakNameEl.textContent = data.peternak_name || '-';

            const peternakAddressEl = document.getElementById('peternakAddress');
            if (peternakAddressEl) peternakAddressEl.textContent = data.peternak_address || '-';

            const ternakNameEl = document.getElementById('ternakName');
            if (ternakNameEl) ternakNameEl.textContent = data.ternak_name || '-';

            const ternakTypeEl = document.getElementById('ternakType');
            if (ternakTypeEl) ternakTypeEl.textContent = data.ternak_type || '-';

            const ternakWeightEl = document.getElementById('ternakWeight');
            if (ternakWeightEl) ternakWeightEl.textContent = data.berat_badan ? `${data.berat_badan} kg` : 'Tidak diukur';

            const examDateEl = document.getElementById('examDate');
            if (examDateEl) examDateEl.textContent = formatDate(data.tanggal_pemeriksaan) || '-';

            const examinerEl = document.getElementById('examiner');
            if (examinerEl) examinerEl.textContent = `Dr. ${data.penyuluh_name}` || '-';

            // Print Version Elements
            const printTernakNameEl = document.getElementById('printTernakName');
            if (printTernakNameEl) printTernakNameEl.textContent = data.ternak_name || '-';

            const printTernakTypeEl = document.getElementById('printTernakType');
            if (printTernakTypeEl) printTernakTypeEl.textContent = data.ternak_type || '-';

            const printTernakWeightEl = document.getElementById('printTernakWeight');
            if (printTernakWeightEl) printTernakWeightEl.textContent = data.berat_badan ? `${data.berat_badan} kg` :
                'Tidak diukur';

            const printExamDateEl = document.getElementById('printExamDate');
            if (printExamDateEl) printExamDateEl.textContent = formatDate(data.tanggal_pemeriksaan) || '-';

            const printExaminerEl = document.getElementById('printExaminer');
            if (printExaminerEl) printExaminerEl.textContent = `Dr. ${data.penyuluh_name}` || '-';

            const printTemperatureEl = document.getElementById('printTemperature');
            if (printTemperatureEl) printTemperatureEl.textContent = `${data.suhu_tubuh}°C`;

            const printPeternakNameEl = document.getElementById('printPeternakName');
            if (printPeternakNameEl) printPeternakNameEl.textContent = data.peternak_name || '-';

            const printPeternakAddressEl = document.getElementById('printPeternakAddress');
            if (printPeternakAddressEl) printPeternakAddressEl.textContent = data.peternak_address || '-';

            // Health Status
            const statusElement = document.getElementById('healthStatusBanner');
            const statusIndicator = document.getElementById('statusIndicator');
            const statusText = document.getElementById('healthStatusText');
            const healthScore = document.getElementById('healthScore');
            const temperature = document.getElementById('temperatureValue');
            const printHealthStatus = document.getElementById('printHealthStatus');

            // Set status styling
            const statusClasses = {
                'sehat': 'bg-green-100 text-green-800',
                'perlu_perhatian': 'bg-yellow-100 text-yellow-800',
                'sakit': 'bg-red-100 text-red-800'
            };

            const statusTexts = {
                'sehat': 'Sehat',
                'perlu_perhatian': 'Perlu Perhatian',
                'sakit': 'Sakit'
            };

            if (statusElement) {
                statusElement.className =
                    `rounded-lg p-4 text-center ${statusClasses[data.status_kesehatan] || 'bg-gray-100 text-gray-800'}`;
            }
            if (statusIndicator) {
                statusIndicator.className = `health-indicator-circle status-${data.status_kesehatan}`;
            }
            if (statusText) {
                statusText.textContent = statusTexts[data.status_kesehatan] || 'Tidak Diketahui';
            }
            if (healthScore) {
                healthScore.textContent = `${data.health_score || 0}/7`;
            }
            if (temperature) {
                temperature.textContent = `${data.suhu_tubuh}°C`;
            }
            if (printHealthStatus) {
                printHealthStatus.textContent = statusTexts[data.status_kesehatan] || 'Tidak Diketahui';
            }

            // Physical Examination Parameters (Screen)
            const nafsuMakanEl = document.getElementById('nafsuMakan');
            if (nafsuMakanEl) nafsuMakanEl.textContent = data.nafsu_makan_text || '-';

            const pernafasanEl = document.getElementById('pernafasan');
            if (pernafasanEl) pernafasanEl.textContent = data.pernafasan_text || '-';

            const kulitBuluEl = document.getElementById('kulitBulu');
            if (kulitBuluEl) kulitBuluEl.textContent = data.kulit_bulu_text || '-';

            const mataHidungEl = document.getElementById('mataHidung');
            if (mataHidungEl) mataHidungEl.textContent = data.mata_hidung_text || '-';

            const fesesEl = document.getElementById('feses');
            if (fesesEl) fesesEl.textContent = data.feses_text || '-';

            const aktivitasEl = document.getElementById('aktivitas');
            if (aktivitasEl) aktivitasEl.textContent = data.aktivitas_text || '-';

            // Physical Examination Parameters (Print)
            const printNafsuMakanEl = document.getElementById('printNafsuMakan');
            if (printNafsuMakanEl) printNafsuMakanEl.textContent = data.nafsu_makan_text || '-';

            const printPernafasanEl = document.getElementById('printPernafasan');
            if (printPernafasanEl) printPernafasanEl.textContent = data.pernafasan_text || '-';

            const printKulitBuluEl = document.getElementById('printKulitBulu');
            if (printKulitBuluEl) printKulitBuluEl.textContent = data.kulit_bulu_text || '-';

            const printMataHidungEl = document.getElementById('printMataHidung');
            if (printMataHidungEl) printMataHidungEl.textContent = data.mata_hidung_text || '-';

            const printFesesEl = document.getElementById('printFeses');
            if (printFesesEl) printFesesEl.textContent = data.feses_text || '-';

            const printAktivitasEl = document.getElementById('printAktivitas');
            if (printAktivitasEl) printAktivitasEl.textContent = data.aktivitas_text || '-';

            // Actions and Recommendations (Screen)
            const dataTindakanEl = document.getElementById('data-tindakan');
            if (dataTindakanEl) dataTindakanEl.textContent = data.tindakan || '-';

            const dataRekomendasiEl = document.getElementById('data-rekomendasi');
            if (dataRekomendasiEl) dataRekomendasiEl.textContent = data.rekomendasi || '-';

            // Actions and Recommendations (Print)
            const printTindakanEl = document.getElementById('printTindakan');
            if (printTindakanEl) printTindakanEl.textContent = data.tindakan || '-';

            const printRekomendasiEl = document.getElementById('printRekomendasi');
            if (printRekomendasiEl) printRekomendasiEl.textContent = data.rekomendasi || '-';

            // Footer
            const createdAtEl = document.getElementById('createdAt');
            if (createdAtEl) createdAtEl.textContent = formatDate(data.created_at) || '-';

            // Update modal subtitle
            const modalSubtitleEl = document.getElementById('modalSubtitle');
            if (modalSubtitleEl) modalSubtitleEl.textContent = `${data.ternak_name} - ${data.peternak_name}`;

            // Set print date
            const printDateEl = document.getElementById('printDate');
            if (printDateEl) printDateEl.textContent = new Date().toLocaleDateString('id-ID');
        }

        // Function to close modal
        function closeModal() {
            const modal = document.getElementById('reportModal');
            if (modal) modal.classList.add('hidden');
            currentReportData = null;
        }

        // Function to print report
        function printReport() {
            // Show print elements
            document.querySelectorAll('.print-only').forEach(el => {
                el.style.display = 'block';
            });

            window.print();

            // Hide print elements after printing
            setTimeout(() => {
                document.querySelectorAll('.print-only').forEach(el => {
                    el.style.display = 'none';
                });
            }, 1000);
        }

        // Function to format date
        function formatDate(dateString) {
            if (!dateString) return '-';

            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };

            return date.toLocaleDateString('id-ID', options);
        }

        // ========================================
        // DELETE FUNCTIONALITY
        // ========================================

        // Show confirmation modal for single report delete
        function confirmDeleteReport(reportId, reportName) {
            pendingDeleteId = reportId;
            pendingDeleteName = reportName;

            // Update modal content
            const deleteReportNameEl = document.getElementById('deleteReportName');
            if (deleteReportNameEl) deleteReportNameEl.textContent = reportName;

            // Show confirmation modal
            const deleteModal = document.getElementById('deleteConfirmModal');
            if (deleteModal) deleteModal.classList.remove('hidden');
        }

        // Delete current report from modal
        function confirmDeleteCurrentReport() {
            if (!currentReportData) {
                showNotification('Error: Data laporan tidak ditemukan', 'error');
                return;
            }

            confirmDeleteReport(
                currentReportData.id,
                currentReportData.ternak_name || 'Laporan'
            );
        }

        // Cancel delete operation
        function cancelDelete() {
            pendingDeleteId = null;
            pendingDeleteName = null;

            // Hide confirmation modal
            const deleteModal = document.getElementById('deleteConfirmModal');
            if (deleteModal) deleteModal.classList.add('hidden');

            // Reset button state
            resetDeleteButton();
        }

        // Execute the delete operation
        async function executeDelete() {
            if (!pendingDeleteId || isDeleting) {
                return;
            }

            if (pendingDeleteId === 'bulk') {
                await executeBulkDelete();
                return;
            }

            try {
                isDeleting = true;
                showDeleteLoading();

                // Send delete request
                const response = await fetch(`/penyuluh/laporan/${pendingDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || ''
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || `HTTP ${response.status}`);
                }

                if (!result.success) {
                    throw new Error(result.message || 'Delete operation failed');
                }

                // Hide modal
                cancelDelete();

                // Show success notification
                showNotification('Laporan berhasil dihapus!', 'success');

                // Remove from UI
                removeReportFromUI(pendingDeleteId);

                // Close detail modal if it's open
                if (!document.getElementById('reportModal').classList.contains('hidden')) {
                    closeModal();
                }

                // Clear pending data
                pendingDeleteId = null;
                pendingDeleteName = null;

            } catch (error) {
                console.error('Error deleting report:', error);
                showNotification(`Gagal menghapus laporan: ${error.message}`, 'error');
                resetDeleteButton();
            } finally {
                isDeleting = false;
            }
        }

        // Confirm bulk delete
        function confirmBulkDelete() {
            if (selectedReports.size === 0) {
                showNotification('Pilih laporan yang ingin dihapus terlebih dahulu', 'warning');
                return;
            }

            const count = selectedReports.size;
            const reportName = `${count} laporan terpilih`;

            // Update confirmation modal for bulk delete
            const deleteReportNameEl = document.getElementById('deleteReportName');
            if (deleteReportNameEl) deleteReportNameEl.textContent = reportName;

            // Set special flag for bulk delete
            pendingDeleteId = 'bulk';
            pendingDeleteName = reportName;

            // Show confirmation modal
            const deleteModal = document.getElementById('deleteConfirmModal');
            if (deleteModal) deleteModal.classList.remove('hidden');
        }

        // Execute bulk delete
        async function executeBulkDelete() {
            if (selectedReports.size === 0 || isDeleting) {
                return;
            }

            try {
                isDeleting = true;
                showDeleteLoading();

                const response = await fetch('/penyuluh/laporan/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || ''
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        ids: Array.from(selectedReports)
                    })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || `HTTP ${response.status}`);
                }

                if (!result.success) {
                    throw new Error(result.message || 'Bulk delete operation failed');
                }

                // Hide modal
                cancelDelete();

                // Show success notification
                showNotification(`Berhasil menghapus ${result.deleted_count} laporan!`, 'success');

                // Remove reports from UI
                selectedReports.forEach(reportId => {
                    removeReportFromUI(reportId);
                });

                // Clear selections
                clearSelection();

            } catch (error) {
                console.error('Error in bulk delete:', error);
                showNotification(`Gagal menghapus laporan: ${error.message}`, 'error');
                resetDeleteButton();
            } finally {
                isDeleting = false;
            }
        }

        // Show loading state on delete button
        function showDeleteLoading() {
            const button = document.getElementById('confirmDeleteBtn');
            const textElement = document.getElementById('deleteButtonText');
            const loadingElement = document.getElementById('deleteButtonLoading');

            if (button) button.disabled = true;
            if (textElement) textElement.classList.add('hidden');
            if (loadingElement) loadingElement.classList.remove('hidden');
        }

        // Reset delete button to normal state
        function resetDeleteButton() {
            const button = document.getElementById('confirmDeleteBtn');
            const textElement = document.getElementById('deleteButtonText');
            const loadingElement = document.getElementById('deleteButtonLoading');

            if (button) button.disabled = false;
            if (textElement) textElement.classList.remove('hidden');
            if (loadingElement) loadingElement.classList.add('hidden');
        }

        // Remove report from UI
        function removeReportFromUI(reportId) {
            const reportRow = document.querySelector(`tr[data-report-id="${reportId}"]`);
            if (reportRow) {
                // Add fade out animation
                reportRow.style.transition = 'all 0.3s ease';
                reportRow.style.opacity = '0';
                reportRow.style.transform = 'scale(0.95)';

                setTimeout(() => {
                    reportRow.remove();

                    // Check if table is empty
                    const remainingReports = document.querySelectorAll('#reportsTableBody tr[data-report-id]');
                    if (remainingReports.length === 0) {
                        showTableEmptyState();
                    }
                }, 300);
            }
        }

        // Show empty state when no reports in table
        function showTableEmptyState() {
            const tableBody = document.getElementById('reportsTableBody');
            if (!tableBody) return;

            tableBody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="10" class="empty-state">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Laporan</h3>
                        <p class="text-gray-500 mb-4">Belum ada laporan kesehatan yang tersedia.</p>
                        <button onclick="switchTab('form')"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Buat Laporan Pertama
                        </button>
                    </td>
                </tr>
            `;
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;

            // Set styles based on type
            const styles = {
                success: 'bg-green-100 border border-green-400 text-green-700',
                error: 'bg-red-100 border border-red-400 text-red-700',
                warning: 'bg-yellow-100 border border-yellow-400 text-yellow-700',
                info: 'bg-blue-100 border border-blue-400 text-blue-700'
            };

            notification.className += ` ${styles[type] || styles.info}`;

            // Set icons based on type
            const icons = {
                success: '✅',
                error: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };

            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="text-lg mr-2">${icons[type] || icons.info}</span>
                    <p class="text-sm font-medium">${message}</p>
                    <button onclick="this.parentElement.parentElement.remove()" 
                        class="ml-4 text-lg leading-none">&times;</button>
                </div>
            `;

            // Add to DOM
            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // ========================================
        // OTHER FUNCTIONS
        // ========================================

        // Bulk print selected reports
        function bulkPrint() {
            if (selectedReports.size === 0) {
                showNotification('Pilih laporan yang ingin dicetak terlebih dahulu', 'warning');
                return;
            }

            // Open print page for selected reports
            const ids = Array.from(selectedReports).join(',');
            window.open(`/penyuluh/laporan/bulk-print?ids=${ids}`, '_blank');
        }

        // Get filter statistics
        function getFilterStatistics() {
            const reportRows = document.querySelectorAll('#reportsTableBody tr[data-report-id]');
            const stats = {
                total: reportRows.length,
                today: 0,
                week: 0,
                month: 0
            };

            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            reportRows.forEach(row => {
                const dateAttr = row.getAttribute('data-date');
                if (dateAttr) {
                    const rowDate = new Date(dateAttr);

                    // Count today
                    const rowDateOnly = new Date(rowDate.getFullYear(), rowDate.getMonth(), rowDate.getDate());
                    if (rowDateOnly.getTime() === today.getTime()) {
                        stats.today++;
                    }

                    // Count this week
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay());
                    const weekEnd = new Date(weekStart);
                    weekEnd.setDate(weekStart.getDate() + 6);
                    weekEnd.setHours(23, 59, 59, 999);

                    if (rowDate >= weekStart && rowDate <= weekEnd) {
                        stats.week++;
                    }

                    // Count this month
                    const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                    const monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59, 999);

                    if (rowDate >= monthStart && rowDate <= monthEnd) {
                        stats.month++;
                    }
                }
            });

            return stats;
        }

        // Update filter buttons with counts
        function updateFilterButtonsWithCounts() {
            const stats = getFilterStatistics();

            const filterAllEl = document.getElementById('filter-all');
            if (filterAllEl) filterAllEl.innerHTML = `Semua (${stats.total})`;

            const filterTodayEl = document.getElementById('filter-today');
            if (filterTodayEl) filterTodayEl.innerHTML = `Hari Ini (${stats.today})`;

            const filterWeekEl = document.getElementById('filter-week');
            if (filterWeekEl) filterWeekEl.innerHTML = `Minggu Ini (${stats.week})`;

            const filterMonthEl = document.getElementById('filter-month');
            if (filterMonthEl) filterMonthEl.innerHTML = `Bulan Ini (${stats.month})`;
        }

        // Real-time suhu tubuh validation
        document.getElementById('suhu_tubuh')?.addEventListener('input', function() {
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

        // Auto-save draft functionality
        function saveDraft() {
            const form = document.getElementById('healthReportForm');
            if (!form) return;

            const formData = new FormData(form);
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

        // ========================================
        // EVENT LISTENERS AND INITIALIZATION
        // ========================================

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set default values
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            const tanggalPemeriksaanEl = document.getElementById('tanggal_pemeriksaan');
            if (tanggalPemeriksaanEl) {
                tanggalPemeriksaanEl.value = now.toISOString().slice(0, 16);
            }

            // Load draft if exists
            loadDraft();

            // Auto-save draft every 30 seconds
            setInterval(saveDraft, 30000);

            // Clear draft on successful submission
            if (window.location.search.includes('success=1')) {
                clearDraft();
            }

            // Update button counts
            updateFilterButtonsWithCounts();

            // Modal event listeners
            const modal = document.getElementById('reportModal');
            if (modal) {
                // Close modal when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal();
                    }
                });
            }

            const deleteModal = document.getElementById('deleteConfirmModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        cancelDelete();
                    }
                });
            }

            // Close modals with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (modal && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                    if (deleteModal && !deleteModal.classList.contains('hidden')) {
                        cancelDelete();
                    }
                }
            });

            // Quick filter keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Only if search input is not focused
                if (document.activeElement.id !== 'searchInput' && document.activeElement.type !== 'date') {
                    if (e.altKey) {
                        switch (e.key) {
                            case '1':
                                filterByPeriod('all');
                                e.preventDefault();
                                break;
                            case '2':
                                filterByPeriod('today');
                                e.preventDefault();
                                break;
                            case '3':
                                filterByPeriod('week');
                                e.preventDefault();
                                break;
                            case '4':
                                filterByPeriod('month');
                                e.preventDefault();
                                break;
                        }
                    }
                }
            });
        });
    </script>
@endpush
