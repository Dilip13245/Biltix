<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="user-id" content="{{ auth()->id() ?? 1 }}">
    <title>Phase Tasks</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <div class="content_wraper F_poppins">
        <header class="project-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-12 d-flex align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-outline-primary" onclick="history.back()">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h4 class="mb-0">{{ __('messages.tasks') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </header>
<div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
  <div>
    <h2>{{ __('messages.tasks') }}</h2>
    <p>{{ __('messages.manage_track_tasks') }}</p>
  </div>
  <div class="d-flex align-items-center gap-2 gap-md-3 flex-wrap">
    <form class="serchBar position-relative serchBar2">
      <input class="form-control" type="search" placeholder="{{ __('messages.search_task_name') }}" aria-label="Search" dir="auto" style="padding-right: 45px;">
      <span class="search_icon" style="right: 15px; pointer-events: none;"><img src="{{ asset('website/images/icons/search.svg') }}" alt="search"></span>
    </form>
    <select class="form-select w-auto" id="statusFilter">
      <option value="">{{ __('messages.all_status') }}</option>
      <option value="pending">{{ __('messages.pending') }}</option>
      <option value="in_progress">{{ __('messages.in_progress') }}</option>
      <option value="completed">{{ __('messages.completed') }}</option>
    </select>
    @can('tasks', 'create')
        <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#addTaskModal" onclick="if(!this.disabled){this.disabled=true;setTimeout(()=>{this.disabled=false;},3000);}">
            <i class="fas fa-plus"></i>
            {{ __('messages.add_new_task') }}
        </button>
    @endcan
  </div>
</div>
<section class="px-md-4">
  <div class="container-fluid">
    <div class="row gy-4 card_wraPper" id="tasksContainer">
      <!-- Loading state -->
      <div class="col-12 text-center" id="loadingState">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">{{ __('messages.loading') }}</span>
        </div>
        <p class="mt-2 text-muted">{{ __('messages.loading') }}</p>
      </div>
      
      <!-- No tasks state -->
      <div class="col-12 text-center d-none" id="noTasksState">
        <div class="py-5">
          <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">{{ __('messages.no_tasks_found') }}</h5>
          <p class="text-muted">{{ __('messages.create_first_task') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>
@include('website.modals.add-task-modal')
@include('website.modals.drawing-modal')
@include('website.modals.task-details-modal')

<script>
// Global variables
let currentTasks = [];
let currentProjectId = null;
let currentPhaseId = null;
let currentUserId = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Test toastr
    if (typeof toastr !== 'undefined') {
        console.log('Toastr loaded successfully');
    } else {
        console.error('Toastr not loaded');
    }
    
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const pathParts = window.location.pathname.split('/');
    currentProjectId = pathParts[3]; // /website/project/{id}/phase-tasks
    currentPhaseId = urlParams.get('phase_id');
    
    // Try multiple sources for user ID
    currentUserId = localStorage.getItem('user_id') || 
                   sessionStorage.getItem('user_id') || 
                   document.querySelector('meta[name="user-id"]')?.content || 
                   '1'; // Default fallback
    
    console.log('Current User ID:', currentUserId);
    console.log('Current Project ID:', currentProjectId);
    console.log('Current Phase ID:', currentPhaseId);
    
    // Load tasks and users
    loadTasks();
    loadUsers();
    
    // Setup event listeners
    setupEventListeners();
});

function setupEventListeners() {
    // Search functionality
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterTasks();
        });
    }
    
    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterTasks();
        });
    }
    
    // Add task form
    const addTaskForm = document.getElementById('addTaskForm');
    if (addTaskForm) {
        addTaskForm.addEventListener('submit', handleTaskSubmit);
    }
    
    // Hide phase dropdown in modal since this is a phase page
    const addTaskModal = document.getElementById('addTaskModal');
    if (addTaskModal) {
        addTaskModal.addEventListener('show.bs.modal', function() {
            const phaseContainer = document.getElementById('phaseSelectContainer');
            const phaseSelect = document.getElementById('phaseSelect');
            if (phaseContainer) {
                phaseContainer.style.display = 'none';
            }
            if (phaseSelect) {
                phaseSelect.removeAttribute('required');
            }
        });
    }
}

