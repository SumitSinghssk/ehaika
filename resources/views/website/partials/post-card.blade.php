{{--
    One blog card, used everywhere a post is listed (blog listing, category pages, "Keep reading").
    $post: a Blog with its categories loaded. $heading: the tag for the title (h2 in a listing, h3 under a section heading).
--}}
@php
    $heading ??= 'h2';
@endphp

<a href="{{ $post->url() }}" class="post will-rise card group flex flex-col overflow-hidden">
    <div class="bg-tone overflow-hidden">
        @if ($post->imageUrl())
            <img src="{{ $post->imageUrl() }}" alt="" loading="lazy" class="aspect-[16/10] w-full object-cover transition-transform duration-[1.2s] ease-[cubic-bezier(.2,.8,.2,1)] group-hover:scale-105" />
        @else
            <div class="aspect-[16/10]"></div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-7">
        <p class="text-soft flex flex-wrap items-center gap-x-3 text-[0.875rem] font-medium">
            @if ($post->categories->isNotEmpty())<span class="text-accent font-semibold">{{ $post->categories->first()->name }}</span><span aria-hidden="true">·</span>@endif
            <time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->format('F j, Y') }}</time>
        </p>
        <{{ $heading }} class="mt-3 text-[1.3125rem] leading-snug font-semibold tracking-[-0.02em]">{{ $post->title }}</{{ $heading }}>
        <p class="text-soft mt-3">{{ $post->summary(140) }}</p>
        <span class="text-accent mt-auto inline-flex items-center gap-2 pt-6 font-semibold">Read article <svg class="size-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5" /></svg></span>
    </div>
</a>
