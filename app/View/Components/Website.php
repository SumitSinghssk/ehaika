<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

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
