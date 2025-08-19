<!-- Create Inspection Modal -->
<div class="modal fade" id="createInspectionModal" tabindex="-1" aria-labelledby="createInspectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createInspectionModalLabel">
          <i class="fas fa-clipboard-check me-2"></i>Create New Inspection
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createInspectionForm">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="inspectionTitle" class="form-label fw-medium">Inspection Title</label>
              <input type="text" class="form-control Input_control" id="inspectionTitle" name="title" required
                placeholder="e.g., Foundation Check">
            </div>
            <div class="col-md-6 mb-3">
              <label for="inspectionType" class="form-label fw-medium">Inspection Type</label>
              <select class="form-select Input_control" id="inspectionType" name="type" required>
                <option value="">Select Type</option>
                <option value="structural">Structural</option>
                <option value="electrical">Electrical</option>
                <option value="plumbing">Plumbing</option>
                <option value="safety">Safety</option>
                <option value="quality">Quality</option>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="inspectionDate" class="form-label fw-medium">Inspection Date</label>
              <input type="date" class="form-control Input_control" id="inspectionDate" name="date" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="inspector" class="form-label fw-medium">Inspector</label>
              <select class="form-select Input_control" id="inspector" name="inspector">
                <option value="">Select Inspector</option>
                <option value="john_smith">John Smith - Senior Inspector</option>
                <option value="sarah_johnson">Sarah Johnson - Quality Inspector</option>
                <option value="mike_wilson">Mike Wilson - Safety Inspector</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="inspectionDescription" class="form-label fw-medium">Description</label>
            <textarea class="form-control Input_control" id="inspectionDescription" name="description" rows="3" required
              placeholder="Detailed description of what will be inspected..."></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="priority" class="form-label fw-medium">{{ __("messages.priority") }}</label>
              <select class="form-select Input_control" id="priority" name="priority">
                <option value="low">{{ __("messages.low") }}</option>
                <option value="medium" selected>{{ __("messages.medium") }}</option>
                <option value="high">{{ __("messages.high") }}</option>
                <option value="critical">{{ __("messages.critical") }}</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="status" class="form-label fw-medium">Status</label>
              <select class="form-select Input_control" id="status" name="status">
                <option value="scheduled" selected>Scheduled</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">>{{ __("messages.cancel") }}</button>
        <button type="submit" form="createInspectionForm" class="btn orange_btn">
          <i class="fas fa-plus me-2"></i>Create Inspection
        </button>
      </div>
    </div>
  </div>
</div>