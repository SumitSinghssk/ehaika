@props(['label', 'model', 'swatches' => 'SECTION_COLOURS'])

<div>
    <p class="text-[12.5px] font-medium text-slate-700 dark:text-slate-200">{{ $label }}</p>
    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
        <template x-for="colour in {{ $swatches }}" :key="colour">
            <button
                type="button"
                x-on:click="{{ $model }} = colour"
                :title="colour"
                :aria-label="'Use ' + colour"
                :style="'background-color:' + colour"
                class="h-7 w-7 cursor-pointer rounded-full ring-1 ring-slate-900/10 transition hover:scale-110"
                :class="({{ $model }} || '').toLowerCase() === colour ? 'outline-2 outline-offset-2 outline-blue-600' : ''"
            ></button>
        </template>
        <label class="relative h-7 w-7 cursor-pointer overflow-hidden rounded-full ring-1 ring-slate-900/10" title="Pick any colour">
            <span class="absolute inset-0" style="background: conic-gradient(red, yellow, lime, aqua, blue, magenta, red)"></span>
            <input type="color" x-model="{{ $model }}" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" aria-label="Pick any colour" />
        </label>
    </div>
</div>
