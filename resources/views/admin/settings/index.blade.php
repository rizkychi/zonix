@extends('layouts.master')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')
    <x-breadcrumb :items="[['label' => __('Admin')], ['label' => __('Settings'), 'url' => route('admin.settings.index')]]" />

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Settings') }}</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                @foreach ($validSections as $validSection => $label)
                                    <a class="nav-link mb-2 {{ $section === $validSection ? 'active' : '' }}"
                                        id="v-pills-{{ $validSection }}-tab"
                                        href="{{ route('admin.settings.index', $validSection) }}" role="tab"
                                        aria-controls="v-pills-{{ $validSection }}"
                                        aria-selected="{{ $section === $validSection ? 'true' : 'false' }}">{{ __($label) }}</a>
                                @endforeach
                            </div>
                        </div><!-- end col -->
                        <div class="col-md-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                <form action="{{ route('admin.settings.update', $section) }}" method="POST"
                                    id="settings-form" enctype="multipart/form-data" novalidate>
                                    @csrf
                                    @method('PUT')
                                    @include("admin.settings._{$section}")
                                </form>
                            </div>
                        </div><!--  end col -->
                    </div>
                    <!--end row-->
                </div>

                <div class="card-footer d-flex align-items-center justify-content-end sticky-bottom">
                    <x-button-form form="settings-form" :enable-back="false">
                        {{ __('Save Changes') }}
                    </x-button-form>
                </div><!-- end card-footer -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
