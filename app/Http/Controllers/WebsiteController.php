<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.home.index', ['posts' => $this->latestPosts(3)]);
    }

    public function platform()
    {
        return view('website.platform');
    }

    public function features()
    {
        return view('website.features');
    }

    public function about()
    {
        return view('website.about', ['posts' => $this->latestPosts(2)]);
    }

    private function latestPosts(int $count)
    {
        return rescue(fn () => Blog::published()->with('categories')->latest('published_at')->take($count)->get(), collect(), report: false);
    }
}
