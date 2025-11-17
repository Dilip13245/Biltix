<!-- Add Snag Modal -->
<div class="modal fade" id="addSnagModal" tabindex="-1" aria-labelledby="addSnagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #addSnagModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #addSnagModal .modal-header {
                        position: relative !important;
                    }

                    /* Fix validation icon overlap - remove icon and set proper padding */
                    #addSnagModal .form-control.is-invalid,
                    #addSnagModal .Input_control.is-invalid,
                    #addSnagModal input.is-invalid,
                    #addSnagModal select.is-invalid,
                    #addSnagModal textarea.is-invalid {
                        border-color: #dc3545 !important;
                        background-image: none !important;
                        padding-right: 0.75rem !important;
                        padding-left: 0.75rem !important;
                    }

                    [dir="rtl"] #addSnagModal .form-control.is-invalid,
                    [dir="rtl"] #addSnagModal .Input_control.is-invalid,
                    [dir="rtl"] #addSnagModal input.is-invalid,
                    [dir="rtl"] #addSnagModal select.is-invalid,
                    [dir="rtl"] #addSnagModal textarea.is-invalid {
                        padding-right: 0.75rem !important;
                        padding-left: 0.75rem !important;
                    }

                    /* For select fields - keep dropdown arrow, remove validation icon */
                    #addSnagModal .form-select.is-invalid:not([multiple]):not([size]),
                    #addSnagModal .form-select.is-invalid:not([multiple])[size="1"],
                    #addSnagModal select.is-invalid:not([multiple]):not([size]),
                    #addSnagModal select.is-invalid:not([multiple])[size="1"] {
                        --bs-form-select-bg-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
                        background-position: var(--bs-form-select-bg-position) !important;
                        background-size: 16px 12px !important;
                        padding-right: 3rem !important;
                        border-color: #dc3545 !important;
                    }

                    [dir="rtl"] #addSnagModal .form-select.is-invalid:not([multiple]):not([size]),
                    [dir="rtl"] #addSnagModal .form-select.is-invalid:not([multiple])[size="1"],
                    [dir="rtl"] #addSnagModal select.is-invalid:not([multiple]):not([size]),
                    [dir="rtl"] #addSnagModal select.is-invalid:not([multiple])[size="1"] {
                        --bs-form-select-bg-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
                        padding-right: 0.75rem !important;
                        padding-left: 3rem !important;
                        border-color: #dc3545 !important;
                    }

                    /* Ensure invalid-feedback has proper spacing */
                    #addSnagModal .invalid-feedback {
                        display: block;
                        width: 100%;
                        margin-top: 0.25rem;
                        font-size: 0.875rem;
                        color: #dc3545;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="addSnagModalLabel">
                            {{ __('messages.add_new_snag') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="addSnagModalLabel">
                        {{ __('messages.add_new_snag') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <form id="addSnagForm" class="protected-form" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="categorySelect" class="form-label fw-medium">{{ __('messages.category') }}</label>
                        <select class="form-select Input_control searchable-select" id="categorySelect" name="category"
                            data-placeholder="{{ __('messages.search') }}..."
                            data-no-results="{{ __('messages.no_results_found') }}">
                            <option value="">{{ __('messages.select_category') }}</option>
                            <option value="Category 1">Category 1</option>
                            <option value="Category 2">Category 2</option>
                            <option value="Category 3">Category 3</option>
                            <option value="Category 4">Category 4</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="snagTitle"
                            class="form-label fw-medium"></label>{{ __('messages.snag_title') }}</label>
                        <input type="text" class="form-control Input_control" id="snagTitle" name="title"
                            placeholder="{{ __('messages.enter_snag_title') }}" maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-medium">{{ __('messages.add_description') }}
                            <span class="text-muted">({{ __('messages.optional') }})</span></label>
                        <textarea class="form-control Input_control" id="description" name="description" rows="3"
                            placeholder="{{ __('messages.provide_detailed_description') }}" maxlength="1000"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label fw-medium">{{ __('messages.add_location') }}</label>
                        <input type="text" class="form-control Input_control" id="location" name="location"
                            placeholder="{{ __('messages.location_example') }}" maxlength="200">
                    </div>

                    <div class="mb-3" id="phaseSelectContainer">
                        <label for="phaseSelect" class="form-label fw-medium">{{ __('messages.phase') }}</label>
                        <select class="form-select Input_control searchable-select" id="phaseSelect" name="phase_id"
                            data-placeholder="{{ __('messages.search') }}..."
                            data-no-results="{{ __('messages.no_results_found') }}">
                            <option value="">{{ __('messages.select_phase') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assignedTo" class="form-label fw-medium">{{ __('messages.assign_to') }} <span
                                class="text-muted">({{ __('messages.optional') }})</span></label>
                        <select class="form-select Input_control searchable-select" id="assignedTo"
                            name="assigned_to" data-placeholder="{{ __('messages.search') }}..."
                            data-no-results="{{ __('messages.no_results_found') }}">
                            <option value="">{{ __('messages.select_user') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="snagPhotos" class="form-label fw-medium">{{ __('messages.upload_images') }} <span
                                class="text-muted">({{ __('messages.optional') }})</span></label>
                        <input type="file" class="form-control Input_control" id="snagPhotos" name="photos[]"
                            accept="image/*" multiple>
                        <div class="form-text">{{ __('messages.upload_photos_limit') }}</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn orange_btn api-action-btn" id="createSnagBtn">
                    {{ __('messages.create_snag') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Validation function
    function validateSnagForm() {
        const form = document.getElementById('addSnagForm');
        if (!form) return false;

        let isValid = true;

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        // Validate category
        const categorySelect = form.querySelector('#categorySelect');
        if (!categorySelect.value) {
            showFieldError(categorySelect, '{{ __('messages.category') }} {{ __('messages.is_required') }}');
            isValid = false;
        }

        // Validate title
        const snagTitle = form.querySelector('#snagTitle');
        if (!snagTitle.value.trim()) {
            showFieldError(snagTitle, '{{ __('messages.snag_title') }} {{ __('messages.is_required') }}');
            isValid = false;
        }

        // Validate location
        const location = form.querySelector('#location');
        if (!location.value.trim()) {
            showFieldError(location, '{{ __('messages.add_location') }} {{ __('messages.is_required') }}');
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
            showFieldError(phaseSelect, '{{ __('messages.phase') }} {{ __('messages.is_required') }}');
            isValid = false;
        }

        if (!isValid) {
            showToast('{{ __('messages.please_fill_fields_correctly') }}', 'error');
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
        errorDiv.textContent = message;
        field.parentElement.appendChild(errorDiv);
    }

    // Form submit handler - handle both form submit and button click
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addSnagForm');
        const submitBtn = document.getElementById('createSnagBtn');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (!validateSnagForm()) {
                    return false;
                }
            });
        }

        // Also handle button click
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const form = document.getElementById('addSnagForm');
                if (form && typeof validateSnagForm === 'function') {
                    if (validateSnagForm()) {
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
    document.getElementById('addSnagModal')?.addEventListener('hidden.bs.modal', function() {
        const form = document.getElementById('addSnagForm');
        const btn = document.getElementById('createSnagBtn');

        if (form) {
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        }
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '{{ __('messages.create_snag') }}';
        }
    });

    // Load data when modal is shown
    if (!window.snagModalListenerAdded) {
        window.snagModalListenerAdded = true;
        document.getElementById('addSnagModal')?.addEventListener('shown.bs.modal', async function() {
            const btn = document.getElementById('createSnagBtn');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '{{ __('messages.create_snag') }}';
            }

            // Load data
            if (window.loadUsers) await window.loadUsers();
            if (window.loadPhases) await window.loadPhases();

            // Destroy and recreate dropdowns
            setTimeout(() => {
                ['assignedTo', 'phaseSelect', 'categorySelect'].forEach(id => {
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
                        // Create new instance with translated placeholder
                        if (window.SearchableDropdown) {
                            const placeholder = select.getAttribute('data-placeholder') ||
                                '{{ __('messages.search') }}...';
                            const noResultsText = select.getAttribute('data-no-results') ||
                                '{{ __('messages.no_results_found') }}';
                            select.searchableDropdown = new SearchableDropdown(select, {
                                placeholder: placeholder,
                                noResultsText: noResultsText
                            });
                        }
                    }
                });
            }, 100);
        });
    }
</script>
