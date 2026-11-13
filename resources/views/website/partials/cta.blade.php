{{--
    Closing call to action: a blue-tint panel with a heading, a sentence and one button to the contact page.
    Pass $title, $text and optionally $action, $facts (list of [label, value]) and $tagline.
--}}
@php
    $title ??= config('ethaika.platform.cta.title');
    $text ??= config('ethaika.platform.cta.text');
    $action ??= 'Schedule Demo';
    $facts ??= [];
    $tagline ??= null;
    $href ??= route('contact');
@endphp

<section class="wrap pb-[clamp(72px,8vw,120px)]" aria-labelledby="cta-title">
    <div class="cta-panel will-rise bg-accent-tint grid gap-10 rounded-[28px] p-8 md:grid-cols-12 md:items-end md:p-14">
        <div class="md:col-span-7">
            <h2 id="cta-title" class="t-head max-w-[20ch]"><span class="line"><span>{{ $title }}</span></span></h2>
            <p class="text-soft mt-5 max-w-[52ch]">{{ $text }}</p>
            @if ($tagline)
                <p class="text-accent-ink mt-3 font-semibold">{{ $tagline }}</p>
            @endif
            <a href="{{ $href }}" class="btn mt-8">
                {{ $action }}
                <svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5" /></svg>
            </a>
        </div>
        @if (count($facts))
            <dl class="card grid md:col-span-5">
                @foreach ($facts as [$label, $value])
                    <div @class(['px-6 py-5', 'border-line border-t' => ! $loop->first])>
                        <dt class="text-soft text-[0.875rem] font-medium">{{ $label }}</dt>
                        <dd class="mt-1 font-semibold">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </div>
</section>
