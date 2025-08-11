<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel">
          <i class="fas fa-plus me-2"></i>Add New Task
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addTaskForm">
          @csrf
          <div class="row">
            <div class="col-md-8 mb-3">
              <label for="taskTitle" class="form-label fw-medium">Task Title</label>
              <input type="text" class="form-control Input_control" id="taskTitle" name="title" required
                placeholder="e.g., Pour Concrete Slab">
            </div>
            <div class="col-md-4 mb-3">
              <label for="priority" class="form-label fw-medium">Priority</label>
              <select class="form-select Input_control" id="priority" name="priority" required>
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="startDate" class="form-label fw-medium">Start Date</label>
              <input type="date" class="form-control Input_control" id="startDate" name="start_date" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="dueDate" class="form-label fw-medium">Due Date</label>
              <input type="date" class="form-control Input_control" id="dueDate" name="due_date" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="assignedTo" class="form-label fw-medium">Assigned To</label>
              <select class="form-select Input_control" id="assignedTo" name="assigned_to">
                <option value="">Select Team Member</option>
                <option value="1">John Smith - Project Manager</option>
                <option value="2">Sarah Johnson - Engineer</option>
                <option value="3">Mike Wilson - Supervisor</option>
                <option value="4">Lisa Brown - Quality Inspector</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="taskStatus" class="form-label fw-medium">Status</label>
              <select class="form-select Input_control" id="taskStatus" name="status">
                <option value="pending" selected>Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="on_hold">On Hold</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="taskDescription" class="form-label fw-medium">Description</label>
            <textarea class="form-control Input_control" id="taskDescription" name="description" rows="4" required
              placeholder="Detailed description of the task..."></textarea>
          </div>

          <div class="mb-3">
            <label for="progress" class="form-label fw-medium">Progress (%)</label>
            <input type="range" class="form-range" id="progress" name="progress" min="0" max="100" value="0">
            <div class="d-flex justify-content-between">
              <span>0%</span>
              <span id="progressValue">0%</span>
              <span>100%</span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="addTaskForm" class="btn orange_btn">
          <i class="fas fa-plus me-2"></i>Add Task
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const progressSlider = document.getElementById('progress');
  const progressValue = document.getElementById('progressValue');
  
  progressSlider.addEventListener('input', function() {
    progressValue.textContent = this.value + '%';
  });
});
</script>