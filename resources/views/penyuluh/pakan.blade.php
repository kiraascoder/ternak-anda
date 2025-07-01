@extends('layouts.app')

@section('title', 'Rekomendasi Pakan')
@section('page-title', 'Rekomendasi Pakan')
@section('page-description', 'Optimalisasi nutrisi dan manajemen pakan ternak')

@push('styles')
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .nutrition-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .nutrition-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0ea5e9 0%, #0284c7 100%);
        }

        .nutrient-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .nutrient-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.8s ease;
            position: relative;
        }

        .nutrient-fill.protein {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        }

        .nutrient-fill.energy {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }

        .nutrient-fill.fiber {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }

        .nutrient-fill.mineral {
            background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .feed-item {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .feed-item:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.1);
        }

        .feed-item.selected {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .calculator-panel {
            background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%);
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 1.5rem;
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

        .cost-indicator {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .cost-low {
            background: #dcfce7;
            color: #166534;
        }

        .cost-medium {
            background: #fef3c7;
            color: #92400e;
        }

        .cost-high {
            background: #fecaca;
            color: #991b1b;
        }

        .feed-schedule {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
        }

        .schedule-time {
            background: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
            min-width: 80px;
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

        .nutrition-gauge {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#ef4444 0deg 72deg,
                    #f59e0b 72deg 144deg,
                    #10b981 144deg 216deg,
                    #8b5cf6 216deg 288deg,
                    #06b6d4 288deg 360deg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nutrition-gauge::before {
            content: '';
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            position: absolute;
        }

        .gauge-center {
            position: relative;
            z-index: 10;
            text-align: center;
        }

        .condition-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0.125rem;
        }

        .condition-pregnant {
            background: #fef3c7;
            color: #92400e;
        }

        .condition-lactating {
            background: #dbeafe;
            color: #1e40af;
        }

        .condition-growing {
            background: #dcfce7;
            color: #166534;
        }

        .condition-sick {
            background: #fecaca;
            color: #991b1b;
        }

        .ai-suggestion {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #93c5fd;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .feed-comparison {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .comparison-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .comparison-item:last-child {
            border-bottom: none;
        }

        .trend-indicator {
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-down {
            color: #ef4444;
        }

        .trend-stable {
            color: #6b7280;
        }

        .feeding-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .feeding-timeline::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-dot {
            position: absolute;
            left: -2rem;
            top: 0.25rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #e5e7eb;
        }

        .timeline-dot.completed {
            background: #10b981;
        }

        .timeline-dot.current {
            background: #3b82f6;
        }

        .timeline-dot.upcoming {
            background: #d1d5db;
        }

        .weather-factor {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 0.75rem;
            margin: 0.5rem 0;
        }

        .seasonal-adjustment {
            background: #e0f2fe;
            border: 1px solid #0891b2;
            border-radius: 8px;
            padding: 0.75rem;
        }

        .inventory-alert {
            background: #fef2f2;
            border: 2px solid #fca5a5;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .optimization-tip {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .nutrition-score {
            font-size: 2rem;
            font-weight: bold;
            color: #059669;
        }

        .deficiency-warning {
            background: #fef2f2;
            color: #dc2626;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0.5rem 0;
        }

        .surplus-info {
            background: #f0fdf4;
            color: #16a34a;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0.5rem 0;
        }

        .feed-quality-indicator {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .quality-excellent {
            background: #10b981;
        }

        .quality-good {
            background: #3b82f6;
        }

        .quality-fair {
            background: #f59e0b;
        }

        .quality-poor {
            background: #ef4444;
        }

        .price-trend {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            border: 1px solid #e5e7eb;
            background: white;
            color: #6b7280;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px 8px 0 0;
        }

        .tab-button.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .tab-button:not(.active):hover {
            background: #f9fafb;
            color: #374151;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Rekomendasi Pakan Ternak</h1>
                    <p class="text-green-100">Optimalisasi nutrisi untuk produktivitas maksimal</p>
                </div>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $totalTernak ?? 25 }}</div>
                        <div class="text-sm text-green-100">Total Ternak</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">Rp {{ number_format($biayaPakanBulan ?? 12500000) }}</div>
                        <div class="text-sm text-green-100">Biaya Pakan/Bulan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $efisiensiPakan ?? 89 }}%</div>
                        <div class="text-sm text-green-100">Efisiensi Pakan</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 mb-6">
            <button onclick="showTab('calculator')" id="tab-calculator" class="tab-button active">
                🧮 Calculator Nutrisi
            </button>
            <button onclick="showTab('recommendations')" id="tab-recommendations" class="tab-button">
                🎯 Rekomendasi
            </button>
            <button onclick="showTab('schedule')" id="tab-schedule" class="tab-button">
                📅 Jadwal Pakan
            </button>
            <button onclick="showTab('monitoring')" id="tab-monitoring" class="tab-button">
                📊 Monitoring
            </button>
            <button onclick="showTab('database')" id="tab-database" class="tab-button">
                📚 Database Pakan
            </button>
        </div>

        <!-- Tab Calculator Nutrisi -->
        <div id="content-calculator" class="tab-content active">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="calculator-panel">
                        <h3 class="text-lg font-semibold text-amber-800 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center mr-3">
                                🧮
                            </span>
                            Calculator Kebutuhan Nutrisi
                        </h3>

                        <form id="nutritionCalculator" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="animalType" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Ternak <span class="text-red-500">*</span>
                                    </label>
                                    <select id="animalType" name="animal_type" required onchange="updateNutritionNeeds()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">Pilih Jenis Ternak</option>
                                        <option value="sapi_perah">Sapi Perah</option>
                                        <option value="sapi_potong">Sapi Potong</option>
                                        <option value="kambing_perah">Kambing Perah</option>
                                        <option value="kambing_potong">Kambing Potong</option>
                                        <option value="domba">Domba</option>
                                        <option value="kerbau">Kerbau</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="animalWeight" class="block text-sm font-medium text-gray-700 mb-2">
                                        Berat Badan (kg) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="animalWeight" name="weight" min="50" max="1000"
                                        required onchange="updateNutritionNeeds()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="400">
                                </div>

                                <div>
                                    <label for="animalAge" class="block text-sm font-medium text-gray-700 mb-2">
                                        Umur (bulan)
                                    </label>
                                    <input type="number" id="animalAge" name="age" min="1" max="120"
                                        onchange="updateNutritionNeeds()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="24">
                                </div>

                                <div>
                                    <label for="animalGender" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Kelamin
                                    </label>
                                    <select id="animalGender" name="gender" onchange="updateNutritionNeeds()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">Pilih</option>
                                        <option value="jantan">Jantan</option>
                                        <option value="betina">Betina</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Kondisi Khusus
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <label class="condition-tag condition-pregnant cursor-pointer">
                                        <input type="checkbox" name="conditions[]" value="pregnant" class="mr-2"
                                            onchange="updateNutritionNeeds()">
                                        🤰 Bunting
                                    </label>
                                    <label class="condition-tag condition-lactating cursor-pointer">
                                        <input type="checkbox" name="conditions[]" value="lactating" class="mr-2"
                                            onchange="updateNutritionNeeds()">
                                        🍼 Laktasi
                                    </label>
                                    <label class="condition-tag condition-growing cursor-pointer">
                                        <input type="checkbox" name="conditions[]" value="growing" class="mr-2"
                                            onchange="updateNutritionNeeds()">
                                        📈 Pertumbuhan
                                    </label>
                                    <label class="condition-tag condition-sick cursor-pointer">
                                        <input type="checkbox" name="conditions[]" value="sick" class="mr-2"
                                            onchange="updateNutritionNeeds()">
                                        🏥 Pemulihan
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="milkProduction" class="block text-sm font-medium text-gray-700 mb-2">
                                        Produksi Susu (liter/hari)
                                    </label>
                                    <input type="number" id="milkProduction" name="milk_production" min="0"
                                        max="50" step="0.1" onchange="updateNutritionNeeds()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="15">
                                </div>

                                <div>
                                    <label for="activityLevel" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tingkat Aktivitas
                                    </label>
                                    <select id="activityLevel" name="activity" onchange="updateNutritionNeeds()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="low">Rendah - Kandang</option>
                                        <option value="moderate" selected>Sedang - Semi-intensif</option>
                                        <option value="high">Tinggi - Penggembalaan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="weather-factor">
                                <h4 class="font-medium text-amber-800 mb-2 flex items-center">
                                    🌤️ Faktor Cuaca & Musim
                                </h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-amber-700 mb-1">Musim</label>
                                        <select name="season" onchange="updateNutritionNeeds()"
                                            class="w-full px-2 py-1 border border-amber-300 rounded text-sm">
                                            <option value="dry">Kemarau</option>
                                            <option value="wet">Hujan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-amber-700 mb-1">Suhu Rata-rata</label>
                                        <select name="temperature" onchange="updateNutritionNeeds()"
                                            class="w-full px-2 py-1 border border-amber-300 rounded text-sm">
                                            <option value="cool">Sejuk (< 25°C)</option>
                                            <option value="moderate" selected>Sedang (25-30°C)</option>
                                            <option value="hot">Panas (> 30°C)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="button" onclick="calculateNutrition()"
                                class="w-full px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                                🔄 Hitung Kebutuhan Nutrisi
                            </button>
                        </form>
                    </div>

                    <div class="ai-suggestion">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                🤖
                            </div>
                            <h4 class="font-semibold text-blue-900">AI Smart Recommendations</h4>
                            <button type="button" onclick="getAIRecommendations()"
                                class="ml-auto px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                Analisis AI
                            </button>
                        </div>
                        <div id="aiRecommendations">
                            <p class="text-sm text-blue-700">Masukkan data ternak untuk mendapatkan rekomendasi pakan dari
                                AI</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="nutrition-card">
                        <h3 class="text-lg font-semibold text-sky-800 mb-4">Kebutuhan Nutrisi Harian</h3>

                        <div class="nutrition-gauge mx-auto mb-4">
                            <div class="gauge-center">
                                <div class="nutrition-score" id="nutritionScore">85</div>
                                <div class="text-xs text-gray-600">Score</div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Protein Kasar</span>
                                    <span class="text-sm text-gray-600" id="proteinNeed">2.5 kg</span>
                                </div>
                                <div class="nutrient-bar">
                                    <div class="nutrient-fill protein" id="proteinBar" style="width: 75%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Energi (TDN)</span>
                                    <span class="text-sm text-gray-600" id="energyNeed">8.2 kg</span>
                                </div>
                                <div class="nutrient-bar">
                                    <div class="nutrient-fill energy" id="energyBar" style="width: 80%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Serat Kasar</span>
                                    <span class="text-sm text-gray-600" id="fiberNeed">3.2 kg</span>
                                </div>
                                <div class="nutrient-bar">
                                    <div class="nutrient-fill fiber" id="fiberBar" style="width: 90%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Mineral & Vitamin</span>
                                    <span class="text-sm text-gray-600" id="mineralNeed">0.8 kg</span>
                                </div>
                                <div class="nutrient-bar">
                                    <div class="nutrient-fill mineral" id="mineralBar" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-white rounded-lg">
                            <h5 class="font-medium text-gray-800 mb-2">Total Kebutuhan:</h5>
                            <div class="text-2xl font-bold text-green-600" id="totalFeedNeed">15.8 kg/hari</div>
                            <div class="text-sm text-gray-600">Bahan Kering</div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Estimasi Biaya</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Per Hari</span>
                                <span class="font-medium" id="costPerDay">Rp 45.000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Per Bulan</span>
                                <span class="font-medium" id="costPerMonth">Rp 1.350.000</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-sm font-medium text-gray-800">Per Tahun</span>
                                <span class="font-bold text-green-600" id="costPerYear">Rp 16.425.000</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Quick Actions</h4>
                        <div class="space-y-2">
                            <button onclick="generateFeedPlan()"
                                class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors">
                                <span class="text-2xl mr-3">📋</span>
                                <div>
                                    <p class="font-medium text-gray-900">Generate Feed Plan</p>
                                    <p class="text-xs text-gray-500">Buat rencana pakan otomatis</p>
                                </div>
                            </button>

                            <button onclick="optimizeCosting()"
                                class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors">
                                <span class="text-2xl mr-3">💰</span>
                                <div>
                                    <p class="font-medium text-gray-900">Optimize Costing</p>
                                    <p class="text-xs text-gray-500">Cari kombinasi pakan termurah</p>
                                </div>
                            </button>

                            <button onclick="openFeedMixer()"
                                class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors">
                                <span class="text-2xl mr-3">🔄</span>
                                <div>
                                    <p class="font-medium text-gray-900">Feed Mixer</p>
                                    <p class="text-xs text-gray-500">Campur pakan manual</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Rekomendasi -->
        <div id="content-recommendations" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div class="recommendation-card">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Rekomendasi Utama</h3>

                        <div class="space-y-4">
                            <div class="bg-white rounded-lg p-4 border-l-4 border-green-500">
                                <h4 class="font-semibold text-gray-900 mb-2">Pakan Konsentrat Premium</h4>
                                <p class="text-sm text-gray-600 mb-3">Formula khusus untuk sapi perah produktif dengan
                                    protein tinggi</p>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Jumlah:</span>
                                        <span class="font-medium">8 kg/hari</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Biaya:</span>
                                        <span class="font-medium">Rp 32.000/hari</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Protein:</span>
                                        <span class="font-medium">18%</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Efisiensi:</span>
                                        <span class="font-medium text-green-600">92%</span>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center justify-between">
                                    <span class="cost-indicator cost-medium">Biaya Sedang</span>
                                    <button class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                                        Gunakan
                                    </button>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 border-l-4 border-yellow-500">
                                <h4 class="font-semibold text-gray-900 mb-2">Hijauan + Fermentasi</h4>
                                <p class="text-sm text-gray-600 mb-3">Kombinasi rumput gajah dengan silase jagung untuk
                                    serat optimal</p>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Jumlah:</span>
                                        <span class="font-medium">25 kg/hari</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Biaya:</span>
                                        <span class="font-medium">Rp 15.000/hari</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Serat:</span>
                                        <span class="font-medium">28%</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Efisiensi:</span>
                                        <span class="font-medium text-green-600">85%</span>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center justify-between">
                                    <span class="cost-indicator cost-low">Biaya Rendah</span>
                                    <button class="px-3 py-1 bg-yellow-600 text-white rounded text-sm hover:bg-yellow-700">
                                        Gunakan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Alternatif Pakan</h3>

                        <div class="space-y-3">
                            <div class="feed-comparison">
                                <div class="comparison-item">
                                    <div>
                                        <h5 class="font-medium">Dedak Padi + Bungkil Kelapa</h5>
                                        <p class="text-xs text-gray-500">Ekonomis untuk maintenance</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium">Rp 28.000/hari</div>
                                        <div class="cost-indicator cost-low">Hemat 38%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="feed-comparison">
                                <div class="comparison-item">
                                    <div>
                                        <h5 class="font-medium">Complete Feed Premium</h5>
                                        <p class="text-xs text-gray-500">All-in-one untuk hasil maksimal</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium">Rp 65.000/hari</div>
                                        <div class="cost-indicator cost-high">Premium +45%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="feed-comparison">
                                <div class="comparison-item">
                                    <div>
                                        <h5 class="font-medium">Pakan Lokal Mix</h5>
                                        <p class="text-xs text-gray-500">Berbahan lokal tersedia</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium">Rp 35.000/hari</div>
                                        <div class="cost-indicator cost-medium">Standar</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Analisis Nutrisi</h3>

                        <div class="space-y-4">
                            <div class="optimization-tip">
                                <h5 class="font-medium text-blue-800 flex items-center mb-2">
                                    💡 Tips Optimasi
                                </h5>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Tambahkan 0.5kg konsentrat untuk boost produksi susu</li>
                                    <li>• Berikan mineral block untuk mencegah defisiensi</li>
                                    <li>• Pertimbangkan probiotik untuk kesehatan pencernaan</li>
                                </ul>
                            </div>

                            <div class="deficiency-warning">
                                ⚠️ Potensi kekurangan Vitamin A - tambahkan hijauan segar
                            </div>

                            <div class="surplus-info">
                                ✅ Protein cukup - produksi susu dapat optimal
                            </div>

                            <div class="seasonal-adjustment">
                                <h5 class="font-medium text-cyan-800 mb-2">🌱 Penyesuaian Musim</h5>
                                <p class="text-sm text-cyan-700">
                                    Musim hujan: Tambah 10% konsentrat karena kualitas hijauan menurun.
                                    Pastikan pakan tidak lembab/berjamur.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">ROI Analysis</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">+25%</div>
                                    <div class="text-sm text-green-700">Peningkatan Produksi</div>
                                </div>
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">3.2:1</div>
                                    <div class="text-sm text-blue-700">ROI Ratio</div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Investasi Pakan/Bulan</span>
                                    <span class="font-medium">Rp 1.350.000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Peningkatan Pendapatan</span>
                                    <span class="font-medium text-green-600">Rp 4.320.000</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-sm font-medium text-gray-800">Net Profit</span>
                                    <span class="font-bold text-green-600">Rp 2.970.000</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <h5 class="font-medium text-gray-800 mb-1">Payback Period</h5>
                                <div class="text-lg font-bold text-gray-900">2.3 bulan</div>
                                <div class="text-sm text-gray-600">Untuk mencapai break-even</div>
                            </div>
                        </div>
                    </div>

                    <div class="inventory-alert">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                            <div>
                                <h5 class="font-medium text-red-800 mb-1">Peringatan Stok</h5>
                                <p class="text-sm text-red-700">
                                    Konsentrat sisa 3 hari. Segera lakukan pemesanan untuk mencegah kekosongan stok.
                                </p>
                                <button class="mt-2 px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                                    Order Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Jadwal Pakan -->
        <div id="content-schedule" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Jadwal Pemberian Pakan</h3>
                            <button onclick="createSchedule()"
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                                + Buat Jadwal
                            </button>
                        </div>

                        <div class="feeding-timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot completed"></div>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="schedule-time">05:30</div>
                                        <span class="text-sm font-medium text-green-700">✅ Selesai</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900">Pakan Pagi - Hijauan</h4>
                                    <p class="text-sm text-gray-600 mt-1">Rumput gajah segar + mineral mix</p>
                                    <div class="mt-2 text-sm text-gray-500">Jumlah: 15 kg • Diberikan: 05:25</div>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot current"></div>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="schedule-time">11:00</div>
                                        <span class="text-sm font-medium text-blue-700">🟦 Berlangsung</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900">Pakan Siang - Konsentrat</h4>
                                    <p class="text-sm text-gray-600 mt-1">Konsentrat protein tinggi</p>
                                    <div class="mt-2 text-sm text-gray-500">Jumlah: 4 kg • Target: 11:00</div>
                                    <div class="mt-3">
                                        <button
                                            class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 mr-2">
                                            ✅ Tandai Selesai
                                        </button>
                                        <button class="px-3 py-1 bg-gray-400 text-white rounded text-sm hover:bg-gray-500">
                                            ⏰ Tunda
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot upcoming"></div>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="schedule-time">16:30</div>
                                        <span class="text-sm font-medium text-gray-500">⏳ Mendatang</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900">Pakan Sore - Mix</h4>
                                    <p class="text-sm text-gray-600 mt-1">Silase jagung + dedak padi</p>
                                    <div class="mt-2 text-sm text-gray-500">Jumlah: 8 kg • Estimasi: 16:30</div>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot upcoming"></div>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="schedule-time">19:00</div>
                                        <span class="text-sm font-medium text-gray-500">⏳ Mendatang</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900">Pakan Malam - Hijauan</h4>
                                    <p class="text-sm text-gray-600 mt-1">Jerami fermentasi + molases</p>
                                    <div class="mt-2 text-sm text-gray-500">Jumlah: 12 kg • Estimasi: 19:00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Template Jadwal</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors">
                                <h4 class="font-medium text-gray-900 mb-2">Sapi Perah Laktasi</h4>
                                <p class="text-sm text-gray-600 mb-3">4x pemberian dengan fokus protein tinggi</p>
                                <div class="space-y-1 text-xs text-gray-500">
                                    <div>05:30 - Hijauan + Mineral</div>
                                    <div>11:00 - Konsentrat protein</div>
                                    <div>16:30 - Mix konsentrat</div>
                                    <div>19:00 - Hijauan malam</div>
                                </div>
                                <button
                                    class="mt-3 w-full px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200">
                                    Gunakan Template
                                </button>
                            </div>

                            <div
                                class="border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-500 transition-colors">
                                <h4 class="font-medium text-gray-900 mb-2">Sapi Potong</h4>
                                <p class="text-sm text-gray-600 mb-3">3x pemberian untuk pertumbuhan optimal</p>
                                <div class="space-y-1 text-xs text-gray-500">
                                    <div>06:00 - Hijauan pagi</div>
                                    <div>12:00 - Konsentrat + hijauan</div>
                                    <div>18:00 - Hijauan sore</div>
                                </div>
                                <button
                                    class="mt-3 w-full px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                                    Gunakan Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress Hari Ini</h3>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Jadwal Selesai</span>
                                <span class="text-2xl font-bold text-green-600">1/4</span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-green-600 h-3 rounded-full" style="width: 25%"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <div class="text-lg font-bold text-blue-600">15.2kg</div>
                                    <div class="text-xs text-gray-500">Sudah diberikan</div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-orange-600">23.8kg</div>
                                    <div class="text-xs text-gray-500">Target hari ini</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Reminder</h3>

                        <div class="space-y-3">
                            <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <span class="text-2xl mr-3">⏰</span>
                                <div>
                                    <p class="font-medium text-yellow-800">Pakan Siang</p>
                                    <p class="text-sm text-yellow-600">45 menit lagi (11:00)</p>
                                </div>
                            </div>

                            <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <span class="text-2xl mr-3">💊</span>
                                <div>
                                    <p class="font-medium text-blue-800">Vitamin Mix</p>
                                    <p class="text-sm text-blue-600">Tambahkan ke pakan sore</p>
                                </div>
                            </div>

                            <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                <span class="text-2xl mr-3">📦</span>
                                <div>
                                    <p class="font-medium text-red-800">Stok Konsentrat</p>
                                    <p class="text-sm text-red-600">Sisa 3 hari, perlu order</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Settings</h3>

                        <div class="space-y-3">
                            <label class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Auto Reminder</span>
                                <input type="checkbox" checked class="toggle-switch">
                            </label>

                            <label class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Weather Adjustment</span>
                                <input type="checkbox" checked class="toggle-switch">
                            </label>

                            <label class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Mobile Notifications</span>
                                <input type="checkbox" class="toggle-switch">
                            </label>

                            <div class="pt-3 border-t border-gray-200">
                                <label class="block text-sm text-gray-700 mb-2">Toleransi Waktu</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    <option>± 15 menit</option>
                                    <option>± 30 menit</option>
                                    <option>± 1 jam</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Monitoring -->
        <div id="content-monitoring" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konsumsi Pakan 7 Hari Terakhir</h3>

                        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center mb-4">
                            <div class="text-center">
                                <div class="text-4xl mb-2">📊</div>
                                <p class="text-gray-600">Chart akan ditampilkan di sini</p>
                                <p class="text-sm text-gray-500">Data konsumsi harian dalam bentuk grafik</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">18.5kg</div>
                                <div class="text-sm text-gray-500">Rata-rata</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">21.2kg</div>
                                <div class="text-sm text-gray-500">Tertinggi</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">15.8kg</div>
                                <div class="text-sm text-gray-500">Terendah</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">95%</div>
                                <div class="text-sm text-gray-500">Konsistensi</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend Produktivitas</h3>

                        <div class="space-y-4">
                            <div class="feed-comparison">
                                <div class="comparison-item">
                                    <div>
                                        <h5 class="font-medium">Produksi Susu</h5>
                                        <p class="text-sm text-gray-500">Rata-rata per hari</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">22.5 L</div>
                                        <div class="trend-indicator trend-up">
                                            ↑ +2.3L dari minggu lalu
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="feed-comparison">
                                <div class="comparison-item">
                                    <div>
                                        <h5 class="font-medium">Pertambahan Berat</h5>
                                        <p class="text-sm text-gray-500">Per minggu</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">1.8 kg</div>
                                        <div class="trend-indicator trend-stable">
                                            → Stabil
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="feed-comparison">
                                <div class="comparison-item">
                                    <div>
                                        <h5 class="font-medium">Efisiensi Pakan</h5>
                                        <p class="text-sm text-gray-500">Feed Conversion Ratio</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">1:6.2</div>
                                        <div class="trend-indicator trend-up">
                                            ↑ Membaik 0.3 poin
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="feed-comparison">
                                <div class="comparison-item">
                                    <div>
                                        <h5 class="font-medium">Kondisi Tubuh Score</h5>
                                        <p class="text-sm text-gray-500">BCS rata-rata</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">3.2/5</div>
                                        <div class="trend-indicator trend-up">
                                            ↑ +0.1 poin optimal
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Analisis Biaya Pakan</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-3 bg-red-50 rounded-lg">
                                    <div class="text-lg font-bold text-red-600">Rp 45.000</div>
                                    <div class="text-sm text-red-700">Biaya/Hari</div>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-lg">
                                    <div class="text-lg font-bold text-green-600">Rp 2.000</div>
                                    <div class="text-sm text-green-700">Per Liter Susu</div>
                                </div>
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <div class="text-lg font-bold text-blue-600">67%</div>
                                    <div class="text-sm text-blue-700">% Total Biaya</div>
                                </div>
                            </div>

                            <div class="price-trend">
                                <span class="text-sm text-gray-600 mr-2">Trend harga pakan:</span>
                                <span class="trend-indicator trend-up text-red-500">
                                    ↑ +5% bulan ini
                                </span>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <h5 class="font-medium text-yellow-800 mb-1">💡 Rekomendasi Penghematan</h5>
                                <p class="text-sm text-yellow-700">
                                    Substitusi 20% konsentrat dengan local feed mix dapat menghemat Rp 9.000/hari
                                    tanpa mengurangi kualitas nutrisi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Feed Intake Analysis</h3>

                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium">Target vs Aktual</span>
                                    <span class="text-sm text-green-600">95% tercapai</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 95%"></div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Hijauan</span>
                                    <span class="font-medium">24.5kg (98%)</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Konsentrat</span>
                                    <span class="font-medium">7.8kg (92%)</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Mineral/Vitamin</span>
                                    <span class="font-medium">0.3kg (100%)</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <h5 class="font-medium text-gray-800 mb-1">Kualitas Konsumsi</h5>
                                <div class="text-2xl font-bold text-green-600">A-</div>
                                <div class="text-sm text-gray-600">Sangat baik, sedikit kekurangan konsentrat</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Health Indicators</h3>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                <span class="text-sm">Pencernaan</span>
                                <span class="text-green-600 font-medium">Normal</span>
                            </div>

                            <div class="flex items-center justify-between p-2 bg-yellow-50 rounded">
                                <span class="text-sm">Nafsu Makan</span>
                                <span class="text-yellow-600 font-medium">Perlu Perhatian</span>
                            </div>

                            <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                <span class="text-sm">Aktivitas</span>
                                <span class="text-green-600 font-medium">Aktif</span>
                            </div>

                            <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                <span class="text-sm">Hidrasi</span>
                                <span class="text-green-600 font-medium">Cukup</span>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <h5 class="font-medium text-blue-800 mb-1">📋 Action Items</h5>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Monitor asupan konsentrat lebih ketat</li>
                                <li>• Cek kualitas hijauan untuk palatabilitas</li>
                                <li>• Pertimbangkan feed additive probiotik</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Data</h3>

                        <div class="space-y-3">
                            <button
                                class="w-full flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="text-2xl mr-3">📊</span>
                                <div class="text-left">
                                    <p class="font-medium">Export to Excel</p>
                                    <p class="text-xs text-gray-500">Data konsumsi & analisis</p>
                                </div>
                            </button>

                            <button
                                class="w-full flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="text-2xl mr-3">📄</span>
                                <div class="text-left">
                                    <p class="font-medium">Generate Report</p>
                                    <p class="text-xs text-gray-500">Laporan lengkap PDF</p>
                                </div>
                            </button>

                            <button
                                class="w-full flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="text-2xl mr-3">📤</span>
                                <div class="text-left">
                                    <p class="font-medium">Share Data</p>
                                    <p class="text-xs text-gray-500">Kirim ke penyuluh</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Database Pakan -->
        <div id="content-database" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 p-4 sticky top-4">
                        <h4 class="font-semibold text-gray-900 mb-4">Filter Pakan</h4>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                <select id="categoryFilter" onchange="filterFeeds()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    <option value="">Semua Kategori</option>
                                    <option value="hijauan">Hijauan</option>
                                    <option value="konsentrat">Konsentrat</option>
                                    <option value="limbah">Limbah Pertanian</option>
                                    <option value="mineral">Mineral & Vitamin</option>
                                    <option value="additive">Feed Additive</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    <option value="">Semua Harga</option>
                                    <option value="low">
                                        < Rp 3.000/kg</option>
                                    <option value="medium">Rp 3.000 - 6.000/kg</option>
                                    <option value="high">> Rp 6.000/kg</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ketersediaan</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2">
                                        <span class="text-sm">Tersedia Lokal</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2">
                                        <span class="text-sm">Import</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2">
                                        <span class="text-sm">Musiman</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                                <input type="text" placeholder="Nama pakan..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @php
                            $feedDatabase = [
                                [
                                    'name' => 'Rumput Gajah Segar',
                                    'category' => 'hijauan',
                                    'protein' => '12%',
                                    'energy' => '55%',
                                    'fiber' => '28%',
                                    'price' => 1500,
                                    'unit' => 'kg',
                                    'availability' => 'Lokal',
                                    'quality' => 'excellent',
                                    'description' => 'Hijauan berkualitas tinggi, mudah dicerna, palatabilitas baik',
                                ],
                                [
                                    'name' => 'Konsentrat Protein 18%',
                                    'category' => 'konsentrat',
                                    'protein' => '18%',
                                    'energy' => '78%',
                                    'fiber' => '8%',
                                    'price' => 4500,
                                    'unit' => 'kg',
                                    'availability' => 'Lokal',
                                    'quality' => 'excellent',
                                    'description' => 'Konsentrat premium untuk sapi perah dengan protein tinggi',
                                ],
                                [
                                    'name' => 'Silase Jagung',
                                    'category' => 'hijauan',
                                    'protein' => '8%',
                                    'energy' => '68%',
                                    'fiber' => '22%',
                                    'price' => 2200,
                                    'unit' => 'kg',
                                    'availability' => 'Lokal',
                                    'quality' => 'good',
                                    'description' => 'Fermentasi jagung dengan palatabilitas tinggi',
                                ],
                                [
                                    'name' => 'Dedak Padi',
                                    'category' => 'limbah',
                                    'protein' => '13%',
                                    'energy' => '65%',
                                    'fiber' => '12%',
                                    'price' => 2800,
                                    'unit' => 'kg',
                                    'availability' => 'Lokal',
                                    'quality' => 'good',
                                    'description' => 'Limbah penggilingan padi, ekonomis dan bergizi',
                                ],
                                [
                                    'name' => 'Bungkil Kelapa',
                                    'category' => 'konsentrat',
                                    'protein' => '21%',
                                    'energy' => '58%',
                                    'fiber' => '15%',
                                    'price' => 3200,
                                    'unit' => 'kg',
                                    'availability' => 'Lokal',
                                    'quality' => 'good',
                                    'description' => 'Sumber protein nabati berkualitas dari kelapa lokal',
                                ],
                                [
                                    'name' => 'Mineral Mix Complete',
                                    'category' => 'mineral',
                                    'protein' => '0%',
                                    'energy' => '0%',
                                    'fiber' => '0%',
                                    'price' => 12000,
                                    'unit' => 'kg',
                                    'availability' => 'Import',
                                    'quality' => 'excellent',
                                    'description' => 'Campuran mineral dan vitamin lengkap untuk ternak',
                                ],
                            ];
                        @endphp

                        @foreach ($feedDatabase as $feed)
                            <div class="feed-item" data-category="{{ $feed['category'] }}">
                                <div class="feed-quality-indicator quality-{{ $feed['quality'] }}"></div>

                                <div class="mb-3">
                                    <h4 class="font-semibold text-gray-900">{{ $feed['name'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $feed['description'] }}</p>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="grid grid-cols-3 gap-2 text-xs">
                                        <div class="text-center p-1 bg-red-50 rounded">
                                            <div class="font-medium text-red-700">{{ $feed['protein'] }}</div>
                                            <div class="text-red-600">Protein</div>
                                        </div>
                                        <div class="text-center p-1 bg-orange-50 rounded">
                                            <div class="font-medium text-orange-700">{{ $feed['energy'] }}</div>
                                            <div class="text-orange-600">Energi</div>
                                        </div>
                                        <div class="text-center p-1 bg-green-50 rounded">
                                            <div class="font-medium text-green-700">{{ $feed['fiber'] }}</div>
                                            <div class="text-green-600">Serat</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <div class="text-lg font-bold text-gray-900">Rp
                                            {{ number_format($feed['price']) }}</div>
                                        <div class="text-sm text-gray-500">per {{ $feed['unit'] }}</div>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                        {{ $feed['availability'] }}
                                    </span>
                                </div>

                                <div class="flex space-x-2">
                                    <button onclick="addToMix('{{ $feed['name'] }}')"
                                        class="flex-1 px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                        + Tambah
                                    </button>
                                    <button onclick="viewFeedDetail('{{ $feed['name'] }}')"
                                        class="px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Feed Mixer</h3>

                        <div class="space-y-4">
                            <div class="border border-gray-300 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3">Pakan yang Dipilih:</h4>
                                <div id="selectedFeeds" class="space-y-2">
                                    <p class="text-sm text-gray-500">Belum ada pakan yang dipilih</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <div class="text-lg font-bold text-blue-600" id="totalWeight">0 kg</div>
                                    <div class="text-sm text-blue-700">Total Berat</div>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-lg">
                                    <div class="text-lg font-bold text-green-600" id="totalCost">Rp 0</div>
                                    <div class="text-sm text-green-700">Total Biaya</div>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <button onclick="clearMix()"
                                    class="flex-1 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                    Clear All
                                </button>
                                <button onclick="saveFeedMix()"
                                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Simpan Mix
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Feed Mixer -->
    <div id="feedMixerModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Feed Mixer Professional</h3>
                <button onclick="closeFeedMixer()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Formula Campuran</h4>
                    <div class="space-y-3" id="mixerFormula">
                        <!-- Feed mixer content will be populated here -->
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Analisis Nutrisi</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Protein Kasar:</span>
                                <span class="font-medium" id="mixProtein">0%</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Energi (TDN):</span>
                                <span class="font-medium" id="mixEnergy">0%</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Serat Kasar:</span>
                                <span class="font-medium" id="mixFiber">0%</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-semibold">Total Biaya/kg:</span>
                                <span class="font-bold text-green-600" id="mixCostPerKg">Rp 0</span>
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
        let selectedFeeds = [];
        let nutritionData = {};

        function showTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            document.getElementById(`tab-${tabName}`).classList.add('active');
            document.getElementById(`content-${tabName}`).classList.add('active');
        }

        function updateNutritionNeeds() {
            const animalType = document.getElementById('animalType').value;
            const weight = parseFloat(document.getElementById('animalWeight').value) || 0;
            const age = parseFloat(document.getElementById('animalAge').value) || 0;
            const milkProduction = parseFloat(document.getElementById('milkProduction').value) || 0;

            if (!animalType || !weight) return;

            // Base calculation (simplified)
            let proteinNeed = weight * 0.06; // 6% of body weight
            let energyNeed = weight * 0.18; // 18% of body weight
            let fiberNeed = weight * 0.08; // 8% of body weight
            let mineralNeed = weight * 0.002; // 0.2% of body weight

            // Adjust for milk production
            if (milkProduction > 0) {
                proteinNeed += milkProduction * 0.15;
                energyNeed += milkProduction * 0.35;
            }

            // Adjust for conditions
            const conditions = document.querySelectorAll('input[name="conditions[]"]:checked');
            conditions.forEach(condition => {
                switch (condition.value) {
                    case 'pregnant':
                        proteinNeed *= 1.2;
                        energyNeed *= 1.15;
                        break;
                    case 'lactating':
                        proteinNeed *= 1.5;
                        energyNeed *= 1.4;
                        break;
                    case 'growing':
                        proteinNeed *= 1.3;
                        energyNeed *= 1.25;
                        break;
                }
            });

            const totalFeedNeed = proteinNeed + energyNeed + fiberNeed + mineralNeed;

            // Update UI
            document.getElementById('proteinNeed').textContent = proteinNeed.toFixed(1) + ' kg';
            document.getElementById('energyNeed').textContent = energyNeed.toFixed(1) + ' kg';
            document.getElementById('fiberNeed').textContent = fiberNeed.toFixed(1) + ' kg';
            document.getElementById('mineralNeed').textContent = mineralNeed.toFixed(1) + ' kg';
            document.getElementById('totalFeedNeed').textContent = totalFeedNeed.toFixed(1) + ' kg/hari';

            // Update cost estimation
            const costPerDay = totalFeedNeed * 3000; // Average Rp 3000/kg
            document.getElementById('costPerDay').textContent = 'Rp ' + Math.round(costPerDay).toLocaleString();
            document.getElementById('costPerMonth').textContent = 'Rp ' + Math.round(costPerDay * 30).toLocaleString();
            document.getElementById('costPerYear').textContent = 'Rp ' + Math.round(costPerDay * 365).toLocaleString();

            // Store for other functions
            nutritionData = {
                protein: proteinNeed,
                energy: energyNeed,
                fiber: fiberNeed,
                mineral: mineralNeed,
                total: totalFeedNeed
            };
        }

        function calculateNutrition() {
            updateNutritionNeeds();

            // Animate nutrition bars
            const proteinPercent = Math.min((nutritionData.protein / (nutritionData.total * 0.25)) * 100, 100);
            const energyPercent = Math.min((nutritionData.energy / (nutritionData.total * 0.6)) * 100, 100);
            const fiberPercent = Math.min((nutritionData.fiber / (nutritionData.total * 0.25)) * 100, 100);
            const mineralPercent = Math.min((nutritionData.mineral / (nutritionData.total * 0.05)) * 100, 100);

            document.getElementById('proteinBar').style.width = proteinPercent + '%';
            document.getElementById('energyBar').style.width = energyPercent + '%';
            document.getElementById('fiberBar').style.width = fiberPercent + '%';
            document.getElementById('mineralBar').style.width = mineralPercent + '%';

            // Update nutrition score
            const avgPercent = (proteinPercent + energyPercent + fiberPercent + mineralPercent) / 4;
            document.getElementById('nutritionScore').textContent = Math.round(avgPercent);

            alert('Perhitungan kebutuhan nutrisi berhasil!');
        }

        function getAIRecommendations() {
            if (!nutritionData.total) {
                alert('Silakan hitung kebutuhan nutrisi terlebih dahulu');
                return;
            }

            const aiDiv = document.getElementById('aiRecommendations');
            aiDiv.innerHTML = `
                    <div class="space-y-3">
                        <div class="p-3 bg-white border border-blue-200 rounded-lg">
                            <h5 class="font-semibold text-blue-800 mb-2">🎯 Rekomendasi Utama</h5>
                            <p class="text-sm text-blue-700">
                                Kombinasi 60% hijauan berkualitas + 35% konsentrat protein tinggi + 5% mineral mix
                                akan memberikan nutrisi optimal dengan efisiensi biaya terbaik.
                            </p>
                        </div>
                        
                        <div class="p-3 bg-white border border-green-200 rounded-lg">
                            <h5 class="font-semibold text-green-800 mb-2">💡 Optimasi</h5>
                            <p class="text-sm text-green-700">
                                Tambah
kan probiotik untuk meningkatkan pencernaan dan mengurangi 10% konsentrat untuk 
                            menghemat biaya tanpa mengurangi performa.
                        </p>
                    </div>
                    
                    <div class="p-3 bg-white border border-yellow-200 rounded-lg">
                        <h5 class="font-semibold text-yellow-800 mb-2">⚠️ Peringatan</h5>
                        <p class="text-sm text-yellow-700">
                            Pantau konsumsi air (minimal 50L/hari) dan pastikan hijauan tidak layu. 
                            Berikan pakan dalam porsi kecil untuk meningkatkan palatabilitas.
                        </p>
                    </div>
                </div>
            `;
        }

        function generateFeedPlan() {
            if (!nutritionData.total) {
                alert('Silakan hitung kebutuhan nutrisi terlebih dahulu');
                return;
            }

            const plan = {
                morning: {
                    time: '05:30',
                    feeds: [{
                            name: 'Rumput Gajah Segar',
                            amount: Math.round(nutritionData.total * 0.4)
                        },
                        {
                            name: 'Mineral Mix',
                            amount: Math.round(nutritionData.mineral * 0.5)
                        }
                    ]
                },
                noon: {
                    time: '11:00',
                    feeds: [{
                        name: 'Konsentrat Protein 18%',
                        amount: Math.round(nutritionData.protein * 0.6)
                    }]
                },
                afternoon: {
                    time: '16:30',
                    feeds: [{
                            name: 'Silase Jagung',
                            amount: Math.round(nutritionData.energy * 0.3)
                        },
                        {
                            name: 'Dedak Padi',
                            amount: Math.round(nutritionData.fiber * 0.4)
                        }
                    ]
                },
                evening: {
                    time: '19:00',
                    feeds: [{
                            name: 'Rumput Gajah Segar',
                            amount: Math.round(nutritionData.total * 0.3)
                        },
                        {
                            name: 'Mineral Mix',
                            amount: Math.round(nutritionData.mineral * 0.5)
                        }
                    ]
                }
            };

            let planHtml = '<div class="space-y-4">';
            Object.entries(plan).forEach(([period, data]) => {
                planHtml += `
                    <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                        <h4 class="font-semibold text-green-800">${period.charAt(0).toUpperCase() + period.slice(1)} - ${data.time}</h4>
                        <ul class="mt-2 space-y-1">
                `;
                data.feeds.forEach(feed => {
                    planHtml += `<li class="text-sm text-green-700">• ${feed.name}: ${feed.amount} kg</li>`;
                });
                planHtml += '</ul></div>';
            });
            planHtml += '</div>';

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-backdrop';
            modal.innerHTML = `
                <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white modal-content">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Rencana Pakan Harian</h3>
                        <button onclick="this.closest('.modal-backdrop').remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    ${planHtml}
                    <div class="mt-6 flex space-x-3">
                        <button onclick="saveFeedPlan()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Simpan Rencana
                        </button>
                        <button onclick="this.closest('.modal-backdrop').remove()" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function optimizeCosting() {
            if (!nutritionData.total) {
                alert('Silakan hitung kebutuhan nutrisi terlebih dahulu');
                return;
            }

            const alternatives = [{
                    name: 'Ekonomis',
                    cost: Math.round(nutritionData.total * 2500),
                    feeds: ['Rumput Lapang', 'Dedak Padi', 'Bungkil Kelapa'],
                    efficiency: 78,
                    description: 'Hemat 35% dengan bahan lokal'
                },
                {
                    name: 'Balanced',
                    cost: Math.round(nutritionData.total * 3200),
                    feeds: ['Rumput Gajah', 'Konsentrat Standard', 'Mineral Mix'],
                    efficiency: 87,
                    description: 'Keseimbangan optimal harga-kualitas'
                },
                {
                    name: 'Premium',
                    cost: Math.round(nutritionData.total * 4500),
                    feeds: ['Complete Feed', 'Probiotik', 'Vitamin Premium'],
                    efficiency: 95,
                    description: 'Hasil maksimal untuk produktivitas tinggi'
                }
            ];

            let html = '<div class="space-y-4">';
            alternatives.forEach(alt => {
                html += `
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-500 cursor-pointer transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-gray-900">${alt.name}</h4>
                            <span class="text-lg font-bold text-green-600">Rp ${alt.cost.toLocaleString()}/hari</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">${alt.description}</p>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                Efisiensi: ${alt.efficiency}% | Pakan: ${alt.feeds.join(', ')}
                            </div>
                            <button onclick="selectCostOption('${alt.name}')" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                Pilih
                            </button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-backdrop';
            modal.innerHTML = `
                <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white modal-content">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Optimasi Biaya Pakan</h3>
                        <button onclick="this.closest('.modal-backdrop').remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    ${html}
                </div>
            `;
            document.body.appendChild(modal);
        }

        function openFeedMixer() {
            document.getElementById('feedMixerModal').classList.remove('hidden');
            updateMixerDisplay();
        }

        function closeFeedMixer() {
            document.getElementById('feedMixerModal').classList.add('hidden');
        }

        function addToMix(feedName) {
            const existingIndex = selectedFeeds.findIndex(f => f.name === feedName);
            if (existingIndex >= 0) {
                selectedFeeds[existingIndex].amount += 1;
            } else {
                selectedFeeds.push({
                    name: feedName,
                    amount: 1,
                    price: getFeedPrice(feedName),
                    protein: getFeedProtein(feedName),
                    energy: getFeedEnergy(feedName),
                    fiber: getFeedFiber(feedName)
                });
            }
            updateSelectedFeedsDisplay();
            updateMixerDisplay();
        }

        function getFeedPrice(feedName) {
            const prices = {
                'Rumput Gajah Segar': 1500,
                'Konsentrat Protein 18%': 4500,
                'Silase Jagung': 2200,
                'Dedak Padi': 2800,
                'Bungkil Kelapa': 3200,
                'Mineral Mix Complete': 12000
            };
            return prices[feedName] || 3000;
        }

        function getFeedProtein(feedName) {
            const protein = {
                'Rumput Gajah Segar': 12,
                'Konsentrat Protein 18%': 18,
                'Silase Jagung': 8,
                'Dedak Padi': 13,
                'Bungkil Kelapa': 21,
                'Mineral Mix Complete': 0
            };
            return protein[feedName] || 10;
        }

        function getFeedEnergy(feedName) {
            const energy = {
                'Rumput Gajah Segar': 55,
                'Konsentrat Protein 18%': 78,
                'Silase Jagung': 68,
                'Dedak Padi': 65,
                'Bungkil Kelapa': 58,
                'Mineral Mix Complete': 0
            };
            return energy[feedName] || 60;
        }

        function getFeedFiber(feedName) {
            const fiber = {
                'Rumput Gajah Segar': 28,
                'Konsentrat Protein 18%': 8,
                'Silase Jagung': 22,
                'Dedak Padi': 12,
                'Bungkil Kelapa': 15,
                'Mineral Mix Complete': 0
            };
            return fiber[feedName] || 15;
        }

        function updateSelectedFeedsDisplay() {
            const container = document.getElementById('selectedFeeds');
            if (selectedFeeds.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500">Belum ada pakan yang dipilih</p>';
                document.getElementById('totalWeight').textContent = '0 kg';
                document.getElementById('totalCost').textContent = 'Rp 0';
                return;
            }

            let html = '';
            let totalWeight = 0;
            let totalCost = 0;

            selectedFeeds.forEach((feed, index) => {
                totalWeight += feed.amount;
                totalCost += feed.amount * feed.price;

                html += `
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div>
                            <span class="font-medium">${feed.name}</span>
                            <span class="text-sm text-gray-500">- ${feed.amount} kg</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="adjustFeedAmount(${index}, -1)" class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600">-</button>
                            <button onclick="adjustFeedAmount(${index}, 1)" class="w-6 h-6 bg-green-500 text-white rounded text-xs hover:bg-green-600">+</button>
                            <button onclick="removeFeed(${index})" class="w-6 h-6 bg-gray-500 text-white rounded text-xs hover:bg-gray-600">×</button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            document.getElementById('totalWeight').textContent = totalWeight + ' kg';
            document.getElementById('totalCost').textContent = 'Rp ' + totalCost.toLocaleString();
        }

        function adjustFeedAmount(index, change) {
            selectedFeeds[index].amount += change;
            if (selectedFeeds[index].amount <= 0) {
                selectedFeeds.splice(index, 1);
            }
            updateSelectedFeedsDisplay();
            updateMixerDisplay();
        }

        function removeFeed(index) {
            selectedFeeds.splice(index, 1);
            updateSelectedFeedsDisplay();
            updateMixerDisplay();
        }

        function updateMixerDisplay() {
            if (selectedFeeds.length === 0) return;

            let totalWeight = selectedFeeds.reduce((sum, feed) => sum + feed.amount, 0);
            let weightedProtein = selectedFeeds.reduce((sum, feed) => sum + (feed.protein * feed.amount), 0);
            let weightedEnergy = selectedFeeds.reduce((sum, feed) => sum + (feed.energy * feed.amount), 0);
            let weightedFiber = selectedFeeds.reduce((sum, feed) => sum + (feed.fiber * feed.amount), 0);
            let totalCost = selectedFeeds.reduce((sum, feed) => sum + (feed.price * feed.amount), 0);

            const avgProtein = (weightedProtein / totalWeight).toFixed(1);
            const avgEnergy = (weightedEnergy / totalWeight).toFixed(1);
            const avgFiber = (weightedFiber / totalWeight).toFixed(1);
            const costPerKg = (totalCost / totalWeight).toFixed(0);

            document.getElementById('mixProtein').textContent = avgProtein + '%';
            document.getElementById('mixEnergy').textContent = avgEnergy + '%';
            document.getElementById('mixFiber').textContent = avgFiber + '%';
            document.getElementById('mixCostPerKg').textContent = 'Rp ' + parseInt(costPerKg).toLocaleString();

            // Update mixer formula display
            const formulaContainer = document.getElementById('mixerFormula');
            if (formulaContainer) {
                let formulaHtml = '';
                selectedFeeds.forEach((feed, index) => {
                    const percentage = ((feed.amount / totalWeight) * 100).toFixed(1);
                    formulaHtml += `
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded">
                            <div>
                                <span class="font-medium">${feed.name}</span>
                                <span class="text-sm text-gray-500 block">${percentage}% (${feed.amount} kg)</span>
                            </div>
                            <div class="text-right text-sm">
                                <div>Rp ${(feed.price * feed.amount).toLocaleString()}</div>
                                <div class="text-gray-500">P:${feed.protein}% E:${feed.energy}%</div>
                            </div>
                        </div>
                    `;
                });
                formulaContainer.innerHTML = formulaHtml;
            }
        }

        function clearMix() {
            selectedFeeds = [];
            updateSelectedFeedsDisplay();
            updateMixerDisplay();
        }

        function saveFeedMix() {
            if (selectedFeeds.length === 0) {
                alert('Belum ada pakan yang dipilih');
                return;
            }

            const mixName = prompt('Nama untuk campuran pakan ini:');
            if (!mixName) return;

            const mixData = {
                name: mixName,
                feeds: selectedFeeds,
                created: new Date().toISOString(),
                totalWeight: selectedFeeds.reduce((sum, feed) => sum + feed.amount, 0),
                totalCost: selectedFeeds.reduce((sum, feed) => sum + (feed.price * feed.amount), 0)
            };

            // Save to localStorage (in real app, save to database)
            const savedMixes = JSON.parse(localStorage.getItem('feedMixes') || '[]');
            savedMixes.push(mixData);
            localStorage.setItem('feedMixes', JSON.stringify(savedMixes));

            alert('Campuran pakan berhasil disimpan!');
            closeFeedMixer();
        }

        function saveFeedPlan() {
            alert('Rencana pakan berhasil disimpan!');
            document.querySelector('.modal-backdrop').remove();
        }

        function selectCostOption(option) {
            alert(`Opsi ${option} berhasil dipilih!`);
            document.querySelector('.modal-backdrop').remove();
        }

        function createSchedule() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-backdrop';
            modal.innerHTML = `
                <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-lg bg-white modal-content">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Buat Jadwal Baru</h3>
                        <button onclick="this.closest('.modal-backdrop').remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Pemberian</label>
                            <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pakan</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded">
                                <option>Hijauan Pagi</option>
                                <option>Konsentrat</option>
                                <option>Mix Pakan</option>
                                <option>Mineral/Vitamin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (kg)</label>
                            <input type="number" min="1" max="50" class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded" rows="3"></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="saveSchedule()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Simpan
                            </button>
                            <button type="button" onclick="this.closest('.modal-backdrop').remove()" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function saveSchedule() {
            alert('Jadwal berhasil disimpan!');
            document.querySelector('.modal-backdrop').remove();
        }

        function filterFeeds() {
            const category = document.getElementById('categoryFilter').value;
            const feedItems = document.querySelectorAll('.feed-item');

            feedItems.forEach(item => {
                if (!category || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function viewFeedDetail(feedName) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-backdrop';
            modal.innerHTML = `
                <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white modal-content">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Detail Pakan: ${feedName}</h3>
                        <button onclick="this.closest('.modal-backdrop').remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-3 rounded">
                                <h4 class="font-medium text-gray-900 mb-2">Komposisi Nutrisi</h4>
                                <div class="space-y-1 text-sm">
                                    <div>Protein Kasar: ${getFeedProtein(feedName)}%</div>
                                    <div>Energi (TDN): ${getFeedEnergy(feedName)}%</div>
                                    <div>Serat Kasar: ${getFeedFiber(feedName)}%</div>
                                    <div>Abu: 8-12%</div>
                                    <div>Kadar Air: 10-15%</div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <h4 class="font-medium text-gray-900 mb-2">Informasi Harga</h4>
                                <div class="space-y-1 text-sm">
                                    <div>Harga: Rp ${getFeedPrice(feedName).toLocaleString()}/kg</div>
                                    <div>Trend: Stabil</div>
                                    <div>Musim: Tersedia</div>
                                    <div>Kualitas: Premium</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-3 rounded">
                            <h4 class="font-medium text-blue-900 mb-2">Rekomendasi Penggunaan</h4>
                            <p class="text-sm text-blue-700">
                                Cocok untuk sapi perah produktif. Berikan 2-3 kali sehari dengan dosis maksimal 8kg/hari. 
                                Kombinasikan dengan hijauan untuk hasil optimal.
                            </p>
                        </div>
                        <button onclick="addToMix('${feedName}')" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Tambah ke Mix
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-calculate when page loads with default values
            setTimeout(() => {
                if (document.getElementById('animalType').value || document.getElementById('animalWeight')
                    .value) {
                    updateNutritionNeeds();
                }
            }, 500);
        });
    </script>
@endpush
