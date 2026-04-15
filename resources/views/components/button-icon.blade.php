<{{ $el }} type="{{ $type }}"
    {{ $attributes->merge(['class' => 'btn btn-' . $size . ' btn-' . $variant . ' btn-label waves-effect waves-light']) }}>
    <i class="{{ $icon }} label-icon align-middle fs-16 me-2"></i> {{ $slot }}</{{ $el }}>
