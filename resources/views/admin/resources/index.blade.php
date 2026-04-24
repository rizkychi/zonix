@extends('layouts.master')
@section('title')
    {{ __('Resources') }}
@endsection
@section('content')
    <x-breadcrumb :items="[
        ['label' => __('Admin')],
        ['label' => __('Resources'), 'url' => route('admin.resources.index')],
    ]"/>

    @if (session('sync_result'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('sync_result') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Resources Manager') }}</h4>

                    <div class="flex-shrink-0">
                        <form method="POST" action="{{ route('admin.resources.sync') }}">
                            @csrf
                            <!-- Buttons with Label -->
                            <x-button-icon type="submit" variant="primary" icon="ri-refresh-line">
                                {{ __('Sync Routes') }}
                            </x-button-icon>
                        </form>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-1">
                        {{ __('Always synchronize after developing new features. You can enable/disable the resources at any time.') }}
                    </p>

                    <x-data-table id="resources-table" class="table-sm" :columns="[
                        ['data' => 'name', 'title' => __('Route Name / Permission')],
                        ['data' => 'uri', 'title' => __('URI')],
                        ['data' => 'http_method', 'title' => __('Method')],
                        ['data' => 'controller_action', 'title' => __('Action')],
                        ['data' => 'group', 'title' => __('Group')],
                        ['data' => 'is_active', 'title' => __('Status'), 'orderable' => false, 'searchable' => false],
                    ]"
                        ajax="{{ route('admin.resources.index') }}" />
                </div><!-- end card-body -->

            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection