<!-- Add Snag Modal -->
<div class="modal fade" id="addSnagModal" tabindex="-1" aria-labelledby="addSnagModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSnagModalLabel">
          <i class="fas fa-exclamation-triangle me-2"></i>Add New Snag
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSnagForm" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-8 mb-3">
              <label for="snagTitle" class="form-label fw-medium">Snag Title</label>
              <input type="text" class="form-control Input_control" id="snagTitle" name="title" required
                placeholder="Brief description of the issue">
            </div>
            <div class="col-md-4 mb-3">
              <label for="priority" class="form-label fw-medium">Priority</label>
              <select class="form-select Input_control" id="priority" name="priority" required>
                <option value="low">Low Priority</option>
                <option value="medium" selected>Medium Priority</option>
                <option value="high">High Priority</option>
                <option value="critical">Critical</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label fw-medium">Detailed Description</label>
            <textarea class="form-control Input_control" id="description" name="description" rows="4" required
              placeholder="Provide detailed description of the snag/issue..."></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="category" class="form-label fw-medium">Category</label>
              <select class="form-select Input_control" id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="electrical">Electrical</option>
                <option value="plumbing">Plumbing</option>
                <option value="structural">Structural</option>
                <option value="finishing">Finishing</option>
                <option value="safety">Safety</option>
                <option value="hvac">HVAC</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="location" class="form-label fw-medium">Location</label>
              <input type="text" class="form-control Input_control" id="location" name="location" required
                placeholder="e.g., Building A, Floor 2, Room 205">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="reportedBy" class="form-label fw-medium">Reported By</label>
              <input type="text" class="form-control Input_control" id="reportedBy" name="reported_by" required
                placeholder="Name of person reporting">
            </div>
            <div class="col-md-6 mb-3">
              <label for="dateReported" class="form-label fw-medium">Date Reported</label>
              <input type="date" class="form-control Input_control" id="dateReported" name="date_reported" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="assignedTo" class="form-label fw-medium">Assign To</label>
              <select class="form-select Input_control" id="assignedTo" name="assigned_to">
                <option value="">Select Team Member</option>
                <option value="john_smith">John Smith - Supervisor</option>
                <option value="sarah_johnson">Sarah Johnson - Engineer</option>
                <option value="mike_wilson">Mike Wilson - Technician</option>
                <option value="lisa_brown">Lisa Brown - Quality Inspector</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="targetDate" class="form-label fw-medium">Target Completion Date</label>
              <input type="date" class="form-control Input_control" id="targetDate" name="target_date">
            </div>
          </div>

          <div class="mb-3">
            <label for="snagPhotos" class="form-label fw-medium">Photos (Optional)</label>
            <input type="file" class="form-control Input_control" id="snagPhotos" name="photos[]" 
              accept="image/*" multiple>
            <div class="form-text">Upload photos showing the issue (Max: 5 photos, 5MB each)</div>
          </div>

          <div class="mb-3">
            <label for="status" class="form-label fw-medium">Initial Status</label>
            <select class="form-select Input_control" id="status" name="status">
              <option value="open" selected>Open</option>
              <option value="in_progress">In Progress</option>
              <option value="pending_review">Pending Review</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="addSnagForm" class="btn orange_btn">
          <i class="fas fa-plus me-2"></i>Add Snag
        </button>
      </div>
    </div>
  </div>
</div>