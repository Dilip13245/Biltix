<!-- Create Inspection Modal -->
<div class="modal fade" id="createInspectionModal" tabindex="-1" aria-labelledby="createInspectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
        <h5 class="modal-title" id="createInspectionModalLabel">
          <i class="fas fa-clipboard-check {{ margin_end(2) }}"></i>{{ __("messages.create_new_inspection") }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createInspectionForm">
          @csrf
          <input type="hidden" name="user_id" value="{{ auth()->id() }}">
          <input type="hidden" name="project_id" value="{{ request()->get('project_id') ?? 1 }}">
          
          <div class="mb-3">
            <label for="category" class="form-label fw-medium">{{ __("messages.category") }}</label>
            <select class="form-select Input_control" id="category" name="category" required>
              <option value="">{{ __("messages.select_category") }}</option>
              <option value="structural">{{ __("messages.structural") }}</option>
              <option value="electrical">{{ __("messages.electrical") }}</option>
              <option value="plumbing">{{ __("messages.plumbing") }}</option>
              <option value="safety">{{ __("messages.safety") }}</option>
              <option value="quality">{{ __("messages.quality") }}</option>
              <option value="mechanical">{{ __("messages.mechanical") }}</option>
              <option value="finishing">{{ __("messages.finishing") }}</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label fw-medium">{{ __("messages.description") }}</label>
            <textarea class="form-control Input_control" id="description" name="description" rows="3" required
              placeholder="{{ __("messages.provide_detailed_description") }}"></textarea>
          </div>

          <div class="mb-3">
            <label for="checklist_items" class="form-label fw-medium">{{ __("messages.checklist_items") }}</label>
            <div id="checklistContainer">
              <div class="input-group mb-2">
                <input type="text" class="form-control Input_control" name="checklist_items[]" 
                  placeholder="{{ __("messages.enter_checklist_item") }}" required>
                <button type="button" class="btn btn-outline-danger" onclick="removeChecklistItem(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addChecklistItem()">
              <i class="fas fa-plus me-1"></i>{{ __("messages.add_item") }}
            </button>
          </div>

          <div class="mb-3">
            <label for="images" class="form-label fw-medium">{{ __("messages.images_optional") }}</label>
            <input type="file" class="form-control Input_control" id="images" name="images[]" 
              multiple accept="image/jpeg,image/jpg,image/png">
            <small class="text-muted">{{ __("messages.max_10mb_per_image") }}</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("messages.cancel") }}</button>
        <button type="submit" form="createInspectionForm" class="btn orange_btn">
          <i class="fas fa-plus me-2"></i>{{ __("messages.create_inspection") }}
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function addChecklistItem() {
  const container = document.getElementById('checklistContainer');
  const newItem = document.createElement('div');
  newItem.className = 'input-group mb-2';
  newItem.innerHTML = `
    <input type="text" class="form-control Input_control" name="checklist_items[]" 
      placeholder="{{ __("messages.enter_checklist_item") }}" required>
    <button type="button" class="btn btn-outline-danger" onclick="removeChecklistItem(this)">
      <i class="fas fa-trash"></i>
    </button>
  `;
  container.appendChild(newItem);
}

function removeChecklistItem(button) {
  const container = document.getElementById('checklistContainer');
  if (container.children.length > 1) {
    button.parentElement.remove();
  }
}

// Form submission - copy exact task pattern
document.addEventListener('DOMContentLoaded', function() {
  const createInspectionForm = document.getElementById('createInspectionForm');
  if (createInspectionForm) {
    createInspectionForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const fileInput = document.getElementById('images');
      
      if (fileInput.files && fileInput.files.length > 0) {
        // Store all files
        window.selectedFiles = fileInput.files;
        
        // Open drawing modal with image markup config
        openDrawingModal({
          title: 'Markup Inspection Images',
          saveButtonText: 'Submit Inspection',
          mode: 'image',
          onSave: function(imageData) {
            // Close drawing modal
            const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
            if (drawingModal) drawingModal.hide();
            
            // Close inspection modal
            const inspectionModal = bootstrap.Modal.getInstance(document.getElementById('createInspectionModal'));
            if (inspectionModal) inspectionModal.hide();
            
            alert('Inspection created successfully!');
            location.reload();
          }
        });
        
        // Load images after modal is shown
        document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
          if (window.selectedFiles.length === 1) {
            loadImageToCanvas(window.selectedFiles[0]);
          } else {
            loadMultipleFiles(window.selectedFiles);
          }
        }, { once: true });
        
      } else {
        // Open drawing modal with blank canvas config
        openDrawingModal({
          title: 'Inspection Drawing',
          saveButtonText: 'Submit Inspection',
          mode: 'blank',
          onSave: function(imageData) {
            // Close drawing modal
            const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
            if (drawingModal) drawingModal.hide();
            
            // Close inspection modal
            const inspectionModal = bootstrap.Modal.getInstance(document.getElementById('createInspectionModal'));
            if (inspectionModal) inspectionModal.hide();
            
            alert('Inspection created successfully!');
            location.reload();
          }
        });
      }
    });
  }
});
</script>