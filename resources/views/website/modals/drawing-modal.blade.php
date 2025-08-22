<!-- Common Drawing Modal -->
<div class="modal fade" id="drawingModal" tabindex="-1" aria-labelledby="drawingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="drawingModalLabel">
          <i class="fas fa-pencil-alt me-2"></i><span id="drawingModalTitle">{{ __("messages.drawing") }}</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="d-flex h-100">
          <!-- Drawing Tools Sidebar -->
          <div class="drawing-tools-sidebar bg-light p-3" style="width: 250px; border-right: 1px solid #dee2e6;">
            <h6 class="fw-bold mb-3">{{ __("messages.drawing_tools") }}</h6>
            
            <!-- Tool Selection -->
            <div class="mb-3">
              <label class="form-label fw-medium">{{ __("messages.tools") }}</label>
              <div class="btn-group-vertical w-100" role="group">
                <button type="button" class="btn btn-outline-primary active" id="penTool" onclick="setTool('pen')">
                  <i class="fas fa-pen me-2"></i>{{ __("messages.pen") }}
                </button>
                <button type="button" class="btn btn-outline-primary" id="circleTool" onclick="setTool('circle')">
                  <i class="fas fa-circle me-2"></i>{{ __("messages.circle") }}
                </button>
                <button type="button" class="btn btn-outline-primary" id="arrowTool" onclick="setTool('arrow')">
                  <i class="fas fa-arrow-right me-2"></i>{{ __("messages.arrow") }}
                </button>
                <button type="button" class="btn btn-outline-primary" id="textTool" onclick="setTool('text')">
                  <i class="fas fa-font me-2"></i>{{ __("messages.text") }}
                </button>
              </div>
            </div>

            <!-- Color Picker -->
            <div class="mb-3">
              <label for="colorPicker" class="form-label fw-medium">{{ __("messages.color") }}</label>
              <input type="color" class="form-control form-control-color" id="colorPicker" value="#ff0000">
            </div>

            <!-- Brush Size -->
            <div class="mb-3">
              <label for="brushSize" class="form-label fw-medium">{{ __("messages.brush_size") }}</label>
              <input type="range" class="form-range" id="brushSize" min="1" max="10" value="3">
              <div class="d-flex justify-content-between">
                <small>1</small>
                <small>10</small>
              </div>
            </div>

            <!-- File Navigation -->
            <div class="mb-3" id="fileNavigation" style="display: none;">
              <label class="form-label fw-medium">{{ __("messages.files") }}</label>
              <div class="d-flex gap-2 mb-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="prevFileBtn" onclick="previousFile()">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <span class="flex-grow-1 text-center small" id="fileCounter">1 / 1</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="nextFileBtn" onclick="nextFile()">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
              <div class="small text-muted text-center" id="currentFileName">image.jpg</div>
            </div>

            <!-- Actions -->
            <div class="mb-3">
              <button type="button" class="btn btn-info w-100 mb-2" onclick="undoLastAction()">
                <i class="fas fa-undo me-2"></i>{{ __("messages.undo") }}
              </button>
              <button type="button" class="btn btn-warning w-100 mb-2" onclick="clearCanvas()">
                <i class="fas fa-eraser me-2"></i>{{ __("messages.clear_all") }}
              </button>
            </div>
          </div>

          <!-- Drawing Canvas Area -->
          <div class="flex-grow-1 d-flex align-items-center justify-content-center bg-white">
            <canvas id="canvas" style="border: 1px solid #dee2e6; cursor: crosshair;"></canvas>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("messages.cancel") }}</button>
        <button type="button" class="btn orange_btn" id="saveDrawingBtn">
          <i class="fas fa-save me-2"></i><span id="saveButtonText">{{ __("messages.save") }}</span>
        </button>
      </div>
    </div>
  </div>
</div>