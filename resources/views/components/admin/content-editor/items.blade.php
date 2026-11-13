{{--
    A collapsible list of repeated items. Fields in the slot use `item` and `i`.
    `list` is the JS expression of the array, `create` a JS object literal for a new item,
    `title` a JS expression for the row label.
--}}
@props(['list', 'create', 'title', 'addLabel' => 'Add an item', 'min' => 0, 'max' => 30])

<div class="space-y-2" x-data="{ expanded: 0 }">
    <template x-for="(item, i) in {{ $list }}" :key="item._k || i">
        <div class="overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-1 bg-slate-50 pr-1 dark:bg-slate-800/60">
                <button
                    type="button"
                    x-on:click="expanded = expanded === i ? -1 : i"
                    :aria-expanded="expanded === i"
                    class="flex min-w-0 flex-1 cursor-pointer items-center gap-2 px-3 py-2 text-left"
                >
                    <span class="text-[11px] font-semibold text-slate-400" x-text="i + 1"></span>
                    <span class="truncate text-[12.5px] font-medium text-slate-800 dark:text-slate-100" x-text="({{ $title }}) || 'Untitled'"></span>
                    <span
                        class="ml-auto shrink-0 text-slate-400 transition"
                        :class="expanded === i ? 'rotate-180' : ''"
                        x-html="icon('chevron', 'h-3.5 w-3.5')"
                    ></span>
                </button>
                <button
                    type="button"
                    title="Move up"
                    aria-label="Move up"
                    x-on:click="moveItem({{ $list }}, i, -1); expanded = i - 1"
                    :disabled="i === 0"
                    class="grid h-7 w-7 cursor-pointer place-items-center rounded-md text-slate-500 hover:bg-slate-200/70 disabled:cursor-default disabled:opacity-30 dark:hover:bg-slate-700"
                    x-html="icon('up', 'h-3.5 w-3.5')"
                ></button>
                <button
                    type="button"
                    title="Move down"
                    aria-label="Move down"
                    x-on:click="moveItem({{ $list }}, i, 1); expanded = i + 1"
                    :disabled="i === {{ $list }}.length - 1"
                    class="grid h-7 w-7 cursor-pointer place-items-center rounded-md text-slate-500 hover:bg-slate-200/70 disabled:cursor-default disabled:opacity-30 dark:hover:bg-slate-700"
                    x-html="icon('down', 'h-3.5 w-3.5')"
                ></button>
                <button
                    type="button"
                    title="Remove"
                    aria-label="Remove"
                    x-on:click="removeItem({{ $list }}, i, {{ (int) $min }})"
                    :disabled="{{ $list }}.length <= {{ (int) $min }}"
                    class="grid h-7 w-7 cursor-pointer place-items-center rounded-md text-slate-500 hover:bg-red-50 hover:text-red-600 disabled:cursor-default disabled:opacity-30 dark:hover:bg-red-500/10"
                    x-html="icon('trash', 'h-3.5 w-3.5')"
                ></button>
            </div>
            <div x-show="expanded === i" class="space-y-3 border-t border-slate-200 p-3 dark:border-slate-700">
                {{ $slot }}
            </div>
        </div>
    </template>

    <button
        type="button"
        x-show="{{ $list }}.length < {{ (int) $max }}"
        x-on:click="addItem({{ $list }}, {{ $create }}, {{ (int) $max }}); expanded = {{ $list }}.length - 1"
        class="flex h-9 w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-dashed border-slate-300 text-[12.5px] font-medium text-slate-600 transition hover:border-blue-400 hover:text-blue-700 dark:border-slate-700 dark:text-slate-300"
    >
        <span x-html="icon('plus', 'h-3.5 w-3.5')"></span>
        {{ $addLabel }}
    </button>
</div>
