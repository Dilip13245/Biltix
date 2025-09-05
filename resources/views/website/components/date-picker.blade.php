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
.flatpickr-calendar {
    font-family: 'Poppins', sans-serif;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border: none;
}
.flatpickr-day.selected {
    background: #F58D2E;
    border-color: #F58D2E;
}
.flatpickr-day:hover {
    background: #F58D2E;
    border-color: #F58D2E;
}
.flatpickr-months .flatpickr-month {
    background: #F58D2E;
    color: white;
}
.flatpickr-current-month .flatpickr-monthDropdown-months {
    background: #F58D2E;
}
.flatpickr-weekdays {
    background: #f8f9fa;
}
.date-picker-wrapper .fa-calendar-alt {
    z-index: 1;
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
        onReady: function(selectedDates, dateStr, instance) {
            instance.calendarContainer.classList.add('custom-flatpickr');
        }
    };
    
    flatpickr("#{{ $id }}", config);
});
</script>
@endpush