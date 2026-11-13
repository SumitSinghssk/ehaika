{{--
    A row of buttons for picking one value.
    Pass `model` for a plain property, or `get` plus `set` (with VALUE as the placeholder) for custom logic.
--}}
@props(['label', 'options', 'model' => null, 'get' => null, 'set' => null])

@php
    $get ??= $model;
@endphp

<div>
    <p class="text-[12.5px] font-medium text-slate-700 dark:text-slate-200">{{ $label }}</p>
    <div
        class="mt-1.5 grid gap-0.5 rounded-lg bg-slate-100 p-0.5 dark:bg-slate-800"
        style="grid-template-columns: repeat({{ count($options) }}, minmax(0, 1fr))"
        role="radiogroup"
        aria-label="{{ $label }}"
    >
        @foreach ($options as $value => $text)
            @php
                $literal = "'" . e((string) $value) . "'";
                $action = $set ? str_replace('VALUE', $literal, $set) : "{$model} = {$literal}";
            @endphp

            <button
                type="button"
                role="radio"
                x-on:click="{{ $action }}"
                :aria-checked="(({{ $get }}) ?? '') === {{ $literal }}"
                class="h-7 cursor-pointer truncate rounded-md px-1.5 text-[12px] font-medium transition"
                :class="(({{ $get }}) ?? '') === {{ $literal }} ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-950 dark:text-white' : 'text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white'"
            >
                {{ $text }}
            </button>
        @endforeach
    </div>
</div>
