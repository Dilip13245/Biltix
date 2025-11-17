<!-- Add Photo Modal -->
<div class="modal fade" id="addPhotoModal" tabindex="-1" aria-labelledby="addPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #addPhotoModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #addPhotoModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="addPhotoModalLabel">
                            {{ __('messages.add_new') }} Photos
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="addPhotoModalLabel">
                        {{ __('messages.add_new') }} Photos
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <form id="addPhotoForm" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="mb-4">
                        <label for="photoFiles" class="form-label fw-medium">{{ __('messages.select_photos') }}</label>
                        <input type="file" class="form-control Input_control" id="photoFiles" name="photos[]"
                            accept="image/*" multiple>
                        <div class="form-text">You can select multiple photos. Supported formats: JPG, PNG, GIF (Max:
                            5MB each)</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="photoCategory" class="form-label fw-medium">{{ __('messages.category') }}</label>
                            <select class="form-select Input_control" id="photoCategory" name="category">
                                <option value="">{{ __('messages.select_category') }}</option>
                                <option value="foundation">{{ __('messages.category_foundation') }}</option>
                                <option value="structure">{{ __('messages.category_structure') }}</option>
                                <option value="finishing">{{ __('messages.category_finishing') }}</option>
                                <option value="safety">{{ __('messages.category_safety') }}</option>
                                <option value="equipment">{{ __('messages.category_equipment') }}</option>
                                <option value="progress">{{ __('messages.category_progress') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="photoDate" class="form-label fw-medium">{{ __('messages.date_taken') }}</label>
                            @include('website.includes.date-picker', [
                                'id' => 'photoDate',
                                'name' => 'date_taken',
                                'placeholder' => __('messages.select_photo_date'),
                                'value' => date('Y-m-d'),
                                'maxDate' => date('Y-m-d'),
                                'required' => true,
                            ])
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photoDescription" class="form-label fw-medium">{{ __('messages.description') }}</label>
                        <textarea class="form-control Input_control" id="photoDescription" name="description" rows="3"
                            placeholder="{{ __('messages.brief_description_photos') }}"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="photographer" class="form-label fw-medium">{{ __('messages.photographer') }}</label>
                        <input type="text" class="form-control Input_control" id="photographer" name="photographer"
                            placeholder="{{ __('messages.name_of_photographer') }}">
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label fw-medium">{{ __('messages.location_area') }}</label>
                        <input type="text" class="form-control Input_control" id="location" name="location"
                            placeholder="{{ __('messages.location_example_zone') }}">
                    </div>

                    <!-- Photo Preview Area -->
                    <div id="photoPreview" class="mt-3" style="display: none;">
                        <h6 class="fw-medium mb-3">{{ __('messages.photo_preview') }}</h6>
                        <div id="previewContainer" class="row g-3"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn orange_btn api-action-btn" id="addPhotoBtn">
                    {{ __('messages.upload_photo') }}s
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photoFiles');
        const previewArea = document.getElementById('photoPreview');
        const previewContainer = document.getElementById('previewContainer');

        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const files = e.target.files;
                previewContainer.innerHTML = '';

                if (files.length > 0) {
                    previewArea.style.display = 'block';

                    Array.from(files).forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewHTML = `
                <div class="col-md-3">
                  <div class="card">
                    <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;">
                    <div class="card-body p-2">
                      <small class="text-muted">${file.name}</small>
                    </div>
                  </div>
                </div>
              `;
                                previewContainer.insertAdjacentHTML('beforeend',
                                    previewHTML);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                } else {
                    previewArea.style.display = 'none';
                }
            });
        }

        // Validation function
        function validatePhotoForm() {
            const form = document.getElementById('addPhotoForm');
            if (!form) return false;

            let isValid = true;

            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            // Validate photos
            const photoFiles = form.querySelector('#photoFiles');
            if (!photoFiles.files || photoFiles.files.length === 0) {
                showFieldError(photoFiles, '{{ __('messages.please_select_at_least_one_photo') }}');
                isValid = false;
            }

            // Validate category
            const photoCategory = form.querySelector('#photoCategory');
            if (!photoCategory.value) {
                showFieldError(photoCategory, '{{ __('messages.category_is_required') }}');
                isValid = false;
            }

            // Validate date
            const photoDate = form.querySelector('#photoDate');
            if (!photoDate.value) {
                showFieldError(photoDate, '{{ __('messages.date_taken_is_required') }}');
                isValid = false;
            }

            if (!isValid) {
                if (typeof showToast !== 'undefined') {
                    showToast('{{ __('messages.please_fill_required_fields') }}', 'error');
                } else if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __('messages.please_fill_required_fields') }}');
                } else {
                    alert('{{ __('messages.please_fill_required_fields') }}');
                }
            }

            return isValid;
        }

        function showFieldError(field, message) {
            field.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            field.parentElement.appendChild(errorDiv);
        }

        // Form submit handler - handle both form submit and button click
        const addPhotoForm = document.getElementById('addPhotoForm');
        const submitBtn = document.getElementById('addPhotoBtn');

        if (addPhotoForm) {
            addPhotoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (!validatePhotoForm()) {
                    return false;
                }
            });
        }

        // Also handle button click
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const form = document.getElementById('addPhotoForm');
                if (form && typeof validatePhotoForm === 'function') {
                    if (validatePhotoForm()) {
                        // Validation passed, trigger form submit
                        form.dispatchEvent(new Event('submit', {
                            bubbles: true,
                            cancelable: true
                        }));
                        // Button protection
                        if (!this.disabled) {
                            this.disabled = true;
                            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                            setTimeout(() => {
                                this.disabled = false;
                                this.innerHTML = '{{ __('messages.upload_photo') }}s';
                            }, 5000);
                        }
                    }
                }
            });
        }
    });
</script>
