<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ dir_attr() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    
    <!-- Language Meta Tags -->
    <meta name="language" content="{{ app()->getLocale() }}">
    <link rel="alternate" hreflang="{{ app()->getLocale() }}" href="{{ url()->current() }}">
    @foreach(array_keys(config('app.available_locales', [])) as $locale)
        @if($locale !== app()->getLocale())
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ locale_url($locale) }}">
        @endif
    @endforeach
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    @if(is_rtl())
        <!-- Arabic fonts -->
        <link href="https://fonts.bunny.net/css?family=noto-sans-arabic:400,500,600,700" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700" rel="stylesheet" />
    @else
        <!-- English fonts -->
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @endif
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    
    @stack('styles')
    
    <style>
        /* Base font families */
        body {
            @if(is_rtl())
                font-family: 'Cairo', 'Noto Sans Arabic', Arial, sans-serif;
            @else
                font-family: 'Figtree', system-ui, sans-serif;
            @endif
        }
        
        /* RTL specific adjustments */
        @if(is_rtl())
            body {
                text-align: right;
                direction: rtl;
            }
            
            .navbar-brand {
                margin-left: 1rem;
                margin-right: 0;
            }
        @endif
        
        /* Loading indicator */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Language transition */
        * {
            transition: all 0.2s ease-in-out;
        }
    </style>
</head>

<body class="{{ rtl_class() }}">
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __t('common.home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ __t('common.about') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ __t('common.contact') }}</a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <!-- Language Switcher -->
                        <li class="nav-item">
                            @include('components.language-switcher')
                        </li>
                        
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{ __t('common.login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{ __t('common.register') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">{{ __t('common.profile') }}</a></li>
                                    <li><a class="dropdown-item" href="#">{{ __t('common.settings') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __t('common.logout') }}
                                        </a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="#" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="py-4">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @if($errors->any())
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-dark text-light py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ config('app.name', 'Laravel') }}</h5>
                        <p class="text-muted">{{ __t('common.description', [], null, 'Your application description here') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>{{ __t('common.language') }}</h5>
                        @include('components.language-switcher')
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __t('common.all_rights_reserved', [], null, 'All rights reserved.') }}</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    
    <script>
        // Global JavaScript variables
        window.currentLocale = '{{ app()->getLocale() }}';
        window.isRtl = {{ is_rtl() ? 'true' : 'false' }};
        window.availableLocales = @json(config('app.available_locales', []));
        
        // Enhanced locale_url function for JavaScript
        function locale_url(locale, url) {
            url = url || window.location.href;
            const urlObj = new URL(url);
            urlObj.searchParams.set('lang', locale);
            return urlObj.toString();
        }
        
        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Loading state management
        function showLoading() {
            document.body.classList.add('loading');
        }
        
        function hideLoading() {
            document.body.classList.remove('loading');
        }
        
        // Handle language switching
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth transitions
            document.body.style.transition = 'all 0.3s ease-in-out';
            
            // Handle form submissions with loading states
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function() {
                    showLoading();
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>