@props(['title'])

<section class="space-y-3.5 border-b border-slate-200 px-4 py-4 last:border-b-0 dark:border-slate-800">
    <h3 class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase dark:text-slate-400">{{ $title }}</h3>
    {{ $slot }}
</section>
