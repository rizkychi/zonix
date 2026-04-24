@extends('layouts.master')
@section('title')
    {{ __('Permissions') }}
@endsection
@section('content')
    <x-breadcrumb :items="[
        ['label' => __('Admin')],
        ['label' => __('Roles'), 'url' => route('admin.roles.index')],
        ['label' => __('Permissions')],
    ]"/>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        {{ __('Edit') }} {{ Str::ucfirst($role->name) }} {{ __('Permissions') }}
                    </h4>
                    <button class="btn btn-soft-secondary me-2" id="select-all-btn">{{ __('Select All') }}</button>
                    <button class="btn btn-light" id="deselect-all-btn">{{ __('Clear') }}</button>
                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-3">
                        {{ __('Assign permissions to the role by selecting the checkboxes below. You can also use the "Select All" and "Deselect All" buttons for easier management.') }}
                    </p>

                    <form action="{{ route('admin.roles.permissions.sync', $role) }}" method="post" id="permission-form">
                        @csrf
                        <div class="row gy-3">
                            @foreach ($resources as $group => $resourceGroup)
                                <div class="col-md-4">
                                    <div class="list-group">
                                        <label
                                            class="list-group-item active form-check form-check-right form-check-success ps-3 select-all">{{ Str::title($group) }}
                                            <input class="form-check-input me-0" type="checkbox" {{-- role="switch" --}}
                                                id="select-all-{{ $group }}">
                                        </label>
                                        @foreach ($resourceGroup as $resource)
                                            <label
                                                class="list-group-item form-check {{-- form-switch --}} form-check-right form-check-success fw-normal ps-3">
                                                <input class="form-check-input me-0" type="checkbox" {{-- role="switch" --}}
                                                    name="permissions[]" value="{{ $resource->name }}"
                                                    {{ in_array($resource->name, $rolePermissions) ? 'checked' : '' }}>
                                                {{ zx_permission_name($group, $resource->name) }}
                                            </label>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div><!-- end row -->
                    </form>
                    <!-- end form -->
                </div><!-- end card-body -->
                <div class="card-footer d-flex align-items-center justify-content-end">
                    <x-button-form form="permission-form" back-url="{{ route('admin.roles.index') }}">
                        {{ __('Update') }}
                    </x-button-form>
                </div><!-- end card-footer -->

            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
