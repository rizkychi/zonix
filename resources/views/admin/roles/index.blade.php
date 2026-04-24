@extends('layouts.master')
@section('title')
    {{ __('Roles') }}
@endsection
@section('content')
    <x-breadcrumb :items="[
        ['label' => __('Admin')],
        ['label' => __('Roles'), 'url' => route('admin.roles.index')],
    ]"/>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Roles Manager') }}</h4>

                    <div class="flex-shrink-0">
                        <x-button-icon el="a" href="{{ route('admin.roles.create') }}" variant="primary" icon="ri-add-line">
                            {{ __('Add Role') }}
                        </x-button-icon>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-1">
                        {{ __('Manage your roles and their associated permissions.') }}
                    </p>

                    <x-data-table id="roles-table" class="table-sm" :columns="[
                        ['data' => 'DT_RowIndex', 'title' => '#'],
                        ['data' => 'name', 'title' => __('Name')],
                        ['data' => 'permissions_count', 'title' => __('Total Permissions')],
                        ['data' => 'users_count', 'title' => __('Total Users')],
                        ['data' => 'actions', 'title' => __('Actions'), 'orderable' => false, 'searchable' => false],
                    ]"
                        ajax="{{ route('admin.roles.index') }}" />
                </div><!-- end card-body -->

            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
