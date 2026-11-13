<?php

namespace App\Http\Controllers\Website;

use App\Enums\CommonStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Helpers\Settings;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        return $this->listing(null);
    }

    public function category(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->where('status', CommonStatusEnum::ACTIVE->value)->firstOrFail();

        return $this->listing($category);
    }

    public function show(string $slug)
    {
        $blog = Blog::published()->with(['categories', 'author'])->where('slug', $slug)->firstOrFail();

        $categoryIds = $blog->categories->pluck('id');
        $related = Blog::published()
            ->whereKeyNot($blog->id)
            ->when($categoryIds->isNotEmpty(), fn ($q) => $q->orderByRaw(
                'exists (select 1 from blog_category_blog bcb where bcb.blog_id = blogs.id and bcb.blog_category_id in ('.$categoryIds->implode(',').')) desc'
            ))
            ->latest('published_at')
            ->with('categories')
            ->take(3)
            ->get();

        return view('website.blog.show', compact('blog', 'related'));
    }

    public function feed()
    {
        $posts = Blog::published()->latest('published_at')->take(20)->get();

        return response()
            ->view('website.blog.feed', ['posts' => $posts, 'siteName' => Settings::appName()])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    private function listing(?BlogCategory $category)
    {
        $search = trim((string) request()->query('q', ''));
        $search = mb_substr($search, 0, 80);

        $posts = Blog::published()
            ->with('categories')
            ->when($category, fn ($q) => $q->whereHas('categories', fn ($c) => $c->whereKey($category->id)))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', '%'.addcslashes($search, '%_\\').'%')
                ->orWhere('excerpt', 'like', '%'.addcslashes($search, '%_\\').'%')))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        abort_if($posts->currentPage() > max(1, $posts->lastPage()), 404);

        $featured = null;
        $grid = $posts->getCollection();

        $categories = BlogCategory::where('status', CommonStatusEnum::ACTIVE->value)
            ->whereHas('blogs', fn ($q) => $q->published())
            ->withCount(['blogs' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get();
        $total = Blog::published()->count();

        return view('website.blog.index', compact('posts', 'categories', 'category', 'featured', 'grid', 'search', 'total'));
    }
}
