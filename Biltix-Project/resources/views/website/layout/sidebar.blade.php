<!-- ============= Left Sidebar Navigation ========================= -->
<nav class="sidebar-nav" id="sidebarNav">
  <!-- Mobile Close Button -->
  <div class="sidebar-header d-lg-none">
    <h5 class="sidebar-title">BILTIX</h5>
    <button class="sidebar-close" id="sidebarClose">
      <i class="fas fa-times"></i>
    </button>
  </div>

  <ul class="nav-menu">
    <li class="nav-item">
      <a href="{{ route('website.dashboard') }}" class="nav-link {{ request()->routeIs('website.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>{{ __('website.nav.projects') }}</span>
      </a>
    </li>
    @if(isset($project))
      <li class="nav-item">
        <a href="{{ route('website.project.plans', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.plans') ? 'active' : '' }}">
          <i class="fas fa-drafting-compass"></i>
          <span>{{ __('website.nav.plans') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.progress', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.progress') ? 'active' : '' }}">
          <i class="fas fa-chart-line"></i>
          <span>{{ __('website.nav.project_progress') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.inspections', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.inspections') ? 'active' : '' }}">
          <i class="fas fa-clipboard-check"></i>
          <span>{{ __('website.nav.inspection') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.tasks', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.tasks') ? 'active' : '' }}">
          <i class="fas fa-tasks"></i>
          <span>{{ __('website.nav.tasks') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.snag-list', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.snag-list') ? 'active' : '' }}">
          <i class="fas fa-exclamation-triangle"></i>
          <span>{{ __('website.nav.snag_list') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.gallery', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.gallery') ? 'active' : '' }}">
          <i class="fas fa-camera"></i>
          <span>{{ __('website.nav.photos') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.files', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.files') ? 'active' : '' }}">
          <i class="fas fa-folder"></i>
          <span>{{ __('website.nav.files') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.team-members', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.team-members') ? 'active' : '' }}">
          <i class="fas fa-users"></i>
          <span>{{ __('website.nav.people') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.daily-logs', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.daily-logs') ? 'active' : '' }}">
          <i class="fas fa-calendar-day"></i>
          <span>{{ __('website.nav.daily_logs') }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('website.project.notifications', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.notifications') ? 'active' : '' }}">
          <i class="fas fa-bell notifaction-icon"></i>
          <span>{{ __('website.nav.notifications_support') }}</span>
        </a>
      </li>
    @endif
  </ul>
</nav>