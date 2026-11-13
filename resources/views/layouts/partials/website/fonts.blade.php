<link rel="preload" href="{{ asset('fonts/general-sans/GeneralSans-400.woff2') }}" as="font" type="font/woff2" crossorigin />
<link rel="preload" href="{{ asset('fonts/general-sans/GeneralSans-600.woff2') }}" as="font" type="font/woff2" crossorigin />
<style>
@foreach ([400, 500, 600, 700] as $weight)
@font-face { font-family: 'General Sans'; src: url('{{ asset("fonts/general-sans/GeneralSans-$weight.woff2") }}') format('woff2'); font-weight: {{ $weight }}; font-style: normal; font-display: swap; }
@endforeach
</style>
