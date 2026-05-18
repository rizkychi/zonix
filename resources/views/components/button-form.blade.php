<div class="d-flex gap-2">
    @if ($enableBack)
        <a href="{{ $backUrl }}" class="btn btn-{{ $size }} btn-light waves-effect waves-light">
            {{ __('Cancel') }}
        </a>
    @endif

    <button type="submit" form="{{ $form }}"
    {{ $attributes->merge(['class' => 'btn btn-' . $size . ' btn-' . $variant . ' btn-label waves-effect waves-light']) }}>
    <i class="{{ $icon }} label-icon align-middle fs-16"></i> {{ $slot }}</button>
</div>