@php
    $c = config('ethaika');
    $fp = $c['features_page'];
    $f = $c['features']['items'];
    $check = '<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3.5 8.5 3 3 6-7"/></svg>';
@endphp

<x-website title="Features · Ethaika" :description="$fp['meta']" :breadcrumbs="[['name' => 'Features', 'url' => route('features')]]">
    @include('website.partials.page-hero', [
        'title' => $fp['title'],
        'text' => [$fp['text']],
        'primary' => ['Book Your Demo', route('contact')],
        'secondary' => ['See the Platform', route('platform')],
        'image' => 'images/site/simulation-engine.webp',
        'imageAlt' => $c['hero']['image_alt'],
    ])

    <section class="wrap pb-[clamp(72px,8vw,120px)]" aria-labelledby="int-title">
        <p class="will-rise mx-auto max-w-[60ch] text-center text-[clamp(1.25rem,2vw,1.625rem)] leading-snug font-semibold tracking-[-0.02em]">{{ $fp['note'] }}</p>
        <h2 id="int-title" class="sr-only">{{ $c['integrations']['label'] }}</h2>
        <ul class="card mt-12 grid grid-cols-2 overflow-hidden sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($c['integrations']['logos'] as $logo)
                <li class="tile will-rise group border-line flex flex-col items-center justify-center gap-3 border-r border-b p-8 text-center">
                    <img src="{{ asset($logo['src']) }}" alt="" {!! \App\Support\ResponsiveImage::dimensions($logo['src']) !!} loading="lazy" decoding="async" @class(['h-10 object-contain transition-transform duration-500 group-hover:-translate-y-1', 'w-10 object-left object-cover' => $logo['crop'], 'w-auto' => ! $logo['crop'], 'invert' => $logo['invert']]) />
                    <span class="font-semibold">{{ $logo['name'] }}</span>
                </li>
            @endforeach
        </ul>
        <dl class="mt-4 grid gap-4 sm:grid-cols-3">
            @foreach ($c['realtime']['stats'] as $s)
                <div class="stat card will-rise flex items-baseline gap-4 p-6">
                    <dd class="text-[clamp(2.5rem,4vw,3.5rem)] leading-none font-semibold tracking-[-0.045em] tabular-nums"><span class="count" data-to="{{ $s['value'] }}">{{ $s['value'] }}</span><span class="text-accent">{{ $s['suffix'] }}</span></dd>
                    <dt class="text-soft leading-snug">{{ $s['label'] }}</dt>
                </div>
            @endforeach
        </dl>
    </section>

    <section class="bg-tone" aria-labelledby="multi-title">
        <div class="sec wrap grid gap-12 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-5">
                <h2 id="multi-title" class="t-head"><span class="line"><span>{{ $fp['multi']['title'] }}</span></span></h2>
                <p class="will-rise text-soft mt-5">{{ $fp['multi']['text'] }}</p>
                <p class="will-rise text-accent-ink mt-4 font-semibold">{{ $fp['multi']['note'] }}</p>
                <a href="{{ route('contact') }}" class="will-rise btn mt-8">Book Your Demo <svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5" /></svg></a>
            </div>
            <figure class="shot will-rise lg:col-span-7"><x-picture :src="$fp['multi']['image']" alt="Ethaika trends dashboard comparing evaluation runs across frameworks" sizes="(min-width: 1024px) 56vw, 92vw" /></figure>
        </div>
    </section>

    <section id="features" class="sec wrap" aria-labelledby="feat-title">
        <div class="mx-auto max-w-[760px] text-center">
            <h2 id="feat-title" class="t-head"><span class="line"><span>{{ $c['features']['title'] }}</span></span></h2>
        </div>
        <div class="mt-12 grid gap-4 md:grid-cols-2">
            @foreach ($f as $i => $item)
                <article class="feat card will-rise p-8 md:p-10">
                    <span class="bg-accent block h-[3px] w-10" aria-hidden="true"></span>
                    <h3 class="feat-h mt-6">{{ $item['name'] }}</h3>
                    @foreach ($item['body'] as $p)
                        <p class="text-soft mt-3">{{ $p }}</p>
                    @endforeach
                </article>
            @endforeach
        </div>
    </section>

    @include('website.partials.faq', ['tone' => true])

    <div class="pt-[clamp(72px,8vw,120px)]">
        @include('website.partials.cta', [
            'title' => config('ethaika.platform.cta.title'),
            'text' => config('ethaika.platform.cta.text'),
            'facts' => config('ethaika.platform.cta.facts'),
        ])
    </div>
</x-website>
