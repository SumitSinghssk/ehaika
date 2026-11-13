@php
    $c = config('ethaika');
    $description = $c['hero']['text'];
    $arrow = '<svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5"/></svg>';
    $check = '<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3.5 8.5 3 3 6-7"/></svg>';
    $mark = [
        'yes' => ['<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3.5 8.5 3 3 6-7"/></svg>', 'Yes'],
        'part' => ['<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 8h8"/></svg>', 'Partly'],
        'no' => ['<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m4.5 4.5 7 7m0-7-7 7"/></svg>', 'No'],
    ];
    $f = $c['features']['items'];
    [$l1, $l2] = array_pad(explode(' your ', $c['hero']['title'], 2), 2, '');
@endphp

<x-website
    title="Ethaika · Trust, Test, and Evaluate your AI Systems"
    :description="$description"
    :schema="[
        [
            '@type' => 'SoftwareApplication',
            'name' => 'Ethaika',
            'applicationCategory' => 'BusinessApplication',
            'description' => $description,
            'url' => url('/'),
            'publisher' => ['@id' => url('/') . '#organization'],
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => collect($c['faq']['items'])->map(fn ($q) => ['@type' => 'Question', 'name' => $q['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => implode(' ', $q['a']) . (isset($q['list']) ? ' ' . implode(', ', $q['list']) . '. ' . $q['after'] : '')]])->all(),
        ],
    ]"
>
    <section id="top" class="relative overflow-hidden pt-[calc(72px+clamp(48px,8vh,96px))]" aria-labelledby="hero-title">
        <div class="hero-wash pointer-events-none absolute inset-x-0 top-0 -z-10 h-[90%] bg-[radial-gradient(60%_55%_at_50%_0%,#e7eeff_0%,#ffffff_70%)]" aria-hidden="true"></div>

        <div class="wrap text-center">
            <h1 id="hero-title" class="mx-auto max-w-[20ch] text-[length:var(--step-display)] leading-[0.98] font-semibold tracking-[-0.045em]">
                <span class="line"><span>{{ $l1 }}</span></span>
                @if ($l2)<span class="line"><span>your <span class="text-accent">{{ $l2 }}</span></span></span>@endif
            </h1>
            <p class="hero-in will-rise text-soft t-lead mx-auto mt-6 max-w-[56ch]">{{ $c['hero']['text'] }}</p>
            <div class="hero-in will-rise mt-9 flex flex-wrap items-center justify-center gap-3">
                <a href="#contact" class="btn">{{ $c['hero']['primary'] }} {!! $arrow !!}</a>
                <a href="#platform" class="btn btn-line">{{ $c['hero']['secondary'] }}</a>
            </div>
        </div>

        <div class="wrap mt-[clamp(48px,7vh,80px)] [perspective:1800px]">
            <figure class="hero-shot shot mx-auto max-w-[1180px] origin-[50%_0%]">
                <x-picture :src="$c['hero']['image']" :alt="$c['hero']['image_alt']" sizes="(min-width: 1240px) 1180px, 92vw" priority />
            </figure>
        </div>

        <div class="wrap mt-14 pb-4">
            <p class="text-soft text-center text-[0.875rem] font-medium">{{ $c['integrations']['label'] }}</p>
            <ul class="mt-6 flex flex-wrap items-center justify-center gap-x-12 gap-y-6">
                @foreach ($c['integrations']['logos'] as $logo)
                    <li class="trust will-rise flex items-center gap-3 opacity-80 transition-opacity duration-300 hover:opacity-100">
                        <img src="{{ asset($logo['src']) }}" alt="" {!! \App\Support\ResponsiveImage::dimensions($logo['src']) !!} loading="lazy" decoding="async" @class(['h-7 object-contain', 'w-7 object-left object-cover' => $logo['crop'], 'w-auto' => ! $logo['crop'], 'invert' => $logo['invert']]) />
                        <span class="text-[1.0625rem] font-semibold tracking-[-0.01em]">{{ $logo['name'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section id="why" class="sec wrap" aria-labelledby="why-title">
        <div class="mx-auto max-w-[860px] text-center">
            <h2 id="why-title" class="t-head"><span class="line"><span>{{ $c['why']['title'] }}</span></span></h2>
        </div>
        <dl class="card why-card will-rise mt-12 grid overflow-hidden md:grid-cols-3">
            @foreach ($c['why']['stats'] as $s)
                <div @class(['stat p-8 md:p-10', 'border-line border-t md:border-t-0 md:border-l' => ! $loop->first])>
                    <dd class="text-[clamp(3rem,5vw,4.25rem)] leading-none font-semibold tracking-[-0.045em] tabular-nums">
                        <span class="count" data-to="{{ $s['value'] }}">{{ $s['value'] }}</span><span class="text-accent">{{ $s['suffix'] }}</span>
                    </dd>
                    <dt class="mt-5">
                        <span class="block text-[1.1875rem] font-semibold tracking-[-0.01em]">{{ $s['label'] }}</span>
                        <span class="text-soft mt-2 block leading-relaxed">{{ $s['text'] }}</span>
                    </dt>
                </div>
            @endforeach
        </dl>
        <div class="mt-10 text-center">
            <a href="#contact" class="btn why-in will-rise">{{ $c['why']['action'] }} {!! $arrow !!}</a>
        </div>
    </section>

    <section id="platform" class="bg-tone" aria-labelledby="rt-title">
        <div class="sec wrap">
            <div class="grid gap-10 md:grid-cols-12 md:items-end">
                <div class="md:col-span-7">
                    <h2 id="rt-title" class="t-head max-w-[18ch]"><span class="line"><span>{{ $c['realtime']['title'] }}</span></span></h2>
                </div>
                <div class="md:col-span-5">
                    <p class="rt-in will-rise text-soft">{{ $c['realtime']['text'] }}</p>
                </div>
            </div>

            <dl class="mt-12 grid gap-4 sm:grid-cols-3">
                @foreach ($c['realtime']['stats'] as $s)
                    <div class="stat card will-rise flex items-baseline gap-4 p-6">
                        <dd class="text-[clamp(2.5rem,4vw,3.5rem)] leading-none font-semibold tracking-[-0.045em] tabular-nums"><span class="count" data-to="{{ $s['value'] }}">{{ $s['value'] }}</span><span class="text-accent">{{ $s['suffix'] }}</span></dd>
                        <dt class="text-soft leading-snug">{{ $s['label'] }}</dt>
                    </div>
                @endforeach
            </dl>

            <div class="viewer mt-10" data-viewer>
                <div class="flex flex-wrap gap-2" role="tablist" aria-label="Ethaika screens">
                    @foreach ($c['realtime']['screens'] as $i => $s)
                        <button
                            type="button"
                            role="tab"
                            id="vt-{{ $i }}"
                            aria-controls="vp-{{ $i }}"
                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                            data-i="{{ $i }}"
                            class="v-tab text-soft relative overflow-hidden rounded-[12px] border border-[var(--color-line)] bg-white px-4 py-2.5 text-[0.9375rem] font-semibold transition-colors hover:text-ink aria-selected:border-ink aria-selected:text-ink"
                        >
                            {{ $s['name'] }}
                            <span class="v-bar bg-accent absolute inset-x-0 bottom-0 h-[2px] origin-left scale-x-0" aria-hidden="true"></span>
                        </button>
                    @endforeach
                </div>
                <div class="shot mt-4">
                    <div class="grid">
                        @foreach ($c['realtime']['screens'] as $i => $s)
                            <x-picture
                                :src="$s['src']"
                                :alt="$s['alt']"
                                sizes="(min-width: 1024px) 62vw, 92vw"
                                id="vp-{{ $i }}"
                                role="tabpanel"
                                aria-labelledby="vt-{{ $i }}"
                                class="v-panel col-start-1 row-start-1 aspect-[16/9] w-full object-cover object-top transition-opacity duration-500 {{ $i > 0 ? 'opacity-0' : '' }}"
                            />
                        @endforeach
                    </div>
                </div>
                <div class="mt-8 flex justify-center">
                    <a href="#contact" class="btn rt-in will-rise">{{ $c['realtime']['action'] }} {!! $arrow !!}</a>
                </div>
            </div>
        </div>
    </section>

    <section id="integrations" class="sec wrap grid gap-12 md:grid-cols-12 md:items-center" aria-labelledby="int-title">
        <div class="md:col-span-6">
            <h2 id="int-title" class="t-head"><span class="line"><span>{{ $c['integrations']['title'] }}</span></span></h2>
            @foreach ($c['integrations']['text'] as $p)
                <p class="int-in will-rise text-soft mt-5">{{ $p }}</p>
            @endforeach
            <a href="#contact" class="int-in will-rise btn mt-8">{{ $c['integrations']['action'] }} {!! $arrow !!}</a>
        </div>
        <ul class="card int-grid grid grid-cols-2 overflow-hidden md:col-span-6 md:col-start-7 sm:grid-cols-3">
            @foreach ($c['integrations']['logos'] as $i => $logo)
                <li class="logo will-rise group border-line flex aspect-square flex-col items-center justify-center gap-4 border-r border-b p-5 text-center">
                    <img src="{{ asset($logo['src']) }}" alt="" {!! \App\Support\ResponsiveImage::dimensions($logo['src']) !!} loading="lazy" decoding="async" @class(['h-12 object-contain transition-transform duration-500 ease-[cubic-bezier(.2,.8,.2,1)] group-hover:-translate-y-1 group-hover:scale-105', 'w-12 object-left object-cover' => $logo['crop'], 'w-auto' => ! $logo['crop'], 'invert' => $logo['invert']]) />
                    <span class="font-semibold">{{ $logo['name'] }}</span>
                </li>
            @endforeach
            <li class="logo will-rise bg-accent-tint text-accent-ink border-line flex aspect-square flex-col items-center justify-center gap-2 border-r border-b p-5 text-center">
                <span class="text-[2rem] leading-none font-semibold">+</span>
                <span class="text-[0.875rem] font-semibold">Standard telemetry &amp; integration protocols</span>
            </li>
        </ul>
    </section>

    <section id="features" class="bg-tone" aria-labelledby="feat-title">
        <div class="sec wrap">
            <div class="mx-auto max-w-[760px] text-center">
                <h2 id="feat-title" class="t-head"><span class="line"><span>{{ $c['features']['title'] }}</span></span></h2>
            </div>

            <div class="mt-12 grid gap-4 lg:grid-cols-3">
                <article class="feat card will-rise grid overflow-hidden lg:col-span-2 lg:grid-cols-2">
                    <div class="p-8">
                        <h3 class="feat-h">{{ $f[0]['name'] }}</h3>
                        @foreach ($f[0]['body'] as $p)<p class="text-soft mt-3">{{ $p }}</p>@endforeach
                    </div>
                    <div class="relative min-h-[260px] overflow-hidden">
                        <x-picture :src="$c['photos']['team']['src']" :alt="$c['photos']['team']['alt']" sizes="(min-width: 1024px) 40vw, 92vw" class="feat-img absolute inset-0 size-full scale-110 object-cover" />
                    </div>
                </article>
                @foreach ([1, 2, 3, 4] as $k)
                    <article class="feat card will-rise p-8">
                        <h3 class="feat-h">{{ $f[$k]['name'] }}</h3>
                        @foreach ($f[$k]['body'] as $p)<p class="text-soft mt-3">{{ $p }}</p>@endforeach
                    </article>
                @endforeach
                <article class="feat card will-rise grid gap-3 p-8 lg:col-span-3 lg:grid-cols-[1fr_2fr] lg:items-center">
                    <h3 class="feat-h">{{ $f[5]['name'] }}</h3>
                    @foreach ($f[5]['body'] as $p)<p class="text-soft">{{ $p }}</p>@endforeach
                </article>
            </div>

            <div class="cmp card will-rise mt-4 overflow-x-auto overflow-y-hidden">
                <table class="w-full min-w-[860px] border-collapse text-left text-[0.9375rem]">
                    <caption class="sr-only">Ethaika compared with vendor tools, evaluation frameworks and observability tools</caption>
                    <thead>
                        <tr>
                            @foreach ($c['features']['compare']['columns'] as $col)
                                <th scope="col" @class(['px-6 py-5 font-semibold', 'bg-accent text-white' => $loop->last, 'sticky left-0 z-10 bg-white' => $loop->first, 'text-soft' => ! $loop->first && ! $loop->last])>{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($c['features']['compare']['rows'] as $row)
                            <tr class="cmp-row border-line border-t">
                                <th scope="row" class="sticky left-0 z-10 bg-white px-6 py-4 font-semibold">{{ $row[0] }}</th>
                                @foreach (array_slice($row, 1) as $cell)
                                    <td @class(['px-6 py-4 align-top', 'bg-accent-tint text-accent-ink font-semibold' => $loop->last, 'text-soft' => ! $loop->last])>
                                        <span class="flex items-start gap-2"><span @class(['mt-[3px]', 'text-accent' => $cell[0] === 'yes', 'text-fade' => $cell[0] !== 'yes'])>{!! $mark[$cell[0]][0] !!}</span><span><span class="sr-only">{{ $mark[$cell[0]][1] }}: </span>{{ $cell[1] }}</span></span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @include('website.partials.faq')

    <section id="contact" class="wrap pb-[clamp(72px,8vw,120px)]" aria-labelledby="contact-title">
        <div class="bg-accent-tint grid gap-10 overflow-hidden rounded-[28px] p-6 md:grid-cols-12 md:p-12">
            <div class="md:col-span-5 md:py-4">
                <h2 id="contact-title" class="t-head"><span class="line"><span>{{ $c['contact']['title'] }}</span></span></h2>
                <p class="contact-in will-rise text-soft mt-5 max-w-[36ch]">{{ $c['contact']['text'] }}</p>
                <figure class="contact-in will-rise mt-8 hidden overflow-hidden rounded-[var(--radius)] md:block">
                    <x-picture :src="$c['photos']['engineers']['src']" :alt="$c['photos']['engineers']['alt']" sizes="(min-width: 768px) 36vw, 92vw" class="aspect-[4/3] w-full object-cover" />
                </figure>
            </div>

            <div class="contact-in will-rise card p-6 md:col-span-7 md:p-10">
                @include('website.partials.contact-form', ['from' => 'home'])
            </div>
        </div>
    </section>
</x-website>
