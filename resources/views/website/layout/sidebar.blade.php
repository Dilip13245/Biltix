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
      <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>{{ __('messages.projects') }}</span>
      </a>
    </li>
    
    <!-- General Navigation Items -->
    @if(!isset($project))
      <li class="nav-item">
        <a href="{{ route('website.safety-checklist') }}" class="nav-link {{ request()->routeIs('website.safety-checklist') ? 'active' : '' }}">
          <i class="fas fa-shield-alt"></i>
          <span>{{ __('messages.safety_checklist') }}</span>
        </a>
      </li>
    @endif
    @if(isset($project))
      @can('plans', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.plans', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.plans') ? 'active' : '' }}">
          <i class="fas fa-drafting-compass"></i>
          <span>{{ __('messages.plans') }}</span>
        </a>
      </li>
      @endcan
      
      @can('progress', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.progress', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.progress') ? 'active' : '' }}">
          <i class="fas fa-chart-line"></i>
          <span>{{ __('messages.project_progress') }}</span>
        </a>
      </li>
      @endcan
      
      @can('inspections', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.inspections', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.inspections') ? 'active' : '' }}">
          <i class="fas fa-clipboard-check"></i>
          <span>{{ __('messages.inspections') }}</span>
        </a>
      </li>
      @endcan
      
      @can('tasks', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.tasks', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.tasks') ? 'active' : '' }}">
          <i class="fas fa-tasks"></i>
          <span>{{ __('messages.tasks') }}</span>
        </a>
      </li>
      @endcan
      
      @can('snags', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.snag-list', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.snag-list') ? 'active' : '' }}">
          <i class="fas fa-exclamation-triangle"></i>
          <span>{{ __('messages.snag_list') }}</span>
        </a>
      </li>
      @endcan
      
      @can('files', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.gallery', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.gallery') ? 'active' : '' }}">
          <i class="fas fa-camera"></i>
          <span>{{ __('messages.project_gallery') }}</span>
        </a>
      </li>
      @endcan
      
      @can('files', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.files', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.files') ? 'active' : '' }}">
          <i class="fas fa-folder"></i>
          <span>{{ __('messages.project_files') }}</span>
        </a>
      </li>
      @endcan
      
      @can('team', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.team-members', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.team-members') ? 'active' : '' }}">
          <i class="fas fa-users"></i>
          <span>{{ __('messages.team_members') }}</span>
        </a>
      </li>
      @endcan
      
      @can('daily_logs', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.daily-logs', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.daily-logs') ? 'active' : '' }}">
          <i class="fas fa-calendar-day"></i>
          <span>{{ __('messages.daily_logs') }}</span>
        </a>
      </li>
      @endcan
      
      <li class="nav-item">
        <a href="{{ route('website.project.notifications', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.notifications') ? 'active' : '' }}">
          <i class="fas fa-bell notifaction-icon"></i>
          <span>{{ __('messages.notifications') }}</span>
        </a>
      </li>
      
      @can('inspections', 'view')
      <li class="nav-item">
        <a href="{{ route('website.project.safety-checklist', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.safety-checklist') ? 'active' : '' }}">
          <i class="fas fa-shield-alt"></i>
          <span>{{ __('messages.safety_checklist') }}</span>
        </a>
      </li>
      @endcan
      
      <li class="nav-item">
        <a href="{{ route('website.project.help-support', $project->id) }}" class="nav-link {{ request()->routeIs('website.project.help-support') ? 'active' : '' }}">
          <i class="fas fa-headset"></i>
          <span>{{ __('messages.help_support') }}</span>
        </a>
      </li>
    @endif
  </ul>
</nav>