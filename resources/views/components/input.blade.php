<label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif
</label>
<input type="{{ $type }}" class="form-control {{ $class }} @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}"
    placeholder="{{ $placeholder }}" value="{{ old($name, $value ?? '') }}" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }} {{ $readonly ? 'readonly' : '' }}>
@error($name)
    <div class="invalid-feedback server-error d-block">
        {{ $message }}
    </div>
@enderror
