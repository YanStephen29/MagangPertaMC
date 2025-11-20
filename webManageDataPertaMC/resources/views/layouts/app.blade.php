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
            @if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
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

                    @if(session('warning'))
                        <div id="warning-notification" class="w-full bg-yellow-100 border-b border-yellow-400 text-yellow-700 px-4 py-3 shadow-md">
                            <div class="max-w-7xl mx-auto flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ session('warning') }}
                                </div>
                                <button onclick="closeNotification('warning-notification')" class="text-yellow-500 hover:text-yellow-700 font-bold text-xl">&times;</button>
                            </div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div id="info-notification" class="w-full bg-blue-100 border-b border-blue-400 text-blue-700 px-4 py-3 shadow-md">
                            <div class="max-w-7xl mx-auto flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ session('info') }}
                                </div>
                                <button onclick="closeNotification('info-notification')" class="text-blue-500 hover:text-blue-700 font-bold text-xl">&times;</button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div id="session-error-notification" class="w-full bg-red-100 border-b border-red-400 text-red-700 px-4 py-3 shadow-md">
                            <div class="max-w-7xl mx-auto flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ session('error') }}
                                </div>
                                <button onclick="closeNotification('session-error-notification')" class="text-red-500 hover:text-red-700 font-bold text-xl">&times;</button>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div id="validation-error-notification" class="w-full bg-red-100 border-b border-red-400 text-red-700 px-4 py-3 shadow-md">
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
                                <button onclick="closeNotification('validation-error-notification')" class="text-red-500 hover:text-red-700 font-bold text-xl ml-4">&times;</button>
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
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
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

        <!-- Toast Notification System -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

        <script>
            // Toast Notification System
            function showToast(message, type = 'info', duration = 4000) {
                const toast = document.createElement('div');
                const toastId = 'toast-' + Date.now();
                toast.id = toastId;
                
                // Define styles for different types
                const styles = {
                    success: 'bg-green-100 border-green-400 text-green-700',
                    error: 'bg-red-100 border-red-400 text-red-700',
                    warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
                    info: 'bg-blue-100 border-blue-400 text-blue-700'
                };
                
                // Define icons for different types
                const icons = {
                    success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
                    error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>',
                    warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>',
                    info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>'
                };
                
                toast.className = `flex items-center justify-between p-4 border rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full opacity-0 ${styles[type] || styles.info}`;
                
                toast.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            ${icons[type] || icons.info}
                        </svg>
                        <span>${message}</span>
                    </div>
                    <button onclick="closeToast('${toastId}')" class="ml-4 text-current hover:opacity-70 font-bold text-xl">&times;</button>
                `;
                
                document.getElementById('toast-container').appendChild(toast);
                
                // Trigger entrance animation
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                }, 100);
                
                // Auto-hide after duration
                setTimeout(() => {
                    closeToast(toastId);
                }, duration);
                
                return toastId;
            }
            
            function closeToast(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }
            }
            
            // Enhanced alert function that uses toast
            function showAlert(message, type = 'info') {
                showToast(message, type);
            }
            
            // Replace browser alert with toast for better UX
            window.originalAlert = window.alert;
            window.alert = function(message) {
                showToast(message, 'info');
            };
            
            // Global functions for consistent notifications
            window.showSuccess = function(message) { showToast(message, 'success'); };
            window.showError = function(message) { showToast(message, 'error'); };
            window.showWarning = function(message) { showToast(message, 'warning'); };
            window.showInfo = function(message) { showToast(message, 'info'); };
        </script>
        
        <!-- Privilege Check Script -->
        <script src="{{ asset('js/privilege-check.js') }}"></script>
    </body>
</html>
