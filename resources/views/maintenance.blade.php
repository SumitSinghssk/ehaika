<x-website.bare :title="$maintenance['title']">
    @if (! empty($preview))
        <x-slot:before>
            <div class="bg-ink fixed inset-x-0 top-0 z-10 px-4 py-2 text-center text-sm font-medium text-white">
                Preview: this is what visitors see while maintenance mode is on.
                <a href="{{ route('admin.settings.index', ['tab' => 'maintenance']) }}" class="ml-1 underline underline-offset-2">Back to settings</a>
            </div>
        </x-slot:before>
    @endif

    @include('errors.partials.bare-body', [
        'code' => null,
        'title' => $maintenance['title'],
        'text' => $maintenance['message'],
        'retry' => false,
        'extra' => $back
            ? '<p class="bg-accent-tint text-accent-ink mt-8 inline-flex items-center gap-2 rounded-full px-4 py-2 text-[0.9375rem] font-medium"><span class="bg-accent size-2 animate-pulse rounded-full"></span>Expected back '
                . e($back->isToday() ? 'today at ' . $back->format('H:i') : $back->format('D j M, H:i')) . '</p>'
            : null,
    ])
</x-website.bare>
