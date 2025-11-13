<!-- Create Inspection Modal -->
<div class="modal fade" id="createInspectionModal" tabindex="-1" aria-labelledby="createInspectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <style>
          #createInspectionModal .modal-header .btn-close {
            position: static !important;
            right: auto !important;
            top: auto !important;
            margin: 0 !important;
          }
          #createInspectionModal .modal-header {
            position: relative !important;
          }
        </style>
        @if(app()->getLocale() == 'ar')
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title" id="createInspectionModalLabel">
            {{ __("messages.create_new_inspection") }}<i class="fas fa-clipboard-check ms-2"></i>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @else
        <h5 class="modal-title" id="createInspectionModalLabel">
          <i class="fas fa-clipboard-check me-2"></i>{{ __("messages.create_new_inspection") }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        @endif
      </div>
      <div class="modal-body">
        <form id="createInspectionForm" class="protected-form" novalidate>
          @csrf
          <input type="hidden" name="user_id" value="{{ auth()->id() }}">
          <input type="hidden" id="modalProjectId" name="project_id" value="1">
          
          <div class="mb-3" id="phaseSelectContainer">
            <label for="phaseSelect" class="form-label fw-medium">{{ __("messages.phase") }}</label>
            <select class="form-select Input_control searchable-select" id="phaseSelect" name="phase_id">
              <option value="">{{ __("messages.select_phase") }}</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="category" class="form-label fw-medium">{{ __("messages.category") }}</label>
            <select class="form-select Input_control searchable-select" id="category" name="category">
              <option value="">{{ __("messages.select_category") }}</option>
              <option value="structural">{{ __("messages.structural") }}</option>
              <option value="electrical">{{ __("messages.electrical") }}</option>
              <option value="plumbing">{{ __("messages.plumbing") }}</option>
              <option value="safety">{{ __("messages.safety") }}</option>
              <option value="quality">{{ __("messages.quality") }}</option>
              <option value="mechanical">{{ __("messages.mechanical") }}</option>
              <option value="finishing">{{ __("messages.finishing") }}</option>
            </select>
            <small class="text-muted">{{ __("messages.type_to_add_new_category") }}</small>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label fw-medium">{{ __("messages.description") }}</label>
            <textarea class="form-control Input_control" id="description" name="description" rows="3"
              placeholder="{{ __("messages.provide_detailed_description") }}" maxlength="500"></textarea>
          </div>

          <div class="mb-3">
            <label for="checklist_items" class="form-label fw-medium">{{ __("messages.checklist_items") }}</label>
            <div id="checklistContainer">
              <div class="input-group mb-2">
                <input type="text" class="form-control Input_control" name="checklist_items[]" 
                  placeholder="{{ __("messages.enter_checklist_item") }}" maxlength="200">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __("messages.cancel") }}</button>
        <button type="button" class="btn orange_btn api-action-btn" id="inspectionSubmitBtn">
          {{ __("messages.create_inspection") }}
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
      placeholder="{{ __("messages.enter_checklist_item") }}" maxlength="200">
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

// Simple button protection
function protectButton(btn) {
  if (btn.disabled) return;
  btn.disabled = true;
  const originalText = btn.innerHTML;
  btn.setAttribute('data-original-text', originalText);
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
  // Store timeout ID so it can be cleared if needed
  btn.setAttribute('data-timeout-id', setTimeout(function() {
    resetFormSubmission('createInspectionForm');
  }, 30000)); // 30 seconds max timeout
}

