@php
    $heading ??= 'h2';
    $priority ??= false;
    $category = $post->categories->first();
@endphp

<a href="{{ $post->url() }}" class="post will-rise group flex flex-col">
    <div class="border-line bg-tone relative overflow-hidden rounded-[16px] border">
        @if ($post->imageUrl())
            <x-picture :src="$post->featured_image" location="storage" :priority="$priority" sizes="(min-width: 1024px) 31vw, (min-width: 640px) 46vw, 92vw" class="aspect-[16/10] w-full object-cover transition-transform duration-[1.2s] ease-[cubic-bezier(.2,.8,.2,1)] group-hover:scale-[1.04]" />
        @else
            <div class="aspect-[16/10]"></div>
        @endif
        @if ($category)
            <span class="bg-paper/85 text-ink absolute top-3 left-3 rounded-[8px] px-2.5 py-1 text-[0.75rem] font-medium backdrop-blur">{{ $category->name }}</span>
        @endif
    </div>
    <{{ $heading }} class="group-hover:text-accent mt-5 text-[1.25rem] leading-[1.3] font-semibold tracking-[-0.015em] transition-colors">{{ $post->title }}</{{ $heading }}>
    <p class="text-soft mt-2 line-clamp-2 text-[0.9375rem] leading-relaxed">{{ $post->summary(160) }}</p>
    <p class="text-fade mt-3 text-[0.8125rem]">
        <time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->format('M j, Y') }}</time>
        <span aria-hidden="true"> · </span>{{ $post->readingMinutes() }} min read
    </p>
</a>
