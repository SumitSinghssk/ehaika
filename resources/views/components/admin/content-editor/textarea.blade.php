@props(['label', 'model', 'rows' => 3, 'placeholder' => null, 'hint' => null])

<label class="block">
    <span class="block text-[12.5px] font-medium text-slate-700 dark:text-slate-200">{{ $label }}</span>
    <textarea
        x-model="{{ $model }}"
        rows="{{ $rows }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        class="{{ \App\Support\FormField::controlClasses() }} mt-1.5 resize-y px-3 py-2 text-[13px] leading-5"
    ></textarea>
    @if ($hint)
        <span class="mt-1 block text-[11.5px] leading-4 text-slate-500 dark:text-slate-400">{{ $hint }}</span>
    @endif
</label>
