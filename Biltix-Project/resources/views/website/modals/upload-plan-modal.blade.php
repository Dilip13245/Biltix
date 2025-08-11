<!-- Upload Plan Modal -->
<div class="modal fade" id="uploadPlanModal" tabindex="-1" aria-labelledby="uploadPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadPlanModalLabel">
          <i class="fas fa-upload me-2"></i>Upload New Plan
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="uploadPlanForm" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="planName" class="form-label fw-medium">Plan Name</label>
              <input type="text" class="form-control Input_control" id="planName" name="plan_name" required
                placeholder="e.g., Ground Floor Plan">
            </div>
            <div class="col-md-6 mb-3">
              <label for="planType" class="form-label fw-medium">Plan Type</label>
              <select class="form-select Input_control" id="planType" name="plan_type" required>
                <option value="">Select Type</option>
                <option value="architectural">Architectural</option>
                <option value="structural">Structural</option>
                <option value="electrical">Electrical</option>
                <option value="plumbing">Plumbing</option>
                <option value="mechanical">Mechanical</option>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="version" class="form-label fw-medium">Version</label>
              <input type="text" class="form-control Input_control" id="version" name="version" 
                placeholder="e.g., 1.0, 2.1">
            </div>
            <div class="col-md-6 mb-3">
              <label for="status" class="form-label fw-medium">Status</label>
              <select class="form-select Input_control" id="status" name="status">
                <option value="draft">Draft</option>
                <option value="review">Under Review</option>
                <option value="approved">Approved</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="planFile" class="form-label fw-medium">Plan File</label>
            <input type="file" class="form-control Input_control" id="planFile" name="plan_file" 
              accept=".pdf,.dwg,.jpg,.jpeg,.png" required>
            <div class="form-text">Supported formats: PDF, DWG, JPG, PNG (Max: 10MB)</div>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label fw-medium">Description</label>
            <textarea class="form-control Input_control" id="description" name="description" rows="3"
              placeholder="Brief description of the plan..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="uploadPlanForm" class="btn orange_btn">
          <i class="fas fa-upload me-2"></i>Upload Plan
        </button>
      </div>
    </div>
  </div>
</div>