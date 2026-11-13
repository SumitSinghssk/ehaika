{{-- Image chooser: upload, reuse an image already on this page, alt text and optional crop focus. --}}
@props(['label' => 'Image', 'model', 'focus' => null, 'optional' => false])

<div x-data="blockImage()" class="space-y-2.5">
    <p class="text-[12.5px] font-medium text-slate-700 dark:text-slate-200">
        {{ $label }}
        @if ($optional)
            <span class="ml-1 font-normal text-slate-400">(optional)</span>
        @endif
    </p>

    <div class="relative overflow-hidden rounded-lg border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/60">
        <img x-show="{{ $model }}.src" :src="{{ $model }}.src" alt="" class="aspect-[4/3] w-full object-cover" />
        <button
            type="button"
            x-show="!{{ $model }}.src"
            x-on:click="$refs.file.click()"
            class="flex aspect-[4/3] w-full cursor-pointer flex-col items-center justify-center gap-1.5 text-slate-400 transition hover:text-slate-600 dark:hover:text-slate-200"
        >
            <span x-html="icon('image', 'h-7 w-7')"></span>
            <span class="text-[12px] font-medium">Upload an image</span>
        </button>
        <div x-show="uploading" x-cloak class="absolute inset-0 grid place-items-center bg-white/70 dark:bg-slate-900/70">
            <span class="h-6 w-6 animate-spin rounded-full border-2 border-blue-600 border-t-transparent"></span>
        </div>
        <button
            type="button"
            x-show="{{ $model }}.src"
            x-on:click="{{ $model }}.src = ''"
            aria-label="Remove image"
            class="absolute top-2 right-2 grid h-7 w-7 cursor-pointer place-items-center rounded-full bg-white/90 text-slate-700 shadow ring-1 ring-slate-900/10 transition hover:text-red-600"
            x-html="icon('x', 'h-3.5 w-3.5')"
        ></button>
    </div>

    <div class="flex gap-1.5">
        <button
            type="button"
            x-on:click="$refs.file.click()"
            :disabled="uploading"
            class="inline-flex h-8 flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white text-[12px] font-medium text-slate-700 transition hover:bg-slate-50 disabled:opacity-60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
        >
            <span x-html="icon('upload', 'h-3.5 w-3.5')"></span>
            <span x-text="{{ $model }}.src ? 'Replace' : 'Upload'"></span>
        </button>
        <button
            type="button"
            x-show="ctx.images.length"
            x-on:click="picking = !picking"
            :aria-expanded="picking"
            class="inline-flex h-8 flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-lg border text-[12px] font-medium transition"
            :class="picking ? 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-500/40 dark:bg-blue-500/10 dark:text-blue-300' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800'"
        >
            <span x-html="icon('images', 'h-3.5 w-3.5')"></span>
            Page images
        </button>
        <input x-ref="file" type="file" accept="image/jpeg,image/png,image/webp" class="hidden" x-on:change="uploadFile($event, {{ $model }})" />
    </div>

    <div x-show="picking" x-cloak class="grid grid-cols-4 gap-1.5">
        <template x-for="pic in ctx.images" :key="pic.src">
            <button
                type="button"
                x-on:click="{{ $model }}.src = pic.src; {{ $model }}.alt = {{ $model }}.alt || pic.alt; picking = false"
                class="cursor-pointer overflow-hidden rounded-md ring-1 ring-slate-200 transition hover:ring-2 hover:ring-blue-500 dark:ring-slate-700"
                :class="{{ $model }}.src === pic.src ? 'ring-2 ring-blue-600' : ''"
            >
                <img :src="pic.src" alt="" class="aspect-square w-full object-cover" />
            </button>
        </template>
    </div>

    <p x-show="error" x-cloak x-text="error" class="text-[12px] text-red-600"></p>

    <div x-show="{{ $model }}.src" class="space-y-2.5">
        <x-admin.content-editor.text
            label="Alt text"
            model="{{ $model }}.alt"
            placeholder="Describe the image"
            hint="Read out by screen readers and used by search engines."
        />
        @if ($focus)
            <x-admin.content-editor.segmented
                label="Keep in view when cropped"
                :model="$focus"
                :options="['top' => 'Top', '' => 'Auto', 'center' => 'Middle', 'bottom' => 'Bottom']"
            />
        @endif
    </div>
</div>
