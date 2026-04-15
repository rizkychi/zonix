@extends('layouts.master')
@section('title')
    {{ __('Roles') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('Admin') }}
        @endslot
        @slot('title')
            {{ __('Roles') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        @if (isset($role))
                            {{ __('Edit') }} {{ __('Role') }}
                        @else
                            {{ __('Create') }} {{ __('Role') }}
                        @endif
                    </h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-2">
                        @if (isset($role))
                            {{ __('Update the details of the role below. You can also manage permissions for this role.') }}
                        @else
                            {{ __('Fill in the details below to create a role.') }}
                        @endif
                    </p>

                    <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
                        method="post" id="role-form" class="row g-3 needs-validation" novalidate>
                        @csrf
                        @if (isset($role))
                            @method('PUT')
                        @endif

                        <div class="col-md-12 position-relative">
                            <label for="name" class="form-label">{{ __('Role Name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" placeholder="{{ __('e.g. admin, editor, manager') }}"
                                value="{{ old('name', $role->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback server-error d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </form>
                </div><!-- end card-body -->
                <div class="card-footer d-flex align-items-center justify-content-end">
                    <x-button-form form="role-form" back-url="{{ route('admin.roles.index') }}">
                        @if (isset($role))
                            {{ __('Update') }}
                        @else
                            {{ __('Create') }}
                        @endif
                    </x-button-form>
                </div><!-- end card-footer -->

            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
