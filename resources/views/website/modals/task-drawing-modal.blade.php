<!-- Task Drawing Modal -->
<div class="modal fade" id="taskDrawingModal" tabindex="-1" aria-labelledby="taskDrawingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="taskDrawingModalLabel">
          <i class="fas fa-pencil-alt me-2"></i>Task Drawing
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="d-flex h-100">
          <!-- Drawing Tools Sidebar -->
          <div class="drawing-tools-sidebar bg-light p-3" style="width: 250px; border-right: 1px solid #dee2e6;">
            <h6 class="fw-bold mb-3">Drawing Tools</h6>
            
            <!-- Tool Selection -->
            <div class="mb-3">
              <label class="form-label fw-medium">Tools</label>
              <div class="btn-group-vertical w-100" role="group">
                <button type="button" class="btn btn-outline-primary active" id="penTool" onclick="setDrawingTool('pen')">
                  <i class="fas fa-pen me-2"></i>Pen
                </button>
                <button type="button" class="btn btn-outline-primary" id="circleTool" onclick="setDrawingTool('circle')">
                  <i class="fas fa-circle me-2"></i>Circle
                </button>
                <button type="button" class="btn btn-outline-primary" id="arrowTool" onclick="setDrawingTool('arrow')">
                  <i class="fas fa-arrow-right me-2"></i>Arrow
                </button>
                <button type="button" class="btn btn-outline-primary" id="textTool" onclick="setDrawingTool('text')">
                  <i class="fas fa-font me-2"></i>Text
                </button>
              </div>
            </div>

            <!-- Color Picker -->
            <div class="mb-3">
              <label for="drawingColorPicker" class="form-label fw-medium">Color</label>
              <input type="color" class="form-control form-control-color" id="drawingColorPicker" value="#ff0000">
            </div>

            <!-- Brush Size -->
            <div class="mb-3">
              <label for="drawingBrushSize" class="form-label fw-medium">Brush Size</label>
              <input type="range" class="form-range" id="drawingBrushSize" min="1" max="10" value="3">
              <div class="d-flex justify-content-between">
                <small>1</small>
                <small>10</small>
              </div>
            </div>

            <!-- Actions -->
            <div class="mb-3">
              <button type="button" class="btn btn-warning w-100 mb-2" onclick="clearDrawingCanvas()">
                <i class="fas fa-eraser me-2"></i>Clear All
              </button>
            </div>
          </div>

          <!-- Drawing Canvas Area -->
          <div class="flex-grow-1 d-flex align-items-center justify-content-center bg-white">
            <canvas id="taskDrawingCanvas" style="border: 1px solid #dee2e6; cursor: crosshair;"></canvas>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn orange_btn" onclick="saveTaskDrawing()">
          <i class="fas fa-save me-2"></i>Save Task
        </button>
      </div>
    </div>
  </div>
</div>