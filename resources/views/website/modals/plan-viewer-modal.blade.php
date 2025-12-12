<!-- Plan Viewer Modal -->
<div class="modal fade" id="planViewerModal" tabindex="-1" aria-labelledby="planViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="padding: 1rem 1.5rem;">
                <style>
                    #planViewerModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #planViewerModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title mb-0" id="planViewerModalLabel">
                            {{ __('messages.plan_viewer') }}
                        </h5>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Zoom Controls -->
                            <div class="btn-group btn-group-sm d-none d-md-flex" role="group">
                                <button class="btn btn-outline-primary" onclick="zoomIn()">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button class="btn btn-outline-primary" onclick="zoomOut()">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button class="btn btn-outline-primary" onclick="resetZoom()">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                </button>
                            </div>

                            <!-- Mobile Zoom Controls -->
                            <div class="d-flex d-md-none gap-1">
                                <button class="btn btn-sm btn-outline-primary" onclick="zoomIn()">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="zoomOut()">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="resetZoom()">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                </button>
                            </div>

                            <!-- Download Button -->
                            <button class="btn btn-sm btn-outline-primary ms-3 api-action-btn" onclick="downloadPlan()" title="{{ __('messages.download') }}">
                                <i class="fas fa-download"></i>
                            </button>

                            <!-- Close Button -->
                            <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"
                                style="position: static !important;"></button>
                        </div>
                    </div>
                @else
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title mb-0" id="planViewerModalLabel">
                            {{ __('messages.plan_viewer') }}
                        </h5>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Zoom Controls -->
                            <div class="btn-group btn-group-sm d-none d-md-flex" role="group">
                                <button class="btn btn-outline-primary" onclick="zoomIn()">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button class="btn btn-outline-primary" onclick="zoomOut()">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button class="btn btn-outline-primary" onclick="resetZoom()">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                </button>
                            </div>

                            <!-- Mobile Zoom Controls -->
                            <div class="d-flex d-md-none gap-1">
                                <button class="btn btn-sm btn-outline-primary" onclick="zoomIn()">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="zoomOut()">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="resetZoom()">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                </button>
                            </div>

                            <!-- Download Button -->
                            <button class="btn btn-sm btn-outline-primary me-3 api-action-btn" onclick="downloadPlan()" title="{{ __('messages.download') }}">
                                <i class="fas fa-download"></i>
                            </button>

                            <!-- Close Button -->
                            <button type="button" class="btn-close me-2" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"
                                style="position: static !important;"></button>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-body p-0">
                <div class="row h-100">
                    <div class="col-md-9 p-0 @if (app()->getLocale() == 'ar') order-2 @else order-1 @endif">
                        <div id="planViewerContainer"
                            class="h-100 position-relative overflow-auto bg-light d-flex align-items-center justify-content-center">
                            <!-- Content will be dynamically loaded here -->
                            <div id="planContent" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                    <div class="col-md-3 @if (app()->getLocale() == 'ar') border-end order-1 @else border-start order-2 @endif" @if (app()->getLocale() == 'ar') style="text-align: right !important; direction: rtl !important;" @endif>
                        <div class="p-3" @if (app()->getLocale() == 'ar') style="text-align: right !important; direction: rtl !important;" @endif>
                            <h6 class="fw-bold mb-3">{{ __('messages.plan_information') }}</h6>

                            <div class="mb-3">
                                <strong>{{ __('messages.name') }}:</strong>
                                <p id="planInfoName" class="mb-2">-</p>
                            </div>



                            <div class="mb-3">
                                <strong>{{ __('messages.file_type') }}:</strong>
                                <p id="planInfoFileType" class="mb-2">-</p>
                            </div>

                            <div class="mb-3">
                                <strong>{{ __('messages.file_size') }}:</strong>
                                <p id="planInfoSize" class="mb-2">-</p>
                            </div>

                            <div class="mb-3">
                                <strong>{{ __('messages.upload_date') }}:</strong>
                                <p id="planInfoUpdated" class="mb-2">-</p>
                            </div>

                            <!-- Tools Section - Commented Out
              <hr>
              
              <h6 class="fw-bold mb-3">{{ __('messages.tools') }}</h6>
              <div class="d-grid gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="addAnnotation()">
                  <i class="fas fa-sticky-note me-1"></i>{{ __('messages.add_note') }}
                </button>
                <button class="btn btn-sm btn-outline-primary" onclick="measureTool()">
                  <i class="fas fa-ruler me-1"></i>{{ __('messages.measure') }}
                </button>
                <button class="btn btn-sm btn-outline-primary" onclick="printPlan()">
                  <i class="fas fa-print me-1"></i>{{ __('messages.print') }}
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="reportIssue()">
                  <i class="fas fa-exclamation-triangle me-1"></i>{{ __('messages.report_issue') }}
                </button>
              </div>

              <hr>
              
              <h6 class="fw-bold mb-3">{{ __('messages.recent_comments') }}</h6>
              <div id="planComments">
                <div class="text-muted text-center py-3">
                  <i class="fas fa-comments fa-2x mb-2 d-block"></i>
                  {{ __('messages.no_comments_yet') }}
                </div>
              </div>
              
              <div class="mt-3">
                <textarea class="form-control form-control-sm" rows="2"
                    placeholder="{{ __('messages.add_comment_placeholder') }}" id="planComment"></textarea>
                <button class="btn btn-sm orange_btn mt-2 w-100 api-action-btn" onclick="addPlanComment()">
                  <i class="fas fa-comment me-1"></i>{{ __('messages.add_comment') }}
                </button>
              </div>
              -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;
    let currentPlanData = null;

    function loadPlanContent(planData) {
        currentPlanData = planData;
        currentZoom = 1;
        const container = document.getElementById('planContent');
        const fileType = planData.file_type?.toLowerCase();

        // Reset container
        container.innerHTML = '';
        container.style.overflow = 'auto';

        if (isImageFile(fileType)) {
            // Image files
            container.innerHTML =
                `<img id="planImage" src="${planData.file_path}" alt="${planData.title}" style="cursor: grab; transition: transform 0.3s; width: 100%; height: 100%; object-fit: contain; display: block;">`;
            setupImageInteractions();
        } else if (fileType?.includes('pdf')) {
            // PDF files - better viewer
            container.innerHTML = `
      <div style="width: 100%; height: 100%; min-height: 600px;">
        <iframe id="pdfViewer" src="${planData.file_path}#toolbar=1&navpanes=1&scrollbar=1&view=FitH" 
                style="width: 100%; height: 100%; border: none; min-height: 600px;"></iframe>
      </div>
    `;
        } else if (fileType?.includes('word') || fileType?.includes('doc')) {
            // Word documents - Office viewer
            const officeUrl =
                `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(planData.file_path)}`;
            container.innerHTML = `
      <div style="width: 100%; height: 100%; min-height: 600px;">
        <iframe src="${officeUrl}" style="width: 100%; height: 100%; border: none; min-height: 600px;"></iframe>
      </div>
    `;
        } else if (fileType?.includes('excel') || fileType?.includes('sheet')) {
            // Excel files - Office viewer
            const officeUrl =
                `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(planData.file_path)}`;
            container.innerHTML = `
      <div style="width: 100%; height: 100%; min-height: 600px;">
        <iframe src="${officeUrl}" style="width: 100%; height: 100%; border: none; min-height: 600px;"></iframe>
      </div>
    `;
        } else if (fileType?.includes('text') || fileType?.includes('txt')) {
            // Text files
            fetch(planData.file_path)
                .then(response => response.text())
                .then(text => {
                    container.innerHTML = `
          <div class="p-4" style="background: #f8f9fa; height: 100%; overflow-y: auto;">
            <pre style="white-space: pre-wrap; font-family: monospace; font-size: 14px;">${text}</pre>
          </div>
        `;
                })
                .catch(() => {
                    showFileIcon(container, planData);
                });
        } else {
            // Other files - show icon and download link
            showFileIcon(container, planData);
        }
    }

    function isImageFile(fileType) {
        return fileType?.includes('image') || fileType?.includes('jpg') || fileType?.includes('jpeg') || fileType
            ?.includes('png') || fileType?.includes('gif') || fileType?.includes('bmp') || fileType?.includes('svg');
    }

    function showFileIcon(container, planData) {
        container.innerHTML = `
    <div class="text-center p-5 d-flex flex-column justify-content-center align-items-center" style="height: 100%; min-height: 400px;">
      <i class="${getFileIcon(planData.file_type)} fa-5x text-primary mb-3"></i>
      <h5>${planData.title}</h5>
      <p class="text-muted mb-3">${planData.file_type?.toUpperCase()} {{ __('messages.file') }}</p>
      <div class="d-flex gap-2">
        <a href="${planData.file_path}" target="_blank" class="btn btn-primary">
          <i class="fas fa-external-link-alt me-2"></i>{{ __('messages.open_file') }}
        </a>
        <a href="${planData.file_path}" download class="btn btn-outline-primary">
          <i class="fas fa-download me-2"></i>{{ __('messages.download') }}
        </a>
      </div>
    </div>
  `;
    }

    function getFileIcon(fileType) {
        const type = fileType?.toLowerCase();
        if (type?.includes('pdf')) return 'fas fa-file-pdf';
        if (type?.includes('dwg')) return 'fas fa-drafting-compass';
        if (type?.includes('word') || type?.includes('doc')) return 'fas fa-file-word';
        if (type?.includes('excel') || type?.includes('sheet')) return 'fas fa-file-excel';
        return 'fas fa-file';
    }

    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;

    function setupImageInteractions() {
        const image = document.getElementById('planImage');
        if (!image) return;

        const container = document.getElementById('planViewerContainer');

        // Mouse events for dragging
        image.addEventListener('mousedown', (e) => {
            isDragging = true;
            image.style.cursor = 'grabbing';
            startX = e.pageX - container.offsetLeft;
            startY = e.pageY - container.offsetTop;
            scrollLeft = container.scrollLeft;
            scrollTop = container.scrollTop;
            e.preventDefault();
        });

        image.addEventListener('mouseleave', () => {
            isDragging = false;
            image.style.cursor = 'grab';
        });

        image.addEventListener('mouseup', () => {
            isDragging = false;
            image.style.cursor = 'grab';
        });

        image.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const y = e.pageY - container.offsetTop;
            const walkX = (x - startX) * 2;
            const walkY = (y - startY) * 2;
            container.scrollLeft = scrollLeft - walkX;
            container.scrollTop = scrollTop - walkY;
        });

        // Wheel zoom
        container.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -0.1 : 0.1;
            currentZoom = Math.max(0.1, Math.min(5, currentZoom + delta));
            image.style.transform = `scale(${currentZoom})`;
        });
    }

    function zoomIn() {
        const image = document.getElementById('planImage');
        const pdfViewer = document.getElementById('pdfViewer');

        if (image) {
            currentZoom = Math.min(currentZoom * 1.2, 5);
            image.style.transform = `scale(${currentZoom})`;
            image.style.transformOrigin = 'center center';
        } else if (pdfViewer) {
            // For PDF, try to send zoom command
            try {
                pdfViewer.contentWindow.postMessage({
                    action: 'zoom',
                    direction: 'in'
                }, '*');
            } catch (e) {
                console.log('PDF zoom not supported');
            }
        }
    }

    function zoomOut() {
        const image = document.getElementById('planImage');
        const pdfViewer = document.getElementById('pdfViewer');

        if (image) {
            currentZoom = Math.max(currentZoom / 1.2, 0.1);
            image.style.transform = `scale(${currentZoom})`;
            image.style.transformOrigin = 'center center';
        } else if (pdfViewer) {
            // For PDF, try to send zoom command
            try {
                pdfViewer.contentWindow.postMessage({
                    action: 'zoom',
                    direction: 'out'
                }, '*');
            } catch (e) {
                console.log('PDF zoom not supported');
            }
        }
    }

    function resetZoom() {
        const image = document.getElementById('planImage');
        const pdfViewer = document.getElementById('pdfViewer');

        if (image) {
            currentZoom = 1;
            image.style.transform = `scale(${currentZoom})`;
            image.style.transformOrigin = 'center center';
        } else if (pdfViewer) {
            // For PDF, reload to reset zoom
            const currentSrc = pdfViewer.src;
            pdfViewer.src = currentSrc;
        }
    }

    function downloadPlan() {
        if (currentPlanData) {
            const link = document.createElement('a');
            link.href = currentPlanData.file_path;
            link.download = currentPlanData.title + '.' + (currentPlanData.file_type?.split('/')[1] || 'file');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    function printPlan() {
        if (currentPlanData) {
            const fileType = currentPlanData.file_type?.toLowerCase();

            if (isImageFile(fileType)) {
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
        <html>
          <head>
            <title>{{ __('messages.print') }} - ${currentPlanData.title}</title>
            <style>
              body { margin: 0; text-align: center; }
              img { max-width: 100%; height: auto; }
            </style>
          </head>
          <body>
            <img src="${currentPlanData.file_path}" onload="window.print(); window.close();">
          </body>
        </html>
      `);
                printWindow.document.close();
            } else {
                // For other files, open in new tab for printing
                window.open(currentPlanData.file_path, '_blank');
            }
        }
    }

    function addAnnotation() {
        alert('{{ __('messages.annotation_feature_coming_soon') }}');
    }

    function measureTool() {
        alert('{{ __('messages.measure_feature_coming_soon') }}');
    }

    function reportIssue() {
        alert('{{ __('messages.issue_report_feature_coming_soon') }}');
    }

    function addPlanComment() {
        const commentInput = document.getElementById('planComment');
        const comment = commentInput.value.trim();

        if (comment) {
            const commentsDiv = document.getElementById('planComments');
            const newCommentHTML = `
      <div class="mb-3">
        <div class="bg-light p-2 rounded">
          <strong class="small">{{ __('messages.you') }}</strong>
          <small class="text-muted d-block">{{ __('messages.just_now') }}</small>
          <p class="small mb-0 mt-1">${comment}</p>
        </div>
      </div>
    `;

            // Remove "no comments" message if exists
            const noComments = commentsDiv.querySelector('.text-muted.text-center');
            if (noComments) {
                noComments.remove();
            }

            commentsDiv.insertAdjacentHTML('beforeend', newCommentHTML);
            commentInput.value = '';
        }
    }

    function addAnnotation() {
        alert('Annotation tool would be implemented here');
    }

    function measureTool() {
        alert('Measurement tool would be implemented here');
    }

    function printPlan() {
        const planImage = document.getElementById('planImage');
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
    <html>
      <head><title>{{ __('messages.print') }} Plan</title></head>
      <body style="margin:0; text-align:center;">
        <img src="${planImage.src}" style="max-width:100%; height:auto;">
      </body>
    </html>
  `);
        printWindow.document.close();
        printWindow.print();
    }

    function reportIssue() {
        alert('Issue reporting form would open here');
    }

    function addPlanComment() {
        const comment = document.getElementById('planComment').value;
        if (comment.trim()) {
            const commentsDiv = document.getElementById('planComments');
            const newCommentHTML = `
      <div class="mb-3">
        <div class="bg-light p-2 rounded">
          <strong class="small">You</strong>
          <small class="text-muted d-block">Just now</small>
          <p class="small mb-0 mt-1">${comment}</p>
        </div>
      </div>
    `;
            commentsDiv.insertAdjacentHTML('beforeend', newCommentHTML);
            document.getElementById('planComment').value = '';
        }
    }

    // Pan functionality
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('planViewerContainer');
        const image = document.getElementById('planImage');

        image.addEventListener('mousedown', (e) => {
            isDragging = true;
            image.style.cursor = 'grabbing';
            startX = e.pageX - container.offsetLeft;
            startY = e.pageY - container.offsetTop;
            scrollLeft = container.scrollLeft;
            scrollTop = container.scrollTop;
        });

        image.addEventListener('mouseleave', () => {
            isDragging = false;
            image.style.cursor = 'grab';
        });

        image.addEventListener('mouseup', () => {
            isDragging = false;
            image.style.cursor = 'grab';
        });

        image.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const y = e.pageY - container.offsetTop;
            const walkX = (x - startX) * 2;
            const walkY = (y - startY) * 2;
            container.scrollLeft = scrollLeft - walkX;
            container.scrollTop = scrollTop - walkY;
        });
    });
</script>