// Load data when modal is shown
if (!window.inspectionModalListenerAdded) {
  window.inspectionModalListenerAdded = true;
  const createInspectionModal = document.getElementById('createInspectionModal');
  if (createInspectionModal) {
    createInspectionModal.addEventListener('shown.bs.modal', async function() {
      const btn = document.getElementById('inspectionSubmitBtn');
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = '{{ __("messages.create_inspection") }}';
      }
      
      // Get project ID
      const projectId = document.getElementById('modalProjectId')?.value || 
                       (typeof getCurrentProjectId === 'function' ? getCurrentProjectId() : 
                       (typeof getProjectIdFromUrl === 'function' ? getProjectIdFromUrl() : 1));
      
      // Load phases if loadPhases function exists, otherwise load directly
      if (window.loadPhases) {
        await window.loadPhases();
      } else {
        // Load phases directly
        try {
          const response = await api.listPhases({
            project_id: projectId
          });
          const phaseSelect = document.getElementById('phaseSelect');
          
          if (response.code === 200 && phaseSelect) {
            phaseSelect.innerHTML = '<option value="">{{ __("messages.select_phase") }}</option>';
            response.data.forEach(phase => {
              const option = document.createElement('option');
              option.value = phase.id;
              option.textContent = phase.title || phase.name;
              phaseSelect.appendChild(option);
            });
          }
        } catch (error) {
          console.error('Error loading phases:', error);
        }
      }
      
      // Destroy and recreate dropdowns
      setTimeout(() => {
        ['phaseSelect', 'category'].forEach(id => {
          const select = document.getElementById(id);
          if (select) {
            // Remove old wrapper
            const wrapper = select.closest('.searchable-dropdown');
            if (wrapper) {
              const parent = wrapper.parentElement;
              parent.insertBefore(select, wrapper);
              wrapper.remove();
              select.style.display = '';
            }
            // Create new instance
            if (window.SearchableDropdown) {
              select.searchableDropdown = new SearchableDropdown(select);
            }
          }
        });
        
        // Allow adding new category when modal opens
        setTimeout(() => {
          const categorySelect = document.getElementById('category');
          const categoryWrapper = categorySelect?.closest('.searchable-dropdown');
          const categoryInput = categoryWrapper?.querySelector('input[type="text"]');
          
          if (categoryInput && !categoryInput.hasAttribute('data-custom-listener')) {
            categoryInput.setAttribute('data-custom-listener', 'true');
            categoryInput.addEventListener('keydown', function(e) {
              if (e.key === 'Enter' && this.value.trim()) {
                e.preventDefault();
                const newValue = this.value.trim();
                
                // Check if option already exists
                const existingOption = Array.from(categorySelect.options).find(opt => 
                  opt.value.toLowerCase() === newValue.toLowerCase()
                );
                
                if (!existingOption) {
                  // Add new option
                  const newOption = document.createElement('option');
                  newOption.value = newValue;
                  newOption.textContent = newValue;
                  categorySelect.appendChild(newOption);
                  
                  // Update searchable dropdown
                  if (categorySelect.searchableDropdown) {
                    categorySelect.searchableDropdown.updateOptions();
                  }
                }
                
                // Select the option
                categorySelect.value = existingOption ? existingOption.value : newValue;
                this.value = existingOption ? existingOption.textContent : newValue;
                
                if (categorySelect.searchableDropdown) {
                  categorySelect.searchableDropdown.hideDropdown();
                }
              }
            });
          }
        }, 100);
      }, 100);
    });
  }
}

// Form submission with API integration
document.addEventListener('DOMContentLoaded', function() {
  const createInspectionForm = document.getElementById('createInspectionForm');
  const submitBtn = document.getElementById('inspectionSubmitBtn');
  
  if (createInspectionForm) {
    createInspectionForm.addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      // Validate form first
      if (!validateInspectionForm()) {
        return false;
      }
      
      // Protect button
      if (submitBtn) protectButton(submitBtn);
      
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
            submitInspectionWithImages(imageData);
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
        // No images selected - call API directly
        submitInspectionWithoutImages();
      }
    });
  }
  
  // Also handle button click
  if (submitBtn) {
    submitBtn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const form = document.getElementById('createInspectionForm');
      if (form && typeof validateInspectionForm === 'function') {
        if (validateInspectionForm()) {
          // Validation passed, trigger form submit
          form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        }
      }
    });
  }
});

// // Reset form submission function
// function resetFormSubmission(formId) {
//   const form = document.getElementById(formId);
//   if (form) {
//     // Find the submit button
//     const submitBtn = form.querySelector('button[type="submit"]') || document.getElementById('inspectionSubmitBtn');
//     if (submitBtn) {
//       // Clear any existing timeout
//       const timeoutId = submitBtn.getAttribute('data-timeout-id');
//       if (timeoutId) {
//         clearTimeout(parseInt(timeoutId));
//         submitBtn.removeAttribute('data-timeout-id');
//       }
      
//       // Use the global releaseButton function if available
//       if (typeof window.releaseButton === 'function') {
//         window.releaseButton(submitBtn);
//       } else {
//         // Fallback: manually reset button
//         submitBtn.disabled = false;
//         const originalText = submitBtn.getAttribute('data-original-text') || '{{ __("messages.create_inspection") }}';
//         submitBtn.innerHTML = originalText;
//         submitBtn.removeAttribute('data-original-text');
//       }
//     }
//   }
// }

