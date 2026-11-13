@include('errors.partials.page', [
    'code' => $exception->getStatusCode(),
    'title' => 'Something is not right',
    'text' => 'We could not complete that request. Check the address, or head back home and try again.',
])
