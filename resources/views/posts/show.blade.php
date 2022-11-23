@extends('layouts.app')

@section('title',$post->title)

{{-- @if ($post['is_new'])
    <div>A new blog post! using if</div>
@else
    <div>Blog post is old! using elseif/else</div>    
@endif


@section('content')

@unless ($post['is_new'])
    <div>It is an old post... using unless</div>   
@endunless --}}
@section('content')
<div class="row">
    <div class="col-8">
        @if($post->image)
        <div style="background-image:url('{{ $post->image->url() }}');min-height: 500px;color:white;text-align:center;background-attachment:fixed;background-size:contain;">
            <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
        @else
            <h1>
        @endif
            {{ $post->title }}</h1>
        @if($post->image)
            </h1>
        </div>
        @else
            </h1>     
        @endif   
        <p>{{ $post->content }}</p>
        {{-- <img src="/storage/{{ $post->image->path }}" /> --}}
        {{-- <div class="mt-2 mb-2" >
            <img src="{{ Storage::url($post->image->path) }}" />
        </div> --}}
        {{-- <div class="mt-2 mb-2" >
            <img src="{{ $post->image->url() }}" />
        </div> --}}
        {{-- <p>Added {{ $post->created_at->diffForHumans() }}</p> --}}
        {{-- 
        @isset($post['has_comments'])
            <div>The post has some comment using... isset</div>   
        @endisset --}}
        {{-- @if (now()->diffInMinutes($post->created_at) < 160) --}}
            {{-- @component('components.badge' , ['type' => 'primary'])
                Brand new post!
            @endcomponent --}}

            {{-- @badge(['type' => 'primary'])
                Alias Badge New post!
            @endbadge
            @badge( ['type' => 'danger']) 
                Super New POST 
            @endbadge
            @badge()
            hi
            @endbadge() --}}
        {{-- <x-badge type="success" :show="now()->diffInMinutes($post->created_at) < 30"> --}}
        {{-- <x-badge type='primary' show="{{ now()->diffInMinutes($post->created_at)<160 }}">    
            Brand New Post!
        </x-badge> --}}
        <x-badge show="{{ now()->diffInMinutes($post->created_at) < 30 }}" type="primary">
            {{ __('New Blog Post!') }}
        </x-badge>
        <x-updated :date="$post->created_at" name="{{ $post->user->name }}">
        </x-updated>
        <x-updated :date="$post->updated_at">
            {{ __('Updated') }}
        </x-updated>

        <x-tags :tags="$post->tags"></x-tags>

        <p>
            {{-- Currently read by {{ $counter }} people --}}
            {{ trans_choice('messages.people.reading', $counter) }}
        </p>
        {{-- @endif --}}
        <h4>{{ __('Comments') }}s</h4>

        {{-- @include('comments.partials.form') --}}
        {{-- @commentForm(['route'=>route('posts.comments.store',['post'=>$post->id])]) 
        @endcommentForm --}}
        <x-commentForm :route="route('posts.comments.store',['post'=>$post->id])"></x-commentForm>

        {{-- @commentList(['comments'=>$post->comments]) 
        @endcommentList --}}
        <x-commentList :comments="$post->comments"></x-commentList>
    </div>
    <div class="col-4">
        @include('posts.partials.activity')
    </div>    
@endsection('content')