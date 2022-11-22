@extends('layouts.app')

@section('title','Home Page')

@section('content')
    {{-- <h1>{{ __('messages.welcome') }}</h1>
    <h1>@lang('messages.welcome')</h1> --}}
    <p> {{ __('Welcome to Laravel!') }}</p>
    {{-- <p>{{ __('messages.example_with_value',['name' => 'John']) }}</p> --}}

    
    {{-- <p> Using JSON: {{ __('Hello :name',['name' => 'Nathik']) }}</p> --}}
   
    <p>{{ __('This is the content of the main page!') }}</p>

    {{-- <p>{{ trans_choice('messages.plural',0, ['a' =>1]) }}</p>
    <p>{{ trans_choice('messages.plural',1, ['a' =>1]) }}</p>
    <p>{{ trans_choice('messages.plural',2, ['a' =>1]) }}</p> --}}


    {{-- <div>
        @for ($i=0;$i<10;$i++)
            <div>The current value is {{ $i }}</div>    
        @endfor
    </div>

    <div>
        @php $done = false @endphp
        @while (!$done)
            <div>I am not done</div>
            @php if(random_int(0,1) === 1) $done = true
            @endphp
        @endwhile
    </div> --}}
@endsection