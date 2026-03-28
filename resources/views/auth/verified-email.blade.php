@extends('layouts.master-without-nav')
@section('title')
    {{ __('Email Verified') }}
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
                                    <div class="avatar-title bg-light text-success display-3 rounded-circle">
                                        <i class="ri-checkbox-circle-fill"></i>
                                    </div>
                                </div>
                                <div class="mt-4 pt-2">
                                    <h4>{{ __('Well done !') }}</h4>
                                    <p class="text-muted mx-4">{{ __('Aww yeah, you successfully verified your email address.') }}</p>
                                    <div class="mt-4">
                                        <a href="{{ route('dashboard') }}?verified=1" class="btn btn-success w-100">{{ __('Back to Dashboard') }}</a>
                                    </div>
                                </div>
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