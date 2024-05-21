@extends('layouts.app-login')

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        <img src="{{ Helper::_get_logo() }}" alt="logo" class="w-50">
                        <p>{{ Helper::_setting_code('web_caption') }}</p>
                    </div>
    

                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Confirm Password</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('password.confirm') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" tabindex="1"
                                        required autofocus>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
