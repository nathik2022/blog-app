{{-- @break($key == 2) --}}
{{-- @continue($key == 1) --}}
{{-- @if ($loop->even) --}}
<h3>
    @if($post->trashed())
        <del>
    @endif
    <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show',['post'=> $post->id]) }}">{{ $post->title }}</a>
    @if($post->trashed())
        <del>
    @endif
</h3>   
{{-- @else
<div style="background-color: silver;">{{ $key }}.{{ $post->title }}</div>
@endif  --}}
{{-- <p class="text-muted">
    Added {{ $post->created_at->diffForHumans() }}userId="{{ $post->user->userId }}"
    by {{ $post->user->name }}
</p> --}}
<x-updated :date="$post->created_at" name="{{$post->user->name}}">
    @slot('userId',$post->user->id)
</x-updated>

<x-tags :tags="$post->tags"></x-tags>

@if ($post->comments_count)

    <p>{{ $post->comments_count }} comments</p>
@else    
    <p>No comments yet!</p>    
@endif
<div class="mb-3">
    @auth
        @can('update',$post)    
            <a href="{{ route('posts.edit',['post'=> $post->id]) }}" 
                class="btn btn-primary">
                    Edit
            </a>
        @endcan
    @endauth
    {{-- @cannot('delete',$post)
        <p>You can't delete this post.</p>   
    @endcannot --}}
    @auth
        @if(!$post->trashed())   
            @can('delete',$post)
                <form class="d-inline" 
                    action="{{ route('posts.destroy',['post'=> $post->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="Delete!" class="btn btn-primary">
                </form>
            @endcan
        @else
            @can('delete',$post)
            <form class="d-inline" 
                action="{{ route('posts.restore',['post' => $post->id]) }}" method="POST">
                @method('POST')
                @csrf
                <input type="submit" class="btn btn-primary" value="Restore">
            </form>
            @endcan
        @endif          
    @endauth   
</div> 