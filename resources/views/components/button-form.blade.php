<div class="d-flex gap-2">
    <a href="{{ $backUrl }}" class="btn btn-{{ $size }} btn-light waves-effect waves-light">
        {{ __('Cancel') }}
    </a>

    <button type="submit" form="{{ $form }}"
    {{ $attributes->merge(['class' => 'btn btn-' . $size . ' btn-' . $variant . ' btn-label waves-effect waves-light']) }}>
    <i class="{{ $icon }} label-icon align-middle fs-16 me-2"></i> {{ $slot }}</button>
</div>