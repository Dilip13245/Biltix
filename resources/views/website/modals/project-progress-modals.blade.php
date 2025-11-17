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
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h6 class="modal-title" id="activitiesModalLabel">
                            <span id="activitiesModalTitle">{{ __('messages.add_activity') }}</span>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h6 class="modal-title" id="activitiesModalLabel">
                        <span id="activitiesModalTitle">{{ __('messages.add_activity') }}</span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.close') }}"></button>
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
                                    placeholder="{{ __('messages.enter_activity_description') }}" maxlength="150"
                                    required>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" id="addMoreActivityBtn" class="btn btn-outline-primary btn-sm"
                                onclick="addActivityField()" style="min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.add_more') }}
                            </button>
                            <button type="button" id="removeLastActivityBtn" class="btn btn-outline-danger btn-sm"
                                onclick="removeLastActivityField()"
                                style="display:none; min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"
                    style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn api-action-btn" onclick="saveActivity()"
                    style="padding: 0.7rem 1.5rem;">
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

                        #manpowerModal .manpower-field .row>div {
                            flex: 0 0 100%;
                            max-width: 100%;
                        }

                        #manpowerModal .manpower-field .row>div:first-child {
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
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h6 class="modal-title" id="manpowerModalLabel">
                            <span id="manpowerModalTitle">{{ __('messages.add_manpower_equipment') }}</span>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h6 class="modal-title" id="manpowerModalLabel">
                        <span id="manpowerModalTitle">{{ __('messages.add_manpower_equipment') }}</span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.close') }}"></button>
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
                                            placeholder="{{ __('messages.count') }}" min="0" max="2147483647"
                                            oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" id="addMoreManpowerBtn" class="btn btn-outline-primary btn-sm"
                                onclick="addManpowerField()" style="min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.add_more') }}
                            </button>
                            <button type="button" id="removeLastManpowerBtn" class="btn btn-outline-danger btn-sm"
                                onclick="removeLastManpowerField()"
                                style="display:none; min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"
                    style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn api-action-btn" onclick="saveManpower()"
                    style="padding: 0.7rem 1.5rem;">
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
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h6 class="modal-title" id="safetyModalLabel">
                            <span id="safetyModalTitle">{{ __('messages.add_safety_item') }}</span>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h6 class="modal-title" id="safetyModalLabel">
                        <span id="safetyModalTitle">{{ __('messages.add_safety_item') }}</span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.close') }}"></button>
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
                            <button type="button" id="addMoreSafetyBtn" class="btn btn-outline-primary btn-sm"
                                onclick="addSafetyField()" style="min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.add_more') }}
                            </button>
                            <button type="button" id="removeLastSafetyBtn" class="btn btn-outline-danger btn-sm"
                                onclick="removeLastSafetyField()"
                                style="display:none; min-width: 120px; padding: 0.5rem 1rem;">
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"
                    style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-sm orange_btn api-action-btn" onclick="saveSafetyItem()"
                    style="padding: 0.7rem 1.5rem;">
                    <span id="safetySaveBtn">{{ __('messages.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    // Modal Functions
    function openActivitiesModal() {
        const titleEl = document.getElementById('activitiesModalTitle');
        const saveBtnSpan = document.getElementById('activitiesSaveBtn');
        if (titleEl) titleEl.textContent = '{{ __('messages.add_activity') }}';
        if (saveBtnSpan) saveBtnSpan.textContent = '{{ __('messages.save') }}';

        const form = document.getElementById('activitiesForm');
        const activityId = document.getElementById('activityId');
        if (form) form.reset();
        if (activityId) activityId.value = '';

        const container = document.getElementById('modalActivitiesContainer');
        if (container) {
            container.innerHTML = `
        <div class="activity-field mb-2">
            <input type="text" class="form-control Input_control" name="description[]" 
                placeholder="{{ __('messages.enter_activity_description') }}" maxlength="150" required>
        </div>
    `;
        }

        const removeBtn = document.getElementById('removeLastActivityBtn');
        if (removeBtn) removeBtn.style.display = 'none';
        new bootstrap.Modal(document.getElementById('activitiesModal')).show();
    }

    function openManpowerModal() {
        const titleEl = document.getElementById('manpowerModalTitle');
        const saveBtnSpan = document.getElementById('manpowerSaveBtn');
        if (titleEl) titleEl.textContent = '{{ __('messages.add_manpower_equipment') }}';
        if (saveBtnSpan) saveBtnSpan.textContent = '{{ __('messages.save') }}';

        const form = document.getElementById('manpowerForm');
        const manpowerId = document.getElementById('manpowerId');
        if (form) form.reset();
        if (manpowerId) manpowerId.value = '';

        const container = document.getElementById('modalManpowerContainer');
        if (container) {
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
        }

        const removeBtn = document.getElementById('removeLastManpowerBtn');
        if (removeBtn) removeBtn.style.display = 'none';
        new bootstrap.Modal(document.getElementById('manpowerModal')).show();
    }

    function openSafetyModal() {
        const titleEl = document.getElementById('safetyModalTitle');
        const saveBtnSpan = document.getElementById('safetySaveBtn');
        if (titleEl) titleEl.textContent = '{{ __('messages.add_safety_item') }}';
        if (saveBtnSpan) saveBtnSpan.textContent = '{{ __('messages.save') }}';

        const form = document.getElementById('safetyForm');
        const safetyId = document.getElementById('safetyId');
        if (form) form.reset();
        if (safetyId) safetyId.value = '';

        const container = document.getElementById('modalSafetyContainer');
        if (container) {
            container.innerHTML = `
        <div class="safety-field mb-2">
            <input type="text" class="form-control Input_control" name="checklist_item[]" 
                placeholder="{{ __('messages.enter_safety_item') }}" maxlength="120" required>
        </div>
    `;
        }

        const removeBtn = document.getElementById('removeLastSafetyBtn');
        if (removeBtn) removeBtn.style.display = 'none';
        new bootstrap.Modal(document.getElementById('safetyModal')).show();
    }

    // Edit Functions
    function editActivity(id, description) {
        const titleEl = document.getElementById('activitiesModalTitle');
        const saveBtnSpan = document.getElementById('activitiesSaveBtn');
        const activityIdEl = document.getElementById('activityId');
        const activityDescEl = document.getElementById('activityDescription');

        if (titleEl) titleEl.textContent = '{{ __('messages.edit_activity') }}';
        if (saveBtnSpan) saveBtnSpan.textContent = '{{ __('messages.update') }}';
        if (activityIdEl) activityIdEl.value = id;
        if (activityDescEl) activityDescEl.value = description;
        new bootstrap.Modal(document.getElementById('activitiesModal')).show();
    }

    function editManpower(id, category, count) {
        const titleEl = document.getElementById('manpowerModalTitle');
        const saveBtnSpan = document.getElementById('manpowerSaveBtn');
        const manpowerIdEl = document.getElementById('manpowerId');
        const manpowerCategoryEl = document.getElementById('manpowerCategory');
        const manpowerCountEl = document.getElementById('manpowerCount');

        if (titleEl) titleEl.textContent = '{{ __('messages.edit_manpower_equipment') }}';
        if (saveBtnSpan) saveBtnSpan.textContent = '{{ __('messages.update') }}';
        if (manpowerIdEl) manpowerIdEl.value = id;
        if (manpowerCategoryEl) manpowerCategoryEl.value = category;
        if (manpowerCountEl) manpowerCountEl.value = count;
        new bootstrap.Modal(document.getElementById('manpowerModal')).show();
    }

    function editSafetyItem(id, item) {
        const titleEl = document.getElementById('safetyModalTitle');
        const saveBtnSpan = document.getElementById('safetySaveBtn');
        const safetyIdEl = document.getElementById('safetyId');
        const safetyItemEl = document.getElementById('safetyItem');

        if (titleEl) titleEl.textContent = '{{ __('messages.edit_safety_item') }}';
        if (saveBtnSpan) saveBtnSpan.textContent = '{{ __('messages.update') }}';
        if (safetyIdEl) safetyIdEl.value = id;
        if (safetyItemEl) safetyItemEl.value = item;
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
        const activityIdEl = document.getElementById('activityId');
        const activityId = activityIdEl ? activityIdEl.value : '';
        const descriptions = Array.from(form.querySelectorAll('input[name="description[]"]'))
            .map(input => input.value.trim())
            .filter(desc => desc);

        if (descriptions.length === 0) {
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.please_enter_description') }}');
            } else {
                alert('{{ __('messages.please_enter_description') }}');
            }
            return;
        }

        // Get the button element (parent of the span with id activitiesSaveBtn)
        const saveBtnSpan = document.getElementById('activitiesSaveBtn');
        const saveBtn = saveBtnSpan ? saveBtnSpan.closest('button') : document.querySelector(
            '#activitiesModal button[onclick="saveActivity()"]');

        if (!saveBtn) {
            console.error('Save button not found');
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.error_saving_activity') }}');
            } else {
                alert('{{ __('messages.error_saving_activity') }}');
            }
            return;
        }

        // Store original content
        const originalContent = saveBtn.innerHTML;

        // Protect button and show loading
        if (window.protectButton) {
            window.protectButton(saveBtn);
        } else {
            saveBtn.disabled = true;
            if (saveBtnSpan) {
                saveBtnSpan.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';
            } else {
                saveBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';
            }
        }

        try {
            let response;
            if (activityId && descriptions.length === 1) {
                response = await api.updateActivity({
                    activity_id: activityId,
                    description: descriptions[0]
                }, saveBtn);
            } else {
                response = await api.addActivity({
                    project_id: currentProjectId,
                    user_id: currentUserId,
                    descriptions: descriptions
                }, saveBtn);
            }

            if (response.code === 200) {
                bootstrap.Modal.getInstance(document.getElementById('activitiesModal')).hide();
                if (typeof loadActivities === 'function') {
                    loadActivities();
                }
                if (typeof toastr !== 'undefined') {
                    toastr.success(response.message || '{{ __('messages.activities_saved_successfully') }}');
                } else {
                    alert(response.message || '{{ __('messages.activities_saved_successfully') }}');
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(response.message || '{{ __('messages.failed_to_save_activity') }}');
                } else {
                    alert(response.message || '{{ __('messages.failed_to_save_activity') }}');
                }
            }
        } catch (error) {
            console.error('Error saving activity:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.error_saving_activity') }}');
            } else {
                alert('{{ __('messages.error_saving_activity') }}');
            }
        } finally {
            // Release button if protection system is available
            if (window.releaseButton) {
                window.releaseButton(saveBtn);
            } else {
                // Fallback: restore manually
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalContent;
            }
        }
    }

    async function saveManpower() {
        const form = document.getElementById('manpowerForm');
        const itemIdEl = document.getElementById('manpowerId');
        const itemId = itemIdEl ? itemIdEl.value : '';
        const categories = Array.from(form.querySelectorAll('input[name="category[]"]')).map(input => input.value
            .trim());
        const counts = Array.from(form.querySelectorAll('input[name="count[]"]')).map(input => parseInt(input
            .value));

        const validItems = [];
        for (let i = 0; i < categories.length; i++) {
            if (categories[i] && !isNaN(counts[i]) && counts[i] >= 0) {
                validItems.push({
                    category: categories[i],
                    count: counts[i]
                });
            }
        }

        if (validItems.length === 0) {
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.please_enter_valid_data') }}');
            } else {
                alert('{{ __('messages.please_enter_valid_data') }}');
            }
            return;
        }

        // Get the button element (parent of the span with id manpowerSaveBtn)
        const saveBtnSpan = document.getElementById('manpowerSaveBtn');
        const saveBtn = saveBtnSpan ? saveBtnSpan.closest('button') : document.querySelector(
            '#manpowerModal button[onclick="saveManpower()"]');

        if (!saveBtn) {
            console.error('Save button not found');
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.error_saving_manpower') }}');
            } else {
                alert('{{ __('messages.error_saving_manpower') }}');
            }
            return;
        }

        // Store original content
        const originalContent = saveBtn.innerHTML;

        // Protect button and show loading
        if (window.protectButton) {
            window.protectButton(saveBtn);
        } else {
            saveBtn.disabled = true;
            if (saveBtnSpan) {
                saveBtnSpan.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';
            } else {
                saveBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';
            }
        }

        try {
            let response;
            if (itemId && validItems.length === 1) {
                response = await api.updateManpowerEquipment({
                    item_id: itemId,
                    category: validItems[0].category,
                    count: validItems[0].count
                }, saveBtn);
            } else {
                response = await api.addManpowerEquipment({
                    project_id: currentProjectId,
                    user_id: currentUserId,
                    items: validItems
                }, saveBtn);
            }

            if (response.code === 200) {
                bootstrap.Modal.getInstance(document.getElementById('manpowerModal')).hide();
                if (typeof loadManpowerEquipment === 'function') {
                    loadManpowerEquipment();
                }
                if (typeof toastr !== 'undefined') {
                    toastr.success(response.message || '{{ __('messages.manpower_items_saved_successfully') }}');
                } else {
                    alert(response.message || '{{ __('messages.manpower_items_saved_successfully') }}');
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(response.message || '{{ __('messages.failed_to_save_manpower') }}');
                } else {
                    alert(response.message || '{{ __('messages.failed_to_save_manpower') }}');
                }
            }
        } catch (error) {
            console.error('Error saving manpower:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.error_saving_manpower') }}');
            } else {
                alert('{{ __('messages.error_saving_manpower') }}');
            }
        } finally {
            // Release button if protection system is available
            if (window.releaseButton) {
                window.releaseButton(saveBtn);
            } else {
                // Fallback: restore manually
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalContent;
            }
        }
    }

    async function saveSafetyItem() {
        const form = document.getElementById('safetyForm');
        const itemIdEl = document.getElementById('safetyId');
        const itemId = itemIdEl ? itemIdEl.value : '';
        const checklistItems = Array.from(form.querySelectorAll('input[name="checklist_item[]"]'))
            .map(input => input.value.trim())
            .filter(item => item);

        if (checklistItems.length === 0) {
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.please_enter_safety_item') }}');
            } else {
                alert('{{ __('messages.please_enter_safety_item') }}');
            }
            return;
        }

        // Get the button element (parent of the span with id safetySaveBtn)
        const saveBtnSpan = document.getElementById('safetySaveBtn');
        const saveBtn = saveBtnSpan ? saveBtnSpan.closest('button') : document.querySelector(
            '#safetyModal button[onclick="saveSafetyItem()"]');

        if (!saveBtn) {
            console.error('Save button not found');
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.error_saving_safety_item') }}');
            } else {
                alert('{{ __('messages.error_saving_safety_item') }}');
            }
            return;
        }

        // Store original content
        const originalContent = saveBtn.innerHTML;

        // Protect button and show loading
        if (window.protectButton) {
            window.protectButton(saveBtn);
        } else {
            saveBtn.disabled = true;
            if (saveBtnSpan) {
                saveBtnSpan.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';
            } else {
                saveBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';
            }
        }

        try {
            let response;
            if (itemId && checklistItems.length === 1) {
                response = await api.updateSafetyItem({
                    item_id: itemId,
                    checklist_item: checklistItems[0]
                }, saveBtn);
            } else {
                response = await api.addSafetyItem({
                    project_id: currentProjectId,
                    user_id: currentUserId,
                    checklist_items: checklistItems
                }, saveBtn);
            }

            if (response.code === 200) {
                bootstrap.Modal.getInstance(document.getElementById('safetyModal')).hide();
                if (typeof loadSafetyItems === 'function') {
                    loadSafetyItems();
                }
                if (typeof toastr !== 'undefined') {
                    toastr.success(response.message || '{{ __('messages.safety_items_saved_successfully') }}');
                } else {
                    alert(response.message || '{{ __('messages.safety_items_saved_successfully') }}');
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(response.message || '{{ __('messages.failed_to_save_safety_item') }}');
                } else {
                    alert(response.message || '{{ __('messages.failed_to_save_safety_item') }}');
                }
            }
        } catch (error) {
            console.error('Error saving safety item:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('{{ __('messages.error_saving_safety_item') }}');
            } else {
                alert('{{ __('messages.error_saving_safety_item') }}');
            }
        } finally {
            // Release button if protection system is available
            if (window.releaseButton) {
                window.releaseButton(saveBtn);
            } else {
                // Fallback: restore manually
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalContent;
            }
        }
    }
</script>
