<!-- Add Snag Modal -->
<div class="modal fade" id="addSnagModal" tabindex="-1" aria-labelledby="addSnagModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
        <h5 class="modal-title" id="addSnagModalLabel">
          <i class="fas fa-exclamation-triangle {{ margin_end(2) }}"></i>{{ __("messages.add_new_snag") }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSnagForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="issueType" class="form-label fw-medium">{{ __("messages.type_of_issue") }}</label>
            <select class="form-select Input_control" id="issueType" name="issue_type" required>
              <option value="">{{ __("messages.select_issue_type") }}</option>
              <option value="electrical">{{ __("messages.electrical") }}</option>
              <option value="mechanical">{{ __("messages.mechanical") }}</option>
              <option value="plumbing">{{ __("messages.plumbing") }}</option>
              <option value="structural">{{ __("messages.structural") }}</option>
              <option value="finishing">{{ __("messages.finishing") }}</option>
              <option value="safety">{{ __("messages.safety") }}</option>
              <option value="hvac">{{ __("messages.hvac") }}</option>
              <option value="other">{{ __("messages.other") }}</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label fw-medium">{{ __("messages.add_description") }} <span class="text-muted">({{ __("messages.optional") }})</span></label>
            <textarea class="form-control Input_control" id="description" name="description" rows="4"
              placeholder="{{ __("messages.provide_detailed_description") }}"></textarea>
          </div>
          
          <div class="mb-3">
            <label for="location" class="form-label fw-medium">{{ __("messages.add_location") }}</label>
            <input type="text" class="form-control Input_control" id="location" name="location" required
              placeholder="{{ __("messages.location_example") }}">
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
        <button type="submit" form="addSnagForm" class="btn orange_btn">
          {{ __("messages.next") }} →
        </button>
      </div>
    </div>
  </div>
</div>