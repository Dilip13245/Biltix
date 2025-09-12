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
  
  <!-- API Client Scripts -->
  <script src="{{ asset('website/js/api-config.js') }}"></script>
  <script src="{{ asset('website/js/api-encryption.js') }}"></script>
  <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
  <script src="{{ asset('website/js/api-helpers.js') }}"></script>
  <script src="{{ asset('website/js/api-client.js') }}"></script>
  
  <script src="{{ asset('website/js/custom.js') }}"></script>
  
  <!-- Universal Auth System -->
  <script src="{{ asset('website/js/universal-auth.js') }}"></script>
  <script>
      // Disable auth check on app layout pages - Laravel middleware handles it
      window.DISABLE_JS_AUTH_CHECK = true;
      
      // Load header project data
      document.addEventListener('DOMContentLoaded', function() {
          loadHeaderProjectData();
      });
      
      async function loadHeaderProjectData() {
          // Get project ID from controller if available
          const projectId = @json(isset($project) ? $project->id : null);
          
          if (projectId) {
              
              try {
                  const response = await api.getProjectDetails({
                      project_id: projectId
                  });
                  
                  if (response.code === 200 && response.data) {
                      const project = response.data;
                      
                      // Update header elements
                      document.getElementById('headerProjectTitle').textContent = project.project_title || 'Project';
                      
                      if (project.project_start_date) {
                          document.getElementById('headerStartDate').textContent = formatHeaderDate(project.project_start_date);
                      }
                      
                      if (project.project_due_date) {
                          document.getElementById('headerEndDate').textContent = formatHeaderDate(project.project_due_date);
                      }
                      
                      // Calculate and display progress (placeholder - you can enhance this)
                      const progress = calculateProjectProgress(project);
                      document.getElementById('headerProgress').textContent = progress + '%';
                      
                      // Update status
                      const statusElement = document.getElementById('headerStatus');
                      const statusBadge = document.getElementById('headerStatusBadge');
                      
                      if (project.status) {
                          statusElement.textContent = getStatusText(project.status);
                          statusBadge.className = getStatusClass(project.status);
                      }
                  }
              } catch (error) {
                  console.error('Failed to load header project data:', error);
                  // Keep loading text on error
              }
          }
      }
      
      function formatHeaderDate(dateString) {
          const date = new Date(dateString);
          return date.toLocaleDateString('en-US', {
              month: 'short',
              day: 'numeric',
              year: 'numeric'
          });
      }
      
      function calculateProjectProgress(project) {
          // Simple progress calculation based on dates
          if (project.project_start_date && project.project_due_date) {
              const startDate = new Date(project.project_start_date);
              const endDate = new Date(project.project_due_date);
              const currentDate = new Date();
              
              if (currentDate < startDate) return 0;
              if (currentDate > endDate) return 100;
              
              const totalDuration = endDate - startDate;
              const elapsed = currentDate - startDate;
              
              return Math.round((elapsed / totalDuration) * 100);
          }
          return 0;
      }
      
      function getStatusText(status) {
          switch (status?.toLowerCase()) {
              case 'active':
              case 'in_progress':
                  return @json(__('messages.active'));
              case 'completed':
                  return @json(__('messages.completed'));
              case 'planning':
                  return @json(__('messages.planning'));
              case 'on_hold':
                  return @json(__('messages.on_hold'));
              default:
                  return @json(__('messages.active'));
          }
      }
      
      function getStatusClass(status) {
          switch (status?.toLowerCase()) {
              case 'active':
              case 'in_progress':
                  return 'status-badge-active';
              case 'completed':
                  return 'status-badge-completed';
              case 'planning':
                  return 'status-badge-planning';
              case 'on_hold':
                  return 'status-badge-hold';
              default:
                  return 'status-badge-active';
          }
      }
  </script>
  
  <!-- RTL Icon Spacing Fix -->
  <script src="{{ asset('website/js/rtl-spacing-fix.js') }}"></script>
  
  @include('website.layout.auth-check')
  @include('website.layout.user-info')
  
  @stack('scripts')

</body>

</html>