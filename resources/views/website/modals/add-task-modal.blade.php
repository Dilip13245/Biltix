<!-- {{ __('messages.add_task') }} Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #addTaskModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #addTaskModal .modal-header {
                        position: relative !important;
                    }
                    
                    /* Fix validation icon overlap - remove icon and set proper padding */
                    #addTaskModal .form-control.is-invalid,
                    #addTaskModal .Input_control.is-invalid,
                    #addTaskModal input.is-invalid,
                    #addTaskModal select.is-invalid,
                    #addTaskModal textarea.is-invalid {
                        border-color: #dc3545 !important;
                        background-image: none !important;
                        padding-right: 0.75rem !important;
                        padding-left: 0.75rem !important;
                    }
                    
                    [dir="rtl"] #addTaskModal .form-control.is-invalid,
                    [dir="rtl"] #addTaskModal .Input_control.is-invalid,
                    [dir="rtl"] #addTaskModal input.is-invalid,
                    [dir="rtl"] #addTaskModal select.is-invalid,
                    [dir="rtl"] #addTaskModal textarea.is-invalid {
                        padding-right: 0.75rem !important;
                        padding-left: 0.75rem !important;
                    }
                    
                    /* For select fields - keep dropdown arrow, remove validation icon */
                    #addTaskModal .form-select.is-invalid:not([multiple]):not([size]),
                    #addTaskModal .form-select.is-invalid:not([multiple])[size="1"],
                    #addTaskModal select.is-invalid:not([multiple]):not([size]),
                    #addTaskModal select.is-invalid:not([multiple])[size="1"] {
                        --bs-form-select-bg-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
                        background-position: var(--bs-form-select-bg-position) !important;
                        background-size: 16px 12px !important;
                        padding-right: 3rem !important;
                        border-color: #dc3545 !important;
                    }
                    
                    [dir="rtl"] #addTaskModal .form-select.is-invalid:not([multiple]):not([size]),
                    [dir="rtl"] #addTaskModal .form-select.is-invalid:not([multiple])[size="1"],
                    [dir="rtl"] #addTaskModal select.is-invalid:not([multiple]):not([size]),
                    [dir="rtl"] #addTaskModal select.is-invalid:not([multiple])[size="1"] {
                        --bs-form-select-bg-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
                        padding-right: 0.75rem !important;
                        padding-left: 3rem !important;
                        border-color: #dc3545 !important;
                    }
                    
                    /* Ensure invalid-feedback has proper spacing */
                    #addTaskModal .invalid-feedback {
                        display: block;
                        width: 100%;
                        margin-top: 0.25rem;
                        font-size: 0.875rem;
                        color: #dc3545;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="addTaskModalLabel">
                            {{ __('messages.add_new_task') }}<i class="fas fa-plus ms-2"></i>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="addTaskModalLabel">
                        <i class="fas fa-plus me-2"></i>{{ __('messages.add_new_task') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body">
                <form id="addTaskForm" class="protected-form" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="taskName" class="form-label fw-medium">{{ __('messages.task_name') }}</label>
                        <input type="text" class="form-control Input_control" id="taskName" name="task_name"
                            placeholder="{{ __('messages.enter_task_name') }}" maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label for="taskDescription"
                            class="form-label fw-medium">{{ __('messages.task_description') }}</label>
                        <textarea class="form-control Input_control" id="taskDescription" name="description" rows="3"
                            placeholder="{{ __('messages.brief_task_description') }}" maxlength="500"></textarea>
                    </div>

                    <div class="mb-3" id="phaseSelectContainer">
                        <label for="phaseSelect" class="form-label fw-medium">{{ __('messages.phase') }}</label>
                        <select class="form-select Input_control searchable-select" id="phaseSelect" name="phase_id">
                            <option value="">{{ __('messages.select_phase') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assignedTo" class="form-label fw-medium">{{ __('messages.assign_to') }}</label>
                        <select class="form-select Input_control searchable-select" id="assignedTo" name="assigned_to">
                            <option value="">{{ __('messages.select_user') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="taskPriority" class="form-label fw-medium">{{ __('messages.priority') }}</label>
                        <select class="form-select Input_control searchable-select" id="taskPriority" name="priority">
                            <option value="">{{ __('messages.select_priority') }}</option>
                            <option value="low">{{ __('messages.low') }}</option>
                            <option value="medium">{{ __('messages.medium') }}</option>
                            <option value="high">{{ __('messages.high') }}</option>
                            <option value="critical">{{ __('messages.critical') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dueDate" class="form-label fw-medium">{{ __('messages.due_date') }}</label>
                        @include('website.includes.date-picker', [
                            'id' => 'dueDate',
                            'name' => 'due_date',
                            'placeholder' => __('messages.select_due_date'),
                            'minDate' => date('Y-m-d'),
                            'required' => true,
                        ])
                    </div>

                    <div class="mb-4">
                        <label for="taskImages" class="form-label fw-medium">{{ __('messages.upload_images') }} <span
                                class="text-muted">({{ __('messages.optional') }})</span></label>
                        <input type="file" class="form-control Input_control" id="taskImages" name="images[]"
                            accept="image/*" multiple>
                        <div class="form-text">{{ __('messages.upload_task_images') }}</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn orange_btn api-action-btn" id="addTaskSubmitBtn">
                    {{ __('messages.next') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Validation function
    function validateTaskForm() {
        const form = document.getElementById('addTaskForm');
        if (!form) return false;

        let isValid = true;

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        // Validate task name
        const taskName = form.querySelector('#taskName');
        if (!taskName.value.trim()) {
            showFieldError(taskName, '{{ __('messages.task_name') }} is required');
            isValid = false;
        }

        // Validate task description
        const taskDescription = form.querySelector('#taskDescription');
        if (!taskDescription.value.trim()) {
            showFieldError(taskDescription, '{{ __('messages.task_description') }} is required');
            isValid = false;
        }

        // Validate priority
        const taskPriority = form.querySelector('#taskPriority');
        if (!taskPriority.value) {
            showFieldError(taskPriority, '{{ __('messages.priority') }} is required');
            isValid = false;
        }

        // Validate phase - skip if field is hidden (phase-specific pages)
        const phaseSelect = form.querySelector('#phaseSelect');
        const phaseContainer = form.querySelector('#phaseSelectContainer');
        // Check if phase field is visible (hidden on phase-specific pages)
        let isPhaseFieldVisible = true;
        if (phaseContainer) {
            const inlineStyle = phaseContainer.style.display;
            const computedStyle = window.getComputedStyle(phaseContainer).display;
            isPhaseFieldVisible = inlineStyle !== 'none' && computedStyle !== 'none';
        }

        if (isPhaseFieldVisible && phaseSelect && !phaseSelect.value) {
            showFieldError(phaseSelect, '{{ __('messages.phase') }} is required');
            isValid = false;
        }

        // Validate assigned to
        const assignedTo = form.querySelector('#assignedTo');
        if (!assignedTo.value) {
            showFieldError(assignedTo, '{{ __('messages.assign_to') }} is required');
            isValid = false;
        }

        // Validate due date
        const dueDate = form.querySelector('#dueDate');
        if (!dueDate.value) {
            showFieldError(dueDate, '{{ __('messages.due_date') }} is required');
            isValid = false;
        }

        if (!isValid) {
            showToast('Please fill in all required fields.', 'error');
        }

        return isValid;
    }

    function showToast(message, type) {
        if (typeof toastr !== 'undefined') {
            toastr[type || 'error'](message);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type === 'success' ? 'success' : 'error',
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            alert(message);
        }
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.style.display = 'block';
        errorDiv.style.width = '100%';
        errorDiv.textContent = message;

        // For date picker fields, append after the wrapper (field ke niche)
        if (field.classList.contains('modern-datepicker-input')) {
            const wrapper = field.closest('.modern-datepicker-wrapper');
            if (wrapper) {
                // Remove any existing error message for this field
                const existingError = wrapper.parentElement?.querySelector('.invalid-feedback');
                if (existingError) existingError.remove();

                // Insert error message after the wrapper using insertAdjacentElement
                wrapper.insertAdjacentElement('afterend', errorDiv);
            } else {
                field.parentElement.appendChild(errorDiv);
            }
        } else {
            // Remove any existing error message for this field
            const existingError = field.parentElement?.querySelector('.invalid-feedback');
            if (existingError) existingError.remove();

            field.parentElement.appendChild(errorDiv);
        }
    }

    // Form submit handler - handle both form submit and button click
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addTaskForm');
        const submitBtn = document.getElementById('addTaskSubmitBtn');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (!validateTaskForm()) {
                    return false;
                }
                // If validation passes, allow form submission to continue
                // (the actual submission logic is handled elsewhere)
            });
        }

        // Also handle button click
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const form = document.getElementById('addTaskForm');
                if (form && typeof validateTaskForm === 'function') {
                    if (validateTaskForm()) {
                        // Validation passed, trigger form submit
                        form.dispatchEvent(new Event('submit', {
                            bubbles: true,
                            cancelable: true
                        }));
                    }
                }
            });
        }
    });

    // Reset modal button when modal is hidden
    document.getElementById('addTaskModal')?.addEventListener('hidden.bs.modal', function() {
        const form = document.getElementById('addTaskForm');
        const btn = document.getElementById('addTaskSubmitBtn');

        if (form) {
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        }
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '{{ __('messages.next') }}';
        }
    });

    // Load data when modal is shown
    if (!window.taskModalListenerAdded) {
        window.taskModalListenerAdded = true;
        document.getElementById('addTaskModal')?.addEventListener('shown.bs.modal', async function() {
            const btn = document.getElementById('addTaskSubmitBtn');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '{{ __('messages.next') }}';
            }
            
            // Load data
            console.log('Loading users and phases...');
            if (window.loadUsers) {
                await window.loadUsers();
                console.log('Users loaded');
            }
            if (window.loadPhases) {
                await window.loadPhases();
                console.log('Phases loaded');
            }
            
            // Destroy and recreate dropdowns
            setTimeout(() => {
                ['assignedTo', 'phaseSelect', 'taskPriority'].forEach(id => {
                    const select = document.getElementById(id);
                    if (select) {
                        // Remove old wrapper
                        const wrapper = select.closest('.searchable-dropdown');
                        if (wrapper) {
                            const parent = wrapper.parentElement;
                            parent.insertBefore(select, wrapper);
                            wrapper.remove();
                            select.style.display = '';
                        }
                        // Create new instance
                        if (window.SearchableDropdown) {
                            select.searchableDropdown = new SearchableDropdown(select);
                        }
                    }
                });
                console.log('Dropdowns recreated');
            }, 100);
        });
    }
</script>
