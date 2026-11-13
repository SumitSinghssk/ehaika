<x-website.bare title="Something went wrong">
    @include('errors.partials.bare-body', [
        'code' => 500,
        'title' => 'Something went wrong',
        'text' => 'We hit an unexpected error on our side and the team has been notified. Please try again in a moment.',
    ])
</x-website.bare>
