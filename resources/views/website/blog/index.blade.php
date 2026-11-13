@php
    $b = config('ethaika.blog');
    $title = $category ? $category->name . ' · Blog · Ethaika' : 'Blog & News · Ethaika';
    $arrow = '<svg class="size-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5"/></svg>';
    $pill = 'inline-flex items-center gap-2 rounded-[10px] border px-3.5 py-1.5 text-[0.875rem] font-medium transition-colors';
    $pillOn = 'border-ink bg-ink text-paper';
    $pillOff = 'border-line text-soft hover:text-ink hover:border-[color-mix(in_srgb,var(--color-ink)_30%,transparent)]';
    $byline = fn ($post) => $post->author && ! str_contains(strtolower($post->author->name), 'admin') ? $post->author->name : \App\Helpers\Settings::appName() . ' Team';
@endphp

<x-website
    :title="$title"
    :description="$category?->description ?: $b['meta']"
    :noindex="$search !== ''"
    :breadcrumbs="array_values(array_filter([['name' => 'Blog', 'url' => route('blog.index')], $category ? ['name' => $category->name, 'url' => route('blog.category', $category->slug)] : null]))"
>
    <section class="pt-[calc(72px+clamp(28px,4vw,48px))] pb-[clamp(72px,8vw,112px)]" aria-labelledby="blog-title">
        <div class="wrap">
            <nav class="text-fade flex items-center gap-2 text-[0.875rem]" aria-label="Breadcrumb">
                <a href="{{ route('blog.index') }}" class="hover:text-ink transition-colors">Blog</a>
                <span aria-hidden="true">/</span>
                <span class="text-soft">{{ $category ? $category->name : ($search !== '' ? 'Search' : 'Latest') }}</span>
            </nav>
            <h1 id="blog-title" class="sr-only">{{ $category ? $category->name : $b['title'] }}</h1>

            @if ($featured)
                <a href="{{ $featured->url() }}" class="hero-in group border-line bg-tone mt-6 grid overflow-hidden rounded-[20px] border lg:grid-cols-2">
                    <div class="relative overflow-hidden">
                        @if ($featured->imageUrl())
                            <x-picture :src="$featured->featured_image" location="storage" sizes="(min-width: 1024px) 50vw, 94vw" priority class="aspect-[16/10] size-full object-cover transition-transform duration-[1.2s] ease-[cubic-bezier(.2,.8,.2,1)] group-hover:scale-[1.03]" />
                        @endif
                    </div>
                    <div class="flex flex-col justify-center p-7 md:p-10">
                        <p class="flex flex-wrap items-center gap-2 text-[0.8125rem]">
                            <span class="bg-ink text-paper rounded-[8px] px-2.5 py-1 font-medium">Featured</span>
                            @if ($featured->categories->isNotEmpty())
                                <span class="border-line text-soft rounded-[8px] border px-2.5 py-1">{{ $featured->categories->first()->name }}</span>
                            @endif
                            <span class="text-fade">{{ $featured->readingMinutes() }} min read</span>
                        </p>
                        <h2 class="group-hover:text-accent mt-5 text-[clamp(1.75rem,2.6vw,2.25rem)] leading-[1.22] font-semibold tracking-[-0.025em] transition-colors">{{ $featured->title }}</h2>
                        <p class="text-soft mt-4 text-[1.0625rem] leading-relaxed">{{ $featured->summary(190) }}</p>
                        <p class="text-fade mt-6 flex flex-wrap items-center gap-x-2 text-[0.875rem]">
                            <span class="text-ink font-medium">{{ $byline($featured) }}</span>
                            <span aria-hidden="true">·</span>
                            <time datetime="{{ optional($featured->published_at)->toDateString() }}">{{ optional($featured->published_at)->format('M j, Y') }}</time>
                        </p>
                        <span class="mt-7 inline-flex items-center gap-2 text-[0.9375rem] font-semibold">Read article {!! $arrow !!}</span>
                    </div>
                </a>
            @endif

            <div class="hero-in border-line mt-10 flex flex-col gap-4 border-b pb-8 lg:flex-row lg:items-center">
                <form method="GET" action="{{ $category ? route('blog.category', $category->slug) : route('blog.index') }}" role="search" class="relative lg:w-72">
                    <label for="blog-q" class="sr-only">Search articles</label>
                    <svg class="text-fade pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="7" cy="7" r="4.75" /><path d="m10.5 10.5 3.5 3.5" /></svg>
                    <input id="blog-q" name="q" type="search" value="{{ $search }}" placeholder="Search {{ $total }} {{ \Illuminate\Support\Str::plural('article', $total) }}…" class="border-line bg-paper placeholder:text-fade focus:border-accent w-full rounded-[10px] border py-2.5 pr-3 pl-10 text-[0.9375rem] outline-none transition-colors" />
                </form>
                @if ($categories->isNotEmpty())
                    <nav class="flex flex-wrap gap-2" aria-label="Categories">
                        <a href="{{ route('blog.index') }}" class="{{ $pill }} {{ $category ? $pillOff : $pillOn }}" @if (! $category) aria-current="page" @endif>All <span class="opacity-60">{{ $total }}</span></a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('blog.category', $cat->slug) }}" class="{{ $pill }} {{ $category?->is($cat) ? $pillOn : $pillOff }}" @if ($category?->is($cat)) aria-current="page" @endif>{{ $cat->name }} <span class="opacity-60">{{ $cat->blogs_count }}</span></a>
                        @endforeach
                    </nav>
                @endif
            </div>

            <div class="mt-10 flex items-baseline justify-between gap-4">
                <h2 class="text-ink text-[0.8125rem] font-semibold tracking-[0.08em] uppercase">
                    {{ $search !== '' ? 'Results for “' . $search . '”' : ($featured ? 'More articles' : 'Articles') }}
                </h2>
                @php($shown = $posts->total() - ($featured ? 1 : 0))
                <p class="text-fade text-[0.8125rem]">{{ $shown }} {{ \Illuminate\Support\Str::plural('article', $shown) }}</p>
            </div>

            @if ($grid->isEmpty() && ! $featured)
                <div class="border-line mt-6 rounded-[20px] border p-12 text-center">
                    <p class="text-[1.125rem] font-semibold">{{ $search !== '' ? 'No articles match your search.' : 'No articles yet.' }}</p>
                    <p class="text-soft mt-2">{{ $search !== '' ? 'Try another word, or browse all articles.' : 'New articles will appear here as soon as they are published.' }}</p>
                    @if ($search !== '')
                        <a href="{{ route('blog.index') }}" class="btn btn-line mt-6">All articles</a>
                    @endif
                </div>
            @elseif ($grid->isNotEmpty())
                <div class="mt-6 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($grid as $post)
                        @include('website.partials.post-card', ['post' => $post, 'priority' => ! $featured && $loop->first])
                    @endforeach
                </div>
            @endif

            @if ($posts->hasPages())
                <nav class="border-line mt-16 flex items-center justify-center gap-1.5 border-t pt-10" aria-label="Pages">
                    @if (! $posts->onFirstPage())
                        <a href="{{ $posts->previousPageUrl() }}" class="text-soft hover:text-ink grid size-10 place-items-center rounded-[10px] transition-colors" aria-label="Previous page">‹</a>
                    @endif
                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $n => $url)
                        <a href="{{ $url }}" @class(['grid size-10 place-items-center rounded-[10px] text-[0.9375rem] font-medium transition-colors', 'bg-ink text-paper' => $n === $posts->currentPage(), 'text-soft hover:text-ink hover:bg-tone' => $n !== $posts->currentPage()]) @if ($n === $posts->currentPage()) aria-current="page" @endif>{{ $n }}</a>
                    @endforeach
                    @if ($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}" class="text-soft hover:text-ink grid size-10 place-items-center rounded-[10px] transition-colors" aria-label="Next page">›</a>
                    @endif
                </nav>
            @endif
        </div>
    </section>

    @include('website.partials.cta', [
        'title' => config('ethaika.platform.cta.title'),
        'text' => config('ethaika.platform.cta.text'),
        'facts' => config('ethaika.platform.cta.facts'),
    ])
</x-website>