async function loadUsers() {
    try {
        const response = await api.getAllUsers();
        
        if (response.code === 200) {
            const users = response.data || [];
            const assignedToSelect = document.getElementById('assignedTo');
            
            // Clear existing options except the first one
            assignedToSelect.innerHTML = '<option value="">{{ __("messages.select_user") }}</option>';
            
            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = user.name;
                assignedToSelect.appendChild(option);
            });
            
            // Initialize searchable dropdown after loading options
            setTimeout(() => {
                if (typeof SearchableDropdown !== 'undefined') {
                    if (!assignedToSelect.searchableDropdown) {
                        assignedToSelect.searchableDropdown = new SearchableDropdown(assignedToSelect);
                    } else {
                        assignedToSelect.searchableDropdown.updateOptions();
                    }
                }
            }, 100);
        }
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

async function loadTasks() {
    try {
        showLoading();
        
        const requestData = {
            user_id: currentUserId,
            project_id: currentProjectId,
            phase_id: currentPhaseId || '1'
        };
        
        const response = await api.getTasks(requestData);
        
        if (response.code === 200) {
            currentTasks = response.data.data || [];
            renderTasks(currentTasks);
        } else {
            console.error('Failed to load tasks:', response.message);
            showNoTasks();
        }
    } catch (error) {
        console.error('Error loading tasks:', error);
        showNoTasks();
    }
}

