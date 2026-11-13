<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Ethaika's three articles from ethaika.com/blog, with their images, in Admin → Blog.
 *
 *   php artisan db:seed --class=EthaikaBlogSeeder
 *
 * Bodies live in database/seeders/content/ethaika-blog/*.html and images in database/seeders/assets/blog.
 * Running it again updates the same posts (matched by slug). The starter kit's demo posts and
 * categories (agency topics) are set to Inactive so the blog only shows Ethaika's articles;
 * nothing is deleted, and they can be switched back on in Admin → Blog.
 */
class EthaikaBlogSeeder extends Seeder
{
    private const FOLDER = 'blogs/ethaika';

    public function run(): void
    {
        $disk = Storage::disk('public');
        foreach (File::files(database_path('seeders/assets/blog')) as $file) {
            $disk->put(self::FOLDER.'/'.$file->getFilename(), File::get($file->getPathname()));
        }

        $categories = [
            'enterprise-ai' => BlogCategory::updateOrCreate(['slug' => 'enterprise-ai'], ['name' => 'Enterprise AI', 'status' => CommonStatusEnum::ACTIVE->value, 'description' => 'How enterprises decide which models, architectures and workflows work best.']),
            'ai-evaluation' => BlogCategory::updateOrCreate(['slug' => 'ai-evaluation'], ['name' => 'AI Evaluation', 'status' => CommonStatusEnum::ACTIVE->value, 'description' => 'Testing, evaluating and judging AI systems before they go live.']),
        ];

        $posts = [
            [
                'slug' => 'ai-adoption-is-no-longer-the-hard-part-knowing-what-works-is',
                'title' => 'Navigating AI Challenges: Cost, Quality, and Governance',
                'excerpt' => 'AI adoption is no longer the hard part. Knowing what works is. As enterprises race to deploy LLMs, copilots, RAG systems, and AI agents, a more pressing challenge is quietly emerging in boardrooms: how do you systematically determine what actually works best for your business?',
                'file' => 'ai-adoption.html',
                'image' => 'ai-adoption-what-works.webp',
                'published_at' => '2026-06-20 09:00:00',
                'categories' => ['enterprise-ai'],
            ],
            [
                'slug' => 'ai-testing-vs-ai-evaluation',
                'title' => 'AI Testing vs AI Evaluation: What’s the Real Difference?',
                'excerpt' => 'While they may sound similar, they address fundamentally different aspects of AI systems. Understanding this distinction is essential if you want to build reliable, scalable, and production-ready AI applications.',
                'file' => 'ai-testing-vs-evaluation.html',
                'image' => 'ai-testing-vs-ai-evaluation.webp',
                'published_at' => '2026-06-08 09:00:00',
                'categories' => ['ai-evaluation'],
            ],
            [
                'slug' => 'llm-as-judge-the-smarter-way-to-evaluate-ai-at-scale',
                'title' => 'LLM-as-Judge: The Smarter Way to Evaluate AI at Scale',
                'excerpt' => 'You’ve launched your AI agent. Thousands of responses are being generated every day. How many of those responses did anyone actually review? LLM-as-Judge closes the evaluation gap.',
                'file' => 'llm-as-judge.html',
                'image' => 'llm-as-judge.webp',
                'published_at' => '2026-06-07 09:00:00',
                'categories' => ['ai-evaluation'],
            ],
        ];

        $userId = User::orderBy('id')->value('id');
        $contact = route('contact');

        foreach ($posts as $p) {
            $html = File::get(database_path('seeders/content/ethaika-blog/'.$p['file']));
            $html = preg_replace_callback('/\{\{img:([a-z0-9-]+\.webp)\}\}/', fn ($m) => media_url(self::FOLDER.'/'.$m[1]), $html);
            $html = str_replace('{{contact}}', $contact, $html);

            $blog = Blog::withTrashed()->updateOrCreate(['slug' => $p['slug']], [
                'user_id' => $userId,
                'title' => $p['title'],
                'excerpt' => $p['excerpt'],
                'content' => $html,
                'featured_image' => self::FOLDER.'/'.$p['image'],
                'status' => CommonStatusEnum::ACTIVE->value,
                'published_at' => $p['published_at'],
            ]);
            $blog->categories()->sync(collect($p['categories'])->map(fn ($slug) => $categories[$slug]->id)->all());
        }

        // keep the blog Ethaika-only: switch the starter kit's demo posts and categories off (not deleted)
        Blog::whereNotIn('slug', collect($posts)->pluck('slug'))->update(['status' => CommonStatusEnum::INACTIVE->value]);
        BlogCategory::whereNotIn('slug', array_keys($categories))->update(['status' => CommonStatusEnum::INACTIVE->value]);
    }
}
