{{-- RTL Helper Component --}}
@php
    $isRtl = is_rtl();
    $direction = $isRtl ? 'rtl' : 'ltr';
    $textAlign = $isRtl ? 'text-end' : 'text-start';
    $floatClass = $isRtl ? 'float-end' : 'float-start';
    $marginStart = $isRtl ? 'ms-' : 'me-';
    $marginEnd = $isRtl ? 'me-' : 'ms-';
@endphp

<script>
    // Global RTL helper functions
    window.isRTL = {{ $isRtl ? 'true' : 'false' }};
    window.getDirection = () => '{{ $direction }}';
    window.getTextAlign = () => '{{ $textAlign }}';
    window.getFloatClass = () => '{{ $floatClass }}';
    window.getMarginStart = (size) => '{{ $marginStart }}' + size;
    window.getMarginEnd = (size) => '{{ $marginEnd }}' + size;
</script>