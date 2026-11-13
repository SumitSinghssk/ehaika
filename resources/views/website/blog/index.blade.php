@php
    $b = config('ethaika.blog');
    $title = $category ? $category->name . ' · Blog · Ethaika' : 'Blog & News · Ethaika';
@endphp

<x-website
    :title="$title"
    :description="$category?->description ?: $b['meta']"
    :breadcrumbs="array_values(array_filter([['name' => 'Blog', 'url' => route('blog.index')], $category ? ['name' => $category->name, 'url' => route('blog.category', $category->slug)] : null]))"
>
    @include('website.partials.page-hero', [
        'title' => $category ? $category->name : $b['title'],
        'text' => $category && $category->description ? [$category->description] : [],
    ])

    <section class="wrap pb-[clamp(72px,8vw,120px)]" aria-label="Articles">
        @if ($categories->isNotEmpty())
            <nav class="mb-10 flex flex-wrap justify-center gap-2" aria-label="Categories">
                <a href="{{ route('blog.index') }}" @class(['rounded-[12px] border px-4 py-2 text-[0.9375rem] font-semibold transition-colors', 'border-ink bg-ink text-white' => ! $category, 'border-[var(--color-line)] bg-white text-soft hover:text-ink' => $category]) @if (! $category) aria-current="page" @endif>All</a>
                @foreach ($categories as $cat)
                    <a href="{{ route('blog.category', $cat->slug) }}" @class(['rounded-[12px] border px-4 py-2 text-[0.9375rem] font-semibold transition-colors', 'border-ink bg-ink text-white' => $category?->is($cat), 'border-[var(--color-line)] bg-white text-soft hover:text-ink' => ! $category?->is($cat)]) @if ($category?->is($cat)) aria-current="page" @endif>{{ $cat->name }}</a>
                @endforeach
            </nav>
        @endif

        @if ($posts->isEmpty())
            <div class="card p-12 text-center">
                <p class="t-lead font-semibold">No articles yet.</p>
                <p class="text-soft mt-2">New articles will appear here as soon as they are published.</p>
            </div>
        @else
            @if ($posts->isNotEmpty())
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        @include('website.partials.post-card', ['post' => $post, 'priority' => $loop->first])
                    @endforeach
                </div>
            @endif

            @if ($posts->hasPages())
                <nav class="mt-12 flex items-center justify-center gap-2" aria-label="Pages">
                    @if ($posts->onFirstPage())
                        <span class="text-fade rounded-[12px] border border-[var(--color-line)] px-4 py-2 font-semibold">Previous</span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}" class="hover:border-ink rounded-[12px] border border-[var(--color-line)] bg-white px-4 py-2 font-semibold">Previous</a>
                    @endif
                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $n => $url)
                        <a href="{{ $url }}" @class(['grid size-10 place-items-center rounded-[12px] border font-semibold', 'border-ink bg-ink text-white' => $n === $posts->currentPage(), 'border-[var(--color-line)] bg-white hover:border-ink' => $n !== $posts->currentPage()]) @if ($n === $posts->currentPage()) aria-current="page" @endif>{{ $n }}</a>
                    @endforeach
                    @if ($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}" class="hover:border-ink rounded-[12px] border border-[var(--color-line)] bg-white px-4 py-2 font-semibold">Next</a>
                    @else
                        <span class="text-fade rounded-[12px] border border-[var(--color-line)] px-4 py-2 font-semibold">Next</span>
                    @endif
                </nav>
            @endif
        @endif
    </section>

    @include('website.partials.faq', ['tone' => true])
</x-website>
