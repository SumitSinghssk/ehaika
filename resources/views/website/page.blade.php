<x-website
    :title="$page->title . ' · Ethaika'"
    :description="\Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $page->content))), 160)"
    :image="$page->featured_image"
    :breadcrumbs="[['name' => $page->title, 'url' => route('page.show', $page->slug)]]"
    :modified="optional($page->updated_at)->toIso8601String()"
>
    <header class="relative overflow-hidden pt-[calc(72px+clamp(48px,8vh,88px))] pb-[clamp(40px,5vw,64px)]">
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-full bg-[radial-gradient(60%_60%_at_50%_0%,#e7eeff_0%,#ffffff_70%)]" aria-hidden="true"></div>
        <div class="wrap text-center">
            <h1 id="page-title" class="mx-auto max-w-[22ch] text-[clamp(2.5rem,5vw,4.5rem)] leading-[1] font-semibold tracking-[-0.045em]">
                <span class="line"><span>{{ $page->title }}</span></span>
            </h1>
            @if ($page->updated_at)
                <p class="hero-in will-rise text-soft mt-5">Last updated <time class="text-ink font-semibold" datetime="{{ $page->updated_at->toDateString() }}">{{ $page->updated_at->format('F j, Y') }}</time></p>
            @endif
        </div>
    </header>

    @if ($page->featured_image)
        <div class="wrap pb-10">
            <figure class="shot mx-auto max-w-[1080px]"><img src="{{ media_url($page->featured_image) }}" alt="" /></figure>
        </div>
    @endif

    <div class="wrap pb-[clamp(72px,8vw,120px)]">
        <div class="ps-content prose-eth mx-auto max-w-[760px]">
            {!! $page->content !!}
        </div>
    </div>

    @include('website.partials.cta', [
        'title' => config('ethaika.platform.cta.title'),
        'text' => config('ethaika.platform.cta.text'),
    ])
</x-website>
