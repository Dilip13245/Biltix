<!-- Upload File Modal -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <style>
          #uploadFileModal .modal-header .btn-close {
            position: static !important;
            right: auto !important;
            top: auto !important;
            margin: 0 !important;
          }
          #uploadFileModal .modal-header {
            position: relative !important;
          }
        </style>
        @if(app()->getLocale() == 'ar')
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title" id="uploadFileModalLabel">
            {{ __('messages.upload_file') }}<i class="fas fa-upload ms-2"></i>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @else
        <h5 class="modal-title" id="uploadFileModalLabel">
          <i class="fas fa-upload me-2"></i>{{ __('messages.upload_file') }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        @endif
      </div>
      <div class="modal-body">
        <form id="uploadFileForm" enctype="multipart/form-data" novalidate>
          @csrf
          <div class="mb-3">
            <label for="fileTitle" class="form-label fw-medium">{{ __('messages.file_title') }}</label>
            <input type="text" class="form-control Input_control" id="fileTitle" name="title"
              placeholder="{{ __('messages.enter_file_title') }}">
          </div>

          <div class="mb-3">
            <label for="fileCategory" class="form-label fw-medium">{{ __('messages.category') }}</label>
            <select class="form-select Input_control" id="fileCategory" name="category">
              <option value="">{{ __('messages.select_category') }}</option>
              <option value="drawings">{{ __('messages.drawings') }}</option>
              <option value="documents">{{ __('messages.documents') }}</option>
              <option value="specifications">{{ __('messages.specifications') }}</option>
              <option value="reports">{{ __('messages.reports') }}</option>
              <option value="photos">{{ __('messages.photos') }}</option>
              <option value="other">{{ __('messages.other') }}</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="fileUpload" class="form-label fw-medium">{{ __('messages.select_file') }}</label>
            <input type="file" class="form-control Input_control" id="fileUpload" name="file"
              accept=".pdf,.doc,.docx,.xls,.xlsx,.dwg,.jpg,.jpeg,.png,.gif,.txt">
            <div class="form-text">{{ __('messages.supported_formats') }}: PDF, DOC, XLS, DWG, JPG, PNG</div>
          </div>

          <div class="mb-3">
            <label for="fileDescription" class="form-label fw-medium">{{ __('messages.description') }} <span class="text-muted">({{ __('messages.optional') }})</span></label>
            <textarea class="form-control Input_control" id="fileDescription" name="description" rows="3"
              placeholder="{{ __('messages.brief_description') }}"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
        <button type="button" class="btn orange_btn api-action-btn" id="uploadFileSubmitBtn">
          {{ __('messages.next') }}
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Validation function
function validateFileForm() {
    const form = document.getElementById('uploadFileForm');
    if (!form) return false;
    
    let isValid = true;
    
    // Clear previous errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    // Validate file title
    const fileTitle = form.querySelector('#fileTitle');
    if (!fileTitle.value.trim()) {
        showFieldError(fileTitle, '{{ __('messages.file_title') }} is required');
        isValid = false;
    }
    
    // Validate category
    const fileCategory = form.querySelector('#fileCategory');
    if (!fileCategory.value) {
        showFieldError(fileCategory, '{{ __('messages.category') }} is required');
        isValid = false;
    }
    
    // Validate file upload
    const fileUpload = form.querySelector('#fileUpload');
    if (!fileUpload.files || fileUpload.files.length === 0) {
        showFieldError(fileUpload, '{{ __('messages.select_file') }} is required');
        isValid = false;
    }
    
    if (!isValid) {
        if (typeof showToast !== 'undefined') {
            showToast('Please fill in all required fields.', 'error');
        } else if (typeof toastr !== 'undefined') {
            toastr.error('Please fill in all required fields.');
        } else {
            alert('Please fill in all required fields.');
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

// Form submit handler - handle both form submit and button click
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('uploadFileForm');
    const submitBtn = document.getElementById('uploadFileSubmitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (!validateFileForm()) {
                return false;
            }
        });
    }
    
    // Also handle button click
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = document.getElementById('uploadFileForm');
            if (form && typeof validateFileForm === 'function') {
                if (validateFileForm()) {
                    // Validation passed, trigger form submit
                    form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                }
            }
        });
    }
});

// Reset modal when it's hidden
document.getElementById('uploadFileModal')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('uploadFileForm');
    const btn = document.getElementById('uploadFileSubmitBtn');
    
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
document.getElementById('uploadFileModal')?.addEventListener('show.bs.modal', function() {
    const btn = document.getElementById('uploadFileSubmitBtn');
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '{{ __('messages.next') }}';
    }
});
</script>