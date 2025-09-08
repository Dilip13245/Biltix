@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Daily Logs')

@section('content')
    <div class="content-header border-0 shadow-none mb-4 d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2>{{ __('messages.daily_logs') }}</h2>
            <p>{{ __('messages.monitor_equipment_staff') }}</p>
        </div>
        <div class="gallery-filters d-flex align-items-center gap-3 flex-wrap">
            <select class="form-select w-auto">
                <option>{{ __('messages.all_teams') }}</option>
                <option>{{ __('messages.team_a') }}</option>
                <option>{{ __('messages.team_b') }}</option>
                <option>{{ __('messages.team_c') }}</option>
            </select>
            @include('website.includes.date-picker', [
              'id' => 'logDate',
              'name' => 'log_date',
              'class' => 'border rounded-3 px-3 py-2 bg-light fw-normal fs14',
              'placeholder' => 'Select date',
              'value' => date('Y-m-d')
            ])
            @can('daily_logs', 'create')
            <button class="btn orange_btn py-2" data-permission="daily_logs:create">
                <i class="fas fa-plus"></i>
                {{ __('messages.add_daily_log') }}
            </button>
            @endcan
        </div>
    </div>
    <section class="px-md-4">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay="0s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt">{{ __('messages.active_equipment') }}</div>
                                <div class="stat-value">24</div>
                            </div>
                            <span class="ms-auto stat-icon bg1">
                                <svg width="26" height="21" viewBox="0 0 26 21" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.53125 0.5C1.49609 0.5 0.65625 1.33984 0.65625 2.375V14.875C0.65625 15.9102 1.49609 16.75 2.53125 16.75H3.15625C3.15625 18.8203 4.83594 20.5 6.90625 20.5C8.97656 20.5 10.6562 18.8203 10.6562 16.75H15.6562C15.6562 18.8203 17.3359 20.5 19.4062 20.5C21.4766 20.5 23.1562 18.8203 23.1562 16.75H24.4062C25.0977 16.75 25.6562 16.1914 25.6562 15.5C25.6562 14.8086 25.0977 14.25 24.4062 14.25V11.75V10.5V9.76953C24.4062 9.10547 24.1445 8.46875 23.6758 8L20.6562 4.98047C20.1875 4.51172 19.5508 4.25 18.8867 4.25H16.9062V2.375C16.9062 1.33984 16.0664 0.5 15.0312 0.5H2.53125ZM16.9062 6.75H18.8867L21.9062 9.76953V10.5H16.9062V6.75ZM5.03125 16.75C5.03125 16.2527 5.22879 15.7758 5.58042 15.4242C5.93206 15.0725 6.40897 14.875 6.90625 14.875C7.40353 14.875 7.88044 15.0725 8.23208 15.4242C8.58371 15.7758 8.78125 16.2527 8.78125 16.75C8.78125 17.2473 8.58371 17.7242 8.23208 18.0758C7.88044 18.4275 7.40353 18.625 6.90625 18.625C6.40897 18.625 5.93206 18.4275 5.58042 18.0758C5.22879 17.7242 5.03125 17.2473 5.03125 16.75ZM19.4062 14.875C19.9035 14.875 20.3804 15.0725 20.7321 15.4242C21.0837 15.7758 21.2812 16.2527 21.2812 16.75C21.2812 17.2473 21.0837 17.7242 20.7321 18.0758C20.3804 18.4275 19.9035 18.625 19.4062 18.625C18.909 18.625 18.4321 18.4275 18.0804 18.0758C17.7288 17.7242 17.5312 17.2473 17.5312 16.75C17.5312 16.2527 17.7288 15.7758 18.0804 15.4242C18.4321 15.0725 18.909 14.875 19.4062 14.875Z"
                                        fill="#4477C4" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay=".4s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt">{{ __('messages.staff_present') }}</div>
                                <div class="stat-value">42</div>
                            </div>
                            <span class="ms-auto stat-icon bg2">
                                <svg width="26" height="21" viewBox="0 0 26 21" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5.95312 0.5C6.78193 0.5 7.57678 0.82924 8.16283 1.41529C8.74889 2.00134 9.07812 2.7962 9.07812 3.625C9.07812 4.4538 8.74889 5.24866 8.16283 5.83471C7.57678 6.42076 6.78193 6.75 5.95312 6.75C5.12432 6.75 4.32947 6.42076 3.74342 5.83471C3.15737 5.24866 2.82812 4.4538 2.82812 3.625C2.82812 2.7962 3.15737 2.00134 3.74342 1.41529C4.32947 0.82924 5.12432 0.5 5.95312 0.5ZM20.3281 0.5C21.1569 0.5 21.9518 0.82924 22.5378 1.41529C23.1239 2.00134 23.4531 2.7962 23.4531 3.625C23.4531 4.4538 23.1239 5.24866 22.5378 5.83471C21.9518 6.42076 21.1569 6.75 20.3281 6.75C19.4993 6.75 18.7045 6.42076 18.1184 5.83471C17.5324 5.24866 17.2031 4.4538 17.2031 3.625C17.2031 2.7962 17.5324 2.00134 18.1184 1.41529C18.7045 0.82924 19.4993 0.5 20.3281 0.5ZM0.328125 12.168C0.328125 9.86719 2.19531 8 4.49609 8H6.16406C6.78516 8 7.375 8.13672 7.90625 8.37891C7.85547 8.66016 7.83203 8.95312 7.83203 9.25C7.83203 10.7422 8.48828 12.082 9.52344 13C9.51562 13 9.50781 13 9.49609 13H1.16016C0.703125 13 0.328125 12.625 0.328125 12.168ZM16.1602 13C16.1523 13 16.1445 13 16.1328 13C17.1719 12.082 17.8242 10.7422 17.8242 9.25C17.8242 8.95312 17.7969 8.66406 17.75 8.37891C18.2812 8.13281 18.8711 8 19.4922 8H21.1602C23.4609 8 25.3281 9.86719 25.3281 12.168C25.3281 12.6289 24.9531 13 24.4961 13H16.1602ZM9.07812 9.25C9.07812 8.25544 9.47321 7.30161 10.1765 6.59835C10.8797 5.89509 11.8336 5.5 12.8281 5.5C13.8227 5.5 14.7765 5.89509 15.4798 6.59835C16.183 7.30161 16.5781 8.25544 16.5781 9.25C16.5781 10.2446 16.183 11.1984 15.4798 11.9017C14.7765 12.6049 13.8227 13 12.8281 13C11.8336 13 10.8797 12.6049 10.1765 11.9017C9.47321 11.1984 9.07812 10.2446 9.07812 9.25ZM5.32812 19.457C5.32812 16.582 7.66016 14.25 10.5352 14.25H15.1211C17.9961 14.25 20.3281 16.582 20.3281 19.457C20.3281 20.0312 19.8633 20.5 19.2852 20.5H6.37109C5.79688 20.5 5.32812 20.0352 5.32812 19.457Z"
                                        fill="#F58D2E" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay=".8s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt">{{ __('messages.tasks_completed') }}</div>
                                <div class="stat-value">18</div>
                            </div>
                            <span class="ms-auto stat-icon bg-green1">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.9844 20.5C13.6365 20.5 16.1801 19.4464 18.0554 17.5711C19.9308 15.6957 20.9844 13.1522 20.9844 10.5C20.9844 7.84784 19.9308 5.3043 18.0554 3.42893C16.1801 1.55357 13.6365 0.5 10.9844 0.5C8.33221 0.5 5.78867 1.55357 3.91331 3.42893C2.03794 5.3043 0.984375 7.84784 0.984375 10.5C0.984375 13.1522 2.03794 15.6957 3.91331 17.5711C5.78867 19.4464 8.33221 20.5 10.9844 20.5ZM15.3984 8.66406L10.3984 13.6641C10.0312 14.0312 9.4375 14.0312 9.07422 13.6641L6.57422 11.1641C6.20703 10.7969 6.20703 10.2031 6.57422 9.83984C6.94141 9.47656 7.53516 9.47266 7.89844 9.83984L9.73438 11.6758L14.0703 7.33594C14.4375 6.96875 15.0312 6.96875 15.3945 7.33594C15.7578 7.70312 15.7617 8.29687 15.3945 8.66016L15.3984 8.66406Z"
                                        fill="#16A34A" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay="0.9s">
                <div class="col-lg-12 mb-4 mt-4">
                    <div class="card B_shadow">
                        <div class="card-body card-body p-0">
                            <!-- {{ __('messages.equipment_log') }} Section -->
                            <div class="equipment-table">
                                <ul class="nav nav-tabs  gap-2 gap-md-3 mb-3 mb-md-4 px-md-4 pt-md-3 pt-2" id="logTabs"
                                    role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="equipment-tab" data-bs-toggle="tab"
                                            data-bs-target="#equipment" type="button"
                                            role="tab">{{ __('messages.equipment_log') }}</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff"
                                            type="button" role="tab">{{ __('messages.staff_log') }}</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="task-tab" data-bs-toggle="tab" data-bs-target="#task"
                                            type="button" role="tab">{{ __('messages.task_log') }}</button>
                                    </li>
                                </ul>
                                <div class="tab-content px-md-4" id="logTabsContent">
                                    <div class="tab-pane fade show active" id="equipment" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.equipment_id') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.type') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.operator') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.status') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.hours_used') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.location') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span
                                                                class="badge bg-orange text-white">{{ __('messages.maintenance') }}</span>
                                                        </td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge badge2">{{ __('messages.idle') }}</span>
                                                        </td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="staff" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.equipment_id') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">Type</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.operator') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">Status</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.hours_used') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">Location</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span
                                                                class="badge bg-orange text-white">{{ __('messages.maintenance') }}</span>
                                                        </td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge badge2">{{ __('messages.idle') }}</span>
                                                        </td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="task" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.equipment_id') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">Type</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.operator') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">Status</th>
                                                        <th class="fw-semibold text-white primaryBG">
                                                            {{ __('messages.hours_used') }}</th>
                                                        <th class="fw-semibold text-white primaryBG">Location</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span
                                                                class="badge bg-orange text-white">{{ __('messages.maintenance') }}</span>
                                                        </td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge badge2">{{ __('messages.idle') }}</span>
                                                        </td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-medium text-black">EXC-001</span>
                                                        </td>
                                                        <td>{{ __('messages.excavator') }}</td>
                                                        <td>{{ __('messages.john_smith') }}</td>
                                                        <td><span class="badge bg-green1">{{ __('messages.active') }}</span></td>
                                                        <td>6.5</td>
                                                        <td>{{ __('messages.zone_a') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Team filter functionality
            const teamFilter = document.querySelector('select.form-select');
            if (teamFilter) {
                teamFilter.addEventListener('change', function() {
                    const selectedTeam = this.value;
                    alert(
                        `Filtering logs for: ${selectedTeam}\n\nThis would filter the table data to show only entries for the selected team.`);
                });
            }

            // Date filter functionality
            const dateInput = document.querySelector('input[type="date"]');
            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    const selectedDate = this.value;
                    alert(
                        `Filtering logs for date: ${selectedDate}\n\nThis would update all tables to show data for the selected date.`);
                });
            }

            // Tab switching functionality is handled by Bootstrap, but we can add custom logic
            const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function(e) {
                    const targetTab = e.target.getAttribute('data-bs-target');
                    console.log(`Switched to tab: ${targetTab}`);

                    // Here you could load different data for each tab
                    if (targetTab === '#equipment') {
                        console.log('Loading equipment log data...');
                    } else if (targetTab === '#staff') {
                        console.log('Loading staff log data...');
                    } else if (targetTab === '#task') {
                        console.log('Loading task log data...');
                    }
                });
            });

            // Make table rows clickable for detailed view
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.style.cursor = 'pointer';
                row.addEventListener('click', function() {
                    const equipmentId = this.cells[0].textContent.trim();
                    const type = this.cells[1].textContent;
                    const operator = this.cells[2].textContent;
                    const status = this.cells[3].textContent;
                    const hours = this.cells[4].textContent;
                    const location = this.cells[5].textContent;

                    alert(
                        `Equipment Details:\n\nID: ${equipmentId}\nType: ${type}\nOperator: ${operator}\nStatus: ${status}\nHours Used: ${hours}\nLocation: ${location}\n\nDetailed log view would open here with:\n• Complete usage history\n• Maintenance records\n• Performance metrics\n• Photo documentation`);
                });
            });
        });
    </script>

@endsection
