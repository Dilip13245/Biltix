@extends('website.layout.app')

@section('title', __('messages.project_dashboard'))

@section('content')
<div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
  <div>
    <h2>{{ __('messages.project_dashboard') }}</h2>
    <p>{{ __('messages.welcome') }} to the project dashboard.</p>
  </div>
  @can('projects', 'create')
      <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#createProjectModal">
        <i class="fas fa-plus"></i>
        {{ __('messages.new_project') }}
      </button>
  @endcan
</div>
<div class="container-fluid">
    <!-- Dashboard content here -->
</div>

@include('website.modals.drawing-modal')

<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createProjectModalLabel">
          <i class="fas fa-plus me-2"></i>{{ __('messages.create_new_project') }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createProjectForm">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="project_title" class="form-label fw-medium">{{ __('messages.project_name') }}</label>
                <input type="text" class="form-control Input_control" id="project_title" name="project_title" required
                  placeholder="{{ __('messages.project_name') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="contractor_name" class="form-label fw-medium">{{ __('messages.contractor_name') }}</label>
                <input type="text" class="form-control Input_control" id="contractor_name" name="contractor_name" required
                  placeholder="{{ __('messages.contractor_name') }}">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="type" class="form-label fw-medium">{{ __('messages.project_type') }}</label>
                <select class="form-select Input_control" id="type" name="type" required>
                  <option value="">{{ __('messages.select_type') }}</option>
                  <option value="commercial">{{ __('messages.commercial') }}</option>
                  <option value="residential">{{ __('messages.residential') }}</option>
                  <option value="industrial">{{ __('messages.industrial') }}</option>
                  <option value="renovation">{{ __('messages.renovation') }}</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="project_location" class="form-label fw-medium">{{ __('messages.location') }}</label>
                <input type="text" class="form-control Input_control" id="project_location" name="project_location" required
                  placeholder="{{ __('messages.location') }}">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="project_start_date" class="form-label fw-medium">{{ __('messages.start_date') }}</label>
                @include('website.includes.date-picker', [
                  'id' => 'project_start_date',
                  'name' => 'project_start_date',
                  'placeholder' => __('messages.select_start_date'),
                  'minDate' => date('Y-m-d'),
                  'required' => true
                ])
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="project_due_date" class="form-label fw-medium">{{ __('messages.end_date') }}</label>
                @include('website.includes.date-picker', [
                  'id' => 'project_due_date',
                  'name' => 'project_due_date',
                  'placeholder' => __('messages.select_end_date'),
                  'minDate' => date('Y-m-d'),
                  'required' => true
                ])
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="priority" class="form-label fw-medium">{{ __('messages.priority') }}</label>
                <select class="form-select Input_control" id="priority" name="priority">
                  <option value="low">{{ __('messages.low') }}</option>
                  <option value="medium" selected>{{ __('messages.medium') }}</option>
                  <option value="high">{{ __('messages.high') }}</option>
                  <option value="critical">{{ __('messages.critical') }}</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="construction_plans" class="form-label fw-medium">{{ __('messages.construction_plans') }}</label>
                <input type="file" class="form-control Input_control" id="construction_plans" name="construction_plans[]" 
                  multiple accept=".pdf,.docx,.jpg,.jpeg,.png">
                <small class="text-muted">{{ __('messages.max_25mb_per_file') }}</small>
              </div>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="gantt_chart" class="form-label fw-medium">{{ __('messages.gantt_chart') }}</label>
            <input type="file" class="form-control Input_control" id="gantt_chart" name="gantt_chart[]" 
              multiple accept=".pdf,.docx,.jpg,.jpeg,.png">
            <small class="text-muted">{{ __('messages.max_25mb_per_file') }}</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
        <button type="submit" form="createProjectForm" class="btn orange_btn api-action-btn">
          <i class="fas fa-plus me-2"></i>{{ __('messages.create') }}
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Create Project Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const createProjectForm = document.getElementById('createProjectForm');
  if (createProjectForm) {
    createProjectForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const fileInputs = document.querySelectorAll('input[type="file"]');
      let hasFiles = false;
      
      fileInputs.forEach(input => {
        if (input.files && input.files.length > 0) {
          hasFiles = true;
        }
      });
      
      if (hasFiles) {
        // Store form data
        window.projectFormData = new FormData(createProjectForm);
        
        // Close project modal
        const projectModal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
        if (projectModal) projectModal.hide();
        
        // Open drawing modal for file markup
        setTimeout(() => {
          openDrawingModal({
            title: 'Markup Project Files',
            saveButtonText: 'Create Project',
            mode: 'image',
            onSave: function(imageData) {
              // Close drawing modal
              const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
              if (drawingModal) drawingModal.hide();
              
              alert('Project created successfully with markup!');
              location.reload();
            }
          });
          
          // Load first file to canvas
          const firstFileInput = Array.from(fileInputs).find(input => input.files.length > 0);
          if (firstFileInput && firstFileInput.files[0]) {
            document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
              loadImageToCanvas(firstFileInput.files[0]);
            }, { once: true });
          }
        }, 300);
      } else {
        // No files, create project directly
        const projectModal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
        if (projectModal) projectModal.hide();
        
        alert('Project created successfully!');
        createProjectForm.reset();
        location.reload();
      }
    });
  }
});
</script>

@endsection