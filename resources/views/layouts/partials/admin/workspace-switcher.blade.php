@php
    $block = $block ?? false;
    $keys = array_keys($workspaces);
@endphp

@if (count($workspaces) > 1)
    <div
        role="radiogroup"
        aria-label="Switch workspace"
        style="grid-template-columns: repeat({{ count($keys) }}, minmax(0, 1fr)); --ws-count: {{ count($keys) }}; --ws-index: {{ (int) array_search($workspace, $keys, true) }}"
        x-data="{
            step(by) {
                const keys = Object.keys(this.workspaces)
                this.setWorkspace(keys[(keys.indexOf(this.workspace) + by + keys.length) % keys.length], false)
                this.$nextTick(() => this.$root.querySelector('[aria-checked=true]')?.focus())
            },
        }"
        x-effect="$el.style.setProperty('--ws-index', Math.max(0, Object.keys(workspaces).indexOf(workspace)))"
        x-on:keydown.arrow-right.prevent="step(1)"
        x-on:keydown.arrow-down.prevent="step(1)"
        x-on:keydown.arrow-left.prevent="step(-1)"
        x-on:keydown.arrow-up.prevent="step(-1)"
        @class([
            'relative grid shrink-0 items-center rounded-lg border border-slate-200 bg-slate-100/80 p-0.5 dark:border-slate-800 dark:bg-slate-900',
            'w-full' => $block,
        ])
    >
        {{-- Sliding pill behind the active workspace --}}
        <span
            aria-hidden="true"
            class="pointer-events-none absolute inset-y-0.5 left-0.5 w-[calc((100%-0.25rem)/var(--ws-count))] translate-x-[calc(100%*var(--ws-index))] rounded-md bg-white shadow-xs ring-1 ring-slate-200/80 transition-transform duration-200 ease-out motion-reduce:transition-none dark:bg-slate-800 dark:ring-slate-700"
        ></span>

        @foreach ($workspaces as $key => $item)
            @php($js = \Illuminate\Support\Js::from($key))
            <button
                type="button"
                role="radio"
                x-on:click="setWorkspace({{ $js }})"
                aria-checked="{{ $key === $workspace ? 'true' : 'false' }}"
                x-bind:aria-checked="(workspace === {{ $js }}).toString()"
                tabindex="{{ $key === $workspace ? '0' : '-1' }}"
                x-bind:tabindex="workspace === {{ $js }} ? 0 : -1"
                title="{{ $item['label'] }}: {{ $item['hint'] ?? '' }}"
                aria-label="{{ $item['label'] }} workspace"
                @class([
                    'group/ws relative z-10 inline-flex min-w-7 cursor-pointer items-center justify-center gap-1.5 rounded-md text-xs font-semibold text-slate-500 transition-colors hover:text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:outline-none aria-checked:text-slate-900 dark:text-slate-400 dark:hover:text-white dark:aria-checked:text-white',
                    'h-7 px-1.5 sm:px-3' => ! $block,
                    'h-8 px-3' => $block,
                ])
            >
                <x-admin.icon
                    :name="$item['icon']"
                    class="h-3.5 w-3.5 shrink-0 transition-colors group-aria-checked/ws:text-blue-600 dark:group-aria-checked/ws:text-blue-400"
                />
                <span @class(['hidden sm:inline' => ! $block])>{{ $item['label'] }}</span>
            </button>
        @endforeach
    </div>
@endif
