@if (!isset($show) || $show)
    <span class="badge  badge-{{ $type ??  __('success') }} badge-lg">
        {{ $slot }}
    </span>    
@endif
