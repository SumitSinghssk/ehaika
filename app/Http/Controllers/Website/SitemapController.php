<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Support\SiteMap;

class SitemapController extends Controller
{
    public function __invoke()
    {
        return response(SiteMap::xml(), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
