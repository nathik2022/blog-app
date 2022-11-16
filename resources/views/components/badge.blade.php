@if (!isset($show) || $show)
    <span class="badge  badge-{{ $type ?? 'success' }} badge-lg">
        {{ $slot }}
    </span>    
@endif
