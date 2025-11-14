<div class="modal fade" id="taskSelectionModal" tabindex="-1" aria-hidden="true" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #taskSelectionModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #taskSelectionModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title">{{ __('messages.add_new_task') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title">{{ __('messages.add_new_task') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="task-option-card" onclick="openCreateTaskModal()">
                            <i class="fas fa-plus-circle task-option-icon"></i>
                            <h6 class="mb-2">{{ __('messages.create_new_task') }}</h6>
                            <p class="mb-0 small text-muted">{{ __('messages.create_task_from_scratch') }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="task-option-card" onclick="openTaskLibrary()">
                            <i class="fas fa-book task-option-icon"></i>
                            <h6 class="mb-2">{{ __('messages.choose_from_library') }}</h6>
                            <p class="mb-0 small text-muted">{{ __('messages.select_from_existing_tasks') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.task-option-card {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 20px 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
    min-height: 180px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.task-option-card:hover {
    border-color: #F58D2E;
    background-color: #fff8f3;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(245, 141, 46, 0.2);
}
.task-option-icon {
    font-size: 40px;
    color: #F58D2E;
    margin-bottom: 15px;
}
.task-option-card h6 {
    font-weight: 600;
    color: #333;
}
@media (max-width: 767px) {
    .task-option-card {
        min-height: 150px;
        padding: 15px 10px;
    }
    .task-option-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }
}
</style>

<script>
function openCreateTaskModal() {
    const selectionModal = bootstrap.Modal.getInstance(document.getElementById('taskSelectionModal'));
    if (selectionModal) selectionModal.hide();
    
    setTimeout(() => {
        const createModal = new bootstrap.Modal(document.getElementById('addTaskModal'));
        createModal.show();
    }, 300);
}

function openTaskLibrary() {
    const selectionModal = bootstrap.Modal.getInstance(document.getElementById('taskSelectionModal'));
    if (selectionModal) selectionModal.hide();
    
    setTimeout(() => {
        loadTaskLibrary();
        const libraryModal = new bootstrap.Modal(document.getElementById('taskLibraryModal'));
        libraryModal.show();
    }, 300);
}
</script>
