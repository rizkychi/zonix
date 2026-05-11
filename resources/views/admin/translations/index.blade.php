@extends('layouts.master')
@section('title')
    {{ __('Translations') }}
@endsection
@section('content')
    <x-breadcrumb :items="[['label' => __('Admin')], ['label' => __('Translations'), 'url' => route('admin.translations.index')]]" />

    <x-lang />

    <div class="row">
        <div class="col-lg-7 d-flex">
            <div class="card d-flex flex-column w-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Translation Manager') }}</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-2">
                        {{ __('Manage your application translations across different locales.') }}
                    </p>

                    <form method="GET" class="row g-3 align-items-end" id="filter-form">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">{{ __('Source Locale') }}</label>
                            <select name="source" class="form-select">
                                @foreach ($locales as $locale)
                                    <option value="{{ $locale }}" @selected($source === $locale)>
                                        {{ strtoupper($locale) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">{{ __('Target Locale') }}</label>
                            <select name="target" class="form-select">
                                @foreach ($locales as $locale)
                                    <option value="{{ $locale }}" @selected($target === $locale)>
                                        {{ strtoupper($locale) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">{{ __('Status') }}</label>
                            <select name="status" class="form-select">
                                <option value="all" @selected($status === 'all')>{{ __('All') }}
                                    ({{ $stats['total'] }})</option>
                                <option value="translated" @selected($status === 'translated')>{{ __('Translated') }}
                                    ({{ $stats['translated'] }})</option>
                                <option value="missing" @selected($status === 'missing')>{{ __('Missing') }}
                                    ({{ $stats['missing'] }})
                                </option>
                                <option value="identical" @selected($status === 'identical')>{{ __('Identical') }}
                                    ({{ $stats['identical'] }})</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-lg-5 d-flex">
            <div class="card d-flex flex-column w-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Progress') }} {{ strtoupper($source) }} →
                        {{ strtoupper($target) }}</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-3">
                        {{ __('Track the progress of your translation efforts.') }}
                    </p>
                    <div class="row gap-2 g-0 align-items-center">
                        <div class="col text-center bg-success-subtle rounded-start">
                            <small class="text-success text-nowrap">{{ __('Translated') }}</small>
                            <div class="fw-bold text-success" style="font-size: 1.25rem;">{{ $stats['translated'] }}</div>
                        </div>
                        <div class="col text-center bg-danger-subtle rounded-start">
                            <small class="text-danger text-nowrap">{{ __('Missing') }}</small>
                            <div class="fw-bold text-danger" style="font-size: 1.25rem;">{{ $stats['missing'] }}</div>
                        </div>
                        <div class="col text-center bg-warning-subtle rounded-start">
                            <small class="text-warning text-nowrap">{{ __('Identical') }}</small>
                            <div class="fw-bold text-warning" style="font-size: 1.25rem;">{{ $stats['identical'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="progress animated-progress rounded-bottom rounded-0" style="height: 6px;">
                    <div class="progress-bar-striped progress-bar-animated bg-success rounded-0" role="progressbar"
                        style="width: {{ $stats['percent'] }}%" aria-valuenow="{{ $stats['percent'] }}"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div><!-- end row -->

    {{-- Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <pre class="mb-0" style="font-size:0.85rem;">{{ session('success') }}</pre>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Action Cards --}}
    <div class="row g-3">
        {{-- Scan Missing --}}
        <div class="col-lg-3">
            <form method="POST" action="{{ route('admin.translations.scan-missing') }}"
                class="card h-100 border-0 shadow-sm" onsubmit="window.showLoading(true);">
                @csrf
                <input type="hidden" name="locale" value="{{ $source }}">
                <div class="card-body d-flex flex-column">
                    <!-- Row: icon + text -->
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary fs-17 rounded">
                                <i class="bx bx-search-alt"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <h2 class="h6 mb-1">{{ __('Scan Missing') }}</h2>
                            <p class="text-muted small mb-0">
                                {{ __('Find all') }} <code>__()</code> {{ __('in Blade/PHP files') }},
                                {{ __('sync new keys to') }} <code>{{ $source }}.json</code>.
                            </p>
                        </div>
                    </div>

                    <!-- Button -->
                    <button class="btn btn-primary mt-auto w-100" type="submit">{{ __('Run Scan') }}</button>
                </div>
            </form>
        </div>

        {{-- Auto Translate --}}
        <div class="col-lg-3">
            <form method="POST" action="{{ route('admin.translations.translate') }}"
                class="card h-100 border-0 shadow-sm" onsubmit="window.showLoading(true);">
                @csrf
                <input type="hidden" name="source" value="{{ $source }}">
                <input type="hidden" name="target" value="{{ $target }}">
                <div class="card-body d-flex flex-column">
                    <!-- Row: icon + text -->
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
                                <i class="ri-robot-2-line"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <h2 class="h6 mb-1">{{ __('Auto Translate') }}</h2>
                            <p class="text-muted small mb-0">
                                {{ __('Translate keys that are missing in') }}
                                <code>{{ $target }}.json</code> {{ __('via AI') }}.
                            </p>
                        </div>
                    </div>

                    <!-- Button -->
                    <button class="btn btn-success mt-auto" @if ($stats['missing'] === 0) disabled @endif>
                        {{ __('Translate Now') }}
                        @if ($stats['missing'] > 0)
                            <span class="badge bg-light text-dark ms-1">{{ $stats['missing'] }}</span>
                        @endif
                    </button>
                </div>
            </form>
        </div>

        {{-- Sort Files --}}
        <div class="col-lg-3">
            <form method="POST" action="{{ route('admin.translations.sort') }}" class="card h-100 border-0 shadow-sm" onsubmit="window.showLoading(true);">
                @csrf
                <div class="card-body d-flex flex-column">
                    <!-- Row: icon + text -->
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-secondary-subtle text-secondary fs-17 rounded">
                                <i class="ri-sort-asc"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <h2 class="h6 mb-1">{{ __('Sort Files') }}</h2>
                            <p class="text-muted small mb-0">
                                {{ __('Sort all translation files alphabetically by their keys.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Button -->
                    <button class="btn btn-outline-secondary mt-auto" type="submit">
                        {{ __('Sort JSON') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Add Locale --}}
        <div class="col-lg-3">
            <form method="POST" action="{{ route('admin.translations.add-locale') }}"
                class="card h-100 border-0 shadow-sm" onsubmit="window.showLoading(true);">
                @csrf
                <input type="hidden" name="source" value="{{ $source }}">
                <div class="card-body d-flex flex-column">
                    <!-- Row: icon + text -->
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-dark-subtle text-dark fs-17 rounded">
                                <i class="ri-earth-line"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <h2 class="h6 mb-1">{{ __('Add Locale') }}</h2>
                            <p class="text-muted small mb-0">
                                {{ __('Create a new locale and automatically translate from the source.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="input-group mt-auto">
                        <input type="text" name="target" class="form-control" placeholder="fr, de, ja...">
                        <button class="btn btn-dark" type="submit">{{ __('Add') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            {{-- Translation Table --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <strong>{{ strtoupper($source) }}</strong>
                        <span class="text-muted mx-1">→</span>
                        <strong>{{ strtoupper($target) }}</strong>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <x-data-table id="translations-table" class="table-sm" :columns="[
                        ['data' => 'key', 'title' => __('Key')],
                        ['data' => 'source', 'title' => __('Source') . ' (' . strtoupper($source) . ')'],
                        ['data' => 'target', 'title' => __('Target') . ' (' . strtoupper($target) . ')', 'orderable' => false, 'searchable' => false, 'width' => '30%'],
                        ['data' => 'status', 'title' => __('Status'), 'orderable' => false, 'searchable' => false, 'width' => '10%'],
                    ]" ajax="{{ route('admin.translations.index') }}" :params="[
                        'source' => $source,
                        'target' => $target,
                        'status' => $status
                    ]"/>
                </div>
            </div>
        </div>
    </div>
@endsection