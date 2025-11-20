<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Pertamina MC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-pertamina {
            background-image: url('{{ asset('image/login_pertamina.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            min-height: 100vh;
        }
        
        .bg-pertamina::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(220, 38, 38, 0.4) 0%,
                rgba(185, 28, 28, 0.5) 50%,
                rgba(153, 27, 27, 0.6) 100%
            );
            z-index: 1;
        }
        
        .content-overlay {
            position: relative;
            z-index: 2;
        }
        
        .logo-container {
            animation: float 6s ease-in-out infinite;
            transition: all 0.3s ease;
        }
        
        .logo-container:hover {
            transform: scale(1.05);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .glass-card {
            background: linear-gradient(
                145deg, 
                rgba(255, 255, 255, 0.25) 0%, 
                rgba(255, 245, 245, 0.15) 30%,
                rgba(254, 242, 242, 0.20) 70%,
                rgba(255, 255, 255, 0.18) 100%
            );
            backdrop-filter: blur(30px) saturate(1.3);
            border: 2px solid;
            border-image: linear-gradient(
                145deg, 
                rgba(220, 38, 38, 0.3) 0%,
                rgba(255, 255, 255, 0.4) 30%,
                rgba(220, 38, 38, 0.2) 70%,
                rgba(255, 255, 255, 0.3) 100%
            ) 1;
            box-shadow: 
                0 30px 70px rgba(220, 38, 38, 0.15),
                0 20px 40px rgba(0, 0, 0, 0.20),
                0 10px 20px rgba(185, 28, 28, 0.10),
                inset 0 2px 0 rgba(255, 255, 255, 0.5),
                inset 0 -2px 0 rgba(220, 38, 38, 0.1),
                inset 2px 0 0 rgba(255, 255, 255, 0.2),
                inset -2px 0 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(255, 255, 255, 0.5);
            box-shadow: 
                0 6px 20px rgba(0, 0, 0, 0.1),
                0 2px 8px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            font-weight: 500;
            letter-spacing: 0.025em;
        }
        
        .input-field:focus {
            background: rgba(255, 255, 255, 0.99);
            border-color: #dc2626;
            box-shadow: 
                0 12px 30px rgba(220, 38, 38, 0.25),
                0 6px 20px rgba(0, 0, 0, 0.1),
                0 2px 8px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.95),
                0 0 0 4px rgba(220, 38, 38, 0.08);
            transform: translateY(-2px) scale(1.01);
        }
        
        .input-field::placeholder {
            color: rgba(107, 114, 128, 0.8);
            font-weight: 500;
        }
        
        .company-text {
            text-align: center;
            animation: fadeInUp 1.2s ease-out 0.5s both;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 50%, #ffffff 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 
                0 2px 4px rgba(0, 0, 0, 0.3),
                0 4px 8px rgba(0, 0, 0, 0.2);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }
        
        .text-gradient-enhanced {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 30%, #e9ecef 70%, #ffffff 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 
                0 3px 6px rgba(0, 0, 0, 0.4),
                0 6px 12px rgba(0, 0, 0, 0.3);
            filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.4));
            letter-spacing: 0.1em;
        }
        
        .text-shadow-enhanced {
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 
                0 2px 4px rgba(0, 0, 0, 0.5),
                0 4px 8px rgba(0, 0, 0, 0.3),
                0 1px 0 rgba(255, 255, 255, 0.1);
            letter-spacing: 0.05em;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-gradient:hover::before {
            left: 100%;
        }
        
        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(220, 38, 38, 0.4);
        }
        
        .demo-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid rgba(220, 38, 38, 0.1);
            transition: all 0.3s ease;
        }
        
        .demo-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: rgba(220, 38, 38, 0.3);
        }
        
        .fade-in {
            animation: fadeIn 1s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .title-gradient {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 30%, #4a5568 70%, #1a202c 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-weight: 700;
            letter-spacing: 0.05em;
            position: relative;
        }
        
        .professional-header {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .professional-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #dc2626, #ef4444, #dc2626);
            border-radius: 2px;
            margin: 1rem auto;
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
        }
        
        .professional-label {
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            position: relative;
        }
        
        .professional-label::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: linear-gradient(90deg, #dc2626, #ef4444);
            transition: width 0.3s ease;
        }
        
        .professional-label:hover::after {
            width: 30px;
        }
        
        .professional-button {
            position: relative;
            overflow: hidden;
            border: 2px solid rgba(220, 38, 38, 0.3);
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #dc2626 100%);
            box-shadow: 
                0 8px 20px rgba(220, 38, 38, 0.3),
                0 4px 10px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        
        .professional-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .professional-button:hover::before {
            left: 100%;
        }
        
        .professional-button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 30px rgba(220, 38, 38, 0.4),
                0 6px 15px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
        
        .professional-button:active {
            transform: translateY(0);
        }
        
        .subtitle-text {
            color: #6b7280;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="min-h-screen bg-pertamina flex items-center justify-center">
    <div class="content-overlay flex flex-col items-center px-4 py-8">
        <!-- Logo Section -->
        <div class="mb-8 fade-in text-center">
            <div class="logo-container w-28 h-28 bg-white rounded-full p-4 shadow-2xl border-4 border-white mx-auto mb-4">
                <img 
                    src="{{ asset('image/pertamina_logo.jpg') }}" 
                    alt="Pertamina Logo" 
                    class="w-full h-full object-contain rounded-full"
                >
            </div>
            <div class="company-text">
                <h2 class="text-white text-2xl font-bold tracking-wide leading-tight">
                    <span class="block text-white text-shadow-enhanced">PT. PERTAMINA</span>
                    <span class="block text-base font-semibold mt-2 text-shadow-enhanced">
                        Maintenance & Construction
                    </span>
                </h2>
            </div>
        </div>
        
        <!-- Login Card -->
        <div class="relative">
            <div class="professional-glass-enhancement"></div>
            <div class="glass-card rounded-2xl p-12 w-full max-w-2xl slide-up">
                <div class="professional-header text-center">
                    <h1 class="text-4xl text-white font-bold mb-4 text-shadow-enhanced">Login</h1>
                <p class="text-white-600 text-sm font-bold tracking-wide mb-4">Secure Authentication System</p>
                <div class="professional-divider"></div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <ul class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6 px-4">
                @csrf
                
                <div>
                    <label for="username" class="block text-sm font-semibold text-white mb-3 tracking-wide uppercase professional-label text-shadow-enhanced">Username</label>
                    <div class="relative">
                        <input id="username" name="username" type="text" required maxlength="20"
                               value="{{ old('username') }}"
                               class="input-field w-full px-8 py-4 rounded-xl focus:outline-none text-gray-800 placeholder-gray-500"
                               placeholder="Enter your username">
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-white mb-3 tracking-wide uppercase professional-label text-shadow-enhanced">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required maxlength="8"
                               class="input-field w-full px-8 py-4 rounded-xl focus:outline-none text-gray-800 placeholder-gray-500"
                               placeholder="Enter your password">
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-8">
                    <button type="submit" 
                            class="professional-button btn-gradient text-white font-semibold py-4 px-12 rounded-xl text-sm tracking-wide uppercase shadow-lg hover:shadow-xl transition-all duration-300 relative z-10">
                        <span class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Secure Login
                        </span>
                    </button>
                </div>
            </form>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-center fade-in">
            <p class="text-white text-sm font-medium opacity-90">
                &copy; {{ date('Y') }} PT. Pertamina Maintenance & Construction
            </p>
            <p class="text-red-100 text-xs mt-1 opacity-80">
                Secure Database Management System
            </p>
        </div>
    </div>
</body>
</html>
