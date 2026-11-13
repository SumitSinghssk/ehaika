{{-- Settings for the selected block. `block` is the selected block. --}}
@php
    $align = ['left' => 'Left', 'center' => 'Centre', 'right' => 'Right'];
@endphp

<template x-if="block.type === 'intro'">
    <x-admin.content-editor.section title="Content">
        <x-admin.content-editor.text label="Small label" model="block.eyebrow" placeholder="Optional, e.g. About us" />
        <x-admin.content-editor.textarea label="Headline" model="block.heading" rows="2" />
        <x-admin.content-editor.rich label="Lead" model="block.lead" rows="4" hint="One or two sentences." />
    </x-admin.content-editor.section>
</template>

<template x-if="block.type === 'banner'">
    <div>
        <x-admin.content-editor.section title="Image">
            <x-admin.content-editor.image model="block.image" focus="block.focus" />
            <x-admin.content-editor.segmented
                label="Image position"
                get="block.reverse ? 'right' : 'left'"
                set="block.reverse = VALUE === 'right'"
                :options="['left' => 'Left', 'right' => 'Right']"
            />
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="Content">
            <x-admin.content-editor.text label="Small label" model="block.eyebrow" />
            <x-admin.content-editor.textarea label="Headline" model="block.heading" rows="2" hint="Keep it to a few words." />
            <x-admin.content-editor.rich label="Text" model="block.text" rows="4" hint="Press Enter to start a new paragraph." />
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="List">
            <x-admin.content-editor.segmented
                label="Style"
                get="block.listStyle"
                set="setListStyle(block, VALUE)"
                :options="['none' => 'None', 'points' => 'Ticks', 'steps' => 'Steps']"
            />
            <div x-show="block.listStyle !== 'none'">
                <x-admin.content-editor.items
                    list="block.items"
                    create="{ strong: '', text: '' }"
                    title="item.strong || item.text.replace(/<[^>]+>/g, '')"
                    add-label="Add a line"
                    max="8"
                >
                    <x-admin.content-editor.text label="Bold start" model="item.strong" placeholder="e.g. Step one" />
                    <x-admin.content-editor.rich label="Rest of the line" model="item.text" rows="2" />
                </x-admin.content-editor.items>
            </div>
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="Colours">
            <x-admin.content-editor.colour label="Background" model="block.background" swatches="BANNER_COLOURS" />
            <x-admin.content-editor.toggle label="White text" model="block.light" hint="Turn on for dark backgrounds." />
        </x-admin.content-editor.section>
    </div>
</template>

<template x-if="block.type === 'features'">
    <div>
        <x-admin.content-editor.section title="Heading">
            <x-admin.content-editor.text label="Heading" model="block.heading" placeholder="Optional" />
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="Cards">
            <x-admin.content-editor.items
                list="block.items"
                create="{ title: '', text: '', image: { src: '', alt: '' } }"
                title="item.title"
                add-label="Add a card"
                min="1"
                max="8"
            >
                <x-admin.content-editor.text label="Title" model="item.title" />
                <x-admin.content-editor.rich label="Text" model="item.text" rows="3" />
                <x-admin.content-editor.image label="Image" model="item.image" optional />
            </x-admin.content-editor.items>
            <p class="text-[11.5px] leading-4 text-slate-500 dark:text-slate-400">Cards sit up to four in a row on large screens and two on phones.</p>
        </x-admin.content-editor.section>
    </div>
</template>

<template x-if="block.type === 'tiles'">
    <div>
        <x-admin.content-editor.section title="Heading">
            <x-admin.content-editor.text label="Heading" model="block.heading" placeholder="Optional" />
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="Tiles">
            <x-admin.content-editor.items
                list="block.items"
                create="{ image: { src: '', alt: '' }, caption: '' }"
                title="item.caption || ('Image ' + (i + 1))"
                add-label="Add a tile"
                min="1"
                max="4"
            >
                <x-admin.content-editor.image model="item.image" />
                <x-admin.content-editor.text label="Caption" model="item.caption" placeholder="Optional, shown on the image" />
            </x-admin.content-editor.items>
        </x-admin.content-editor.section>
    </div>
</template>

<template x-if="block.type === 'wide'">
    <div>
        <x-admin.content-editor.section title="Image">
            <x-admin.content-editor.image model="block.image" focus="block.focus" />
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="Text over the image">
            <x-admin.content-editor.text label="Headline" model="block.heading" placeholder="Optional" />
            <x-admin.content-editor.rich label="Text" model="block.text" rows="3" placeholder="Optional" />
        </x-admin.content-editor.section>
    </div>
</template>

<template x-if="block.type === 'text'">
    <x-admin.content-editor.section title="Content">
        <x-admin.content-editor.text label="Small label" model="block.eyebrow" placeholder="Optional" />
        <x-admin.content-editor.text label="Heading" model="block.heading" placeholder="Optional" />
        <x-admin.content-editor.rich label="Text" model="block.body" rows="10" hint="Press Enter to start a new paragraph." />
    </x-admin.content-editor.section>
