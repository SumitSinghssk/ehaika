@php
    $category = $blog->categories->first();
    $image = $blog->imageUrl();
    $appName = \App\Helpers\Settings::appName();
    $author = $blog->author && ! str_contains(strtolower($blog->author->name), 'admin') ? $blog->author->name : $appName . ' Team';
    $chip = 'border-line text-soft hover:text-ink rounded-[8px] border px-2.5 py-1 text-[0.8125rem] transition-colors';
    $share = 'border-line text-soft hover:text-ink hover:bg-tone grid size-9 place-items-center rounded-[10px] border transition-colors';
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
        'author' => ['@type' => 'Organization', 'name' => $appName],
        'publisher' => ['@id' => url('/') . '#organization'],
        'mainEntityOfPage' => $blog->url(),
    ]]"
>
    <article>
        <header class="border-line border-b pt-[calc(72px+clamp(28px,4vw,56px))] pb-[clamp(40px,5vw,64px)]">
            <div class="wrap wrap-read grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
                <div>
                    <a href="{{ route('blog.index') }}" class="hero-in group text-soft hover:text-ink inline-flex items-center gap-2 text-[0.875rem] transition-colors">
                        <svg class="size-4 transition-transform duration-300 group-hover:-translate-x-1" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M13.5 8h-11M7 3.5 2.5 8 7 12.5" /></svg>
                        Back to Blog
                    </a>
                    @if ($category)
                        <p class="hero-in mt-6"><a href="{{ route('blog.category', $category->slug) }}" class="border-line text-soft hover:text-ink rounded-[8px] border px-2.5 py-1 text-[0.75rem] font-medium tracking-[0.08em] uppercase transition-colors">{{ $category->name }}</a></p>
                    @endif
                    <h1 id="page-title" class="mt-5 text-[clamp(2rem,3.4vw,2.75rem)] leading-[1.15] font-semibold tracking-[-0.025em]">
                        <span class="line"><span>{{ $blog->title }}</span></span>
                    </h1>
                    @if ($blog->excerpt)
                        <p class="hero-in text-soft mt-5 text-[1.125rem] leading-relaxed">{{ $blog->excerpt }}</p>
                    @endif
                    <p class="hero-in text-fade mt-6 flex flex-wrap items-center gap-x-2 text-[0.875rem]">
                        <time datetime="{{ optional($blog->published_at)->toDateString() }}">{{ optional($blog->published_at)->format('F j, Y') }}</time>
                        <span aria-hidden="true">·</span>
                        <span>{{ $blog->readingMinutes() }} min read</span>
                    </p>
                    @if ($blog->categories->count() > 1)
                        <p class="hero-in mt-4 flex flex-wrap gap-2">
                            @foreach ($blog->categories as $cat)
                                <a href="{{ route('blog.category', $cat->slug) }}" class="{{ $chip }}">{{ $cat->name }}</a>
                            @endforeach
                        </p>
                    @endif
                </div>
                @if ($image)
                    <figure class="hero-shot border-line overflow-hidden rounded-[18px] border">
                        <x-picture :src="$blog->featured_image" location="storage" sizes="(min-width: 1024px) 46vw, 94vw" priority class="aspect-[16/10] w-full object-cover" />
                    </figure>
                @endif
            </div>
        </header>

        <div class="wrap wrap-read grid gap-12 py-[clamp(48px,6vw,80px)] lg:grid-cols-[minmax(0,1fr)_240px] xl:gap-16">
            <div @class(['prose-eth min-w-0', 'ps-content' => str_contains((string) $blog->content, 'class="ps-')]) data-toc-source>
                {!! \App\Support\ResponsiveImage::enhanceHtml($blog->content) !!}
            </div>

            <aside class="lg:order-none" aria-label="About this article">
                <div class="toc-sticky lg:sticky">
                    <div class="flex items-center gap-3">
                        <span class="bg-accent-tint text-accent grid size-10 place-items-center rounded-full text-[0.9375rem] font-semibold" aria-hidden="true">{{ mb_strtoupper(mb_substr($author, 0, 1)) }}</span>
                        <div>
                            <p class="text-[0.9375rem] font-semibold">{{ $author }}</p>
                            <p class="text-fade text-[0.8125rem]">{{ optional($blog->published_at)->format('M j, Y') }}</p>
                        </div>
                    </div>

                    <nav class="border-line mt-6 hidden border-t pt-6 lg:block" aria-label="On this page" data-toc hidden>
                        <p class="text-fade text-[0.75rem] font-medium tracking-[0.08em] uppercase">On this page</p>
                        <ol class="border-line mt-4 grid gap-0.5 border-l text-[0.875rem]" data-toc-list></ol>
                    </nav>

                    <div class="border-line bg-tone mt-6 rounded-[16px] border p-5">
                        <p class="text-[1.0625rem] leading-snug font-semibold">Book your demo today.</p>
                        <p class="text-soft mt-2 text-[0.875rem] leading-relaxed">Simulate real user conversations with AI systems to test behavior, responses, and edge cases before production.</p>
                        <a href="{{ route('contact') }}" class="btn mt-4 !min-h-[44px] w-full">Book Your Demo</a>
                    </div>

                    <div class="mt-6">
                        <p class="text-fade text-[0.75rem] font-medium tracking-[0.08em] uppercase">Share</p>
                        <p class="mt-3 flex gap-2">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($blog->url()) }}" target="_blank" rel="noopener" class="{{ $share }}" aria-label="Share on LinkedIn"><svg class="size-4" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3 9.75h4v11H3v-11Zm6.5 0h3.8v1.5h.06c.53-1 1.83-2.06 3.77-2.06 4.03 0 4.77 2.65 4.77 6.1v5.46h-4v-4.84c0-1.16-.02-2.64-1.61-2.64-1.61 0-1.86 1.26-1.86 2.56v4.92h-4v-11Z" fill="currentColor" /></svg></a>
                            <a href="https://x.com/intent/post?url={{ urlencode($blog->url()) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener" class="{{ $share }}" aria-label="Share on X"><svg class="size-4" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.75 3h3.07l-6.7 7.66L22 21h-6.17l-4.83-6.32L5.47 21H2.4l7.17-8.2L2 3h6.33l4.37 5.78L17.75 3Zm-1.08 16.17h1.7L7.4 4.74H5.58l11.09 14.43Z" fill="currentColor" /></svg></a>
                            <a href="mailto:?subject={{ rawurlencode($blog->title) }}&body={{ rawurlencode($blog->url()) }}" class="{{ $share }}" aria-label="Share by email"><svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="1.75" y="3.25" width="12.5" height="9.5" rx="1.5" /><path d="m2.5 4.5 5.5 4 5.5-4" /></svg></a>
                        </p>
                    </div>
                </div>
            </aside>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="border-line border-t" aria-labelledby="related-title">
            <div class="wrap wrap-read py-[clamp(56px,7vw,96px)]">
                <div class="flex items-baseline justify-between gap-4">
                    <h2 id="related-title" class="text-[clamp(1.5rem,2.2vw,1.875rem)] font-semibold tracking-[-0.02em]">Related articles</h2>
                    <a href="{{ route('blog.index') }}" class="group text-soft hover:text-ink inline-flex items-center gap-2 text-[0.9375rem] font-medium transition-colors">All articles <svg class="size-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5" /></svg></a>
                </div>
                <div class="mt-8 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $post)
                        @include('website.partials.post-card', ['post' => $post, 'heading' => 'h3'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('website.partials.cta', [
        'title' => config('ethaika.platform.cta.title'),
        'text' => config('ethaika.platform.cta.text'),
        'facts' => config('ethaika.platform.cta.facts'),
    ])
</x-website>
