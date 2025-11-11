<div class="modal fade" id="taskLibraryModal" tabindex="-1" aria-hidden="true"
    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.task_library') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="search" class="form-control" id="librarySearchInput"
                            placeholder="{{ __('messages.search_tasks') }}" />
                    </div>
                </div>
                <div id="taskLibraryList" class="task-library-list">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">{{ __('messages.loading') }}...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .task-library-list {
        max-height: 600px;
        overflow-y: auto;
    }

    .task-library-item {
        transition: all 0.2s ease;
        border: 1px solid #e0e0e0;
    }

    .task-library-item:hover {
        border-color: #F58D2E;
        background-color: #fff8f3;
    }

    .task-library-item h6 {
        color: #333;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .task-library-list {
            max-height: 500px;
        }

        #taskLibraryModal .modal-dialog {
            margin: 1rem auto;
        }
    }
</style>

<script>
    async function loadTaskLibrary() {
        try {
            const response = await api.getTasks({
                user_id: currentUserId,
                project_id: currentProjectId
            });

            if (response.code === 200) {
                const tasks = response.data.data || [];
                renderTaskLibrary(tasks);
            }
        } catch (error) {
            console.error('Error loading task library:', error);
            document.getElementById('taskLibraryList').innerHTML =
                '<p class="text-center text-danger">{{ __('messages.failed_to_load_tasks') }}</p>';
        }
    }

    function renderTaskLibrary(tasks) {
        const container = document.getElementById('taskLibraryList');

        if (!tasks || tasks.length === 0) {
            container.innerHTML =
                '<div class="text-center py-4"><i class="fas fa-inbox fa-3x text-muted mb-3"></i><p class="text-muted">{{ __('messages.no_tasks_found') }}</p></div>';
            return;
        }

        const html = tasks.map(task => `
        <div class="card mb-2 task-library-item" style="cursor: pointer;" onclick="selectTaskFromLibrary(${task.id})">
            <div class="card-body p-3">
                <div class="d-flex align-items-start">
                    <i class="fas fa-tasks text-primary me-3 mt-1" style="font-size: 20px;"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${task.title}</h6>
                        <p class="mb-0 small text-muted">${task.description || '{{ __('messages.no_description') }}'}</p>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} text-muted"></i>
                </div>
            </div>
        </div>
    `).join('');

        container.innerHTML = html;

        // Search functionality
        document.getElementById('librarySearchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.task-library-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    async function selectTaskFromLibrary(taskId) {
        try {
            const response = await api.getTaskDetails({
                task_id: taskId,
                user_id: currentUserId
            });

            if (response.code === 200) {
                const task = response.data;

                // Close library modal
                const libraryModal = bootstrap.Modal.getInstance(document.getElementById('taskLibraryModal'));
                if (libraryModal) libraryModal.hide();

                // Open create task modal with pre-filled data
                setTimeout(() => {
                    document.getElementById('taskName').value = task.title;
                    document.getElementById('taskDescription').value = task.description || '';

                    const createModal = new bootstrap.Modal(document.getElementById('addTaskModal'));
                    createModal.show();
                }, 300);
            }
        } catch (error) {
            console.error('Error loading task details:', error);
            toastr.error('{{ __('messages.failed_to_load_task') }}');
        }
    }
</script>
