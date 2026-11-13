@props([
    'src',
    'location' => 'public',
    'alt' => '',
    'sizes' => '100vw',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'priority' => false,
])

@php
    $info = app(\App\Support\ResponsiveImage::class)->sources($location, $src);
    $url = media_url($src, $location);
    $width ??= $info['width'] ?? null;
    $height ??= $info['height'] ?? null;
@endphp

<picture class="contents">
    @foreach ($info['sets'] ?? [] as $format => $srcset)
        <source type="image/{{ $format }}" srcset="{{ $srcset }}" sizes="{{ $sizes }}" />
    @endforeach
    <img
        src="{{ $url }}"
        alt="{{ $alt }}"
        @if ($width) width="{{ $width }}" @endif
        @if ($height) height="{{ $height }}" @endif
        @if ($priority) fetchpriority="high" @else loading="{{ $loading }}" decoding="async" @endif
        {{ $attributes }}
    />
</picture>
