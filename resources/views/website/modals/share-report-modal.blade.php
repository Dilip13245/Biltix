<!-- Share Report Modal -->
<div class="modal fade" id="shareReportModal" tabindex="-1" aria-labelledby="shareReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 600px;">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="modal-header border-0 p-4 pb-2">
                <style>
                    #shareReportModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #shareReportModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="shareReportModalLabel">
                            <span class="d-flex align-items-center justify-content-center text-primary">
                                <i class="fas fa-share-alt"></i>
                            </span>
                            {{ __('messages.share_with') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                @else
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="shareReportModalLabel">
                        <span class="d-flex align-items-center justify-content-center text-primary">
                            <i class="fas fa-share-alt"></i>
                        </span>
                        {{ __('messages.share_with') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>

            <!-- Body -->
            <div class="modal-body p-4 pt-2">
                <div class="d-flex flex-column gap-3">
                    
                    <!-- Option 1: All Stakeholders -->
                    <div class="share-option-card p-3 rounded-3 border cursor-pointer d-flex align-items-center justify-content-between transition-all"
                         id="card-stakeholders" onclick="selectOption('all_stakeholders')">
                        <div>
                            <span class="text-dark fw-medium">{{ __('messages.share_with_all_stakeholders') }}</span>
                        </div>
                        <div class="selection-icon">
                            <i class="fas fa-check-circle text-primary fs-4 d-none active-icon"></i>
                            <div class="inactive-circle border rounded-circle"></div>
                        </div>
                    </div>

                    <!-- Option 2: Selected Members -->
                    <div class="share-option-card p-3 rounded-3 border cursor-pointer transition-all"
                         id="card-members">
                        <div class="d-flex align-items-center justify-content-between" onclick="selectOption('selected_members')">
                            <div>
                                <div class="text-dark fw-medium">{{ __('messages.share_with_selected_members') }}</div>
                                <div class="small text-muted mt-1"><span id="selectedCountDisplay">0</span> {{ __('messages.members_selected') }}</div>
                            </div>
                            <div class="arrow-icon">
                                <i class="fas fa-chevron-down text-muted transition-transform"></i>
                            </div>
                        </div>
                        
                        <!-- Members List -->
                        <div id="membersListContainer" class="mt-3 d-none border-top pt-3">
                            <div id="membersList" class="d-flex flex-column gap-2 custom-scrollbar" style="max-height: 300px; overflow-y: auto;">
                                <!-- Members will be injected here -->
                            </div>
                        </div>
                    </div>

                    <!-- Option 3: Link -->
                    <div class="share-link-card p-3 rounded-3 border bg-light-subtle mt-2">
                        <div class="mb-2 text-dark fw-medium">{{ __('messages.share_via_link') }}</div>
                        <div class="input-group bg-white rounded-3 border overflow-hidden">
                            <input type="text" class="form-control border-0 shadow-none py-2 ps-3" id="shareLink" readonly placeholder="{{ __('messages.link_will_appear_here') }}">
                            <button class="btn btn-link text-primary text-decoration-none px-3" type="button" id="copyLinkBtn">
                                <i class="far fa-copy fs-5"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold fs-6" id="sendShareBtn" style="background-color: #3b71ca; border-color: #3b71ca;">
                    <span id="sendShareBtnText">{{ __('messages.share') }}</span>
                    <span id="sendShareBtnLoader" class="spinner-border spinner-border-sm d-none ms-2"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .share-option-card {
        border-color: #e0e0e0;
        background-color: #fff;
    }
    .share-option-card:hover {
        background-color: #f8f9fa;
        border-color: #d0d0d0;
    }
    .share-option-card.active {
        border-color: #3b71ca; /* Primary Blue */
        background-color: #f0f7ff;
    }
    
    .inactive-circle {
        width: 24px;
        height: 24px;
        border-color: #ccc !important;
        background-color: #fff;
    }
    
    .share-option-card.active .active-icon {
        display: block !important;
    }
    .share-option-card.active .inactive-circle {
        display: none;
    }

    .share-option-card.active .arrow-icon i {
        transform: rotate(180deg);
        color: #3b71ca !important;
    }

    .member-item {
        transition: background-color 0.2s;
        border: 1px solid transparent;
    }
    .member-item:hover {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .member-avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
        background-color: #e9ecef;
    }
    
    .member-check-circle {
        width: 22px;
        height: 22px;
        border: 2px solid #ddd;
        border-radius: 50%;
        transition: all 0.2s;
        background-color: #fff;
    }
    
    .member-item.selected .member-check-circle {
        background-color: #3b71ca;
        border-color: #3b71ca;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
        background-position: center;
        background-repeat: no-repeat;
        background-size: 70%;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #bbb;
    }
    
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .transition-transform {
        transition: transform 0.2s ease-in-out;
    }

    /* RTL Support */
    [dir="rtl"] .input-group .form-control {
        border-radius: 0 0.375rem 0.375rem 0;
    }
    [dir="rtl"] .input-group .btn {
        border-radius: 0.375rem 0 0 0.375rem;
    }
    [dir="rtl"] .me-2 {
        margin-left: 0.5rem !important;
        margin-right: 0 !important;
    }
    [dir="rtl"] .ms-2 {
        margin-right: 0.5rem !important;
        margin-left: 0 !important;
    }
</style>

<script>
    let currentReportFilePath = null;
    let selectedMemberIds = new Set();
    let allProjectMembers = [];
    let currentShareOption = 'all_stakeholders';

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Copy link button
        document.getElementById('copyLinkBtn').addEventListener('click', function() {
            const shareLink = document.getElementById('shareLink').value;
            if (shareLink) {
                navigator.clipboard.writeText(shareLink).then(() => {
                    toastr.success('{{ __('messages.link_copied') }}');
                });
            }
        });

        // Send share button
        document.getElementById('sendShareBtn').addEventListener('click', sendReport);
    });

    function selectOption(option) {
        currentShareOption = option;
        
        // Update UI
        const cardStakeholders = document.getElementById('card-stakeholders');
        const cardMembers = document.getElementById('card-members');
        const membersListContainer = document.getElementById('membersListContainer');

        if (option === 'all_stakeholders') {
            cardStakeholders.classList.add('active');
            cardMembers.classList.remove('active');
            membersListContainer.classList.add('d-none');
        } else {
            cardStakeholders.classList.remove('active');
            cardMembers.classList.add('active');
            membersListContainer.classList.remove('d-none');
        }
    }

    function openShareReportModal(filePath) {
        currentReportFilePath = filePath;
        selectedMemberIds.clear();
        
        // Reset to default state
        selectOption('all_stakeholders');
        updateSelectedCount();

        // Load members
        loadProjectMembers();

        // Generate share link
        generateShareLink(filePath);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('shareReportModal'));
        modal.show();
    }

    async function loadProjectMembers() {
        try {
            const projectId = getProjectIdFromUrl();
            const response = await api.listMembers({
                project_id: projectId
            });

            if (response.code === 200) {
                allProjectMembers = response.data || [];
                renderMembersList();
            }
        } catch (error) {
            console.error('Error loading members:', error);
            toastr.error('{{ __('messages.failed_to_load_members') }}');
        }
    }

    function renderMembersList() {
        const membersList = document.getElementById('membersList');
        membersList.innerHTML = '';

        if (allProjectMembers.length === 0) {
            membersList.innerHTML = '<p class="text-muted small text-center my-3">{{ __('messages.no_members') }}</p>';
            return;
        }

        allProjectMembers.forEach(member => {
            membersList.appendChild(createMemberElement(member));
        });
    }

    function createMemberElement(member) {
        const div = document.createElement('div');
        div.className = 'member-item d-flex align-items-center justify-content-between p-2 rounded cursor-pointer';
        div.dataset.memberId = member.user_id;
        div.onclick = () => toggleMember(member.user_id, div);

        const profileImage = member.user?.profile_image;
        const imageHtml = profileImage ?
            `<img src="${profileImage}" alt="${member.user.name}" class="member-avatar rounded-circle">` :
            `<div class="member-avatar rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"><i class="fas fa-user"></i></div>`;

        const roleDisplay = member.role_in_project ?
            member.role_in_project.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) :
            'Member';

        div.innerHTML = `
            <div class="d-flex align-items-center gap-3">
                ${imageHtml}
                <div>
                    <div class="fw-semibold text-dark fs-6" style="line-height: 1.2;">${member.user?.name || 'Unknown'}</div>
                    <div class="text-muted small">${roleDisplay}</div>
                </div>
            </div>
            <div class="member-check-circle"></div>
        `;

        return div;
    }

    function toggleMember(memberId, element) {
        if (selectedMemberIds.has(memberId)) {
            selectedMemberIds.delete(memberId);
            element.classList.remove('selected');
        } else {
            selectedMemberIds.add(memberId);
            element.classList.add('selected');
        }
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const count = selectedMemberIds.size;
        document.getElementById('selectedCountDisplay').textContent = count;
    }

    function generateShareLink(filePath) {
        const baseUrl = window.location.origin;
        const shareLink = `${baseUrl}/storage/${filePath}`;
        document.getElementById('shareLink').value = shareLink;
    }

    async function sendReport() {
        if (currentShareOption === 'selected_members' && selectedMemberIds.size === 0) {
            toastr.warning('{{ __('messages.select_at_least_one_member') }}');
            return;
        }

        if (currentShareOption === 'all_stakeholders') {
            const stakeholders = allProjectMembers.filter(m => m.role_in_project && m.role_in_project.toLowerCase().includes('stakeholder'));
            if (stakeholders.length === 0) {
                toastr.error('{{ __('messages.no_stakeholder_assigned') }}');
                return;
            }
        }

        const sendBtn = document.getElementById('sendShareBtn');
        const sendBtnText = document.getElementById('sendShareBtnText');
        const sendBtnLoader = document.getElementById('sendShareBtnLoader');

        sendBtn.disabled = true;
        sendBtnText.classList.add('d-none');
        sendBtnLoader.classList.remove('d-none');

        try {
            const projectId = getProjectIdFromUrl();
            let recipientIds = [];

            if (currentShareOption === 'all_stakeholders') {
                recipientIds = allProjectMembers
                    .filter(m => m.role_in_project && m.role_in_project.toLowerCase().includes('stakeholder'))
                    .map(m => m.user_id);
            } else {
                recipientIds = Array.from(selectedMemberIds);
            }

            const response = await api.shareReport({
                project_id: projectId,
                file_path: currentReportFilePath,
                recipient_ids: recipientIds,
                share_option: currentShareOption
            });

            if (response.code === 200) {
                toastr.success('{{ __('messages.report_shared_successfully') }}');
                bootstrap.Modal.getInstance(document.getElementById('shareReportModal')).hide();
            } else {
                toastr.error(response.message || '{{ __('messages.failed_to_share_report') }}');
            }
        } catch (error) {
            console.error('Error sharing report:', error);
            toastr.error('{{ __('messages.error_sharing_report') }}');
        } finally {
            sendBtn.disabled = false;
            sendBtnText.classList.remove('d-none');
            sendBtnLoader.classList.add('d-none');
        }
    }

    function getProjectIdFromUrl() {
        const pathParts = window.location.pathname.split('/');
        const projectIndex = pathParts.indexOf('project');
        return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
    }
</script>
