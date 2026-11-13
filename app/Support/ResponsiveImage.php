<?php

namespace App\Support;

use App\Services\Media\MediaLibrary;
use GdImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ResponsiveImage
{
    public const WIDTHS = [480, 800, 1200];

    public const FOLDER = '_responsive';

    private const QUALITY = ['avif' => 52, 'webp' => 80];

    private const SOURCES = ['jpg', 'jpeg', 'png', 'webp'];

    private array $cache = [];

    public function __construct(private MediaLibrary $library) {}

    public static function refreshFor(Model $model, string $attribute = 'featured_image'): void
    {
        if (! $model->wasRecentlyCreated && ! $model->wasChanged($attribute)) {
            return;
        }

        $old = $model->getOriginal($attribute);
        $new = $model->getAttribute($attribute);

        dispatch(function () use ($old, $new) {
            $images = app(self::class);
            if ($old && $old !== $new) {
                $images->forget('storage', $old);
            }
            if ($new) {
                $images->generate('storage', $new);
            }
        })->afterResponse();
    }

    public function sources(string $location, ?string $path): ?array
    {
        if (blank($path)) {
            return null;
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');

        if (str_ends_with(strtolower(strtok($path, '?')), '.svg')) {
            return $this->cache["$location:$path"] ??= $this->svgSize($location, $path);
        }

        if (! $this->supported($path)) {
            return null;
        }

        return $this->cache["$location:$path"] ??= $this->collect($location, $path);
    }

    public static function enhanceHtml(?string $html, string $sizes = '(min-width: 1024px) 720px, 92vw'): string
    {
        if (blank($html) || ! str_contains($html, '<img')) {
            return (string) $html;
        }

        $images = app(self::class);
        $prefix = rtrim(asset('storage'), '/').'/';

        return preg_replace_callback('/<img\b[^>]*>/i', function ($match) use ($images, $prefix, $sizes) {
            $tag = $match[0];
            if (! preg_match('/\ssrc="([^"]+)"/i', $tag, $src) || ! str_starts_with($src[1], $prefix)) {
                return $tag;
            }
            $path = rawurldecode(strtok(substr($src[1], strlen($prefix)), '?'));
            $info = $images->sources('storage', $path);
            $extra = '';
            if (! preg_match('/\sloading=/i', $tag)) {
                $extra .= ' loading="lazy" decoding="async"';
            }
            if ($info && ! preg_match('/\swidth=/i', $tag)) {
                $extra .= ' width="'.$info['width'].'" height="'.$info['height'].'"';
            }
            if (! empty($info['sets']['webp']) && ! preg_match('/\ssrcset=/i', $tag)) {
                $extra .= ' srcset="'.e($info['sets']['webp']).'" sizes="'.e($sizes).'"';
            }

            return preg_replace('/\s*\/?>$/', $extra.' />', $tag, 1);
        }, $html);
    }

    public static function dimensions(?string $path, string $location = 'public'): string
    {
        $info = app(self::class)->sources($location, $path);

        return $info ? 'width="'.$info['width'].'" height="'.$info['height'].'"' : '';
    }

    public static function contentPaths(?string $html): array
    {
        $prefix = preg_quote(rtrim(asset('storage'), '/').'/', '#');
        preg_match_all('#<img\b[^>]*\ssrc="'.$prefix.'([^"?]+)#i', (string) $html, $m);

        return array_map('rawurldecode', $m[1]);
    }

    public function generate(string $location, string $path, bool $force = false): int
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $source = rescue(fn () => $this->library->absolutePath($location, $path), null, report: false);

        if (! $source || ! is_file($source) || ! $this->supported($path) || ! function_exists('imagewebp')) {
            return 0;
        }

        $size = @getimagesize($source);
        if (! $size || $size[0] <= self::WIDTHS[0]) {
            return 0;
        }

        $image = @imagecreatefromstring((string) file_get_contents($source));
        if (! $image instanceof GdImage) {
            return 0;
        }

        $disk = Storage::disk('public');
        $made = 0;
        foreach ($this->widths($size[0]) as $width) {
            $scaled = $width === $size[0] ? $image : imagescale($image, $width, -1, IMG_BICUBIC);
            if (! $scaled instanceof GdImage) {
                continue;
            }
            imagealphablending($scaled, false);
            imagesavealpha($scaled, true);

            foreach ($this->formats() as $format) {
                if ($format === 'webp' && $width === $size[0] && str_ends_with(strtolower($path), '.webp')) {
                    continue;
                }
                $target = $this->variantPath($location, $path, $width, $format);
                if (! $force && $disk->exists($target) && $disk->lastModified($target) >= filemtime($source)) {
                    continue;
                }
                $disk->makeDirectory(dirname($target));
                $absolute = $disk->path($target);
                $ok = $format === 'avif'
                    ? imageavif($scaled, $absolute, self::QUALITY['avif'], 6)
                    : imagewebp($scaled, $absolute, self::QUALITY['webp']);
                $made += $ok ? 1 : 0;
            }

        }
        unset($this->cache["$location:$path"]);

        return $made;
    }

    public function forget(string $location, ?string $path): void
    {
        if (blank($path)) {
            return;
        }
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $base = self::FOLDER.'/'.$location.'/'.$this->stem($path);
        $disk = Storage::disk('public');
        foreach ($disk->files(dirname($base)) as $file) {
            if (preg_match('#^'.preg_quote($base, '#').'-\d+\.(avif|webp)$#', $file)) {
                $disk->delete($file);
            }
        }
    }

    private function collect(string $location, string $path): ?array
    {
        $source = rescue(fn () => $this->library->absolutePath($location, $path), null, report: false);
        $size = $source && is_file($source) ? @getimagesize($source) : false;
        if (! $size) {
            return null;
        }

        $disk = Storage::disk('public');
        $sets = [];
        foreach (['avif', 'webp'] as $format) {
            $entries = [];
            foreach ($this->widths($size[0]) as $width) {
                if ($format === 'webp' && $width === $size[0] && str_ends_with(strtolower($path), '.webp')) {
                    $entries[] = media_url($path, $location)." {$width}w";

                    continue;
                }
                $variant = $this->variantPath($location, $path, $width, $format);
                if ($disk->exists($variant)) {
                    $entries[] = media_url($variant, 'storage')." {$width}w";
                }
            }
            if (count($entries) > 1) {
                $sets[$format] = implode(', ', $entries);
            }
        }

        return $sets ? ['width' => $size[0], 'height' => $size[1], 'sets' => $sets] : ['width' => $size[0], 'height' => $size[1], 'sets' => []];
    }

    private function svgSize(string $location, string $path): ?array
    {
        $file = rescue(fn () => $this->library->absolutePath($location, $path), null, report: false);
        $svg = $file && is_file($file) ? (string) file_get_contents($file, false, null, 0, 4096) : '';

        if (preg_match('/viewBox="[\d.\-]+[\s,]+[\d.\-]+[\s,]+([\d.]+)[\s,]+([\d.]+)"/i', $svg, $m)) {
            return ['width' => (int) round((float) $m[1]), 'height' => (int) round((float) $m[2]), 'sets' => []];
        }

        return null;
    }

    private function widths(int $original): array
    {
        return [...array_filter(self::WIDTHS, fn ($w) => $w < $original), $original];
    }

    private function formats(): array
    {
        return function_exists('imageavif') ? ['avif', 'webp'] : ['webp'];
    }

    private function variantPath(string $location, string $path, int $width, string $format): string
    {
        return self::FOLDER.'/'.$location.'/'.$this->stem($path)."-$width.$format";
    }

    private function stem(string $path): string
    {
        return preg_replace('/\.[a-z0-9]+$/i', '', $path);
    }

    private function supported(string $path): bool
    {
        return in_array(strtolower(pathinfo(strtok($path, '?'), PATHINFO_EXTENSION)), self::SOURCES, true);
    }
}
