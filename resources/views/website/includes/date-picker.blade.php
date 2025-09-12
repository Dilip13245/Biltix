{{-- Vanilla Calendar Date Picker --}}
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

<div class="vanilla-calendar-wrapper position-relative">
    <input 
        type="text" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}"
        class="{{ $class }} vanilla-calendar-input" 
        {{ $required ? 'required' : '' }}
        {{ is_rtl() ? 'dir=rtl' : '' }}
        readonly
    >
    <i class="fas fa-calendar-alt position-absolute {{ is_rtl() ? 'start-0 ms-3' : 'end-0 me-3' }} top-50 translate-middle-y text-muted"></i>
    <div id="{{ $id }}_calendar" class="vanilla-calendar" style="display: none;"></div>
</div>

<style>
.vanilla-calendar {
    position: absolute;
    top: 100%;
    {{ is_rtl() ? 'right: 0;' : 'left: 0;' }}
    z-index: 1000;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    padding: 15px;
    min-width: 280px;
    font-family: 'Poppins', sans-serif;
}
.vanilla-calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.vanilla-calendar-nav {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 4px;
    color: #F58D2E;
}
.vanilla-calendar-nav:hover {
    background: #f8f9fa;
}
.vanilla-calendar-month {
    font-weight: 600;
    color: #333;
}
.vanilla-calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}
.vanilla-calendar-day {
    padding: 8px;
    text-align: center;
    cursor: pointer;
    border-radius: 4px;
    font-size: 14px;
}
.vanilla-calendar-day:hover {
    background: #f8f9fa;
}
.vanilla-calendar-day.selected {
    background: #F58D2E;
    color: white;
}
.vanilla-calendar-day.disabled {
    color: #ccc;
    cursor: not-allowed;
}
.vanilla-calendar-weekday {
    font-weight: 600;
    color: #666;
    font-size: 12px;
    padding: 5px;
    text-align: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('{{ $id }}');
    const calendar = document.getElementById('{{ $id }}_calendar');
    
    if (!input || !calendar) return;
    
    const minDate = @if($minDate) new Date('{{ $minDate }}') @else null @endif;
    const maxDate = @if($maxDate) new Date('{{ $maxDate }}') @else null @endif;
    
    let currentDate = new Date();
    let selectedDate = null;
    
    const months = @if(is_rtl()) ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'] @else ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] @endif;
    
    const weekdays = @if(is_rtl()) ['ح', 'ن', 'ث', 'ر', 'خ', 'ج', 'س'] @else ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] @endif;
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        let html = '<div class="vanilla-calendar-header">';
        html += '<button type="button" class="vanilla-calendar-nav" onclick="window.calendarPrev_{{ $id }}()">‹</button>';
        html += '<div class="vanilla-calendar-month">' + months[month] + ' ' + year + '</div>';
        html += '<button type="button" class="vanilla-calendar-nav" onclick="window.calendarNext_{{ $id }}()">›</button>';
        html += '</div><div class="vanilla-calendar-grid">';
        
        weekdays.forEach(day => {
            html += '<div class="vanilla-calendar-weekday">' + day + '</div>';
        });
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="vanilla-calendar-day"></div>';
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            let classes = 'vanilla-calendar-day';
            
            if (minDate && date < minDate) classes += ' disabled';
            if (maxDate && date > maxDate) classes += ' disabled';
            if (selectedDate && date.toDateString() === selectedDate.toDateString()) classes += ' selected';
            
            html += '<div class="' + classes + '" onclick="window.calendarSelect_{{ $id }}(' + year + ',' + month + ',' + day + ')">' + day + '</div>';
        }
        
        html += '</div>';
        calendar.innerHTML = html;
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
        // Format date manually to avoid timezone issues
        const formattedDate = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
        input.value = formattedDate;
        calendar.style.display = 'none';
        renderCalendar();
        
        // Trigger change event
        const changeEvent = new Event('change', { bubbles: true });
        input.dispatchEvent(changeEvent);
    };
    
    input.addEventListener('click', function(e) {
        e.preventDefault();
        calendar.style.display = calendar.style.display === 'none' ? 'block' : 'none';
        if (calendar.style.display === 'block') renderCalendar();
    });
    
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !calendar.contains(e.target)) {
            calendar.style.display = 'none';
        }
    });
    
    calendar.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    if (input.value) {
        selectedDate = new Date(input.value);
        currentDate = new Date(selectedDate);
    }
});
</script>