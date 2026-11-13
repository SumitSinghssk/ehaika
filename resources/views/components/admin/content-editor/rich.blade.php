{{-- Inline text with paragraphs, bold, italic and links. --}}
@props(['label', 'model', 'rows' => 3, 'placeholder' => null, 'hint' => null])

@php
    $tool = 'grid h-7 w-7 cursor-pointer place-items-center rounded-md transition';
    $on = 'bg-slate-900 text-white dark:bg-white dark:text-slate-900';
    $off = 'text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white';
@endphp

<div x-data="inlineRich(() => {{ $model }}, (value) => ({{ $model }} = value))">
    <p class="block text-[12.5px] font-medium text-slate-700 dark:text-slate-200">{{ $label }}</p>
    <div class="{{ \App\Support\FormField::controlClasses() }} mt-1.5 overflow-hidden">
        <div class="flex items-center gap-0.5 border-b border-slate-200 p-1 dark:border-slate-700" x-on:mousedown.prevent>
            <button
                type="button"
                title="Bold (Ctrl+B)"
                aria-label="Bold"
                x-on:click="command('bold')"
                :aria-pressed="active.bold"
                class="{{ $tool }}"
                :class="active.bold ? @js($on) : @js($off)"
                x-html="icon('bold', 'h-3.5 w-3.5')"
            ></button>
            <button
                type="button"
                title="Italic (Ctrl+I)"
                aria-label="Italic"
                x-on:click="command('italic')"
                :aria-pressed="active.italic"
                class="{{ $tool }}"
                :class="active.italic ? @js($on) : @js($off)"
                x-html="icon('italic', 'h-3.5 w-3.5')"
            ></button>
            <button
                type="button"
                title="Add or edit a link"
                aria-label="Link"
                x-on:click="openLink()"
                :aria-pressed="active.link"
                class="{{ $tool }}"
                :class="active.link ? @js($on) : @js($off)"
                x-html="icon('link', 'h-3.5 w-3.5')"
            ></button>
        </div>

        <div x-show="linkUrl !== null" x-cloak class="space-y-1.5 border-b border-slate-200 bg-slate-50 p-2 dark:border-slate-700 dark:bg-slate-800/60">
            <input
                type="text"
                x-ref="linkInput"
                x-model="linkUrl"
                aria-label="Link address"
                placeholder="https://… or /contact"
                x-on:keydown.enter.prevent="applyLink()"
                x-on:keydown.escape.stop="linkUrl = null"
                class="{{ \App\Support\FormField::controlClasses() }} h-8 px-2 text-[12.5px]"
            />
            <div class="flex gap-3 text-[12px] font-semibold">
                <button type="button" x-on:click="applyLink()" class="cursor-pointer text-blue-600 hover:underline dark:text-blue-400">Apply</button>
                <button type="button" x-show="active.link" x-on:click="removeLink()" class="cursor-pointer text-red-600 hover:underline">Remove link</button>
                <button type="button" x-on:click="linkUrl = null" class="cursor-pointer text-slate-500 hover:underline">Cancel</button>
            </div>
        </div>

        <div class="relative">
            @if ($placeholder)
                <span x-show="empty" class="pointer-events-none absolute top-2 left-3 text-[13px] text-slate-400">{{ $placeholder }}</span>
            @endif

            <div
                x-ref="box"
                role="textbox"
                aria-multiline="true"
                aria-label="{{ $label }}"
                contenteditable="true"
                x-on:focus="document.execCommand('defaultParagraphSeparator', false, 'p')"
                x-on:input="emit()"
                x-on:paste="paste($event)"
                style="min-height: {{ $rows * 1.25 + 1 }}rem"
                class="px-3 py-2 text-[13px] leading-5 outline-none [&_a]:text-blue-600 [&_a]:underline dark:[&_a]:text-blue-400 [&_p+p]:mt-2"
            ></div>
        </div>
    </div>

    <p x-show="needsSelection" x-cloak class="mt-1 text-[11.5px] leading-4 text-amber-600 dark:text-amber-400">
        Select some text first, then press the link button.
    </p>
    @if ($hint)
        <p class="mt-1 text-[11.5px] leading-4 text-slate-500 dark:text-slate-400">{{ $hint }}</p>
    @endif
</div>
