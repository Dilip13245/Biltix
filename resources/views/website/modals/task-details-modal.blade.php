<!-- {{ __("messages.task_details") }} Modal -->
<div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="taskDetailsModalLabel">
          <i class="fas fa-tasks {{ rtl_helper()->marginEnd(2) }}"></i>{{ __("messages.task_details") }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <h4 id="taskDetailTitle" class="fw-bold mb-3">Task Title</h4>
            <p id="taskDetailDescription" class="text-muted mb-4">Task description will appear here...</p>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <strong>Start Date:</strong>
                <p id="taskDetailStartDate" class="mb-2">-</p>
              </div>
              <div class="col-md-6">
                <strong>Due Date:</strong>
                <p id="taskDetailDueDate" class="mb-2">-</p>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <strong>Assigned To:</strong>
                <p id="taskDetailAssignedTo" class="mb-2">-</p>
              </div>
              <div class="col-md-6">
                <strong>{{ __("messages.priority") }}:</strong>
                <span id="taskDetail{{ __("messages.priority") }}" class="badge">-</span>
              </div>
            </div>

            <div class="mb-3">
              <strong>Progress:</strong>
              <div class="progress mt-2" style="height: 8px;">
                <div id="taskDetailProgressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
              </div>
              <small id="taskDetailProgressText" class="text-muted">0% Complete</small>
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="card bg-light">
              <div class="card-body">
                <h6 class="card-title">Status</h6>
                <span id="taskDetailStatus" class="badge badge2 mb-3">{{ __('messages.pending') }}</span>
                
                <h6 class="card-title mt-3">Actions</h6>
                <div class="d-grid gap-2">
                  <button class="btn btn-sm btn-primary" onclick="editTask()">
                    <i class="fas fa-edit {{ rtl_helper()->marginEnd(1) }}"></i>Edit Task
                  </button>
                  <button class="btn btn-sm btn-success" onclick="markComplete()">
                    <i class="fas fa-check {{ rtl_helper()->marginEnd(1) }}"></i>Mark Complete
                  </button>
                  <button class="btn btn-sm btn-warning" onclick="addComment()">
                    <i class="fas fa-comment {{ rtl_helper()->marginEnd(1) }}"></i>Add Comment
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Comments Section -->
        <div class="mt-4">
          <h6 class="fw-bold mb-3">Comments & Updates</h6>
          <div id="taskComments">
            <div class="d-flex mb-3">
              <img src="{{ asset('website/images/icons/avtar.svg') }}" class="rounded-circle {{ rtl_helper()->marginEnd(3) }}" width="40" height="40">
              <div class="flex-grow-1">
                <div class="bg-light p-3 rounded">
                  <strong>John Smith</strong>
                  <small class="text-muted {{ rtl_helper()->marginStart(2) }}">2 hours ago</small>
                  <p class="mb-0 mt-1">Task has been reviewed and approved for execution.</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Add Comment Form -->
          <div class="mt-3">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Add a comment..." id="newComment">
              <button class="btn orange_btn" type="button" onclick="submitComment()">
                <i class="fas fa-paper-plane"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("messages.close") }}</button>
        <button type="button" class="btn orange_btn" onclick="editTask()">
          <i class="fas fa-edit {{ rtl_helper()->marginEnd(2) }}"></i>Edit Task
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function editTask() {
  // Close details modal and open edit modal
  $('#taskDetailsModal').modal('hide');
  $('#addTaskModal').modal('show');
}

function markComplete() {
  // Update task status to completed
  document.getElementById('taskDetailStatus').textContent = 'Completed';
  document.getElementById('taskDetailStatus').className = 'badge badge1 mb-3';
  document.getElementById('taskDetailProgressBar').style.width = '100%';
  document.getElementById('taskDetailProgressText').textContent = '100% Complete';
}

function addComment() {
  document.getElementById('newComment').focus();
}

function submitComment() {
  const comment = document.getElementById('newComment').value;
  if (comment.trim()) {
    // Add comment to the list (this would normally be an AJAX call)
    const commentsDiv = document.getElementById('taskComments');
    const newCommentHTML = `
      <div class="d-flex mb-3">
        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="rounded-circle me-3" width="40" height="40">
        <div class="flex-grow-1">
          <div class="bg-light p-3 rounded">
            <strong>You</strong>
            <small class="text-muted">Just now</small>
            <p class="mb-0 mt-1">${comment}</p>
          </div>
        </div>
      </div>
    `;
    commentsDiv.insertAdjacentHTML('beforeend', newCommentHTML);
    document.getElementById('newComment').value = '';
  }
}
</script>