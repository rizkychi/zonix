@extends('layouts.master')
@section('title')
    {{ __('Menu') }}
@endsection
@section('content')
    <x-breadcrumb :items="[['label' => __('Admin')], ['label' => __('Menu'), 'url' => route('admin.menu.index')]]" />

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('Menu Builder') }}</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <p class="text-muted mb-2">
                        {{ __('Drag and drop the menu items to build your menu. You can also edit or delete existing items.') }}
                    </p>

                    {{-- ROOT sortable list --}}
                    <div class="list-group nested-sortable gap-1" id="root-list">
                        @foreach ($menuTree as $item)
                            @include('admin.menu._nestable_item', ['item' => $item, 'nested' => 1])
                        @endforeach
                    </div>
                </div><!-- end card-body -->

                {{-- Save bar --}}
                <div id="save-bar" class="justify-content-between">
                    <x-button-icon id="btn-create-item" icon="ri-add-line" variant="primary">
                        {{ __('Create Menu') }}
                    </x-button-icon>
                    <x-button-icon id="btn-save-order" icon="ri-save-2-fill" variant="success">
                        {{ __('Save Order') }}
                    </x-button-icon>
                </div>
            </div><!-- end card -->
        </div><!-- end col -->
    </div>

    {{-- ── Edit Modal ──────────────────────────────────────────────────────────── --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="edit-form" novalidate autocomplete="off">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title d-none" id="modal-title-create"><i
                                class="ri-add-line me-1"></i>{{ __('Create Menu') }}</h5>
                        <h5 class="modal-title d-none" id="modal-title-edit"><i
                                class="ri-pencil-fill me-1"></i>{{ __('Edit Menu') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id">
                        <div class="row gy-3">
                            <div class="col-12 flex-column d-flex">
                                <div class="btn-group" role="group" aria-label="{{ __('Type') }}">
                                    <input class="btn-check" type="radio" name="edit_type" id="et-item" value="item"
                                        checked>
                                    <label class="btn btn-outline-primary" for="et-item">{{ __('Menu Item') }}</label>
                                    <input class="btn-check" type="radio" name="edit_type" id="et-title" value="title">
                                    <label class="btn btn-outline-primary" for="et-title">{{ __('Section Title') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('Label') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit-title" name="title"
                                    placeholder="{{ __('Enter menu label') }}" required>
                            </div>
                            <div class="col-md-6 eio">
                                <label class="form-label fw-semibold">{{ __('Icon Class') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i id="edit-icon-preview"
                                            class="ri-question-line"></i></span>
                                    <input type="text" class="form-control" id="edit-icon" name="icon"
                                        placeholder="{{ __('e.g. ri-dashboard-line') }}">
                                </div>
                            </div>
                            <div class="col-md-12 eio">
                                <div class="row">
                                    <div class="col-md-6 eio">
                                        <label class="form-label fw-semibold">{{ __('Route Name') }}</label>
                                        <input type="text" class="form-control" id="edit-route" name="route"
                                            placeholder="{{ __('e.g. admin.dashboard') }}">
                                    </div>
                                    <div class="col-md-6 eio">
                                        <label class="form-label fw-semibold">{{ __('Custom URL') }}</label>
                                        <input type="text" class="form-control" id="edit-url" name="url"
                                            placeholder="{{ __('e.g. https://example.com') }}">
                                    </div>
                                    <div class="col-md-12 eio">
                                        <p class="form-text text-muted mb-0">
                                            {{ __('If both route and URL are provided, route will be used.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 eio">
                                <label class="form-label fw-semibold">{{ __('Permission RBAC') }}</label>
                                <select class="form-select select2" id="edit-permission" name="permission" data-allow-clear="true" data-placeholder="{{ __('Select permission') }}">
                                    <option value=""></option>
                                    @foreach ($permissions as $perm)
                                        <option value="{{ $perm }}">{{ $perm }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 eio">
                                <label class="form-label fw-semibold">{{ __('Badge Text') }}</label>
                                <input type="text" class="form-control" id="edit-badge-text" name="badge_text" placeholder="{{ __('e.g. New') }}">
                            </div>
                            <div class="col-md-3 eio">
                                <label class="form-label fw-semibold">{{ __('Badge Class') }}</label>
                                <select class="form-select select2" id="edit-badge-class" name="badge_class" data-allow-clear="true" data-placeholder="{{ __('Select class') }}">
                                    <option value=""></option>
                                    <option value="bg-success">Success</option>
                                    <option value="bg-danger">Danger</option>
                                    <option value="bg-warning text-dark">Warning</option>
                                    <option value="bg-info text-dark">Info</option>
                                    <option value="bg-primary">Primary</option>
                                    <option value="bg-secondary">Secondary</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="col-auto d-flex gap-3">
                            <div class="form-check form-switch eio">
                                <input class="form-check-input" type="checkbox" id="edit-new-tab" name="open_new_tab">
                                <label class="form-check-label" for="edit-new-tab">{{ __('New Tab') }}</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit-active" name="is_active">
                                <label class="form-check-label" for="edit-active">{{ __('Active') }}</label>
                            </div>
                        </div>
                        <div class="col-auto d-flex gap-2">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="button" class="btn btn-success btn-label" id="btn-save-edit">
                                <i class="ri-save-2-fill label-icon align-middle fs-16"></i>{{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ── Delete Confirm Modal ──────────────────────────────────────────────── --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="mt-3">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-5">
                            <h4>
                                {{ __('Are you sure?') }}
                            </h4>
                            <p class="text-muted mx-4 mb-0">
                                {{ __('All child items will also be deleted.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="btn-confirm-delete">
                        <i class="ri-delete-bin-line me-1"></i>{{ __('Yes, delete it!') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('css')
    <style>
        /* ── Sortable states ──────────────────────────────────────────────────── */
        .sortable-ghost {
            opacity: .4;
            background: rgba(var(--vz-primary-rgb), .07) !important;
            border: 2px dashed var(--vz-primary) !important;
        }

        .sortable-drag {
            opacity: 1 !important;
        }

        /* ── List item ────────────────────────────────────────────────────────── */
        .nested-list {
            margin-top: 0px;
        }

        .nested-list .list-group-item {
            background: transparent;
        }

        .nested-list > :first-child {
            margin-top: 8px;
        }

        .list-group-item {
            border-radius: 6px !important;
            border: 1px solid var(--vz-border-color);
            padding: 8px 12px;
            background: rgba(var(--vz-primary-rgb), 0.03) !important;
        }

        .list-group-item+.list-group-item {
            margin-top: 5px;
            border-top-width: 1px;
        }

        /* ── Inactive item ────────────────────────────────────────────────────── */
        .list-group-item.item-inactive>.item-row>.item-title {
            text-decoration: line-through;
            opacity: .5;
        }

        /* ── Drag handle ──────────────────────────────────────────────────────── */
        .handle {
            cursor: grab;
            color: var(--vz-secondary-color);
            font-size: 16px;
            padding: 0 2px;
            flex-shrink: 0;
        }

        .handle:active {
            cursor: grabbing;
        }

        /* ── Save bar ─────────────────────────────────────────────────────────── */
        #save-bar {
            position: sticky;
            bottom: 0;
            z-index: 9;
            background: var(--vz-card-bg);
            border-top: 1px solid var(--vz-border-color);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            border-radius: 0 0 8px 8px;
        }

        /* ── Input group text for icon preview ────────────────────────────────── */
        .input-group-text {
            width: 42px;
            overflow: hidden;
            white-space: nowrap;
        }
    </style>
@endpush