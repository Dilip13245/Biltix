<!-- Add Photo Modal -->
<div class="modal fade" id="addPhotoModal" tabindex="-1" aria-labelledby="addPhotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
        <h5 class="modal-title" id="addPhotoModalLabel">
          <i class="fas fa-camera {{ margin_end(2) }}"></i>{{ __("messages.add_new") }} Photos
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addPhotoForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-4">
            <label for="photoFiles" class="form-label fw-medium">Select Photos</label>
            <input type="file" class="form-control Input_control" id="photoFiles" name="photos[]" 
              accept="image/*" multiple required>
            <div class="form-text">You can select multiple photos. Supported formats: JPG, PNG, GIF (Max: 5MB each)</div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="photoCategory" class="form-label fw-medium">Category</label>
              <select class="form-select Input_control" id="photoCategory" name="category" required>
                <option value="">Select Category</option>
                <option value="foundation">Foundation</option>
                <option value="structure">Structure</option>
                <option value="finishing">Finishing</option>
                <option value="safety">Safety</option>
                <option value="equipment">Equipment</option>
                <option value="progress">Progress</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="photoDate" class="form-label fw-medium">Date Taken</label>
              @include('website.includes.date-picker', [
                'id' => 'photoDate',
                'name' => 'date_taken',
                'placeholder' => 'Select photo date',
                'value' => date('Y-m-d'),
                'maxDate' => date('Y-m-d'),
                'required' => true
              ])
            </div>
          </div>

          <div class="mb-3">
            <label for="photoDescription" class="form-label fw-medium">Description</label>
            <textarea class="form-control Input_control" id="photoDescription" name="description" rows="3"
              placeholder="Brief description of the photos..."></textarea>
          </div>

          <div class="mb-3">
            <label for="photographer" class="form-label fw-medium">Photographer</label>
            <input type="text" class="form-control Input_control" id="photographer" name="photographer" 
              placeholder="Name of person who took the photos">
          </div>

          <div class="mb-3">
            <label for="location" class="form-label fw-medium">Location/Area</label>
            <input type="text" class="form-control Input_control" id="location" name="location" 
              placeholder="e.g., Zone A, Ground Floor, etc.">
          </div>

          <!-- Photo Preview Area -->
          <div id="photoPreview" class="mt-3" style="display: none;">
            <h6 class="fw-medium mb-3">Photo Preview:</h6>
            <div id="previewContainer" class="row g-3"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __("messages.cancel") }}</button>
        <button type="submit" form="addPhotoForm" class="btn orange_btn" id="addPhotoBtn">
          <i class="fas fa-upload me-2"></i>{{ __("messages.upload_photo") }}s
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
              previewContainer.insertAdjacentHTML('beforeend', previewHTML);
            };
            reader.readAsDataURL(file);
          }
        });
      } else {
        previewArea.style.display = 'none';
      }
    });
  }
  
  // Button protection
  var btn = document.getElementById('addPhotoBtn');
  if (btn) {
    btn.addEventListener('click', function() {
      if (!this.disabled) {
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
        setTimeout(() => {
          this.disabled = false;
          this.innerHTML = '<i class="fas fa-upload me-2"></i>{{ __("messages.upload_photo") }}s';
        }, 5000);
      }
    });
  }
});
</script>