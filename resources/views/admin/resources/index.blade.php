@extends('layouts.master')
@section('title')
    {{ __('Resources') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('Admin') }}
        @endslot
        @slot('title')
            {{ __('Resources') }}
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
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Resources') }} {{ __('Manager') }}</h4>

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
                        ['data' => 'name', 'title' => 'Route Name / Permission'],
                        ['data' => 'uri', 'title' => 'URI'],
                        ['data' => 'http_method', 'title' => 'Method'],
                        ['data' => 'controller_action', 'title' => 'Action'],
                        ['data' => 'group', 'title' => 'Group'],
                        ['data' => 'is_active', 'title' => 'Status', 'orderable' => false, 'searchable' => false],
                    ]"
                        ajax="{{ route('admin.resources.data') }}" />
                </div><!-- end card-body -->

            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection