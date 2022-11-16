@extends('layouts.app')

@section('title','Registration Page')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name" >Name</label>
            <input name="name" id="name" value="{{ old('name') }}" 
                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif    
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input name="email" id="email" value="{{  old('email') }}" 
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input name="password" id="password"  type="password" 
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="password_confirmation">Retyped Password</label>
            <input name="password_confirmation" id="password_confirmaton" type="password"  required 
                class="form-control" >
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register!</button>
    </form>
@endsection