// Submit inspection with images
async function submitInspectionWithImages(imageDataArray) {
  try {
    const formData = new FormData();
    const form = document.getElementById('createInspectionForm');
    
    // Add form fields
    formData.append('category', form.category.value);
    formData.append('description', form.description.value);
    formData.append('project_id', form.project_id.value);
    // Get phase_id from dropdown or stored value for phase pages
    let phaseId = document.getElementById('phaseSelect').value;
    if (!phaseId && window.currentPhaseId) {
      phaseId = window.currentPhaseId;
    }
    if (phaseId) {
      formData.append('phase_id', phaseId);
    }
    console.log('Phase ID being sent:', phaseId);
    
    // Add checklist items
    const checklistItems = Array.from(form.querySelectorAll('input[name="checklist_items[]"]'))
      .map(input => input.value.trim())
      .filter(value => value);
    
    checklistItems.forEach(item => {
      formData.append('checklist_items[]', item);
    });
    
    // Convert image data to blobs and add to FormData
    if (Array.isArray(imageDataArray)) {
      for (let i = 0; i < imageDataArray.length; i++) {
        const data = imageDataArray[i];
        if (typeof data === 'string') {
          // This is a drawing (base64 string)
          const blob = dataURLToBlob(data);
          formData.append('images[]', blob, `inspection_image_${i + 1}.png`);
        } else if (data instanceof File) {
          // This is an original file
          formData.append('images[]', data, data.name);
        }
      }
    } else {
      const blob = dataURLToBlob(imageDataArray);
      formData.append('images[]', blob, 'inspection_image.png');
    }
    
    const response = await api.createInspection(formData);
    
    if (response.code === 200) {
      closeModalsAndReload();
      showToast('success', 'Inspection created successfully!');
    } else {
      resetFormSubmission('createInspectionForm');
      showToast('error', response.message || 'Failed to create inspection');
    }
  } catch (error) {
    console.error('Error creating inspection:', error);
    resetFormSubmission('createInspectionForm');
    showToast('error', 'Failed to create inspection. Please try again.');
  }
}

// Submit inspection without images
async function submitInspectionWithoutImages() {
  try {
    const formData = new FormData();
    const form = document.getElementById('createInspectionForm');
    
    // Add form fields
    formData.append('category', form.category.value);
    formData.append('description', form.description.value);
    formData.append('project_id', form.project_id.value);
    // Get phase_id from dropdown or stored value for phase pages
    let phaseId = document.getElementById('phaseSelect').value;
    if (!phaseId && window.currentPhaseId) {
      phaseId = window.currentPhaseId;
    }
    if (phaseId) {
      formData.append('phase_id', phaseId);
    }
    
    // Add checklist items
    const checklistItems = Array.from(form.querySelectorAll('input[name="checklist_items[]"]'))
      .map(input => input.value.trim())
      .filter(value => value);
    
    checklistItems.forEach(item => {
      formData.append('checklist_items[]', item);
    });
    
    const response = await api.createInspection(formData);
    
    if (response.code === 200) {
      const inspectionModal = bootstrap.Modal.getInstance(document.getElementById('createInspectionModal'));
      if (inspectionModal) inspectionModal.hide();
      
      showToast('success', response.message);
      location.reload();
    } else {
      resetFormSubmission('createInspectionForm');
      showToast('error', response.message || 'Failed to create inspection');
    }
  } catch (error) {
    console.error('Error creating inspection:', error);
    resetFormSubmission('createInspectionForm');
    showToast('error', 'Failed to create inspection. Please try again.');
  }
}

// Submit inspection with drawing
async function submitInspectionWithDrawing(imageData) {
  try {
    const formData = new FormData();
    const form = document.getElementById('createInspectionForm');
    
    // Add form fields
    formData.append('category', form.category.value);
    formData.append('description', form.description.value);
    formData.append('project_id', form.project_id.value);
    // Get phase_id from dropdown or stored value for phase pages
    let phaseId = document.getElementById('phaseSelect').value;
    if (!phaseId && window.currentPhaseId) {
      phaseId = window.currentPhaseId;
    }
    if (phaseId) {
      formData.append('phase_id', phaseId);
    }
    console.log('Phase ID being sent:', phaseId);
    
    // Add checklist items
    const checklistItems = Array.from(form.querySelectorAll('input[name="checklist_items[]"]'))
      .map(input => input.value.trim())
      .filter(value => value);
    
    checklistItems.forEach(item => {
      formData.append('checklist_items[]', item);
    });
    
    // Add drawing as image
    const blob = dataURLToBlob(imageData);
    formData.append('images[]', blob, 'inspection_drawing.png');
    
    const response = await api.createInspection(formData);
    
    if (response.code === 200) {
      closeModalsAndReload();
      showToast('success', 'Inspection created successfully!');
    } else {
      showToast('error', response.message || 'Failed to create inspection');
    }
  } catch (error) {
    console.error('Error creating inspection:', error);
    showToast('error', 'Failed to create inspection. Please try again.');
  }
}

// Helper functions
function dataURLToBlob(dataURL) {
  // If it's already a File object, return it as is
  if (dataURL instanceof File) {
    return dataURL;
  }
  
  const arr = dataURL.split(',');
  const mime = arr[0].match(/:(.*?);/)[1];
  const bstr = atob(arr[1]);
  let n = bstr.length;
  const u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new Blob([u8arr], { type: mime });
}

