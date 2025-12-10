<!-- Scheduled Meetings Modal -->
<div class="modal fade" id="scheduledMeetingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <style>
                    #scheduledMeetingsModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #scheduledMeetingsModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-calendar-check me-2 text-warning"></i>
                            {{ __('messages.scheduled_meetings') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-calendar-check me-2 text-warning"></i>
                        {{ __('messages.scheduled_meetings') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>

            <div class="modal-body pt-4">
                <style>
                    /* Scheduled Meetings Modal Styling */
                    #scheduledMeetingsModal .filter-pills {
                        display: flex;
                        gap: 0.75rem;
                        flex-wrap: wrap;
                        padding-bottom: 1.5rem;
                    }

                    #scheduledMeetingsModal .filter-pill {
                        padding: 0.5rem 1.25rem;
                        border-radius: 2rem;
                        border: 1px solid #E5E7EB;
                        background: white;
                        color: #6B7280;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.3s;
                        font-size: 0.9rem;
                    }

                    #scheduledMeetingsModal .filter-pill:hover {
                        background: #F9FAFB;
                        border-color: #D1D5DB;
                    }

                    #scheduledMeetingsModal .filter-pill.active {
                        background: #FFF7ED;
                        border-color: #FF9500;
                        color: #C2410C;
                    }

                    #scheduledMeetingsModal .meeting-card {
                        background: white;
                        border: 1px solid #E5E7EB;
                        border-radius: 1rem;
                        padding: 1.5rem;
                        margin-bottom: 1rem;
                        transition: all 0.3s;
                        cursor: pointer;
                        position: relative;
                        overflow: hidden;
                    }

                    #scheduledMeetingsModal .meeting-card:hover {
                        border-color: #FF9500;
                        box-shadow: 0 4px 12px rgba(255, 149, 0, 0.1);
                        transform: translateY(-2px);
                    }

                    #scheduledMeetingsModal .meeting-card::before {
                        content: '';
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 4px;
                        background: #E5E7EB;
                        transition: background 0.3s;
                    }

                    #scheduledMeetingsModal .meeting-card:hover::before {
                        background: #FF9500;
                    }

                    #scheduledMeetingsModal .meeting-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: start;
                        margin-bottom: 1rem;
                    }

                    #scheduledMeetingsModal .meeting-title-text {
                        font-weight: 600;
                        font-size: 1.1rem;
                        color: #1F2937;
                        margin-bottom: 0.25rem;
                    }

                    #scheduledMeetingsModal .status-badge {
                        padding: 0.375rem 0.875rem;
                        border-radius: 1rem;
                        font-size: 0.75rem;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.025em;
                    }

                    #scheduledMeetingsModal .status-upcoming {
                        background: #FFF7ED;
                        color: #C2410C;
                        border: 1px solid #FFDBA6;
                    }

                    #scheduledMeetingsModal .status-scheduled {
                        background: #ECFDF5;
                        color: #047857;
                        border: 1px solid #A7F3D0;
                    }

                    #scheduledMeetingsModal .meeting-meta {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                        gap: 1rem;
                        margin-bottom: 1.25rem;
                    }

                    #scheduledMeetingsModal .meta-item {
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        color: #4B5563;
                        font-size: 0.9rem;
                    }

                    #scheduledMeetingsModal .meta-item i {
                        width: 16px;
                        color: #9CA3AF;
                    }

                    #scheduledMeetingsModal .attendees-preview {
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        padding-top: 1rem;
                        border-top: 1px solid #F3F4F6;
                    }

                    #scheduledMeetingsModal .avatar-group {
                        display: flex;
                        margin-right: 0.5rem;
                    }

                    #scheduledMeetingsModal .avatar {
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        background: #FFF7ED;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #C2410C;
                        font-size: 0.75rem;
                        font-weight: 600;
                        margin-left: -8px;
                        border: 2px solid white;
                        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                    }

                    #scheduledMeetingsModal .avatar:first-child {
                        margin-left: 0;
                    }

                    #scheduledMeetingsModal .meeting-actions {
                        display: flex;
                        gap: 0.75rem;
                        margin-top: 1rem;
                    }

                    #scheduledMeetingsModal .btn-view-details {
                        flex: 1;
                        padding: 0.625rem;
                        background: white;
                        border: 1px solid #E5E7EB;
                        border-radius: 0.5rem;
                        color: #4B5563;
                        font-weight: 500;
                        transition: all 0.2s;
                    }

                    #scheduledMeetingsModal .btn-view-details:hover {
                        background: #F9FAFB;
                        border-color: #D1D5DB;
                        color: #1F2937;
                    }

                    #scheduledMeetingsModal .btn-join {
                        flex: 1;
                        padding: 0.625rem;
                        background: #FFF7ED;
                        border: 1px solid #FFDBA6;
                        border-radius: 0.5rem;
                        color: #C2410C;
                        font-weight: 500;
                        transition: all 0.2s;
                    }

                    #scheduledMeetingsModal .btn-join:hover {
                        background: #FFEDD5;
                        border-color: #FED7AA;
                    }

                    /* Responsive */
                    @media (max-width: 768px) {
                        #scheduledMeetingsModal .filter-pills {
                            overflow-x: auto;
                            flex-wrap: nowrap;
                            padding-bottom: 1rem;
                            margin-right: -1rem;
                            padding-right: 1rem;
                        }
                        
                        #scheduledMeetingsModal .meeting-meta {
                            grid-template-columns: 1fr;
                            gap: 0.75rem;
                        }
                    }

                    /* RTL Support */
                    [dir="rtl"] #scheduledMeetingsModal .meeting-card::before {
                        left: auto;
                        right: 0;
                    }

                    [dir="rtl"] #scheduledMeetingsModal .avatar {
                        margin-left: 0;
                        margin-right: -8px;
                    }

                    [dir="rtl"] #scheduledMeetingsModal .avatar:first-child {
                        margin-right: 0;
                    }
                </style>

                <!-- Filter Pills -->
                <div class="filter-pills">
                    <button class="filter-pill active" data-filter="all">{{ __('messages.all') }}</button>
                    <button class="filter-pill" data-filter="today">{{ __('messages.today') }}</button>
                    <button class="filter-pill" data-filter="week">{{ __('messages.this_week') }}</button>
                    <button class="filter-pill" data-filter="upcoming">{{ __('messages.upcoming') }}</button>
                </div>

                <!-- Meetings List -->
                <div id="meetingsList">
                    <!-- Dynamic meetings will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let meetings = [];
    let currentFilter = 'all';

    // Expose loadMeetings globally
    window.loadMeetings = async function(phaseId = null) {
        try {
            const listContainer = document.getElementById('meetingsList');
            listContainer.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-warning"></i></div>';

            const params = {
                project_id: window.projectId || 1,
                limit: 50
            };

            // Add phase_id from parameter or global variable
            const finalPhaseId = phaseId || window.currentPhaseId;
            if (finalPhaseId) {
                params.phase_id = finalPhaseId;
            }

            const response = await api.listMeetings(params);

            if (response.code === 200) {
                meetings = response.data.map(m => ({
                    id: m.id,
                    title: m.title,
                    status: getStatus(m.date, m.start_time, m.status),
                    date: formatDate(m.date),
                    time: formatTime(m.start_time),
                    type: m.location_type,
                    location: m.location,
                    phase: m.phase ? m.phase.name : null,
                    attendees: m.attendees.map(a => ({
                        name: a.name,
                        role: 'Member' // Role not always available in attendee pivot
                    }))
                }));
                renderMeetings(currentFilter);
            } else {
                listContainer.innerHTML = `<div class="text-center py-5 text-danger">${response.message}</div>`;
            }
        } catch (error) {
            console.error('Error loading meetings:', error);
            document.getElementById('meetingsList').innerHTML = '<div class="text-center py-5 text-danger">{{ __('messages.error_loading_meetings') }}</div>';
        }
    };

    function getStatus(dateStr, timeStr, dbStatus) {
        if (dbStatus === 'cancelled') return 'cancelled';
        
        const meetingDate = new Date(dateStr);
        const today = new Date();
        meetingDate.setHours(0,0,0,0);
        today.setHours(0,0,0,0);
        
        return meetingDate >= today ? 'upcoming' : 'completed';
    }

    function formatDate(dateStr) {
        return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function formatTime(timeStr) {
        return new Date(`2000-01-01T${timeStr}`).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
    }

    function renderMeetings(filter = 'all') {
        const list = document.getElementById('meetingsList');
        
        let filtered = meetings;
        const now = new Date();

        if (filter === 'upcoming') {
            filtered = meetings.filter(m => m.status === 'upcoming');
        } else if (filter === 'today') {
            const todayStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            filtered = meetings.filter(m => m.date === todayStr);
        } else if (filter === 'week') {
            // Simple week filter logic (next 7 days)
            const nextWeek = new Date();
            nextWeek.setDate(now.getDate() + 7);
            filtered = meetings.filter(m => {
                const mDate = new Date(m.date);
                return mDate >= now && mDate <= nextWeek;
            });
        }

        if (filtered.length === 0) {
            list.innerHTML = '<div class="text-center py-5 text-muted">{{ __('messages.no_meetings_found') }}</div>';
            return;
        }

        list.innerHTML = filtered.map(meeting => {
            const statusClass = meeting.status === 'upcoming' ? 'status-upcoming' : 'status-scheduled';
            const statusText = meeting.status === 'upcoming' ? '{{ __('messages.upcoming') }}' : '{{ __('messages.completed') }}';
            
            const avatars = meeting.attendees.slice(0, 3).map(a => 
                `<div class="avatar" title="${a.name}">${a.name.charAt(0)}</div>`
            ).join('');
            const moreCount = meeting.attendees.length - 3;

            return `
                <div class="meeting-card">
                    <div class="meeting-header">
                        <div style="flex: 1;">
                            <div class="meeting-title-text">${meeting.title}</div>
                            <div class="text-muted small">
                                ID: #${meeting.id}
                                ${meeting.phase ? `<span class="badge bg-light text-dark border ms-2">${meeting.phase}</span>` : ''}
                            </div>
                        </div>
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </div>
                    
                    <div class="meeting-meta">
                        <div class="meta-item">
                            <i class="far fa-calendar"></i>
                            <span>${meeting.date}</span>
                        </div>
                        <div class="meta-item">
                            <i class="far fa-clock"></i>
                            <span>${meeting.time}</span>
                        </div>
                        <div class="meta-item">
                            <i class="${meeting.type === 'physical' ? 'fas fa-map-marker-alt' : 'fas fa-video'}"></i>
                            <span>${meeting.location}</span>
                        </div>
                    </div>

                    <div class="attendees-preview">
                        <div class="d-flex align-items-center gap-2 flex-grow-1">
                            <div class="avatar-group">${avatars}</div>
                            ${moreCount > 0 ? `<span class="text-muted small">+${moreCount} more</span>` : ''}
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-light border" onclick="viewMeetingDetails(${meeting.id})">
                                {{ __('messages.view_details') }}
                            </button>
                            ${meeting.type === 'online' ? `
                                <button class="btn btn-sm btn-warning text-white" onclick="joinMeeting('${meeting.location}')">
                                    {{ __('messages.join_meeting') }}
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Filter functionality
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            renderMeetings(currentFilter);
        });
    });

    // View meeting details
    // View meeting details
    window.viewMeetingDetails = async function(id) {
        try {
            // Show loading state if needed, or just wait
            const response = await api.getMeetingDetails(id);
            
            if (response.code === 200) {
                if (typeof window.loadMeetingDetails === 'function') {
                    window.loadMeetingDetails(response.data);
                    const modal = new bootstrap.Modal(document.getElementById('meetingDetailsModal'));
                    modal.show();
                } else {
                    console.error('loadMeetingDetails function not found');
                    toastr.error('{{ __('messages.error_loading_details') }}');
                }
            } else {
                toastr.error(response.message || '{{ __('messages.failed_to_load_details') }}');
            }
        } catch (error) {
            console.error('Error fetching meeting details:', error);
            toastr.error('{{ __('messages.error_loading_details') }}');
        }
    };

    // Join online meeting
    window.joinMeeting = function(link) {
        if (!link.startsWith('http')) {
            link = 'https://' + link;
        }
        window.open(link, '_blank');
    };

    // Initialize - load meetings when modal opens
    const scheduledMeetingsModal = document.getElementById('scheduledMeetingsModal');
    scheduledMeetingsModal.addEventListener('show.bs.modal', function(event) {
        // Use global currentPhaseId set by openPhaseModal function
        const phaseId = window.currentPhaseId;
        loadMeetings(phaseId);
    });
});
</script>
