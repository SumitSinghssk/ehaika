<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Public website layout. Each page passes its own SEO defaults; a record in
 * Admin → SEO for the same path overrides them.
 */
class Website extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $image = null,
        public string $type = 'website',
        public array $schema = [],
        public bool $noindex = false,
        public array $breadcrumbs = [],
        public ?string $published = null,
        public ?string $modified = null,
        public ?string $seoKey = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('layouts.website');
    }
}
