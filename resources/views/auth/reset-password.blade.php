@extends('layouts.master-without-nav')
@section('title')
    {{ __('Reset Password') }}
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
                                    <h5 class="text-primary">{{ __('Create new password') }}</h5>
                                    <p class="text-muted">{{ __('Your new password must be different from previous used password.') }}
                                    </p>
                                </div>

                                <div class="p-2">
                                    <form class="form-horizontal" method="POST" action="{{ route('password.store') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                        <input type="hidden" name="email" value="{{ $request->get('email') }}">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">{{ __('Email') }}</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="useremail" placeholder="{{ __('Enter email') }}"
                                                value="{{ $request->get('email') }}" id="email" disabled>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">{{ __('Password') }}</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" name="password"
                                                    class="form-control pe-5 password-input" onpaste="return false"
                                                    placeholder="{{ __('Enter password') }}" id="password-input"
                                                    aria-describedby="passwordInput"
                                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                            <div id="passwordInput" class="form-text">{{ __('Must be at least 8 characters.') }}</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="confirm-password-input">{{ __('Confirm Password') }}</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" name="password_confirmation"
                                                    class="form-control pe-5 password-input" onpaste="return false"
                                                    placeholder="{{ __('Confirm password') }}"
                                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                    id="confirm-password-input" required>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                    type="button" id="confirm-password-input"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                        </div>

                                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                            <h5 class="fs-13">{{ __('Password must contain:') }}</h5>
                                            <p id="pass-length" class="invalid fs-12 mb-2">{{ __('Minimum 8 characters') }}</p>
                                            <p id="pass-lower" class="invalid fs-12 mb-2">{{ __('At lowercase letter (a-z)') }}
                                            </p>
                                            <p id="pass-upper" class="invalid fs-12 mb-2">{{ __('At least uppercase letter (A-Z)') }}</p>
                                            <p id="pass-number" class="invalid fs-12 mb-0">{{ __('A least number (0-9)') }}</p>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-success w-100 waves-effect waves-light"
                                                type="submit">{{ __('Reset Password') }}</button>
                                        </div>

                                    </form><!-- end form -->
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">{{ __('Wait, I remember my password...') }} <a href="{{ route('login') }}"
                                    class="fw-semibold text-primary text-decoration-underline"> {{ __('Click here') }} </a>
                            </p>
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
    @vite(['resources/js/pages/reset-password.js'])
@endpush
