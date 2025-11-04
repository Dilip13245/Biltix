{{-- Modern Date Picker --}}
@php
    $id = $id ?? 'datePicker_' . uniqid();
    $name = $name ?? 'date';
    $value = $value ?? '';
    $placeholder = $placeholder ?? __('messages.select_date');
    $required = $required ?? false;
    $class = $class ?? 'form-control Input_control';
    $minDate = $minDate ?? null;
    $maxDate = $maxDate ?? null;
@endphp

<div class="modern-datepicker-wrapper position-relative">
    <div class="modern-datepicker-input-group">
        <input type="text" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
            placeholder="{{ $placeholder }}" class="modern-datepicker-input" {{ $required ? 'required' : '' }}
            {{ is_rtl() ? 'dir=rtl' : '' }} readonly>
        <div class="modern-datepicker-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z"
                    stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path
                    d="M15.6947 13.7002H15.7037M15.6947 16.7002H15.7037M11.9955 13.7002H12.0045M11.9955 16.7002H12.0045M8.29431 13.7002H8.30329M8.29431 16.7002H8.30329"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    <div id="{{ $id }}_calendar" class="modern-datepicker-calendar" style="display: none;">
        <div class="datepicker-backdrop"></div>
        <div class="datepicker-content"></div>
    </div>
</div>

