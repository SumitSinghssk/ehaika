{{--
    Error page body, in the home page's design: the blue wash of the page hero, the status code set large and light
    like the footer wordmark, a title, one sentence and the way back.
    $code, $title, $text, $primary [label, href], $secondary [label, href] (optional), $links (bool: list the header menu).
--}}
@php
    $secondary ??= null;
    $links ??= false;
    $arrow = '<svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5"/></svg>';

    // the pages most visitors are after, taken from Admin → Menus → Header (Home is already the main button)
    $menu = $links
        ? collect(rescue(fn () => app(\App\Services\Menus\MenuBuilder::class)->tree('header'), [], report: false))
            ->filter(fn ($i) => filled($i['label'] ?? null) && filled($i['url'] ?? null) && rtrim(url($i['url']), '/') !== rtrim(url('/'), '/'))
            ->take(6)
            ->values()
        : collect();
@endphp

<section class="relative overflow-hidden pt-[calc(72px+clamp(40px,7vh,80px))] pb-[clamp(72px,9vw,128px)]" aria-labelledby="hero-title">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-full bg-[radial-gradient(60%_60%_at_50%_0%,#e7eeff_0%,#ffffff_70%)]" aria-hidden="true"></div>
    <div class="wrap text-center">
        <p class="err-mark hero-in will-rise" aria-hidden="true">
            <span class="fm text-[clamp(6.5rem,24vw,17rem)] leading-[0.85] font-bold tracking-[-0.06em]">@foreach (mb_str_split((string) $code) as $ch)<span class="fm-l">{{ $ch }}</span>@endforeach</span>
        </p>
        <h1 id="hero-title" class="mx-auto mt-6 max-w-[22ch] text-[clamp(2.25rem,4.4vw,4rem)] leading-[1.02] font-semibold tracking-[-0.04em]">
            <span class="line"><span>{{ $title }}</span></span>
        </h1>
        <p class="hero-in will-rise text-soft t-lead mx-auto mt-5 max-w-[56ch]">{{ $text }}</p>
        <div class="hero-in will-rise mt-9 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ $primary[1] }}" class="btn">{{ $primary[0] }} {!! $arrow !!}</a>
            @if ($secondary)
                <a href="{{ $secondary[1] }}" class="btn btn-line">{{ $secondary[0] }}</a>
            @endif
        </div>

        @if ($menu->isNotEmpty())
            <nav class="hero-in will-rise mx-auto mt-16 max-w-[760px] text-left" aria-label="Popular pages">
                <h2 class="text-soft text-center text-[0.9375rem]">Or go straight to</h2>
                <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                    @foreach ($menu as $item)
                        <li>
                            <a href="{{ $item['url'] }}" @if ($item['new_tab'] ?? false) target="_blank" rel="noopener" @endif class="err-link">
                                {{ $item['label'] }} {!! $arrow !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif
    </div>
</section>
