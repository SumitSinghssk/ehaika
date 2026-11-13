<?php

namespace App\Models;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'status' => CommonStatusEnum::class,
        'published_at' => 'datetime',
        'deleted_data' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_blog');
    }

    public function seo()
    {
        return $this->hasOne(Seo::class, 'slug', 'slug');
    }

    /** Live on the website: active, and its publish date has come. */
    public function scopePublished($query)
    {
        return $query->where('status', CommonStatusEnum::ACTIVE->value)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function url(): string
    {
        return route('blog.show', $this->slug);
    }

    public function imageUrl(): ?string
    {
        return $this->featured_image ? media_url($this->featured_image) : null;
    }

    /** Minutes to read at about 220 words a minute. */
    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->content)) / 220));
    }

    public function summary(int $limit = 160): string
    {
        $text = $this->excerpt ?: strip_tags((string) $this->content);

        return \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', html_entity_decode($text))), $limit);
    }

    protected static function booted(): void
    {
        static::deleting(function (Blog $blog) {
            if (! $blog->isForceDeleting()) {
                $blog->deleted_data = [
                    'slug' => $blog->slug,
                    'title' => $blog->title,
                ];

                $blog->slug = null;

                $blog->saveQuietly();
            }
        });

        static::restoring(function (Blog $blog) {
            if ($blog->deleted_data) {
                $blog->slug = $blog->deleted_data['slug'] ?? $blog->slug;
                $blog->deleted_data = null;
            }
        });
    }
}
