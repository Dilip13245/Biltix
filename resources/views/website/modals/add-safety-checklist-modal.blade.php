<!-- Add Safety Checklist Modal -->
<div class="modal fade" id="addSafetyChecklistModal" tabindex="-1" aria-labelledby="addSafetyChecklistModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
        <h5 class="modal-title" id="addSafetyChecklistModalLabel">
          <i class="fas fa-shield-alt {{ margin_end(2) }}"></i>{{ __('messages.add_safety_checklist') }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSafetyChecklistForm">
          @csrf
          <div class="row">
            <div class="col-md-8 mb-3">
              <label for="checklistTitle" class="form-label fw-medium">{{ __('messages.checklist_title') }}</label>
              <input type="text" class="form-control Input_control" id="checklistTitle" name="title" required
                placeholder="{{ __('messages.checklist_title_placeholder') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="checklistType" class="form-label fw-medium">{{ __('messages.checklist_type') }}</label>
              <select class="form-select Input_control" id="checklistType" name="type" required>
                <option value="">{{ __('messages.select_type') }}</option>
                <option value="daily">{{ __('messages.daily_inspection') }}</option>
                <option value="weekly">{{ __('messages.weekly_review') }}</option>
                <option value="equipment">{{ __('messages.equipment_check') }}</option>
                <option value="meeting">{{ __('messages.safety_meeting') }}</option>
                <option value="incident">{{ __('messages.incident_report') }}</option>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="checklistDate" class="form-label fw-medium">{{ __('messages.date') }}</label>
              @include('website.includes.date-picker', [
                'id' => 'checklistDate',
                'name' => 'date',
                'placeholder' => __('messages.select_inspection_date'),
                'value' => date('Y-m-d'),
                'required' => true
              ])
            </div>
            <div class="col-md-6 mb-3">
              <label for="inspector" class="form-label fw-medium">{{ __('messages.inspector_responsible') }}</label>
              <input type="text" class="form-control Input_control" id="inspector" name="inspector" 
                placeholder="{{ __('messages.inspector_placeholder') }}">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">{{ __('messages.safety_items_to_check') }}</label>
            <div id="safetyItems">
              <div class="input-group mb-2">
                <input type="text" class="form-control" name="items[]" placeholder="{{ __('messages.safety_item_example') }}">
                <button class="btn btn-outline-danger" type="button" onclick="removeItem(this)">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSafetyItem()">
              <i class="fas fa-plus {{ margin_end(1) }}"></i>{{ __('messages.add_item') }}
            </button>
          </div>

          <div class="mb-3">
            <label for="location" class="form-label fw-medium">{{ __('messages.location_area') }}</label>
            <input type="text" class="form-control Input_control" id="location" name="location" 
              placeholder="{{ __('messages.location_placeholder') }}">
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label fw-medium">{{ __('messages.additional_notes') }}</label>
            <textarea class="form-control Input_control" id="notes" name="notes" rows="3"
              placeholder="{{ __('messages.notes_placeholder') }}"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("messages.cancel") }}</button>
        <button type="submit" form="addSafetyChecklistForm" class="btn orange_btn" id="safetyChecklistBtn">
          <i class="fas fa-plus {{ margin_end(2) }}"></i>{{ __('messages.create_checklist') }}
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function addSafetyItem() {
  const container = document.getElementById('safetyItems');
  const newItem = document.createElement('div');
  newItem.className = 'input-group mb-2';
  newItem.innerHTML = `
    <input type="text" class="form-control" name="items[]" placeholder="{{ __('messages.enter_safety_item') }}">
    <button class="btn btn-outline-danger" type="button" onclick="removeItem(this)">
      <i class="fas fa-times"></i>
    </button>
  `;
  container.appendChild(newItem);
}

function removeItem(button) {
  button.closest('.input-group').remove();
}

function protectSafetyChecklistButton() {
  var btn = document.getElementById('safetyChecklistBtn');
  if (btn && !btn.disabled) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
    setTimeout(function() {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-plus {{ margin_end(2) }}"></i>{{ __('messages.create_checklist') }}';
    }, 5000);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  var btn = document.getElementById('safetyChecklistBtn');
  if (btn) {
    btn.addEventListener('click', protectSafetyChecklistButton);
  }
});
</script>