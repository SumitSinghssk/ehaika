<x-website.bare title="Something went wrong">
    @include('errors.partials.bare-body', [
        'code' => $exception->getStatusCode(),
        'title' => 'Something went wrong',
        'text' => 'The site could not complete that request. Please try again in a moment.',
    ])
</x-website.bare>
