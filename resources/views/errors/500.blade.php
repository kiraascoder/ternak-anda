<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server | Pakeng-Ternak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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

        .gradient-bg {
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
        }

        .error-code {
            background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bounce-in {
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .slide-up {
            animation: slideUp 1s ease-out 0.3s both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glitch {
            animation: glitch 2s ease-in-out infinite;
        }

        @keyframes glitch {

            0%,
            100% {
                transform: translate(0);
            }

            20% {
                transform: translate(-2px, 2px);
            }

            40% {
                transform: translate(-2px, -2px);
            }

            60% {
                transform: translate(2px, 2px);
            }

            80% {
                transform: translate(2px, -2px);
            }
        }

        .spin-reverse {
            animation: spin-reverse 3s linear infinite;
        }

        @keyframes spin-reverse {
            from {
                transform: rotate(360deg);
            }

            to {
                transform: rotate(0deg);
            }
        }

        .fade-in-delay {
            animation: fadeInDelay 1s ease-out 1s both;
        }

        @keyframes fadeInDelay {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Server Error Icon with Animation -->
        <div class="floating-animation mb-8">
            <div
                class="w-32 h-32 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                <svg class="w-16 h-16 text-white glitch" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Error Content -->
        <div class="text-white">
            <!-- Error Code -->
            <h1 class="text-8xl md:text-9xl font-black mb-4 bounce-in error-code">
                500
            </h1>

            <!-- Main Message -->
            <h2 class="text-3xl md:text-4xl font-bold mb-4 slide-up">
                Kesalahan Server Internal
            </h2>

            <!-- Description -->
            <p class="text-xl md:text-2xl text-white text-opacity-90 mb-8 slide-up">
                Terjadi kesalahan pada server kami. Tim teknis sedang memperbaikinya
            </p>


            <!-- Action Buttons -->


            <!-- Back to Home -->
            <div class="mt-6 fade-in-delay">
                <a href="/"
                    class="text-white text-opacity-70 hover:text-opacity-100 transition-all duration-300 inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full floating-animation glitch">
        </div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-white bg-opacity-5 rounded-full floating-animation"
            style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 right-5 w-12 h-12 bg-white bg-opacity-5 rounded-full floating-animation"
            style="animation-delay: 4s;"></div>
        <div class="absolute bottom-1/3 left-5 w-8 h-8 bg-white bg-opacity-10 rounded-full floating-animation glitch"
            style="animation-delay: 1s;"></div>
    </div>

    <script>
        // Countdown timer
        let countdown = 15 * 60; // 15 minutes in seconds
        const countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            const minutes = Math.floor(countdown / 60);
            const seconds = countdown % 60;
            countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (countdown > 0) {
                countdown--;
            } else {
                countdownElement.textContent = 'Memuat ulang...';
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);

        // Check server status function
        function checkServerStatus() {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;

            button.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Mengecek...</span>
                </div>
            `;

            // Simulate checking server status
            setTimeout(() => {
                button.innerHTML = originalText;
                alert('Server masih dalam perbaikan. Silakan coba lagi dalam beberapa menit.');
            }, 2000);
        }

        // Auto refresh every 5 minutes
        setTimeout(() => {
            window.location.reload();
        }, 5 * 60 * 1000);
    </script>
</body>

</html>
