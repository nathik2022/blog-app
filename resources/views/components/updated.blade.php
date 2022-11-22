{{-- <p class="text-muted">
    {{ empty(trim(string) $slot) ? 'Added ' : $slot }} {{ (is_string($date) ? now()::parse($date) : $date)->diffForHumans() }}
    @if (isset($name))
        by {{ $name }}
    @endif
</p> --}}
<p class="text-muted">
    {{ empty(trim($slot)) ? 'Added' : $slot }} {{ $date->diffForHumans() }}
    @if(isset($name))
        @if(isset($userId))
            {{ __('by') }} <a href="{{ route('users.show',['user'=>$userId]) }}">{{ $name }}</a>
        @else
        {{ __('by') }} {{ $name }}
        @endif    
    @endif
</p>
