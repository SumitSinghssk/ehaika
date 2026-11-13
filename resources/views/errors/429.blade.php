@include('errors.partials.page', [
    'code' => 429,
    'title' => 'Too many requests',
    'text' => 'We received a lot of requests from you in a short time. Wait a minute, then try again.',
])
