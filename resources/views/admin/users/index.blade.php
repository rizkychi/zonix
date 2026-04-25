@extends('layouts.master')
@section('title')
    {{ __('User Roles') }}
@endsection
@section('content')
    <x-breadcrumb :items="[
        ['label' => __('Admin')],
        ['label' => __('User Roles'), 'url' => route('admin.user-roles.index')],
    ]" />

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('User Roles Manager') }}</h4>

                    <div class="flex-shrink-0">
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-1">
                        {{ __('Manage user roles and their associated permissions.') }}
                    </p>

                    <x-data-table id="users-table" class="table-sm" :columns="[
                        ['data' => 'DT_RowIndex', 'title' => '#'],
                        ['data' => 'username', 'title' => __('Username'), 'class' => 'username'],
                        ['data' => 'full_name', 'title' => __('Full Name')],
                        ['data' => 'email', 'title' => __('Email')],
                        ['data' => 'roles_list', 'title' => __('Roles'), 'orderable' => false],
                        ['data' => 'actions', 'title' => __('Actions'), 'orderable' => false, 'searchable' => false],
                    ]"
                        ajax="{{ route('admin.user-roles.index') }}" />
                </div><!-- end card-body -->

            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <!-- Default Modals -->
    <div id="role-modal" class="modal fade" tabindex="-1" aria-labelledby="role-modal-label" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="role-modal-label">{{ __('Edit') }} <span id="user-username"></span>
                        {{ __('Roles') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">{{ __('Select the roles for this user.') }}</p>
                    <form id="roles-form">
                        <input type="hidden" id="user-id" name="user_id" value="">
                        <div class="row">
                            <div class="col-12">
                                <label for="roles" class="form-label">{{ __('Roles') }}</label>
                                <select id="roles" class="form-control" data-choices data-choices-removeItem
                                    name="roles[]" multiple>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button class="btn btn-success btn-load waves-effect waves-light" id="save-roles-btn" type="button">
                        <span class="d-flex align-items-center gap-2">
                            <span class="spinner-border flex-shrink-0" role="status" style="display: none;">
                                <span class="visually-hidden">{{ __('Saving...') }}</span>
                            </span>
                            <span class="flex-grow-1">
                                {{ __('Save Changes') }}
                            </span>
                        </span>
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
