<!-- Create Meeting Modal -->
<div class="modal fade" id="createMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <style>
                    #createMeetingModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #createMeetingModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-calendar-plus me-2 text-warning"></i>
                            {{ __('messages.create_meeting') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-calendar-plus me-2 text-warning"></i>
                        {{ __('messages.create_meeting') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>

            <div class="modal-body pt-4">
                <style>
                    /* Create Meeting Modal Styling */
                    #createMeetingModal .form-label {
                        font-weight: 500;
                        color: #374151;
                        font-size: 0.95rem;
                        margin-bottom: 0.5rem;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    }

                    #createMeetingModal .form-control,
                    #createMeetingModal .form-select {
                        border: 1px solid #E5E7EB;
                        border-radius: 0.5rem;
                        padding: 0.75rem 1rem;
                        font-size: 0.95rem;
                        background-color: #F9FAFB;
                        transition: all 0.2s;
                    }

                    #createMeetingModal .form-control:focus,
                    #createMeetingModal .form-select:focus {
                        border-color: #FF9500;
                        box-shadow: 0 0 0 0.2rem rgba(255, 149, 0, 0.15);
                        background-color: white;
                    }

                    #createMeetingModal .time-inputs {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 1rem;
                    }

                    #createMeetingModal .location-type-toggle {
                        display: flex;
                        gap: 0.75rem;
                    }

                    #createMeetingModal .location-type-btn {
                        flex: 1;
                        padding: 0.75rem 1rem;
                        border: 1px solid #E5E7EB;
                        border-radius: 0.5rem;
                        background: white;
                        cursor: pointer;
                        transition: all 0.3s;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.5rem;
                        font-size: 0.95rem;
                        color: #6B7280;
                    }

                    #createMeetingModal .location-type-btn:hover {
                        background: #FFF7ED;
                        border-color: #FFDBA6;
                    }

                    #createMeetingModal .location-type-btn.active {
                        border-color: #FF9500;
                        background: #FFF7ED;
                        color: #C2410C;
                        font-weight: 500;
                    }

                    #createMeetingModal .location-type-btn input[type="radio"] {
                        display: none;
                    }

                    #createMeetingModal .upload-area {
                        border: 2px dashed #D1D5DB;
                        border-radius: 0.75rem;
                        padding: 2rem;
                        text-align: center;
                        background: #F9FAFB;
                        cursor: pointer;
                        transition: all 0.3s;
                    }

                    #createMeetingModal .upload-area:hover {
                        border-color: #FF9500;
                        background: #FFF7ED;
                    }

                    #createMeetingModal .upload-area i {
                        color: #9CA3AF;
                        margin-bottom: 0.75rem;
                        transition: color 0.3s;
                    }

                    #createMeetingModal .upload-area:hover i {
                        color: #FF9500;
                    }

                    /* Team Member Selection Styling */
                    #createMeetingModal .team-selection-wrapper {
                        position: relative;
                        background: #F9FAFB;
                        border: 1px solid #E5E7EB;
                        border-radius: 0.5rem;
                        padding: 0.5rem;
                    }

                    #createMeetingModal .selected-members-container {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 0.5rem;
                        margin-bottom: 0.5rem;
                        min-height: 38px;
                    }

                    #createMeetingModal .member-tag {
                        background: #FFF7ED;
                        color: #C2410C;
                        border: 1px solid #FFDBA6;
                        padding: 0.25rem 0.75rem;
                        border-radius: 1rem;
                        font-size: 0.875rem;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    }

                    #createMeetingModal .member-tag i {
                        cursor: pointer;
                        font-size: 0.8rem;
                        opacity: 0.7;
                    }

                    #createMeetingModal .member-tag i:hover {
                        opacity: 1;
                    }

                    #createMeetingModal .member-search-input {
                        border: none;
                        background: transparent;
                        width: 100%;
                        outline: none;
                        padding: 0.25rem 0.5rem;
                        font-size: 0.95rem;
                    }

                    #createMeetingModal .members-dropdown {
                        position: absolute;
                        top: 100%;
                        left: 0;
                        right: 0;
                        background: white;
                        border: 1px solid #E5E7EB;
                        border-radius: 0.5rem;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        max-height: 250px;
                        overflow-y: auto;
                        z-index: 10;
                        display: none;
                        margin-top: 0.25rem;
                    }

                    #createMeetingModal .members-dropdown.show {
                        display: block;
                    }

                    #createMeetingModal .member-option {
                        padding: 0.75rem 1rem;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        transition: background 0.2s;
                    }

                    #createMeetingModal .member-option:hover {
                        background: #F3F4F6;
                    }

                    #createMeetingModal .member-avatar {
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        background: #E5E7EB;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 0.8rem;
                        font-weight: 600;
                        color: #4B5563;
                    }

                    /* Validation Styling */
                    #createMeetingModal .form-control.is-invalid,
                    #createMeetingModal .form-select.is-invalid {
                        border-color: #dc3545 !important;
                        border-width: 2px !important;
                        background-image: none !important;
                    }

                    #createMeetingModal .form-control.is-invalid:focus,
                    #createMeetingModal .form-select.is-invalid:focus {
                        border-color: #dc3545 !important;
                        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
                    }

                    #createMeetingModal .invalid-feedback {
                        display: block;
                        width: 100%;
                        margin-top: 0.25rem;
                        font-size: 0.875rem;
                        color: #dc3545;
                        color: #4B5563;
                    }

                    /* Responsive */
                    @media (max-width: 768px) {
                        #createMeetingModal .time-inputs {
                            grid-template-columns: 1fr;
                        }
                        
                        #createMeetingModal .location-type-toggle {
                            flex-direction: column;
                        }
                    }

                    /* RTL Support */
                    [dir="rtl"] #createMeetingModal .form-label i {
                        margin-left: 0.5rem;
                        margin-right: 0;
                    }

                    [dir="rtl"] #createMeetingModal .form-control {
                        text-align: right;
                    }

                    [dir="rtl"] #createMeetingModal .form-control::placeholder {
                        text-align: right;
                    }
                </style>

                <form id="createMeetingForm" class="protected-form" novalidate>
                    @csrf
                    
                    <!-- Hidden Phase ID -->
                    <input type="hidden" id="meetingPhaseId">

                    <!-- Meeting Title -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-heading text-muted"></i>
                            {{ __('messages.meeting_title') }}
                        </label>
                        <input type="text" id="meetingTitleInput" class="form-control" 
                               placeholder="{{ __('messages.enter_meeting_title') }}" dir="auto" required>
                    </div>

                    <div class="row">
                        <!-- Date -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="far fa-calendar text-muted"></i>
                                {{ __('messages.date') }}
                            </label>
                            <input type="date" id="meetingDate" class="form-control" required>
                        </div>

                        <!-- Time -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="far fa-clock text-muted"></i>
                                {{ __('messages.time') }}
                            </label>
                            <div class="time-inputs">
                                <input type="time" id="meetingStartTime" class="form-control" placeholder="{{ __('messages.start') }}" required>
                                <input type="time" id="meetingEndTime" class="form-control" placeholder="{{ __('messages.end') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-align-left text-muted"></i>
                            {{ __('messages.description') }}
                        </label>
                        <textarea id="meetingDescription" class="form-control" rows="3" 
                                  placeholder="{{ __('messages.add_description') }}" dir="auto"></textarea>
                    </div>

                    <!-- Team Members (Merged) -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-users text-muted"></i>
                            {{ __('messages.team_members') }}
                        </label>
                        <div class="team-selection-wrapper" id="teamSelectionWrapper">
                            <div class="selected-members-container" id="selectedMembersContainer">
                                <!-- Selected members tags will appear here -->
                            </div>
                            <input type="text" class="member-search-input" id="memberSearchInput" 
                                   placeholder="{{ __('messages.search_team_members') }}..." autocomplete="off">
                            
                            <div class="members-dropdown" id="membersDropdown">
                                <!-- Dynamic member options -->
                            </div>
                        </div>
                    </div>

                    <!-- Meeting Location -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt text-muted"></i>
                            {{ __('messages.location') }}
                        </label>
                        <div class="location-type-toggle mb-3">
                            <label class="location-type-btn active" id="physicalMeetingBtn">
                                <input type="radio" name="locationType" value="physical" checked>
                                <i class="fas fa-building"></i>
                                <span>{{ __('messages.physical_meeting') }}</span>
                            </label>
                            <label class="location-type-btn" id="onlineMeetingBtn">
                                <input type="radio" name="locationType" value="online">
                                <i class="fas fa-video"></i>
                                <span>{{ __('messages.online_meeting') }}</span>
                            </label>
                        </div>
                        <input type="text" id="meetingLocation" class="form-control" 
                               placeholder="{{ __('messages.enter_meeting_address') }}" dir="auto" required>
                    </div>

                    <!-- Upload Documents -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-paperclip text-muted"></i>
                            {{ __('messages.documents') }}
                        </label>
                        <div class="upload-area" onclick="document.getElementById('meetingDocuments').click()">
                            <i class="fas fa-cloud-upload-alt fa-2x"></i>
                            <p class="mb-1 fw-medium text-dark">{{ __('messages.click_to_upload') }}</p>
                            <small class="text-muted">PDF, DOC, XLS (Max 10MB)</small>
                        </div>
                        <input type="file" id="meetingDocuments" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" class="d-none">
                        <div id="uploadedFilesList" class="mt-2"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mt-5">
                        <button type="submit" class="btn orange_btn py-3 fw-bold">
                            {{ __('messages.create_meeting') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Location Type Toggle
    const physicalBtn = document.getElementById('physicalMeetingBtn');
    const onlineBtn = document.getElementById('onlineMeetingBtn');
    const locationInput = document.getElementById('meetingLocation');

    physicalBtn.addEventListener('click', function() {
        physicalBtn.classList.add('active');
        onlineBtn.classList.remove('active');
        locationInput.placeholder = '{{ __('messages.enter_meeting_address') }}';
    });

    onlineBtn.addEventListener('click', function() {
        onlineBtn.classList.add('active');
        physicalBtn.classList.remove('active');
        locationInput.placeholder = '{{ __('messages.enter_meeting_link') }}';
    });

    // File Upload Preview
    const uploadedFiles = [];
    document.getElementById('meetingDocuments').addEventListener('change', function(e) {
        const filesList = document.getElementById('uploadedFilesList');
        filesList.innerHTML = '';
        uploadedFiles.length = 0; // Clear previous
        
        Array.from(e.target.files).forEach(file => {
            uploadedFiles.push(file);
            const fileItem = document.createElement('div');
            fileItem.className = 'd-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded border';
            fileItem.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <i class="far fa-file-alt text-warning"></i>
                    <span class="text-dark small fw-medium">${file.name}</span>
                </div>
                <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
            `;
            filesList.appendChild(fileItem);
        });
    });

    // Team Member Selection Logic
    let teamMembers = [];
    let selectedMembers = [];
    const searchInput = document.getElementById('memberSearchInput');
    const dropdown = document.getElementById('membersDropdown');
    const selectedContainer = document.getElementById('selectedMembersContainer');
    const wrapper = document.getElementById('teamSelectionWrapper');

    // Fetch Team Members
    async function fetchTeamMembers() {
        try {
            const response = await api.listMembers({
                project_id: window.projectId || 1, // Fallback to 1 if not set
                limit: 100 // Get all members
            });
            
            if (response.code === 200) {
                teamMembers = response.data.map(m => ({
                    id: m.user.id,
                    name: m.user.name,
                    role: m.role_in_project,
                    avatar: m.user.profile_image
                }));
            }
        } catch (error) {
            console.error('Error fetching team members:', error);
        }
    }
    
    // Load members on init
    fetchTeamMembers();

    // Set Phase ID when modal opens
    const createMeetingModal = document.getElementById('createMeetingModal');
    createMeetingModal.addEventListener('show.bs.modal', function () {
        const phaseId = window.currentPhaseId; 
        if (phaseId) {
            document.getElementById('meetingPhaseId').value = phaseId;
        } else {
            document.getElementById('meetingPhaseId').value = '';
        }
    });

    // Show dropdown on focus
    searchInput.addEventListener('focus', () => {
        renderDropdown(teamMembers);
        dropdown.classList.add('show');
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    // Filter members
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        const filtered = teamMembers.filter(m => 
            m.name.toLowerCase().includes(term) || 
            m.role.toLowerCase().includes(term)
        );
        renderDropdown(filtered);
        dropdown.classList.add('show');
    });

    function renderDropdown(members) {
        if (members.length === 0) {
            dropdown.innerHTML = '<div class="p-3 text-muted text-center small">{{ __('messages.no_members_found') }}</div>';
            return;
        }

        dropdown.innerHTML = members.map(member => {
            const isSelected = selectedMembers.find(m => m.id === member.id);
            if (isSelected) return ''; // Skip already selected

            const avatarHtml = member.avatar 
                ? `<img src="${member.avatar}" class="member-avatar" style="object-fit: cover;">`
                : `<div class="member-avatar">${member.name.charAt(0)}</div>`;

            return `
                <div class="member-option" onclick="selectMember(${member.id})">
                    ${avatarHtml}
                    <div>
                        <div class="fw-medium text-dark small">${member.name}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">${member.role}</div>
                    </div>
                </div>
            `;
        }).join('');
    }

    window.selectMember = function(id) {
        const member = teamMembers.find(m => m.id === id);
        if (member && !selectedMembers.find(m => m.id === id)) {
            selectedMembers.push(member);
            renderSelectedTags();
            searchInput.value = '';
            renderDropdown(teamMembers); // Refresh dropdown
            dropdown.classList.remove('show');
        }
    };

    window.removeMember = function(id) {
        selectedMembers = selectedMembers.filter(m => m.id !== id);
        renderSelectedTags();
    };

    function renderSelectedTags() {
        selectedContainer.innerHTML = selectedMembers.map(member => `
            <div class="member-tag">
                <span>${member.name}</span>
                <i class="fas fa-times" onclick="removeMember(${member.id})"></i>
            </div>
        `).join('');
    }

    // Form Submission
    document.getElementById('createMeetingForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Clear previous validation
        this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        this.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        
        // Validate required fields
        let isValid = true;
        const title = document.getElementById('meetingTitleInput');
        const date = document.getElementById('meetingDate');
        const startTime = document.getElementById('meetingStartTime');
        const endTime = document.getElementById('meetingEndTime');
        const location = document.getElementById('meetingLocation');
        
        if (!title.value.trim()) {
            title.classList.add('is-invalid');
            const error = document.createElement('div');
            error.className = 'invalid-feedback';
            error.textContent = '{{ __('messages.meeting_title_required') }}';
            title.parentNode.appendChild(error);
            isValid = false;
        }
        
        if (!date.value) {
            date.classList.add('is-invalid');
            const error = document.createElement('div');
            error.className = 'invalid-feedback';
            error.textContent = '{{ __('messages.date_required') }}';
            date.parentNode.appendChild(error);
            isValid = false;
        }
        
        if (!startTime.value) {
            startTime.classList.add('is-invalid');
            const error = document.createElement('div');
            error.className = 'invalid-feedback';
            error.textContent = '{{ __('messages.start_time_required') }}';
            startTime.parentNode.appendChild(error);
            isValid = false;
        }
        
        if (!endTime.value) {
            endTime.classList.add('is-invalid');
            const error = document.createElement('div');
            error.className = 'invalid-feedback';
            error.textContent = '{{ __('messages.end_time_required') }}';
            endTime.parentNode.appendChild(error);
            isValid = false;
        }
        
        if (!location.value.trim()) {
            location.classList.add('is-invalid');
            const error = document.createElement('div');
            error.className = 'invalid-feedback';
            error.textContent = '{{ __('messages.location_required') }}';
            location.parentNode.appendChild(error);
            isValid = false;
        }
        
        if (!isValid) {
            return;
        }
        
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        
        try {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('messages.processing') }}';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('project_id', window.projectId || 1);
            formData.append('user_id', window.userId || 1);
            
            const phaseId = document.getElementById('meetingPhaseId').value;
            if (phaseId) {
                formData.append('phase_id', phaseId);
            }

            formData.append('title', document.getElementById('meetingTitleInput').value);
            formData.append('date', document.getElementById('meetingDate').value);
            formData.append('start_time', document.getElementById('meetingStartTime').value);
            formData.append('end_time', document.getElementById('meetingEndTime').value);
            formData.append('description', document.getElementById('meetingDescription').value);
            
            const locationType = document.querySelector('input[name="locationType"]:checked').value;
            formData.append('location_type', locationType);
            formData.append('location', document.getElementById('meetingLocation').value);

            // Append attendees
            selectedMembers.forEach(member => {
                formData.append('attendees[]', member.id);
            });

            // Append documents
            uploadedFiles.forEach(file => {
                formData.append('documents[]', file);
            });

            const response = await api.createMeeting(formData);

            if (response.code === 200) {
                toastr.success(response.message || '{{ __('messages.meeting_created_successfully') }}');
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createMeetingModal'));
                if (modal) modal.hide();
                
                // Reset form
                e.target.reset();
                selectedMembers = [];
                renderSelectedTags();
                uploadedFiles.length = 0;
                document.getElementById('uploadedFilesList').innerHTML = '';
                
                // Refresh list if function exists
                if (typeof loadMeetings === 'function') {
                    loadMeetings();
                }
            } else {
                toastr.error(response.message || '{{ __('messages.failed_to_create_meeting') }}');
            }

        } catch (error) {
            console.error('Error creating meeting:', error);
            toastr.error('{{ __('messages.error_creating_meeting') }}');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
});
</script>
