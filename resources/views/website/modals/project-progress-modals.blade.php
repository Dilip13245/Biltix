<!-- Activities Modal -->
<div class="modal fade" id="activitiesModal" tabindex="-1" aria-labelledby="activitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
            <div class="modal-body p-3">
                <form id="activitiesForm">
                    <input type="hidden" id="activityId" name="activity_id">
                    <div id="modalActivitiesContainer">
                        <div class="activity-field mb-2">
                            <label class="form-label small fw-medium mb-1">{{ __('messages.description') }}</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="description[]" 
                                    placeholder="{{ __('messages.enter_activity_description') }}" required>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-field" onclick="removeField(this)" style="display:none;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addMoreActivityBtn" class="btn btn-outline-primary btn-sm" onclick="addActivityField()">
                        <i class="fas fa-plus {{ margin_end(1) }}"></i>{{ __('messages.add_more') }}
                    </button>
                </form>
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn" onclick="saveActivity()">
                    <i class="fas fa-save {{ margin_end(1) }}"></i><span id="activitiesSaveBtn">{{ __('messages.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Manpower Equipment Modal -->
<div class="modal fade" id="manpowerModal" tabindex="-1" aria-labelledby="manpowerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
            <div class="modal-body p-3">
                <form id="manpowerForm">
                    <input type="hidden" id="manpowerId" name="item_id">
                    <div id="modalManpowerContainer">
                        <div class="manpower-field mb-2">
                            <div class="row g-2">
                                <div class="col-7">
                                    <label class="form-label small fw-medium mb-1">{{ __('messages.category') }}</label>
                                    <input type="text" class="form-control form-control-sm" name="category[]" 
                                        placeholder="{{ __('messages.enter_category') }}" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label small fw-medium mb-1">{{ __('messages.count') }}</label>
                                    <input type="number" class="form-control form-control-sm" name="count[]" 
                                        placeholder="0" min="0" required>
                                </div>
                                <div class="col-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-field" onclick="removeField(this)" style="display:none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addMoreManpowerBtn" class="btn btn-outline-primary btn-sm" onclick="addManpowerField()">
                        <i class="fas fa-plus {{ margin_end(1) }}"></i>{{ __('messages.add_more') }}
                    </button>
                </form>
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn" onclick="saveManpower()">
                    <i class="fas fa-save {{ margin_end(1) }}"></i><span id="manpowerSaveBtn">{{ __('messages.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Safety Items Modal -->
<div class="modal fade" id="safetyModal" tabindex="-1" aria-labelledby="safetyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
            <div class="modal-body p-3">
                <form id="safetyForm">
                    <input type="hidden" id="safetyId" name="item_id">
                    <div id="modalSafetyContainer">
                        <div class="safety-field mb-2">
                            <label class="form-label small fw-medium mb-1">{{ __('messages.checklist_item') }}</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="checklist_item[]" 
                                    placeholder="{{ __('messages.enter_safety_item') }}" required>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-field" onclick="removeField(this)" style="display:none;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addMoreSafetyBtn" class="btn btn-outline-primary btn-sm" onclick="addSafetyField()">
                        <i class="fas fa-plus {{ margin_end(1) }}"></i>{{ __('messages.add_more') }}
                    </button>
                </form>
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn" onclick="saveSafetyItem()">
                    <i class="fas fa-save {{ margin_end(1) }}"></i><span id="safetySaveBtn">{{ __('messages.save') }}</span>
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
    new bootstrap.Modal(document.getElementById('activitiesModal')).show();
}

function openManpowerModal() {
    document.getElementById('manpowerModalTitle').textContent = '{{ __("messages.add_manpower_equipment") }}';
    document.getElementById('manpowerSaveBtn').textContent = '{{ __("messages.save") }}';
    document.getElementById('manpowerForm').reset();
    document.getElementById('manpowerId').value = '';
    new bootstrap.Modal(document.getElementById('manpowerModal')).show();
}

function openSafetyModal() {
    document.getElementById('safetyModalTitle').textContent = '{{ __("messages.add_safety_item") }}';
    document.getElementById('safetySaveBtn').textContent = '{{ __("messages.save") }}';
    document.getElementById('safetyForm').reset();
    document.getElementById('safetyId').value = '';
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
    const container = document.getElementById('activitiesContainer');
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'activity-field mb-3';
    fieldDiv.innerHTML = `
        <div class="d-flex">
            <textarea class="form-control" name="description[]" rows="3" 
                placeholder="{{ __('messages.enter_activity_description') }}" required></textarea>
            <button type="button" class="btn btn-danger {{ margin_start(2) }} remove-field" onclick="removeField(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(fieldDiv);
    updateRemoveButtons('activitiesContainer');
}

function addManpowerField() {
    const container = document.getElementById('manpowerContainer');
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'manpower-field mb-3';
    fieldDiv.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control" name="category[]" 
                    placeholder="{{ __('messages.enter_category') }}" required>
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control" name="count[]" 
                    placeholder="{{ __('messages.enter_count') }}" min="0" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-field" onclick="removeField(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(fieldDiv);
    updateRemoveButtons('manpowerContainer');
}

function addSafetyField() {
    const container = document.getElementById('safetyContainer');
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'safety-field mb-3';
    fieldDiv.innerHTML = `
        <div class="d-flex">
            <textarea class="form-control" name="checklist_item[]" rows="3" 
                placeholder="{{ __('messages.enter_safety_item') }}" required></textarea>
            <button type="button" class="btn btn-danger {{ margin_start(2) }} remove-field" onclick="removeField(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(fieldDiv);
    updateRemoveButtons('safetyContainer');
}

function removeField(button) {
    const fieldDiv = button.closest('.activity-field, .manpower-field, .safety-field');
    const container = fieldDiv.parentNode;
    fieldDiv.remove();
    updateRemoveButtons(container.id);
}

function updateRemoveButtons(containerId) {
    const container = document.getElementById(containerId);
    const fields = container.children;
    for (let i = 0; i < fields.length; i++) {
        const removeBtn = fields[i].querySelector('.remove-field');
        if (removeBtn) {
            removeBtn.style.display = fields.length > 1 ? 'block' : 'none';
        }
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