<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Construction Project Dashboard">
  <meta name="keywords" content="HTML,CSS,XML,JavaScript">
  <meta name="author" content="John Doe">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Biltix')</title>

  <!-- FAVICON -->
  <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />

  <!-- BOOTSTRAP CSS -->
  <link rel="stylesheet" href="{{ bootstrap_css() }}" />

  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- ----ANIMATION CSS-- -->
  <link rel="stylesheet" href="{{ asset('website/css/animate.css') }}">

  
  <!-- CUSTOM CSS -->
  <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />

  <!-- RESPONSIVE CSS -->
  <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}" />
  
  <!-- RTL CSS -->
  <link rel="stylesheet" href="{{ asset('website/css/rtl-auto.css') }}" />
  
  <!-- TOASTR CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="{{ asset('website/css/toastr-custom.css') }}">
  

</head>

<body>
  <div class="content_wraper">
    @include('website.layout.header')

    <div class="main-content-wrapper">
      @include('website.layout.sidebar')

      <!-- Mobile Overlay -->
      <div class="mobile-overlay" id="mobileOverlay"></div>

      <!-- ============= Main Content Area ========================= -->
      <main class="main-content">
        @yield('content')
      </main>
    </div>
  </div>

  <!-- ============= JavaScript Files ========================= -->
  <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('website/js/wow.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="{{ asset('website/js/toastr-config.js') }}"></script>
  <script src="{{ asset('website/js/permissions.js') }}"></script>
  
  <!-- SIMPLE DRAWING LIBRARY -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.7/signature_pad.umd.min.js"></script>
  
  <script src="{{ asset('website/js/custom.js') }}"></script>
  
  <!-- Universal Auth System -->
  <script src="{{ asset('website/js/universal-auth.js') }}"></script>
  <script>
      // Disable auth check on app layout pages - Laravel middleware handles it
      window.DISABLE_JS_AUTH_CHECK = true;
  </script>
  
  <!-- RTL Icon Spacing Fix -->
  <script src="{{ asset('website/js/rtl-spacing-fix.js') }}"></script>
  
  @include('website.layout.auth-check')
  @include('website.layout.user-info')
  
  @stack('scripts')

</body>

</html>