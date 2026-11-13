{{--
    Block-based content builder for pages and blog posts.
    The saved HTML goes into a hidden field named `name`, so it is saved with the surrounding form.
--}}
@props([
    'name' => 'content',
    'value' => null,
    'kind' => 'page',
    'label' => 'Content',
    'required' => false,
    'images' => [],
])

@php
    use App\Support\FormField;

    $html = (string) FormField::old($name, $value);
    $message = $errors->first($name);
    $noun = $kind === 'blog' ? 'post' : 'page';
    $config = [
        'kind' => $kind,
        'label' => $label,
        'html' => $html,
        'required' => (bool) $required,
        'images' => array_values(array_filter($images)),
        'siteUrl' => url('/'),
        'uploadUrl' => route('admin.images.store'),
        'placeholder' => asset('images/placeholder.svg'),
    ];
    $iconButton = 'grid cursor-pointer place-items-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 disabled:cursor-default disabled:opacity-40 disabled:hover:bg-transparent dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white';
@endphp

<div x-data="contentEditor(@js($config))" {{ $attributes }}>
    <textarea name="{{ $name }}" x-model="html" class="hidden" aria-hidden="true" tabindex="-1">{{ $html }}</textarea>

    {{-- Empty: pick a template --}}
    <div x-show="isEmpty" x-cloak>
        <p class="text-sm text-slate-600 dark:text-slate-300">
            Start with a ready-made {{ $noun }} layout, then change the words and pictures. You can add, move or remove blocks at any time.
        </p>

        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <template x-for="template in templates" :key="template.key">
                <button
                    type="button"
                    x-on:click="openEditor(template.key)"
                    class="group cursor-pointer overflow-hidden rounded-xl border border-slate-200 bg-white text-left transition hover:border-blue-400 hover:shadow-md focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:outline-none dark:border-slate-700 dark:bg-slate-900 dark:hover:border-blue-400"
                >
                    <span class="pointer-events-none relative block h-40 overflow-hidden border-b border-slate-100 bg-white select-none dark:border-slate-800" aria-hidden="true" x-init="$nextTick(() => fitPreview($el, 1100))">
                        <div class="ps-content absolute top-0 left-0 origin-top-left p-8" style="width: 1100px" x-html="templateMarkup(template.key)"></div>
                    </span>
                    <span class="block px-3.5 py-3">
                        <span class="flex items-center gap-1.5 text-sm font-semibold text-slate-900 group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-300">
                            <span x-html="icon('layout', 'h-4 w-4 text-slate-400 group-hover:text-blue-600')"></span>
                            <span x-text="template.name"></span>
                        </span>
                        <span class="mt-0.5 block text-xs leading-5 text-slate-500 dark:text-slate-400" x-text="template.description"></span>
                    </span>
                </button>
            </template>
        </div>

        <button
            type="button"
            x-on:click="openEditor()"
            class="mt-3 inline-flex cursor-pointer items-center gap-1.5 text-sm font-medium text-blue-600 hover:underline dark:text-blue-400"
        >
            <span x-html="icon('plus', 'h-4 w-4')"></span>
            Or start with a blank {{ $noun }}
        </button>
    </div>

    {{-- Has content: preview and open the builder --}}
    <div x-show="!isEmpty" x-cloak>
        <div class="flex flex-wrap items-center gap-2">
            <x-admin.button type="button" icon="pencil" x-on:click="openEditor()">Edit content</x-admin.button>
            <span class="text-xs text-slate-500 dark:text-slate-400" x-text="outline.length + (outline.length === 1 ? ' block' : ' blocks')"></span>
            <span x-show="changed" class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                Changed — save the {{ $noun }} to keep it
            </span>
        </div>

        <div class="mt-4 flex flex-wrap gap-1.5">
            <template x-for="(row, i) in outline" :key="i">
                <span class="inline-flex max-w-full items-center gap-1.5 rounded-md bg-slate-100 px-2 py-1 text-xs text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    <span x-html="icon(row.type, 'h-3.5 w-3.5 shrink-0 text-slate-400')"></span>
                    <span class="truncate" x-text="row.summary"></span>
                </span>
            </template>
        </div>

        <button
            type="button"
            x-on:click="openEditor()"
            title="Open the content builder"
            class="group relative mt-4 block max-h-96 w-full cursor-pointer overflow-hidden rounded-xl border border-slate-200 bg-white p-5 text-left dark:border-slate-700"
        >
            <div class="ps-content pointer-events-none" x-html="html"></div>
            <span class="absolute inset-x-0 bottom-0 h-24 bg-linear-to-t from-white to-transparent"></span>
            <span class="absolute inset-0 grid place-items-center bg-slate-900/0 transition group-hover:bg-slate-900/5">
                <span class="rounded-lg bg-white px-3 py-1.5 text-sm font-medium text-slate-900 opacity-0 shadow ring-1 ring-slate-900/10 transition group-hover:opacity-100">
                    Open the builder
                </span>
            </span>
        </button>
    </div>

    <p x-show="missing" x-cloak class="mt-2 text-sm text-red-600 dark:text-red-400">Add some content before saving this {{ $noun }}.</p>
    @if ($message)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @endif

    {{-- Full-screen builder --}}
    <template x-teleport="body">
        <div>
            <template x-if="open">
                <div
                    x-ref="dialog"
                    tabindex="-1"
                    role="dialog"
                    aria-modal="true"
                    aria-label="{{ $label }}"
                    class="admin-theme fixed inset-0 z-[100] flex flex-col bg-slate-100 outline-none dark:bg-slate-950"
                >
                    {{-- Top bar --}}
                    <header class="flex h-14 shrink-0 items-center gap-2 border-b border-slate-200 bg-white px-2 sm:px-3 dark:border-slate-800 dark:bg-slate-900">
                        <button type="button" title="Back to the form (Esc)" aria-label="Close builder" x-on:click="closeEditor()" class="{{ $iconButton }} h-9 w-9" x-html="icon('x', 'h-4.5 w-4.5')"></button>
                        <div class="mr-auto min-w-0 border-l border-slate-200 pl-3 dark:border-slate-800">
                            <p class="truncate text-[13.5px] font-semibold text-slate-900 dark:text-white">{{ $label }}</p>
                            <p class="truncate text-[12px] text-slate-500 dark:text-slate-400" x-text="ctx.title || 'Untitled {{ $noun }}'"></p>
                        </div>

                        <div class="hidden items-center gap-0.5 rounded-lg bg-slate-100 p-0.5 md:flex dark:bg-slate-800" role="radiogroup" aria-label="Preview size">
                            <template x-for="d in DEVICES" :key="d.id">
                                <button
                                    type="button"
                                    role="radio"
                                    :title="d.label"
                                    :aria-label="d.label"
                                    :aria-checked="device === d.id"
                                    x-on:click="device = d.id"
                                    class="grid h-8 w-8 cursor-pointer place-items-center rounded-md transition"
                                    :class="device === d.id ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-950 dark:text-white' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'"
                                    x-html="icon(d.icon)"
                                ></button>
                            </template>
                        </div>

                        <div class="flex items-center gap-0.5">
                            <button type="button" title="Undo (Ctrl+Z)" aria-label="Undo" x-on:click="undo()" :disabled="!past.length" class="{{ $iconButton }} h-9 w-9" x-html="icon('undo')"></button>
                            <button type="button" title="Redo (Ctrl+Shift+Z)" aria-label="Redo" x-on:click="redo()" :disabled="!future.length" class="{{ $iconButton }} h-9 w-9" x-html="icon('redo')"></button>
                            <button
                                type="button"
                                title="Blocks"
                                aria-label="Blocks"
                                x-on:click="panel = panel === 'left' ? null : 'left'"
                                class="{{ $iconButton }} h-9 w-9 lg:hidden"
                                :class="panel === 'left' ? 'bg-slate-100 dark:bg-slate-800' : ''"
                                x-html="icon('list')"
                            ></button>
                            <button
                                type="button"
                                title="Block settings"
                                aria-label="Block settings"
                                x-on:click="panel = panel === 'right' ? null : 'right'"
                                class="{{ $iconButton }} h-9 w-9 xl:hidden"
                                :class="panel === 'right' ? 'bg-slate-100 dark:bg-slate-800' : ''"
                                x-html="icon('panel')"
                            ></button>
                        </div>

                        <span class="hidden text-[12px] sm:inline" :class="changed ? 'text-amber-600 dark:text-amber-400' : 'text-slate-500 dark:text-slate-400'" role="status">
                            <span x-show="changed">Not saved yet</span>
                            <span x-show="!changed" class="inline-flex items-center gap-1"><span x-html="icon('check', 'h-3.5 w-3.5')"></span> Saved</span>
                        </span>

                        <button
                            type="button"
                            x-on:click="closeEditor()"
                            class="hidden h-9 cursor-pointer items-center rounded-lg border border-slate-200 bg-white px-3 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50 sm:inline-flex dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Done
                        </button>
                        <button
                            type="button"
                            x-on:click="saveForm()"
                            title="Save the {{ $noun }} (Ctrl+S)"
                            class="inline-flex h-9 cursor-pointer items-center gap-1.5 rounded-lg bg-blue-600 px-3 text-[13px] font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            <span x-html="icon('save')"></span>
                            Save {{ $noun }}
                        </button>
                    </header>

                    <div class="relative flex min-h-0 flex-1">
                        <div x-show="panel" class="absolute inset-0 z-20 bg-slate-950/30 xl:hidden" x-on:click="panel = null" aria-hidden="true"></div>

                        {{-- Left: block library and outline --}}
                        <aside
                            class="w-72 shrink-0 flex-col border-r border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900"
                            :class="panel === 'left' ? 'absolute inset-y-0 left-0 z-30 flex shadow-xl lg:static lg:shadow-none' : 'hidden lg:flex'"
                        >
                            <div class="grid shrink-0 grid-cols-3 gap-1 border-b border-slate-200 p-2 dark:border-slate-800">
                                <template x-for="tab in [['add', 'Add', 'plus'], ['outline', 'Outline', 'list'], ['templates', 'Layouts', 'layout']]" :key="tab[0]">
                                    <button
                                        type="button"
                                        x-on:click="leftTab = tab[0]"
                                        :aria-pressed="leftTab === tab[0]"
                                        class="inline-flex h-8 cursor-pointer items-center justify-center gap-1.5 rounded-md text-[12.5px] font-medium transition"
                                        :class="leftTab === tab[0] ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800'"
                                    >
                                        <span x-html="icon(tab[2], 'h-3.5 w-3.5')"></span>
                                        <span x-text="tab[1]"></span>
                                    </button>
                                </template>
                            </div>

                            <div class="min-h-0 flex-1 overflow-y-auto">
                                {{-- Add a block --}}
                                <div x-show="leftTab === 'add'" class="space-y-2.5 p-3">
                                    <div x-show="insertAt !== null" class="mb-2 flex items-center justify-between gap-2 rounded-lg bg-blue-50 px-3 py-2 text-[12px] text-blue-800 dark:bg-blue-500/10 dark:text-blue-200">
                                        <span x-text="insertAt === 0 ? 'Adding at the top' : 'Adding below “' + (blocks[insertAt - 1] ? summary(blocks[insertAt - 1]) : 'the last block') + '”'"></span>
                                        <button type="button" x-on:click="insertAt = null" class="cursor-pointer font-semibold hover:underline">Cancel</button>
                                    </div>

                                    <template x-for="type in BLOCK_ORDER" :key="type">
                                        <button
                                            type="button"
                                            draggable="true"
                                            x-on:click="insertBlock(type)"
                                            x-on:dragstart="startDrag($event, { kind: 'new', type })"
                                            x-on:dragend="endDrag()"
                                            title="Click to add, or drag onto the page"
                                            :aria-label="'Add ' + BLOCK_INFO[type].name"
                                            class="group block w-full cursor-pointer overflow-hidden rounded-xl border border-slate-200 bg-white text-left transition hover:border-blue-400 hover:shadow-md focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:outline-none dark:border-slate-700 dark:bg-slate-900 dark:hover:border-blue-400"
                                        >
                                            <span class="pointer-events-none relative block h-32 overflow-hidden bg-white select-none" aria-hidden="true" x-init="$nextTick(() => fitPreview($el, previewWidth(type), 128))">
                                                <div class="ps-content absolute top-0 left-0 origin-top-left p-5" :style="'width:' + previewWidth(type) + 'px'" x-html="previewMarkup(type)"></div>
                                            </span>
                                            <span class="flex items-start gap-2.5 border-t border-slate-100 px-3 py-2.5 dark:border-slate-800">
                                                <span class="mt-0.5 shrink-0 text-slate-500 transition group-hover:text-blue-600 dark:text-slate-400" x-html="icon(type)"></span>
                                                <span class="min-w-0">
                                                    <span class="block text-[13px] font-medium text-slate-900 dark:text-white" x-text="BLOCK_INFO[type].name"></span>
                                                    <span class="block text-[12px] leading-4 text-slate-500 dark:text-slate-400" x-text="BLOCK_INFO[type].description"></span>
                                                </span>
                                                <span class="ml-auto shrink-0 text-slate-300 transition group-hover:text-blue-600" x-html="icon('plus')"></span>
                                            </span>
                                        </button>
                                    </template>
                                </div>

                                {{-- Outline --}}
                                <div x-show="leftTab === 'outline'">
                                    <p x-show="!blocks.length" class="p-5 text-[13px] text-slate-500">No blocks yet.</p>
                                    <ul class="space-y-1 p-3">
                                        <template x-for="(block, index) in blocks" :key="block.id">
                                            <li
                                                draggable="true"
                                                x-on:dragstart="startDrag($event, { kind: 'move', id: block.id })"
                                                x-on:dragend="endDrag()"
                                                x-on:dragover.prevent
                                                x-on:drop="onOutlineDrop($event, index)"
                                                class="flex items-center gap-1 rounded-lg border transition"
                                                :class="(block.id === selectedId ? 'border-blue-200 bg-blue-50 dark:border-blue-500/40 dark:bg-blue-500/10' : 'border-transparent hover:bg-slate-50 dark:hover:bg-slate-800/60') + (movingId === block.id ? ' opacity-40' : '')"
                                            >
                                                <span class="grid h-9 w-7 shrink-0 cursor-grab place-items-center text-slate-400" title="Drag to reorder" x-html="icon('grip')"></span>
                                                <button type="button" x-on:click="select(block.id); scrollToBlock(block.id); panel = null" class="flex min-w-0 flex-1 cursor-pointer items-center gap-2 py-2 pr-2 text-left">
                                                    <span class="shrink-0" :class="block.id === selectedId ? 'text-blue-600' : 'text-slate-500'" x-html="icon(block.type)"></span>
                                                    <span class="min-w-0">
                                                        <span class="block truncate text-[12.5px] font-medium text-slate-900 dark:text-white" x-text="summary(block)"></span>
                                                        <span class="block text-[11px] text-slate-500 dark:text-slate-400" x-text="BLOCK_INFO[block.type].name"></span>
                                                    </span>
                                                </button>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                                {{-- Layout templates --}}
                                <div x-show="leftTab === 'templates'" class="space-y-2.5 p-3">
                                    <p class="text-[12px] leading-4 text-slate-500 dark:text-slate-400">
                                        Replace the whole {{ $noun }} with a ready-made layout. Changed your mind? Undo brings it back.
                                    </p>
                                    <template x-for="template in templates" :key="template.key">
                                        <button
                                            type="button"
                                            x-on:click="useTemplate(template.key)"
                                            class="group block w-full cursor-pointer overflow-hidden rounded-xl border border-slate-200 bg-white text-left transition hover:border-blue-400 hover:shadow-md dark:border-slate-700 dark:bg-slate-900 dark:hover:border-blue-400"
                                        >
                                            <span class="pointer-events-none relative block h-36 overflow-hidden bg-white select-none" aria-hidden="true" x-init="$nextTick(() => fitPreview($el, 1100))">
                                                <div class="ps-content absolute top-0 left-0 origin-top-left p-8" style="width: 1100px" x-html="templateMarkup(template.key)"></div>
                                            </span>
                                            <span class="block border-t border-slate-100 px-3 py-2.5 dark:border-slate-800">
                                                <span class="block text-[13px] font-medium text-slate-900 group-hover:text-blue-700 dark:text-white" x-text="template.name"></span>
                                                <span class="block text-[12px] leading-4 text-slate-500 dark:text-slate-400" x-text="template.description"></span>
                                            </span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </aside>

                        {{-- Canvas --}}
                        <main
                            x-ref="canvas"
                            class="min-w-0 flex-1 overflow-y-auto px-3 py-6 sm:px-6 sm:py-8"
                            x-on:click="select(null)"
                            x-on:dragover="onCanvasDragOver($event)"
                            x-on:drop="onCanvasDrop($event)"
                            x-on:dragleave="if (!$el.contains($event.relatedTarget)) dropIndex = null"
                        >
                            <div class="mx-auto rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5 transition-[width] duration-300" :class="[deviceWidth, device === 'mobile' ? 'p-4' : 'p-6 sm:p-8']">
                                <div x-show="blocks.length" class="ps-content">
                                    <template x-for="(block, index) in blocks" :key="block.id">
                                        <div
                                            :data-block="block.id"
                                            :draggable="!(block.type === 'rich' && selectedId === block.id)"
                                            x-on:dragstart="startDrag($event, { kind: 'move', id: block.id })"
                                            x-on:dragend="endDrag()"
                                            x-on:click.stop="if ($event.target.closest('a')) $event.preventDefault(); select(block.id)"
                                            class="group/block relative cursor-pointer rounded-[18px] outline-2 outline-offset-[5px] transition-[outline-color,opacity] duration-150"
                                            :class="(movingId === block.id ? 'opacity-40 ' : '') + (selectedId === block.id ? 'outline outline-blue-500' : 'outline outline-transparent hover:outline-blue-300/80')"
                                        >
                                            <span x-show="dropIndex === index" class="pointer-events-none absolute right-0 -top-[11px] left-0 z-20 h-[3px] rounded-full bg-blue-500" aria-hidden="true"></span>
                                            <span x-show="dropIndex === blocks.length && index === blocks.length - 1" class="pointer-events-none absolute right-0 -bottom-[11px] left-0 z-20 h-[3px] rounded-full bg-blue-500" aria-hidden="true"></span>

                                            <template x-if="block.type === 'rich' && selectedId === block.id">
                                                <div class="ps-rich cursor-auto" x-on:click.stop>
                                                    <div :data-rich="block.id"></div>
                                                </div>
                                            </template>
                                            <div x-show="!(block.type === 'rich' && selectedId === block.id)" x-html="render(block)"></div>

                                            <span
                                                class="pointer-events-none absolute -top-3 left-3 z-10 rounded-md px-2 py-0.5 text-[11px] font-semibold text-white shadow-sm transition-opacity"
                                                :class="selectedId === block.id ? 'bg-blue-600 opacity-100' : 'bg-blue-400 opacity-0 group-hover/block:opacity-100'"
                                                x-text="BLOCK_INFO[block.type].name"
                                            ></span>

                                            <div x-show="selectedId === block.id" x-on:click.stop class="absolute -top-4 right-3 z-10 flex items-center gap-0.5 rounded-lg bg-white p-0.5 shadow-md ring-1 ring-slate-900/10">
                                                <span title="Drag to move" class="grid h-7 w-7 cursor-grab place-items-center text-slate-400" x-html="icon('grip', 'h-3.5 w-3.5')"></span>
                                                <button type="button" title="Move up" aria-label="Move up" :disabled="index === 0" x-on:click="moveBlock(block.id, -1)" class="{{ $iconButton }} h-7 w-7 text-slate-600" x-html="icon('up', 'h-3.5 w-3.5')"></button>
                                                <button type="button" title="Move down" aria-label="Move down" :disabled="index === blocks.length - 1" x-on:click="moveBlock(block.id, 1)" class="{{ $iconButton }} h-7 w-7 text-slate-600" x-html="icon('down', 'h-3.5 w-3.5')"></button>
                                                <button type="button" title="Duplicate" aria-label="Duplicate" x-on:click="duplicateBlock(block.id)" class="{{ $iconButton }} h-7 w-7 text-slate-600" x-html="icon('copy', 'h-3.5 w-3.5')"></button>
                                                <button type="button" title="Add a block below" aria-label="Add a block below" x-on:click="addBelow(index)" class="{{ $iconButton }} h-7 w-7 text-slate-600" x-html="icon('plus', 'h-3.5 w-3.5')"></button>
                                                <button type="button" title="Remove" aria-label="Remove" x-on:click="removeBlock(block.id)" class="grid h-7 w-7 cursor-pointer place-items-center rounded-lg text-slate-600 transition hover:bg-red-50 hover:text-red-600" x-html="icon('trash', 'h-3.5 w-3.5')"></button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div
                                    x-show="!blocks.length"
                                    class="rounded-2xl border-2 border-dashed px-6 py-16 text-center transition-colors"
                                    :class="dropIndex !== null ? 'border-blue-400 bg-blue-50/60' : 'border-slate-200'"
                                >
                                    <span class="mx-auto block w-fit text-slate-300" x-html="icon('layout', 'h-9 w-9')"></span>
                                    <p class="mt-3 text-[15px] font-semibold text-slate-900" x-text="dropIndex !== null ? 'Drop here to add it' : 'Nothing here yet'"></p>
                                    <p class="mx-auto mt-1 max-w-sm text-[13px] text-slate-500">Start with a ready-made layout, or add blocks one by one.</p>
                                    <div class="mt-5 flex flex-wrap justify-center gap-2">
                                        <button
                                            type="button"
                                            x-on:click.stop="leftTab = 'templates'; panel = 'left'"
                                            class="inline-flex h-9 cursor-pointer items-center gap-1.5 rounded-lg bg-blue-600 px-3 text-[13px] font-semibold text-white hover:bg-blue-700"
                                        >
                                            <span x-html="icon('sparkles')"></span>
                                            Choose a layout
                                        </button>
                                        <button
                                            type="button"
                                            x-on:click.stop="addBelow(-1)"
                                            class="inline-flex h-9 cursor-pointer items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 text-[13px] font-medium text-slate-700 hover:bg-slate-50"
                                        >
                                            <span x-html="icon('plus')"></span>
                                            Add a block
                                        </button>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    x-show="blocks.length"
                                    x-on:click.stop="addBelow(blocks.length - 1)"
                                    class="mt-6 flex w-full cursor-pointer items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-slate-200 py-5 text-[13px] font-medium text-slate-500 transition hover:border-blue-400 hover:text-blue-700"
                                >
                                    <span x-html="icon('plus')"></span>
                                    Add a block
                                </button>
                            </div>

                            <p class="mx-auto mt-4 max-w-xl text-center text-[12px] text-slate-500 dark:text-slate-400">
                                This preview uses the same styles as the website. Drag blocks from the left, or drag a block to move it. Ctrl+S saves, Ctrl+Z undoes, Esc closes.
                            </p>
                        </main>

                        {{-- Right: settings for the selected block --}}
                        <aside
                            class="w-80 shrink-0 flex-col border-l border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900"
                            :class="panel === 'right' ? 'absolute inset-y-0 right-0 z-30 flex shadow-xl xl:static xl:shadow-none' : 'hidden xl:flex'"
                        >
                            <template x-for="block in (selected ? [selected] : [])" :key="block.id">
                                <div class="flex min-h-0 flex-1 flex-col">
                                    <div class="flex shrink-0 items-start gap-3 border-b border-slate-200 px-4 py-3.5 dark:border-slate-800">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300" x-html="icon(block.type)"></span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[13.5px] font-semibold text-slate-900 dark:text-white" x-text="BLOCK_INFO[block.type].name"></p>
                                            <p class="text-[12px] leading-4 text-slate-500 dark:text-slate-400" x-text="BLOCK_INFO[block.type].description"></p>
                                        </div>
                                        <button type="button" title="Deselect" aria-label="Deselect" x-on:click="select(null)" class="{{ $iconButton }} h-7 w-7" x-html="icon('x', 'h-3.5 w-3.5')"></button>
                                    </div>
                                    <div class="min-h-0 flex-1 overflow-y-auto">
                                        <x-admin.content-editor.inspector />
                                    </div>
                                    <div class="grid shrink-0 grid-cols-2 gap-2 border-t border-slate-200 p-3 dark:border-slate-800">
                                        <button
                                            type="button"
                                            x-on:click="duplicateBlock(block.id)"
                                            class="inline-flex h-8 cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white text-[12.5px] font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                                        >
                                            <span x-html="icon('copy', 'h-3.5 w-3.5')"></span>
                                            Duplicate
                                        </button>
                                        <button
                                            type="button"
                                            x-on:click="removeBlock(block.id)"
                                            class="inline-flex h-8 cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white text-[12.5px] font-medium text-red-600 hover:bg-red-50 dark:border-red-500/40 dark:bg-slate-900 dark:hover:bg-red-500/10"
                                        >
                                            <span x-html="icon('trash', 'h-3.5 w-3.5')"></span>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="!selected" class="min-h-0 flex-1 space-y-5 overflow-y-auto p-4">
                                <div>
                                    <p class="text-[13.5px] font-semibold text-slate-900 dark:text-white">{{ $label }}</p>
                                    <p class="mt-1 text-[12.5px] leading-5 text-slate-600 dark:text-slate-300">
                                        This is the body of the {{ $noun }}, shown below its title. Click a block to edit it, or add new blocks from the left.
                                    </p>
                                </div>
                                <ul class="space-y-2 text-[12.5px] leading-5 text-slate-600 dark:text-slate-300">
                                    <li class="flex gap-2"><span class="mt-0.5 shrink-0 text-emerald-600" x-html="icon('check', 'h-3.5 w-3.5')"></span> Use large, bright photos. They work best in banners.</li>
                                    <li class="flex gap-2"><span class="mt-0.5 shrink-0 text-emerald-600" x-html="icon('check', 'h-3.5 w-3.5')"></span> Keep headlines to a few words and text to two or three lines.</li>
                                    <li class="flex gap-2"><span class="mt-0.5 shrink-0 text-emerald-600" x-html="icon('check', 'h-3.5 w-3.5')"></span> Check the mobile preview: most visitors are on phones.</li>
                                </ul>
                                <div class="space-y-2 border-t border-slate-200 pt-4 dark:border-slate-800">
                                    <button
                                        type="button"
                                        x-on:click="leftTab = 'templates'; panel = 'left'"
                                        class="inline-flex h-8 w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white text-[12.5px] font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                                    >
                                        <span x-html="icon('sparkles', 'h-3.5 w-3.5')"></span>
                                        <span x-text="blocks.length ? 'Replace with a layout' : 'Choose a layout'"></span>
                                    </button>
                                    <button
                                        type="button"
                                        x-show="blocks.length"
                                        x-on:click="clearAll()"
                                        class="inline-flex h-8 w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white text-[12.5px] font-medium text-red-600 hover:bg-red-50 dark:border-red-500/40 dark:bg-slate-900 dark:hover:bg-red-500/10"
                                    >
                                        <span x-html="icon('trash', 'h-3.5 w-3.5')"></span>
                                        Remove all blocks
                                    </button>
                                    <p class="text-[11.5px] leading-4 text-slate-500 dark:text-slate-400">Changed your mind? Undo brings it back.</p>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>
