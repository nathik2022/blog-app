@extends('layouts.app')

@section('title','Contact Page')

@section('content')
    <h1>{{ __('Contact Page') }}</h1>
    <p>{{ __('This is the content of the contact page!') }}</p>

    @can('home.secret')
        <p>
            <a href="{{ route('home.secret') }}">
                {{ __('Go to special contact details!') }}
            </a>
        </p>
    @endcan
@endsection