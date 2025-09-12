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
        <form id="uploadFileForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="fileTitle" class="form-label fw-medium">{{ __('messages.file_title') }}</label>
            <input type="text" class="form-control Input_control" id="fileTitle" name="title" required
              placeholder="{{ __('messages.enter_file_title') }}">
          </div>

          <div class="mb-3">
            <label for="fileCategory" class="form-label fw-medium">{{ __('messages.category') }}</label>
            <select class="form-select Input_control" id="fileCategory" name="category" required>
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
            <input type="file" class="form-control Input_control" id="fileUpload" name="file" required
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
        <button type="submit" form="uploadFileForm" class="btn orange_btn">
          {{ __('messages.next') }} <i class="fas fa-arrow-right ms-2"></i>
        </button>
      </div>
    </div>
  </div>
</div>