<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pakeng-Ternak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16a34a',
                        secondary: '#065f46',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #16a34a 0%, #065f46 100%);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .slide-in {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="min-h-screen hero-bg flex items-center justify-center p-4">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
                <g fill="#ffffff" fill-opacity="0.1">
                    <circle cx="7" cy="7" r="2" />
                    <circle cx="20" cy="7" r="2" />
                    <circle cx="33" cy="7" r="2" />
                    <circle cx="46" cy="7" r="2" />
                    <circle cx="7" cy="20" r="2" />
                    <circle cx="20" cy="20" r="2" />
                    <circle cx="33" cy="20" r="2" />
                    <circle cx="46" cy="20" r="2" />
                    <circle cx="7" cy="33" r="2" />
                    <circle cx="20" cy="33" r="2" />
                    <circle cx="33" cy="33" r="2" />
                    <circle cx="46" cy="33" r="2" />
                    <circle cx="7" cy="46" r="2" />
                    <circle cx="20" cy="46" r="2" />
                    <circle cx="33" cy="46" r="2" />
                    <circle cx="46" cy="46" r="2" />
                </g>
            </g>
        </svg>
    </div>

    <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <!-- Left Side - Branding & Info -->
        <div class="text-white text-center lg:text-left slide-in">
            <!-- Logo -->
            <div class="flex items-center justify-center lg:justify-start mb-8">
                <div class="bg-white/20 backdrop-filter backdrop-blur-lg rounded-xl p-3">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <span class="ml-4 text-3xl font-bold">Pakeng-Ternak</span>
            </div>

            <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                Selamat Datang Kembali!
            </h1>
            <p class="text-xl text-green-100 mb-8 max-w-md mx-auto lg:mx-0">
                Masuk ke dashboard Anda dan lanjutkan mengelola peternakan dengan teknologi terdepan.
            </p>

            <!-- Features highlight -->            
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full max-w-md mx-auto">
            <div class="glass-effect rounded-2xl shadow-2xl p-8 slide-in" style="animation-delay: 0.2s;">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Masuk</h2>                    
                </div>                

                <!-- Error Messages (Laravel Style) -->
                <div id="error-message"
                    class="hidden mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="error-text">Email atau password salah</span>
                    </div>
                </div>

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    <input type="hidden" id="selected-role" name="role" value="personal">

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                placeholder="••••••••">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword()">
                                <svg id="eye-icon" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-6">                        
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-secondary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transform hover:scale-105 transition-transform">
                        <span id="login-text">Masuk</span>
                        <svg id="loading-spinner" class="hidden animate-spin -mr-1 ml-3 h-5 w-5 text-white inline"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('admin.register') }}"
                            class="text-primary hover:text-secondary font-semibold transition-colors">
                            Daftar di sini
                        </a>
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="mt-4 text-center">
                    <a href="/"
                        class="text-gray-500 hover:text-gray-700 text-sm transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 floating-animation opacity-20">
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" />
        </svg>
    </div>
    <div class="absolute bottom-20 right-20 floating-animation opacity-20" style="animation-delay: 2s;">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
        </svg>
    </div>

    <script>
        let selectedRole = 'personal';

        function selectRole(role) {
            selectedRole = role;
            document.getElementById('selected-role').value = role;

            // Update visual feedback
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.classList.remove('bg-blue-50', 'border-blue-200', 'text-blue-700');
                btn.classList.add('bg-gray-50', 'border-gray-200', 'text-gray-700');
            });

            const selectedBtn = document.getElementById(`role-${role}`);
            selectedBtn.classList.remove('bg-gray-50', 'border-gray-200', 'text-gray-700');
            selectedBtn.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-700');
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginText = document.getElementById('login-text');
            const loadingSpinner = document.getElementById('loading-spinner');
            const submitBtn = this.querySelector('button[type="submit"]');

            loginText.textContent = 'Memproses...';
            loadingSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
        });

        // Simulate error handling (replace with actual Laravel error handling)
        function showError(message) {
            const errorDiv = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');

            errorText.textContent = message;
            errorDiv.classList.remove('hidden');

            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 5000);
        }

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                showError('Email dan password harus diisi');
                return;
            }

            if (!email.includes('@')) {
                e.preventDefault();
                showError('Format email tidak valid');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                showError('Password minimal 6 karakter');
                return;
            }
        });

        // Auto-focus on first input
        window.addEventListener('load', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>

</html>
