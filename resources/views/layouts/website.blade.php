{{--
    Public website layout (Ethaika, light).
    Every page passes its own SEO defaults; an SEO record saved in Admin → SEO for the same path overrides them.
    Brief: docs/ethaika-home-prompt.md
--}}

<x-app body-class="site-light">
    @include('layouts.partials.website.seo', [
        'pageTitle' => $title,
        'pageDescription' => $description,
        'pageImage' => $image,
        'pageType' => $type,
        'pageSchema' => $schema,
        'pageNoindex' => $noindex,
        'breadcrumbs' => $breadcrumbs,
        'published' => $published,
        'modified' => $modified,
        'seoKey' => $seoKey,
    ])

    @push('head-scripts')
        <script>
            // Motion hides only what it will reveal. If the motion script never starts, show everything.
            document.documentElement.classList.add('js');
            setTimeout(() => window.__ethaikaMotion || document.documentElement.classList.remove('js'), 3000);
        </script>
        <link rel="preconnect" href="https://api.fontshare.com" />
        <link rel="preconnect" href="https://cdn.fontshare.com" crossorigin />
        <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=general-sans@400,500,600,700&display=swap" />
        @vite(['resources/css/website.css', 'resources/js/website.js'])
    @endpush

    <a href="#main" class="bg-ink sr-only fixed top-3 left-3 z-[80] px-4 py-2 text-white focus:not-sr-only">Skip to content</a>

    @if (auth('web')->check() && rescue(fn () => \App\Support\Maintenance::isOn(), false, report: false))
        <div class="bg-ink sticky top-0 z-[70] px-4 py-2 text-center text-sm font-medium text-white">
            Maintenance mode is on: visitors see the maintenance page.
            <a href="{{ route('admin.settings.index', ['tab' => 'maintenance']) }}" class="ml-1 underline underline-offset-2">Turn it off</a>
        </div>
    @endif

    <x-website.header />

    <main id="main">
        {{ $slot }}
    </main>

    <x-website.footer />
</x-app>
