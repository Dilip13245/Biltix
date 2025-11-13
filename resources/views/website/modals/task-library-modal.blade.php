<div class="modal fade" id="taskLibraryModal" tabindex="-1" aria-hidden="true"
    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #taskLibraryModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #taskLibraryModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title">{{ __('messages.task_library') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                @else
                    <h5 class="modal-title">{{ __('messages.task_library') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="mb-3">
                    <div class="input-group" style="position: relative;">
                        @if(app()->getLocale() == 'ar')
                        <input type="search" class="form-control" id="librarySearchInput"
                            placeholder="{{ __('messages.search_tasks') }}" style="padding-left: 45px; padding-right: 12px;" />
                        <span class="input-group-text" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); z-index: 5; border: none; background: transparent; pointer-events: none; padding: 0;"><i class="fas fa-search" style="color: #6c757d;"></i></span>
                        @else
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="search" class="form-control" id="librarySearchInput"
                            placeholder="{{ __('messages.search_tasks') }}" />
                        @endif
                    </div>
                </div>
                <div id="taskLibraryList" class="task-library-list">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">{{ __('messages.loading') }}...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .task-library-list {
        max-height: 600px;
        overflow-y: auto;
    }

    .task-library-item {
        transition: all 0.2s ease;
        border: 1px solid #e0e0e0;
    }

    .task-library-item:not(.expanded):hover {
        border-color: #F58D2E;
        background-color: #fff8f3;
    }

    .task-library-item h6 {
        color: #333;
        font-weight: 600;
    }

    .task-library-item {
        cursor: pointer;
    }

    .task-library-item.expanded {
        border-color: #F58D2E;
        background-color: #fff8f3;
    }

    .task-library-descriptions {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        padding: 0;
    }

    .task-library-item.expanded .task-library-descriptions {
        max-height: 500px;
        padding: 1rem 0 0 0;
        margin-top: 0.5rem;
        border-top: 1px solid #e0e0e0;
    }

    .task-library-description-item {
        padding: 0.75rem 1rem;
        margin: 0.5rem 0.5rem;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        background-color: #fff;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .task-library-description-item:hover {
        border-color: #F58D2E;
        background-color: #fff8f3;
        transform: translateX(5px);
    }

    .task-library-description-item:last-child {
        margin-bottom: 0;
    }

    .task-library-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .task-library-item-header i.fa-chevron-down {
        transition: transform 0.3s ease;
    }

    .task-library-item.expanded .task-library-item-header i.fa-chevron-down {
        transform: rotate(180deg);
    }

    @media (max-width: 767px) {
        .task-library-list {
            max-height: 500px;
        }

        #taskLibraryModal .modal-dialog {
            margin: 1rem auto;
        }
    }
</style>

<script>
    async function loadTaskLibrary() {
        try {
            const response = await api.getTaskLibrary();

            if (response.code === 200) {
                const taskLibraries = response.data || [];
                renderTaskLibrary(taskLibraries);
            }
        } catch (error) {
            console.error('Error loading task library:', error);
            document.getElementById('taskLibraryList').innerHTML =
                '<p class="text-center text-danger">{{ __('messages.failed_to_load_tasks') }}</p>';
        }
    }

    function renderTaskLibrary(taskLibraries) {
        const container = document.getElementById('taskLibraryList');

        if (!taskLibraries || taskLibraries.length === 0) {
            container.innerHTML =
                '<div class="text-center py-4"><i class="fas fa-inbox fa-3x text-muted mb-3"></i><p class="text-muted">{{ __('messages.no_tasks_found') }}</p></div>';
            return;
        }

        const html = taskLibraries.map(library => {
            const descriptionsCount = library.descriptions ? library.descriptions.length : 0;
            const descriptionsHtml = library.descriptions && library.descriptions.length > 0 ?
                library.descriptions.map(desc =>
                    `<div class="task-library-description-item" data-title="${library.title}" data-description="${desc.description.replace(/"/g, '&quot;').replace(/'/g, '&#39;')}">
                        <p class="mb-0 small">${desc.description}</p>
                    </div>`
                ).join('') :
                `<div class="text-center text-muted py-2"><small>{{ __('messages.no_descriptions_available') }}</small></div>`;

            const descriptionsText = descriptionsCount > 0 ?
                `${descriptionsCount} ${descriptionsCount > 1 ? '{{ __('messages.descriptions_available') }}' : '{{ __('messages.description_available') }}'}` :
                '';

            return `
                <div class="card mb-2 task-library-item" data-library-id="${library.id}">
                    <div class="card-body p-3">
                        <div class="task-library-item-header" onclick="toggleTaskLibraryItem(${library.id})">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="fas fa-tasks text-primary me-3" style="font-size: 20px;"></i>
                                <div>
                                    <h6 class="mb-0">${library.title}</h6>
                                    ${descriptionsText ? `<small class="text-muted">${descriptionsText}</small>` : ''}
                                </div>
                            </div>
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>
                        <div class="task-library-descriptions">
                            ${descriptionsHtml}
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = html;

        // Add click handlers for description items (stop propagation to prevent toggling parent)
        document.querySelectorAll('.task-library-description-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent toggling the parent card
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description')
                    .replace(/&quot;/g, '"')
                    .replace(/&#39;/g, "'");
                selectTaskFromLibrary(title, description);
            });
        });

        // Search functionality
        const searchInput = document.getElementById('librarySearchInput');
        if (searchInput) {
            // Remove old event listeners by cloning
            const newSearchInput = searchInput.cloneNode(true);
            searchInput.parentNode.replaceChild(newSearchInput, searchInput);

            newSearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.task-library-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });
        }
    }

    function toggleTaskLibraryItem(libraryId) {
        const item = document.querySelector(`[data-library-id="${libraryId}"]`);
        if (item) {
            item.classList.toggle('expanded');
        }
    }

    function selectTaskFromLibrary(title, description) {
        // Close library modal
        const libraryModal = bootstrap.Modal.getInstance(document.getElementById('taskLibraryModal'));
        if (libraryModal) libraryModal.hide();

        // Open create task modal with pre-filled data
        setTimeout(() => {
            const taskNameField = document.getElementById('taskName');
            const taskDescriptionField = document.getElementById('taskDescription');

            if (taskNameField) {
                taskNameField.value = title;
            }
            if (taskDescriptionField) {
                taskDescriptionField.value = description;
            }

            const createModal = new bootstrap.Modal(document.getElementById('addTaskModal'));
            createModal.show();
        }, 300);
    }
</script>
