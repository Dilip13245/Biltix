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
        <form id="addMemberForm">
          @csrf
          
          <div class="mb-3">
            <label for="memberSelect" class="form-label fw-medium">{{ __("messages.select_user") }}</label>
            <select class="form-select Input_control" id="memberSelect" name="member_user_id" required>
              <option value="">{{ __("messages.select_user") }}</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="roleDisplay" class="form-label fw-medium">{{ __("messages.role") }}</label>
            <input type="text" class="form-control Input_control" id="roleDisplay" name="role_in_project" readonly
              placeholder="{{ __("messages.role_will_display_here") }}">
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("messages.cancel") }}</button>
        <button type="submit" form="addMemberForm" class="btn orange_btn" id="memberSubmitBtn">
          <i class="fas fa-user-plus me-2"></i>{{ __("messages.add_member") }}
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
        
        if (response.code === 200 && memberSelect) {
          memberSelect.innerHTML = '<option value="">{{ __("messages.select_user") }}</option>';
          response.data.forEach(user => {
            memberSelect.innerHTML += `<option value="${user.id}" data-role="${user.role}">${user.name}</option>`;
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
  
  if (memberSelect && roleDisplay) {
    memberSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      if (selectedOption.value) {
        const role = selectedOption.getAttribute('data-role');
        roleDisplay.value = role || '';
      } else {
        roleDisplay.value = '';
      }
    });
  }
});
</script>