@extends('layouts.master-without-nav')
@section('title')
    {{ __('Sign Up') }}
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
                                    <h5 class="text-primary">{{ __('Create New Account') }}</h5>
                                    <p class="text-muted">{{ __('Get your free velzon account now') }}</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="needs-validation" novalidate method="POST"
                                        action="{{ route('register') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">{{ __('Email') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" id="useremail"
                                                placeholder="{{ __('Enter email address') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">{{ __('Username') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                name="username" value="{{ old('username') }}" id="username"
                                                placeholder="{{ __('Enter username') }}" required>
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="fname" class="form-label">{{ __('Full Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('fname') is-invalid @enderror" name="fname"
                                                value="{{ old('fname') }}" id="fname" placeholder="{{ __('Enter full name') }}"
                                                required>
                                            @error('fname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">{{ __('Password') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="userpassword" placeholder="{{ __('Enter password') }}" required>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mt-3">
                                            <button class="btn btn-success w-100" type="submit">{{ __('Sign Up') }}</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">{{ __('Already have an account?') }} <a href="{{ route('login') }}"
                                    class="fw-semibold text-primary text-decoration-underline"> {{ __('Sign in') }} </a> </p>
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
    @vite(['resources/js/pages/register.js'])
@endpush
