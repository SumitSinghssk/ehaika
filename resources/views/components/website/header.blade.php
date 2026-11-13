@php
    use App\Helpers\Settings;

    $shape = function (array $item) use (&$shape) {
        return [
            'label' => (string) ($item['label'] ?? ''),
            'url' => $item['url'] ?? '#',
            'new_tab' => (bool) ($item['new_tab'] ?? false),
            'children' => collect($item['children'] ?? [])->map($shape)->filter(fn ($c) => $c['label'] !== '')->values()->all(),
        ];
    };

    $items = collect(rescue(fn () => app(\App\Services\Menus\MenuBuilder::class)->tree('header'), [], report: false))
        ->map($shape)
        ->filter(fn ($item) => $item['label'] !== '')
        ->values();

    if ($items->isEmpty()) {
        $items = collect([
            ['label' => 'How it works', 'url' => url('/#how'), 'new_tab' => false, 'children' => []],
            ['label' => 'Features', 'url' => url('/#features'), 'new_tab' => false, 'children' => []],
            ['label' => 'Compare', 'url' => url('/#compare'), 'new_tab' => false, 'children' => []],
            ['label' => 'FAQ', 'url' => url('/#faq'), 'new_tab' => false, 'children' => []],
        ]);
    }

    $name = Settings::appName();
    $logoSetting = Settings::logoLight();
    $logo = $logoSetting ?: asset('images/site/ethaika-logo.webp');
    $logoInk = $logoSetting ? '' : 'brightness-0';
@endphp

<header
    id="siteHeader"
    class="bg-paper fixed inset-x-0 top-0 z-50 transition-[translate] duration-500 ease-[cubic-bezier(.2,.8,.2,1)] [&.is-hidden]:-translate-y-full"
    x-data="{ open: false }"
    x-on:keydown.escape.window="open = false"
    x-effect="document.documentElement.classList.toggle('menu-open', open)"
>
    <div class="wrap flex h-[72px] items-center justify-between gap-8">
        <a href="{{ url('/') }}" class="relative z-10 shrink-0" aria-label="{{ $name }} home">
            <img src="{{ $logo }}" alt="{{ $name }}" width="69" height="44" class="h-9 w-auto {{ $logoInk }}" />
        </a>

        <nav class="hidden flex-1 items-center gap-8 pl-[6vw] md:flex" aria-label="Main">
            @foreach ($items as $item)
                @if (count($item['children']))
                    <div class="relative" x-data="{ sub: false }" x-on:mouseenter="sub = true" x-on:mouseleave="sub = false" x-on:focusout="$el.contains($event.relatedTarget) || (sub = false)">
                        <button type="button" class="ulink inline-flex items-center gap-1.5 text-[0.9375rem] font-medium" x-on:click="sub = !sub" :aria-expanded="sub">
                            {{ $item['label'] }}
                            <svg class="size-3 transition-transform duration-300" :class="sub && 'rotate-180'" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 6 5 5 5-5" /></svg>
                        </button>
                        <div x-show="sub" x-cloak x-transition.opacity.duration.200ms class="absolute top-full left-0 pt-5">
                            <div class="bg-paper grid min-w-56 p-2 shadow-[0_24px_48px_-24px_rgb(0_0_0/0.35)]">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['url'] }}" @if ($child['new_tab']) target="_blank" rel="noopener" @endif class="hover:bg-tone px-3 py-2.5 text-[0.9375rem] transition-colors">{{ $child['label'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $item['url'] }}" @if ($item['new_tab']) target="_blank" rel="noopener" @endif class="ulink text-[0.9375rem] font-medium">{{ $item['label'] }}</a>
                @endif
            @endforeach
        </nav>

        <div class="relative z-10 flex items-center gap-3">
            <a href="{{ url('/#contact') }}" class="btn !min-h-[44px] !px-5 max-md:!hidden">Schedule Demo</a>
            <button type="button" class="inline-flex h-11 items-center gap-3 text-[0.9375rem] font-medium md:hidden" x-on:click="open = !open" :aria-expanded="open" aria-controls="siteMenu">
                <span x-text="open ? 'Close' : 'Menu'">Menu</span>
                <span class="relative block h-2.5 w-5" aria-hidden="true">
                    <i class="bg-ink absolute inset-x-0 top-0 h-[2px] transition-transform duration-300" :class="open && 'translate-y-[4px] rotate-45'"></i>
                    <i class="bg-ink absolute inset-x-0 bottom-0 h-[2px] transition-transform duration-300" :class="open && '-translate-y-[4px] -rotate-45'"></i>
                </span>
            </button>
        </div>
    </div>

    <div id="siteMenu" x-show="open" x-cloak x-transition.opacity.duration.300ms class="bg-paper fixed inset-0 flex flex-col px-[var(--margin)] pt-28 pb-8 md:hidden">
        <nav class="grid gap-1" aria-label="Main">
            @foreach ($items as $i => $item)
                <a href="{{ $item['url'] }}" x-on:click="open = false" class="menu-item text-[2.5rem] leading-tight font-bold tracking-[-0.04em]" style="--d: {{ 0.05 * $i + 0.05 }}s">{{ $item['label'] }}</a>
                @foreach ($item['children'] as $child)
                    <a href="{{ $child['url'] }}" x-on:click="open = false" class="text-soft py-1 pl-1 text-lg">{{ $child['label'] }}</a>
                @endforeach
            @endforeach
        </nav>
        <a href="{{ url('/#contact') }}" x-on:click="open = false" class="btn mt-auto w-full">Schedule Demo</a>
    </div>
</header>

@once
    @push('heads')
        <style>
            #siteMenu .menu-item {
                animation: menu-rise 0.6s var(--d, 0s) both cubic-bezier(0.2, 0.8, 0.2, 1);
            }
            @keyframes menu-rise {
                from {
                    opacity: 0;
                    transform: translateY(24px);
                }
            }
            html.menu-open {
                overflow: hidden;
            }
            html.menu-open #siteHeader {
                translate: none !important;
            }
        </style>
    @endpush
@endonce
