{{--
    The contact / demo form. Posts to contact.store; each request becomes an Enquiry in Admin.
    $from: 'home' sends the visitor back to the home page's #contact section, anything else to /contact-us.
    $subject: the topic selected by default.
--}}
@php
    $c = config('ethaika.contact');
    $from ??= 'contact';
    $subject ??= 'demo';
    $arrow = '<svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 8h11m-4.5-4.5L13.5 8 9 12.5"/></svg>';
@endphp

<div data-contact>
@if (session('sent'))
    <div role="status" class="py-10">
        <span class="bg-accent grid size-12 place-items-center rounded-full text-white">
            <svg class="size-5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3.5 8.5 3 3 6-7" /></svg>
        </span>
        <p class="t-head mt-6">Thank you, {{ session('sent') }}.</p>
        <p class="text-soft mt-4">A member of the team will get back to you with practical next steps.</p>
    </div>
@else
    <h3 class="text-[1.375rem] font-semibold tracking-[-0.02em]">{{ $c['form_title'] }}</h3>
    <p class="text-soft mt-2">{{ $c['form_text'] }}</p>
    {{-- data-ajax: resources/js/website.js sends it in the background and shows the result here; without script it posts normally --}}
    <form method="POST" action="{{ route('contact.store') }}" class="mt-8 grid gap-6" novalidate data-ajax>
        @csrf
        <input type="hidden" name="_from" value="{{ $from }}" />
        <div class="grid gap-6 sm:grid-cols-2">
            <div class="field">
                <label for="d-name">Name</label>
                <input id="d-name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required maxlength="120" @error('name') aria-invalid="true" aria-describedby="d-name-e" @enderror />
                @error('name')<p id="d-name-e" class="err">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="d-email">Work email</label>
                <input id="d-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required maxlength="190" @error('email') aria-invalid="true" aria-describedby="d-email-e" @enderror />
                @error('email')<p id="d-email-e" class="err">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid gap-6 sm:grid-cols-2">
            <div class="field">
                <label for="d-company">Company <span class="text-soft font-normal">(optional)</span></label>
                <input id="d-company" name="company" type="text" value="{{ old('company') }}" autocomplete="organization" maxlength="160" />
            </div>
            <div class="field">
                <label for="d-phone">Phone <span class="text-soft font-normal">(optional)</span></label>
                <input id="d-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" maxlength="40" @error('phone') aria-invalid="true" aria-describedby="d-phone-e" @enderror />
                @error('phone')<p id="d-phone-e" class="err">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="field">
            <label for="d-subject">Topic</label>
            <select id="d-subject" name="subject">
                @foreach (\App\Http\Controllers\Website\ContactController::SUBJECTS as $key => $label)
                    <option value="{{ $key }}" @selected(old('subject', $subject) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="d-message">Message</label>
            <textarea id="d-message" name="message" rows="4" required maxlength="5000" @error('message') aria-invalid="true" aria-describedby="d-message-e" @enderror>{{ old('message') }}</textarea>
            @error('message')<p id="d-message-e" class="err">{{ $message }}</p>@enderror
        </div>
        <x-bot-protection />
        <p data-form-error role="alert" class="rounded-[12px] bg-[#fdecec] px-4 py-3 text-[0.9375rem] font-medium text-[#b42318]" hidden></p>
        <div>
            <button type="submit" class="btn w-full disabled:cursor-wait disabled:opacity-70 sm:w-auto">
                <span data-label>{{ $c['action'] }}</span>
                <span data-sending hidden>Sending…</span>
                {!! $arrow !!}
            </button>
        </div>
    </form>

    {{-- shown in place of the form after a successful background send --}}
    <template data-success>
        <div role="status" tabindex="-1" class="py-10 outline-none">
            <span class="bg-accent grid size-12 place-items-center rounded-full text-white">
                <svg class="size-5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3.5 8.5 3 3 6-7" /></svg>
            </span>
            <p class="t-head mt-6">Thank you, <span data-name></span>.</p>
            <p class="text-soft mt-4">A member of the team will get back to you with practical next steps.</p>
            <button type="button" data-again class="btn btn-line mt-8">Send another message</button>
        </div>
    </template>
@endif
</div>
