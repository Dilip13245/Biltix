@extends('website.layout.app')

@section('title', __('messages.submittals'))

@section('content')
    <style>
        .raw-material-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            background: #fff;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .raw-material-card:hover {
            transform: translateY(-5px);
        }

        .rm-image-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .rm-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rm-content {
            padding: 16px;
        }

        .rm-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .rm-date {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .rm-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #842029;
        }

        .rm-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f0f0f0;
            padding-top: 12px;
            margin-top: 12px;
        }

        .rm-author {
            font-size: 0.9rem;
            font-weight: 500;
            color: #333;
        }

        .add-btn-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #F58D2E;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(245, 141, 46, 0.4);
            z-index: 100;
            border: none;
            cursor: pointer;
        }

        /* RTL Support */
        [dir="rtl"] .add-btn-float {
            right: auto;
            left: 30px;
        }

        [dir="rtl"] input,
        [dir="rtl"] textarea,
        [dir="rtl"] select {
            text-align: right;
            direction: rtl;
        }

        [dir="rtl"] input::placeholder,
        [dir="rtl"] textarea::placeholder {
            text-align: right;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">{{ __('messages.submittals') }}</h2>
                <p class="text-muted mb-0">{{ __('messages.manage_view_raw_materials') }}</p>
            </div>
            @can('raw_materials', 'create')
                <button class="btn orange_btn" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                    {{ __('messages.add_material') }}
                </button>
            @endcan
        </div>

        <div class="row g-4" id="rawMaterialsContainer">
            <!-- Materials will be loaded here -->
        </div>
    </div>

    <!-- Add Material Modal -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <style>
                        #addMaterialModal .form-control.is-invalid {
                            border-color: #dc3545 !important;
                            border-width: 2px !important;
                            background-image: none;
                        }

                        #addMaterialModal .form-control.is-valid {
                            border-color: #28a745 !important;
                            border-width: 2px !important;
                            background-image: none;
                        }

                        #addMaterialModal .form-select.is-invalid {
                            border-color: #dc3545 !important;
                            border-width: 2px !important;
                        }

                        #addMaterialModal .form-select.is-valid {
                            border-color: #28a745 !important;
                            border-width: 2px !important;
                        }

                        #addMaterialModal .invalid-feedback {
                            display: block;
                            width: 100%;
                            margin-top: 0.25rem;
                            font-size: 0.875rem;
                            color: #dc3545;
                        }

                        #addMaterialModal .form-control.is-invalid:focus,
                        #addMaterialModal .form-select.is-invalid:focus {
                            border-color: #dc3545 !important;
                            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
                        }

                        #addMaterialModal .form-control.is-valid:focus,
                        #addMaterialModal .form-select.is-valid:focus {
                            border-color: #28a745 !important;
                            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
                        }

                        #addMaterialModal .modal-footer {
                            gap: 10px;
                        }

                        #addMaterialModal .modal-footer .btn {
                            flex: 1;
                            min-width: 120px;
                            padding: 0.7rem 1.5rem;
                            font-weight: 500;
                        }

                        @media (max-width: 768px) {
                            #addMaterialModal .modal-dialog {
                                margin: 0.5rem;
                                max-width: calc(100% - 1rem);
                            }

                            #addMaterialModal .modal-body {
                                padding: 1rem;
                            }

                            #addMaterialModal .modal-footer {
                                flex-direction: column;
                                gap: 8px;
                            }

                            #addMaterialModal .modal-footer .btn {
                                width: 100%;
                            }

                            .form-control-lg {
                                font-size: 14px !important;
                                padding: 0.75rem !important;
                            }

                            .upload-zone {
                                padding: 2rem 1rem !important;
                            }

                            .upload-zone i {
                                font-size: 2rem !important;
                            }

                            .upload-zone h6 {
                                font-size: 14px;
                            }

                            .upload-zone small {
                                font-size: 11px;
                            }
                        }

                        @media (max-width: 576px) {
                            .modal-title {
                                font-size: 1.1rem;
                            }

                            #addMaterialModal .modal-footer .btn {
                                padding: 0.65rem 1rem;
                                font-size: 14px;
                            }

                            .rm-content {
                                padding: 0.75rem;
                            }

                            .rm-header,
                            .rm-footer {
                                flex-direction: column;
                                align-items: flex-start !important;
                                gap: 0.5rem;
                            }
                        }

                        #addMaterialModal .modal-header .btn-close {
                            position: static !important;
                            right: auto !important;
                            top: auto !important;
                            margin: 0 !important;
                        }

                        #addMaterialModal .modal-header {
                            position: relative !important;
                        }
                    </style>
                    @if (app()->getLocale() == 'ar')
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h5 class="modal-title">{{ __('messages.raw_materials') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="{{ __('messages.close') }}"></button>
                        </div>
                    @else
                        <h5 class="modal-title">{{ __('messages.submittals') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    @endif
                </div>
                <div class="modal-body pt-4">
                    <form id="addMaterialForm" class="protected-form" enctype="multipart/form-data" novalidate>
                        @csrf
                        <!-- File Upload Area -->
                        <div class="mb-4">
                            <div class="upload-zone text-center p-4 border-2 border-dashed rounded-3"
                                style="background-color: #f8f9fa; border-color: #dee2e6; cursor: pointer;"
                                onclick="document.getElementById('materialFile').click()">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h6 class="mb-1" id="fileNameDisplay">{{ __('messages.drop_files_here') }}</h6>
                                <small class="text-muted">{{ __('messages.support_files_format') }}</small>
                                <input type="file" id="materialFile" name="image" class="d-none" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="custom-filter-dropdown" id="typeFilterWrapper">
                                <div class="custom-filter-btn" id="typeFilterBtn">{{ __('messages.select_type') }}</div>
                                <div class="custom-filter-options" id="typeFilterOptions">
                                    <div class="custom-filter-option selected" data-value="">{{ __('messages.select_type') }}</div>
                                    <div class="custom-filter-option" data-value="drawing">{{ __('messages.drawing') }}</div>
                                    <div class="custom-filter-option" data-value="material">{{ __('messages.material') }}</div>
                                </div>
                                <select id="materialType" name="type" class="form-select" style="display: none;" required onchange="toggleQuantityField()">
                                    <option value="">{{ __('messages.select_type') }}</option>
                                    <option value="drawing">{{ __('messages.drawing') }}</option>
                                    <option value="material">{{ __('messages.material') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="text" id="materialName" name="name"
                                class="form-control form-control-lg Input_control" dir="auto"
                                placeholder="{{ __('messages.name') }}" required>
                        </div>


                        <div class="mb-3" id="quantityContainer" style="display: none;">
                            <input type="number" id="materialQuantity" name="quantity"
                                class="form-control form-control-lg Input_control" dir="auto"
                                placeholder="{{ __('messages.quantity') }}" min="1">
                        </div>

                        <div class="mb-3">
                            <textarea id="materialDescription" name="description" class="form-control form-control-lg Input_control" rows="3"
                                dir="auto" placeholder="{{ __('messages.description') }}"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="button" class="btn orange_btn api-action-btn" id="createMaterialBtn">
                        {{ __('messages.upload') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">{{ __('messages.details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img id="detailImage" src="" class="img-fluid rounded" alt="Material">
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">{{ __('messages.name') }}</label>
                                <p id="detailName" class="fw-semibold mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">{{ __('messages.type') }}</label>
                                <p id="detailType" class="fw-semibold mb-0"></p>
                            </div>
                            <div class="mb-3" id="detailQuantitySection">
                                <label class="text-muted small">{{ __('messages.quantity') }}</label>
                                <p id="detailQuantity" class="fw-semibold mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">{{ __('messages.description') }}</label>
                                <p id="detailDescription" class="mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">{{ __('messages.posted_by') }}</label>
                                <p id="detailPostedBy" class="fw-semibold mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">{{ __('messages.date') }}</label>
                                <p id="detailDate" class="mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">{{ __('messages.status') }}</label>
                                <p id="detailStatus" class="mb-0"></p>
                            </div>
                        </div>
                    </div>

                    <div id="rejectionNoteSection" style="display: none;" class="mt-4">
                        <label class="form-label">{{ __('messages.rejection_note') }}</label>
                        <textarea class="form-control" id="rejectionNote" rows="3"
                            placeholder="{{ __('messages.enter_rejection_reason') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0" id="detailsFooter">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    @can('raw_materials', 'reject')
                        <button type="button" id="rejectBtn" class="btn btn-danger" onclick="handleReject()">
                            <i class="fas fa-times {{ margin_end(1) }}"></i>{{ __('messages.reject') }}
                        </button>
                    @endcan
                    @can('raw_materials', 'approve')
                        <button type="button" id="acceptBtn" class="btn btn-success" onclick="handleAccept()">
                            <i class="fas fa-check {{ margin_end(1) }}"></i>{{ __('messages.accept') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <script>
        window.projectId = {{ $project->id ?? 1 }};
        window.userId = {{ auth()->id() ?? 1 }};
        let currentMaterialId = null;
        let rawMaterials = [];

        function toggleQuantityField() {
            const typeSelect = document.getElementById('materialType');
            const quantityContainer = document.getElementById('quantityContainer');
            const quantityInput = document.getElementById('materialQuantity');

            if (typeSelect.value === 'material') {
                quantityContainer.style.display = 'block';
                quantityInput.required = true;
            } else {
                quantityContainer.style.display = 'none';
                quantityInput.required = false;
                quantityInput.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadRawMaterials();
        });

        async function loadRawMaterials() {
            try {
                const response = await api.listRawMaterials({
                    project_id: window.projectId
                });
                if (response.code === 200) {
                    rawMaterials = response.data;
                    renderMaterials(rawMaterials);
                } else {
                    toastr.error(response.message || '{{ __('messages.failed_to_load_materials') }}');
                }
            } catch (error) {
                console.error('Error loading materials:', error);
                toastr.error('{{ __('messages.error_loading_materials') }}');
            }
        }

        function renderMaterials(materials) {
            const container = document.getElementById('rawMaterialsContainer');
            if (!materials || materials.length === 0) {
                container.innerHTML =
                    '<div class="col-12 text-center py-5"><p class="text-muted">{{ __('messages.no_materials_found') }}</p></div>';
                return;
            }

            container.innerHTML = materials.map(material => {
                const statusClass = material.status === 'approved' ? 'status-approved' : material.status ===
                    'rejected' ? 'status-rejected' : '';
                const statusIcon = material.status === 'approved' ? 'fa-check' : material.status === 'rejected' ?
                    'fa-times' : 'fa-clock';
                const statusText = material.status.charAt(0).toUpperCase() + material.status.slice(1);
                const imageUrl = material.image_url || 'https://placehold.co/600x400?text=No+Image';
                const date = new Date(material.created_at).toLocaleDateString();

                return `
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="raw-material-card" onclick="openDetailsModal(${material.id})">
                            <div class="rm-image-container">
                                <img src="${imageUrl}" alt="${material.name}">
                            </div>
                            <div class="rm-content">
                                <div class="rm-header">
                                    <span class="rm-date">{{ __('messages.date') }}: ${date}</span>
                                    <span class="rm-status ${statusClass}"><i class="fas ${statusIcon} me-1"></i>${statusText}</span>
                                </div>
                                <div class="rm-footer">
                                    <span class="rm-author">{{ __('messages.posted_by') }}: ${material.posted_by?.name || 'N/A'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function openDetailsModal(id) {
            currentMaterialId = id;
            try {
                const response = await api.getRawMaterialDetails({
                    raw_material_id: id
                });
                if (response.code === 200) {
                    const material = response.data;
                    const imageUrl = material.image_url || 'https://placehold.co/600x400?text=No+Image';
                    const date = new Date(material.created_at).toLocaleDateString();
                    const typeLabel = material.type === 'drawing' ? '{{ __('messages.drawing') }}' :
                        '{{ __('messages.material') }}';

                    document.getElementById('detailImage').src = imageUrl;
                    document.getElementById('detailName').textContent = material.name;
                    document.getElementById('detailType').textContent = typeLabel;

                    const quantitySection = document.getElementById('detailQuantitySection');
                    if (material.type === 'material') {
                        quantitySection.style.display = 'block';
                        document.getElementById('detailQuantity').textContent = material.quantity + ' Units';
                    } else {
                        quantitySection.style.display = 'none';
                    }

                    document.getElementById('detailDescription').textContent = material.description || 'N/A';
                    document.getElementById('detailPostedBy').textContent = material.posted_by?.name || 'N/A';
                    document.getElementById('detailDate').textContent = date;
                    document.getElementById('detailStatus').textContent = material.status.charAt(0).toUpperCase() +
                        material.status.slice(1);

                    const rejectBtn = document.getElementById('rejectBtn');
                    const acceptBtn = document.getElementById('acceptBtn');

                    if (material.status !== 'pending') {
                        if (rejectBtn) rejectBtn.style.display = 'none';
                        if (acceptBtn) acceptBtn.style.display = 'none';
                    } else {
                        if (rejectBtn) rejectBtn.style.display = 'inline-block';
                        if (acceptBtn) acceptBtn.style.display = 'inline-block';
                    }

                    document.getElementById('rejectionNoteSection').style.display = 'none';
                    document.getElementById('rejectionNote').value = '';

                    var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                    myModal.show();
                } else {
                    toastr.error(response.message || '{{ __('messages.failed_to_load_material_details') }}');
                }
            } catch (error) {
                console.error('Error loading material details:', error);
                toastr.error('{{ __('messages.error_loading_material_details') }}');
            }
        }

        async function handleReject() {
            const noteSection = document.getElementById('rejectionNoteSection');
            if (noteSection.style.display === 'none') {
                noteSection.style.display = 'block';
            } else {
                const note = document.getElementById('rejectionNote').value.trim();
                if (!note) {
                    toastr.warning('{{ __('messages.please_enter_rejection_reason') }}');
                    return;
                }

                try {
                    const response = await api.rejectRawMaterial({
                        raw_material_id: currentMaterialId,
                        user_id: window.userId,
                        rejection_note: note
                    });

                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('detailsModal')).hide();
                        loadRawMaterials();
                        toastr.success(response.message || '{{ __('messages.material_rejected_successfully') }}');
                    } else {
                        toastr.error(response.message || '{{ __('messages.error_rejecting_material') }}');
                    }
                } catch (error) {
                    console.error('Error rejecting material:', error);
                    toastr.error('{{ __('messages.error_rejecting_material') }}');
                }
            }
        }

        async function handleAccept() {
            try {
                const response = await api.approveRawMaterial({
                    raw_material_id: currentMaterialId,
                    user_id: window.userId
                });

                if (response.code === 200) {
                    bootstrap.Modal.getInstance(document.getElementById('detailsModal')).hide();
                    loadRawMaterials();
                    toastr.success(response.message || '{{ __('messages.material_approved_successfully') }}');
                } else {
                    toastr.error(response.message || '{{ __('messages.error_approving_material') }}');
                }
            } catch (error) {
                console.error('Error approving material:', error);
                toastr.error('{{ __('messages.error_approving_material') }}');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const createBtn = document.getElementById('createMaterialBtn');
            if (createBtn) {
                createBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const form = document.getElementById('addMaterialForm');
                    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                        'is-invalid'));
                    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                    const nameInput = document.getElementById('materialName');
                    const typeSelect = document.getElementById('materialType');
                    const qtyInput = document.getElementById('materialQuantity');
                    let isValid = true;

                    if (!nameInput.value.trim()) {
                        nameInput.classList.add('is-invalid');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = '{{ __('messages.material_name_required') }}';
                        nameInput.parentNode.appendChild(errorDiv);
                        toastr.error('{{ __('messages.material_name_required') }}');
                        isValid = false;
                    }

                    if (!typeSelect.value) {
                        typeSelect.classList.add('is-invalid');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = '{{ __('messages.select_type') }}';
                        typeSelect.parentNode.appendChild(errorDiv);
                        if (isValid) toastr.error('{{ __('messages.select_type') }}');
                        isValid = false;
                    }

                    if (typeSelect.value === 'material' && (!qtyInput.value || qtyInput.value <= 0)) {
                        qtyInput.classList.add('is-invalid');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = '{{ __('messages.quantity_required') }}';
                        qtyInput.parentNode.appendChild(errorDiv);
                        if (isValid) toastr.error('{{ __('messages.quantity_required') }}');
                        isValid = false;
                    }

                    if (!isValid) return;

                    nameInput.classList.add('is-valid');
                    typeSelect.classList.add('is-valid');
                    if (typeSelect.value === 'material') qtyInput.classList.add('is-valid');

                    const btn = e.currentTarget;
                    const origText = btn.innerHTML;
                    btn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('messages.uploading') }}';
                    btn.disabled = true;

                    try {
                        const formData = new FormData();
                        formData.append('user_id', window.userId);
                        formData.append('project_id', window.projectId);
                        formData.append('name', nameInput.value);
                        formData.append('type', typeSelect.value);

                        if (typeSelect.value === 'material') {
                            formData.append('quantity', qtyInput.value);
                        }

                        const desc = document.getElementById('materialDescription').value;
                        if (desc) formData.append('description', desc);

                        const fileInput = document.getElementById('materialFile');
                        if (fileInput.files[0]) formData.append('image', fileInput.files[0]);

                        const response = await api.createRawMaterial(formData);

                        if (response.code === 200) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'addMaterialModal'));
                            if (modal) modal.hide();

                            toastr.success(response.message ||
                                '{{ __('messages.material_created_successfully') }}');
                            form.reset();
                            form.querySelectorAll('.is-valid').forEach(el => el.classList.remove(
                                'is-valid'));
                            document.getElementById('fileNameDisplay').textContent =
                                '{{ __('messages.drop_files_here') }}';
                            toggleQuantityField();

                            if (typeof loadRawMaterials === 'function') loadRawMaterials();
                        } else {
                            toastr.error(response.message ||
                                '{{ __('messages.failed_to_create_material') }}');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error('{{ __('messages.error_creating_material') }}');
                    } finally {
                        btn.innerHTML = origText;
                        btn.disabled = false;
                    }
                });
            }

            const fileInput = document.getElementById('materialFile');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const display = document.getElementById('fileNameDisplay');
                    if (e.target.files[0] && display) {
                        display.textContent = e.target.files[0].name;
                    }
                });
            }
        });
    </script>
@endsection

<script>
    // Initialize custom type dropdown after page load
    document.addEventListener('DOMContentLoaded', function() {
        const typeFilterBtn = document.getElementById('typeFilterBtn');
        const typeFilterOptions = document.getElementById('typeFilterOptions');
        const materialTypeSelect = document.getElementById('materialType');

        if (typeFilterBtn && typeFilterOptions) {
            typeFilterBtn.addEventListener('click', function() {
                typeFilterOptions.style.display = typeFilterOptions.style.display === 'block' ? 'none' : 'block';
            });

            const options = typeFilterOptions.querySelectorAll('.custom-filter-option');
            options.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    const text = this.textContent;

                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    typeFilterBtn.textContent = text;
                    if (materialTypeSelect) {
                        materialTypeSelect.value = value;
                        toggleQuantityField();
                    }

                    typeFilterOptions.style.display = 'none';
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('#typeFilterWrapper')) {
                    typeFilterOptions.style.display = 'none';
                }
            });
        }
    });
</script>

<style>
    #typeFilterWrapper {
        width: 100%;
    }

    #typeFilterBtn {
        width: 100%;
        text-align: left;
    }

    [dir="rtl"] #typeFilterBtn {
        text-align: right;
    }
</style>
