@php
    $p = config('ethaika.platform');
    $mark = [
        'yes' => '<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3.5 8.5 3 3 6-7"/></svg>',
        'part' => '<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 8h8"/></svg>',
        'no' => '<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m4.5 4.5 7 7m0-7-7 7"/></svg>',
    ];
    $check = $mark['yes'];
@endphp

<x-website title="Platform · Enterprise AI Decision Intelligence · Ethaika" :description="$p['meta']" :breadcrumbs="[['name' => 'Platform', 'url' => route('platform')]]">
    @include('website.partials.page-hero', [
        'title' => $p['title'],
        'text' => $p['intro'],
        'primary' => ['Schedule a Demo', route('contact')],
        'secondary' => ['Explore Features', route('features')],
    ])

    <section class="wrap pb-[clamp(72px,8vw,120px)]" aria-label="The questions enterprises are asking">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($p['questions'] as $q)
                <article class="tile card will-rise flex flex-col p-7">
                    <h2 class="text-[1.25rem] leading-tight font-semibold tracking-[-0.02em]">{{ $q['q'] }}</h2>
                    <p class="text-soft mt-2">{{ $q['a'] }}</p>
                    <p class="mt-auto flex flex-wrap gap-2 pt-6">
                        @foreach ($q['tags'] as $tag)
                            <span class="bg-accent-tint text-accent-ink rounded-full px-3 py-1 text-[0.8125rem] font-semibold">{{ $tag }}</span>
                        @endforeach
                    </p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-tone" aria-labelledby="hidden-title">
        <div class="sec wrap">
            <div class="grid gap-6 md:grid-cols-12 md:items-end">
                <h2 id="hidden-title" class="t-head md:col-span-6"><span class="line"><span>{{ $p['hidden']['title'] }}</span></span></h2>
                <p class="will-rise text-soft t-lead md:col-span-6">{{ $p['hidden']['text'] }}</p>
            </div>
            <div class="mt-12 grid gap-4 md:grid-cols-3">
                @foreach ($p['hidden']['items'] as $item)
                    <article class="tile card will-rise p-8">
                        <h3 class="feat-h">{{ $item['name'] }}</h3>
                        <ul class="text-soft mt-4 grid gap-2">
                            @foreach ($item['lines'] as $line)
                                <li class="flex items-start gap-2"><span class="text-accent mt-[3px]">{!! $check !!}</span>{{ $line }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
            <div class="will-rise mt-10 max-w-[68ch]">
                @foreach ($p['hidden']['after'] as $line)
                    <p @class(['t-lead font-semibold' => $loop->first, 'text-soft mt-3' => ! $loop->first])>{{ $line }}</p>
                @endforeach
            </div>
        </div>
    </section>

    <section class="sec wrap" aria-labelledby="cost-title">
        <div class="mx-auto max-w-[760px] text-center">
            <h2 id="cost-title" class="t-head"><span class="line"><span>{{ $p['cost']['title'] }}</span></span></h2>
            <p class="will-rise text-soft t-lead mt-5">{{ $p['cost']['text'] }}</p>
        </div>
        <div class="card mt-12 grid overflow-hidden sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($p['cost']['items'] as $i => $item)
                <article class="tile will-rise border-line border-b p-8 sm:border-r">
                    <h3 class="feat-h">{{ $item['name'] }}</h3>
                    <p class="text-soft mt-3">{{ $item['text'] }}</p>
                </article>
            @endforeach
        </div>
        <p class="will-rise mx-auto mt-10 max-w-[62ch] text-center font-semibold">{{ $p['cost']['after'] }}</p>
    </section>

    <section class="bg-tone" aria-labelledby="gaps-title">
        <div class="sec wrap grid gap-12 md:grid-cols-12">
            <div class="md:col-span-5">
                <div class="md:sticky md:top-28">
                    <h2 id="gaps-title" class="t-head"><span class="line"><span>{{ $p['gaps']['title'] }}</span></span></h2>
                    <p class="will-rise text-soft t-lead mt-5">{{ $p['gaps']['text'] }}</p>
                </div>
            </div>
            <div class="grid gap-4 md:col-span-7">
                @foreach ($p['gaps']['items'] as $item)
                    <article class="tile card will-rise grid gap-2 p-7 sm:grid-cols-[1fr_1.6fr] sm:gap-8">
                        <h3 class="feat-h">{{ $item['name'] }}</h3>
                        <p class="text-soft">{{ $item['text'] }}</p>
                    </article>
                @endforeach
                <p class="will-rise bg-accent text-white mt-2 rounded-[var(--radius)] p-7 font-semibold">{{ $p['gaps']['after'] }}</p>
            </div>
        </div>
    </section>

    <section id="capabilities" class="sec wrap" aria-labelledby="enter-title">
        <div class="mx-auto max-w-[860px] text-center">
            <h2 id="enter-title" class="t-head"><span class="line"><span>{{ $p['enter']['title'] }}</span></span></h2>
            <p class="will-rise text-soft t-lead mt-5">{{ $p['enter']['text'] }}</p>
        </div>
        <div class="viewer mt-12" data-viewer>
            <div class="flex flex-wrap justify-center gap-2" role="tablist" aria-label="Ethaika capabilities">
                @foreach ($p['enter']['tabs'] as $i => $t)
                    <button type="button" role="tab" id="vt-{{ $i }}" aria-controls="vp-{{ $i }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" data-i="{{ $i }}" class="v-tab text-soft relative overflow-hidden rounded-[12px] border border-[var(--color-line)] bg-white px-4 py-2.5 text-[0.9375rem] font-semibold transition-colors hover:text-ink aria-selected:border-ink aria-selected:text-ink">
                        {{ $t['name'] }}
                        <span class="v-bar bg-accent absolute inset-x-0 bottom-0 h-[2px] origin-left scale-x-0" aria-hidden="true"></span>
                    </button>
                @endforeach
            </div>
            <div class="mt-6 grid">
                @foreach ($p['enter']['tabs'] as $i => $t)
                    <div id="vp-{{ $i }}" role="tabpanel" aria-labelledby="vt-{{ $i }}" @if ($i) inert @endif @class(['v-panel col-start-1 row-start-1 grid gap-8 transition-opacity duration-500 lg:grid-cols-12 lg:items-center', 'opacity-0 pointer-events-none' => $i > 0])>
                        <div class="lg:col-span-4">
                            <h3 class="text-[1.625rem] leading-tight font-semibold tracking-[-0.025em]">{{ $t['title'] }}</h3>
                            <p class="text-soft mt-3">{{ $t['text'] }}</p>
                            <ul class="mt-6 grid gap-3">
                                @foreach ($t['points'] as $pt)
                                    <li class="flex items-start gap-2.5"><span class="text-accent mt-[3px]">{!! $check !!}</span><span>{{ $pt }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                        <figure class="shot lg:col-span-8"><x-picture :src="$t['image']" :alt="'Ethaika ' . $t['title']" sizes="(min-width: 1024px) 64vw, 92vw" /></figure>
                    </div>
                @endforeach
            </div>
        </div>
        <p class="will-rise mt-12 text-center text-[clamp(1.375rem,2.4vw,2rem)] font-semibold tracking-[-0.025em]">{{ $p['enter']['after'] }}</p>
    </section>

    <section class="bg-tone" aria-labelledby="how-title">
        <div class="sec wrap grid gap-12 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-5">
                <h2 id="how-title" class="t-head"><span class="line"><span>{{ $p['how']['title'] }}</span></span></h2>
                <p class="will-rise text-soft t-lead mt-5">{{ $p['how']['text'] }}</p>
                <div class="mt-8 grid gap-3">
                    @foreach ($p['how']['pillars'] as $pl)
                        <div class="tile card will-rise p-6">
                            <h3 class="feat-h">{{ $pl['name'] }}</h3>
                            <p class="text-soft mt-2">{{ $pl['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <figure class="shot will-rise lg:col-span-7"><x-picture :src="$p['how']['image']" alt="Ethaika platform architecture: users, scaled simulation, cross-platform evaluation and reports, connected to enterprise agents and the foundation AI layer through secured config" sizes="(min-width: 1024px) 56vw, 92vw" /></figure>
        </div>
    </section>

    <section class="sec wrap" aria-labelledby="now-title">
        <div class="mx-auto max-w-[760px] text-center">
            <h2 id="now-title" class="t-head"><span class="line"><span>{{ $p['now']['title'] }}</span></span></h2>
            <p class="will-rise text-soft t-lead mt-5">{{ $p['now']['text'] }}</p>
        </div>
        <div class="mt-12 grid gap-4 md:grid-cols-3">
            @foreach ($p['now']['items'] as $item)
                <article class="tile card will-rise p-8">
                    <span class="bg-accent block h-[3px] w-10" aria-hidden="true"></span>
                    <h3 class="feat-h mt-5">{{ $item['name'] }}</h3>
                    <p class="text-soft mt-3">{{ $item['text'] }}</p>
                </article>
            @endforeach
        </div>
        <p class="will-rise mt-10 text-center font-semibold">{{ $p['now']['after'] }}</p>
    </section>

    <section class="bg-tone" aria-labelledby="wins-title">
        <div class="sec wrap">
            <div class="mx-auto max-w-[760px] text-center">
                <h2 id="wins-title" class="t-head"><span class="line"><span>{{ $p['wins']['title'] }}</span></span></h2>
                <p class="will-rise text-soft t-lead mt-5">{{ $p['wins']['text'] }}</p>
            </div>
            <dl class="card will-rise mt-12 grid overflow-hidden sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($p['wins']['stats'] as $s)
                    <div @class(['p-8', 'border-line border-t sm:border-t-0 sm:border-l' => ! $loop->first])>
                        <dd class="text-accent text-[clamp(2.75rem,4.5vw,4rem)] leading-none font-semibold tracking-[-0.045em]">{{ $s['value'] }}</dd>
                        <dt class="mt-4"><span class="block font-semibold">{{ $s['label'] }}</span><span class="text-soft mt-1 block">{{ $s['text'] }}</span></dt>
                    </div>
                @endforeach
            </dl>
            <div class="cmp card will-rise mt-4 overflow-x-auto">
                <table class="w-full min-w-[860px] border-collapse text-left text-[0.9375rem]">
                    <caption class="sr-only">Ethaika compared with vendor tools, evaluation frameworks and observability tools</caption>
                    <thead>
                        <tr>
                            @foreach (config('ethaika.features.compare.columns') as $col)
                                <th scope="col" @class(['px-6 py-5 font-semibold', 'bg-accent text-white' => $loop->last, 'sticky left-0 z-10 bg-white' => $loop->first, 'text-soft' => ! $loop->first && ! $loop->last])>{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (config('ethaika.features.compare.rows') as $row)
                            <tr class="cmp-row border-line border-t">
                                <th scope="row" class="sticky left-0 z-10 bg-white px-6 py-4 font-semibold">{{ $row[0] }}</th>
                                @foreach (array_slice($row, 1) as $cell)
                                    <td @class(['px-6 py-4 align-top', 'bg-accent-tint text-accent-ink font-semibold' => $loop->last, 'text-soft' => ! $loop->last])>
                                        <span class="flex items-start gap-2"><span @class(['mt-[3px]', 'text-accent' => $cell[0] === 'yes', 'text-fade' => $cell[0] !== 'yes'])>{!! $mark[$cell[0]] !!}</span>{{ $cell[1] }}</span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="will-rise mt-10 text-center font-semibold">{{ $p['wins']['after'] }}</p>
        </div>
    </section>

    <section class="sec wrap" aria-labelledby="vision-title">
        <div class="grid gap-12 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-6">
                <h2 id="vision-title" class="t-head"><span class="line"><span>{{ $p['vision']['title'] }}</span></span></h2>
                <dl class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="tile card will-rise p-6"><dt class="text-soft text-[0.875rem] font-semibold">Today</dt><dd class="mt-2 text-[1.25rem] font-semibold tracking-[-0.02em]">{{ $p['vision']['today'] }}</dd></div>
                    <div class="tile will-rise bg-accent rounded-[var(--radius)] p-6 text-white"><dt class="text-[0.875rem] font-semibold text-white/80">Tomorrow</dt><dd class="mt-2 text-[1.25rem] font-semibold tracking-[-0.02em]">{{ $p['vision']['tomorrow'] }}</dd></div>
                </dl>
            </div>
            <div class="lg:col-span-6">
                <blockquote class="will-rise text-[clamp(1.375rem,2.2vw,1.875rem)] leading-snug font-semibold tracking-[-0.02em]">“{{ $p['vision']['quote'] }}”</blockquote>
                <ol class="mt-8 grid gap-3 sm:grid-cols-3">
                    @foreach ($p['vision']['steps'] as [$step, $line])
                        <li class="tile card will-rise p-5"><span class="block font-semibold">{{ $step }}</span><span class="text-soft text-[0.9375rem]">{{ $line }}</span></li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    @include('website.partials.cta', [
        'title' => $p['cta']['title'],
        'text' => $p['cta']['text'],
        'tagline' => $p['cta']['tagline'],
        'action' => $p['cta']['action'],
        'facts' => $p['cta']['facts'],
    ])
</x-website>
