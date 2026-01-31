/**
 * Gantt Chart Implementation
 */
document.addEventListener('DOMContentLoaded', function () {
    const chartContainer = document.getElementById('ganttChart');
    const viewButtons = document.querySelectorAll('[data-view]');
    const monthYearFilter = document.getElementById('monthYearFilter');

    // State
    let currentState = {
        view: 'day', // day, week, year
        currentDate: new Date(),
        activities: [],
        cellWidth: 50, // px
        projectId: getProjectIdFromUrl()
    };

    // Helper to get translation
    function t(key, defaultVal) {
        if (window.translations && window.translations[key]) {
            return window.translations[key];
        }
        return defaultVal;
    }

    // Initialize
    init();

    function init() {
        setupEventListeners();
        populateDateFilter();
        loadActivities();
    }

    function getProjectIdFromUrl() {
        const pathParts = window.location.pathname.split('/');
        const projectIndex = pathParts.indexOf('project');
        return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : null;
    }

    async function loadActivities() {
        try {
            if (typeof window.api === 'undefined') {
                console.error('API Client not loaded');
                return;
            }

            const response = await window.api.listGanttActivities({
                project_id: currentState.projectId
            });

            if (response.code === 200) {
                currentState.activities = response.data;
                render();
            } else {
                console.error('Failed to load activities:', response.message);
                if (typeof toastr !== 'undefined') toastr.error(t('failed_load', 'Failed to load activities'));
            }
        } catch (error) {
            console.error('Error loading activities:', error);
        }
    }

    function setupEventListeners() {
        // View Switching
        viewButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                viewButtons.forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                currentState.view = e.target.dataset.view;
                render();
            });
        });

        // Date Filter
        if (monthYearFilter) {
            monthYearFilter.addEventListener('change', (e) => {
                const [year, month] = e.target.value.split('-');
                currentState.currentDate = new Date(year, month - 1, 1);
                render();
            });
        }

        // Add Activity Form Submit
        const addActivityForm = document.querySelector('#addActivityModal form');
        if (addActivityForm) {
            addActivityForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                await saveActivity(new FormData(addActivityForm));
            });
        }
    }

    async function saveActivity(formData) {
        try {
            const data = Object.fromEntries(formData.entries());
            data.project_id = currentState.projectId;

            // Progress is removed from user input, handled by backend

            if (typeof window.api === 'undefined') {
                console.error('API Client not loaded');
                return;
            }

            let response;
            if (data.activity_id) {
                // Update
                response = await window.api.updateGanttActivity(data);
            } else {
                // Create
                response = await window.api.createGanttActivity(data);
            }

            if (response.code === 200) {
                // Close modal
                const modalEl = document.getElementById('addActivityModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                // Reload activities
                loadActivities();

                // Reset form
                document.querySelector('#addActivityModal form').reset();

                if (typeof toastr !== 'undefined') {
                    toastr.success(t('saved_success', 'Activity saved successfully'));
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(response.message || t('failed_save', 'Failed to save activity'));
                } else {
                    alert('Error: ' + response.message);
                }
            }
        } catch (error) {
            console.error('Error saving activity:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error(t('error_occurred', 'An unexpected error occurred.'));
            } else {
                alert('An unexpected error occurred.');
            }
        }
    }

    function populateDateFilter() {
        const date = new Date();
        const currentYear = date.getFullYear();
        const currentMonth = date.getMonth();

        let options = '';
        // Last 2 years to Next 2 years
        for (let y = currentYear - 2; y <= currentYear + 2; y++) {
            for (let m = 0; m < 12; m++) {
                const isSelected = (y === currentYear && m === currentMonth) ? 'selected' : '';
                const monthDate = new Date(y, m);
                // Use a generic locale or try to detect
                const monthName = monthDate.toLocaleString('default', { month: 'long' });
                // We could also localize month names if needed, but browser usually handles it based on locale or we can map it.
                // For now relying on browser default which *might* be English if not configured.
                // But user asked for localization. 
                // Let's rely on standard Intl date formatting which respects browser/system locale. 
                // However, Laravel sets the app locale. Maybe we need strict month names?
                // Let's assume browser locale matches or is acceptable for now.
                options += `<option value="${y}-${m + 1}" ${isSelected}>${monthName} ${y}</option>`;
            }
        }
        monthYearFilter.innerHTML = options;
    }

    // Robust Local Date Parser
    function parseLocal(dateStr) {
        if (!dateStr) return new Date();
        const cleanStr = dateStr.split(' ')[0].split('T')[0];
        const parts = cleanStr.split('-');
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function render() {
        if (!chartContainer) return;

        // Configuration based on view
        const config = getViewConfig(currentState.view);
        currentState.cellWidth = config.cellWidth;

        // Calculate Range
        const { startDate, endDate, totalCols } = calculateDateRange(currentState.currentDate, currentState.view);

        // Clear Chart
        chartContainer.innerHTML = '';

        // Setup Grid Columns
        chartContainer.style.gridTemplateColumns = `250px repeat(${totalCols}, ${config.cellWidth}px)`;

        // 1. Header Row
        renderHeader(startDate, totalCols, config, startDate);

        // 2. Data Rows
        currentState.activities.forEach(activity => {
            const actStart = parseLocal(activity.start_date);
            const actEnd = parseLocal(activity.end_date);

            // Filter logic: Render if overlaps
            if (actStart <= endDate && actEnd >= startDate) {
                renderActivityRow(activity, startDate, totalCols, config);
            }
        });

        // 3. Current Date Line
        renderCurrentDateLine(startDate, totalCols, config);
    }

    function getViewConfig(view) {
        switch (view) {
            case 'week':
                return { cellWidth: 40, unit: 'week', labelFormat: 'W' };
            case 'year':
                return { cellWidth: 50, unit: 'month', labelFormat: 'MMM' };
            case 'day':
            default:
                return { cellWidth: 40, unit: 'day', labelFormat: 'd' };
        }
    }

    function calculateDateRange(centerDate, view) {
        const year = centerDate.getFullYear();
        const month = centerDate.getMonth();
        let startDate, endDate, totalCols;

        if (view === 'day') {
            startDate = new Date(year, month, 1);
            endDate = new Date(year, month + 1, 0); // Last day of month
            totalCols = endDate.getDate();
        } else if (view === 'week') {
            startDate = new Date(year, month - 1, 1);
            endDate = new Date(year, month + 2, 0);
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            totalCols = Math.ceil(diffDays / 7);
        } else { // year
            startDate = new Date(year, 0, 1);
            endDate = new Date(year, 11, 31);
            totalCols = 12;
        }

        return { startDate, endDate, totalCols };
    }

    function renderHeader(startDate, totalCols, config, gridStartDate) {
        const cornerCell = document.createElement('div');
        cornerCell.className = 'gantt-header-cell';
        cornerCell.textContent = t('activity', 'Activity');
        cornerCell.style.position = 'sticky';

        // Sticky logic: Logic must align usually with "Start" edge
        if (document.dir === 'rtl') {
            cornerCell.style.right = '0';
        } else {
            cornerCell.style.left = '0';
        }

        cornerCell.style.zIndex = '20';
        cornerCell.style.backgroundColor = '#fff';
        chartContainer.appendChild(cornerCell);

        for (let i = 0; i < totalCols; i++) {
            const cell = document.createElement('div');
            cell.className = 'gantt-header-cell';

            let label = '';
            let currentDate = new Date(gridStartDate);

            if (currentState.view === 'day') {
                currentDate.setDate(gridStartDate.getDate() + i);
                label = currentDate.getDate();
            } else if (currentState.view === 'week') {
                currentDate.setDate(gridStartDate.getDate() + (i * 7));
                label = `W${getWeekNumber(currentDate)}`;
            } else { // month
                currentDate.setMonth(gridStartDate.getMonth() + i);
                label = currentDate.toLocaleString('default', { month: 'short' });
            }

            cell.textContent = label;
            chartContainer.appendChild(cell);
        }
    }

    function renderActivityRow(activity, gridStartDate, totalCols, config) {
        // Task Name Cell
        const nameCell = document.createElement('div');
        nameCell.className = 'gantt-task-name';
        nameCell.textContent = activity.name;

        // Sticky Logic for Name Cell
        if (document.dir === 'rtl') {
            nameCell.style.right = '0';
        }
        // Note: CSS class handles left:0 for LTR usually, but better to be explicit if needed.
        // The default CSS likely has line 250: left: 0. 
        // We override it for RTL.

        chartContainer.appendChild(nameCell);

        const rowStartIndex = chartContainer.children.length; // Create cells

        for (let i = 0; i < totalCols; i++) {
            const cell = document.createElement('div');
            cell.className = 'gantt-cell';
            chartContainer.appendChild(cell);
        }

        const actStart = parseLocal(activity.start_date);
        const actEnd = parseLocal(activity.end_date);
        const gridStart = new Date(gridStartDate);
        gridStart.setHours(0, 0, 0, 0);

        let startDiff, duration;

        if (currentState.view === 'day') {
            startDiff = (actStart - gridStart) / (1000 * 60 * 60 * 24);
            duration = ((actEnd - actStart) / (1000 * 60 * 60 * 24)) + 1;
        } else if (currentState.view === 'week') {
            startDiff = (actStart - gridStart) / (1000 * 60 * 60 * 24 * 7);
            duration = ((actEnd - actStart) / (1000 * 60 * 60 * 24 * 7));
        } else { // year
            const monthDiffStart = (actStart.getFullYear() - gridStart.getFullYear()) * 12 + (actStart.getMonth() - gridStart.getMonth());
            startDiff = monthDiffStart + (actStart.getDate() / 30);
            const monthDiffTotal = (actEnd.getFullYear() - actStart.getFullYear()) * 12 + (actEnd.getMonth() - actStart.getMonth());
            duration = monthDiffTotal + ((actEnd.getDate() - actStart.getDate()) / 30) + (1 / 30);
        }

        let leftOffset = startDiff * currentState.cellWidth;
        let width = duration * currentState.cellWidth;
        const totalGridWidth = totalCols * currentState.cellWidth;

        // Visual Gap Logic (Margin)
        const GAP = 2; // px on each side

        // Strict clipping against 0 and totalGridWidth
        let clippedLeft = leftOffset;
        let clippedWidth = width;

        // Clip Start
        if (clippedLeft < 0) {
            clippedWidth += clippedLeft; // Reduce width
            clippedLeft = 0;
        }

        // Clip End
        if (clippedLeft + clippedWidth > totalGridWidth) {
            clippedWidth = totalGridWidth - clippedLeft;
        }

        // Only Render if visible
        if (clippedWidth > 0 && clippedLeft < totalGridWidth) {
            const firstCell = chartContainer.children[rowStartIndex];

            if (firstCell) {
                const barContainer = document.createElement('div');

                // Color Logic
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const endDateLocal = parseLocal(activity.end_date);

                let timeClass = '';
                if (['complete', 'approve'].includes(activity.status)) timeClass = 'time-completed';
                else if (today > endDateLocal) timeClass = 'time-delayed';
                else if (actStart > today) timeClass = 'time-future';
                else timeClass = 'time-active';

                barContainer.className = `gantt-bar-container ${timeClass}`;

                // Apply strict positioning with GAP
                // We use GAP to make it look nice inside the 'cell tracks' calculated
                const visualLeft = clippedLeft + GAP;
                const visualWidth = Math.max(clippedWidth - (GAP * 2), 0);

                if (visualWidth > 0) {
                    barContainer.style.width = `${visualWidth}px`;

                    if (document.dir === 'rtl') {
                        barContainer.style.left = 'auto';
                        barContainer.style.right = `${visualLeft}px`;
                    } else {
                        barContainer.style.left = `${visualLeft}px`;
                    }

                    const bar = document.createElement('div');
                    bar.className = 'gantt-bar';
                    const progress = activity.progress || 0;
                    bar.style.width = `${progress}%`;
                    bar.textContent = progress >= 30 ? `${progress}%` : '';

                    barContainer.appendChild(bar);
                    firstCell.appendChild(barContainer);

                    barContainer.style.cursor = 'pointer';
                    barContainer.addEventListener('click', () => {
                        if (today > endDateLocal) openViewModal(activity);
                        else openEditModal(activity);
                    });
                }
            }
        }
    }

    function openEditModal(activity) {
        const modalEl = document.getElementById('addActivityModal');
        const form = modalEl.querySelector('form');

        document.getElementById('activityModalTitle').textContent = t('edit_activity', 'Edit Activity');

        // Populate fields
        form.querySelector('[name="activity_id"]').value = activity.id;
        form.querySelector('[name="name"]').value = activity.name;
        form.querySelector('[name="description"]').value = activity.description || '';
        form.querySelector('[name="start_date"]').value = activity.start_date.split('T')[0];
        form.querySelector('[name="end_date"]').value = activity.end_date.split('T')[0];
        form.querySelector('[name="workers_count"]').value = activity.workers_count;
        form.querySelector('[name="equipment_count"]').value = activity.equipment_count;

        // Status populated
        const statusSelect = form.querySelector('[name="status"]');
        if (['todo', 'in_progress', 'complete', 'approve'].includes(activity.status)) {
            statusSelect.value = activity.status;
        } else {
            statusSelect.value = 'todo';
        }

        // Trigger duration calculation
        const event = new Event('change');
        document.getElementById('startDate').dispatchEvent(event);

        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        modalEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('activityModalTitle').textContent = t('add_activity', 'Add Activity');
            form.reset();
            form.querySelector('[name="activity_id"]').value = '';
        }, { once: true });
    }

    function openViewModal(activity) {
        document.getElementById('viewName').textContent = activity.name || '-';
        document.getElementById('viewDescription').textContent = activity.description || t('no_description', 'No description provided');

        const start = parseLocal(activity.start_date).toLocaleDateString();
        const end = parseLocal(activity.end_date).toLocaleDateString();
        document.getElementById('viewStartDate').textContent = start;
        document.getElementById('viewEndDate').textContent = end;

        document.getElementById('viewWorkers').textContent = activity.workers_count || 0;
        document.getElementById('viewEquipment').textContent = activity.equipment_count || 0;

        const progress = activity.progress || 0;
        const progressBar = document.getElementById('viewProgressBar');
        progressBar.style.width = `${progress}%`;

        // Color for Progress Bar in View Modal
        let colorClass = 'primary';
        if (['complete', 'approve'].includes(activity.status)) colorClass = 'warning';
        else if (activity.progress >= 100) colorClass = 'danger'; // Overdue/Delayed

        progressBar.className = `progress-bar bg-${colorClass}`;
        document.getElementById('viewProgressText').textContent = `${progress}%`;

        const badge = document.getElementById('viewStatusBadge');
        badge.className = `badge bg-${colorClass} d-table mt-1`;
        badge.textContent = getActivityStatusLabel(activity.status);

        const modal = new bootstrap.Modal(document.getElementById('viewActivityModal'));
        modal.show();
    }

    function getActivityStatusLabel(status) {
        return t(status, status);
    }

    function renderCurrentDateLine(gridStartDate, totalCols, config) {
        const today = new Date();
        const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

        let diff;
        if (currentState.view === 'day') {
            diff = (todayDate - gridStartDate) / (1000 * 60 * 60 * 24);
        } else if (currentState.view === 'week') {
            diff = (todayDate - gridStartDate) / (1000 * 60 * 60 * 24 * 7);
        } else { // year
            diff = (todayDate.getFullYear() - gridStartDate.getFullYear()) * 12 + (todayDate.getMonth() - gridStartDate.getMonth());
            diff += todayDate.getDate() / 30;
        }

        if (diff >= 0 && diff <= totalCols) {
            const line = document.createElement('div');
            line.className = 'current-date-line';

            const leftPos = 250 + (diff * config.cellWidth);

            line.style.left = `${leftPos}px`;
            if (document.dir === 'rtl') {
                line.style.left = 'auto';
                line.style.right = `${leftPos}px`;
            }

            chartContainer.appendChild(line);
        }
    }

    function getWeekNumber(d) {
        d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
        d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
        var yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }
});
