{{-- Date Picker Component --}}
@props([
    'id' => 'datePicker_' . uniqid(),
    'name' => 'date',
    'value' => '',
    'placeholder' => __('messages.select_date'),
    'required' => false,
    'class' => 'form-control Input_control',
    'minDate' => null,
    'maxDate' => null,
    'mode' => 'single',
    'enableTime' => false,
    'dateFormat' => 'Y-m-d',
    'altFormat' => 'F j, Y',
    'showAltInput' => true
])

<div class="date-picker-wrapper position-relative">
    <input 
        type="text" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}"
        class="{{ $class }} flatpickr-input" 
        {{ $required ? 'required' : '' }}
        {{ is_rtl() ? 'dir=rtl' : '' }}
        readonly
        {{ $attributes }}
    >
    <i class="fas fa-calendar-alt position-absolute {{ is_rtl() ? 'start-0 ms-3' : 'end-0 me-3' }} top-50 translate-middle-y text-muted pointer-events-none"></i>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
/* Professional Calendar Design */
.flatpickr-calendar {
    font-family: 'Poppins', sans-serif !important;
    border-radius: 16px !important;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12) !important;
    border: 1px solid #e5e7eb !important;
    background: white !important;
    width: 320px !important;
    padding: 0 !important;
    overflow: hidden;
}

/* Header styling */
.flatpickr-months {
    background: linear-gradient(135deg, #F58D2E 0%, #e67e22 100%) !important;
    padding: 16px 20px !important;
    border-radius: 16px 16px 0 0 !important;
}

.flatpickr-month {
    background: transparent !important;
    color: white !important;
    height: auto !important;
}

.flatpickr-current-month {
    font-size: 16px !important;
    font-weight: 600 !important;
    color: white !important;
}

.flatpickr-monthDropdown-months,
.numInputWrapper {
    background: rgba(255,255,255,0.2) !important;
    border: 1px solid rgba(255,255,255,0.3) !important;
    border-radius: 6px !important;
    color: white !important;
    font-weight: 600 !important;
    margin: 0 4px !important;
}

.flatpickr-monthDropdown-months:hover,
.numInputWrapper:hover {
    background: rgba(255,255,255,0.3) !important;
}

.flatpickr-monthDropdown-months option {
    background: #F58D2E !important;
    color: white !important;
}

.numInput {
    color: white !important;
    background: transparent !important;
    border: none !important;
    font-weight: 600 !important;
}

.flatpickr-prev-month,
.flatpickr-next-month {
    color: white !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease !important;
}

.flatpickr-prev-month:hover,
.flatpickr-next-month:hover {
    background: rgba(255,255,255,0.2) !important;
}

/* Weekdays header */
.flatpickr-weekdays {
    background: #f8fafc !important;
    padding: 12px 0 !important;
    border-bottom: 1px solid #e5e7eb !important;
}

.flatpickr-weekday {
    width: calc(100% / 7) !important;
    height: 32px !important;
    line-height: 32px !important;
    text-align: center !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #6b7280 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 0 !important;
    margin: 0 !important;
    box-sizing: border-box !important;
}

/* Days container */
.flatpickr-days {
    width: 100% !important;
    padding: 12px !important;
    background: white !important;
    box-sizing: border-box !important;
}

.flatpickr-day {
    width: calc((100% - 24px) / 7) !important;
    height: 40px !important;
    line-height: 40px !important;
    margin: 0 !important;
    border-radius: 8px !important;
    text-align: center !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #374151 !important;
    border: none !important;
    background: transparent !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    box-sizing: border-box !important;
}

.flatpickr-day:hover {
    background: #fef3e2 !important;
    color: #F58D2E !important;
    transform: scale(1.05) !important;
}

.flatpickr-day.selected {
    background: #F58D2E !important;
    color: white !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 8px rgba(245, 141, 46, 0.3) !important;
}

.flatpickr-day.today {
    background: #e0f2fe !important;
    color: #0369a1 !important;
    font-weight: 600 !important;
}

.flatpickr-day.today.selected {
    background: #F58D2E !important;
    color: white !important;
}

.flatpickr-day.prevMonthDay,
.flatpickr-day.nextMonthDay {
    color: #d1d5db !important;
}

.flatpickr-day.disabled {
    color: #d1d5db !important;
    cursor: not-allowed !important;
}

.date-picker-wrapper .fa-calendar-alt {
    z-index: 1;
    color: #F58D2E;
}

/* Mobile responsive */
@media (max-width: 480px) {
    .flatpickr-calendar {
        width: 300px !important;
        max-width: 95vw !important;
    }
    
    .flatpickr-day {
        width: calc((100% - 24px) / 7) !important;
        height: 36px !important;
        line-height: 36px !important;
        font-size: 13px !important;
    }
    
    .flatpickr-weekday {
        height: 28px !important;
        line-height: 28px !important;
        font-size: 11px !important;
    }
}

@media (max-width: 360px) {
    .flatpickr-calendar {
        width: 280px !important;
    }
    
    .flatpickr-day {
        width: calc((100% - 24px) / 7) !important;
        height: 32px !important;
        line-height: 32px !important;
        font-size: 12px !important;
    }
}
@if(is_rtl())
.flatpickr-calendar {
    direction: rtl;
}
.flatpickr-calendar.rtl .flatpickr-months {
    flex-direction: row-reverse;
}
@endif
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@if(is_rtl())
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    const config = {
        dateFormat: "{{ $dateFormat }}",
        altInput: {{ $showAltInput ? 'true' : 'false' }},
        altFormat: "{{ $altFormat }}",
        mode: "{{ $mode }}",
        enableTime: {{ $enableTime ? 'true' : 'false' }},
        @if($minDate)
        minDate: "{{ $minDate }}",
        @endif
        @if($maxDate)
        maxDate: "{{ $maxDate }}",
        @endif
        @if(is_rtl())
        locale: "ar",
        @endif
        theme: "light",
        allowInput: false,
        clickOpens: true,
        changeMonth: true,
        changeYear: true,
        onReady: function(selectedDates, dateStr, instance) {
            instance.calendarContainer.classList.add('custom-flatpickr');
        }
    };
    
    flatpickr("#{{ $id }}", config);
});
</script>
@endpush