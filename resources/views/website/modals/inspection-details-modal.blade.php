<!-- Inspection Details Modal -->
<div class="modal fade" id="inspectionDetailsModal" tabindex="-1" aria-labelledby="inspectionDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #inspectionDetailsModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #inspectionDetailsModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="inspectionDetailsModalLabel">
                            {{ __('messages.inspection_details') }}<i class="fas fa-clipboard-check ms-2"></i>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="inspectionDetailsModalLabel">
                        <i class="fas fa-clipboard-check me-2"></i>{{ __('messages.inspection_details') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column - Inspection Info -->
                    <div class="col-lg-8">
                        <!-- Checklist -->
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ __('messages.checklist') }}</h6>
                                <span class="badge bg-secondary" id="checklist-progress">0/0</span>
                            </div>
                            <div class="card-body">
                                <div id="checklist-container">
                                    <!-- Checklist items will be loaded here -->
                                </div>
                            </div>
                        </div>
                        <!-- Inspection Header -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">{{ __('messages.category') }}</h6>
                                        <p class="mb-2" id="inspection-category"></p>

                                        <h6 class="text-muted mb-1">{{ __('messages.created_date') }}</h6>
                                        <p class="mb-0" id="inspection-date"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">{{ __('messages.status') }}</h6>
                                        <span class="badge mb-2" id="inspection-status"></span>

                                        <h6 class="text-muted mb-1">{{ __('messages.inspector') }}</h6>
                                        <p class="mb-2" id="inspection-inspector"></p>

                                        <h6 class="text-muted mb-1">{{ __('messages.phase') }}</h6>
                                        <p class="mb-0" id="inspection-phase"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('messages.description') }}</h6>
                            </div>
                            <div class="card-body">
                                <p id="inspection-description" class="mb-0"></p>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="card mb-3" id="inspection-images-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ __('messages.images') }}</h6>
                                <button type="button" class="btn btn-sm btn-primary" id="upload-image-btn"
                                    onclick="openDrawingModalForImages()">
                                    <i class="fas fa-plus"></i> {{ __('messages.upload_photo') }}
                                </button>
                            </div>
                            <div class="card-body">
                                <input type="file" id="image-upload-input" accept="image/*" multiple
                                    style="display: none;" onchange="handleImageUpload(this.files)">
                                <div class="row" id="inspection-images-container">
                                    <!-- Images will be loaded here -->
                                </div>
                                <div id="no-images-message" class="text-muted text-center py-3" style="display: none;">
                                    {{ __('messages.no_images_uploaded') }}
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Right Column - Comments & Actions -->
                    <div class="col-lg-4">
                        <!-- Comments Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('messages.comments') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3" id="existing-comment" style="display: none;">
                                    <div class="alert alert-info mb-3">
                                        <strong>{{ __('messages.existing_comment') }}:</strong>
                                        <p class="mb-0 mt-2" id="existing-comment-text"></p>
                                    </div>
                                </div>
                                <div id="comment-input-section">
                                    <div class="mb-3">
                                        <textarea class="form-control" id="inspection-comment" rows="4" placeholder="{{ __('messages.add_comment') }}"></textarea>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="comment-btn"
                                        onclick="updateInspectionComment()">
                                        {{ __('messages.add_comment') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('messages.actions') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-success" id="mark-complete-btn"
                                        onclick="markInspectionComplete()">
                                        <i class="fas fa-check"></i> {{ __('messages.mark_as_completed') }}
                                    </button>
                                    <button type="button" class="btn btn-success" id="completed-btn" disabled
                                        style="display: none;">
                                        <i class="fas fa-check"></i> {{ __('messages.completed') }}
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> {{ __('messages.close') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentInspectionId = null;
    let currentInspectionData = null;

    function openInspectionDetails(inspectionId) {
        currentInspectionId = inspectionId;
        loadInspectionDetails(inspectionId);
        const modal = new bootstrap.Modal(document.getElementById('inspectionDetailsModal'));
        modal.show();
    }

    async function loadInspectionDetails(inspectionId) {
        try {
            const response = await api.getInspectionDetails({
                inspection_id: inspectionId,
                user_id: getUserId()
            });

            if (response.code === 200) {
                currentInspectionData = response.data;
                populateInspectionDetails(response.data);
            } else {
                toastr.error(response.message || '{{ __('messages.failed_load_details') }}');
            }
        } catch (error) {
            console.error('Error loading inspection details:', error);
            toastr.error('{{ __('messages.failed_load_details') }}');
        }
    }

    function populateInspectionDetails(inspection) {
        // Basic info
        document.getElementById('inspection-category').textContent = inspection.category || '-';
        document.getElementById('inspection-date').textContent = formatDate(inspection.created_at);
        document.getElementById('inspection-inspector').textContent = inspection.inspected_by_name || inspection
            .created_by_name || '-';
        document.getElementById('inspection-phase').textContent = inspection.phase_name || '-';
        document.getElementById('inspection-description').textContent = inspection.description || '-';

        // Handle comment display
        handleCommentDisplay(inspection.comment);

        // Status
        const statusBadge = document.getElementById('inspection-status');
        statusBadge.textContent = getStatusText(inspection.status);
        statusBadge.className = `badge ${getStatusClass(inspection.status)}`;

        // Update action buttons based on status
        updateActionButtons(inspection.status);

        // Load images
        loadInspectionImages(inspection.images || []);

        // Load checklist
        loadInspectionChecklist(inspection.checklists || []);
    }

    function loadInspectionImages(images) {
        const imagesCard = document.getElementById('inspection-images-card');
        const imagesContainer = document.getElementById('inspection-images-container');
        const noImagesMessage = document.getElementById('no-images-message');
        const uploadBtn = document.getElementById('upload-image-btn');

        imagesCard.style.display = 'block';
        imagesContainer.innerHTML = '';

        if (images.length === 0) {
            noImagesMessage.style.display = 'block';
        } else {
            noImagesMessage.style.display = 'none';
            images.forEach(image => {
                const imageCol = document.createElement('div');
                imageCol.className = 'col-md-4 mb-3';

                imageCol.innerHTML = `
                <div class="position-relative">
                    <img src="${image.image_url}" class="img-fluid rounded cursor-pointer" 
                         onclick="showImageModal('${image.image_url}')" 
                         style="height: 120px; width: 100%; object-fit: cover;">
                </div>
            `;

                imagesContainer.appendChild(imageCol);
            });
        }

        // Hide upload button if inspection is completed
        if (currentInspectionData && currentInspectionData.status === 'completed') {
            uploadBtn.style.display = 'none';
        } else {
            uploadBtn.style.display = 'block';
        }
    }

    function loadInspectionChecklist(checklist) {
        const checklistContainer = document.getElementById('checklist-container');
        const progressBadge = document.getElementById('checklist-progress');

        if (checklist.length === 0) {
            checklistContainer.innerHTML = '<p class="text-muted mb-0">{{ __('messages.no_checklist_items') }}</p>';
            progressBadge.textContent = '0/0';
            return;
        }

        let completedCount = 0;
        checklistContainer.innerHTML = '';
        const isCompleted = currentInspectionData.status === 'completed';

        checklist.forEach((item, index) => {
            if (item.is_checked) completedCount++;

            const checklistItem = document.createElement('div');
            checklistItem.className = 'form-check mb-2';

            checklistItem.innerHTML = `
            <div class="d-flex align-items-center justify-content-between w-100">
                <label class="form-check-label ${isCompleted ? 'text-muted' : ''} flex-grow-1" for="checklist-${item.id}">
                    ${item.checklist_item}
                </label>
                <div class="flex-grow-1 mx-3" style="border-bottom: 2px dotted #dee2e6;"></div>
                <input class="form-check-input" type="checkbox" 
                       id="checklist-${item.id}" 
                       ${item.is_checked ? 'checked' : ''}
                       ${isCompleted ? 'disabled' : ''}
                       onchange="toggleChecklistItem(${item.id}, this.checked)">
            </div>
        `;

            checklistContainer.appendChild(checklistItem);
        });

        progressBadge.textContent = `${completedCount}/${checklist.length}`;
    }

    async function toggleChecklistItem(checklistId, isChecked) {
        // Prevent interaction if inspection is completed
        if (currentInspectionData.status === 'completed') {
            toastr.warning('{{ __('messages.cannot_modify_completed') }}');
            return;
        }

        try {
            const response = await api.updateInspection({
                user_id: getUserId(),
                inspection_id: currentInspectionId,
                checklist_updates: [{
                    id: checklistId,
                    is_checked: isChecked
                }]
            });

            if (response.code === 200) {
                // Update local data
                const checklistItem = currentInspectionData.checklists.find(item => item.id === checklistId);
                if (checklistItem) {
                    checklistItem.is_checked = isChecked;
                }

                // Update progress
                const completedCount = currentInspectionData.checklists.filter(item => item.is_checked).length;
                document.getElementById('checklist-progress').textContent =
                    `${completedCount}/${currentInspectionData.checklists.length}`;

                const message = response.message === 'api.inspections.updated_success' ?
                    '{{ __('messages.checklist_updated') }}' : response.message ||
                    '{{ __('messages.checklist_updated') }}';
                toastr.success(message);
            } else {
                // Revert checkbox state
                document.getElementById(`checklist-${checklistId}`).checked = !isChecked;
                toastr.error(response.message || '{{ __('messages.failed_update_checklist') }}');
            }
        } catch (error) {
            console.error('Error updating checklist:', error);
            // Revert checkbox state
            document.getElementById(`checklist-${checklistId}`).checked = !isChecked;
            toastr.error('{{ __('messages.failed_update_checklist') }}');
        }
    }

    async function updateInspectionComment() {
        const comment = document.getElementById('inspection-comment').value.trim();

        if (!comment) {
            toastr.warning('{{ __('messages.please_enter_comment') }}');
            return;
        }

        try {
            const response = await api.updateInspection({
                user_id: getUserId(),
                inspection_id: currentInspectionId,
                comment: comment
            });

            if (response.code === 200) {
                currentInspectionData.comment = comment;
                handleCommentDisplay(comment);
                toastr.success(response.message || '{{ __('messages.comment_updated') }}');
            } else {
                toastr.error(response.message || '{{ __('messages.failed_update_comment') }}');
            }
        } catch (error) {
            console.error('Error updating comment:', error);
            toastr.error('{{ __('messages.failed_update_comment') }}');
        }
    }

    async function markInspectionComplete() {
        try {
            const response = await api.completeInspection({
                user_id: getUserId(),
                inspection_id: currentInspectionId
            });

            if (response.code === 200) {
                currentInspectionData.status = 'completed';
                updateActionButtons('completed');

                // Disable all checkboxes
                const checkboxes = document.querySelectorAll('#checklist-container input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.disabled = true;
                });

                // Update checkbox labels to muted
                const labels = document.querySelectorAll('#checklist-container .form-check-label');
                labels.forEach(label => {
                    label.classList.add('text-muted');
                });

                // Update status badge
                const statusBadge = document.getElementById('inspection-status');
                statusBadge.textContent = getStatusText('completed');
                statusBadge.className = `badge ${getStatusClass('completed')}`;

                toastr.success(response.message || '{{ __('messages.inspection_marked_complete') }}');

                // Refresh the inspections list
                if (typeof loadInspections === 'function') {
                    loadInspections();
                }
            } else {
                toastr.error(response.message || '{{ __('messages.failed_mark_complete') }}');
            }
        } catch (error) {
            console.error('Error marking inspection complete:', error);
            toastr.error('{{ __('messages.failed_mark_complete') }}');
        }
    }

    async function markInspectionPending() {
        // This function can be implemented if needed for reopening inspections
        toastr.info('Feature not implemented yet');
    }

    function updateActionButtons(status) {
        const completeBtn = document.getElementById('mark-complete-btn');
        const completedBtn = document.getElementById('completed-btn');
        const commentInputSection = document.getElementById('comment-input-section');
        const uploadBtn = document.getElementById('upload-image-btn');

        if (status === 'completed') {
            completeBtn.style.display = 'none';
            completedBtn.style.display = 'block';
            if (uploadBtn) uploadBtn.style.display = 'none';
            // Disable comment input when completed
            if (commentInputSection) {
                const textarea = document.getElementById('inspection-comment');
                const commentBtn = document.getElementById('comment-btn');
                if (textarea) textarea.disabled = true;
                if (commentBtn) commentBtn.disabled = true;
            }
        } else {
            completeBtn.style.display = 'block';
            completedBtn.style.display = 'none';
            if (uploadBtn) uploadBtn.style.display = 'block';
            // Enable comment input when not completed
            if (commentInputSection) {
                const textarea = document.getElementById('inspection-comment');
                const commentBtn = document.getElementById('comment-btn');
                if (textarea) textarea.disabled = false;
                if (commentBtn) commentBtn.disabled = false;
            }
        }
    }

    function getStatusText(status) {
        const statusTexts = {
            'pending': '{{ __('messages.pending') }}',
            'in_progress': '{{ __('messages.in_progress') }}',
            'completed': '{{ __('messages.completed') }}',
            'failed': '{{ __('messages.failed') }}'
        };
        return statusTexts[status] || status;
    }

    function getStatusClass(status) {
        const statusClasses = {
            'pending': 'bg-warning',
            'in_progress': 'bg-info',
            'completed': 'bg-success',
            'failed': 'bg-danger'
        };
        return statusClasses[status] || 'bg-secondary';
    }

    function showImageModal(imageUrl) {
        // Create a simple image modal
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.image_preview') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="${imageUrl}" class="img-fluid" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    `;

        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        // Remove modal from DOM when hidden
        modal.addEventListener('hidden.bs.modal', () => {
            document.body.removeChild(modal);
        });
    }

    function handleCommentDisplay(comment) {
        const existingCommentDiv = document.getElementById('existing-comment');
        const existingCommentText = document.getElementById('existing-comment-text');
        const commentInputSection = document.getElementById('comment-input-section');
        const commentTextarea = document.getElementById('inspection-comment');

        if (comment && comment.trim()) {
            // Show existing comment and hide input section
            existingCommentText.textContent = comment;
            existingCommentDiv.style.display = 'block';
            commentInputSection.style.display = 'none';
        } else {
            // Hide existing comment and show input section
            existingCommentDiv.style.display = 'none';
            commentInputSection.style.display = 'block';
            commentTextarea.value = '';
        }
    }

    function getUserId() {
        // Get user ID from session storage or default to 1
        return sessionStorage.getItem('user_id') || localStorage.getItem('user_id') || '1';
    }

    function formatDate(dateString) {
        if (!dateString) return '-';

        const date = new Date(dateString);
        return date.toLocaleDateString('{{ app()->getLocale() }}', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function openDrawingModalForImages() {
        if (currentInspectionData.status === 'completed') {
            toastr.warning('{{ __('messages.cannot_upload_completed') }}');
            return;
        }

        // Trigger file input
        document.getElementById('image-upload-input').click();
    }

    function handleImageUpload(files) {
        if (!files || files.length === 0) return;

        // Store files globally for drawing modal
        window.selectedInspectionImages = files;

        // Open drawing modal with proper configuration
        if (typeof openDrawingModal === 'function') {
            openDrawingModal({
                title: 'Markup Inspection Images',
                saveButtonText: 'Upload Images',
                mode: 'image',
                onSave: function(imageData) {
                    uploadInspectionImagesWithDrawing(imageData);
                }
            });

            // Load images after modal is shown
            document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
                if (files.length === 1) {
                    loadImageToCanvas(files[0]);
                } else {
                    loadMultipleFiles(files);
                }
            }, {
                once: true
            });
        } else {
            // Fallback if drawing modal function not available
            uploadInspectionImages(files);
        }
    }

    async function uploadInspectionImages(files) {
        const formData = new FormData();
        formData.append('user_id', getUserId());
        formData.append('inspection_id', currentInspectionId);

        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }

        try {

            const response = await api.updateInspection(formData);

            if (response.code === 200) {
                // Reload inspection details to get updated images
                await loadInspectionDetails(currentInspectionId);
                toastr.success(response.message || '{{ __('messages.images_uploaded_successfully') }}');
            } else {
                toastr.error(response.message || '{{ __('messages.failed_upload_images') }}');
            }
        } catch (error) {
            console.error('Error uploading images:', error);
            toastr.error('{{ __('messages.failed_upload_images') }}');
        }

        // Clear the input
        document.getElementById('image-upload-input').value = '';
    }

    // Upload images with drawing markup
    async function uploadInspectionImagesWithDrawing(imageDataArray) {
        try {
            const formData = new FormData();
            formData.append('user_id', getUserId());
            formData.append('inspection_id', currentInspectionId);

            // Convert image data to blobs and add to FormData
            if (Array.isArray(imageDataArray)) {
                for (let i = 0; i < imageDataArray.length; i++) {
                    const blob = dataURLToBlob(imageDataArray[i]);
                    formData.append('images[]', blob, `inspection_image_${i + 1}.png`);
                }
            } else {
                const blob = dataURLToBlob(imageDataArray);
                formData.append('images[]', blob, 'inspection_image.png');
            }


            const response = await api.updateInspection(formData);

            if (response.code === 200) {
                // Close drawing modal
                const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
                if (drawingModal) drawingModal.hide();

                // Reload inspection details to get updated images
                await loadInspectionDetails(currentInspectionId);
                toastr.success(response.message || '{{ __('messages.images_uploaded_successfully') }}');
            } else {
                toastr.error(response.message || '{{ __('messages.failed_upload_images') }}');
            }
        } catch (error) {
            console.error('Error uploading images:', error);
            toastr.error('{{ __('messages.failed_upload_images') }}');
        }

        // Clear stored files
        window.selectedInspectionImages = null;
        document.getElementById('image-upload-input').value = '';
    }

    // Helper function to convert dataURL to blob
    function dataURLToBlob(dataURL) {
        const arr = dataURL.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {
            type: mime
        });
    }

    function showToast(type, message) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            console.log('Toastr not available, message:', message);
            alert(message);
        }
    }
</script>

<style>
    /* RTL Support */
    [dir="rtl"] .modal-header .btn-close {
        margin-left: auto;
        margin-right: 0;
    }

    [dir="rtl"] .card-header {
        text-align: right;
    }

    [dir="rtl"] .form-check {
        padding-right: 1.5em;
        padding-left: 0;
    }

    [dir="rtl"] .form-check-input {
        margin-right: -1.5em;
        margin-left: 0;
    }

    /* Custom styles */
    .cursor-pointer {
        cursor: pointer;
    }

    .inspection-details-modal .modal-dialog {
        max-width: 1200px;
    }

    .checklist-item {
        transition: all 0.2s ease;
    }

    .checklist-item:hover {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding: 4px;
    }

    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .badge {
        font-size: 0.75em;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .modal-xl {
            max-width: 95%;
            margin: 1rem auto;
        }

        .modal-body {
            padding: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .row>.col-lg-8,
        .row>.col-lg-4 {
            margin-bottom: 1rem;
        }
    }

    /* Loading state */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
</style>
