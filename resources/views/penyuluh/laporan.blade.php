@extends('layouts.app')

@section('title', 'Buat Laporan Kesehatan')
@section('page-title', 'Buat Laporan Kesehatan')
@section('page-description', 'Dokumentasi pemeriksaan dan diagnosis kesehatan ternak')

@push('styles')
    <style>
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
                    <p class="text-blue-100">Dokumentasi pemeriksaan medis professional</p>
                </div>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <button onclick="openTemplateModal()"
                        class="px-4 py-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                        📋 Template
                    </button>
                    <button onclick="openKnowledgeBase()"
                        class="px-4 py-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                        📚 Knowledge Base
                    </button>
                    <button onclick="togglePreview()"
                        class="px-4 py-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                        👁️ Preview
                    </button>
                </div>
            </div>
        </div>

        <div class="step-indicator">
            <div class="step active" id="step-1">
                <div class="step-number">1</div>
                <span class="ml-2 font-medium">Info Dasar</span>
            </div>
            <div class="step-line"></div>
            <div class="step inactive" id="step-2">
                <div class="step-number">2</div>
                <span class="ml-2 font-medium">Pemeriksaan</span>
            </div>
            <div class="step-line"></div>
            <div class="step inactive" id="step-3">
                <div class="step-number">3</div>
                <span class="ml-2 font-medium">Diagnosis</span>
            </div>
            <div class="step-line"></div>
            <div class="step inactive" id="step-4">
                <div class="step-number">4</div>
                <span class="ml-2 font-medium">Treatment</span>
            </div>
            <div class="step-line"></div>
            <div class="step inactive" id="step-5">
                <div class="step-number">5</div>
                <span class="ml-2 font-medium">Finalisasi</span>
            </div>
        </div>

        <form id="healthReportForm" action="" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Step 1: Informasi Dasar -->
                    <div class="form-section" id="section-1">
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
                                    <label for="farmer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Peternak <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="farmer_name" name="farmer_name" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Nama lengkap peternak">
                                </div>

                                <div>
                                    <label for="farm_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Peternakan
                                    </label>
                                    <input type="text" id="farm_name" name="farm_name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Nama peternakan">
                                </div>

                                <div>
                                    <label for="animal_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Ternak <span class="text-red-500">*</span>
                                    </label>
                                    <select id="animal_type" name="animal_type" required onchange="updateAnimalInfo()"
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
                                    <label for="animal_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        ID/Nama Ternak <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="animal_id" name="animal_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Contoh: Sapi #001 atau Moli">
                                </div>

                                <div>
                                    <label for="examination_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Pemeriksaan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" id="examination_date" name="examination_date" required
                                        value="{{ date('Y-m-d\TH:i') }}"
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
                                    <label for="animal_weight" class="block text-sm font-medium text-gray-700 mb-2">
                                        Berat Ternak (kg)
                                    </label>
                                    <input type="number" id="animal_weight" name="animal_weight" min="0"
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="450">
                                </div>

                                <div>
                                    <label for="animal_gender" class="block text-sm font-medium text-gray-700 mb-2">
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

                    <!-- Step 2: Pemeriksaan Fisik & Vital Signs -->
                    <div class="form-section hidden" id="section-2">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <span class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-green-600 text-sm font-bold">2</span>
                                </span>
                                Pemeriksaan Fisik & Vital Signs
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="vital-signs-grid">
                                <div class="vital-sign-card">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Suhu Tubuh (°C) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="temperature" name="temperature" step="0.1"
                                        min="35" max="45" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="38.5" onchange="checkVitalSigns()">
                                    <div class="mt-2">
                                        <span class="severity-indicator" id="temp-indicator">Normal</span>
                                    </div>
                                </div>

                                <div class="vital-sign-card">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Detak Jantung (bpm)
                                    </label>
                                    <input type="number" id="heart_rate" name="heart_rate" min="30"
                                        max="200"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="72" onchange="checkVitalSigns()">
                                    <div class="mt-2">
                                        <span class="severity-indicator" id="hr-indicator">Normal</span>
                                    </div>
                                </div>

                                <div class="vital-sign-card">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Frekuensi Napas (/menit)
                                    </label>
                                    <input type="number" id="respiratory_rate" name="respiratory_rate" min="5"
                                        max="60"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="16" onchange="checkVitalSigns()">
                                    <div class="mt-2">
                                        <span class="severity-indicator" id="rr-indicator">Normal</span>
                                    </div>
                                </div>

                                <div class="vital-sign-card">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Capillary Refill Time (detik)
                                    </label>
                                    <input type="number" id="crt" name="crt" min="0" max="10"
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="2.0" onchange="checkVitalSigns()">
                                    <div class="mt-2">
                                        <span class="severity-indicator" id="crt-indicator">Normal</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Gejala yang Diamati</h4>
                                <div class="symptom-selector" id="symptom-selector">
                                    <!-- Symptoms will be populated by JavaScript -->
                                </div>
                            </div>

                            <div>
                                <label for="physical_examination" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hasil Pemeriksaan Fisik Detail
                                </label>
                                <textarea id="physical_examination" name="physical_examination" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                    placeholder="Deskripsi detail hasil pemeriksaan fisik dari kepala hingga ekor..."></textarea>
                            </div>

                            <div>
                                <label for="examination_photos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Foto Pemeriksaan
                                </label>
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                    <input type="file" id="examination_photos" name="examination_photos[]" multiple
                                        accept="image/*" class="hidden" onchange="previewPhotos(this)">
                                    <label for="examination_photos" class="cursor-pointer">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-2">
                                            <span class="text-sm font-medium text-primary">Upload foto</span>
                                            <span class="text-sm text-gray-500">atau drag & drop</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG hingga 5MB (maksimal 5 foto)</p>
                                    </label>
                                </div>
                                <div id="photo-preview" class="mt-4"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Diagnosis -->
                    <div class="form-section hidden" id="section-3">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <span class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-purple-600 text-sm font-bold">3</span>
                                </span>
                                Diagnosis & Assessment
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="ai-suggestion">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-blue-900">AI Diagnosis Suggestions</h4>
                                    <button type="button" onclick="runAIDiagnosis()"
                                        class="ml-auto px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition-colors">
                                        🤖 Analisis
                                    </button>
                                </div>
                                <div id="ai-suggestions" class="diagnosis-suggestions">
                                    <p class="text-sm text-gray-600">Masukkan gejala dan vital signs untuk mendapatkan
                                        saran diagnosis dari AI</p>
                                </div>
                            </div>

                            <div>
                                <label for="primary_diagnosis" class="block text-sm font-medium text-gray-700 mb-2">
                                    Diagnosis Utama <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="primary_diagnosis" name="primary_diagnosis" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                    placeholder="Contoh: Mastitis akut">
                                <div class="mt-2">
                                    <button type="button" onclick="openDiagnosisLibrary()" class="quick-fill-btn">
                                        📚 Pilih dari Library
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="secondary_diagnosis" class="block text-sm font-medium text-gray-700 mb-2">
                                    Diagnosis Sekunder/Diferensial
                                </label>
                                <textarea id="secondary_diagnosis" name="secondary_diagnosis" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                    placeholder="Diagnosis alternatif yang perlu dipertimbangkan..."></textarea>
                            </div>

                            <div>
                                <label for="prognosis" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prognosis <span class="text-red-500">*</span>
                                </label>
                                <select id="prognosis" name="prognosis" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Pilih Prognosis</option>
                                    <option value="excellent">Excellent - Sembuh sempurna</option>
                                    <option value="good">Good - Sembuh dengan minimal komplikasi</option>
                                    <option value="fair">Fair - Sembuh dengan beberapa komplikasi</option>
                                    <option value="guarded">Guarded - Prognosis hati-hati</option>
                                    <option value="poor">Poor - Prognosis buruk</option>
                                    <option value="grave">Grave - Prognosis sangat buruk</option>
                                </select>
                            </div>

                            <div>
                                <label for="severity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tingkat Keparahan
                                </label>
                                <div class="grid grid-cols-4 gap-2">
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="severity" value="mild" class="mr-2">
                                        <span class="text-sm">Ringan</span>
                                    </label>
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="severity" value="moderate" class="mr-2">
                                        <span class="text-sm">Sedang</span>
                                    </label>
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="severity" value="severe" class="mr-2">
                                        <span class="text-sm">Berat</span>
                                    </label>
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="severity" value="critical" class="mr-2">
                                        <span class="text-sm">Kritis</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="diagnosis_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Diagnosis
                                </label>
                                <textarea id="diagnosis_notes" name="diagnosis_notes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                    placeholder="Catatan tambahan mengenai diagnosis, pemeriksaan penunjang yang diperlukan, dll..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Treatment Plan -->
                    <div class="form-section hidden" id="section-4">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <span class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-green-600 text-sm font-bold">4</span>
                                </span>
                                Treatment Plan & Prescription
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="treatment-plan">
                                <h4 class="font-semibold text-green-800 mb-3">Rencana Pengobatan</h4>
                                <textarea id="treatment_plan" name="treatment_plan" rows="3" required
                                    class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                                    placeholder="Outline rencana pengobatan secara keseluruhan..."></textarea>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-md font-semibold text-gray-900">Resep Obat</h4>
                                    <button type="button" onclick="addPrescription()"
                                        class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                        + Tambah Obat
                                    </button>
                                </div>
                                <div id="prescription-list">
                                    <!-- Prescription items will be added here -->
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Follow-up
                                    </label>
                                    <input type="date" id="follow_up_date" name="follow_up_date"
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>

                                <div>
                                    <label for="follow_up_instructions"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Instruksi Follow-up
                                    </label>
                                    <select id="follow_up_instructions" name="follow_up_instructions"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">Pilih instruksi</option>
                                        <option value="phone">Telepon/SMS</option>
                                        <option value="visit">Kunjungan ke lokasi</option>
                                        <option value="clinic">Datang ke klinik</option>
                                        <option value="emergency">Segera jika memburuk</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="care_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                    Instruksi Perawatan untuk Peternak <span class="text-red-500">*</span>
                                </label>
                                <textarea id="care_instructions" name="care_instructions" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                    placeholder="Instruksi detail untuk peternak dalam merawat ternak..."></textarea>
                            </div>

                            <div>
                                <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Tambahan
                                </label>
                                <textarea id="additional_notes" name="additional_notes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                    placeholder="Catatan penting lainnya..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Finalisasi -->
                    <div class="form-section hidden" id="section-5">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <span class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-indigo-600 text-sm font-bold">5</span>
                                </span>
                                Finalisasi & Tanda Tangan
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div>
                                <label for="examination_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                    Biaya Pemeriksaan (Rp)
                                </label>
                                <input type="number" id="examination_fee" name="examination_fee" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                    placeholder="150000">
                            </div>

                            <div>
                                <label for="treatment_cost" class="block text-sm font-medium text-gray-700 mb-2">
                                    Biaya Pengobatan (Rp)
                                </label>
                                <input type="number" id="treatment_cost" name="treatment_cost" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                    placeholder="300000">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanda Tangan Digital
                                </label>
                                <canvas id="signature-pad"
                                    class="signature-pad border-2 border-dashed border-gray-300 rounded-lg w-full h-32"></canvas>
                                <div class="mt-2 flex space-x-2">
                                    <button type="button" onclick="clearSignature()"
                                        class="px-3 py-1 bg-gray-500 text-white rounded text-sm hover:bg-gray-600">
                                        Clear
                                    </button>
                                    <button type="button" onclick="saveSignature()"
                                        class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                        Simpan Tanda Tangan
                                    </button>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                    <div>
                                        <h5 class="font-medium text-yellow-800">Periksa Kembali Data</h5>
                                        <p class="text-sm text-yellow-700 mt-1">Pastikan semua informasi sudah benar
                                            sebelum menyimpan laporan. Laporan yang sudah disimpan akan dikirim ke peternak.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-200 no-print">
                        <button type="button" id="prevBtn" onclick="previousStep()"
                            class="hidden px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            ← Sebelumnya
                        </button>
                        <div class="flex space-x-3 ml-auto">
                            <button type="button" onclick="saveDraft()"
                                class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                💾 Simpan Draft
                            </button>
                            <button type="button" id="nextBtn" onclick="nextStep()"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                                Selanjutnya →
                            </button>
                            <button type="submit" id="submitBtn"
                                class="hidden px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                ✅ Simpan Laporan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="preview-panel" id="preview-panel">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Live Preview</h3>
                        <div class="print-preview" id="print-preview">
                            <div class="text-center mb-6">
                                <h2 class="text-xl font-bold text-gray-900">LAPORAN KESEHATAN TERNAK</h2>
                                <p class="text-sm text-gray-600 mt-1">Klinik Hewan Digital</p>
                            </div>

                            <div class="space-y-4 text-sm">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="font-medium">Peternak:</span>
                                        <span id="preview-farmer">-</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Tanggal:</span>
                                        <span id="preview-date">-</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Ternak:</span>
                                        <span id="preview-animal">-</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Pemeriksa:</span>
                                        <span id="preview-examiner">-</span>
                                    </div>
                                </div>

                                <div class="border-t pt-3">
                                    <h4 class="font-medium mb-2">Keluhan Utama:</h4>
                                    <p id="preview-complaint" class="text-gray-700">-</p>
                                </div>

                                <div class="border-t pt-3">
                                    <h4 class="font-medium mb-2">Vital Signs:</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>Suhu: <span id="preview-temp">-</span>°C</div>
                                        <div>Nadi: <span id="preview-hr">-</span> bpm</div>
                                        <div>Napas: <span id="preview-rr">-</span>/min</div>
                                        <div>CRT: <span id="preview-crt">-</span> detik</div>
                                    </div>
                                </div>

                                <div class="border-t pt-3">
                                    <h4 class="font-medium mb-2">Diagnosis:</h4>
                                    <p id="preview-diagnosis" class="text-gray-700">-</p>
                                </div>

                                <div class="border-t pt-3">
                                    <h4 class="font-medium mb-2">Treatment:</h4>
                                    <p id="preview-treatment" class="text-gray-700">-</p>
                                </div>

                                <div class="border-t pt-3">
                                    <h4 class="font-medium mb-2">Instruksi:</h4>
                                    <p id="preview-instructions" class="text-gray-700">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2 no-print">
                            <button onclick="printReport()"
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                🖨️ Print
                            </button>
                            <button onclick="exportPDF()"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                📄 Export PDF
                            </button>
                            <button onclick="shareReport()"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                📤 Share
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 bg-white rounded-lg border p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Progress Timeline</h4>
                        <div class="space-y-3">
                            <div class="timeline-item">
                                <div class="timeline-dot completed"></div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium">Informasi Dasar</p>
                                    <p class="text-xs text-gray-500">Lengkap</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot current"></div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium">Pemeriksaan Fisik</p>
                                    <p class="text-xs text-gray-500">Sedang berlangsung</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium">Diagnosis</p>
                                    <p class="text-xs text-gray-500">Belum dimulai</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium">Treatment</p>
                                    <p class="text-xs text-gray-500">Belum dimulai</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium">Finalisasi</p>
                                    <p class="text-xs text-gray-500">Belum dimulai</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="floating-save no-print">
            <button onclick="autoSave()"
                class="w-12 h-12 bg-primary text-white rounded-full shadow-lg hover:bg-secondary transition-all transform hover:scale-110">
                💾
            </button>
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
