{{-- The error panel for the standalone pages: $code (string or null), $title, $text, then any extra markup in $slot-like $extra. --}}
@php
    $arrow = '<svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5"/></svg>';
    $email = rescue(fn () => \App\Helpers\Settings::emails()[0] ?? null, null, report: false);
    $phone = rescue(fn () => \App\Helpers\Settings::phones()[0] ?? null, null, report: false);
@endphp

@if ($code)
    <p class="err-mark" aria-hidden="true">
        <span class="fm text-[clamp(6.5rem,24vw,17rem)] leading-[0.85] font-bold tracking-[-0.06em]">@foreach (mb_str_split((string) $code) as $ch)<span class="fm-l">{{ $ch }}</span>@endforeach</span>
    </p>
@endif
<h1 class="mx-auto mt-6 max-w-[22ch] text-[clamp(2.25rem,4.4vw,4rem)] leading-[1.02] font-semibold tracking-[-0.04em]">{{ $title }}</h1>
<p class="text-soft t-lead mx-auto mt-5 max-w-[56ch]">{!! nl2br(e($text)) !!}</p>

{!! $extra ?? '' !!}

@if ($retry ?? true)
    <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
        <a href="{{ url()->current() }}" class="btn">Try again {!! $arrow !!}</a>
        <a href="{{ url('/') }}" class="btn btn-line">Back to home</a>
    </div>
@endif

@if ($email || $phone)
    <p class="text-soft mt-10 text-[0.9375rem]">
        Need us in the meantime?
        @if ($email)<a href="mailto:{{ $email }}" class="ulink text-ink font-medium">{{ $email }}</a>@endif
        @if ($email && $phone) · @endif
        @if ($phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="ulink text-ink font-medium">{{ $phone }}</a>@endif
    </p>
@endif
