<div class="mb-2 mt-2">
    @auth
        <form method="POST" action="{{ route('posts.comments.store',['post' => $post->id]) }}">
            @csrf

            <div class="form-group">
                <textarea id="content" name="content" class="form-control"></textarea>
            </div>
            
            <div><input type="submit" value="Add Comment" class=" btn btn-primary btn-block"></div>
        </form>

        @errors @enderrors
    @else
        <a href="{{ route('login') }}">{{ __('Sign-in') }}</a> {{ __('to post comments!') }}

    @endauth
</div>
<hr/>