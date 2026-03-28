@extends('layouts.master-without-nav')
@section('title')
    {{ __('Sign In') }}
@endsection
@section('content')
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <x-auth-bg />

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                
                <!-- auth page title -->
                <x-auth-title />

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">{{ __('Welcome Back !') }}</h5>
                                    <p class="text-muted">{{ __('Sign in to continue to') }} {{ config('app.name') }}.</p>
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-success text-center mb-4" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <div class="p-2 mt-4">
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">{{ __('Username/Email') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('login') is-invalid @enderror"
                                                value="{{ old('login') }}" id="username"
                                                name="login" placeholder="{{ __('Enter username or email') }}">
                                            @error('login')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="float-end">
                                                <a href="{{ route('password.request') }}"
                                                    class="text-muted">{{ __('Forgot password?') }}</a>
                                            </div>
                                            <label class="form-label" for="password-input">{{ __('Password') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password"
                                                    class="form-control password-input pe-5 @error('password') is-invalid @enderror"
                                                    name="password" placeholder="{{ __('Enter password') }}" id="password-input"
                                                    value="">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" name="remember" type="checkbox" value="true" }}
                                                id="auth-remember-check">
                                            <label class="form-check-label"
                                                for="auth-remember-check">{{ __('Remember me') }}</label>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">{{ __('Sign In') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">{{ __('Don\'t have an account?') }} <a href="{{ route('register') }}"
                                    class="fw-semibold text-primary text-decoration-underline"> {{ __('Sign up') }} </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <x-auth-footer />
    </div>
@endsection
@push('scripts')
    @vite(['resources/js/pages/login.js'])
@endpush
