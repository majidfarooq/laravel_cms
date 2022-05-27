@extends('frontend.layouts.app')
@section('title') {{'Sign In'}} @endsection
@section('keywords') {{'Sign In'}} @endsection
@section('description') {{'Sign In'}} @endsection
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
            <div class="col-lg-12 col-md-12 p-4 px-5 signup-black min-vh-100">
                <form class="row g-3" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="col-12 text-center my-5">
                        <h2><span class="log-in-account text-white">Log In an Account</span></h2>
                    </div>
                    <div class="col-12 form-group mb-3">
                        <label class="phone form-label text-white" for="phone">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" placeholder="email" id="email" name="email" type="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-12 form-group mb-3">
                        <label class="password form-label text-white" for="password">Password</label>
                        <div class="input-group-append">
                            <input class="form-control @error('password') is-invalid @enderror" placeholder="*************" id="password" name="password" type="password">
                            <span class="input-group-text" onclick="password_show_hide(this);" data-id="password">
                            <i class="fas fa-eye" id="show_eye"></i>
                            <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                        </span>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                        @enderror
                    </div>
                    <div class="col-12 form-group">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <a class="forgot-password" href="{{--{{ route('password.request') }}--}}">Forgot Password?</a>
                            </div>
                            <div class="col-lg-6 col-md-12 mypofile">
                                <button class="btn btn-primary" type="submit">
                                    <span class="text-white signin">Sign In</span>
                                    <i class="right-arrow fas fa-arrow-right"></i>
                                </button>
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
    <script></script>
@endsection
