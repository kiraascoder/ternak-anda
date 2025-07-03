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

        .form-section {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .step {
            display: flex;
            align-items: center;
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background: #667eea;
            color: white;
        }

        .step.completed .step-number {
            background: #10b981;
            color: white;
        }

        .step.inactive .step-number {
            background: #e5e7eb;
            color: #6b7280;
        }

        .step-line {
            width: 60px;
            height: 2px;
            background: #e5e7eb;
            margin: 0 1rem;
        }

        .step.completed+.step .step-line {
            background: #10b981;
        }

        .vital-signs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .vital-sign-card {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .vital-sign-card:hover {
            border-color: #3b82f6;
        }

        .vital-sign-card.abnormal {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .symptom-selector {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.5rem;
        }

        .symptom-tag {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .symptom-tag:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .symptom-tag.selected {
            border-color: #3b82f6;
            background: #3b82f6;
            color: white;
        }

        .diagnosis-suggestions {
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 1rem;
        }

        .suggestion-item {
            padding: 0.75rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .suggestion-item:hover {
            background: white;
            border-color: #3b82f6;
        }

        .confidence-bar {
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .confidence-fill {
            height: 100%;
            transition: width 0.5s ease;
        }

        .confidence-high {
            background: #10b981;
        }

        .confidence-medium {
            background: #f59e0b;
        }

        .confidence-low {
            background: #ef4444;
        }

        .treatment-plan {
            background: #f0fdf4;
            border: 2px solid #bbf7d0;
            border-radius: 8px;
            padding: 1rem;
        }

        .prescription-item {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .drug-search {
            position: relative;
        }

        .drug-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
        }

        .drug-option {
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
        }

        .drug-option:hover {
            background: #f9fafb;
        }

        .attachment-preview {
            position: relative;
            display: inline-block;
            margin: 0.5rem;
        }

        .attachment-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }

        .remove-attachment {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .preview-panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            position: sticky;
            top: 2rem;
        }

        .print-preview {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .signature-pad {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            width: 100%;
            height: 150px;
            cursor: crosshair;
        }

        .signature-pad.signed {
            border-color: #10b981;
            border-style: solid;
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

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item:last-child::before {
            background: linear-gradient(to bottom, #e5e7eb 0%, transparent 100%);
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #e5e7eb;
        }

        .timeline-dot.completed {
            background-color: #10b981;
        }

        .timeline-dot.current {
            background-color: #3b82f6;
        }

        .quick-fill-btn {
            padding: 0.5rem 1rem;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quick-fill-btn:hover {
            background: #dbeafe;
        }

        .severity-indicator {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .severity-normal {
            background: #dcfce7;
            color: #166534;
        }

        .severity-mild {
            background: #fef3c7;
            color: #92400e;
        }

        .severity-moderate {
            background: #fed7aa;
            color: #c2410c;
        }

        .severity-severe {
            background: #fecaca;
            color: #991b1b;
        }

        .ai-suggestion {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .floating-save {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .print-preview {
                box-shadow: none !important;
                border: none !important;
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

            <!-- Tab Content: Form Rekomendasi -->
            <div id="content-form" class="tab-content active">
                <form id="healthReportForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="">
                        <div class="lg:col-span-2 space-y-6">
                            <div class="form-section" id="section-1">
                                <div class="section-header">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <span
                                            class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-blue-600 text-sm font-bold">1</span>
                                        </span>
                                        Informasi Dasar Pemeriksaan
                                    </h3>
                                </div>

                                <div class="p-6 space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="animal_id" class="block text-sm font-medium text-gray-700 mb-2">
                                                ID/Nama Ternak <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="animal_id" name="animal_id" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                                placeholder="Contoh: Sapi #001 atau Moli">
                                        </div>

                                        <div>
                                            <label for="animal_type" class="block text-sm font-medium text-gray-700 mb-2">
                                                Jenis Ternak <span class="text-red-500">*</span>
                                            </label>
                                            <select id="animal_type" name="animal_type" required
                                                onchange="updateAnimalInfo()"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                                <option value="">Pilih Jenis Ternak</option>
                                                <option value="sapi">Sapi</option>
                                                <option value="kambing">Kambing</option>
                                                <option value="domba">Domba</option>
                                                <option value="kerbau">Kerbau</option>
                                                <option value="ayam">Ayam</option>
                                                <option value="bebek">Bebek</option>
                                            </select>
                                        </div>


                                        <div>
                                            <label for="examination_date"
                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                Tanggal Pemeriksaan <span class="text-red-500">*</span>
                                            </label>
                                            <input type="datetime-local" id="examination_date" name="examination_date"
                                                required value="{{ date('Y-m-d\TH:i') }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        </div>

                                        <div>
                                            <label for="examiner_name" class="block text-sm font-medium text-gray-700 mb-2">
                                                Nama Pemeriksa <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="examiner_name" name="examiner_name" required
                                                value="Dr. {{ auth()->user()->name ?? 'Budi Santoso' }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label for="animal_age" class="block text-sm font-medium text-gray-700 mb-2">
                                                Umur Ternak
                                            </label>
                                            <div class="flex space-x-2">
                                                <input type="number" id="animal_age" name="animal_age" min="0"
                                                    max="20"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                                    placeholder="2">
                                                <select name="age_unit"
                                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                                    <option value="tahun">Tahun</option>
                                                    <option value="bulan">Bulan</option>
                                                    <option value="hari">Hari</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label for="animal_weight"
                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                Berat Ternak (kg)
                                            </label>
                                            <input type="number" id="animal_weight" name="animal_weight" min="0"
                                                step="0.1"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                                placeholder="450">
                                        </div>

                                        <div>
                                            <label for="animal_gender"
                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                Jenis Kelamin
                                            </label>
                                            <select id="animal_gender" name="animal_gender"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                                <option value="">Pilih</option>
                                                <option value="jantan">Jantan</option>
                                                <option value="betina">Betina</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="chief_complaint" class="block text-sm font-medium text-gray-700 mb-2">
                                            Keluhan Utama <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="chief_complaint" name="chief_complaint" rows="3" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                            placeholder="Jelaskan keluhan utama yang dialami ternak..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-6 border-t border-gray-200 no-print">
                                <button type="submit" id="submitBtn"
                                    class="flex  px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    ✅ Simpan Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>



            <!-- Tab Content: Daftar Rekomendasi -->
            <div id="content-list" class="tab-content">
                <!-- Filter dan Search Bar -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="pakanGrid">
                    @forelse ($laporans ?? [] as $pakan)
                        <div class="pakan-card card-hover pakan-item" data-jenis="{{ strtolower($pakan->jenis) }}"
                            data-kategori="{{ strtolower($pakan->kategori) }}" data-id="{{ $pakan->idRekomendasi }}"
                            data-ternak="{{ $pakan->ternak ? $pakan->ternak->namaTernak : 'Ternak tidak ditemukan' }}"
                            data-penyuluh="{{ $pakan->penyuluh ? $pakan->penyuluh->nama : 'Penyuluh tidak ditemukan' }}"
                            data-tanggal="{{ $pakan->tanggalRekomendasi }}" data-jumlah="{{ $pakan->jumlah }}"
                            data-jenisTernak="{{ $pakan->ternak->jenis }}" data-satuan="{{ $pakan->satuan }}"
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
                                            <p class="text-sm text-gray-500">
                                                {{ ucfirst($pakan->ternak->jenis ?? 'Sapi') }}</p>
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
                                <div class="flex space-x-2">
                                    <button onclick="openDetailModal({{ $pakan->idRekomendasi }})"
                                        class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Detail
                                    </button>

                                    @auth
                                        @if (Auth::id() === $pakan->idPenyuluh)
                                            <a href="{{ route('pakan.edit', $pakan) }}"
                                                class="px-3 py-2 bg-yellow-500 text-white rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
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
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">Belum Ada Rekomendasi Pakan</h3>
                                <p class="mt-1 text-gray-500">Belum ada rekomendasi pakan yang tersedia untuk ternak Anda.
                                </p>
                                <button onclick="switchTab('form')"
                                    class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    Buat Rekomendasi Pertama
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if (!empty($pakans) && count($pakans) > 0)
                    <div class="mt-6 bg-gray-50 rounded-lg px-6 py-4">
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
        </div>
    </div>

    <!-- Modal Template Library -->
    <div id="templateModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Template Library</h3>
                <button onclick="closeTemplateModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="knowledge-card p-4 cursor-pointer" onclick="useTemplate('mastitis')">
                    <h5 class="font-semibold text-gray-900 mb-2">Mastitis Template</h5>
                    <p class="text-sm text-gray-600 mb-3">Template lengkap untuk kasus mastitis pada sapi perah</p>
                    <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Umum</span>
                </div>

                <div class="knowledge-card p-4 cursor-pointer" onclick="useTemplate('respiratory')">
                    <h5 class="font-semibold text-gray-900 mb-2">Respiratory Issues</h5>
                    <p class="text-sm text-gray-600 mb-3">Template untuk masalah pernapasan dan pneumonia</p>
                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Sering</span>
                </div>

                <div class="knowledge-card p-4 cursor-pointer" onclick="useTemplate('digestive')">
                    <h5 class="font-semibold text-gray-900 mb-2">Digestive Problems</h5>
                    <p class="text-sm text-gray-600 mb-3">Template untuk gangguan pencernaan dan diare</p>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Umum</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Diagnosis Library -->
    <div id="diagnosisModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Diagnosis Library</h3>
                <button onclick="closeDiagnosisModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <h4 class="font-semibold text-gray-900">Kategori</h4>
                    <button onclick="filterDiagnosis('all')"
                        class="w-full text-left px-3 py-2 rounded bg-primary text-white">Semua</button>
                    <button onclick="filterDiagnosis('infectious')"
                        class="w-full text-left px-3 py-2 rounded hover:bg-gray-100">Infeksi</button>
                    <button onclick="filterDiagnosis('metabolic')"
                        class="w-full text-left px-3 py-2 rounded hover:bg-gray-100">Metabolik</button>
                    <button onclick="filterDiagnosis('reproductive')"
                        class="w-full text-left px-3 py-2 rounded hover:bg-gray-100">Reproduksi</button>
                    <button onclick="filterDiagnosis('surgical')"
                        class="w-full text-left px-3 py-2 rounded hover:bg-gray-100">Bedah</button>
                </div>

                <div class="lg:col-span-3">
                    <input type="text" placeholder="Cari diagnosis..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                        <div class="knowledge-card p-4 cursor-pointer" onclick="selectDiagnosis('Mastitis akut')">
                            <h5 class="font-semibold text-gray-900">Mastitis akut</h5>
                            <p class="text-sm text-gray-600">Peradangan kelenjar susu yang berkembang cepat</p>
                        </div>
                        <div class="knowledge-card p-4 cursor-pointer" onclick="selectDiagnosis('Pneumonia')">
                            <h5 class="font-semibold text-gray-900">Pneumonia</h5>
                            <p class="text-sm text-gray-600">Infeksi atau inflamasi paru-paru</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentStep = 1;
        let totalSteps = 5;
        let prescriptionCount = 0;
        let attachments = [];

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

        const symptoms = {
            sapi: ['Demam', 'Nafsu makan menurun', 'Pembengkakan ambing', 'Diare', 'Batuk', 'Kesulitan bernapas',
                'Lemas/lesu', 'Tremor', 'Dehidrasi', 'Produksi susu menurun'
            ],
            kambing: ['Demam', 'Nafsu makan hilang', 'Pembengkakan', 'Diare', 'Batuk', 'Sesak napas', 'Lemas', 'Kejang',
                'Dehidrasi', 'Keputihan abnormal'
            ],
            domba: ['Demam tinggi', 'Kehilangan nafsu makan', 'Pembengkakan kaki', 'Mencret', 'Batuk kering',
                'Napas pendek', 'Gelisah', 'Gemetaran', 'Mata berair', 'Bulu rontok'
            ]
        };

        function updateAnimalInfo() {
            const animalType = document.getElementById('animal_type').value;
            if (animalType && symptoms[animalType]) {
                updateSymptomSelector(animalType);
            }
        }

        function updateSymptomSelector(animalType) {
            const selector = document.getElementById('symptom-selector');
            selector.innerHTML = '';

            symptoms[animalType].forEach(symptom => {
                const div = document.createElement('div');
                div.className = 'symptom-tag';
                div.innerHTML = `
                    <input type="checkbox" name="symptoms[]" value="${symptom}" class="mr-2" id="symptom-${symptom.replace(/\s+/g, '-')}">
                    <label for="symptom-${symptom.replace(/\s+/g, '-')}" class="cursor-pointer">${symptom}</label>
                `;
                div.onclick = function(e) {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = div.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                    }
                    div.classList.toggle('selected', div.querySelector('input[type="checkbox"]').checked);
                };
                selector.appendChild(div);
            });
        }

        function checkVitalSigns() {
            const temp = parseFloat(document.getElementById('temperature').value);
            const hr = parseInt(document.getElementById('heart_rate').value);
            const rr = parseInt(document.getElementById('respiratory_rate').value);
            const crt = parseFloat(document.getElementById('crt').value);

            checkVitalSign('temp', temp, [37.5, 39.5], 'temperature');
            checkVitalSign('hr', hr, [60, 80], 'heart_rate');
            checkVitalSign('rr', rr, [12, 20], 'respiratory_rate');
            checkVitalSign('crt', crt, [1, 3], 'crt');
        }

        function checkVitalSign(id, value, normalRange, inputId) {
            const indicator = document.getElementById(id + '-indicator');
            const input = document.getElementById(inputId).parentElement;

            if (isNaN(value)) {
                indicator.textContent = 'Normal';
                indicator.className = 'severity-indicator severity-normal';
                input.classList.remove('abnormal');
                return;
            }

            if (value < normalRange[0] || value > normalRange[1]) {
                indicator.textContent = 'Abnormal';
                indicator.className = 'severity-indicator severity-severe';
                input.classList.add('abnormal');
            } else {
                indicator.textContent = 'Normal';
                indicator.className = 'severity-indicator severity-normal';
                input.classList.remove('abnormal');
            }
        }

        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    document.getElementById(`section-${currentStep}`).classList.add('hidden');
                    document.getElementById(`step-${currentStep}`).classList.remove('active');
                    document.getElementById(`step-${currentStep}`).classList.add('completed');

                    currentStep++;

                    document.getElementById(`section-${currentStep}`).classList.remove('hidden');
                    document.getElementById(`step-${currentStep}`).classList.add('active');

                    updateButtons();
                    updatePreview();
                }
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                document.getElementById(`section-${currentStep}`).classList.add('hidden');
                document.getElementById(`step-${currentStep}`).classList.remove('active');

                currentStep--;

                document.getElementById(`section-${currentStep}`).classList.remove('hidden');
                document.getElementById(`step-${currentStep-1}`).classList.remove('completed');
                document.getElementById(`step-${currentStep}`).classList.add('active');

                updateButtons();
            }
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            prevBtn.classList.toggle('hidden', currentStep === 1);

            if (currentStep === totalSteps) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function validateCurrentStep() {
            const requiredFields = document.querySelectorAll(
                `#section-${currentStep} input[required], #section-${currentStep} textarea[required], #section-${currentStep} select[required]`
            );
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    valid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!valid) {
                alert('Mohon lengkapi semua field yang wajib diisi');
            }

            return valid;
        }

        function previewPhotos(input) {
            const preview = document.getElementById('photo-preview');
            preview.innerHTML = '';

            Array.from(input.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'attachment-preview';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}">
                            <div class="remove-attachment" onclick="removePhoto(${index})">×</div>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                    attachments.push(file);
                }
            });
        }

        function removePhoto(index) {
            attachments.splice(index, 1);
            previewPhotos({
                files: attachments
            });
        }

        function runAIDiagnosis() {
            const suggestions = document.getElementById('ai-suggestions');
            suggestions.innerHTML = `
                <div class="space-y-3">
                    <div class="suggestion-item" onclick="selectAIDiagnosis('Mastitis akut', 85)">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="font-semibold text-gray-900">Mastitis akut</h5>
                            <span class="text-sm font-medium text-green-600">85% confidence</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Peradangan kelenjar susu berdasarkan gejala pembengkakan dan demam</p>
                        <div class="confidence-bar">
                            <div class="confidence-fill confidence-high" style="width: 85%"></div>
                        </div>
                    </div>
                    
                    <div class="suggestion-item" onclick="selectAIDiagnosis('Infeksi bakteri sekunder', 60)">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="font-semibold text-gray-900">Infeksi bakteri sekunder</h5>
                            <span class="text-sm font-medium text-yellow-600">60% confidence</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Kemungkinan infeksi bakteri berdasarkan demam dan kondisi umum</p>
                        <div class="confidence-bar">
                            <div class="confidence-fill confidence-medium" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            `;
        }

        function selectAIDiagnosis(diagnosis, confidence) {
            document.getElementById('primary_diagnosis').value = diagnosis;
            updatePreview();
        }

        function addPrescription() {
            prescriptionCount++;
            const prescriptionList = document.getElementById('prescription-list');
            const div = document.createElement('div');
            div.className = 'prescription-item';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <h5 class="font-medium text-gray-900">Obat #${prescriptionCount}</h5>
                    <button type="button" onclick="removePrescription(this)" class="text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="drug-search">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Obat</label>
                        <input type="text" name="prescriptions[${prescriptionCount}][drug_name]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Contoh: Amoxicillin" onkeyup="searchDrug(this)">
                        <div class="drug-suggestions hidden"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                        <input type="text" name="prescriptions[${prescriptionCount}][dosage]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Contoh: 500mg">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frekuensi</label>
                        <select name="prescriptions[${prescriptionCount}][frequency]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="1x/hari">1x sehari</option>
                            <option value="2x/hari">2x sehari</option>
                            <option value="3x/hari">3x sehari</option>
                            <option value="4x/hari">4x sehari</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
                        <select name="prescriptions[${prescriptionCount}][duration]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="3 hari">3 hari</option>
                            <option value="5 hari">5 hari</option>
                            <option value="7 hari">7 hari</option>
                            <option value="10 hari">10 hari</option>
                            <option value="14 hari">14 hari</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instruksi Pemberian</label>
                    <textarea name="prescriptions[${prescriptionCount}][instructions]" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                        placeholder="Cara pemberian obat..."></textarea>
                </div>
            `;
            prescriptionList.appendChild(div);
        }

        function removePrescription(button) {
            button.closest('.prescription-item').remove();
        }

        function searchDrug(input) {
            const drugs = ['Amoxicillin', 'Ampicillin', 'Penicillin', 'Dexamethasone', 'Oxytetracycline', 'Gentamicin'];
            const suggestions = input.parentElement.querySelector('.drug-suggestions');
            const value = input.value.toLowerCase();

            if (value.length > 1) {
                const matches = drugs.filter(drug => drug.toLowerCase().includes(value));
                if (matches.length > 0) {
                    suggestions.innerHTML = matches.map(drug =>
                        `<div class="drug-option" onclick="selectDrug('${drug}', this)">${drug}</div>`
                    ).join('');
                    suggestions.classList.remove('hidden');
                } else {
                    suggestions.classList.add('hidden');
                }
            } else {
                suggestions.classList.add('hidden');
            }
        }

        function selectDrug(drugName, element) {
            const input = element.closest('.drug-search').querySelector('input');
            input.value = drugName;
            element.parentElement.classList.add('hidden');
        }

        function updatePreview() {
            document.getElementById('preview-farmer').textContent = document.getElementById('farmer_name').value || '-';
            document.getElementById('preview-date').textContent = document.getElementById('examination_date').value || '-';
            document.getElementById('preview-animal').textContent =
                `${document.getElementById('animal_type').value || '-'} - ${document.getElementById('animal_id').value || '-'}`;
            document.getElementById('preview-examiner').textContent = document.getElementById('examiner_name').value || '-';
            document.getElementById('preview-complaint').textContent = document.getElementById('chief_complaint').value ||
                '-';
            document.getElementById('preview-temp').textContent = document.getElementById('temperature').value || '-';
            document.getElementById('preview-hr').textContent = document.getElementById('heart_rate').value || '-';
            document.getElementById('preview-rr').textContent = document.getElementById('respiratory_rate').value || '-';
            document.getElementById('preview-crt').textContent = document.getElementById('crt').value || '-';
            document.getElementById('preview-diagnosis').textContent = document.getElementById('primary_diagnosis').value ||
                '-';
            document.getElementById('preview-treatment').textContent = document.getElementById('treatment_plan').value ||
                '-';
            document.getElementById('preview-instructions').textContent = document.getElementById('care_instructions')
                .value || '-';
        }

        function togglePreview() {
            const panel = document.getElementById('preview-panel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }

        function printReport() {
            window.print();
        }

        function exportPDF() {
            alert('Export PDF akan segera tersedia');
        }

        function shareReport() {
            alert('Fitur share laporan akan segera tersedia');
        }

        function saveDraft() {
            alert('Draft berhasil disimpan');
        }

        function autoSave() {
            // Auto save functionality
            console.log('Auto saving...');
        }

        function openTemplateModal() {
            document.getElementById('templateModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeTemplateModal() {
            document.getElementById('templateModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openDiagnosisLibrary() {
            document.getElementById('diagnosisModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDiagnosisModal() {
            document.getElementById('diagnosisModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function useTemplate(templateType) {
            // Template logic here
            alert(`Menggunakan template: ${templateType}`);
            closeTemplateModal();
        }

        function selectDiagnosis(diagnosis) {
            document.getElementById('primary_diagnosis').value = diagnosis;
            updatePreview();
            closeDiagnosisModal();
        }

        function openKnowledgeBase() {
            alert('Knowledge Base akan dibuka dalam tab baru');
        }

        // Initialize signature pad
        const canvas = document.getElementById('signature-pad');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            ctx.beginPath();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            ctx.stroke();
        }

        function stopDrawing() {
            isDrawing = false;
        }

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            canvas.classList.remove('signed');
        }

        function saveSignature() {
            canvas.classList.add('signed');
            alert('Tanda tangan berhasil disimpan');
        }

        // Auto-save every 30 seconds
        setInterval(autoSave, 30000);

        // Update preview on input change
        document.addEventListener('input', updatePreview);

        document.addEventListener('DOMContentLoaded', function() {
            updateAnimalInfo();
            updatePreview();

            const form = document.getElementById('healthReportForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (validateCurrentStep()) {
                    const submitBtn = document.getElementById('submitBtn');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.innerHTML = '⏳ Menyimpan...';
                    submitBtn.disabled = true;

                    setTimeout(() => {
                        alert('Laporan kesehatan berhasil disimpan dan dikirim ke peternak!');
                        window.location.href = '/health-reports';
                    }, 2000);
                }
            });

            ['templateModal', 'diagnosisModal'].forEach(modalId => {
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
                ['templateModal', 'diagnosisModal'].forEach(modalId => {
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
