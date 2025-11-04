<!-- Upload Plan Modal -->
<div class="modal fade" id="uploadPlanModal" tabindex="-1" aria-labelledby="uploadPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="@if(app()->getLocale() == 'ar') flex-direction: row-reverse; @endif">
        <style>
          #uploadPlanModal .btn-close {
            position: static !important;
            right: auto !important;
            top: auto !important;
          }
        </style>
        @if(app()->getLocale() == 'ar')
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <h5 class="modal-title" id="uploadPlanModalLabel">
            <i class="fas fa-upload me-2"></i>{{ __('messages.upload_new_plan') }}
          </h5>
        @else
          <h5 class="modal-title" id="uploadPlanModalLabel">
            <i class="fas fa-upload me-2"></i>{{ __('messages.upload_new_plan') }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        @endif
      </div>
      <div class="modal-body">
        <form id="uploadPlanForm" enctype="multipart/form-data">
          @csrf
          <div class="row d-none">
            <div class="col-md-6 mb-3">
              <label for="planTitle" class="form-label fw-medium">{{ __('messages.plan_title') }}</label>
              <input type="text" class="form-control Input_control" id="planTitle" name="title"
                placeholder="{{ __('messages.plan_title_example') }}">
            </div>
            <div class="col-md-6 mb-3">
              <label for="drawingNumber" class="form-label fw-medium">{{ __('messages.drawing_number') }}</label>
              <input type="text" class="form-control Input_control" id="drawingNumber" name="drawing_number"
                placeholder="{{ __('messages.drawing_number_example') }}">
            </div>
          </div>

          <div class="mb-3">
            <label for="planFiles" class="form-label fw-medium">{{ __('messages.plan_files') }}</label>
            <input type="file" class="form-control Input_control" id="planFiles" name="files[]" 
              multiple required accept=".pdf,.dwg,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx">
            <div class="form-text">
              <i class="fas fa-info-circle me-1"></i>
              {{ __('messages.plan_files_help') }}
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer" style="@if(app()->getLocale() == 'ar') flex-direction: row-reverse; @endif">
        @if(app()->getLocale() == 'ar')
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
          <button type="submit" form="uploadPlanForm" class="btn orange_btn api-action-btn" id="uploadPlanSubmitBtn">
            {{ __('messages.next') }}
          </button>
        @else
          <button type="submit" form="uploadPlanForm" class="btn orange_btn api-action-btn" id="uploadPlanSubmitBtn">
            {{ __('messages.next') }}
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
// Reset modal when it's hidden
document.getElementById('uploadPlanModal')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('uploadPlanForm');
    const btn = document.getElementById('uploadPlanSubmitBtn');
    
    if (form) form.reset();
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '{{ __('messages.next') }}';
    }
});

// Reset button text when modal is shown
document.getElementById('uploadPlanModal')?.addEventListener('show.bs.modal', function() {
    const btn = document.getElementById('uploadPlanSubmitBtn');
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '{{ __('messages.next') }}';
    }
});
</script>