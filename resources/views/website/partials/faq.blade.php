{{--
    FAQ: sticky heading on the left, the questions in one card on the right (as on the home page).
    Questions come from config('ethaika.faq'); motion and the smooth open live in resources/js/website.js.
--}}
@php
    $faq = config('ethaika.faq');
    $check = '<svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3.5 8.5 3 3 6-7"/></svg>';
    $tone ??= false;
@endphp

<section id="faq" @class(['bg-tone' => $tone]) aria-labelledby="faq-title">
    <div class="sec wrap grid gap-10 md:grid-cols-12">
        <div class="md:col-span-4">
            <div class="md:sticky md:top-28">
                <h2 id="faq-title" class="t-head"><span class="line"><span>{{ $faq['title'] }}</span></span></h2>
                <p class="faq-in will-rise text-soft mt-4 max-w-[30ch]">{{ $faq['text'] }}</p>
            </div>
        </div>
        <div class="card md:col-span-8">
            @foreach ($faq['items'] as $i => $q)
                <details class="faq will-rise group border-line px-6 md:px-8 {{ $loop->first ? '' : 'border-t' }}" @if ($i === 0) open @endif>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 py-6 text-[1.125rem] font-semibold tracking-[-0.01em] [&::-webkit-details-marker]:hidden">
                        {{ $q['q'] }}
                        <span class="bg-tone group-open:bg-accent grid size-8 shrink-0 place-items-center rounded-full transition-[transform,background-color,color] duration-300 group-open:rotate-45 group-open:text-white" aria-hidden="true">
                            <svg class="size-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3v10M3 8h10" /></svg>
                        </span>
                    </summary>
                    <div class="faq-body text-soft max-w-[62ch] pb-6">
                        @foreach ($q['a'] as $p)
                            <p @class(['mt-2' => ! $loop->first])>{{ $p }}</p>
                        @endforeach
                        @isset($q['list'])
                            <ul class="mt-3 grid gap-1.5">
                                @foreach ($q['list'] as $li)
                                    <li class="flex items-start gap-2"><span class="text-accent mt-[3px]">{!! $check !!}</span>{{ $li }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-3">{{ $q['after'] }}</p>
                        @endisset
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
