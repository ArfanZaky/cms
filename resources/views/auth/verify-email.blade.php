@extends('layouts.app-login')

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        <img src="{{ asset('custom/images/logo.svg') }}" alt="logo"  class="w-100">
                    </div>
    

                    <div class="card card-primary">
                        {{-- <div class="card-header">
                            <h4>Confirm Password</h4>
                        </div> --}}
                      

                        <div class="card-body">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <label for="password">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.</label>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Resend Verification Email
                                    </button>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger btn-lg btn-block" tabindex="4">
                                        Logout
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

@section('javascript')
@if (session('status') == 'verification-link-sent')
    <script>
        iziToast.info({
            title: 'New Verification Link!',
            message: 'A new verification link has been sent to the email address you provided during registration.',
            position: 'topRight'
        });
    </script>
@endif
@endsection
