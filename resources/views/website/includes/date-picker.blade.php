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
        <input 
            type="text" 
            id="{{ $id }}" 
            name="{{ $name }}" 
            value="{{ $value }}" 
            placeholder="{{ $placeholder }}"
            class="modern-datepicker-input" 
            {{ $required ? 'required' : '' }}
            {{ is_rtl() ? 'dir=rtl' : '' }}
            readonly
        >
        <div class="modern-datepicker-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15.6947 13.7002H15.7037M15.6947 16.7002H15.7037M11.9955 13.7002H12.0045M11.9955 16.7002H12.0045M8.29431 13.7002H8.30329M8.29431 16.7002H8.30329" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
    {{ is_rtl() ? 'left: 18px;' : 'right: 18px;' }}
    top: 50%;
    transform: translateY(-50%);
    color: #718096;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 2;
}

.modern-datepicker-input:focus + .modern-datepicker-icon {
    color: #F58D2E;
}

.modern-datepicker-calendar {
    position: absolute;
    top: 100%;
    {{ is_rtl() ? 'right: 0;' : 'left: 0;' }}
    z-index: 1000;
    margin-top: 8px;
    width: 100%;
    min-width: 350px;
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
    padding: 24px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f7fafc;
}

.datepicker-nav {
    width: 44px;
    height: 44px;
    border: none;
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-radius: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #4a5568;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.datepicker-nav:hover {
    background: linear-gradient(135deg, #F58D2E, #ff7b00);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 141, 46, 0.25);
}

.datepicker-month {
    font-weight: 700;
    font-size: 18px;
    color: #2d3748;
    background: linear-gradient(135deg, #F58D2E, #ff7b00);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.datepicker-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
}

.datepicker-weekday {
    padding: 12px 8px;
    text-align: center;
    font-weight: 600;
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 1px;
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
        min-width: 320px;
    }
    
    .datepicker-content {
        padding: 20px;
    }
    
    .datepicker-day {
        width: 40px;
        height: 40px;
        font-size: 13px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('{{ $id }}');
    const calendar = document.getElementById('{{ $id }}_calendar');
    const content = calendar?.querySelector('.datepicker-content');
    
    if (!input || !calendar || !content) return;
    
    let minDate = @if($minDate) new Date('{{ $minDate }}') @else null @endif;
    
    // Check for dynamic minimum date (for due date picker)
    if ('{{ $id }}' === 'project_due_date' && window.dueDateMinDate) {
        minDate = window.dueDateMinDate;
    }
    const maxDate = @if($maxDate) new Date('{{ $maxDate }}') @else null @endif;
    
    let currentDate = new Date();
    let selectedDate = null;
    const today = new Date();
    
    const months = @if(is_rtl()) ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'] @else ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] @endif;
    
    const weekdays = @if(is_rtl()) ['أح', 'إث', 'ثل', 'أر', 'خم', 'جم', 'سب'] @else ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] @endif;
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        let html = '<div class="datepicker-header">';
        @if(is_rtl())
        html += '<button type="button" class="datepicker-nav" onclick="window.calendarNext_{{ $id }}()">‹</button>';
        html += '<div class="datepicker-month">' + months[month] + ' ' + year + '</div>';
        html += '<button type="button" class="datepicker-nav" onclick="window.calendarPrev_{{ $id }}()">›</button>';
        @else
        html += '<button type="button" class="datepicker-nav" onclick="window.calendarPrev_{{ $id }}()">‹</button>';
        html += '<div class="datepicker-month">' + months[month] + ' ' + year + '</div>';
        html += '<button type="button" class="datepicker-nav" onclick="window.calendarNext_{{ $id }}()">›</button>';
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
            
            html += '<div class="' + classes + '" onclick="window.calendarSelect_{{ $id }}(' + year + ',' + month + ',' + day + ')">' + day + '</div>';
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
    
    window.calendarSelect_{{ $id }} = function(year, month, day) {
        const date = new Date(year, month, day);
        if (minDate && date < minDate) return;
        if (maxDate && date > maxDate) return;
        
        selectedDate = date;
        const formattedDate = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
        input.value = formattedDate;
        calendar.style.display = 'none';
        renderCalendar();
        
        const changeEvent = new Event('change', { bubbles: true });
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
        
        // Add entrance animation
        requestAnimationFrame(() => {
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
        if (!input.contains(e.target) && !calendar.contains(e.target) && !icon?.contains(e.target)) {
            hideCalendar();
        }
    });
    
    // Prevent calendar from closing when clicking inside
    content.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Initialize with existing value
    if (input.value) {
        selectedDate = new Date(input.value);
        currentDate = new Date(selectedDate);
    }
});
</script>