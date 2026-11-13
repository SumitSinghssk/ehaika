@props(['title', 'back' => null])

@php
    use App\Helpers\Settings;

    $appName = rescue(fn () => Settings::appName(), config('app.name', 'Ethaika'), report: false);
    $logoSetting = rescue(fn () => Settings::logoLight(), null, report: false);
    $favicon = rescue(fn () => Settings::favicon(), null, report: false);
    $logo = $logoSetting ?: asset('images/site/ethaika-logo.webp');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="robots" content="noindex, nofollow" />
        <title>{{ $title }} · {{ $appName }}</title>
        @if ($favicon)
            <link rel="icon" href="{{ $favicon }}" />
        @endif
        @include('layouts.partials.website.fonts')
        @vite(['resources/css/website.css'])
    </head>

    <body class="site-light bg-paper text-ink min-h-screen antialiased">
        {{ $before ?? '' }}
        <div class="relative flex min-h-screen flex-col overflow-hidden">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[80vh] bg-[radial-gradient(60%_60%_at_50%_0%,#e7eeff_0%,#ffffff_70%)]" aria-hidden="true"></div>
            <header class="wrap flex h-[72px] items-center">
                <a href="{{ url('/') }}" aria-label="{{ $appName }} home">
                    <img src="{{ $logo }}" alt="{{ $appName }}" class="h-9 w-auto {{ $logoSetting ? '' : 'brightness-0' }}" />
                </a>
            </header>
            <main id="main" class="wrap flex flex-1 items-center justify-center py-16 text-center">
                <div class="w-full">{{ $slot }}</div>
            </main>
            <footer class="wrap text-soft pb-8 text-center text-[0.875rem]">© {{ now()->year }} {{ config('ethaika.copyright', $appName) }}</footer>
        </div>
    </body>
</html>
