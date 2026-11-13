{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $siteName }} Blog</title>
        <link>{{ route('blog.index') }}</link>
        <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml" />
        <description>{{ config('ethaika.blog.meta') }}</description>
        <language>{{ str_replace('_', '-', app()->getLocale()) }}</language>
        @if ($posts->isNotEmpty())
            <lastBuildDate>{{ optional($posts->first()->published_at)->toRssString() }}</lastBuildDate>
        @endif
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ $post->url() }}</link>
                <guid isPermaLink="true">{{ $post->url() }}</guid>
                <pubDate>{{ optional($post->published_at)->toRssString() }}</pubDate>
                <description>{{ $post->summary(300) }}</description>
                @if ($post->imageUrl())
                    <enclosure url="{{ $post->imageUrl() }}" type="image/webp" length="0" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
