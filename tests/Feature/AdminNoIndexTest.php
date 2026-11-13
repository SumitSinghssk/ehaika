<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

test('every admin response tells search engines not to index it', function (string $path) {
    $this->get($path)->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
})->with([
    'login page' => ['/admin/login'],
    'redirect to login' => ['/admin/dashboard'],
    'unknown admin page' => ['/admin/does-not-exist'],
]);

test('logged-in admin pages and json are noindex too', function () {
    $admin = tap(User::factory()->create())->givePermissionTo(Permission::findOrCreate('admin.pages.view', 'web'));

    $this->actingAs($admin)->get('/admin/pages')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
    $this->actingAs($admin)->postJson('/admin/images', [])->assertUnprocessable()->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
});

test('the public website stays indexable', function () {
    $this->get('/')->assertOk()->assertHeaderMissing('X-Robots-Tag');
});
