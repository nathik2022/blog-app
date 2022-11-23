<div class="mb-2 mt-2">
    @auth
        <form method="POST" action="{{ $route }}" >
            @csrf
            {{-- @method('PUT') --}}
            <div class="form-group">
                <textarea id="content" name="content" class="form-control"></textarea>
            </div>
            
            <div><input type="submit" value="{{ __('Add Comment') }}" class=" btn btn-primary btn-block"></div>
        </form>

        @errors @enderrors
    @else
        <a href="{{ route('login') }}">{{ __('Sign-in') }}</a> {{ __('to post comments!') }}

    @endauth
</div>
<hr/>