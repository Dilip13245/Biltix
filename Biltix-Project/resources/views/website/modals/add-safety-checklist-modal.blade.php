<!-- Add Safety Checklist Modal -->
<div class="modal fade" id="addSafetyChecklistModal" tabindex="-1" aria-labelledby="addSafetyChecklistModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSafetyChecklistModalLabel">
          <i class="fas fa-shield-alt me-2"></i>Add Safety Checklist
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSafetyChecklistForm">
          @csrf
          <div class="row">
            <div class="col-md-8 mb-3">
              <label for="checklistTitle" class="form-label fw-medium">Checklist Title</label>
              <input type="text" class="form-control Input_control" id="checklistTitle" name="title" required
                placeholder="e.g., Daily Safety Inspection">
            </div>
            <div class="col-md-4 mb-3">
              <label for="checklistType" class="form-label fw-medium">Type</label>
              <select class="form-select Input_control" id="checklistType" name="type" required>
                <option value="">Select Type</option>
                <option value="daily">Daily Inspection</option>
                <option value="weekly">Weekly Review</option>
                <option value="equipment">Equipment Check</option>
                <option value="meeting">Safety Meeting</option>
                <option value="incident">Incident Report</option>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="checklistDate" class="form-label fw-medium">Date</label>
              <input type="date" class="form-control Input_control" id="checklistDate" name="date" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="inspector" class="form-label fw-medium">Inspector/Responsible Person</label>
              <input type="text" class="form-control Input_control" id="inspector" name="inspector" 
                placeholder="Name of person responsible">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Safety Items to Check</label>
            <div id="safetyItems">
              <div class="input-group mb-2">
                <input type="text" class="form-control" name="items[]" placeholder="e.g., All workers wearing safety helmets">
                <button class="btn btn-outline-danger" type="button" onclick="removeItem(this)">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSafetyItem()">
              <i class="fas fa-plus me-1"></i>Add Item
            </button>
          </div>

          <div class="mb-3">
            <label for="location" class="form-label fw-medium">Location/Area</label>
            <input type="text" class="form-control Input_control" id="location" name="location" 
              placeholder="e.g., Building A, Ground Floor">
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label fw-medium">Additional Notes</label>
            <textarea class="form-control Input_control" id="notes" name="notes" rows="3"
              placeholder="Any additional safety notes or observations..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="addSafetyChecklistForm" class="btn orange_btn">
          <i class="fas fa-plus me-2"></i>Create Checklist
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
    <input type="text" class="form-control" name="items[]" placeholder="Enter safety item to check">
    <button class="btn btn-outline-danger" type="button" onclick="removeItem(this)">
      <i class="fas fa-times"></i>
    </button>
  `;
  container.appendChild(newItem);
}

function removeItem(button) {
  button.closest('.input-group').remove();
}
</script>