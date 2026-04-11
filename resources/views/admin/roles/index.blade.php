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

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Roles') }} {{ __('Manager') }}</h4>

                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-1">
                        {{ __('Always synchronize after developing new features. You can enable/disable the resources at any time.') }}
                    </p>

                    <x-data-table id="roles-table" class="table-sm" :columns="[
                        ['data' => 'DT_RowIndex', 'title' => '#'],
                        ['data' => 'name', 'title' => __('Name')],
                        ['data' => 'permissions_count', 'title' => __('Total Permissions')],
                        ['data' => 'users_count', 'title' => __('Total Users')],
                        ['data' => 'actions', 'title' => __('Actions'), 'orderable' => false, 'searchable' => false],
                    ]"
                        ajax="{{ route('admin.roles.data') }}" />
                </div><!-- end card-body -->

            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
