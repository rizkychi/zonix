{{--
    Partial: _nestable_item.blade.php
    Called recursively by itself to render nested menu items
    Variables: $item (MenuItem), $nested (int, from 1, indicates current nesting level for styling)
--}}
<div class="list-group-item nested-{{ $nested }} {{ !$item->is_active ? 'item-inactive' : '' }}"
    data-id="{{ $item->id }}" data-type="{{ $item->type }}">

    {{-- ── Item Row ─────────────────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center gap-2" style="min-height:32px;">

        {{-- Drag handle --}}
        <i class="ri-drag-move-line handle" title="{{ __('Drag to move') }}"></i>

        {{-- Type badge --}}
        <span class="badge text-uppercase {{ $item->type === 'title' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }}">
            {{ $item->type === 'title' ? __('Title') : __('Item') }}
        </span>

        {{-- Icon --}}
        @if ($item->icon)
            <i class="{{ $item->icon }} fs-16 align-middle text-primary"></i>
        @else
            <i class="ri-circle-line fs-14 align-middle text-muted"></i>
        @endif

        {{-- Label --}}
        <span class="item-title fw-medium text-truncate" style="max-width:180px" title="{{ $item->title }}">
            {{ $item->title }}
        </span>

        {{-- Route / URL hint --}}
        @if ($item->route || $item->url)
            <small class="text-muted d-none d-lg-inline" style=""
                title="{{ $item->route ?: $item->url }}">
                {{ $item->route ?: $item->url }}
            </small>
        @endif

        {{-- Permission badge --}}
        @if ($item->permission)
            <span class="badge bg-dark bg-opacity-10 text-dark d-none d-xl-inline" title="{{ __('Permission required') }}">
                <i class="ri-lock-2-line me-1"></i>{{ $item->permission }}
            </span>
        @endif

        {{-- Badge preview --}}
        @if ($item->badge_text)
            <span class="badge {{ $item->badge_class ?? 'bg-success' }}">
                {{ $item->badge_text }}
            </span>
        @endif

        {{-- Inactive indicator --}}
        <span class="badge border border-warning text-warning is-inactive {{ $item->is_active ? 'd-none' : '' }}" title="{{ __('Inactive') }}">
            <i class="ri-eye-off-line me-1"></i>{{ __('Inactive') }}
        </span>

        {{-- Actions --}}
        <div class="d-flex gap-1 flex-shrink-0 ms-auto">
            <button class="btn btn-sm btn-icon btn-soft-warning btn-edit-item" data-id="{{ $item->id }}" title="{{ __('Edit') }}">
                <i class="ri-pencil-fill"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-soft-info btn-toggle-item"
                data-id="{{ $item->id }}" data-title='["{{ __('Deactivate') }}", "{{ __('Activate') }}"]' title="{{ $item->is_active ? __('Deactivate') : __('Activate') }}">
                <i class="ri-{{ $item->is_active ? 'eye' : 'eye-off' }}-fill"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-soft-danger btn-delete-item" data-id="{{ $item->id }}" title="{{ __('Delete') }}">
                <i class="ri-delete-bin-fill"></i>
            </button>
        </div>

    </div>{{-- end item-row --}}

    {{-- ── Nested Children ──────────────────────────────────────────────────── --}}
    @if ($nested <= 3)
        <div class="list-group nested-list nested-sortable">
            @foreach (collect($item->children) as $child)
                @include('admin.menu._nestable_item', [
                    'item' => $child,
                    'nested' => $nested + 1,
                ])
            @endforeach
        </div>
    @endif

</div>