</template>

<template x-if="block.type === 'quote'">
    <x-admin.content-editor.section title="Content">
        <x-admin.content-editor.textarea label="Quote" model="block.text" rows="4" />
        <x-admin.content-editor.text label="Who said it" model="block.by" placeholder="e.g. Priya, Mumbai" />
    </x-admin.content-editor.section>
</template>

<template x-if="block.type === 'note'">
    <x-admin.content-editor.section title="Content">
        <x-admin.content-editor.text label="Bold label" model="block.label" placeholder="e.g. Key takeaway" />
        <x-admin.content-editor.rich label="Text" model="block.text" rows="4" />
    </x-admin.content-editor.section>
</template>

<template x-if="block.type === 'rich'">
    <x-admin.content-editor.section title="Rich text">
        <p class="text-[12.5px] leading-5 text-slate-600 dark:text-slate-300">
            Edit this block right on the page. Use the toolbar for headings, lists, links, tables and images.
        </p>
    </x-admin.content-editor.section>
</template>

<template x-if="block.type === 'cta'">
    <div>
        <x-admin.content-editor.section title="Content">
            <x-admin.content-editor.text label="Small label" model="block.eyebrow" placeholder="Optional" />
            <x-admin.content-editor.textarea label="Headline" model="block.heading" rows="2" />
            <x-admin.content-editor.rich label="Text" model="block.text" rows="3" placeholder="Optional" />
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="Buttons">
            <x-admin.content-editor.text label="Main button text" model="block.primary.label" />
            <x-admin.content-editor.text label="Main button link" model="block.primary.url" placeholder="/contact or https://…" />
            <x-admin.content-editor.text label="Second button text" model="block.secondary.label" placeholder="Optional" />
            <div x-show="block.secondary.label">
                <x-admin.content-editor.text label="Second button link" model="block.secondary.url" />
            </div>
            <p class="text-[11.5px] leading-4 text-slate-500 dark:text-slate-400">Leave a button's text empty to hide it.</p>
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="Colours">
            <x-admin.content-editor.colour label="Background" model="block.background" />
            <x-admin.content-editor.toggle label="White text" model="block.light" hint="Turn on for dark backgrounds." />
        </x-admin.content-editor.section>
    </div>
</template>

<template x-if="block.type === 'faq'">
    <div>
        <x-admin.content-editor.section title="Content">
            <x-admin.content-editor.text label="Heading" model="block.heading" placeholder="Optional" />
            <x-admin.content-editor.rich label="Intro" model="block.intro" rows="2" placeholder="Optional" />
        </x-admin.content-editor.section>
        <x-admin.content-editor.section title="Questions">
            <x-admin.content-editor.items list="block.items" create="{ q: '', a: '' }" title="item.q" add-label="Add a question" min="1" max="30">
                <x-admin.content-editor.textarea label="Question" model="item.q" rows="2" />
                <x-admin.content-editor.rich label="Answer" model="item.a" rows="4" hint="Press Enter to start a new paragraph." />
            </x-admin.content-editor.items>
            <p class="text-[11.5px] leading-4 text-slate-500 dark:text-slate-400">
                On the site, answers open when a question is clicked. All answers are shown here so you can edit them.
            </p>
        </x-admin.content-editor.section>
    </div>
</template>

{{-- Style options shared by every block --}}
<x-admin.content-editor.section title="Style">
    <div x-show="hasStyle('align')">
        <x-admin.content-editor.segmented label="Alignment" get="styleValue('align')" set="setStyle({ align: VALUE })" :options="$align" />
    </div>
    <div x-show="hasStyle('size')">
        <x-admin.content-editor.segmented
            label="Heading size"
            get="styleValue('size')"
            set="setStyle({ size: VALUE })"
            :options="['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large']"
        />
    </div>
    <div x-show="hasStyle('width')">
        <x-admin.content-editor.segmented
            label="Width"
            get="styleValue('width')"
            set="setStyle({ width: VALUE })"
            :options="['narrow' => 'Narrow', 'wide' => 'Wide', 'full' => 'Full']"
        />
    </div>
    <div x-show="hasStyle('bg')" class="space-y-3.5">
        <x-admin.content-editor.toggle label="Background colour" get="!!block.style?.bg" set="toggleBg(VALUE)" />
        <div x-show="block.style?.bg" class="space-y-3.5">
            <x-admin.content-editor.colour label="Colour" model="block.style.bg" />
            <x-admin.content-editor.toggle label="White text" get="!!block.style?.light" set="setStyle({ light: VALUE })" hint="Turn on for dark backgrounds." />
        </div>
    </div>
    <div x-show="hasStyle('space')">
        <x-admin.content-editor.segmented
            label="Space around"
            get="styleValue('space')"
            set="setStyle({ space: VALUE })"
            :options="['tight' => 'Tight', 'normal' => 'Normal', 'roomy' => 'Roomy', 'xl' => 'Extra']"
        />
    </div>
</x-admin.content-editor.section>
