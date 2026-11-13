<x-website.bare title="Back shortly">
    @include('errors.partials.bare-body', [
        'code' => 503,
        'title' => 'Back shortly',
        'text' => 'We are making a short update to the site. Please check back in a few minutes.',
    ])
</x-website.bare>
