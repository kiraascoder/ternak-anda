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
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-bold">{{ $userInfo->nama }}</h1>
                    <p class="text-blue-100 mb-2">{{ $userInfo->email }}</p>
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
                        <div class="text-2xl font-bold">{{ $ternakSaya }}</div>
                        <div class="text-sm text-blue-100">Ternak</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">{{ $konsultasiSaya }}</div>
                        <div class="text-sm text-blue-100">Konsultasi</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
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
                                            value="{{ explode(' ', auth()->user()->nama ?? 'John Doe')[0] }}"
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
                                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jenis Kelamin
                                        </label>
                                        <select id="gender" name="gender"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male"
                                                {{ (auth()->user()->jenis_kelamin ?? 'male') === 'male' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="female"
                                                {{ (auth()->user()->jenis_kelamin ?? '') === 'female' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">                                
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
