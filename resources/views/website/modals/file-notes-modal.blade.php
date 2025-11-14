<!-- File Upload Design Modal (Same as Create Project Step 3) -->
<div class="modal fade" id="fileUploadModal" tabindex="-1" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #fileUploadModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #fileUploadModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="fileUploadModalLabel">
                            {{ __('messages.upload_files') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="fileUploadModalLabel">
                        {{ __('messages.upload_files') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <div class="upload-zone p-4 text-center position-relative"
                        onclick="document.getElementById('fileUploadMultiple').click()">
                        <div class="upload-icon mb-3">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                        </div>
                        <p class="text-muted mb-3">{{ __('messages.drag_drop_or_click') }}</p>
                        <small class="text-muted d-block">{{ __('messages.supported_formats_construction') }}</small>
                        <input type="file" class="d-none" id="fileUploadMultiple"
                            multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.dwg,.jpg,.jpeg,.png,.gif,.txt"
                            onchange="handleMultipleFileSelection(this)">
                        <div id="fileUploadFilesList" class="selected-files mt-3"></div>
                    </div>
                    <div id="fileUploadNotesContainer" class="mt-3" style="display: none;">
                        <label class="form-label fw-medium">{{ __('messages.add_notes_for_files') }}</label>
                        <div id="fileUploadNotesList"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">
                    {{ __('messages.cancel') }}
                </button>
                <button type="button" class="btn orange_btn flex-fill" id="proceedFileUploadBtn" style="padding: 0.7rem 1.5rem;">
                    {{ __('messages.proceed') }}
                </button>
            </div>
        </div>
    </div>
</div>

