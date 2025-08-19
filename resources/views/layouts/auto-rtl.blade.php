<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biltix')</title>
    
    <!-- Auto Bootstrap CSS -->
    <link rel="stylesheet" href="{{ bootstrap_css() }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Auto RTL CSS -->
    <link rel="stylesheet" href="{{ asset('css/rtl-auto.css') }}">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}">
    
    @if(is_rtl())
        <link rel="stylesheet" href="{{ asset('website/css/rtl.css') }}">
    @endif
    
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <!-- Bootstrap JS -->
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- RTL Helper Script -->
    <script>
        window.isRTL = {{ is_rtl() ? 'true' : 'false' }};
        window.getDirection = () => '{{ dir_class() }}';
        window.getTextAlign = () => '{{ text_align() }}';
        window.getFloatStart = () => '{{ float_start() }}';
        window.getFloatEnd = () => '{{ float_end() }}';
        window.getMarginStart = (size) => '{{ is_rtl() ? 'me-' : 'ms-' }}' + size;
        window.getMarginEnd = (size) => '{{ is_rtl() ? 'ms-' : 'me-' }}' + size;
    </script>
    
    @stack('scripts')
</body>
</html>