function renderTasks(tasks) {
    const container = document.getElementById('tasksContainer');
    const loadingState = document.getElementById('loadingState');
    const noTasksState = document.getElementById('noTasksState');
    
    if (!container) {
        console.error('Tasks container not found');
        return;
    }
    
    // Hide loading state
    if (loadingState) loadingState.classList.add('d-none');
    
    if (!tasks || tasks.length === 0) {
        // Clear all task cards but keep loading and no-tasks elements
        const taskCards = container.querySelectorAll('.col-md-6:not(#loadingState):not(#noTasksState), .col-lg-4');
        taskCards.forEach(card => card.remove());
        
        // Show no tasks state
        if (noTasksState) noTasksState.classList.remove('d-none');
        return;
    }
    
    // Hide no tasks state
    if (noTasksState) noTasksState.classList.add('d-none');
    
    const tasksHtml = tasks.map((task, index) => {
        const statusBadge = getStatusBadge(task.status);
        const delay = (index * 0.4).toFixed(1);
        
        return `
            <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="${delay}s">
                <div class="card h-100 B_shadow">
                    <div class="card-body p-md-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-semibold black_color mb-0 text-wrap" style="word-wrap: break-word;">${task.title}</h5>
                            ${statusBadge}
                        </div>
                        <p class="mb-2 text-muted fw-medium d-flex align-items-center gap-1">
                            <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_861_2334)">
                                    <g clip-path="url(#clip1_861_2334)">
                                        <path d="M3.5 0.75C3.98398 0.75 4.375 1.14102 4.375 1.625V2.5H7.875V1.625C7.875 1.14102 8.26602 0.75 8.75 0.75C9.23398 0.75 9.625 1.14102 9.625 1.625V2.5H10.9375C11.6621 2.5 12.25 3.08789 12.25 3.8125V5.125H0V3.8125C0 3.08789 0.587891 2.5 1.3125 2.5H2.625V1.625C2.625 1.14102 3.01602 0.75 3.5 0.75ZM0 6H12.25V13.4375C12.25 14.1621 11.6621 14.75 10.9375 14.75H1.3125C0.587891 14.75 0 14.1621 0 13.4375V6ZM1.75 8.1875V9.0625C1.75 9.30313 1.94687 9.5 2.1875 9.5H3.0625C3.30312 9.5 3.5 9.30313 3.5 9.0625V8.1875C3.5 7.94688 3.30312 7.75 3.0625 7.75H2.1875C1.94687 7.75 1.75 7.94688 1.75 8.1875ZM5.25 8.1875V9.0625C5.25 9.30313 5.44688 9.5 5.6875 9.5H6.5625C6.80312 9.5 7 9.30313 7 9.0625V8.1875C7 7.94688 6.80312 7.75 6.5625 7.75H5.6875C5.44688 7.75 5.25 7.94688 5.25 8.1875ZM9.1875 7.75C8.94687 7.75 8.75 7.94688 8.75 8.1875V9.0625C8.75 9.30313 8.94687 9.5 9.1875 9.5H10.0625C10.3031 9.5 10.5 9.30313 10.5 9.0625V8.1875C10.5 7.94688 10.3031 7.75 10.0625 7.75H9.1875ZM1.75 11.6875V12.5625C1.75 12.8031 1.94687 13 2.1875 13H3.0625C3.30312 13 3.5 12.8031 3.5 12.5625V11.6875C3.5 11.4469 3.30312 11.25 3.0625 11.25H2.1875C1.94687 11.25 1.75 11.4469 1.75 11.6875ZM5.6875 11.25C5.44688 11.25 5.25 11.4469 5.25 11.6875V12.5625C5.25 12.8031 5.44688 13 5.6875 13H6.5625C6.80312 13 7 12.8031 7 12.5625V11.6875C7 11.4469 6.80312 11.25 6.5625 11.25H5.6875ZM8.75 11.6875V12.5625C8.75 12.8031 8.94687 13 9.1875 13H10.0625C10.3031 13 10.5 12.5631 10.5 12.5625V11.6875C10.5 11.4469 10.3031 11.25 10.0625 11.25H9.1875C8.94687 11.25 8.75 11.4469 8.75 11.6875Z" fill="#6B7280" />
                                    </g>
                                </g>
                                <defs>
                                    <clipPath id="clip0_861_2334">
                                        <rect width="12.25" height="14" fill="white" transform="translate(0 0.75)" />
                                    </clipPath>
                                    <clipPath id="clip1_861_2334">
                                        <path d="M0 0.75H12.25V14.75H0V0.75Z" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            {{ __('messages.due') }}: ${formatDate(task.due_date)}
                        </p>
                        ${task.assigned_user_name ? `<p class="mb-3 text-muted"><strong>{{ __('messages.assigned_to') }}:</strong> ${task.assigned_user_name}</p>` : '<div class="mb-3"></div>'}
                        <button class="btn btn-primary w-100" onclick="openTaskDetails(${task.id})">
                            <i class="fas fa-eye me-2"></i>{{ __('messages.view_details') }}
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    // Clear existing task cards and add new ones
    const taskCards = container.querySelectorAll('.col-md-6:not(#loadingState):not(#noTasksState), .col-lg-4');
    taskCards.forEach(card => card.remove());
    
    // Add new tasks before loading/no-tasks states
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = tasksHtml;
    
    while (tempDiv.firstChild) {
        container.insertBefore(tempDiv.firstChild, loadingState || noTasksState || null);
    }
}

function getStatusBadge(status) {
    const statusMap = {
        'pending': 'badge2',
        'in_progress': 'badge4', 
        'completed': 'badge1'
    };
    
    const badgeClass = statusMap[status] || 'badge2';
    const statusText = status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');
    
    return `<span class="badge ${badgeClass} fw-normal" style="font-size: 0.9em;">${statusText}</span>`;
}

function formatDate(dateString) {
    if (!dateString) return '{{ __('messages.no_date') }}';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

function filterTasks() {
    const searchInput = document.querySelector('input[type="search"]');
    const statusFilterElement = document.getElementById('statusFilter');
    
    if (!searchInput || !statusFilterElement) {
        console.error('Filter elements not found');
        return;
    }
    
    const searchTerm = searchInput.value.toLowerCase();
    const statusFilter = statusFilterElement.value;
    
    let filteredTasks = [...currentTasks]; // Create a copy
    
    console.log('Filtering tasks:', {
        totalTasks: currentTasks.length,
        searchTerm,
        statusFilter
    });
    
    // Apply search filter
    if (searchTerm) {
        filteredTasks = filteredTasks.filter(task => 
            task.title.toLowerCase().includes(searchTerm) ||
            (task.description && task.description.toLowerCase().includes(searchTerm)) ||
            (task.assigned_user_name && task.assigned_user_name.toLowerCase().includes(searchTerm))
        );
    }
    
    // Apply status filter
    if (statusFilter) {
        filteredTasks = filteredTasks.filter(task => {
            console.log('Task status:', task.status, 'Filter:', statusFilter);
            return task.status === statusFilter;
        });
    }
    
    console.log('Filtered tasks:', filteredTasks.length);
    renderTasks(filteredTasks);
}

function showLoading() {
    const loadingState = document.getElementById('loadingState');
    const noTasksState = document.getElementById('noTasksState');
    if (loadingState) loadingState.classList.remove('d-none');
    if (noTasksState) noTasksState.classList.add('d-none');
}

function hideLoading() {
    const loadingState = document.getElementById('loadingState');
    if (loadingState) {
        loadingState.classList.add('d-none');
    }
}

function showNoTasks() {
    const loadingState = document.getElementById('loadingState');
    const noTasksState = document.getElementById('noTasksState');
    if (loadingState) loadingState.classList.add('d-none');
    if (noTasksState) noTasksState.classList.remove('d-none');
}

async function openTaskDetails(taskId) {
    try {
        const response = await api.getTaskDetails({
            task_id: taskId,
            user_id: currentUserId
        });
        
        if (response.code === 200) {
            const task = response.data;
            populateTaskDetailsModal(task);
            
            const modal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
            modal.show();
        } else {
            toastr.error('{{ __('messages.failed_to_load_task_details') }}');
        }
    } catch (error) {
        console.error('Error loading task details:', error);
        toastr.error('{{ __('messages.error_loading_task_details') }}');
    }
}

function populateTaskDetailsModal(task) {
    document.getElementById('taskDetailTitle').textContent = task.title;
    document.getElementById('taskDetailDescription').textContent = task.description || 'No description provided';
    document.getElementById('taskDetailDueDate').textContent = formatDate(task.due_date);
    document.getElementById('taskDetailStartDate').textContent = formatDate(task.created_at);
    document.getElementById('taskDetailAssignedTo').textContent = task.assigned_user_name || 'Unassigned';
    document.getElementById('taskDetailNumber').textContent = task.task_number || '-';
    document.getElementById('taskDetailStatus').textContent = task.status.charAt(0).toUpperCase() + task.status.slice(1).replace('_', ' ');
    
    // Load images
    loadTaskImages(task.images || []);
    
    // Show/hide add images button based on task status
    const addImagesBtn = document.getElementById('addImagesBtn');
    const resolveBtn = document.getElementById('resolveBtn');
    
    if (task.status === 'completed') {
        addImagesBtn.style.display = 'none';
        resolveBtn.style.display = 'none';
    } else {
        addImagesBtn.style.display = 'block';
        resolveBtn.style.display = 'block';
    }
    
    // Store current task for actions
    window.currentTaskDetails = task;
}

function loadTaskImages(images) {
    const imagesContainer = document.getElementById('taskImagesContainer');
    
    if (!imagesContainer) {
        console.error('Images container not found!');
        return;
    }
    
    if (!images || images.length === 0) {
        imagesContainer.innerHTML = '<div class="col-12"><p class="text-muted">{{ __('messages.no_images_uploaded') }}</p></div>';
        return;
    }
    
    const imagesHtml = images.map((image, index) => {
        const imageUrl = image.image_url || image;
        
        return `
            <div class="col-4 col-sm-3 mb-2">
                <img src="${imageUrl}" alt="Task Image" class="img-fluid rounded" style="height: 80px; width: 100%; object-fit: cover; cursor: pointer; border: 2px solid #e9ecef;" onclick="window.open('${imageUrl}', '_blank')" onerror="console.error('Failed to load image:', this.src)">
            </div>
        `;
    }).join('');
    
    imagesContainer.innerHTML = imagesHtml;
    console.log('Images loaded into container:', imagesContainer);
}

// Simple button protection
function protectTaskButton(btn) {
  if (btn.disabled) return;
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
  setTimeout(function() {
    btn.disabled = false;
    btn.innerHTML = '{{ __("messages.next") }} <i class="fas fa-arrow-right ms-2"></i>';
  }, 5000);
}

async function handleTaskSubmit(e) {
    e.preventDefault();
    
    // Protect button
    const submitBtn = document.querySelector('#addTaskModal button[type="submit"]');
    if (submitBtn) protectTaskButton(submitBtn);
    
    const fileInput = document.getElementById('taskImages');
    
    if (fileInput.files && fileInput.files.length > 0) {
        // Store files and open drawing modal
        window.selectedTaskFiles = fileInput.files;
        
        openDrawingModal({
            title: 'Task Image Markup',
            saveButtonText: 'Save Task',
            mode: 'image',
            onSave: function(imageData) {
                saveTaskWithMarkup(imageData);
            }
        });
        
        // Load images after modal is shown
        document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
            if (window.selectedTaskFiles.length === 1) {
                loadImageToCanvas(window.selectedTaskFiles[0]);
            } else {
                loadMultipleFiles(window.selectedTaskFiles);
            }
        }, { once: true });
    } else {
        // No images, direct API call
        saveTaskWithoutMarkup();
    }
}

async function saveTaskWithMarkup(imageData) {
    try {
        const formData = new FormData();
        
        formData.append('user_id', currentUserId);
        formData.append('project_id', currentProjectId);
        formData.append('phase_id', currentPhaseId || '1');
        formData.append('title', document.getElementById('taskName').value);
        formData.append('description', document.getElementById('taskDescription').value);
        formData.append('due_date', document.getElementById('dueDate').value);
        
        const assignedTo = document.getElementById('assignedTo').value;
        if (assignedTo) {
            formData.append('assigned_to', assignedTo);
        }
        
        // Convert markup to blob and append
        if (Array.isArray(imageData)) {
            imageData.forEach((data, index) => {
                if (typeof data === 'string') {
                    const blob = dataURLtoBlob(data);
                    formData.append('images[]', blob, `markup_${index}.png`);
                } else if (data instanceof File) {
                    formData.append('images[]', data, data.name);
                }
            });
        } else {
            const blob = dataURLtoBlob(imageData);
            formData.append('images[]', blob, 'markup.png');
        }

        const response = await api.createTask(formData);

        if (response.code === 200) {
            // Close modals
            const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
            if (drawingModal) drawingModal.hide();
            
            const addTaskModal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
            if (addTaskModal) addTaskModal.hide();
            
            toastr.success('{{ __('messages.task_created_successfully') }}');
            document.getElementById('addTaskForm').reset();
            loadTasks();
        } else {
            resetFormSubmission('addTaskForm');
            toastr.error('{{ __('messages.failed_to_create_task') }}: ' + response.message);
        }
    } catch (error) {
        console.error('Error creating task:', error);
        resetFormSubmission('addTaskForm');
        toastr.error('{{ __('messages.failed_to_create_task') }}');
    }
}

async function saveTaskWithoutMarkup() {
    try {
        const formData = new FormData();
        
        formData.append('user_id', currentUserId);
        formData.append('project_id', currentProjectId);
        formData.append('phase_id', currentPhaseId || '1');
        formData.append('title', document.getElementById('taskName').value);
        formData.append('description', document.getElementById('taskDescription').value);
        formData.append('due_date', document.getElementById('dueDate').value);
        
        const assignedTo = document.getElementById('assignedTo').value;
        if (assignedTo) {
            formData.append('assigned_to', assignedTo);
        }

        const response = await api.createTask(formData);

        if (response.code === 200) {
            const addTaskModal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
            if (addTaskModal) addTaskModal.hide();
            
            toastr.success('{{ __('messages.task_created_successfully') }}');
            document.getElementById('addTaskForm').reset();
            loadTasks();
        } else {
            resetFormSubmission('addTaskForm');
            toastr.error('{{ __('messages.failed_to_create_task') }}: ' + response.message);
        }
    } catch (error) {
        console.error('Error creating task:', error);
        resetFormSubmission('addTaskForm');
        toastr.error('{{ __('messages.failed_to_create_task') }}');
    }
}

function dataURLtoBlob(dataURL) {
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

// Task details modal functions
function editTask() {
    // Close details modal and open edit modal
    const detailsModal = bootstrap.Modal.getInstance(document.getElementById('taskDetailsModal'));
    if (detailsModal) detailsModal.hide();
    
    // Populate edit form with current task data
    if (window.currentTaskDetails) {
        document.getElementById('taskName').value = window.currentTaskDetails.title;
        document.getElementById('taskDescription').value = window.currentTaskDetails.description || '';
        document.getElementById('dueDate').value = window.currentTaskDetails.due_date;
        document.getElementById('assignedTo').value = window.currentTaskDetails.assigned_to || '';
    }
    
    const addModal = new bootstrap.Modal(document.getElementById('addTaskModal'));
    addModal.show();
}

async function markAsResolved() {
    if (!window.currentTaskDetails) return;
    
    try {
        const formData = new FormData();
        formData.append('task_id', window.currentTaskDetails.id);
        formData.append('user_id', currentUserId);
        
        const response = await api.updateTask(formData);
        
        if (response.code === 200) {
            // Update UI
            document.getElementById('taskDetailStatus').textContent = 'Completed';
            document.getElementById('taskDetailStatus').className = 'badge badge1';
            
            // Hide add images button and resolve button
            document.getElementById('addImagesBtn').style.display = 'none';
            document.getElementById('resolveBtn').style.display = 'none';
            
            // Reload tasks
            loadTasks();
            
            toastr.success('{{ __('messages.task_completed_successfully') }}');
        } else {
            toastr.error('{{ __('messages.failed_to_complete_task') }}');
        }
    } catch (error) {
        console.error('Error completing task:', error);
        toastr.error('{{ __('messages.error_completing_task') }}');
    }
}

function addComment() {
    document.getElementById('newComment').focus();
}

function submitComment() {
    const comment = document.getElementById('newComment').value;
    if (comment.trim()) {
        // Add comment to the list (this would normally be an AJAX call)
        const commentsDiv = document.getElementById('taskComments');
        const newCommentHTML = `
            <div class="d-flex mb-3">
                <img src="{{ asset('website/images/icons/avtar.svg') }}" class="rounded-circle me-3" width="40" height="40">
                <div class="flex-grow-1">
                    <div class="bg-light p-3 rounded">
                        <strong>You</strong>
                        <small class="text-muted">Just now</small>
                        <p class="mb-0 mt-1">${comment}</p>
                    </div>
                </div>
            </div>
        `;
        commentsDiv.insertAdjacentHTML('beforeend', newCommentHTML);
        document.getElementById('newComment').value = '';
    }
}

function openTaskDrawing() {
    if (!window.currentTaskDetails) return;
    
    openDrawingModal({
        title: 'Add Task Drawing',
        saveButtonText: 'Save Drawing',
        mode: 'blank',
        onSave: function(imageData) {
            uploadTaskDrawing(imageData);
        }
    });
}

async function uploadTaskDrawing(imageData) {
    if (!window.currentTaskDetails || !imageData || imageData.length === 0) return;
    
    try {
        const formData = new FormData();
        formData.append('task_id', window.currentTaskDetails.id);
        formData.append('user_id', currentUserId);
        
        imageData.forEach((dataUrl, index) => {
            const blob = dataURLtoBlob(dataUrl);
            formData.append('images[]', blob, `task_drawing_${index}.png`);
        });
        
        const response = await api.updateTask(formData);
        
        if (response.code === 200) {
            // Close drawing modal
            const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
            if (drawingModal) drawingModal.hide();
            
            // Reopen task details modal
            setTimeout(() => {
                openTaskDetails(window.currentTaskDetails.id);
            }, 300);
            
            toastr.success('{{ __('messages.drawing_added_successfully') }}');
        } else {
            toastr.error('{{ __('messages.failed_to_add_drawing') }}');
        }
    } catch (error) {
        console.error('Error adding drawing:', error);
        toastr.error('{{ __('messages.error_adding_drawing') }}');
    }
}

function addNewImages() {
    if (!window.currentTaskDetails) return;
    
    const fileInput = document.getElementById('additionalImages');
    fileInput.click();
    
    fileInput.onchange = function() {
        if (this.files && this.files.length > 0) {
            // Store files for drawing modal
            window.selectedTaskFiles = this.files;
            
            // Close task details modal first
            const taskDetailsModal = bootstrap.Modal.getInstance(document.getElementById('taskDetailsModal'));
            if (taskDetailsModal) taskDetailsModal.hide();
            
            setTimeout(() => {
                openDrawingModal({
                    title: 'Markup Additional Images',
                    saveButtonText: 'Upload Images',
                    mode: 'image',
                    onSave: function(imageData) {
                        submitAdditionalImages(imageData);
                    }
                });
                
                // Load images after modal is shown
                setTimeout(() => {
                    if (window.selectedTaskFiles && window.selectedTaskFiles.length > 0) {
                        if (window.selectedTaskFiles.length === 1) {
                            loadImageToCanvas(window.selectedTaskFiles[0]);
                        } else {
                            loadMultipleFiles(window.selectedTaskFiles);
                        }
                    }
                }, 500);
            }, 300);
        }
    };
}

async function submitAdditionalImages(imageData) {
    if (!window.currentTaskDetails) return;
    
    try {
        const formData = new FormData();
        formData.append('task_id', window.currentTaskDetails.id);
        formData.append('user_id', currentUserId);
        
        if (imageData) {
            if (Array.isArray(imageData)) {
                imageData.forEach((data, index) => {
                    if (typeof data === 'string') {
                        const blob = dataURLtoBlob(data);
                        formData.append('images[]', blob, `additional_image_${index}.png`);
                    } else if (data instanceof File) {
                        formData.append('images[]', data, data.name);
                    }
                });
            } else {
                const blob = dataURLtoBlob(imageData);
                formData.append('images[]', blob, 'additional_image.png');
            }
        }
        
        const response = await api.updateTask(formData);
        
        if (response.code === 200) {
            // Close drawing modal
            const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
            if (drawingModal) drawingModal.hide();
            
            // Clear file input
            document.getElementById('additionalImages').value = '';
            
            // Reopen task details modal
            setTimeout(() => {
                openTaskDetails(window.currentTaskDetails.id);
            }, 300);
            
            toastr.success('{{ __('messages.images_uploaded_successfully') }}');
        } else {
            toastr.error('{{ __('messages.failed_to_upload_images') }}');
        }
    } catch (error) {
        console.error('Error uploading images:', error);
        toastr.error('{{ __('messages.error_uploading_images') }}');
    }
}
</script>
    </div>
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/toastr-config.js') }}"></script>
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/universal-auth.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>
    <script src="{{ asset('website/js/searchable-dropdown.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
</body>
</html>