@props([
    'items' => [],
])

@php
    $activeItem = array_pop($items);
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0 font-size-18">{{ $activeItem['label'] ?? '' }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    @foreach($items as $item)
                        <li class="breadcrumb-item">
                            <a href="{{ $item['url'] ?? 'javascript: void(0);' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach

                    @if($activeItem)
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $activeItem['label'] }}
                        </li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>