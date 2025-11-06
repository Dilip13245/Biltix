<!-- Activities Modal -->
<div class="modal fade" id="activitiesModal" tabindex="-1" aria-labelledby="activitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #activitiesModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }
                    #activitiesModal .modal-header {
                        position: relative !important;
                    }
                    @media (max-width: 576px) {
                        #activitiesModal .modal-dialog {
                            margin: 0.5rem;
                        }
                        #activitiesModal .d-flex.gap-2 {
                            flex-direction: column;
                        }
                        #activitiesModal .d-flex.gap-2 button {
                            width: 100%;
                            min-width: auto;
                        }
                    }
                </style>
                @if(app()->getLocale() == 'ar')
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h6 class="modal-title" id="activitiesModalLabel">
                        <span id="activitiesModalTitle">{{ __('messages.add_activity') }}</span><i class="fas fa-tasks ms-2 text-primary"></i>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @else
                <h6 class="modal-title" id="activitiesModalLabel">
                    <i class="fas fa-tasks me-2 text-primary"></i><span id="activitiesModalTitle">{{ __('messages.add_activity') }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body">
                <form id="activitiesForm">
                    <input type="hidden" id="activityId" name="activity_id">
                    <div class="mb-3">
                        <label class="form-label fw-medium">{{ __('messages.description') }}</label>
                    <div id="modalActivitiesContainer">
                        <div class="activity-field mb-2">
                                <input type="text" class="form-control Input_control" name="description[]" 
                                    placeholder="{{ __('messages.enter_activity_description') }}" maxlength="150" required>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" id="addMoreActivityBtn" class="btn btn-outline-primary btn-sm" onclick="addActivityField()" style="min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.add_more') }}
                            </button>
                            <button type="button" id="removeLastActivityBtn" class="btn btn-outline-danger btn-sm" onclick="removeLastActivityField()" style="display:none; min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn api-action-btn" onclick="saveActivity()" style="padding: 0.7rem 1.5rem;">
                    <span id="activitiesSaveBtn">{{ __('messages.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Manpower Equipment Modal -->
<div class="modal fade" id="manpowerModal" tabindex="-1" aria-labelledby="manpowerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #manpowerModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }
                    #manpowerModal .modal-header {
                        position: relative !important;
                    }
                    @media (max-width: 576px) {
                        #manpowerModal .modal-dialog {
                            margin: 0.5rem;
                        }
                        #manpowerModal .manpower-field .row > div {
                            flex: 0 0 100%;
                            max-width: 100%;
                        }
                        #manpowerModal .manpower-field .row > div:first-child {
                            margin-bottom: 0.5rem;
                        }
                        #manpowerModal .d-flex.gap-2 {
                            flex-direction: column;
                        }
                        #manpowerModal .d-flex.gap-2 button {
                            width: 100%;
                            min-width: auto;
                        }
                    }
                </style>
                @if(app()->getLocale() == 'ar')
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h6 class="modal-title" id="manpowerModalLabel">
                        <span id="manpowerModalTitle">{{ __('messages.add_manpower_equipment') }}</span><i class="fas fa-users ms-2 text-success"></i>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @else
                <h6 class="modal-title" id="manpowerModalLabel">
                    <i class="fas fa-users me-2 text-success"></i><span id="manpowerModalTitle">{{ __('messages.add_manpower_equipment') }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body">
                <form id="manpowerForm">
                    <input type="hidden" id="manpowerId" name="item_id">
                    <div class="mb-3">
                        <label class="form-label fw-medium">{{ __('messages.manpower_equipment') }}</label>
                    <div id="modalManpowerContainer">
                        <div class="manpower-field mb-2">
                                <div class="row">
                                <div class="col-7">
                                        <input type="text" class="form-control Input_control" name="category[]" 
                                            placeholder="{{ __('messages.enter_category') }}" maxlength="50" required>
                                </div>
                                    <div class="col-5">
                                        <input type="number" class="form-control Input_control" name="count[]" 
                                            placeholder="{{ __('messages.count') }}" min="0" max="2147483647" oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);" required>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" id="addMoreManpowerBtn" class="btn btn-outline-primary btn-sm" onclick="addManpowerField()" style="min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.add_more') }}
                            </button>
                            <button type="button" id="removeLastManpowerBtn" class="btn btn-outline-danger btn-sm" onclick="removeLastManpowerField()" style="display:none; min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn api-action-btn" onclick="saveManpower()" style="padding: 0.7rem 1.5rem;">
                    <span id="manpowerSaveBtn">{{ __('messages.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Safety Items Modal -->
<div class="modal fade" id="safetyModal" tabindex="-1" aria-labelledby="safetyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #safetyModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }
                    #safetyModal .modal-header {
                        position: relative !important;
                    }
                    @media (max-width: 576px) {
                        #safetyModal .modal-dialog {
                            margin: 0.5rem;
                        }
                        #safetyModal .d-flex.gap-2 {
                            flex-direction: column;
                        }
                        #safetyModal .d-flex.gap-2 button {
                            width: 100%;
                            min-width: auto;
                        }
                    }
                </style>
                @if(app()->getLocale() == 'ar')
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h6 class="modal-title" id="safetyModalLabel">
                        <span id="safetyModalTitle">{{ __('messages.add_safety_item') }}</span><i class="fas fa-shield-alt ms-2 text-warning"></i>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @else
                <h6 class="modal-title" id="safetyModalLabel">
                    <i class="fas fa-shield-alt me-2 text-warning"></i><span id="safetyModalTitle">{{ __('messages.add_safety_item') }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body">
                <form id="safetyForm">
                    <input type="hidden" id="safetyId" name="item_id">
                    <div class="mb-3">
                        <label class="form-label fw-medium">{{ __('messages.checklist_item') }}</label>
                    <div id="modalSafetyContainer">
                        <div class="safety-field mb-2">
                                <input type="text" class="form-control Input_control" name="checklist_item[]" 
                                    placeholder="{{ __('messages.enter_safety_item') }}" maxlength="120" required>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" id="addMoreSafetyBtn" class="btn btn-outline-primary btn-sm" onclick="addSafetyField()" style="min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.add_more') }}
                            </button>
                            <button type="button" id="removeLastSafetyBtn" class="btn btn-outline-danger btn-sm" onclick="removeLastSafetyField()" style="display:none; min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn api-action-btn" onclick="saveSafetyItem()" style="padding: 0.7rem 1.5rem;">
                    <span id="safetySaveBtn">{{ __('messages.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
// Modal Functions
function openActivitiesModal() {
    document.getElementById('activitiesModalTitle').textContent = '{{ __("messages.add_activity") }}';
    document.getElementById('activitiesSaveBtn').textContent = '{{ __("messages.save") }}';
    document.getElementById('activitiesForm').reset();
    document.getElementById('activityId').value = '';
    const container = document.getElementById('modalActivitiesContainer');
    container.innerHTML = `
        <div class="activity-field mb-2">
            <input type="text" class="form-control Input_control" name="description[]" 
                placeholder="{{ __('messages.enter_activity_description') }}" maxlength="150" required>
        </div>
    `;
    document.getElementById('removeLastActivityBtn').style.display = 'none';
    new bootstrap.Modal(document.getElementById('activitiesModal')).show();
}

function openManpowerModal() {
    document.getElementById('manpowerModalTitle').textContent = '{{ __("messages.add_manpower_equipment") }}';
    document.getElementById('manpowerSaveBtn').textContent = '{{ __("messages.save") }}';
    document.getElementById('manpowerForm').reset();
    document.getElementById('manpowerId').value = '';
    const container = document.getElementById('modalManpowerContainer');
    container.innerHTML = `
        <div class="manpower-field mb-2">
            <div class="row">
                <div class="col-7">
                    <input type="text" class="form-control Input_control" name="category[]" 
                        placeholder="{{ __('messages.enter_category') }}" maxlength="50" required>
                </div>
                <div class="col-5">
                    <input type="number" class="form-control Input_control" name="count[]" 
                        placeholder="{{ __('messages.count') }}" min="0" max="2147483647" oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);" required>
                </div>
            </div>
        </div>
    `;
    document.getElementById('removeLastManpowerBtn').style.display = 'none';
    new bootstrap.Modal(document.getElementById('manpowerModal')).show();
}

function openSafetyModal() {
    document.getElementById('safetyModalTitle').textContent = '{{ __("messages.add_safety_item") }}';
    document.getElementById('safetySaveBtn').textContent = '{{ __("messages.save") }}';
    document.getElementById('safetyForm').reset();
    document.getElementById('safetyId').value = '';
    const container = document.getElementById('modalSafetyContainer');
    container.innerHTML = `
        <div class="safety-field mb-2">
            <input type="text" class="form-control Input_control" name="checklist_item[]" 
                placeholder="{{ __('messages.enter_safety_item') }}" maxlength="120" required>
        </div>
    `;
    document.getElementById('removeLastSafetyBtn').style.display = 'none';
    new bootstrap.Modal(document.getElementById('safetyModal')).show();
}

// Edit Functions
function editActivity(id, description) {
    document.getElementById('activitiesModalTitle').textContent = '{{ __("messages.edit_activity") }}';
    document.getElementById('activitiesSaveBtn').textContent = '{{ __("messages.update") }}';
    document.getElementById('activityId').value = id;
    document.getElementById('activityDescription').value = description;
    new bootstrap.Modal(document.getElementById('activitiesModal')).show();
}

function editManpower(id, category, count) {
    document.getElementById('manpowerModalTitle').textContent = '{{ __("messages.edit_manpower_equipment") }}';
    document.getElementById('manpowerSaveBtn').textContent = '{{ __("messages.update") }}';
    document.getElementById('manpowerId').value = id;
    document.getElementById('manpowerCategory').value = category;
    document.getElementById('manpowerCount').value = count;
    new bootstrap.Modal(document.getElementById('manpowerModal')).show();
}

function editSafetyItem(id, item) {
    document.getElementById('safetyModalTitle').textContent = '{{ __("messages.edit_safety_item") }}';
    document.getElementById('safetySaveBtn').textContent = '{{ __("messages.update") }}';
    document.getElementById('safetyId').value = id;
    document.getElementById('safetyItem').value = item;
    new bootstrap.Modal(document.getElementById('safetyModal')).show();
}

// Dynamic Field Functions
function addActivityField() {
    const container = document.getElementById('modalActivitiesContainer');
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'activity-field mb-2';
    fieldDiv.innerHTML = `
        <input type="text" class="form-control Input_control" name="description[]" 
            placeholder="{{ __('messages.enter_activity_description') }}" maxlength="150" required>
    `;
    container.appendChild(fieldDiv);
    updateRemoveButton('removeLastActivityBtn', container);
}

function removeLastActivityField() {
    const container = document.getElementById('modalActivitiesContainer');
    if (container.children.length > 1) {
        container.removeChild(container.lastChild);
        updateRemoveButton('removeLastActivityBtn', container);
    }
}

function addManpowerField() {
    const container = document.getElementById('modalManpowerContainer');
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'manpower-field mb-2';
    fieldDiv.innerHTML = `
        <div class="row">
            <div class="col-7">
                <input type="text" class="form-control Input_control" name="category[]" 
                    placeholder="{{ __('messages.enter_category') }}" maxlength="50" required>
            </div>
            <div class="col-5">
                <input type="number" class="form-control Input_control" name="count[]" 
                    placeholder="{{ __('messages.count') }}" min="0" max="2147483647" oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);" required>
            </div>
        </div>
    `;
    container.appendChild(fieldDiv);
    updateRemoveButton('removeLastManpowerBtn', container);
}

function removeLastManpowerField() {
    const container = document.getElementById('modalManpowerContainer');
    if (container.children.length > 1) {
        container.removeChild(container.lastChild);
        updateRemoveButton('removeLastManpowerBtn', container);
    }
}

function addSafetyField() {
    const container = document.getElementById('modalSafetyContainer');
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'safety-field mb-2';
    fieldDiv.innerHTML = `
        <input type="text" class="form-control Input_control" name="checklist_item[]" 
            placeholder="{{ __('messages.enter_safety_item') }}" maxlength="120" required>
    `;
    container.appendChild(fieldDiv);
    updateRemoveButton('removeLastSafetyBtn', container);
}

function removeLastSafetyField() {
    const container = document.getElementById('modalSafetyContainer');
    if (container.children.length > 1) {
        container.removeChild(container.lastChild);
        updateRemoveButton('removeLastSafetyBtn', container);
    }
}

function updateRemoveButton(buttonId, container) {
    const removeBtn = document.getElementById(buttonId);
        if (removeBtn) {
        removeBtn.style.display = container.children.length > 1 ? 'inline-block' : 'none';
    }
}

// Save Functions
async function saveActivity() {
    const form = document.getElementById('activitiesForm');
    const activityId = document.getElementById('activityId').value;
    const descriptions = Array.from(form.querySelectorAll('input[name="description[]"]'))
        .map(input => input.value.trim())
        .filter(desc => desc);
    
    if (descriptions.length === 0) {
        alert('{{ __("messages.please_enter_description") }}');
        return;
    }
    
    const saveBtn = document.getElementById('activitiesSaveBtn');
    const originalText = saveBtn.textContent;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __("messages.saving") }}';
    
    try {
        let response;
        if (activityId && descriptions.length === 1) {
            response = await api.updateActivity({
                activity_id: activityId,
                description: descriptions[0]
            });
        } else {
            response = await api.addActivity({
                project_id: currentProjectId,
                user_id: currentUserId,
                descriptions: descriptions
            });
        }
        
        if (response.code === 200) {
            bootstrap.Modal.getInstance(document.getElementById('activitiesModal')).hide();
            loadActivities();
            alert(response.message || '{{ __("messages.activities_saved_successfully") }}');
        } else {
            alert(response.message || '{{ __("messages.failed_to_save_activity") }}');
        }
    } catch (error) {
        alert('{{ __("messages.error_saving_activity") }}');
    } finally {
        saveBtn.textContent = originalText;
    }
}

async function saveManpower() {
    const form = document.getElementById('manpowerForm');
    const itemId = document.getElementById('manpowerId').value;
    const categories = Array.from(form.querySelectorAll('input[name="category[]"]')).map(input => input.value.trim());
    const counts = Array.from(form.querySelectorAll('input[name="count[]"]')).map(input => parseInt(input.value));
    
    const validItems = [];
    for (let i = 0; i < categories.length; i++) {
        if (categories[i] && !isNaN(counts[i]) && counts[i] >= 0) {
            validItems.push({ category: categories[i], count: counts[i] });
        }
    }
    
    if (validItems.length === 0) {
        alert('{{ __("messages.please_enter_valid_data") }}');
        return;
    }
    
    const saveBtn = document.getElementById('manpowerSaveBtn');
    const originalText = saveBtn.textContent;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __("messages.saving") }}';
    
    try {
        let response;
        if (itemId && validItems.length === 1) {
            response = await api.updateManpowerEquipment({
                item_id: itemId,
                category: validItems[0].category,
                count: validItems[0].count
            });
        } else {
            response = await api.addManpowerEquipment({
                project_id: currentProjectId,
                user_id: currentUserId,
                items: validItems
            });
        }
        
        if (response.code === 200) {
            bootstrap.Modal.getInstance(document.getElementById('manpowerModal')).hide();
            loadManpowerEquipment();
            alert(response.message || '{{ __("messages.manpower_items_saved_successfully") }}');
        } else {
            alert(response.message || '{{ __("messages.failed_to_save_manpower") }}');
        }
    } catch (error) {
        alert('{{ __("messages.error_saving_manpower") }}');
    } finally {
        saveBtn.textContent = originalText;
    }
}

