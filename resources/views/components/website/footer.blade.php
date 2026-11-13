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

    $isLegal = fn ($l) => Str::contains(Str::lower($l['label'] ?? ''), ['privacy', 'terms', 'cookie']);
    $legal = $columns->flatMap(fn ($c) => $c['links'])->filter($isLegal)->unique('label')->values();
    $columns = $columns
        ->map(fn ($c) => [...$c, 'links' => collect($c['links'])->reject($isLegal)->values()->all()])
        ->filter(fn ($c) => count($c['links']))
        ->values();

    $emails = collect(Settings::emails())->filter()->values();
    $phones = collect(Settings::phones())->filter()->values();
    $addresses = collect(Settings::addresses())->map(fn ($a) => is_array($a) ? ($a['text'] ?? null) : $a)->filter()->values();
    $socials = collect(Settings::socialLinks())
        ->map(fn ($s) => is_array($s) && filled($s['url'] ?? null) ? ['label' => $s['platform'] ?? $s['label'] ?? 'Link', 'url' => $s['url']] : null)
        ->filter()->values();

    $name = Settings::appName();
    $display = $emails->first();
    $logoSetting = Settings::logoLight();
    $logo = $logoSetting ?: asset('images/site/ethaika-logo.webp');
    $logoInk = $logoSetting ? '' : 'brightness-0';
@endphp

<footer id="siteFooter" class="bg-tone">
    <div class="wrap flex flex-col pt-16 pb-8">
        <div class="grid gap-12 md:grid-cols-12">
            <div class="foot-rise md:col-span-4">
                <a href="{{ url('/') }}" class="inline-block" aria-label="{{ $name }} home">
                    <img src="{{ $logo }}" alt="{{ $name }}" width="69" height="44" loading="lazy" class="h-20 w-auto md:h-24 {{ $logoInk }}" />
                </a>
                @if ($logoSetting)
                    <p class="text-soft mt-4 max-w-[34ch]">{{ config('ethaika.tagline') }}</p>
                @endif
            </div>

            <{{ $columns->isNotEmpty() ? 'nav' : 'div' }} @if ($columns->isNotEmpty()) aria-label="Footer" @endif class="grid grid-cols-2 gap-x-8 gap-y-10 sm:grid-cols-3 md:col-span-6 md:col-start-6">
                @foreach ($columns as $column)
                    <div class="foot-rise">
                        <h2 class="text-soft text-[0.875rem]">
                            @if ($column['url'])
                                <a href="{{ $column['url'] }}" @if ($column['new_tab']) target="_blank" rel="noopener" @endif class="hover:text-ink transition-colors">{{ $column['heading'] }}</a>
                            @else
                                {{ $column['heading'] }}
                            @endif
                        </h2>
                        <ul class="mt-4 grid gap-2.5">
                            @foreach ($column['links'] as $link)
                                <li>
                                    @if (filled($link['url'] ?? null))
                                        <a href="{{ $link['url'] }}" @if ($link['new_tab'] ?? false) target="_blank" rel="noopener" @endif @if (rtrim($link['url'], '/') === rtrim(url()->current(), '/')) aria-current="page" @endif class="ulink font-medium">{{ $link['label'] }}</a>
                                    @else
                                        <span class="font-medium">{{ $link['label'] }}</span>
                                    @endif
                                    @if (! empty($link['children']))
                                        <ul class="border-line mt-2.5 grid gap-2 border-l pl-3">
                                            @foreach ($link['children'] as $sub)
                                                @if (filled($sub['label'] ?? null) && filled($sub['url'] ?? null))
                                                    <li><a href="{{ $sub['url'] }}" @if ($sub['new_tab'] ?? false) target="_blank" rel="noopener" @endif class="ulink text-soft text-[0.9375rem]">{{ $sub['label'] }}</a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
                @if ($phones->isNotEmpty() || $addresses->isNotEmpty() || $emails->count() > 1)
                    <div class="foot-rise">
                        <h2 class="text-soft text-[0.875rem]">Contact</h2>
                        <ul class="mt-4 grid gap-2.5">
                            @foreach ($emails->slice(1) as $email)
                                <li><a href="mailto:{{ $email }}" class="ulink font-medium">{{ $email }}</a></li>
                            @endforeach
                            @foreach ($phones as $phone)
                                <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="ulink font-medium">{{ $phone }}</a></li>
                            @endforeach
                            @foreach ($addresses as $address)
                                <li class="text-soft">{!! nl2br(e($address)) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </{{ $columns->isNotEmpty() ? 'nav' : 'div' }}>

            <a href="#main" class="foot-rise ulink self-start justify-self-start font-medium whitespace-nowrap md:col-span-1 md:justify-self-end" data-top>Back to top</a>
        </div>

        <div class="pt-16">
            @if ($display)
                <a href="mailto:{{ $display }}" class="foot-mark group mx-auto block w-fit text-center text-[clamp(2.75rem,8.4vw,9rem)] leading-[0.9] font-bold tracking-[-0.045em] break-words transition-colors duration-500 hover:text-accent">
                    <span class="line"><span>{{ Str::before($display, '@') }}</span></span><span class="line"><span>{{ '@' . Str::after($display, '@') }}</span></span>
                </a>
            @else
                <a href="{{ route('contact') }}" class="foot-mark group mx-auto block w-fit text-center" aria-label="Schedule a demo with {{ $name }}">
                    <span class="line"><span class="fm text-[clamp(4rem,19vw,18rem)] leading-[0.82] font-bold tracking-[-0.06em]" aria-hidden="true">@foreach (mb_str_split($name) as $i => $ch)<span class="fm-l" style="--d: {{ $i * 45 }}ms">{{ $ch }}</span>@endforeach</span></span>
                    <span class="fm-cta text-accent mt-3 inline-flex items-center gap-2 text-[1.0625rem] font-semibold">Schedule Demo <svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5" /></svg></span>
                </a>
            @endif

            <div class="text-soft mt-10 grid gap-3 text-[0.875rem] md:grid-cols-3 md:items-baseline">
                <nav class="flex flex-wrap gap-x-6 gap-y-2" aria-label="Legal">
                    @foreach ($legal as $link)
                        <a href="{{ $link['url'] ?? '#' }}" @if ($link['new_tab'] ?? false) target="_blank" rel="noopener" @endif class="ulink">{{ $link['label'] }}</a>
                    @endforeach
                </nav>
                <p class="md:text-center">© {{ now()->year }} {{ config('ethaika.copyright') }}</p>
                <p class="flex flex-wrap gap-x-6 gap-y-2 md:justify-end">
                    @foreach ($socials as $social)
                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="ulink text-ink font-medium">{{ $social['label'] }}</a>
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</footer>
