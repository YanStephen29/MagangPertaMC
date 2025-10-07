<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pertamina Maintenance & Construction</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Loading spinner animation */
        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #dc2626; /* Pertamina red */
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Logo pulse animation */
        .logo-pulse {
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Fade in animation */
        .fade-in {
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Loading text animation */
        .loading-text {
            animation: loadingDots 1.5s infinite;
        }

        @keyframes loadingDots {
            0%, 20% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* Background gradient */
        .bg-gradient-pertamina {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 50%, #7f1d1d 100%);
        }
    </style>
</head>
<body class="bg-gradient-pertamina min-h-screen flex flex-col items-center justify-center">
    <!-- Main Container -->
    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        <!-- Logo Container -->
        <div class="logo-pulse fade-in mb-8">
            <div class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 bg-white rounded-full p-4 shadow-2xl">
                <img 
                    src="{{ asset('image/pertamina_logo.jpg') }}" 
                    alt="Pertamina Logo" 
                    class="w-full h-full object-contain rounded-full"
                >
            </div>
        </div>

        <!-- Company Title -->
        <div class="text-center mb-8 fade-in">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2">
                PT. PERTAMINA
            </h1>
            <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-red-100 mb-4">
                MAINTENANCE & CONSTRUCTION
            </h2>
            <div class="w-24 h-1 bg-white mx-auto rounded"></div>
        </div>

        <!-- Loading Spinner -->
        <div class="flex flex-col items-center mb-8 fade-in">
            <div class="spinner mb-4"></div>
            <p class="text-white text-sm sm:text-base font-medium loading-text">
                Loading System...
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="w-64 sm:w-80 bg-red-300 rounded-full h-2 mb-8 fade-in">
            <div class="bg-white h-2 rounded-full progress-bar" style="width: 0%"></div>
        </div>

        <!-- Footer -->
        <div class="text-center text-red-100 text-xs sm:text-sm fade-in">
            <p>&copy; {{ date('Y') }} PT. Pertamina Maintenance & Construction</p>
            <p class="mt-1">Web Management Data System</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.querySelector('.progress-bar');
            const loadingText = document.querySelector('.loading-text');
            
            let progress = 0;
            const loadingSteps = [
                'Initializing System...',
                'Loading Authentication...',
                'Connecting Database...',
                'Preparing Interface...',
                'Almost Ready...',
                'Complete!'
            ];
            
            const interval = setInterval(() => {
                progress += Math.random() * 20 + 10; // Random progress increment
                
                if (progress >= 100) {
                    progress = 100;
                    progressBar.style.width = '100%';
                    loadingText.textContent = loadingSteps[5];
                    
                    // Redirect to login after completion
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.login") }}';
                    }, 1000);
                    
                    clearInterval(interval);
                } else {
                    progressBar.style.width = progress + '%';
                    const stepIndex = Math.min(Math.floor(progress / 20), loadingSteps.length - 2);
                    loadingText.textContent = loadingSteps[stepIndex];
                }
            }, 300); // Update every 300ms
        });
    </script>
</body>
</html>