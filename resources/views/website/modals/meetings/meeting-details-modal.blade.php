<!-- Meeting Details Modal -->
<div class="modal fade" id="meetingDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <style>
                    #meetingDetailsModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #meetingDetailsModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title fw-bold text-dark mb-0">{{ __('messages.meeting_details') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title fw-bold text-dark mb-0">{{ __('messages.meeting_details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>

            <div class="modal-body pt-4">
                <style>
                    /* Meeting Details Modal Styling */


                    #meetingDetailsModal .detail-card {
                        background: white;
                        border: 1px solid #E5E7EB;
                        border-radius: 1rem;
                        padding: 1.5rem;
                        margin-bottom: 1rem;
                    }

                    #meetingDetailsModal .detail-card-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        margin-bottom: 1rem;
                    }

                    #meetingDetailsModal .detail-title {
                        font-weight: 700;
                        font-size: 1.25rem;
                        color: #1F2937;
                        margin-bottom: 0.5rem;
                    }

                    #meetingDetailsModal .status-badge {
                        padding: 0.375rem 0.875rem;
                        border-radius: 1rem;
                        font-size: 0.75rem;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.025em;
                        white-space: nowrap;
                    }

                    #meetingDetailsModal .section-title {
                        color: #C2410C;
                        font-size: 0.95rem;
                        font-weight: 600;
                        margin-bottom: 1rem;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    }

                    #meetingDetailsModal .section-icon {
                        width: 20px;
                        text-align: center;
                        color: #FF9500;
                    }

                    #meetingDetailsModal .detail-text {
                        color: #4B5563;
                        font-size: 0.95rem;
                        line-height: 1.6;
                    }

                    #meetingDetailsModal .location-link {
                        color: #FF9500;
                        text-decoration: none;
                        font-size: 0.9rem;
                        display: inline-flex;
                        align-items: center;
                        gap: 0.375rem;
                        margin-top: 0.5rem;
                        font-weight: 500;
                    }

                    #meetingDetailsModal .location-link:hover {
                        color: #C2410C;
                        text-decoration: underline;
                    }

                    #meetingDetailsModal .agenda-list {
                        list-style: none;
                        padding: 0;
                        margin: 0.75rem 0 0 0;
                    }

                    #meetingDetailsModal .agenda-list li {
                        padding: 0.75rem;
                        background: #F9FAFB;
                        border-radius: 0.5rem;
                        margin-bottom: 0.5rem;
                        color: #4B5563;
                        font-size: 0.9rem;
                        display: flex;
                        gap: 0.75rem;
                    }

                    #meetingDetailsModal .agenda-list li::before {
                        content: attr(data-number) ".";
                        color: #FF9500;
                        font-weight: 700;
                        min-width: 1.5rem;
                    }

                    #meetingDetailsModal .attendee-item {
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                        padding: 0.75rem 0;
                        border-bottom: 1px solid #F3F4F6;
                    }

                    #meetingDetailsModal .attendee-item:last-child {
                        border-bottom: none;
                    }

                    #meetingDetailsModal .attendee-avatar {
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background: #FFF7ED;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #C2410C;
                        font-weight: 600;
                        font-size: 0.9rem;
                        flex-shrink: 0;
                        border: 1px solid #FFDBA6;
                    }

                    #meetingDetailsModal .attendee-name {
                        font-weight: 600;
                        color: #1F2937;
                        font-size: 0.95rem;
                    }

                    #meetingDetailsModal .attendee-role {
                        color: #6B7280;
                        font-size: 0.85rem;
                    }

                    #meetingDetailsModal .document-item {
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                        padding: 1rem;
                        background: #F9FAFB;
                        border-radius: 0.75rem;
                        margin-bottom: 0.75rem;
                        border: 1px solid transparent;
                        transition: all 0.2s;
                    }

                    #meetingDetailsModal .document-item:hover {
                        border-color: #FFDBA6;
                        background: #FFF7ED;
                    }

                    #meetingDetailsModal .document-icon {
                        width: 40px;
                        height: 40px;
                        background: white;
                        border-radius: 0.5rem;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #FF9500;
                        flex-shrink: 0;
                        border: 1px solid #E5E7EB;
                    }

                    #meetingDetailsModal .document-name {
                        font-weight: 500;
                        color: #1F2937;
                        font-size: 0.9rem;
                        margin-bottom: 0.25rem;
                    }

                    #meetingDetailsModal .document-uploader {
                        color: #6B7280;
                        font-size: 0.8rem;
                    }

                    /* Responsive */
                    @media (max-width: 768px) {
                        #meetingDetailsModal .detail-card-header {
                            flex-direction: column;
                            gap: 0.75rem;
                        }
                    }

                    /* RTL Support */
                    [dir="rtl"] #meetingDetailsModal .back-btn:hover {
                        transform: translateX(3px);
                    }
                </style>

                <!-- Title and Status -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h6 class="detail-title" id="detailMeetingTitle">Site Safety Review Meeting</h6>
                            <div class="text-muted small">
                                ID: #<span id="detailMeetingId">12345</span>
                                <span id="detailMeetingPhaseBadge" class="badge bg-light text-dark border ms-2" style="display: none;"></span>
                            </div>
                        </div>
                        <span class="status-badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25" id="detailMeetingStatus">
                            {{ __('messages.upcoming') }}
                        </span>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="detail-card">
                    <div class="section-title">
                        <i class="far fa-calendar section-icon"></i>
                        {{ __('messages.date_time') }}
                    </div>
                    <div class="detail-text">
                        <div class="fw-medium text-dark mb-1" id="detailMeetingDate">November 28, 2025</div>
                        <div class="text-muted" id="detailMeetingTime">10:00 AM - 11:30 AM</div>
                    </div>
                </div>

                <!-- Location -->
                <div class="detail-card">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt section-icon"></i>
                        {{ __('messages.location') }}
                    </div>
                    <div class="detail-text" id="detailMeetingLocation">
                        <div class="fw-medium text-dark">Building Site A, Floor 3</div>
                        <div class="text-muted small mt-1">
                            1234 Construction Blvd, Industrial Zone, City, ST 12345
                        </div>
                    </div>
                    <a href="#" class="location-link" id="detailMapLink" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        {{ __('messages.open_in_maps') }}
                    </a>
                </div>

                <!-- Meeting Agenda -->
                <div class="detail-card">
                    <div class="section-title">
                        <i class="far fa-file-alt section-icon"></i>
                        {{ __('messages.meeting_agenda') }}
                    </div>
                    <div class="detail-text mb-3" id="detailMeetingAgenda">
                        Comprehensive safety review meeting to discuss current site conditions, recent incidents, and preventive measures for the upcoming construction phase.
                    </div>
                    <ol class="agenda-list" id="detailAgendaItems">
                        <li data-number="1">Review of last week's safety incidents</li>
                        <li data-number="2">Discussion of new safety protocols</li>
                        <li data-number="3">Equipment inspection updates</li>
                        <li data-number="4">Q&A and concerns from team members</li>
                    </ol>
                </div>

                <!-- Attendees -->
                <div class="detail-card">
                    <div class="section-title">
                        <i class="fas fa-users section-icon"></i>
                        {{ __('messages.attendees') }}
                        <span class="ms-auto text-muted fw-normal small" id="detailAttendeesCount">5 {{ __('messages.members') }}</span>
                    </div>
                    <div id="detailAttendeesList">
                        <!-- Dynamic attendees list -->
                    </div>
                </div>

                <!-- Documents -->
                <div class="detail-card">
                    <div class="section-title">
                        <i class="far fa-file-pdf section-icon"></i>
                        {{ __('messages.documents_uploads') }}
                        <span class="ms-auto text-muted fw-normal small" id="detailDocumentsCount">3 {{ __('messages.documents') }}</span>
                    </div>
                    <div id="detailDocumentsList">
                        <!-- Dynamic documents list -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Expose globally
    window.loadMeetingDetails = function(details) {
        // Populate details
        document.getElementById('detailMeetingTitle').textContent = details.title;
        document.getElementById('detailMeetingId').textContent = details.id;
        
        // Phase Badge
        const phaseBadge = document.getElementById('detailMeetingPhaseBadge');
        if (details.phase) {
            phaseBadge.textContent = details.phase.name;
            phaseBadge.style.display = 'inline-block';
        } else {
            phaseBadge.style.display = 'none';
        }
        
        const statusBadge = document.getElementById('detailMeetingStatus');
        const status = getStatus(details.date, details.start_time, details.status);
        statusBadge.textContent = status === 'upcoming' ? '{{ __('messages.upcoming') }}' : (status === 'cancelled' ? '{{ __('messages.cancelled') }}' : '{{ __('messages.completed') }}');
        statusBadge.className = `status-badge ${status === 'upcoming' ? 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25' : (status === 'cancelled' ? 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25' : 'bg-success bg-opacity-10 text-success border border-success border-opacity-25')}`;

        document.getElementById('detailMeetingDate').textContent = formatDate(details.date);
        document.getElementById('detailMeetingTime').textContent = `${formatTime(details.start_time)} - ${formatTime(details.end_time)}`;
        document.getElementById('detailMeetingAgenda').textContent = details.description || '{{ __('messages.no_description') }}';

        // Location
        const locationDiv = document.getElementById('detailMeetingLocation');
        if (details.location_type === 'physical') {
            locationDiv.innerHTML = `
                <div class="fw-medium text-dark">${details.location}</div>
            `;
            // Hide map link for now as we don't have lat/long or full address structure yet
            document.getElementById('detailMapLink').style.display = 'none';
        } else {
            locationDiv.innerHTML = `
                <div class="fw-medium text-dark">{{ __('messages.online_meeting') }}</div>
                <a href="${details.location}" target="_blank" class="text-warning text-decoration-none small mt-1 d-block">${details.location}</a>
            `;
            document.getElementById('detailMapLink').style.display = 'none';
        }

        // Agenda items (Description is currently just text, so hiding list or using it if we add structured agenda later)
        document.getElementById('detailAgendaItems').style.display = 'none';

        // Attendees
        document.getElementById('detailAttendeesCount').textContent = `${details.attendees.length} {{ __('messages.members') }}`;
        const attendeesList = document.getElementById('detailAttendeesList');
        
        if (details.attendees.length > 0) {
            attendeesList.innerHTML = details.attendees.map(attendee => {
                const hasImage = attendee.profile_image && attendee.profile_image !== null && attendee.profile_image !== '';
                const avatarHtml = hasImage 
                    ? `<img src="${attendee.profile_image}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;" onerror="this.style.display='none';this.parentElement.innerHTML='${attendee.name.charAt(0)}';this.parentElement.style.padding=''">`
                    : attendee.name.charAt(0);
                
                return `
                    <div class="attendee-item">
                        <div class="attendee-avatar" style="${hasImage ? 'padding:0;' : ''}">
                            ${avatarHtml}
                        </div>
                        <div class="attendee-info">
                            <div class="attendee-name">${attendee.name}</div>
                            <div class="attendee-role">${attendee.pivot && attendee.pivot.role ? attendee.pivot.role : 'Member'}</div>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            attendeesList.innerHTML = '<div class="text-muted small p-3">{{ __('messages.no_attendees') }}</div>';
        }

        // Documents
        document.getElementById('detailDocumentsCount').textContent = `${details.documents.length} {{ __('messages.documents') }}`;
        const documentsList = document.getElementById('detailDocumentsList');
        
        if (details.documents.length > 0) {
            documentsList.innerHTML = details.documents.map(doc => {
                const extension = doc.file_name.split('.').pop().toUpperCase();
                const icon = extension === 'PDF' ? 'fa-file-pdf' : (['JPG','JPEG','PNG'].includes(extension) ? 'fa-file-image' : 'fa-file');
                const uploaderName = doc.uploader ? doc.uploader.name : 'Unknown';
                
                return `
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="far ${icon} fa-lg"></i>
                        </div>
                        <div class="document-info">
                            <div class="document-name">${doc.file_name}</div>
                            <div class="document-uploader">{{ __('messages.uploaded_by') }} : ${uploaderName}</div>
                        </div>
                        <a href="/storage/${doc.file_path}" target="_blank" class="ms-auto btn btn-sm btn-light">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                `;
            }).join('');
        } else {
            documentsList.innerHTML = '<div class="text-muted small p-3">{{ __('messages.no_documents') }}</div>';
        }
    };

    // Helper functions (duplicated from scheduled modal, could be moved to utils)
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
});
</script>
