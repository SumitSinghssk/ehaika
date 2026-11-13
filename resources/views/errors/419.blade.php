@include('errors.partials.page', [
    'code' => 419,
    'title' => 'This page has expired',
    'text' => 'For your security the page timed out. Go back, refresh and try again. Anything you typed may need to be entered again.',
    'primary' => ['Go back and retry', url()->previous() !== url()->current() ? url()->previous() : url('/')],
])
