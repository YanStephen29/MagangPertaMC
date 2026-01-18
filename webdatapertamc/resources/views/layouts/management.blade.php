<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=yes, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>{{ config('app.name', 'Web Data Pertamc') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-navy: #2c3e50;
            --primary-gray: #34495e;
            --accent-blue: #3498db;
            --accent-green: #27ae60;
            --light-gray: #ecf0f1;
            --medium-gray: #95a5a6;
            --dark-gray: #2c3e50;
            --white: #ffffff;
            --border-light: #e9ecef;
            --shadow-light: rgba(44, 62, 80, 0.1);
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Figtree', sans-serif;
            color: var(--dark-gray);
        }

        .navbar-custom {
            background: linear-gradient(135deg, #b91021 0%, #c82333 100%);
            border-bottom: 3px solid #ffffff;
            box-shadow: 0 2px 10px rgba(220, 53, 69, 0.3);
        }

        .navbar-brand img {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .navbar-brand-text {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .navbar-logo {
            height: 40px;
            width: auto;
            margin-right: 10px;
            border-radius: 4px;
        }

        /* Responsive Navigation Styles */
        @media (max-width: 576px) {
            .navbar-logo {
                height: 35px;
                margin-right: 8px;
            }
            
            .navbar-brand-text {
                font-size: 0.95rem;
            }
            
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1rem;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                text-align: center;
            }
        }

        /* Mobile-friendly container */
        @media (max-width: 576px) {
            main .container {
                padding-left: 10px;
                padding-right: 10px;
            }
        }

        /* Active navigation state */
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            font-weight: 600;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
            color: var(--white);
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            color: var(--white);
        }

        .btn-success {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
            color: var(--white);
            font-weight: 500;
        }

        .btn-success:hover {
            background-color: #229954;
            border-color: #229954;
            color: var(--white);
        }

        .btn-outline-primary {
            color: var(--accent-blue);
            border-color: var(--accent-blue);
        }

        .btn-outline-primary:hover {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
            color: var(--white);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px var(--shadow-light);
            border-radius: 8px;
        }

        .card-header-primary {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-gray) 100%);
            color: var(--white);
            border-bottom: none;
            border-radius: 8px 8px 0 0 !important;
        }

        .card-header-secondary {
            background-color: var(--light-gray);
            color: var(--dark-gray);
            border-bottom: 2px solid var(--accent-blue);
            border-radius: 8px 8px 0 0 !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .text-primary-custom {
            color: var(--accent-blue) !important;
        }

        .text-secondary-custom {
            color: var(--primary-gray) !important;
        }

        .border-primary-custom {
            border-color: var(--accent-blue) !important;
        }

        .bg-light-custom {
            background-color: var(--light-gray) !important;
        }

        .badge-primary-custom {
            background-color: var(--accent-blue);
            color: var(--white);
        }

        .badge-secondary-custom {
            background-color: var(--medium-gray);
            color: var(--white);
        }

        .shadow-custom {
            box-shadow: 0 4px 15px var(--shadow-light);
        }

        .gradient-background {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('events.index') }}">
                <img src="{{ asset('images/pertamina_logo.jpg') }}" alt="Pertamina Logo" class="navbar-logo">
                <span class="navbar-brand-text">
                    <span class="d-none d-md-inline">Pertamina Maintenance & Construction</span>
                    <span class="d-inline d-md-none">Pertamina MC</span>
                </span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                            <i class="fas fa-home me-1"></i>
                            <span class="d-inline d-lg-inline">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('request-items.*') ? 'active' : '' }}" href="{{ route('request-items.index') }}">
                            <i class="fas fa-clipboard-list me-1"></i>
                            <span class="d-none d-sm-inline">Request </span>Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('bidangs.*') ? 'active' : '' }}" href="{{ route('bidangs.index') }}">
                            <i class="fas fa-sitemap me-1"></i>
                            <span class="d-none d-sm-inline">Daftar </span>Bidang
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-light mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0 text-muted">
                &copy; {{ date('Y') }} PT Pertamina Maintenance & Construction. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
