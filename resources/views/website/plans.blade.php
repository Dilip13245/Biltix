@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Project Plans')

@section('content')
<div class="content-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
  <div>
    <h2>{{ __('messages.plans') }}</h2>
    <p>{{ __('messages.view_markup_plans') }}</p>
  </div>
  <button class="btn orange_btn" data-bs-toggle="modal" data-bs-target="#uploadPlanModal">
    <i class="fas fa-arrow-up"></i>
{{ __('messages.upload_plan') }}
  </button>
</div>

<div class="CarDs-grid">
  <!-- Plan Card 1 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="0s">
    <div class="plan-image">
      <img src="{{ asset('website/images/place1.png') }}" alt="Ground Floor Plan">
    </div>
    <div class="carD-details">
      <h3>{{ __('messages.ground_floor_plan') }}</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 3.2 • 2.4 MB</span>
        <div class="updated-row">
          <span class="updated">{{ __('messages.updated') }} 2 {{ __('messages.days_ago') }}</span>
          <a href="#" onclick="openPlanViewer('{{ asset('website/images/place1.png') }}', 'Ground Floor Plan', 'Architectural', 'Rev. 3.2', '2.4 MB', '2 days ago')"> 
            <img src="{{ asset('website/images/icons/edit.svg') }}" alt="view">
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 2 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".4s">
    <div class="plan-image">
      <img src="{{ asset('website/images/place2.png') }}" alt="Second Floor Plan">
    </div>
    <div class="carD-details">
      <h3>{{ __('messages.second_floor_plan') }}</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 2.1 • 1.8 MB</span>
        <div class="updated-row">
          <span class="updated">{{ __("messages.updated") }} 1 {{ __("messages.week_ago") }}</span>
          <a href="#" onclick="openPlanViewer('{{ asset('website/images/place2.png') }}', 'Second Floor Plan', 'Architectural', 'Rev. 2.1', '1.8 MB', '1 week ago')"> 
            <img src="{{ asset('website/images/icons/edit.svg') }}" alt="view">
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 3 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".8s">
    <div class="plan-image">
      <img src="{{ asset('website/images/place3.png') }}" alt="Front Elevation">
    </div>
    <div class="carD-details">
      <h3>{{ __('messages.front_elevation') }}</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 1.5 • 3.2 MB</span>
        <div class="updated-row">
          <span class="updated">{{ __("messages.updated") }} 3 {{ __("messages.days_ago") }}</span>
          <a href="#" onclick="openPlanViewer('{{ asset('website/images/place3.png') }}', 'Front Elevation', 'Architectural', 'Rev. 1.5', '3.2 MB', '3 days ago')"> 
            <img src="{{ asset('website/images/icons/edit.svg') }}" alt="view">
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 4 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.2s">
    <div class="plan-image">
      <img src="{{ asset('website/images/place4.png') }}" alt="Building Section A-A">
    </div>
    <div class="carD-details">
      <h3>{{ __('messages.building_section') }}</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 2.0 • 2.1 MB</span>
        <div class="updated-row">
          <span class="updated">{{ __("messages.updated") }} 5 {{ __("messages.days_ago") }}</span>
          <a href="#" onclick="openPlanViewer('{{ asset('website/images/place4.png') }}', 'Building Section A-A', 'Structural', 'Rev. 2.0', '2.1 MB', '5 days ago')"> 
            <img src="{{ asset('website/images/icons/edit.svg') }}" alt="view">
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 5 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.3s">
    <div class="plan-image">
      <img src="{{ asset('website/images/place5.png') }}" alt="Basement Plan">
    </div>
    <div class="carD-details">
      <h3>{{ __('messages.basement_plan') }}</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 1.8 • 1.5 MB</span>
        <div class="updated-row">
          <span class="updated">{{ __("messages.updated") }} 1 {{ __("messages.week_ago") }}</span>
          <a href="#" onclick="openPlanViewer('{{ asset('website/images/place5.png') }}', 'Basement Plan', 'Architectural', 'Rev. 1.8', '1.5 MB', '1 week ago')"> 
            <img src="{{ asset('website/images/icons/edit.svg') }}" alt="view">
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 6 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.6s">
    <div class="plan-image">
      <img src="{{ asset('website/images/place6.png') }}" alt="Roof Plan">
    </div>
    <div class="carD-details">
      <h3>{{ __('messages.roof_plan') }}</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 1.2 • 1.2 MB</span>
        <div class="updated-row">
          <span class="updated">{{ __("messages.updated") }} 2 {{ __("messages.weeks_ago") }}</span>
          <a href="#" onclick="openPlanViewer('{{ asset('website/images/place6.png') }}', 'Roof Plan', 'Architectural', 'Rev. 1.2', '1.2 MB', '2 weeks ago')"> 
            <img src="{{ asset('website/images/icons/edit.svg') }}" alt="view">
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@include('website.modals.upload-plan-modal')
@include('website.modals.plan-viewer-modal')

<script>
function openPlanViewer(imageSrc, name, type, version, size, updated) {
  document.getElementById('planImage').src = imageSrc;
  document.getElementById('planInfoName').textContent = name;
  document.getElementById('planInfoType').textContent = type;
  document.getElementById('planInfoVersion').textContent = version;
  document.getElementById('planInfoSize').textContent = size;
  document.getElementById('planInfoUpdated').textContent = updated;
  
  const modal = new bootstrap.Modal(document.getElementById('planViewerModal'));
  modal.show();
}

// Upload Plan Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const uploadForm = document.getElementById('uploadPlanForm');
  if (uploadForm) {
    uploadForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Show loading state
      const submitBtn = document.querySelector('#uploadPlanModal .btn.orange_btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
      submitBtn.disabled = true;
      
      // Simulate upload (replace with actual AJAX call)
      setTimeout(() => {
        alert('Plan uploaded successfully!');
        bootstrap.Modal.getInstance(document.getElementById('uploadPlanModal')).hide();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        uploadForm.reset();
        
        // Refresh page or add new plan to the grid
        location.reload();
      }, 2000);
    });
  }
});
</script>

@endsection