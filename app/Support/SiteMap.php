<?php

namespace App\Support;

use App\Enums\CommonStatusEnum;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Page;

/**
 * Every public URL of the website, for sitemap.xml (served live at /sitemap.xml
 * and written to a file by Admin → Settings → Sitemap).
 */
class SiteMap
{
    /** @return array<int, array{loc: string, lastmod: string, priority: string, image?: string}> */
    public static function urls(): array
    {
        $fixed = now()->startOfMonth()->toAtomString();
        $latestPost = rescue(fn () => Blog::published()->max('updated_at'), null, report: false);

        $urls = [
            ['loc' => route('home'), 'lastmod' => $fixed, 'priority' => '1.0'],
            ['loc' => route('platform'), 'lastmod' => $fixed, 'priority' => '0.9'],
            ['loc' => route('features'), 'lastmod' => $fixed, 'priority' => '0.9'],
            ['loc' => route('about'), 'lastmod' => $fixed, 'priority' => '0.7'],
            ['loc' => route('contact'), 'lastmod' => $fixed, 'priority' => '0.8'],
            ['loc' => route('blog.index'), 'lastmod' => $latestPost ? \Illuminate\Support\Carbon::parse($latestPost)->toAtomString() : $fixed, 'priority' => '0.8'],
        ];

        rescue(function () use (&$urls) {
            Page::published()->whereNotNull('slug')->get(['slug', 'updated_at'])->each(function (Page $page) use (&$urls) {
                $urls[] = ['loc' => route('page.show', $page->slug), 'lastmod' => $page->updated_at->toAtomString(), 'priority' => '0.4'];
            });

            BlogCategory::where('status', CommonStatusEnum::ACTIVE->value)
                ->whereHas('blogs', fn ($q) => $q->published())
                ->get(['slug', 'updated_at'])
                ->each(function (BlogCategory $category) use (&$urls) {
                    $urls[] = ['loc' => route('blog.category', $category->slug), 'lastmod' => $category->updated_at->toAtomString(), 'priority' => '0.5'];
                });

            Blog::published()->whereNotNull('slug')->latest('published_at')->get()->each(function (Blog $blog) use (&$urls) {
                $urls[] = array_filter([
                    'loc' => $blog->url(),
                    'lastmod' => $blog->updated_at->toAtomString(),
                    'priority' => '0.7',
                    'image' => $blog->imageUrl(),
                ]);
            });
        }, report: false);

        return $urls;
    }

    public static function xml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

        foreach (self::urls() as $url) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>'.htmlspecialchars($url['loc'], ENT_XML1)."</loc>\n";
            $xml .= '        <lastmod>'.$url['lastmod']."</lastmod>\n";
            $xml .= '        <priority>'.$url['priority']."</priority>\n";
            if (! empty($url['image'])) {
                $xml .= '        <image:image><image:loc>'.htmlspecialchars($url['image'], ENT_XML1)."</image:loc></image:image>\n";
            }
            $xml .= "    </url>\n";
        }

        return $xml.'</urlset>';
    }
}
