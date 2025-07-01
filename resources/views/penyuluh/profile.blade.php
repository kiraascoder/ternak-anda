@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile Pengguna')
@section('page-description', 'Kelola informasi akun dan pengaturan Anda')

@push('styles')
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .tab-button:not(.active) {
            color: #6b7280;
            background: white;
            border-color: #e5e7eb;
        }

        .tab-button:not(.active):hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .stats-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
        }

        .activity-item {
            position: relative;
            padding-left: 3rem;
        }

        .activity-item::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .activity-item:last-child::before {
            background: linear-gradient(to bottom, #e5e7eb 0%, transparent 100%);
        }

        .activity-dot {
            position: absolute;
            left: 0.5rem;
            top: 1rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #e5e7eb;
        }

        .activity-dot.success {
            background-color: #10b981;
        }

        .activity-dot.warning {
            background-color: #f59e0b;
        }

        .activity-dot.info {
            background-color: #3b82f6;
        }

        .activity-dot.error {
            background-color: #ef4444;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: #667eea;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(24px);
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

        .farm-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            background: #ecfdf5;
            color: #065f46;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            transition: width 0.5s ease;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 16px;
            height: 16px;
            background: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: white;
            font-weight: bold;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        .strength-weak {
            background: #ef4444;
            width: 25%;
        }

        .strength-fair {
            background: #f59e0b;
            width: 50%;
        }

        .strength-good {
            background: #3b82f6;
            width: 75%;
        }

        .strength-strong {
            background: #10b981;
            width: 100%;
        }

        .avatar-upload {
            position: relative;
            display: inline-block;
        }

        .avatar-upload-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .avatar-upload:hover .avatar-upload-overlay {
            opacity: 1;
        }

        .verification-badge {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                <div class="avatar-upload">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.io/api/?name=' . urlencode(auth()->user()->name ?? 'User') . '&background=667eea&color=ffffff&size=120' }}"
                        alt="Profile Avatar" class="profile-avatar" id="profileAvatar">
                    <div class="avatar-upload-overlay">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <input type="file" id="avatarUpload" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                    <div class="verification-badge">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-bold">{{ auth()->user()->name ?? 'John Doe' }}</h1>
                    <p class="text-blue-100 mb-2">{{ auth()->user()->email ?? 'john.doe@example.com' }}</p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                        <span class="farm-badge">
                            🐄 Peternak
                        </span>
                        <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-full">
                            Bergabung {{ auth()->user()->created_at ?? now()->subYears(2)->format('M Y') }}
                        </span>
                        <span class="text-xs bg-green-500 px-2 py-1 rounded-full">
                            ● Online
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold">25</div>
                        <div class="text-sm text-blue-100">Ternak</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">18</div>
                        <div class="text-sm text-blue-100">Laporan</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">5</div>
                        <div class="text-sm text-blue-100">Konsultasi</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="showTab('personal')" id="tab-personal" class="tab-button active">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Pribadi
                            </button>
                            <button onclick="showTab('farm')" id="tab-farm" class="tab-button">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Info Peternakan
                            </button>
                            <button onclick="showTab('security')" id="tab-security" class="tab-button">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Keamanan
                            </button>
                            <button onclick="showTab('notifications')" id="tab-notifications" class="tab-button">
                                <div class="relative">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-5 5v-5zM5 17h5l-5 5v-5zM9 7h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2z">
                                        </path>
                                    </svg>
                                    <span class="notification-badge">3</span>
                                </div>
                                Notifikasi
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <div id="content-personal" class="tab-content active">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Pribadi</h3>

                            <form id="personalForm" action="" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Depan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="firstName" name="first_name" required
                                            value="{{ explode(' ', auth()->user()->name ?? 'John Doe')[0] }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Belakang <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="lastName" name="last_name" required
                                            value="{{ implode(' ', array_slice(explode(' ', auth()->user()->name ?? 'John Doe'), 1)) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" required
                                            value="{{ auth()->user()->email ?? 'john.doe@example.com' }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nomor Telepon
                                        </label>
                                        <input type="tel" id="phone" name="phone"
                                            value="{{ auth()->user()->phone ?? '+62 812-3456-7890' }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                            placeholder="+62 812-3456-7890">
                                    </div>

                                    <div>
                                        <label for="birthDate" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tanggal Lahir
                                        </label>
                                        <input type="date" id="birthDate" name="birth_date"
                                            value="{{ auth()->user()->birth_date ?? '1985-05-15' }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jenis Kelamin
                                        </label>
                                        <select id="gender" name="gender"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male"
                                                {{ (auth()->user()->gender ?? 'male') === 'male' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="female"
                                                {{ (auth()->user()->gender ?? '') === 'female' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                            Alamat Lengkap
                                        </label>
                                        <textarea id="address" name="address" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                            placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota">{{ auth()->user()->address ?? 'Jl. Merdeka No. 123, Kelurahan Sejahtera, Kecamatan Makmur, Kota Sukses, Jawa Barat 12345' }}</textarea>
                                    </div>

                                    <div>
                                        <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                                            Provinsi
                                        </label>
                                        <select id="province" name="province"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Provinsi</option>
                                            <option value="jawa-barat" selected>Jawa Barat</option>
                                            <option value="jawa-tengah">Jawa Tengah</option>
                                            <option value="jawa-timur">Jawa Timur</option>
                                            <option value="dki-jakarta">DKI Jakarta</option>
                                            <option value="banten">Banten</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kota/Kabupaten
                                        </label>
                                        <select id="city" name="city"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            <option value="bandung" selected>Bandung</option>
                                            <option value="bogor">Bogor</option>
                                            <option value="depok">Depok</option>
                                            <option value="bekasi">Bekasi</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                                            Bio/Deskripsi
                                        </label>
                                        <textarea id="bio" name="bio" rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                            placeholder="Ceritakan sedikit tentang Anda dan pengalaman beternak...">{{ auth()->user()->bio ?? 'Peternak berpengalaman dengan fokus pada peternakan sapi dan kambing modern. Telah berkecimpung di dunia peternakan selama lebih dari 10 tahun.' }}</textarea>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                                    <button type="button" onclick="resetPersonalForm()"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                                        Reset
                                    </button>
                                    <button type="submit" id="personalSubmitBtn"
                                        class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
                                        <span id="personalSubmitText">Simpan Perubahan</span>
                                        <svg id="personalSubmitLoading"
                                            class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div id="content-farm" class="tab-content">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Peternakan</h3>

                            <form id="farmForm" action="" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="farmName" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Peternakan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="farmName" name="farm_name" required
                                            value="Peternakan Makmur Sejahtera"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                            placeholder="Contoh: Peternakan Makmur Sejahtera">
                                    </div>

                                    <div>
                                        <label for="farmType" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jenis Peternakan <span class="text-red-500">*</span>
                                        </label>
                                        <select id="farmType" name="farm_type" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Jenis Peternakan</option>
                                            <option value="sapi" selected>Peternakan Sapi</option>
                                            <option value="kambing">Peternakan Kambing</option>
                                            <option value="domba">Peternakan Domba</option>
                                            <option value="campuran">Peternakan Campuran</option>
                                            <option value="ayam">Peternakan Ayam</option>
                                            <option value="bebek">Peternakan Bebek</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="farmScale" class="block text-sm font-medium text-gray-700 mb-2">
                                            Skala Peternakan
                                        </label>
                                        <select id="farmScale" name="farm_scale"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Skala</option>
                                            <option value="kecil">Kecil (1-10 ekor)</option>
                                            <option value="menengah" selected>Menengah (11-50 ekor)</option>
                                            <option value="besar">Besar (51-200 ekor)</option>
                                            <option value="komersial">Komersial (200+ ekor)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="establishedYear" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tahun Berdiri
                                        </label>
                                        <input type="number" id="establishedYear" name="established_year"
                                            min="1900" max="{{ date('Y') }}" value="2015"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                            placeholder="{{ date('Y') }}">
                                    </div>

                                    <div>
                                        <label for="totalAnimals" class="block text-sm font-medium text-gray-700 mb-2">
                                            Total Ternak Saat Ini
                                        </label>
                                        <input type="number" id="totalAnimals" name="total_animals" min="0"
                                            value="25"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                            placeholder="25">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="farmAddress" class="block text-sm font-medium text-gray-700 mb-2">
                                            Alamat Peternakan
                                        </label>
                                        <textarea id="farmAddress" name="farm_address" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                            placeholder="Alamat lengkap lokasi peternakan...">Jl. Raya Peternakan No. 45, Desa Makmur, Kecamatan Sejahtera, Kabupaten Subang, Jawa Barat 41200</textarea>
                                    </div>

                                    <div>
                                        <label for="landArea" class="block text-sm font-medium text-gray-700 mb-2">
                                            Luas Lahan (m²)
                                        </label>
                                        <input type="number" id="landArea" name="land_area" min="0"
                                            value="5000"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                            placeholder="5000">
                                    </div>

                                    <div>
                                        <label for="certification" class="block text-sm font-medium text-gray-700 mb-2">
                                            Sertifikasi
                                        </label>
                                        <select id="certification" name="certification"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Sertifikasi</option>
                                            <option value="halal" selected>Halal</option>
                                            <option value="organic">Organik</option>
                                            <option value="iso">ISO 22000</option>
                                            <option value="haccp">HACCP</option>
                                            <option value="none">Belum Ada</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="farmDescription" class="block text-sm font-medium text-gray-700 mb-2">
                                            Deskripsi Peternakan
                                        </label>
                                        <textarea id="farmDescription" name="farm_description" rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                            placeholder="Deskripsikan peternakan Anda, fasilitas, dan keunggulan...">Peternakan modern dengan sistem kandang semi-intensif. Dilengkapi dengan fasilitas pakan otomatis, sistem ventilasi yang baik, dan area quarantine. Fokus pada produksi susu sapi berkualitas tinggi dengan standar kebersihan yang ketat.</textarea>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                                    <button type="button" onclick="resetFarmForm()"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                                        Reset
                                    </button>
                                    <button type="submit" id="farmSubmitBtn"
                                        class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
                                        <span id="farmSubmitText">Simpan Perubahan</span>
                                        <svg id="farmSubmitLoading"
                                            class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div id="content-security" class="tab-content">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Pengaturan Keamanan</h3>

                            <div class="space-y-8">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-4">Ubah Password</h4>
                                    <form id="passwordForm" action="" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="space-y-4">
                                            <div>
                                                <label for="currentPassword"
                                                    class="block text-sm font-medium text-gray-700 mb-2">
                                                    Password Saat Ini <span class="text-red-500">*</span>
                                                </label>
                                                <input type="password" id="currentPassword" name="current_password"
                                                    required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            </div>

                                            <div>
                                                <label for="newPassword"
                                                    class="block text-sm font-medium text-gray-700 mb-2">
                                                    Password Baru <span class="text-red-500">*</span>
                                                </label>
                                                <input type="password" id="newPassword" name="new_password" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                                    onkeyup="checkPasswordStrength(this.value)">
                                                <div id="passwordStrength" class="password-strength mt-2"></div>
                                                <p id="passwordStrengthText" class="text-xs text-gray-500 mt-1">Password
                                                    harus minimal 8 karakter</p>
                                            </div>

                                            <div>
                                                <label for="confirmPassword"
                                                    class="block text-sm font-medium text-gray-700 mb-2">
                                                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                                                </label>
                                                <input type="password" id="confirmPassword" name="password_confirmation"
                                                    required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            </div>

                                            <button type="submit" id="passwordSubmitBtn"
                                                class="w-full px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
                                                <span id="passwordSubmitText">Ubah Password</span>
                                                <svg id="passwordSubmitLoading"
                                                    class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-4">Two-Factor Authentication (2FA)
                                    </h4>
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p class="text-sm text-gray-700">Tambahkan lapisan keamanan ekstra ke akun Anda
                                            </p>
                                            <p class="text-xs text-gray-500">Status: <span
                                                    class="text-red-600 font-medium">Tidak Aktif</span></p>
                                        </div>
                                        <label class="toggle-switch">
                                            <input type="checkbox" id="enable2FA" onchange="toggle2FA()">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <button onclick="setup2FA()" id="setup2FABtn"
                                        class="disabled:opacity-50 disabled:cursor-not-allowed px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors"
                                        disabled>
                                        Setup 2FA
                                    </button>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-4">Sesi Login Aktif</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                            <div class="flex items-center space-x-3">
                                                <div class="p-2 bg-green-100 rounded-full">
                                                    <svg class="w-4 h-4 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">Chrome - Windows</p>
                                                    <p class="text-sm text-gray-600">IP: 192.168.1.100 • Jakarta, Indonesia
                                                    </p>
                                                    <p class="text-xs text-gray-500">Sesi saat ini • Login: 2 jam yang lalu
                                                    </p>
                                                </div>
                                            </div>
                                            <span
                                                class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Aktif</span>
                                        </div>

                                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                            <div class="flex items-center space-x-3">
                                                <div class="p-2 bg-blue-100 rounded-full">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">Safari - iPhone</p>
                                                    <p class="text-sm text-gray-600">IP: 192.168.1.101 • Jakarta, Indonesia
                                                    </p>
                                                    <p class="text-xs text-gray-500">Login: 1 hari yang lalu</p>
                                                </div>
                                            </div>
                                            <button
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">Logout</button>
                                        </div>
                                    </div>

                                    <button onclick="logoutAllSessions()"
                                        class="mt-4 w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                        Logout Semua Sesi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="content-notifications" class="tab-content">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Pengaturan Notifikasi</h3>

                            <div class="space-y-6">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-4">Notifikasi Email</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">Laporan Kesehatan</p>
                                                <p class="text-sm text-gray-600">Terima notifikasi untuk laporan kesehatan
                                                    baru</p>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">Konsultasi</p>
                                                <p class="text-sm text-gray-600">Notifikasi pesan baru dari dokter hewan
                                                </p>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">Reminder Follow-up</p>
                                                <p class="text-sm text-gray-600">Pengingat jadwal pemeriksaan ternak</p>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">Newsletter</p>
                                                <p class="text-sm text-gray-600">Tips dan artikel terbaru tentang
                                                    peternakan</p>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-4">Notifikasi Push</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">Alert Kesehatan</p>
                                                <p class="text-sm text-gray-600">Notifikasi darurat untuk masalah kesehatan
                                                    ternak</p>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">Pesan Konsultasi</p>
                                                <p class="text-sm text-gray-600">Notifikasi real-time untuk chat konsultasi
                                                </p>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">Update System</p>
                                                <p class="text-sm text-gray-600">Informasi pembaruan fitur dan sistem</p>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-4">Jadwal Notifikasi</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Mulai</label>
                                            <input type="time" value="07:00"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Berakhir</label>
                                            <input type="time" value="21:00"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Notifikasi akan dikirim hanya dalam rentang waktu
                                        ini</p>
                                </div>

                                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                                    <button type="button"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                                        Reset Default
                                    </button>
                                    <button type="submit"
                                        class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
                                        Simpan Pengaturan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Aktivitas</h3>
                    <div class="space-y-4">
                        <div class="stats-card rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Profile Completion</span>
                                <span class="text-sm font-bold text-gray-900">85%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 85%"></div>
                            </div>
                        </div>

                        <div class="stats-card rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Aktivitas Bulanan</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Laporan Kesehatan</span>
                                    <span class="font-medium">8</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Konsultasi</span>
                                    <span class="font-medium">3</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Data Ternak Baru</span>
                                    <span class="font-medium">2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-4">
                        <div class="activity-item">
                            <div class="activity-dot success"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Laporan kesehatan ditambahkan</p>
                                <p class="text-xs text-gray-500">Sapi Limosin #001 - 2 jam yang lalu</p>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-dot info"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Konsultasi dimulai</p>
                                <p class="text-xs text-gray-500">Dr. Budi Santoso - 5 jam yang lalu</p>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-dot warning"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Reminder follow-up</p>
                                <p class="text-xs text-gray-500">Kambing Boer #003 - 1 hari yang lalu</p>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-dot success"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Profile diperbarui</p>
                                <p class="text-xs text-gray-500">Informasi peternakan - 2 hari yang lalu</p>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-dot info"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Data ternak baru</p>
                                <p class="text-xs text-gray-500">Domba Garut #004 - 3 hari yang lalu</p>
                            </div>
                        </div>
                    </div>

                    <button class="w-full mt-4 px-4 py-2 text-sm text-primary hover:text-secondary font-medium">
                        Lihat Semua Aktivitas
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button onclick="openLaporanModal()"
                            class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="p-2 bg-green-100 rounded-full mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Tambah Laporan</p>
                                <p class="text-xs text-gray-500">Buat laporan kesehatan baru</p>
                            </div>
                        </button>

                        <button onclick="openKonsultasiModal()"
                            class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="p-2 bg-blue-100 rounded-full mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Konsultasi Baru</p>
                                <p class="text-xs text-gray-500">Chat dengan dokter hewan</p>
                            </div>
                        </button>

                        <button onclick="openTernakModal()"
                            class="w-full flex items-center p-3 text-left hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="p-2 bg-purple-100 rounded-full mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Tambah Ternak</p>
                                <p class="text-xs text-gray-500">Daftarkan ternak baru</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2FA Setup -->
    <div id="setupModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white modal-content">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Setup Two-Factor Authentication</h3>

                <div class="mb-6">
                    <div class="w-32 h-32 bg-gray-200 rounded-lg mx-auto mb-4 flex items-center justify-center">
                        <span class="text-gray-500">QR Code</span>
                    </div>
                    <p class="text-sm text-gray-600">Scan QR code ini dengan aplikasi authenticator Anda</p>
                </div>

                <div class="mb-6">
                    <label for="authCode" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Verifikasi
                    </label>
                    <input type="text" id="authCode" maxlength="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-center text-lg"
                        placeholder="123456">
                </div>

                <div class="flex space-x-3">
                    <button onclick="closeSetupModal()"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button onclick="verify2FA()"
                        class="flex-1 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
                        Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            document.getElementById(`tab-${tabName}`).classList.add('active');
            document.getElementById(`content-${tabName}`).classList.add('active');
        }

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileAvatar').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');

            let strength = 0;
            let text = '';

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthBar.className = 'password-strength';

            switch (strength) {
                case 0:
                case 1:
                    strengthBar.classList.add('strength-weak');
                    text = 'Password sangat lemah';
                    break;
                case 2:
                    strengthBar.classList.add('strength-fair');
                    text = 'Password lemah';
                    break;
                case 3:
                    strengthBar.classList.add('strength-good');
                    text = 'Password cukup kuat';
                    break;
                case 4:
                case 5:
                    strengthBar.classList.add('strength-strong');
                    text = 'Password sangat kuat';
                    break;
            }

            strengthText.textContent = text;
        }

        function toggle2FA() {
            const toggle = document.getElementById('enable2FA');
            const setupBtn = document.getElementById('setup2FABtn');

            setupBtn.disabled = !toggle.checked;
        }

        function setup2FA() {
            document.getElementById('setupModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSetupModal() {
            document.getElementById('setupModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function verify2FA() {
            const code = document.getElementById('authCode').value;
            if (code.length === 6) {
                closeSetupModal();
                alert('2FA berhasil diaktifkan!');
            } else {
                alert('Masukkan kode 6 digit yang valid');
            }
        }

        function logoutAllSessions() {
            if (confirm('Yakin ingin logout dari semua sesi? Anda perlu login ulang di semua perangkat.')) {
                alert('Semua sesi telah di-logout');
            }
        }

        function resetPersonalForm() {
            document.getElementById('personalForm').reset();
        }

        function resetFarmForm() {
            document.getElementById('farmForm').reset();
        }

        function openLaporanModal() {
            alert('Redirect ke halaman tambah laporan kesehatan');
        }

        function openKonsultasiModal() {
            alert('Redirect ke halaman konsultasi baru');
        }

        function openTernakModal() {
            alert('Redirect ke halaman tambah ternak');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const avatarUpload = document.querySelector('.avatar-upload-overlay');
            if (avatarUpload) {
                avatarUpload.addEventListener('click', function() {
                    document.getElementById('avatarUpload').click();
                });
            }

            const personalForm = document.getElementById('personalForm');
            const farmForm = document.getElementById('farmForm');
            const passwordForm = document.getElementById('passwordForm');

            if (personalForm) {
                personalForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleFormSubmit(this, 'personal');
                });
            }

            if (farmForm) {
                farmForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleFormSubmit(this, 'farm');
                });
            }

            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const newPassword = document.getElementById('newPassword').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;

                    if (newPassword !== confirmPassword) {
                        alert('Password baru dan konfirmasi password tidak sama');
                        return;
                    }

                    if (newPassword.length < 8) {
                        alert('Password minimal 8 karakter');
                        return;
                    }

                    handleFormSubmit(this, 'password');
                });
            }

            const setupModal = document.getElementById('setupModal');
            if (setupModal) {
                setupModal.addEventListener('click', function(e) {
                    if (e.target === setupModal) {
                        closeSetupModal();
                    }
                });
            }
        });

        function handleFormSubmit(form, type) {
            const submitBtn = document.getElementById(`${type}SubmitBtn`);
            const submitText = document.getElementById(`${type}SubmitText`);
            const submitLoading = document.getElementById(`${type}SubmitLoading`);

            submitBtn.disabled = true;
            submitText.textContent = 'Menyimpan...';
            submitLoading.classList.remove('hidden');

            setTimeout(() => {
                submitBtn.disabled = false;
                submitText.textContent = type === 'password' ? 'Ubah Password' : 'Simpan Perubahan';
                submitLoading.classList.add('hidden');

                const message = type === 'password' ? 'Password berhasil diubah!' :
                    type === 'farm' ? 'Informasi peternakan berhasil disimpan!' :
                    'Informasi pribadi berhasil disimpan!';
                alert(message);
            }, 2000);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const setupModal = document.getElementById('setupModal');
                if (setupModal && !setupModal.classList.contains('hidden')) {
                    closeSetupModal();
                }
            }
        });
    </script>
@endpush
