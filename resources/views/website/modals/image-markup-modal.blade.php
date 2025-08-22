<!-- Image Markup Modal -->
<div class="modal fade" id="imageMarkupModal" tabindex="-1" aria-labelledby="imageMarkupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageMarkupModalLabel">
          <i class="fas fa-edit me-2"></i>Mark Issue on Image
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="d-flex h-100">
          <!-- Drawing Tools Sidebar -->
          <div class="bg-light p-3" style="width: 200px;">
            <h6 class="mb-3">Drawing Tools</h6>
            <div class="d-grid gap-2">
              <button class="btn btn-outline-primary btn-sm" id="penTool" onclick="setTool('pen')">
                <i class="fas fa-pen me-2"></i>Pen
              </button>
              <button class="btn btn-outline-primary btn-sm" id="circleTool" onclick="setTool('circle')">
                <i class="fas fa-circle me-2"></i>Circle
              </button>
              <button class="btn btn-outline-primary btn-sm" id="arrowTool" onclick="setTool('arrow')">
                <i class="fas fa-arrow-right me-2"></i>Arrow
              </button>
              <button class="btn btn-outline-primary btn-sm" id="textTool" onclick="setTool('text')">
                <i class="fas fa-font me-2"></i>Text
              </button>
              <hr>
              <label class="form-label small">Color:</label>
              <input type="color" class="form-control form-control-color" id="colorPicker" value="#ff0000">
              <label class="form-label small mt-2">Size:</label>
              <input type="range" class="form-range" id="brushSize" min="1" max="10" value="3">
              <hr>
              <button class="btn btn-warning btn-sm" onclick="clearCanvas()">
                <i class="fas fa-eraser me-2"></i>Clear
              </button>
              <button class="btn btn-secondary btn-sm" onclick="undoLast()">
                <i class="fas fa-undo me-2"></i>Undo
              </button>
            </div>
          </div>
          
          <!-- Canvas Area -->
          <div class="flex-grow-1 position-relative overflow-auto">
            <canvas id="markupCanvas" style="cursor: crosshair; display: block; margin: auto;"></canvas>
            <img id="markupImage" style="position: absolute; top: 0; left: 0; pointer-events: none; display: none;">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn orange_btn" onclick="saveMarkup()">
          <i class="fas fa-save me-2"></i>Save & Continue
        </button>
      </div>
    </div>
  </div>
</div>