function closeModalsAndReload() {
  const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
  if (drawingModal) drawingModal.hide();
  
  const inspectionModal = bootstrap.Modal.getInstance(document.getElementById('createInspectionModal'));
  if (inspectionModal) inspectionModal.hide();
  
  setTimeout(() => location.reload(), 1000);
}

function validateInspectionForm() {
  const form = document.getElementById('createInspectionForm');
  if (!form) return false;
  
  const phaseSelect = document.getElementById('phaseSelect');
  const phaseContainer = document.getElementById('phaseSelectContainer');
  // Check if phase field is visible (hidden on phase-specific pages)
  // Check both inline style and computed style
  let isPhaseFieldVisible = true;
  if (phaseContainer) {
    const inlineStyle = phaseContainer.style.display;
    const computedStyle = window.getComputedStyle(phaseContainer).display;
    isPhaseFieldVisible = inlineStyle !== 'none' && computedStyle !== 'none';
  }
  const phaseId = phaseSelect ? phaseSelect.value.trim() : '';
  
  const category = form.category.value.trim();
  const description = form.description.value.trim();
  const checklistInputs = Array.from(form.querySelectorAll('input[name="checklist_items[]"]'));
  const checklistItems = checklistInputs
    .map(input => input.value.trim())
    .filter(value => value);
  
  // Clear previous errors
  clearValidationErrors();
  
  let isValid = true;
  
  // Validate phase only if field is visible
  if (isPhaseFieldVisible && !phaseId) {
    showFieldError('phaseSelect', '{{ __("messages.phase") }} is required');
    isValid = false;
  }
  
  // Validate category
  if (!category) {
    showFieldError('category', getValidationMessage('category_required'));
    isValid = false;
  }
  
  // Validate description
  if (!description) {
    showFieldError('description', getValidationMessage('description_required'));
    isValid = false;
  }
  
  // Validate checklist items - at least one non-empty item required
  if (checklistItems.length === 0) {
    // Show error on first checklist input
    const firstChecklistInput = checklistInputs[0];
    if (firstChecklistInput) {
      firstChecklistInput.classList.add('is-invalid');
      const errorDiv = document.createElement('div');
      errorDiv.className = 'invalid-feedback';
      errorDiv.textContent = getValidationMessage('checklist_required');
      firstChecklistInput.parentElement.appendChild(errorDiv);
    }
    isValid = false;
  }
  
  // Validate individual checklist items (show error on empty ones if there are multiple)
  checklistInputs.forEach((input, index) => {
    if (!input.value.trim() && checklistItems.length > 0) {
      // Only show error if there are other non-empty items
      input.classList.add('is-invalid');
      const errorDiv = document.createElement('div');
      errorDiv.className = 'invalid-feedback';
      errorDiv.textContent = '{{ __('messages.checklist_item_cannot_be_empty') }}';
      input.parentElement.appendChild(errorDiv);
    }
  });
  
  if (!isValid) {
    showToast('error', '{{ __('messages.please_fill_required_fields') }}');
  }
  
  return isValid;
}

function clearValidationErrors() {
  document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
  document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

function showFieldError(fieldName, message) {
  const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}[]"]`);
  if (field) {
    field.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    // For date picker fields, append after the wrapper
    if (field.classList.contains('modern-datepicker-input')) {
        const wrapper = field.closest('.modern-datepicker-wrapper');
        if (wrapper && wrapper.parentElement) {
            // Insert error message after the wrapper
            wrapper.parentElement.insertBefore(errorDiv, wrapper.nextSibling);
        } else {
            field.parentNode.appendChild(errorDiv);
        }
    } else {
        // For select fields, append to parent
        field.parentNode.appendChild(errorDiv);
    }
  }
}

function getValidationMessage(key) {
  const messages = {
    category_required: '{{ __('messages.please_select_category') }}',
    description_required: '{{ __('messages.please_enter_description') }}',
    checklist_required: '{{ __('messages.please_add_checklist_item') }}'
  };
  return messages[key] || '{{ __('messages.validation_error') }}';
}

function resetSubmitButton() {
  resetFormSubmission('createInspectionForm');
}

// Reset modal when hidden
document.getElementById('createInspectionModal')?.addEventListener('hidden.bs.modal', function() {
  const form = document.getElementById('createInspectionForm');
  const btn = document.getElementById('inspectionSubmitBtn');
  
  if (form) {
    form.reset();
    clearValidationErrors();
  }
  if (btn) {
    btn.disabled = false;
    btn.innerHTML = '{{ __("messages.create_inspection") }}';
  }
});

function showToast(type, message) {
  if (typeof toastr !== 'undefined') {
    toastr[type](message);
  } else if (typeof Swal !== 'undefined') {
    Swal.fire({
      icon: type === 'success' ? 'success' : 'error',
      title: message,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  } else {
    alert(message);
  }
}
</script>