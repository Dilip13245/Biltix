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
                    toastr.success(data.activity_id ? 'Activity updated successfully' : 'Activity saved successfully');
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(response.message || 'Failed to save activity');
                } else {
                    alert('Error: ' + response.message);
                }
            }
        } catch (error) {
            console.error('Error saving activity:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('An unexpected error occurred.');
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
                const monthName = new Date(y, m).toLocaleString('default', { month: 'long' });
                options += `<option value="${y}-${m + 1}" ${isSelected}>${monthName} ${y}</option>`;
            }
        }
        monthYearFilter.innerHTML = options;
    }

    // Robust Local Date Parser
    function parseLocal(dateStr) {
        if (!dateStr) return new Date();
        // Handle "2026-01-26 12:00:00" or "2026-01-26T12:00:00" or "2026-01-26"
        const cleanStr = dateStr.split(' ')[0].split('T')[0];
        const parts = cleanStr.split('-');
        // Year, Month (0-based), Day
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
            // Check if activity overlaps with current view range
            const actStart = parseLocal(activity.start_date);
            const actEnd = parseLocal(activity.end_date);

            // Filter logic
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
                return { cellWidth: 40, unit: 'week', labelFormat: 'W' }; // Display week number
            case 'year':
                return { cellWidth: 50, unit: 'month', labelFormat: 'MMM' }; // Display Month
            case 'day':
            default:
                return { cellWidth: 40, unit: 'day', labelFormat: 'd' }; // Display Day 1, 2...
        }
    }

    function calculateDateRange(centerDate, view) {
        const year = centerDate.getFullYear();
        const month = centerDate.getMonth();

        // For simplicity, render the whole selected month (Day view), 
        // or a broader range for Week/Year views.
        let startDate, endDate, totalCols;

        if (view === 'day') {
            startDate = new Date(year, month, 1);
            endDate = new Date(year, month + 1, 0); // Last day of month
            totalCols = endDate.getDate();
        } else if (view === 'week') {
            // Show 3 months for better week context
            startDate = new Date(year, month - 1, 1);
            endDate = new Date(year, month + 2, 0);
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            totalCols = Math.ceil(diffDays / 7);
        } else { // year
            // Show full year
            startDate = new Date(year, 0, 1);
            endDate = new Date(year, 11, 31);
            totalCols = 12;
        }

        return { startDate, endDate, totalCols };
    }

    function renderHeader(startDate, totalCols, config, gridStartDate) {
        // Empty top-left cell for task names
        const cornerCell = document.createElement('div');
        cornerCell.className = 'gantt-header-cell';
        cornerCell.textContent = 'Activity';
        cornerCell.style.position = 'sticky';
        cornerCell.style.left = '0';
        cornerCell.style.zIndex = '20';
        cornerCell.style.backgroundColor = '#fff'; // Ensure opaque
        chartContainer.appendChild(cornerCell);

        // Render time headers
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
        chartContainer.appendChild(nameCell);

        const rowStartIndex = chartContainer.children.length; // Create cells

        for (let i = 0; i < totalCols; i++) {
            const cell = document.createElement('div');
            cell.className = 'gantt-cell';
            chartContainer.appendChild(cell);
        }

        const actStart = parseLocal(activity.start_date);
        const actEnd = parseLocal(activity.end_date);

        // Grid Start (already local midnight from calculateDateRange)
        const gridStart = new Date(gridStartDate);
        gridStart.setHours(0, 0, 0, 0);

        // Layout Calculation
        let startDiff, duration;

        if (currentState.view === 'day') {
            startDiff = (actStart - gridStart) / (1000 * 60 * 60 * 24);
            duration = ((actEnd - actStart) / (1000 * 60 * 60 * 24)) + 1;
        } else if (currentState.view === 'week') {
            startDiff = (actStart - gridStart) / (1000 * 60 * 60 * 24 * 7);
            duration = ((actEnd - actStart) / (1000 * 60 * 60 * 24 * 7));
        } else { // year
            // Month diff + day fraction
            const monthDiffStart = (actStart.getFullYear() - gridStart.getFullYear()) * 12 + (actStart.getMonth() - gridStart.getMonth());
            startDiff = monthDiffStart + (actStart.getDate() / 30);

            const monthDiffTotal = (actEnd.getFullYear() - actStart.getFullYear()) * 12 + (actEnd.getMonth() - actStart.getMonth());
            duration = monthDiffTotal + ((actEnd.getDate() - actStart.getDate()) / 30) + (1 / 30);
        }

        let leftOffset = startDiff * currentState.cellWidth;
        let width = duration * currentState.cellWidth;
        const totalGridWidth = totalCols * currentState.cellWidth;

        // Visual Clamping
        // If starts before grid
        if (leftOffset < 0) {
            width += leftOffset; // remove negative part from width
            leftOffset = 0;
        }

        // If extends beyond grid
        if (leftOffset + width > totalGridWidth) {
            width = totalGridWidth - leftOffset;
        }

        const firstCell = chartContainer.children[rowStartIndex];

        if (firstCell && width > 0) {
            const barContainer = document.createElement('div');

            // Time-based Coloring Logic
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            let timeClass = '';
            // actStart is strictly local midnight now
            if (actStart > today) {
                timeClass = 'time-future'; // Red
            } else {
                timeClass = 'time-active'; // Yellow/Blue
            }

            barContainer.className = `gantt-bar-container ${timeClass}`;
            barContainer.style.left = `${leftOffset + 10}px`; // +10 padding adjustment
            barContainer.style.width = `${Math.max(width - 2, 5)}px`;

            const bar = document.createElement('div');
            bar.className = 'gantt-bar';
            bar.style.width = `${activity.progress}%`;
            bar.textContent = activity.progress >= 30 ? `${activity.progress}%` : '';

            barContainer.appendChild(bar);

            if (document.dir === 'rtl') {
                barContainer.style.left = 'auto';
                barContainer.style.right = `${leftOffset + 10}px`;
            }

            firstCell.appendChild(barContainer);

            // Click Event
            barContainer.style.cursor = 'pointer';
            barContainer.addEventListener('click', () => {
                // Determine Edit/View based on End Date vs Today
                const endDateLocal = parseLocal(activity.end_date); // Consistent parsing
                if (endDateLocal >= today) {
                    openEditModal(activity);
                } else {
                    openViewModal(activity);
                }
            });
        }
    }

    function openEditModal(activity) {
        const modalEl = document.getElementById('addActivityModal');
        const form = modalEl.querySelector('form');

        // title
        document.getElementById('activityModalTitle').textContent = 'Edit Activity';

        // Populate fields
        form.querySelector('[name="activity_id"]').value = activity.id;
        form.querySelector('[name="name"]').value = activity.name;
        form.querySelector('[name="description"]').value = activity.description || '';
        form.querySelector('[name="start_date"]').value = activity.start_date.split('T')[0]; // simple split for value
        form.querySelector('[name="end_date"]').value = activity.end_date.split('T')[0];
        form.querySelector('[name="workers_count"]').value = activity.workers_count;
        form.querySelector('[name="equipment_count"]').value = activity.equipment_count;
        form.querySelector('[name="progress"]').value = activity.progress;

        const statusSelect = form.querySelector('[name="status"]');
        if (["planned", "in_progress", "completed", "delayed"].includes(activity.status)) {
            statusSelect.value = activity.status;
        } else {
            statusSelect.value = ''; // Auto/Unknown
        }

        // Trigger duration calculation
        const event = new Event('change');
        document.getElementById('startDate').dispatchEvent(event);

        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        // Reset to "Add" on close
        modalEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('activityModalTitle').textContent = 'Add Activity';
            form.reset();
            form.querySelector('[name="activity_id"]').value = '';
        }, { once: true });
    }

    function openViewModal(activity) {
        document.getElementById('viewName').textContent = activity.name || '-';
        document.getElementById('viewDescription').textContent = activity.description || 'No description provided';

        // Dates
        const start = parseLocal(activity.start_date).toLocaleDateString();
        const end = parseLocal(activity.end_date).toLocaleDateString();
        document.getElementById('viewStartDate').textContent = start;
        document.getElementById('viewEndDate').textContent = end;

        // Resources
        document.getElementById('viewWorkers').textContent = activity.workers_count || 0;
        document.getElementById('viewEquipment').textContent = activity.equipment_count || 0;

        // Progress
        const progress = activity.progress || 0;
        const progressBar = document.getElementById('viewProgressBar');
        progressBar.style.width = `${progress}%`;
        progressBar.className = `progress-bar bg-${getStatusColorClass(activity.status)}`;
        document.getElementById('viewProgressText').textContent = `${progress}%`;

        // Status Badge
        const badge = document.getElementById('viewStatusBadge');
        badge.className = `badge bg-${getStatusColorClass(activity.status)} d-table mt-1`;
        badge.textContent = formatStatus(activity.status);

        // Show Modal
        const modal = new bootstrap.Modal(document.getElementById('viewActivityModal'));
        modal.show();
    }

    function getStatusColorClass(status) {
        switch (status) {
            case 'completed': return 'success';
            case 'in_progress': return 'primary';
            case 'delayed': return 'danger';
            case 'planned': return 'secondary';
            default: return 'primary';
        }
    }

    function formatStatus(status) {
        if (!status) return 'Unknown';
        return status.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    function renderCurrentDateLine(gridStartDate, totalCols, config) {
        const today = new Date(); // Local time
        // Clear time part for accurate date matching in Day view
        const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

        // Calculate offset
        let diff;
        if (currentState.view === 'day') {
            diff = (todayDate - gridStartDate) / (1000 * 60 * 60 * 24);
        } else if (currentState.view === 'week') {
            diff = (todayDate - gridStartDate) / (1000 * 60 * 60 * 24 * 7);
        } else { // year
            // Approx month diff
            diff = (todayDate.getFullYear() - gridStartDate.getFullYear()) * 12 + (todayDate.getMonth() - gridStartDate.getMonth());
            // Add day fraction
            diff += todayDate.getDate() / 30;
        }

        // Check if within view
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
        var weekNo = Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        return weekNo;
    }
});