async function saveSafetyItem() {
    const form = document.getElementById('safetyForm');
    const itemId = document.getElementById('safetyId').value;
    const checklistItems = Array.from(form.querySelectorAll('input[name="checklist_item[]"]'))
        .map(input => input.value.trim())
        .filter(item => item);
    
    if (checklistItems.length === 0) {
        alert('{{ __("messages.please_enter_safety_item") }}');
        return;
    }
    
    const saveBtn = document.getElementById('safetySaveBtn');
    const originalText = saveBtn.textContent;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __("messages.saving") }}';
    
    try {
        let response;
        if (itemId && checklistItems.length === 1) {
            response = await api.updateSafetyItem({
                item_id: itemId,
                checklist_item: checklistItems[0]
            });
        } else {
            response = await api.addSafetyItem({
                project_id: currentProjectId,
                user_id: currentUserId,
                checklist_items: checklistItems
            });
        }
        
        if (response.code === 200) {
            bootstrap.Modal.getInstance(document.getElementById('safetyModal')).hide();
            loadSafetyItems();
            alert(response.message || '{{ __("messages.safety_items_saved_successfully") }}');
        } else {
            alert(response.message || '{{ __("messages.failed_to_save_safety_item") }}');
        }
    } catch (error) {
        alert('{{ __("messages.error_saving_safety_item") }}');
    } finally {
        saveBtn.textContent = originalText;
    }
}
</script>
