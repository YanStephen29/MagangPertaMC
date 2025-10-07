<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pertamina Maintenance dan Construction') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('image/pertamina_logo.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS (if not included in app.css) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Privilege Check CSS -->
        <link rel="stylesheet" href="{{ asset('css/privilege-check.css') }}">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <!-- User Privileges for JavaScript -->
        <script>
            window.userPrivileges = @json(auth('admin')->user() ? auth('admin')->user()->privilege ?? [] : []);
            window.userRole = @json(auth('admin')->user() ? auth('admin')->user()->role : null);
        </script>
    </head>
    <body class="font-sans antialiased overflow-x-hidden">
        <div class="min-h-screen bg-gradient-to-br from-gray-50 via-red-50 to-blue-50 overflow-x-hidden pt-14">
            @include('layouts.navigation')

            <!-- Flash Messages - Below navbar -->
            @if(session('success') || $errors->any())
                <div class="w-full">
                    @if(session('success'))
                        <div id="success-notification" class="w-full bg-green-100 border-b border-green-400 text-green-700 px-4 py-3 shadow-md">
                            <div class="max-w-7xl mx-auto flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ session('success') }}
                                </div>
                                <button onclick="closeNotification('success-notification')" class="text-green-500 hover:text-green-700 font-bold text-xl">&times;</button>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div id="error-notification" class="w-full bg-red-100 border-b border-red-400 text-red-700 px-4 py-3 shadow-md">
                            <div class="max-w-7xl mx-auto flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <ul class="list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button onclick="closeNotification('error-notification')" class="text-red-500 hover:text-red-700 font-bold text-xl ml-4">&times;</button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow border-b border-gray-200">
                    <div class="w-full py-4 px-3 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pb-8">
                {{ $slot }}
            </main>
        </div>

        <!-- Auto-hide Notifications Script -->
        <script>
            function closeNotification(notificationId) {
                const notification = document.getElementById(notificationId);
                if (notification) {
                    notification.style.transition = 'all 0.3s ease-out';
                    notification.style.transform = 'translateY(-100%)';
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }
            }

            // Auto-hide notifications after 4 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const notifications = document.querySelectorAll('[id$="-notification"]');
                notifications.forEach(function(notification) {
                    setTimeout(function() {
                        closeNotification(notification.id);
                    }, 4000); // 4 seconds
                });
            });
        </script>
        
        <!-- Privilege Check Script -->
        <script src="{{ asset('js/privilege-check.js') }}"></script>
    </body>
</html>
