<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Construction Project Dashboard">
  <meta name="keywords" content="HTML,CSS,XML,JavaScript">
  <meta name="author" content="John Doe">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Biltix')</title>

  <!-- FAVICON -->
  <link rel="icon" href="{{ asset('assets/images/icons/logo.svg') }}" type="image/x-icon" />

  <!-- BOOTSTRAP CSS -->
  @if(app()->getLocale() === 'ar')
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-5.3.1-dist/css/bootstrap.rtl.min.css') }}" />
  @else
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-5.3.1-dist/css/bootstrap.min.css') }}" />
  @endif

  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- ----ANIMATION CSS-- -->
  <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
  <!-- CUSTOM CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

  <!-- RESPONSIVE CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
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
  <script src="{{ asset('assets/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('assets/js/wow.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>

</body>

</html>