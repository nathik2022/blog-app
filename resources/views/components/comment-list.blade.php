@forelse ($comments as $comment)
    <p>
        {{ $comment->content }}
    </p>
    <p class="text-muted">
        <x-tags :tags="$comment->tags"></x-tags>
        {{-- added {{  $comment->created_at->diffForHumans() }} --}}
        <x-updated :date="$comment->created_at" name="{{ $comment->user->name }}">@slot('userId',$comment->user->id)</x-updated>
    </p>
@empty
    <p>No comments yet!</p>
@endforelse