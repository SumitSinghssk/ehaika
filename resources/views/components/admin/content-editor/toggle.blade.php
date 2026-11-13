{{-- On/off switch. Pass `model`, or `get` plus `set` (with VALUE as the placeholder). --}}
@props(['label', 'model' => null, 'get' => null, 'set' => null, 'hint' => null])

@php
    $get ??= $model;
    $action = $set ? str_replace('VALUE', "!({$get})", $set) : "{$model} = !{$model}";
@endphp

<div class="flex items-start justify-between gap-3">
    <div>
        <p class="text-[12.5px] font-medium text-slate-700 dark:text-slate-200">{{ $label }}</p>
        @if ($hint)
            <p class="text-[11.5px] leading-4 text-slate-500 dark:text-slate-400">{{ $hint }}</p>
        @endif
    </div>
    <button
        type="button"
        role="switch"
        :aria-checked="!!({{ $get }})"
        aria-label="{{ $label }}"
        x-on:click="{{ $action }}"
        class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition"
        :class="({{ $get }}) ? 'bg-blue-600' : 'bg-slate-300 dark:bg-slate-700'"
    >
        <span class="absolute top-0.5 left-0.5 h-4 w-4 rounded-full bg-white shadow transition" :class="({{ $get }}) ? 'translate-x-4' : ''"></span>
    </button>
</div>
