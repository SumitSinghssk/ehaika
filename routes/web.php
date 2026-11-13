<?php

use App\Http\Controllers\Website\BlogController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\PageController;
use App\Http\Controllers\Website\SitemapController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'index'])->name('home');
Route::get('/platform', [WebsiteController::class, 'platform'])->name('platform');
Route::get('/features', [WebsiteController::class, 'features'])->name('features');
Route::get('/about', [WebsiteController::class, 'about'])->name('about');

Route::get('/contact-us', [ContactController::class, 'show'])->name('contact');
Route::post('/contact-us', [ContactController::class, 'store'])
    ->middleware(['throttle:6,1', 'bot.protect:email'])
    ->name('contact.store');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/feed', [BlogController::class, 'feed'])->name('blog.feed');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Pages built in Admin → Pages (privacy policy, terms of service and any new page) live at /{slug}.
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '(?!(?:admin|up|storage|build|blog|sitemap)$)[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('page.show');

Route::any('{fallbackPlaceholder}', fn () => abort(404))->where('fallbackPlaceholder', '.*')->fallback();
