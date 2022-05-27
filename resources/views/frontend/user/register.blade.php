@extends('frontend.layouts.app')
@section('title') {{'Sign Up'}} @endsection
@section('keywords') {{'Sign Up'}} @endsection
@section('description') {{'Sign Up'}} @endsection
@section('canonical'){{ Request::url() }}@endsection
@section('style')
    <style>
        #mainNav{
            position: relative !important;
            background-color: #0c0c0c !important;
            margin-bottom: 2em !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid signup-section">
        <div class="row">
            <div class="col-lg-6 mx-auto col-md-12 col p-4 px-5 signup-black signup-right min-vh-100">
                <form class="row g-3" method="POST" action="{{ route('register') }}" id="register">
                    @csrf
                    <div class="col-12 form-group text-center my-4">
                        <h2>Create an Account</h2>
                    </div>
                    <div class="col-12 form-group mb-3">
                        <label class="name form-label" for="name">Name</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Name" name="name" type="text">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-12 form-group mb-3">
                        <label class="email form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" placeholder="Email" id="email" name="email" type="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-12 form-group mb-3">
                        <label class="email form-label" for="password">password</label>
                        <input class="form-control @error('password') is-invalid @enderror" placeholder="Password" id="password" name="password" type="password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-12 form-group mb-3">
                        <label class="password_confirmation form-label" for="password_confirmation">Confirm Password</label>
                        <input class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation" type="password">
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-12 form-group already-account">
                        <div class="already-account-box"></div>
                        <h5>
                            <a href="{{ route('login') }}">
                                <span class="already">Already Have an Account?</span>
                            </a>
                        </h5>
                    </div>
                    <div class="col-12 form-group">
                        <div class="row">
                            <div class="col-lg-7 col-md-12 p-0">
                            <span class="wpcf7-list-item first form-check my-2 p-0">
                                <input type="checkbox" id="remember" name="remember">
                                <label class="chekboxMainText ms-4" for="remember">
                                    <span class="cnv-name by-creating">By creating an account, you affirm you have read and agree to our terms of service.</span>
                                </label>
                            </span>
                            </div>
                            <div class="col-lg-5 col-md-12 p-0">
                                <button class="btn btn-primary text-white" type="submit"><span class="signup">Sign Up</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('flashy::message')
    <script type="text/javascript">
        $("#register").validate({
            rules: {
                phone: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    required: true,
                    minlength: 8,
                    equalTo: "#password"
                },
            },
            messages: {
                phone: {
                    required: "Please enter your phone",
                },
                password: {
                    required: "Please enter your password",
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    equalTo: "Please enter same password",
                },
            },
        });
    </script>
    <script></script>
@endsection
