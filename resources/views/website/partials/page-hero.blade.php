@php
    $text = (array) ($text ?? []);
    $primary ??= null;
    $secondary ??= null;
    $image ??= null;
    $imageAlt ??= '';
    $arrow = '<svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5"/></svg>';
@endphp

<section class="relative overflow-hidden pt-[calc(72px+clamp(48px,8vh,88px))] {{ $image ? '' : 'pb-[clamp(56px,7vw,96px)]' }}" aria-labelledby="page-title">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-full bg-[radial-gradient(60%_60%_at_50%_0%,#e7eeff_0%,#ffffff_70%)]" aria-hidden="true"></div>
    <div class="wrap text-center">
        <h1 id="page-title" class="mx-auto max-w-[22ch] text-[clamp(2.5rem,5.2vw,5rem)] leading-[1] font-semibold tracking-[-0.045em]">
            <span class="line"><span>{{ $title }}</span></span>
        </h1>
        @foreach ($text as $p)
            <p class="hero-in will-rise text-soft t-lead mx-auto mt-5 max-w-[60ch]">{{ $p }}</p>
        @endforeach
        @if ($primary || $secondary)
            <div class="hero-in will-rise mt-9 flex flex-wrap items-center justify-center gap-3">
                @if ($primary)<a href="{{ $primary[1] }}" class="btn">{{ $primary[0] }} {!! $arrow !!}</a>@endif
                @if ($secondary)<a href="{{ $secondary[1] }}" class="btn btn-line">{{ $secondary[0] }}</a>@endif
            </div>
        @endif
    </div>
    @if ($image)
        <div class="wrap mt-[clamp(48px,7vh,80px)] pb-[clamp(56px,7vw,96px)] [perspective:1800px]">
            <figure class="hero-shot shot mx-auto max-w-[1180px] origin-[50%_0%]">
                <x-picture :src="$image" :alt="$imageAlt" sizes="(min-width: 1240px) 1180px, 92vw" priority />
            </figure>
        </div>
    @endif
</section>
