<!-- {{ __('messages.task_details') }} Modal -->
<div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #taskDetailsModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #taskDetailsModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="taskDetailsModalLabel">
                            {{ __('messages.task_details') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="taskDetailsModalLabel">
                        {{ __('messages.task_details') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <!-- Task Header -->
                <div class="card B_shadow mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 id="taskDetailTitle" class="fw-bold black_color mb-2">{{ __('messages.task_title') }}</h4>
                                <div class="d-flex gap-3 flex-wrap mb-2">
                                    <span id="taskDetailStatus" class="badge badge3">{{ __('messages.todo') }}</span>
                                    <small class="text-muted"><i class="fas fa-hashtag me-1"></i><span id="taskDetailNumber">-</span></small>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <select class="form-select w-auto d-inline-block" id="taskStatusSelect" onchange="changeTaskStatus()">
                                    <option value="todo">{{ __('messages.todo') }}</option>
                                    <option value="in_progress">{{ __('messages.in_progress') }}</option>
                                    <option value="complete">{{ __('messages.complete') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Details -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card B_shadow h-100">
                            <div class="card-body">
                                <h6 class="fw-semibold black_color mb-3"><i class="fas fa-info-circle orange_color me-2"></i>{{ __('messages.details') }}</h6>
                                <div class="mb-3">
                                    <label class="small_tXt fw-medium">{{ __('messages.description') }}</label>
                                    <p id="taskDetailDescription" class="mb-0">Task description will appear here...</p>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="small_tXt fw-medium">{{ __('messages.start_date') }}</label>
                                        <p id="taskDetailStartDate" class="mb-0">-</p>
                                    </div>
                                    <div class="col-6">
                                        <label class="small_tXt fw-medium">{{ __('messages.due_date') }}</label>
                                        <p id="taskDetailDueDate" class="mb-0">-</p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <label class="small_tXt fw-medium">{{ __('messages.assigned_to') }}</label>
                                        <p id="taskDetailAssignedTo" class="mb-0"><i class="fas fa-user-check me-1 text-primary"></i>-</p>
                                    </div>
                                    <div class="col-6">
                                        <label class="small_tXt fw-medium">{{ __('messages.priority') }}</label>
                                        <p id="taskDetailPriority" class="mb-0">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card B_shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-semibold black_color mb-0"><i class="fas fa-images orange_color me-2"></i>{{ __('messages.images') }}</h6>
                                    <button type="button" class="btn btn-sm orange_btn api-action-btn" id="addImagesBtn" onclick="addNewImages()" title="{{ __('messages.add_new_images') }}">
                                        {{ __('messages.add_image') }}
                                    </button>
                                </div>
                                <div id="taskImagesContainer" class="row g-2">
                                    <!-- Images will be loaded here -->
                                </div>
                                <input type="file" class="d-none" id="additionalImages" accept="image/*" multiple>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                @can('tasks', 'approve')
                <button class="btn btn-success api-action-btn" onclick="markAsResolved()" id="resolveBtn">
                    {{ __('messages.mark_resolved') }}
                </button>
                @endcan
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
