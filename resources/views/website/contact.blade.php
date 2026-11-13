@php
    use App\Helpers\Settings;

    $c = config('ethaika');
    $k = $c['contact'];
    $emails = collect(Settings::emails())->filter()->values();
    $phones = collect(Settings::phones())->filter()->values();
    $addresses = collect(Settings::addresses())->map(fn ($a) => is_array($a) ? ($a['text'] ?? null) : $a)->filter()->values();
    $socials = collect(Settings::socialLinks())->map(fn ($s) => is_array($s) && filled($s['url'] ?? null) ? ['label' => $s['platform'] ?? 'Link', 'url' => $s['url']] : null)->filter()->values();
@endphp

<x-website title="Contact Us · Ethaika" :description="$k['text'] . ' ' . $k['form_text']" :breadcrumbs="[['name' => 'Contact Us', 'url' => route('contact')]]">
    @include('website.partials.page-hero', [
        'title' => $k['title'],
        'text' => [$k['text']],
    ])

    <section id="contact" class="wrap pb-[clamp(72px,8vw,120px)]" aria-label="Send us your message">
        <div class="bg-accent-tint grid gap-10 overflow-hidden rounded-[28px] p-6 md:grid-cols-12 md:p-12">
            <div class="md:col-span-5 md:py-4">
                <h2 class="t-head"><span class="line"><span>{{ $k['label'] }}</span></span></h2>
                <p class="hero-in will-rise text-soft mt-5 max-w-[40ch]">{{ $k['form_text'] }}</p>

                @if ($emails->isNotEmpty() || $phones->isNotEmpty() || $addresses->isNotEmpty() || $socials->isNotEmpty())
                    <dl class="hero-in will-rise card mt-8 grid">
                        @foreach ($emails as $email)
                            <div class="border-line px-6 py-4 {{ $loop->first ? '' : 'border-t' }}"><dt class="text-soft text-[0.875rem]">Email</dt><dd class="mt-1 font-semibold"><a class="ulink" href="mailto:{{ $email }}">{{ $email }}</a></dd></div>
                        @endforeach
                        @foreach ($phones as $phone)
                            <div class="border-line border-t px-6 py-4"><dt class="text-soft text-[0.875rem]">Phone</dt><dd class="mt-1 font-semibold"><a class="ulink" href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a></dd></div>
                        @endforeach
                        @foreach ($addresses as $address)
                            <div class="border-line border-t px-6 py-4"><dt class="text-soft text-[0.875rem]">Address</dt><dd class="mt-1 font-semibold">{!! nl2br(e($address)) !!}</dd></div>
                        @endforeach
                        @foreach ($socials as $social)
                            <div @class(['border-line px-6 py-4', 'border-t' => $emails->isNotEmpty() || $phones->isNotEmpty() || $addresses->isNotEmpty() || ! $loop->first])><dt class="text-soft text-[0.875rem]">{{ $social['label'] }}</dt><dd class="mt-1 font-semibold"><a class="ulink" href="{{ $social['url'] }}" target="_blank" rel="noopener">Follow Ethaika</a></dd></div>
                        @endforeach
                    </dl>
                @endif

                <figure class="hero-in will-rise mt-8 hidden overflow-hidden rounded-[var(--radius)] md:block">
                    <x-picture :src="$c['photos']['engineers']['src']" :alt="$c['photos']['engineers']['alt']" sizes="(min-width: 768px) 36vw, 92vw" class="aspect-[4/3] w-full object-cover" />
                </figure>
            </div>

            <div class="hero-in will-rise card p-6 md:col-span-7 md:p-10">
                @include('website.partials.contact-form', ['from' => 'contact', 'subject' => $subject ?? 'demo'])
            </div>
        </div>
    </section>

    @include('website.partials.faq', ['tone' => true])
</x-website>
