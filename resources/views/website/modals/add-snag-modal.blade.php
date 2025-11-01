<!-- Add Snag Modal -->
<div class="modal fade" id="addSnagModal" tabindex="-1" aria-labelledby="addSnagModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <style>
          #addSnagModal .modal-header .btn-close {
            position: static !important;
            right: auto !important;
            top: auto !important;
            margin: 0 !important;
          }
          #addSnagModal .modal-header {
            position: relative !important;
          }
        </style>
        @if(app()->getLocale() == 'ar')
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title" id="addSnagModalLabel">
            {{ __("messages.add_new_snag") }}<i class="fas fa-exclamation-triangle ms-2"></i>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @else
        <h5 class="modal-title" id="addSnagModalLabel">
          <i class="fas fa-exclamation-triangle me-2"></i>{{ __("messages.add_new_snag") }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        @endif
      </div>
      <div class="modal-body">
        <form id="addSnagForm" class="protected-form" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="snagTitle" class="form-label fw-medium">{{ __("messages.snag_title") }}</label>
            <input type="text" class="form-control Input_control" id="snagTitle" name="title" required
              placeholder="Enter snag title" maxlength="100">
          </div>

          <div class="mb-3">
            <label for="description" class="form-label fw-medium">{{ __("messages.add_description") }}</label>
            <textarea class="form-control Input_control" id="description" name="description" rows="3"
              placeholder="{{ __("messages.provide_detailed_description") }}" maxlength="1000"></textarea>
          </div>
          
          <div class="mb-3">
            <label for="location" class="form-label fw-medium">{{ __("messages.add_location") }}</label>
            <input type="text" class="form-control Input_control" id="location" name="location" required
              placeholder="{{ __("messages.location_example") }}" maxlength="200">
          </div>

          <div class="mb-3" id="phaseSelectContainer">
            <label for="phaseSelect" class="form-label fw-medium">{{ __("messages.phase") }}</label>
            <select class="form-select Input_control searchable-select" id="phaseSelect" name="phase_id" required>
              <option value="">{{ __("messages.select_phase") }}</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="assignedTo" class="form-label fw-medium">{{ __("messages.assign_to") }} <span class="text-muted">({{ __("messages.optional") }})</span></label>
            <select class="form-select Input_control searchable-select" id="assignedTo" name="assigned_to">
              <option value="">{{ __("messages.select_user") }}</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="snagPhotos" class="form-label fw-medium">{{ __("messages.upload_images") }}</label>
            <input type="file" class="form-control Input_control" id="snagPhotos" name="photos[]" 
              accept="image/*" multiple>
            <div class="form-text">{{ __("messages.upload_photos_limit") }}</div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("messages.cancel") }}</button>
        <button type="submit" form="addSnagForm" class="btn orange_btn" id="createSnagBtn">
          <i class="fas fa-save me-2"></i>{{ __("messages.create_snag") }}
        </button>
      </div>
    </div>
  </div>
</div>