<style>
    /* Modern Date Picker Styles */
    .modern-datepicker-wrapper {
        width: 100%;
    }

    .modern-datepicker-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .modern-datepicker-input {
        width: 100%;
        padding: 16px 20px;
        padding-{{ is_rtl() ? 'left' : 'right' }}: 55px;
        border: 2px solid #e8ecf4;
        border-radius: 16px;
        font-size: 15px;
        font-weight: 500;
        color: #2d3748;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        font-family: 'Poppins', sans-serif;
    }

    .modern-datepicker-input:focus {
        outline: none;
        border-color: #F58D2E;
        box-shadow: 0 0 0 4px rgba(245, 141, 46, 0.1), 0 4px 20px rgba(245, 141, 46, 0.15);
        transform: translateY(-1px);
    }

    .modern-datepicker-input::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }

    .modern-datepicker-icon {
        position: absolute;
        {{ is_rtl() ? 'left: 18px;' : 'right: 18px;' }} top: 50%;
        transform: translateY(-50%);
        color: #718096;
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 2;
    }

    .modern-datepicker-input:focus+.modern-datepicker-icon {
        color: #F58D2E;
    }

    .modern-datepicker-calendar {
        position: absolute;
        {{ is_rtl() ? 'right: 0;' : 'left: 0;' }} z-index: 1000;
        width: 100%;
        min-width: 350px;
        max-width: 350px;
    }

    .modern-datepicker-calendar.position-top {
        bottom: 100%;
        margin-bottom: 8px;
    }

    .modern-datepicker-calendar.position-bottom {
        top: 100%;
        margin-top: 8px;
    }

    .datepicker-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.1);
        z-index: -1;
    }

    .datepicker-content {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 8px 25px rgba(0, 0, 0, 0.08);
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        max-width: 350px;
        box-sizing: border-box;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .datepicker-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e8ecf4;
        gap: 8px;
    }

    .datepicker-nav {
        width: 36px;
        height: 36px;
        border: 1.5px solid #e8ecf4;
        background: #ffffff;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #718096;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }

    .datepicker-nav:hover {
        background: #F58D2E;
        border-color: #F58D2E;
        color: white;
        box-shadow: 0 4px 12px rgba(245, 141, 46, 0.25);
    }

    .datepicker-month {
        display: flex;
        gap: 6px;
        align-items: center;
        flex: 1;
        justify-content: center;
    }

    /* Custom Dropdown Styles - Matching Website Design */
    .datepicker-dropdown-wrapper {
        position: relative;
        display: inline-block;
    }

    .datepicker-dropdown-trigger {
        font-weight: 600;
        font-size: 14px;
        color: #2d3748;
        background: #ffffff;
        border: 1.5px solid #e8ecf4;
        border-radius: 8px;
        padding: 6px 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        outline: none;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 110px;
        position: relative;
    }

    .datepicker-dropdown-trigger.year-trigger {
        min-width: 75px;
    }

    .datepicker-dropdown-trigger:hover {
        border-color: #F58D2E;
        background-color: #fffbf7;
        box-shadow: 0 2px 8px rgba(245, 141, 46, 0.15);
    }

    .datepicker-dropdown-trigger.active {
        border-color: #F58D2E;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(245, 141, 46, 0.1);
    }


    .datepicker-dropdown-options {
        position: absolute;
        top: calc(100% + 4px);
        {{ is_rtl() ? 'right: 0;' : 'left: 0;' }}
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 1001;
        display: none;
        min-width: 100%;
        max-height: 240px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .datepicker-dropdown-options.year-options {
        max-height: 280px; /* Show ~10 years with scroll */
    }

    .datepicker-dropdown-options.show {
        display: block;
    }

    .datepicker-dropdown-option {
        padding: 10px 12px;
        cursor: pointer;
        transition: background-color 0.2s;
        font-size: 13px;
        font-weight: 500;
        color: #2d3748;
        white-space: nowrap;
    }

    .datepicker-dropdown-option:hover {
        background-color: #f8f9fa;
    }

    .datepicker-dropdown-option.selected {
        background-color: #fff5f0;
        color: #F58D2E;
        font-weight: 600;
    }

    /* Scrollbar styling */
    .datepicker-dropdown-options::-webkit-scrollbar {
        width: 6px;
    }

    .datepicker-dropdown-options::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .datepicker-dropdown-options::-webkit-scrollbar-thumb {
        background: #F58D2E;
        border-radius: 4px;
    }

    .datepicker-dropdown-options::-webkit-scrollbar-thumb:hover {
        background: #e07a1f;
    }

    [dir="rtl"] .datepicker-dropdown-options {
        left: auto;
        right: 0;
    }

    .datepicker-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        /* gap: 4px; */
        width: 100%;
        max-width: 308px;
        margin: 0 auto;
    }

    .datepicker-weekday {
        padding: 8px 0;
        text-align: center;
        font-weight: 600;
        font-size: 11px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: 44px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        box-sizing: border-box;
        margin: 0 auto;
    }

    .datepicker-day {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: #4a5568;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .datepicker-day:hover {
        background: linear-gradient(135deg, #fff5f0, #ffe8d6);
        color: #F58D2E;
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(245, 141, 46, 0.2);
    }

    .datepicker-day.selected {
        background: linear-gradient(135deg, #F58D2E, #ff7b00);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(245, 141, 46, 0.4);
    }

    .datepicker-day.selected::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 14px;
        background: linear-gradient(135deg, #F58D2E, #ff7b00);
        z-index: -1;
        opacity: 0.3;
    }

    .datepicker-day.today {
        background: #e6fffa;
        color: #00b894;
        font-weight: 700;
    }

    .datepicker-day.disabled {
        color: #cbd5e0;
        cursor: not-allowed;
        background: transparent;
    }

    .datepicker-day.disabled:hover {
        background: transparent;
        transform: none;
        box-shadow: none;
    }

    /* RTL Support */
    [dir="rtl"] .modern-datepicker-input {
        padding-right: 20px;
        padding-left: 55px;
    }

    [dir="rtl"] .modern-datepicker-icon {
        right: auto;
        left: 18px;
    }

    [dir="rtl"] .modern-datepicker-calendar {
        left: auto;
        right: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-datepicker-calendar {
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%);
            min-width: 280px;
            max-width: 300px;
            width: 90vw;
        }

        [dir="rtl"] .modern-datepicker-calendar {
            right: 50% !important;
            left: auto !important;
            transform: translateX(50%);
        }

        .datepicker-content {
            padding: 12px;
            max-width: 100%;
            width: 100%;
        }

        .datepicker-grid {
            max-width: 266px;
            gap: 2px;
            margin: 0 auto;
        }

        .datepicker-day {
            width: 36px;
            height: 36px;
            font-size: 12px;
        }

        .datepicker-weekday {
            width: 36px;
            font-size: 9px;
            padding: 4px 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('{{ $id }}');
        const calendar = document.getElementById('{{ $id }}_calendar');
        const content = calendar?.querySelector('.datepicker-content');

        if (!input || !calendar || !content) return;

        let minDate =
            @if ($minDate)
                new Date('{{ $minDate }}')
            @else
                null
            @endif ;

        // Check for dynamic minimum date (for due date picker)
        if ('{{ $id }}' === 'project_due_date' && window.dueDateMinDate) {
            minDate = window.dueDateMinDate;
        }
        const maxDate =
            @if ($maxDate)
                new Date('{{ $maxDate }}')
            @else
                null
            @endif ;

        let currentDate = new Date();
        let selectedDate = null;
        const today = new Date();

        const months =
            @if (is_rtl())
                ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر',
                    'نوفمبر', 'ديسمبر'
                ]
            @else
                ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ]
            @endif ;

        const weekdays =
            @if (is_rtl())
                ['أح', 'إث', 'ثل', 'أر', 'خم', 'جم', 'سب']
            @else
                ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
            @endif ;

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            let html = '<div class="datepicker-header">';
            @if (is_rtl())
                html +=
                    '<button type="button" class="datepicker-nav" onclick="window.calendarNext_{{ $id }}()">‹</button>';
            @else
                html +=
                    '<button type="button" class="datepicker-nav" onclick="window.calendarPrev_{{ $id }}()">‹</button>';
            @endif
            
            html += '<div class="datepicker-month">';
            
            // Month Dropdown - Custom Design
            html += '<div class="datepicker-dropdown-wrapper">';
            html += '<div class="datepicker-dropdown-trigger month-trigger" onclick="window.toggleDatepickerDropdown_{{ $id }}(\'month\')">';
            html += '<span>' + months[month] + '</span>';
            html += '</div>';
            html += '<div class="datepicker-dropdown-options month-options" id="monthOptions_{{ $id }}">';
            months.forEach((m, i) => {
                html += '<div class="datepicker-dropdown-option' + (i === month ? ' selected' : '') + '" onclick="window.selectDatepickerMonth_{{ $id }}(' + i + ')">' + m + '</div>';
            });
            html += '</div>';
            html += '</div>';
            
            // Year Dropdown - Custom Design with infinite scroll
            html += '<div class="datepicker-dropdown-wrapper">';
            html += '<div class="datepicker-dropdown-trigger year-trigger" onclick="window.toggleDatepickerDropdown_{{ $id }}(\'year\')">';
            html += '<span>' + year + '</span>';
            html += '</div>';
            html += '<div class="datepicker-dropdown-options year-options" id="yearOptions_{{ $id }}" onscroll="window.handleYearScroll_{{ $id }}(this)">';
            const startYear = 1990;
            const endYear = today.getFullYear() + 50;
            
            // Render initial 20 years centered around current year for better scroll experience
            const currentYearIndex = year - startYear;
            let startIndex = Math.max(0, currentYearIndex - 10);
            let endIndex = Math.min(startIndex + 20, endYear - startYear + 1);
            
            // Store range for infinite scroll
            window.yearRange_{{ $id }} = {
                startIndex: startIndex,
                endIndex: endIndex,
                startYear: startYear,
                endYear: endYear,
                totalYears: endYear - startYear + 1,
                currentYear: year
            };
            
            // Render years
            for (let i = startIndex; i < endIndex; i++) {
                const y = startYear + i;
                html += '<div class="datepicker-dropdown-option' + (y === year ? ' selected' : '') + '" data-year-index="' + i + '" onclick="window.selectDatepickerYear_{{ $id }}(' + y + ')">' + y + '</div>';
            }
            
            html += '</div>';
            html += '</div>';
            
            html += '</div>';
            
            @if (is_rtl())
                html +=
                    '<button type="button" class="datepicker-nav" onclick="window.calendarPrev_{{ $id }}()">›</button>';
            @else
                html +=
                    '<button type="button" class="datepicker-nav" onclick="window.calendarNext_{{ $id }}()">›</button>';
            @endif
            html += '</div><div class="datepicker-grid">';

            weekdays.forEach(day => {
                html += '<div class="datepicker-weekday">' + day + '</div>';
            });

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                html += '<div class="datepicker-day"></div>';
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                let classes = 'datepicker-day';

                if (minDate && date < minDate) classes += ' disabled';
                if (maxDate && date > maxDate) classes += ' disabled';
                if (selectedDate && date.toDateString() === selectedDate.toDateString()) classes += ' selected';
                if (date.toDateString() === today.toDateString()) classes += ' today';

                html += '<div class="' + classes + '" onclick="window.calendarSelect_{{ $id }}(' +
                    year + ',' + month + ',' + day + ')">' + day + '</div>';
            }

            html += '</div>';
            content.innerHTML = html;
        }

        window.calendarPrev_{{ $id }} = function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        };

        window.calendarNext_{{ $id }} = function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        };

        // Custom Dropdown Functions
        window.toggleDatepickerDropdown_{{ $id }} = function(type) {
            const monthTrigger = content.querySelector('.month-trigger');
            const yearTrigger = content.querySelector('.year-trigger');
            const monthOptions = content.querySelector('.month-options');
            const yearOptions = content.querySelector('.year-options');
            
            // Close all dropdowns first
            if (monthOptions) monthOptions.classList.remove('show');
            if (yearOptions) yearOptions.classList.remove('show');
            if (monthTrigger) monthTrigger.classList.remove('active');
            if (yearTrigger) yearTrigger.classList.remove('active');
            
            // Open clicked dropdown
            if (type === 'month') {
                if (monthOptions && monthTrigger) {
                    monthOptions.classList.toggle('show');
                    monthTrigger.classList.toggle('active');
                    // Scroll to current month when opening (only dropdown scroll, not page)
                    if (monthOptions.classList.contains('show')) {
                        setTimeout(() => {
                            const currentMonth = currentDate.getMonth();
                            const selectedOption = monthOptions.querySelector('.selected');
                            if (selectedOption) {
                                const optionTop = selectedOption.offsetTop;
                                const optionHeight = selectedOption.offsetHeight;
                                const containerHeight = monthOptions.clientHeight;
                                monthOptions.scrollTop = optionTop - (containerHeight / 2) + (optionHeight / 2);
                            } else {
                                // If selected not found, find current month option
                                const monthOptionsList = monthOptions.querySelectorAll('.datepicker-dropdown-option');
                                if (monthOptionsList[currentMonth]) {
                                    const option = monthOptionsList[currentMonth];
                                    const optionTop = option.offsetTop;
                                    const optionHeight = option.offsetHeight;
                                    const containerHeight = monthOptions.clientHeight;
                                    monthOptions.scrollTop = optionTop - (containerHeight / 2) + (optionHeight / 2);
                                }
                            }
                        }, 50);
                    }
                }
            } else if (type === 'year') {
                if (yearOptions && yearTrigger) {
                    yearOptions.classList.toggle('show');
                    yearTrigger.classList.toggle('active');
                    // Scroll to current year when opening (only dropdown scroll, not page)
                    if (yearOptions.classList.contains('show')) {
                        setTimeout(() => {
                            const currentYear = currentDate.getFullYear();
                            const selectedOption = yearOptions.querySelector('.selected');
                            if (selectedOption) {
                                const optionTop = selectedOption.offsetTop;
                                const optionHeight = selectedOption.offsetHeight;
                                const containerHeight = yearOptions.clientHeight;
                                yearOptions.scrollTop = optionTop - (containerHeight / 2) + (optionHeight / 2);
                            } else {
                                // If selected not found, find current year option
                                if (window.yearRange_{{ $id }}) {
                                    const yearIndex = currentYear - window.yearRange_{{ $id }}.startYear;
                                    const currentYearOption = yearOptions.querySelector('[data-year-index="' + yearIndex + '"]');
                                    if (currentYearOption) {
                                        const optionTop = currentYearOption.offsetTop;
                                        const optionHeight = currentYearOption.offsetHeight;
                                        const containerHeight = yearOptions.clientHeight;
                                        yearOptions.scrollTop = optionTop - (containerHeight / 2) + (optionHeight / 2);
                                    }
                                }
                            }
                        }, 50);
                    }
                }
            }
        };

        window.selectDatepickerMonth_{{ $id }} = function(month) {
            currentDate.setMonth(parseInt(month));
            // Update selected option
            const options = content.querySelectorAll('.month-options .datepicker-dropdown-option');
            options.forEach((opt, i) => {
                opt.classList.toggle('selected', i === month);
            });
            // Close dropdown
            const monthTrigger = content.querySelector('.month-trigger');
            const monthOptions = content.querySelector('.month-options');
            if (monthOptions) monthOptions.classList.remove('show');
            if (monthTrigger) monthTrigger.classList.remove('active');
            renderCalendar();
        };

        window.selectDatepickerYear_{{ $id }} = function(year) {
            currentDate.setFullYear(parseInt(year));
            // Close dropdown
            const yearTrigger = content.querySelector('.year-trigger');
            const yearOptions = content.querySelector('.year-options');
            if (yearOptions) yearOptions.classList.remove('show');
            if (yearTrigger) yearTrigger.classList.remove('active');
            renderCalendar();
        };

        // Infinite scroll handler for year dropdown
        window.handleYearScroll_{{ $id }} = function(element) {
            if (!window.yearRange_{{ $id }}) return;
            
            const range = window.yearRange_{{ $id }};
            const scrollTop = element.scrollTop;
            const scrollHeight = element.scrollHeight;
            const clientHeight = element.clientHeight;
            const threshold = 50; // pixels from edge to trigger load
            
            // Prevent multiple simultaneous loads
            if (element.dataset.loading === 'true') return;
            
            // Load more years when scrolling near top
            if (scrollTop < threshold && range.startIndex > 0) {
                element.dataset.loading = 'true';
                const loadCount = 20;
                const newStartIndex = Math.max(0, range.startIndex - loadCount);
                
                // Get existing options
                const existingOptions = Array.from(element.querySelectorAll('.datepicker-dropdown-option'));
                const firstOption = existingOptions[0];
                const currentScrollTop = element.scrollTop;
                const currentYear = currentDate.getFullYear();
                
                // Prepend new years
                let html = '';
                for (let i = newStartIndex; i < range.startIndex; i++) {
                    const y = range.startYear + i;
                    html += '<div class="datepicker-dropdown-option' + (y === currentYear ? ' selected' : '') + '" data-year-index="' + i + '" onclick="window.selectDatepickerYear_{{ $id }}(' + y + ')">' + y + '</div>';
                }
                
                // Insert before first option
                if (firstOption) {
                    firstOption.insertAdjacentHTML('beforebegin', html);
                } else {
                    element.innerHTML = html + element.innerHTML;
                }
                
                // Update range
                range.startIndex = newStartIndex;
                
                // Restore scroll position (adjust for new content)
                const optionHeight = 38; // approximate height per option
                const newScrollTop = currentScrollTop + (range.startIndex - newStartIndex) * optionHeight;
                element.scrollTop = newScrollTop;
                
                element.dataset.loading = 'false';
            }
            
            // Load more years when scrolling near bottom
            if (scrollTop + clientHeight > scrollHeight - threshold && range.endIndex < range.totalYears) {
                element.dataset.loading = 'true';
                const loadCount = 20;
                const newEndIndex = Math.min(range.endIndex + loadCount, range.totalYears);
                const currentYear = currentDate.getFullYear();
                
                // Append new years
                let html = '';
                for (let i = range.endIndex; i < newEndIndex; i++) {
                    const y = range.startYear + i;
                    html += '<div class="datepicker-dropdown-option' + (y === currentYear ? ' selected' : '') + '" data-year-index="' + i + '" onclick="window.selectDatepickerYear_{{ $id }}(' + y + ')">' + y + '</div>';
                }
                
                element.insertAdjacentHTML('beforeend', html);
                
                // Update range
                range.endIndex = newEndIndex;
                
                element.dataset.loading = 'false';
            }
        };

        window.calendarChangeMonth_{{ $id }} = function(month) {
            currentDate.setMonth(parseInt(month));
            renderCalendar();
        };

        window.calendarChangeYear_{{ $id }} = function(year) {
            currentDate.setFullYear(parseInt(year));
            renderCalendar();
        };

        window.calendarSelect_{{ $id }} = function(year, month, day) {
            const date = new Date(year, month, day);
            if (minDate && date < minDate) return;
            if (maxDate && date > maxDate) return;

            selectedDate = date;
            const formattedDate = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day)
                .padStart(2, '0');
            input.value = formattedDate;
            calendar.style.display = 'none';
            renderCalendar();

            const changeEvent = new Event('change', {
                bubbles: true
            });
            input.dispatchEvent(changeEvent);
        };

        function showCalendar() {
            if ('{{ $id }}' === 'project_due_date' && window.dueDateMinDate) {
                minDate = window.dueDateMinDate;
                if (window.dueDateStartMonth) {
                    currentDate = new Date(window.dueDateStartMonth);
                }
            }

            calendar.style.display = 'block';
            renderCalendar();

            // Determine position based on available space
            requestAnimationFrame(() => {
                const inputRect = input.getBoundingClientRect();
                const calendarHeight = 450; // Approximate calendar height
                const spaceAbove = inputRect.top;
                const spaceBelow = window.innerHeight - inputRect.bottom;

                // Remove existing position classes
                calendar.classList.remove('position-top', 'position-bottom');

                // If in modal or not enough space below, check space above
                if (spaceBelow < calendarHeight && spaceAbove > calendarHeight) {
                    calendar.classList.add('position-top');
                } else {
                    calendar.classList.add('position-bottom');
                }

                content.style.transform = 'translateY(0) scale(1)';
                content.style.opacity = '1';
            });
        }

        function hideCalendar() {
            calendar.style.display = 'none';
        }

        // Event listeners
        input.addEventListener('click', function(e) {
            e.preventDefault();
            showCalendar();
        });

        // Click on icon
        const icon = input.parentElement.querySelector('.modern-datepicker-icon');
        if (icon) {
            icon.addEventListener('click', function(e) {
                e.preventDefault();
                showCalendar();
            });
        }

        // Close on backdrop click
        calendar.addEventListener('click', function(e) {
            if (e.target.classList.contains('datepicker-backdrop')) {
                hideCalendar();
            }
        });

        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !calendar.contains(e.target) && !icon?.contains(e
                .target)) {
                hideCalendar();
            }
        });

        // Prevent calendar from closing when clicking inside
        content.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!content.contains(e.target)) {
                const monthOptions = content.querySelector('.month-options');
                const yearOptions = content.querySelector('.year-options');
                const monthTrigger = content.querySelector('.month-trigger');
                const yearTrigger = content.querySelector('.year-trigger');
                
                if (monthOptions && !monthOptions.contains(e.target) && !monthTrigger?.contains(e.target)) {
                    monthOptions.classList.remove('show');
                    monthTrigger?.classList.remove('active');
                }
                if (yearOptions && !yearOptions.contains(e.target) && !yearTrigger?.contains(e.target)) {
                    yearOptions.classList.remove('show');
                    yearTrigger?.classList.remove('active');
                }
            }
        });

        // Initialize with existing value
        if (input.value) {
            selectedDate = new Date(input.value);
            currentDate = new Date(selectedDate);
        }
    });
</script>
