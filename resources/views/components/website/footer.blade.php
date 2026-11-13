@php
    use App\Helpers\Settings;
    use Illuminate\Support\Str;

    $tree = collect(rescue(fn () => app(\App\Services\Menus\MenuBuilder::class)->tree('footer'), [], report: false))
        ->filter(fn ($item) => filled($item['label'] ?? null));

    $columns = $tree->filter(fn ($item) => ! empty($item['children']))
        ->map(fn ($item) => ['heading' => $item['label'], 'url' => $item['url'] ?? null, 'new_tab' => (bool) ($item['new_tab'] ?? false), 'links' => collect($item['children'])->filter(fn ($c) => filled($c['label'] ?? null))->values()->all()])
        ->values();
    $loose = $tree->filter(fn ($item) => empty($item['children']))->values();
    if ($loose->isNotEmpty()) {
        $columns->push(['heading' => 'More', 'url' => null, 'new_tab' => false, 'links' => $loose->all()]);
    }

    $columns = $columns->filter(fn ($c) => count($c['links']))->values();

    $emails = collect(Settings::emails())->filter()->values();
    $phones = collect(Settings::phones())->filter()->values();
    $addresses = collect(Settings::addresses())->map(fn ($a) => is_array($a) ? ($a['text'] ?? null) : $a)->filter()->values();
    $socials = collect(Settings::socialLinks())
        ->map(fn ($s) => is_array($s) && filled($s['url'] ?? null) ? ['label' => $s['platform'] ?? $s['label'] ?? 'Link', 'url' => $s['url']] : null)
        ->filter()->values();
    $hasContact = $emails->isNotEmpty() || $phones->isNotEmpty() || $addresses->isNotEmpty();

    $icons = [
        'linkedin' => '<path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3 9.75h4v11H3v-11Zm6.5 0h3.8v1.5h.06c.53-1 1.83-2.06 3.77-2.06 4.03 0 4.77 2.65 4.77 6.1v5.46h-4v-4.84c0-1.16-.02-2.64-1.61-2.64-1.61 0-1.86 1.26-1.86 2.56v4.92h-4v-11Z" fill="currentColor"/>',
        'x' => '<path d="M17.75 3h3.07l-6.7 7.66L22 21h-6.17l-4.83-6.32L5.47 21H2.4l7.17-8.2L2 3h6.33l4.37 5.78L17.75 3Zm-1.08 16.17h1.7L7.4 4.74H5.58l11.09 14.43Z" fill="currentColor"/>',
        'github' => '<path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.08 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.65 0 0 .84-.27 2.75 1.02a9.56 9.56 0 0 1 5 0c1.91-1.29 2.75-1.02 2.75-1.02.55 1.38.2 2.4.1 2.65.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.68-4.57 4.93.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2Z" fill="currentColor"/>',
        'youtube' => '<path d="M21.6 7.2a2.5 2.5 0 0 0-1.76-1.77C18.27 5 12 5 12 5s-6.27 0-7.84.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.76 1.77C5.73 19 12 19 12 19s6.27 0 7.84-.43a2.5 2.5 0 0 0 1.76-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8ZM10 15V9l5.2 3L10 15Z" fill="currentColor"/>',
        'facebook' => '<path d="M14 8.5V6.6c0-.85.2-1.3 1.5-1.3H17V2.2A21 21 0 0 0 14.8 2C12.3 2 10.6 3.5 10.6 6.3v2.2H8v3.2h2.6V22H14V11.7h2.7l.4-3.2H14Z" fill="currentColor"/>',
        'instagram' => '<path d="M12 7.2a4.8 4.8 0 1 0 0 9.6 4.8 4.8 0 0 0 0-9.6Zm0 7.9a3.1 3.1 0 1 1 0-6.2 3.1 3.1 0 0 1 0 6.2Zm5-8.1a1.1 1.1 0 1 0 0 2.2 1.1 1.1 0 0 0 0-2.2ZM12 3.6c2.7 0 3 0 4.1.06 2.7.12 4 1.42 4.12 4.12.05 1.06.06 1.38.06 4.07s0 3.01-.06 4.07c-.12 2.7-1.42 4-4.12 4.12-1.06.05-1.38.06-4.1.06s-3-.01-4.06-.06c-2.72-.12-4-1.43-4.12-4.12C3.6 15 3.6 14.7 3.6 12s0-3 .06-4.07c.12-2.7 1.4-4 4.12-4.12C8.9 3.61 9.3 3.6 12 3.6ZM12 2c-2.72 0-3.06 0-4.12.06-3.63.17-5.65 2.18-5.82 5.82C2 8.94 2 9.28 2 12s0 3.06.06 4.12c.17 3.63 2.18 5.65 5.82 5.82C8.94 22 9.28 22 12 22s3.06 0 4.12-.06c3.63-.17 5.65-2.18 5.82-5.82C22 15.06 22 14.72 22 12s0-3.06-.06-4.12c-.17-3.63-2.18-5.65-5.82-5.82C15.06 2 14.72 2 12 2Z" fill="currentColor"/>',
        'discord' => '<path d="M19.6 5.3A16.6 16.6 0 0 0 15.5 4l-.5 1a15.3 15.3 0 0 0-6 0l-.5-1a16.5 16.5 0 0 0-4.1 1.3A17.3 17.3 0 0 0 1.4 17a16.7 16.7 0 0 0 5 2.6l1.1-1.7a10.7 10.7 0 0 1-1.7-.8l.4-.3a11.9 11.9 0 0 0 11.6 0l.4.3a10.7 10.7 0 0 1-1.7.8l1.1 1.7a16.7 16.7 0 0 0 5-2.6 17.2 17.2 0 0 0-3-11.7ZM8.7 14.7c-1 0-1.8-.95-1.8-2.1s.8-2.1 1.8-2.1 1.83.95 1.8 2.1c0 1.15-.8 2.1-1.8 2.1Zm6.6 0c-1 0-1.8-.95-1.8-2.1s.8-2.1 1.8-2.1 1.83.95 1.8 2.1c0 1.15-.8 2.1-1.8 2.1Z" fill="currentColor"/>',
    ];
    $iconFor = function (array $social) use ($icons) {
        $key = Str::lower($social['label'].' '.$social['url']);
        foreach (['linkedin' => 'linkedin', 'twitter' => 'x', 'x.com' => 'x', 'github' => 'github', 'youtube' => 'youtube', 'facebook' => 'facebook', 'instagram' => 'instagram', 'discord' => 'discord'] as $needle => $icon) {
            if (str_contains($key, $needle)) {
                return $icons[$icon];
            }
        }
        if (Str::lower(trim($social['label'])) === 'x') {
            return $icons['x'];
        }

        return null;
    };

    $count = $columns->count() + ($hasContact ? 1 : 0) + ($socials->isNotEmpty() ? 1 : 0);

    $name = Settings::appName();
    $heading = 'text-ink text-[0.8125rem] font-semibold tracking-[0.06em] uppercase';
    $linkClass = 'text-soft hover:text-ink text-[0.9375rem] transition-colors';
