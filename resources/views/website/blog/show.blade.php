@php
    $category = $blog->categories->first();
    $image = $blog->imageUrl();
@endphp

<x-website
    :title="$blog->title . ' · Ethaika'"
    :description="$blog->summary(160)"
    :image="$blog->featured_image"
    type="article"
    :published="optional($blog->published_at)->toIso8601String()"
    :modified="optional($blog->updated_at)->toIso8601String()"
    :breadcrumbs="[['name' => 'Blog', 'url' => route('blog.index')], ['name' => $blog->title, 'url' => $blog->url()]]"
    :schema="[[
        '@type' => 'BlogPosting',
        'headline' => $blog->title,
        'description' => $blog->summary(200),
        'image' => $image,
        'datePublished' => optional($blog->published_at)->toIso8601String(),
        'dateModified' => optional($blog->updated_at)->toIso8601String(),
        'author' => ['@type' => 'Organization', 'name' => 'Ethaika'],
        'publisher' => ['@id' => url('/') . '#organization'],
        'mainEntityOfPage' => $blog->url(),
    ]]"
>
    <article>
        <header class="relative overflow-hidden pt-[calc(72px+clamp(40px,7vh,80px))]">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-full bg-[radial-gradient(60%_60%_at_20%_0%,#e7eeff_0%,#ffffff_70%)]" aria-hidden="true"></div>
            <div class="wrap grid lg:grid-cols-12">
                <div class="lg:col-span-10 xl:col-span-9 xl:col-start-2">
                    <p class="hero-in will-rise text-soft flex flex-wrap items-center gap-x-3 gap-y-1 text-[0.9375rem] font-medium">
                        <a href="{{ route('blog.index') }}" class="ulink">Blog</a>
                        @if ($category)
                            <span aria-hidden="true">/</span>
                            <a href="{{ route('blog.category', $category->slug) }}" class="text-accent ulink font-semibold">{{ $category->name }}</a>
                        @endif
                    </p>
                    <h1 id="page-title" class="mt-5 max-w-[22ch] text-[clamp(2.25rem,4.4vw,4rem)] leading-[1.04] font-semibold tracking-[-0.04em]">
                        <span class="line"><span>{{ $blog->title }}</span></span>
                    </h1>
                    @if ($blog->excerpt)
                        <p class="hero-in will-rise text-soft t-lead mt-5 max-w-[60ch]">{{ $blog->excerpt }}</p>
                    @endif
                    <p class="hero-in will-rise text-soft mt-6 flex flex-wrap items-center gap-x-3 text-[0.9375rem]">
                        <span>Published <time class="text-ink font-semibold" datetime="{{ optional($blog->published_at)->toDateString() }}">{{ optional($blog->published_at)->format('F j, Y') }}</time></span>
                        <span aria-hidden="true">·</span>
                        <span>{{ $blog->readingMinutes() }} min read</span>
                    </p>
                </div>
                @if ($image)
                    <figure class="hero-shot shot mt-[clamp(40px,6vh,64px)] origin-[0%_0%] lg:col-span-12 xl:col-span-11 xl:col-start-2">
                        <x-picture :src="$blog->featured_image" location="storage" sizes="(min-width: 1280px) 1180px, 94vw" priority class="aspect-[16/9] object-cover" />
                    </figure>
                @endif
            </div>
        </header>

        <div class="wrap grid gap-12 py-[clamp(56px,7vw,96px)] lg:grid-cols-12">
            <div class="prose-eth lg:col-span-8 lg:col-start-1 xl:col-span-7 xl:col-start-2">
                {!! \App\Support\ResponsiveImage::enhanceHtml($blog->content) !!}
            </div>

            <aside class="lg:col-span-4 xl:col-span-3 xl:col-start-10">
                <div class="lg:sticky lg:top-28">
                    <div class="bg-accent-tint rounded-[var(--radius)] p-7">
                        <p class="text-[1.375rem] leading-tight font-semibold tracking-[-0.02em]">Book your demo today.</p>
                        <p class="text-soft mt-3">Simulate real user conversations with AI systems to test behavior, responses, and edge cases before production.</p>
                        <a href="{{ route('contact') }}" class="btn mt-6 w-full">Book Your Demo</a>
                    </div>
                    @if ($blog->categories->isNotEmpty())
                        <div class="mt-6">
                            <p class="text-soft text-[0.875rem] font-semibold">Filed under</p>
                            <p class="mt-3 flex flex-wrap gap-2">
                                @foreach ($blog->categories as $cat)
                                    <a href="{{ route('blog.category', $cat->slug) }}" class="bg-tone hover:bg-accent-tint hover:text-accent-ink rounded-full px-3 py-1 text-[0.875rem] font-semibold transition-colors">{{ $cat->name }}</a>
                                @endforeach
                            </p>
                        </div>
                    @endif
                    <div class="mt-6">
                        <p class="text-soft text-[0.875rem] font-semibold">Share</p>
                        <p class="mt-3 flex flex-wrap gap-2">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($blog->url()) }}" target="_blank" rel="noopener" class="bg-tone hover:bg-accent-tint hover:text-accent-ink rounded-full px-3 py-1 text-[0.875rem] font-semibold transition-colors">LinkedIn</a>
                            <a href="https://x.com/intent/post?url={{ urlencode($blog->url()) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener" class="bg-tone hover:bg-accent-tint hover:text-accent-ink rounded-full px-3 py-1 text-[0.875rem] font-semibold transition-colors">X</a>
                            <a href="mailto:?subject={{ rawurlencode($blog->title) }}&body={{ rawurlencode($blog->url()) }}" class="bg-tone hover:bg-accent-tint hover:text-accent-ink rounded-full px-3 py-1 text-[0.875rem] font-semibold transition-colors">Email</a>
                        </p>
                    </div>
                </div>
            </aside>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="bg-tone" aria-labelledby="related-title">
            <div class="sec wrap">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <h2 id="related-title" class="t-head"><span class="line"><span>Keep reading</span></span></h2>
                    <a href="{{ route('blog.index') }}" class="btn btn-line">All articles</a>
                </div>
                <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $post)
                        @include('website.partials.post-card', ['post' => $post, 'heading' => 'h3'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="pt-[clamp(72px,8vw,120px)]">
        @include('website.partials.cta', [
            'title' => config('ethaika.platform.cta.title'),
            'text' => config('ethaika.platform.cta.text'),
            'facts' => config('ethaika.platform.cta.facts'),
        ])
    </div>
</x-website>
