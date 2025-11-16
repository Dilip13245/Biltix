<!-- Add Role Description Modal -->
<div class="modal fade" id="addRoleDescriptionModal" tabindex="-1" aria-labelledby="addRoleDescriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #addRoleDescriptionModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #addRoleDescriptionModal .modal-header {
                        position: relative !important;
                    }

                    #addRoleDescriptionModal .form-control.is-invalid,
                    #addRoleDescriptionModal .Input_control.is-invalid,
                    #addRoleDescriptionModal textarea.is-invalid {
                        border-color: #dc3545 !important;
                        background-image: none !important;
                        padding-right: 0.75rem !important;
                        padding-left: 0.75rem !important;
                    }

                    [dir="rtl"] #addRoleDescriptionModal .form-control.is-invalid,
                    [dir="rtl"] #addRoleDescriptionModal .Input_control.is-invalid,
                    [dir="rtl"] #addRoleDescriptionModal textarea.is-invalid {
                        padding-right: 0.75rem !important;
                        padding-left: 0.75rem !important;
                    }

                    #addRoleDescriptionModal .invalid-feedback {
                        display: block;
                        width: 100%;
                        margin-top: 0.25rem;
                        font-size: 0.875rem;
                        color: #dc3545;
                    }

                    /* Responsive styles */
                    @media (max-width: 576px) {
                        #addRoleDescriptionModal .modal-dialog {
                            margin: 0.5rem;
                        }
                        #addRoleDescriptionModal .modal-footer {
                            flex-direction: column;
                            gap: 0.5rem;
                        }
                        #addRoleDescriptionModal .modal-footer .btn {
                            width: 100%;
                        }
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="addRoleDescriptionModalLabel">
                            <span id="roleDisplay">{{ __('messages.engineers') }}</span> - {{ __('messages.add_log') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="addRoleDescriptionModalLabel">
                        <span id="roleDisplay">{{ __('messages.engineers') }}</span> - {{ __('messages.add_log') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <form id="addRoleDescriptionForm" class="protected-form" novalidate>
                    @csrf
                    <input type="hidden" id="roleInput" name="role" value="engineer">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium {{ text_align() }}">{{ __('messages.descriptions') }} *</label>
                        <div id="descriptionsContainer">
                            <!-- Description fields will be added here dynamically -->
                        </div>
                        <div id="fieldButtonsContainer" class="mt-2 d-flex gap-2">
                            <!-- Buttons will be added here dynamically -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="submit" form="addRoleDescriptionForm" class="btn orange_btn" style="padding: 0.7rem 1.5rem;">
                    {{ __('messages.add_log') }}
                </button>
            </div>
        </div>
    </div>
</div>