@endphp

<footer id="siteFooter" class="border-line bg-paper border-t">
    <div class="wrap pt-16 pb-8 md:pt-20">
        @if ($count)
            <div class="grid grid-cols-2 gap-x-8 gap-y-12 sm:grid-cols-3 lg:grid-cols-[repeat(var(--cols),minmax(0,1fr))]" style="--cols: {{ max($count, 4) }}">
                @if ($columns->isNotEmpty())
                    <nav class="contents" aria-label="Footer">
                        @foreach ($columns as $column)
                            <div class="foot-rise">
                                <h2 class="{{ $heading }}">
                                    @if ($column['url'])
                                        <a href="{{ $column['url'] }}" @if ($column['new_tab']) target="_blank" rel="noopener" @endif class="hover:text-accent transition-colors">{{ $column['heading'] }}</a>
                                    @else
                                        {{ $column['heading'] }}
                                    @endif
                                </h2>
                                <ul class="mt-5 grid gap-3.5">
                                    @foreach ($column['links'] as $link)
                                        <li>
                                            @if (filled($link['url'] ?? null))
                                                <a href="{{ $link['url'] }}" @if ($link['new_tab'] ?? false) target="_blank" rel="noopener" @endif @if (rtrim($link['url'], '/') === rtrim(url()->current(), '/')) aria-current="page" @endif class="{{ $linkClass }} aria-[current=page]:text-ink">{{ $link['label'] }}</a>
                                            @else
                                                <span class="text-fade text-[0.875rem] font-medium">{{ $link['label'] }}</span>
                                            @endif
                                            @if (! empty($link['children']))
                                                <ul class="border-line mt-3 grid gap-3 border-l pl-3">
                                                    @foreach ($link['children'] as $sub)
                                                        @if (filled($sub['label'] ?? null) && filled($sub['url'] ?? null))
                                                            <li><a href="{{ $sub['url'] }}" @if ($sub['new_tab'] ?? false) target="_blank" rel="noopener" @endif class="{{ $linkClass }}">{{ $sub['label'] }}</a></li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </nav>
                @endif

                @if ($hasContact)
                    <div class="foot-rise">
                        <h2 class="{{ $heading }}">Contact</h2>
                        <ul class="mt-5 grid gap-3.5">
                            @foreach ($emails as $email)
                                <li><a href="mailto:{{ $email }}" class="{{ $linkClass }} break-all">{{ $email }}</a></li>
                            @endforeach
                            @foreach ($phones as $phone)
                                <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="{{ $linkClass }}">{{ $phone }}</a></li>
                            @endforeach
                            @foreach ($addresses as $address)
                                <li class="text-soft text-[0.9375rem]">{!! nl2br(e($address)) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($socials->isNotEmpty())
                    <div class="foot-rise">
                        <h2 class="{{ $heading }}">Connect</h2>
                        <ul class="mt-5 flex flex-wrap gap-2">
                            @foreach ($socials as $social)
                                @php($icon = $iconFor($social))
                                <li>
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}" @class(['text-soft hover:text-ink hover:bg-tone grid h-10 place-items-center rounded-[10px] transition-colors', 'w-10' => $icon, 'px-3 text-[0.9375rem]' => ! $icon])>
                                        @if ($icon)
                                            <svg class="size-5" viewBox="0 0 24 24" aria-hidden="true">{!! $icon !!}</svg>
                                        @else
                                            {{ $social['label'] }}
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        <div @class(['border-line flex flex-wrap items-center gap-x-5 gap-y-3', 'mt-14 border-t pt-7' => $count])>
            <a href="{{ url('/') }}" class="text-ink hover:text-accent shrink-0 text-[1.375rem] font-semibold tracking-[-0.02em] transition-colors">{{ $name }}</a>
            <p class="text-soft text-[0.875rem]">© {{ now()->year }} {{ config('ethaika.copyright') }}</p>
        </div>
    </div>
</footer>
