{{--
    A visitor-side error (4xx) inside the full site: header, the error panel, footer. Never indexed.
    Inside Admin the main button leads back to the dashboard instead of the home page.
    Takes the same variables as errors/partials/panel.
--}}
@php
    $inAdmin = request()->is('admin', 'admin/*');
    $primary ??= $inAdmin
        ? ['Back to dashboard', rescue(fn () => route('admin.dashboard'), url('/admin'), report: false)]
        : ['Back to home', url('/')];
    $secondary ??= $inAdmin ? null : ['Contact Us', rescue(fn () => route('contact'), url('/contact-us'), report: false)];
@endphp

<x-website :title="$title . ' · ' . rescue(fn () => \App\Helpers\Settings::appName(), config('app.name'), report: false)" :description="$text" :noindex="true">
    @include('errors.partials.panel', compact('code', 'title', 'text', 'primary', 'secondary') + ['links' => $links ?? false])
</x-website>
