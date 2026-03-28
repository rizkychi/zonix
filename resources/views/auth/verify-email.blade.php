@extends('layouts.master-without-nav')
@section('title')
    {{ __('Verify Email') }}
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
                        <div class="card mt-4 card-bg-fill">
                            <div class="card-body p-4 text-center">
                                <div class="avatar-lg mx-auto mt-2">
                                    <div class="avatar-title bg-light text-primary display-3 rounded-circle">
                                        <i class="ri-mail-fill"></i>
                                    </div>
                                </div>
                                <div class="mt-4 pt-2">
                                    <h4>{{ __('Verify Your Email') }}</h4>
                                    <p class="text-muted mx-4">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>

                                    <div class="mt-4">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger w-100">Logout</button>
                                        </form>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <div class="mt-3 text-center">
                                        <p class="mb-0">{{ __('Didn\'t receive an email?') }}
                                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                                class="fw-semibold text-primary text-decoration-underline">{{ __('Resend') }}</a>
                                        </p>
                                    </div>
                                </form>
                                
                                @if (session('status') == 'verification-link-sent')
                                    <div class="mt-3 font-medium text-sm text-success text-center">
                                    </div>

                                    <div class="alert alert-borderless alert-success text-center mb-2" role="alert">
                                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                    </div>
                                @endif
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

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
