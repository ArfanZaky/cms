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
                    <img src="{{ \App\Helper\Helper::_get_logo() }}" alt="logo" class="w-50 mb-3">
                    <p>{{ \App\Helper\Helper::_setting_code('web_caption') }}</p>
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
                                </div>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password">{{ __('Captcha') }}</label>
                                </div>
                                <div class="g-recaptcha" data-sitekey="{{ env('SITE_KEY') }}"></div>

                                @error('g-recaptcha-response')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class=" text-muted text-center">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
