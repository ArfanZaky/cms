@extends('layouts.app-login')
@section('css')
    <style>
        
    </style>
@endsection
@section('content')
<section class="section">
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="login-brand">
                    <img src="{{ Helper::_get_logo() }}" alt="logo" class="w-50 mb-3">
                    <p>{{ Helper::_setting_code('web_caption') }}</p>
                </div>

                <div class="card card-primary mb-0">
                    <div class="card-header"><h4>{{ __('Login') }}</h4></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                            @csrf

                            <div class="form-group">
                                <label for="email">{{ __('Email Address Or Username') }}</label>

                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password">{{ __('Password') }}</label>
                                    {{-- @if (Route::has('password.request'))
                                        <div class="float-right">
                                            <a class="text-small" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        </div>
                                    @endif --}}
                                </div>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            {{-- <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="custom-control-label" for="remember-me">{{ __('Remember Me') }}</label>
                                </div>
                            </div> --}}

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class=" text-muted text-center">
                    {{-- {{ __('Dont have an account?') }} <a href="{{ route('register') }}">{{ __('Click here to register') }}</a> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
