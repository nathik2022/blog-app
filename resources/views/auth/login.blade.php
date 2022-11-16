@extends('layouts.app')

@section('title','Login Page')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

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
            <input name="password" id="password" type="password" 
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                 {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>
        </div>        
        <button type="submit" class="btn btn-primary btn-block">Login!</button>
    </form>
@endsection