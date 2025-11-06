<!-- Add Team Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <style>
          #addMemberModal .modal-header .btn-close {
            position: static !important;
            right: auto !important;
            top: auto !important;
            margin: 0 !important;
          }
          #addMemberModal .modal-header {
            position: relative !important;
          }
        </style>
        @if(app()->getLocale() == 'ar')
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title" id="addMemberModalLabel">
            {{ __("messages.add_team_member") }}<i class="fas fa-user-plus ms-2"></i>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @else
        <h5 class="modal-title" id="addMemberModalLabel">
          <i class="fas fa-user-plus me-2"></i>{{ __("messages.add_team_member") }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        @endif
      </div>
      <div class="modal-body">
        <form id="addMemberForm" novalidate>
          @csrf
          
          <div class="mb-3">
            <label for="memberSelect" class="form-label fw-medium">{{ __("messages.select_user") }}</label>
            <select class="form-select Input_control" id="memberSelect" name="member_user_id">
              <option value="">{{ __("messages.select_user") }}</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="roleDisplay" class="form-label fw-medium">{{ __("messages.role") }}</label>
            <input type="text" class="form-control Input_control" id="roleDisplay" name="role_display" readonly
              placeholder="{{ __("messages.role_will_display_here") }}" maxlength="50">
            <input type="hidden" id="roleInProject" name="role_in_project" value="">
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __("messages.cancel") }}</button>
        <button type="button" class="btn orange_btn api-action-btn" id="memberSubmitBtn">
          {{ __("messages.add_member") }}
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const addMemberModal = document.getElementById('addMemberModal');
  if (addMemberModal) {
    addMemberModal.addEventListener('show.bs.modal', async function() {
      try {
        // Load users when modal opens
        const response = await api.getAllUsers();
        const memberSelect = document.getElementById('memberSelect');
        
        if (response.code === 200 && memberSelect && response.data) {
          memberSelect.innerHTML = '<option value="">{{ __("messages.select_user") }}</option>';
          response.data.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id || '';
            if (user.role) {
              option.setAttribute('data-role', user.role);
            }
            option.textContent = user.name || '';
            memberSelect.appendChild(option);
          });
          
          // Initialize searchable dropdown after loading options
          if (typeof SearchableDropdown !== 'undefined' && !memberSelect.searchableDropdown) {
            memberSelect.searchableDropdown = new SearchableDropdown(memberSelect);
          }
        }
      } catch (error) {
        console.error('Error loading users:', error);
      }
    });
  }

  // Handle user selection to display role
  const memberSelect = document.getElementById('memberSelect');
  const roleDisplay = document.getElementById('roleDisplay');
  
  // Function to format role name (e.g., project_manager -> Project Manager)
  function formatRoleName(role) {
    if (!role) return '';
    // Replace underscores with spaces and capitalize each word
    return role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
  }
  
  if (memberSelect && roleDisplay) {
    memberSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const roleInProjectField = document.getElementById('roleInProject');
      if (selectedOption.value) {
        const role = selectedOption.getAttribute('data-role');
        // Store original role value (with underscores) in hidden field
        if (roleInProjectField) {
          roleInProjectField.value = role || '';
        }
        // Format the role before displaying
        roleDisplay.value = formatRoleName(role) || '';
      } else {
        if (roleInProjectField) {
          roleInProjectField.value = '';
        }
        roleDisplay.value = '';
      }
    });
  }
  
  // Validation function
  function validateMemberForm() {
    const form = document.getElementById('addMemberForm');
    if (!form) return false;
    
    let isValid = true;
    
    // Clear previous errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    // Validate member selection
    const memberSelect = form.querySelector('#memberSelect');
    if (!memberSelect.value) {
      showFieldError(memberSelect, '{{ __("messages.select_user") }} is required');
      isValid = false;
    }
    
    // Validate role
    const roleInProject = form.querySelector('#roleInProject');
    if (!roleInProject || !roleInProject.value.trim()) {
      const roleDisplay = form.querySelector('#roleDisplay');
      if (roleDisplay) {
        showFieldError(roleDisplay, '{{ __("messages.role") }} is required');
      }
      isValid = false;
    }
    
    if (!isValid) {
      if (typeof showToast !== 'undefined') {
        showToast('Please fill in all required fields.', 'error');
      } else if (typeof toastr !== 'undefined') {
        toastr.error('Please fill in all required fields.');
      } else {
        alert('Please fill in all required fields.');
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
  const addMemberForm = document.getElementById('addMemberForm');
  const submitBtn = document.getElementById('memberSubmitBtn');
  
  if (addMemberForm) {
    addMemberForm.addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      if (!validateMemberForm()) {
        return false;
      }
    });
  }
  
  // Also handle button click
  if (submitBtn) {
    submitBtn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const form = document.getElementById('addMemberForm');
      if (form && typeof validateMemberForm === 'function') {
        if (validateMemberForm()) {
          // Validation passed, trigger form submit
          form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        }
      }
    });
  }

  // Reset modal button when modal is hidden
  if (addMemberModal) {
    addMemberModal.addEventListener('hidden.bs.modal', function() {
        const form = document.getElementById('addMemberForm');
        const btn = document.getElementById('memberSubmitBtn');
        
        if (form) {
          form.reset();
          form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
          form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        }
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '{{ __("messages.add_member") }}';
        }
    });
  }
});
</script>