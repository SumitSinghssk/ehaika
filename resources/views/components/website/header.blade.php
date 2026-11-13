@php
    use App\Helpers\Settings;

    $shape = function (array $item) use (&$shape) {
        return [
            'label' => (string) ($item['label'] ?? ''),
            'url' => filled($item['url'] ?? null) ? $item['url'] : null,
            'style' => $item['style'] ?? 'dropdown',
            'image' => $item['image'] ?? null,
            'description' => $item['description'] ?? null,
            'new_tab' => (bool) ($item['new_tab'] ?? false),
            'children' => collect($item['children'] ?? [])->map($shape)->filter(fn ($c) => $c['label'] !== '')->values()->all(),
        ];
    };

    $items = collect(rescue(fn () => app(\App\Services\Menus\MenuBuilder::class)->tree('header'), [], report: false))
        ->map($shape)
        ->filter(fn ($item) => $item['label'] !== '' && ($item['url'] || $item['children']))
        ->values();

    $current = rtrim(url()->current(), '/');
    $isCurrent = fn (?string $url) => $url && ! str_contains($url, '#') && rtrim(strtok($url, '?'), '/') === $current;
    $inBranch = function (array $item) use (&$inBranch, $isCurrent) {
        return $isCurrent($item['url']) || collect($item['children'])->contains(fn ($c) => $inBranch($c));
    };
    $attrs = fn (array $item) => ($item['new_tab'] ? 'target="_blank" rel="noopener" ' : '') . ($isCurrent($item['url']) ? 'aria-current="page"' : '');
    $hasCards = fn (array $column) => collect($column['children'])->contains(fn ($l) => filled($l['image']));

    $chevron = '<svg class="size-3 transition-transform duration-300" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 6 5 5 5-5" /></svg>';
    $side = '<svg class="size-3" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 3 5 5-5 5" /></svg>';
    $panel = 'bg-paper border-line border shadow-[0_24px_48px_-24px_rgb(11_18_32/0.25)]';

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
    <div class="wrap relative flex h-[72px] items-center justify-between gap-8">
        <a href="{{ url('/') }}" class="relative z-10 shrink-0" aria-label="{{ $name }} home">
            <img src="{{ $logo }}" alt="{{ $name }}" width="69" height="44" class="h-9 w-auto {{ $logoInk }}" />
        </a>

        @if ($items->isNotEmpty())
            <nav class="hidden flex-1 items-center gap-8 pl-[6vw] md:flex" aria-label="Main">
                @foreach ($items as $item)
                    @php($active = $inBranch($item))
                    @if (! $item['children'])
                        <a href="{{ $item['url'] }}" {!! $attrs($item) !!} @class(['ulink text-[0.9375rem] font-medium', 'text-accent' => $active])>{{ $item['label'] }}</a>
                        @continue
                    @endif

                    @php($mega = $item['style'] === 'mega')
                    <div @class(['relative' => ! $mega]) x-data="{ sub: false }" x-on:mouseenter="sub = true" x-on:mouseleave="sub = false" x-on:focusout="$el.contains($event.relatedTarget) || (sub = false)">
                        @if ($item['url'])
                            <span class="inline-flex items-center gap-1">
                                <a href="{{ $item['url'] }}" {!! $attrs($item) !!} @class(['ulink text-[0.9375rem] font-medium', 'text-accent' => $active])>{{ $item['label'] }}</a>
                                <button type="button" class="grid size-6 place-items-center" x-on:click="sub = !sub" :aria-expanded="sub.toString()" aria-label="Show links under {{ $item['label'] }}"><span class="inline-grid transition-transform duration-300" :class="sub && 'rotate-180'">{!! $chevron !!}</span></button>
                            </span>
                        @else
                            <button type="button" @class(['ulink inline-flex items-center gap-1.5 text-[0.9375rem] font-medium', 'text-accent' => $active]) x-on:click="sub = !sub" :aria-expanded="sub.toString()">
                                {{ $item['label'] }}
                                <span class="inline-grid transition-transform duration-300" :class="sub && 'rotate-180'">{!! $chevron !!}</span>
                            </button>
                        @endif

                        @if ($mega)
                            <div x-show="sub" x-cloak x-transition.opacity.duration.200ms class="absolute inset-x-0 top-full px-[var(--margin)] pt-1">
                                <div class="{{ $panel }} flex max-h-[calc(100dvh-7rem)] gap-10 overflow-y-auto rounded-[var(--radius)] p-8">
                                    @foreach ($item['children'] as $column)
                                        @php($cards = $hasCards($column))
                                        <section @class(['min-w-0', 'w-56 shrink-0' => ! $cards, 'flex-1' => $cards])>
                                            @if ($column['url'])
                                                <a href="{{ $column['url'] }}" {!! $attrs($column) !!} class="hover:text-accent text-[1.0625rem] font-semibold transition-colors">{{ $column['label'] }}</a>
                                            @else
                                                <p class="text-[1.0625rem] font-semibold">{{ $column['label'] }}</p>
                                            @endif
                                            @if ($column['children'] && $cards)
                                                <ul class="mt-4 grid grid-cols-[repeat(auto-fill,minmax(9.5rem,1fr))] gap-4">
                                                    @foreach ($column['children'] as $link)
                                                        <li>
                                                            <a href="{{ $link['url'] ?? url('/') }}" {!! $attrs($link) !!} class="group block">
                                                                @if ($link['image'])
                                                                    <span class="bg-tone block aspect-square overflow-hidden rounded-[14px]"><img src="{{ $link['image'] }}" alt="" loading="lazy" class="size-full object-cover transition-transform duration-500 group-hover:scale-105" /></span>
                                                                @endif
                                                                <span class="group-hover:text-accent mt-2.5 block text-[0.9375rem] font-medium transition-colors">{{ $link['label'] }}</span>
                                                                @if ($link['description'])
                                                                    <span class="text-soft block text-[0.875rem]">{{ $link['description'] }}</span>
                                                                @endif
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @elseif ($column['children'])
                                                <ul class="mt-3 grid gap-1">
                                                    @foreach ($column['children'] as $link)
                                                        <li>
                                                            @if ($link['url'])
                                                                <a href="{{ $link['url'] }}" {!! $attrs($link) !!} class="text-soft hover:text-ink block py-1 text-[0.9375rem] transition-colors">{{ $link['label'] }}</a>
                                                            @else
                                                                <span class="text-fade block py-1 text-[0.875rem] font-medium">{{ $link['label'] }}</span>
                                                            @endif
                                                            @if ($link['description'])
                                                                <span class="text-fade block text-[0.8125rem]">{{ $link['description'] }}</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </section>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div x-show="sub" x-cloak x-transition.opacity.duration.200ms class="absolute top-full left-0 pt-5">
                                <ul class="{{ $panel }} grid min-w-60 rounded-[14px] p-2">
                                    @foreach ($item['children'] as $child)
                                        <li class="relative" x-data="{ deep: false }" x-on:mouseenter="deep = true" x-on:mouseleave="deep = false" x-on:focusout="$el.contains($event.relatedTarget) || (deep = false)">
                                            @if (! $child['children'])
                                                @if ($child['url'])
                                                    <a href="{{ $child['url'] }}" {!! $attrs($child) !!} @class(['hover:bg-tone block rounded-[10px] px-3 py-2.5 text-[0.9375rem] transition-colors', 'text-accent' => $isCurrent($child['url'])])>{{ $child['label'] }}</a>
                                                @else
                                                    <span class="text-fade block px-3 py-2.5 text-[0.875rem] font-medium">{{ $child['label'] }}</span>
                                                @endif
                                            @else
                                                <div class="hover:bg-tone flex items-center justify-between rounded-[10px] transition-colors">
                                                    @if ($child['url'])
                                                        <a href="{{ $child['url'] }}" {!! $attrs($child) !!} @class(['flex-1 px-3 py-2.5 text-[0.9375rem]', 'text-accent' => $inBranch($child)])>{{ $child['label'] }}</a>
                                                        <button type="button" class="text-soft grid size-9 place-items-center" x-on:click="deep = !deep" :aria-expanded="deep.toString()" aria-label="Show links under {{ $child['label'] }}">{!! $side !!}</button>
                                                    @else
                                                        <button type="button" @class(['flex flex-1 items-center justify-between gap-3 px-3 py-2.5 text-left text-[0.9375rem]', 'text-accent' => $inBranch($child)]) x-on:click="deep = !deep" :aria-expanded="deep.toString()">
                                                            {{ $child['label'] }}
                                                            <span class="text-soft">{!! $side !!}</span>
                                                        </button>
                                                    @endif
                                                </div>
                                                <div x-show="deep" x-cloak x-transition.opacity.duration.150ms class="absolute top-0 left-full pl-2">
                                                    <ul class="{{ $panel }} grid min-w-56 rounded-[14px] p-2">
                                                        @foreach ($child['children'] as $leaf)
                                                            <li>
                                                                @if ($leaf['url'])
                                                                    <a href="{{ $leaf['url'] }}" {!! $attrs($leaf) !!} @class(['hover:bg-tone block rounded-[10px] px-3 py-2.5 text-[0.9375rem] transition-colors', 'text-accent' => $isCurrent($leaf['url'])])>{{ $leaf['label'] }}</a>
                                                                @else
                                                                    <span class="text-fade block px-3 py-2.5 text-[0.875rem] font-medium">{{ $leaf['label'] }}</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>
        @endif

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

    <div id="siteMenu" x-show="open" x-cloak x-transition.opacity.duration.300ms class="bg-paper fixed inset-0 flex flex-col overflow-y-auto px-[var(--margin)] pt-28 pb-8 md:hidden">
        @if ($items->isNotEmpty())
            <nav class="grid gap-1" aria-label="Main">
                @foreach ($items as $i => $item)
                    @if ($item['url'])
                        <a href="{{ $item['url'] }}" {!! $attrs($item) !!} x-on:click="open = false" @class(['menu-item text-[2.5rem] leading-tight font-bold tracking-[-0.04em]', 'text-accent' => $inBranch($item)]) style="--d: {{ 0.05 * $i + 0.05 }}s">{{ $item['label'] }}</a>
                    @else
                        <p class="menu-item text-[2.5rem] leading-tight font-bold tracking-[-0.04em]" style="--d: {{ 0.05 * $i + 0.05 }}s">{{ $item['label'] }}</p>
                    @endif
                    @foreach ($item['children'] as $child)
                        @if ($child['url'])
                            <a href="{{ $child['url'] }}" {!! $attrs($child) !!} x-on:click="open = false" class="text-soft py-1 pl-1 text-lg">{{ $child['label'] }}</a>
                        @else
                            <p class="text-fade pt-2 pl-1 text-[0.9375rem] font-medium">{{ $child['label'] }}</p>
                        @endif
                        @foreach ($child['children'] as $leaf)
                            @if ($leaf['url'])
                                <a href="{{ $leaf['url'] }}" {!! $attrs($leaf) !!} x-on:click="open = false" class="text-soft py-1 pl-5 text-base">{{ $leaf['label'] }}</a>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            </nav>
        @endif
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
