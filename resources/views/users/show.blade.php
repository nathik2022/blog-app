@extends('layouts.app')

@section('title',$user->name)
@section('content')
        <div class="row">
            <div class="col-4">
                <img src="{{ $user->image ? $user->image->url() : '' }}" class="img-thumbnail avatar" />
            </div>
            <div class="col-8">
                <h3>{{ $user->name }}</h3>

                <p>Currently viewed by {{ $counter }} other users</p>
            </div>
        </div>
        {{-- @commentForm(['route'=>route('users.comments.store',['user'=>$user->id])])'users.comments.store',['user'=>$user->id] 
        @endcommentForm --}}
        <x-commentForm :route="route('users.comments.store',['user'=>$user->id])"></x-commentForm>

        {{-- @commentList(['comments'=>$user->commentsOn]) 
        @endcommentList --}}
        <x-commentList :comments="$user->commentsOn"></x-commentList>
@endsection