@php
    use App\Helpers\Settings;
    use App\Models\Seo;
    use Illuminate\Support\Facades\Cache;

    $path = trim(request()->path(), '/') ?: '/';
    $seoCollection = rescue(fn () => Cache::remember('seo_all', now()->addHours(24), fn () => Seo::all()->keyBy('slug')), collect(), report: false);

    // A record saved in Admin → SEO for this exact path (or this post's slug) wins over the page's own defaults.
    $seoKey = $seoKey ?? null;
    $seo = $seoCollection[$path] ?? ($seoCollection['/'.$path] ?? ($seoKey ? $seoCollection[$seoKey] ?? null : null));
    $fallback = $seoCollection['default-seo'] ?? null;

    $siteName = Settings::appName();
    $title = $seo?->meta_title ?: ($pageTitle ?? null) ?: ($fallback?->meta_title ?: $siteName);
    $description = $seo?->meta_description ?: ($pageDescription ?? null) ?: $fallback?->meta_description;
    $imagePath = $seo?->og_image ?: ($pageImage ?? null);
    $image = $imagePath ? (str_starts_with($imagePath, 'http') ? $imagePath : (str_starts_with($imagePath, '/') ? url($imagePath) : media_url($imagePath))) : url('images/site/og-ethaika.jpg');
    $canonical = url()->current();
    $index = ! ($pageNoindex ?? false) && ($seo?->index ?? true);
    $favicon = Settings::favicon() ?: asset('images/site/favicon.png');
    $scriptSettings = settings('script_settings') ?? [];
    $linkedin = collect(Settings::socialLinks())->first(fn ($link) => str_contains((string) (is_array($link) ? $link['url'] ?? '' : $link), 'linkedin'));
    $linkedin = is_array($linkedin) ? $linkedin['url'] ?? null : $linkedin;

    $graph = [
        [
            '@type' => 'Organization',
            '@id' => url('/').'#organization',
            'name' => $siteName,
            'url' => url('/'),
            'logo' => asset('images/site/ethaika-logo.png'),
            'sameAs' => array_values(array_filter([$linkedin ?: 'https://www.linkedin.com/company/ethaika/'])),
        ],
        [
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => $siteName,
            'publisher' => ['@id' => url('/').'#organization'],
        ],
    ];

    if (! empty($breadcrumbs)) {
        $graph[] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect([['name' => 'Home', 'url' => url('/')], ...$breadcrumbs])
                ->values()
                ->map(fn ($crumb, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb['name'], 'item' => $crumb['url']])
                ->all(),
        ];
    }

    foreach ($pageSchema ?? [] as $node) {
        $graph[] = $node;
    }
@endphp

@push('heads')
    <title>{{ $title }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}" />
    @endif
    <link rel="canonical" href="{{ $canonical }}" />
    <meta name="robots" content="{{ $index ? 'index, follow, max-image-preview:large' : 'noindex, nofollow' }}" />
    <meta name="theme-color" content="#f6f6fa" />
    <link rel="icon" href="{{ $favicon }}" />
    <link rel="apple-touch-icon" href="{{ asset('images/site/apple-touch-icon.png') }}" />

    <meta property="og:type" content="{{ $pageType ?? 'website' }}" />
    <meta property="og:site_name" content="{{ $siteName }}" />
    <meta property="og:title" content="{{ $title }}" />
    @if ($description)
        <meta property="og:description" content="{{ $description }}" />
    @endif
    <meta property="og:url" content="{{ $canonical }}" />
    <meta property="og:image" content="{{ $image }}" />
    <meta property="og:locale" content="en_GB" />
    @if (($pageType ?? null) === 'article')
        @if ($published)
            <meta property="article:published_time" content="{{ $published }}" />
        @endif
        @if ($modified)
            <meta property="article:modified_time" content="{{ $modified }}" />
        @endif
    @endif

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title }}" />
    @if ($description)
        <meta name="twitter:description" content="{{ $description }}" />
    @endif
    <meta name="twitter:image" content="{{ $image }}" />

    <link rel="alternate" type="application/rss+xml" title="{{ $siteName }} blog" href="{{ route('blog.feed') }}" />

    <script type="application/ld+json">
        {!! json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>

    @if ($seo?->custom_css)
        <style>
            {!! $seo->custom_css !!}
        </style>
    @endif

    @if (! empty($scriptSettings['header_css'] ?? null))
        <style>
            {!! $scriptSettings['header_css'] !!}
        </style>
    @endif

    @if (! empty($scriptSettings['header_scripts'] ?? null))
        {!! $scriptSettings['header_scripts'] !!}
    @endif
@endpush

@push('head-scripts')
    @if ($seo?->header_scripts)
        {!! $seo->header_scripts !!}
    @endif
@endpush

@push('scripts')
    @if ($seo?->schema)
        {!! $seo->schema !!}
    @endif

    @if ($seo?->footer_scripts)
        {!! $seo->footer_scripts !!}
    @endif

    @if (! empty($scriptSettings['footer_scripts'] ?? null))
        {!! $scriptSettings['footer_scripts'] !!}
    @endif

    @if (! empty($scriptSettings['footer_css'] ?? null))
        <style>
            {!! $scriptSettings['footer_css'] !!}
        </style>
    @endif
@endpush
