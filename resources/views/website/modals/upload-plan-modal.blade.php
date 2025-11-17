<!-- Upload Plan Modal -->
<div class="modal fade" id="uploadPlanModal" tabindex="-1" aria-labelledby="uploadPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <style>
          #uploadPlanModal .modal-header .btn-close {
            position: static !important;
            right: auto !important;
            top: auto !important;
            margin: 0 !important;
          }

          #uploadPlanModal .modal-header {
            position: relative !important;
          }
        </style>
        @if (app()->getLocale() == 'ar')
          <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="modal-title" id="uploadPlanModalLabel">
              {{ __('messages.upload_new_plan') }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
          </div>
        @else
          <h5 class="modal-title" id="uploadPlanModalLabel">
            {{ __('messages.upload_new_plan') }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
        @endif
      </div>
      <div class="modal-body">
        <form id="uploadPlanForm" enctype="multipart/form-data" novalidate>
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
              multiple accept=".pdf,.dwg,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx">
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
          <button type="button" class="btn orange_btn api-action-btn" id="uploadPlanSubmitBtn">
            {{ __('messages.next') }}
          </button>
        @else
          <button type="button" class="btn orange_btn api-action-btn" id="uploadPlanSubmitBtn">
            {{ __('messages.next') }}
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
// Validation function
function validatePlanForm() {
    const form = document.getElementById('uploadPlanForm');
    if (!form) return false;
    
    let isValid = true;
    
    // Clear previous errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    // Validate files
    const planFiles = form.querySelector('#planFiles');
    if (!planFiles.files || planFiles.files.length === 0) {
        showFieldError(planFiles, '{{ __('messages.plan_files') }} is required');
        isValid = false;
    }
    
    if (!isValid) {
        if (typeof showToast !== 'undefined') {
            showToast('Please select at least one plan file.', 'error');
        } else if (typeof toastr !== 'undefined') {
            toastr.error('Please select at least one plan file.');
        } else {
            alert('Please select at least one plan file.');
        }
    }
    
    return isValid;
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentElement.appendChild(errorDiv);
}

// Form validation only - actual submit is handled in plans.blade.php
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('uploadPlanForm');
    const submitBtn = document.getElementById('uploadPlanSubmitBtn');
    
    // Prevent default form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, { capture: true });
    }
    
    // Button click handler - trigger form submit event (handled by plans.blade.php)
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Validate form
            if (typeof validatePlanForm === 'function' && !validatePlanForm()) {
                return false;
            }
            
            // Trigger form submit event (will be handled by plans.blade.php)
            if (form) {
                const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
                form.dispatchEvent(submitEvent);
            }
        });
    }
});

// Reset modal when it's hidden
document.getElementById('uploadPlanModal')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('uploadPlanForm');
    const btn = document.getElementById('uploadPlanSubmitBtn');
    
    if (form) {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }
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