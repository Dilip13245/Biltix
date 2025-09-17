@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Project Gallery')

@section('content')
    <div class="content-header border-0 shadow-none mb-4">
        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
            <div>
                <h2>{{ __('messages.photo_gallery') }}</h2>
                <p>{{ __('messages.construction_progress_docs') }}</p>
            </div>
            <input type="file" id="photoUploadInput" accept="image/*" multiple style="display: none;" onchange="handlePhotoUpload(this)">
            <button class="btn orange_btn py-2 px-md-4" onclick="document.getElementById('photoUploadInput').click()">
                <i class="fas fa-arrow-up me-2"></i>
                {{ __('messages.upload_photos') }}
            </button>
        </div>
        <div class="gallery-filters d-flex align-items-center gap-3 flex-wrap  mt-3 mt-md-4">
            <div class="position-relative">
                @include('website.includes.date-picker', [
                    'id' => 'photoDateFilter',
                    'name' => 'photo_date',
                    'placeholder' => __('messages.select_date'),
                    'class' => 'form-control'
                ])
            </div>
            <button id="clearDateFilter" class="btn btn-outline-secondary btn-sm" style="display: none;">
                <i class="fas fa-times {{ is_rtl() ? 'ms-1' : 'me-1' }}"></i>{{ __('messages.clear') }}
            </button>
            <div class="total-photos ">
                <span>{{ __('messages.total') }}: </span>
                <span id="totalPhotosCount" class="fw-bold text-primary text-decoration-none">0 {{ __('messages.photos') }}</span>
            </div>
        </div>
    </div>
    <div id="photosContainer">
        <div class="col-12 text-center py-4">
            <div class="spinner-border" role="status"></div>
            <div class="mt-2">{{ __('messages.loading_files') }}...</div>
        </div>
    </div>


    <script>
        let allPhotos = [];
        let filteredPhotos = [];
        const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone; // Auto-detect user timezone
        
        async function loadPhotos() {
            try {
                const projectId = getProjectIdFromUrl();
                console.log('Loading photos for project:', projectId);
                
                // Load all photos without date filter
                const response = await api.getPhotos({ project_id: projectId });
                console.log('Photos API response:', response);
                
                if (response.code === 200 && response.data) {
                    let photos = response.data.data || response.data;
                    console.log('Photos data:', photos);
                    if (!Array.isArray(photos)) {
                        photos = [];
                    }
                    
                    // Convert UTC dates to local timezone for display
                    photos = photos.map(photo => {
                        photo.local_created_at = convertUTCToLocal(photo.created_at);
                        photo.local_taken_at = convertUTCToLocal(photo.taken_at);
                        return photo;
                    });
                    
                    allPhotos = photos;
                    filteredPhotos = photos;
                    
                    displayPhotos(photos);
                    updatePhotoCount(photos.length);
                } else {
                    console.log('No photos or error response:', response);
                    displayNoPhotos();
                }
            } catch (error) {
                console.error('Failed to load photos:', error);
                displayNoPhotos();
            }
        }
        
        function convertLocalDateToUTC(localDate) {
            // Convert user's local date to UTC date for proper filtering
            // Create date in user's timezone
            const localDateTime = new Date(localDate + 'T12:00:00'); // Use noon to avoid timezone edge cases
            // Convert to UTC and get date part
            const utcDate = new Date(localDateTime.getTime() - (localDateTime.getTimezoneOffset() * 60000));
            return utcDate.toISOString().split('T')[0];
        }
        
        function convertUTCToLocal(utcDateString) {
            if (!utcDateString) return null;
            try {
                // Create date object from UTC string
                const utcDate = new Date(utcDateString);
                if (isNaN(utcDate.getTime())) return utcDateString;
                
                // Get local date string in YYYY-MM-DD format
                const year = utcDate.getFullYear();
                const month = String(utcDate.getMonth() + 1).padStart(2, '0');
                const day = String(utcDate.getDate()).padStart(2, '0');
                
                return `${year}-${month}-${day}`;
            } catch (error) {
                console.error('Date conversion error:', error);
                return utcDateString;
            }
        }
        

        
        function displayPhotos(photos) {
            const container = document.getElementById('photosContainer');
            
            if (!photos || photos.length === 0) {
                displayNoPhotos();
                return;
            }
            
            container.innerHTML = '<div class="CarDs-grid">' + photos.map((photo, index) => {
                // Use local dates for display, fallback to original dates
                const dateToShow = photo.local_taken_at || photo.local_created_at || photo.taken_at || photo.created_at;
                const uploadDate = formatDate(dateToShow);
                const uploaderName = getUploaderName(photo);
                const imagePath = photo.file_path.startsWith('http') ? photo.file_path : `{{ asset('storage') }}/${photo.file_path}`;
                
                const delays = ['0s', '.4s', '.8s', '1.2s', '1.3s', '1.6s'];
                const delay = delays[index % delays.length] || '0s';
                
                return `
                    <div class="CustOm_Card wow fadeInUp" data-wow-delay="${delay}">
                        <div class="plan-image">
                            <img src="${imagePath}" alt="${photo.title || photo.file_name}" onclick="openPhotoViewer('${imagePath}', '${photo.title || photo.file_name}', ${photo.id})" style="cursor: pointer;">
                        </div>
                        <div class="carD-details">
                            <div class="d-flex align-items-center justify-content-between gap-2 gam-md-3 flex-wrap">
                                <p class="revision fw-normal">${uploadDate}</p>
                                <p class="revision fw-normal">${uploaderName}</p>
                            </div>
                        </div>
                    </div>
                `;
            }).join('') + '</div>';
        }
        
        function getUploaderName(photo) {
            const uploaderName = photo.uploader?.name || 'Unknown User';
            return `{{ __('messages.by') }} ${uploaderName}`;
        }
        
        function displayNoPhotos() {
            const container = document.getElementById('photosContainer');
            container.className = 'd-flex justify-content-center align-items-center';
            container.style.minHeight = '400px';
            container.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-camera fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted mb-2">{{ __('messages.no_photos_found') }}</h5>
                    <p class="text-muted">{{ __('messages.upload_photos') }}</p>
                </div>
            `;
            updatePhotoCount(0);
        }
        
        function updatePhotoCount(count) {
            document.getElementById('totalPhotosCount').textContent = `${count} {{ __('messages.photos') }}`;
        }
        
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return 'N/A';
                return date.toLocaleDateString('{{ app()->getLocale() }}', { year: 'numeric', month: 'short', day: 'numeric' });
            } catch (error) {
                console.error('Date formatting error:', error);
                return 'N/A';
            }
        }
        
        function getProjectIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('project');
            return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
        }
        
        async function handlePhotoUpload(input) {
            if (input.files && input.files.length > 0) {
                const files = Array.from(input.files);
                
                try {
                    const projectId = getProjectIdFromUrl();
                    const formData = new FormData();
                    
                    formData.append('project_id', projectId);
                    // user_id will be added automatically by API interceptors
                    files.forEach(file => {
                        formData.append('photos[]', file);
                    });
                    
                    const response = await api.uploadPhotos(formData);
                    console.log('Upload response:', response);
                    
                    if (response.code === 200) {
                        toastr.success(`${files.length} {{ __('messages.photos') }} {{ __('messages.file_uploaded_successfully') }}`);
                        loadPhotos();
                    } else {
                        toastr.error('{{ __('messages.upload_failed') }}: ' + (response.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    toastr.error('{{ __('messages.upload_failed') }}: ' + error.message);
                }
                
                input.value = '';
            }
        }
        
        function openPhotoViewer(imageSrc, title, photoId) {
            const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            
            let headerHTML = '';
            if (isRtl) {
                headerHTML = `
                    <div class="modal-header" style="position: relative !important; padding: 1rem 1rem 1rem 1rem !important; min-height: 60px !important; display: flex !important; align-items: center !important;">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="position: absolute !important; left: 1rem !important;"></button>
                        <h5 class="modal-title" style="position: absolute !important; right: 1rem !important; margin: 0 !important;">${title}</h5>
                    </div>`;
            } else {
                headerHTML = `
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>`;
            }
            
            modal.innerHTML = `
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        ` + headerHTML + `
                        <div class="modal-body text-center p-0">
                            <img src="${imageSrc}" class="img-fluid" alt="${title}" style="max-height: 80vh; width: auto;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" onclick="downloadPhoto('${imageSrc}', '${title}')">
                                <i class="fas fa-download ${isRtl ? 'ms-2' : 'me-2'}"></i>{{ __('messages.download') }}
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deletePhoto(${photoId})">
                                <i class="fas fa-trash ${isRtl ? 'ms-2' : 'me-2'}"></i>{{ __('messages.delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.addEventListener('hidden.bs.modal', function() {
                document.body.removeChild(modal);
            });
        }
        
        function downloadPhoto(imageSrc, title) {
            const link = document.createElement('a');
            link.href = imageSrc;
            link.download = title;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        async function deletePhoto(photoId) {
            if (confirm('{{ __('messages.confirm_delete_photo') }}')) {
                try {
                    const response = await api.deletePhoto({ photo_id: photoId });
                    
                    if (response.code === 200) {
                        toastr.success('{{ __('messages.photo_deleted_successfully') }}');
                        bootstrap.Modal.getInstance(document.querySelector('.modal.show')).hide();
                        loadPhotos();
                    } else {
                        toastr.error('{{ __('messages.failed_to_delete_photo') }}');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    toastr.error('{{ __('messages.failed_to_delete_photo') }}');
                }
            }
        }
        

        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, calling loadPhotos');
            loadPhotos();
            
            // Date filter
            const dateInput = document.getElementById('photoDateFilter');
            const clearBtn = document.getElementById('clearDateFilter');
            
            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    const selectedDate = this.value;
                    console.log('Date filter changed:', selectedDate);
                    
                    if (selectedDate) {
                        // Filter photos client-side by local date
                        filteredPhotos = allPhotos.filter(photo => {
                            const photoLocalDate = photo.local_created_at || convertUTCToLocal(photo.created_at);
                            return photoLocalDate === selectedDate;
                        });
                        clearBtn.style.display = 'inline-block';
                    } else {
                        // Show all photos
                        filteredPhotos = allPhotos;
                        clearBtn.style.display = 'none';
                    }
                    
                    displayPhotos(filteredPhotos);
                    updatePhotoCount(filteredPhotos.length);
                });
            }
            
            // Clear date filter
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    if (dateInput) {
                        dateInput.value = '';
                        // Trigger change event
                        const event = new Event('change');
                        dateInput.dispatchEvent(event);
                    }
                });
            }
        });
    </script>

@endsection
