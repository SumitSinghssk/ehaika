@php
    $c = config('ethaika');
    $a = $c['about'];
    $check = '<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3.5 8.5 3 3 6-7"/></svg>';
@endphp

<x-website title="About · Ethaika" :description="$a['meta']" :breadcrumbs="[['name' => 'About', 'url' => route('about')]]">
    @include('website.partials.page-hero', [
        'title' => $a['title'],
        'text' => [$a['text']],
        'primary' => ['Schedule Demo', route('contact')],
        'secondary' => ['Explore Features', route('features')],
    ])

    <section class="wrap pb-[clamp(72px,8vw,120px)]" aria-label="The Ethaika team at work">
        <div class="grid gap-4 md:grid-cols-12">
            <figure class="photo will-rise relative overflow-hidden rounded-[calc(var(--radius)+4px)] md:col-span-7">
                <x-picture :src="$c['photos']['team']['src']" :alt="$c['photos']['team']['alt']" sizes="(min-width: 768px) 56vw, 92vw" class="aspect-[16/11] w-full scale-110 object-cover" />
            </figure>
            <figure class="photo will-rise relative overflow-hidden rounded-[calc(var(--radius)+4px)] md:col-span-5">
                <x-picture :src="$c['photos']['engineers']['src']" :alt="$c['photos']['engineers']['alt']" sizes="(min-width: 768px) 40vw, 92vw" class="aspect-[16/11] w-full scale-110 object-cover md:h-full md:aspect-auto" />
            </figure>
        </div>
    </section>

    <section class="bg-tone" aria-labelledby="values-title">
        <div class="sec wrap">
            <div class="mx-auto max-w-[760px] text-center">
                <h2 id="values-title" class="t-head"><span class="line"><span>{{ $a['note_title'] }}</span></span></h2>
            </div>
            <div class="mt-12 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($a['values'] as $v)
                    <article class="feat card will-rise p-8">
                        <span class="bg-accent block h-[3px] w-10" aria-hidden="true"></span>
                        <h3 class="feat-h mt-6">{{ $v['name'] }}</h3>
                        @foreach ($v['text'] as $p)
                            <p @class(['mt-3', 'font-semibold' => $loop->first && count($v['text']) > 1, 'text-soft' => ! ($loop->first && count($v['text']) > 1)])>{{ $p }}</p>
                        @endforeach
                        @isset($v['list'])
                            <ul class="text-soft mt-3 grid gap-2">
                                @foreach ($v['list'] as $li)
                                    <li class="flex items-start gap-2"><span class="text-accent mt-[3px]">{!! $check !!}</span>{{ $li }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-3 font-semibold">{{ $v['after'] }}</p>
                        @endisset
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="sec wrap" aria-labelledby="feat-title">
        <div class="grid gap-6 md:grid-cols-12 md:items-end">
            <h2 id="feat-title" class="t-head md:col-span-7"><span class="line"><span>{{ $c['features']['title'] }}</span></span></h2>
            <div class="md:col-span-5 md:justify-self-end">
                <a href="{{ route('features') }}" class="will-rise btn btn-line">Explore Features</a>
            </div>
        </div>
        <div class="card mt-10 grid overflow-hidden sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($c['features']['items'] as $item)
                <article class="tile will-rise border-line border-b p-8 sm:border-r">
                    <h3 class="feat-h">{{ $item['name'] }}</h3>
                    <p class="text-soft mt-3">{{ $item['body'][0] }}</p>
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
