<?php

use App\Models\Blog;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function contentAdmin(): User
{
    $role = Role::findOrCreate('super admin', 'web');

    foreach (['pages', 'blogs'] as $module) {
        foreach (['view', 'create', 'edit', 'delete'] as $action) {
            $role->givePermissionTo(Permission::findOrCreate("admin.{$module}.{$action}", 'web'));
        }
    }

    return tap(User::factory()->create())->assignRole($role);
}

const BLOCK_HTML = '<div class="ps-intro">
<h2>About us</h2>
<p class="ps-lead">Who we are.</p>
</div>

<div class="ps-faq ps-has-bg" style="background-color:#f4f3f1;">
<h2 class="ps-heading">Questions</h2>
<div class="ps-faq-list">
<div class="ps-faq-item">
<p class="ps-faq-q">Where are you?</p>
<div class="ps-faq-a"><div>
<p>In Mumbai.</p>
</div></div>
</div>
</div>
</div>';

test('page and blog forms use the content builder instead of a plain editor', function () {
    $admin = contentAdmin();

    foreach (['admin.pages.create', 'admin.blogs.create'] as $route) {
        $this->actingAs($admin)->get(route($route))
            ->assertOk()
            ->assertSee('contentEditor(', false)
            ->assertDontSee('class="tinymce', false);
    }

    $this->get(route('admin.pages.create'))->assertSee('\u0022kind\u0022:\u0022page\u0022', false);
    $this->get(route('admin.blogs.create'))->assertSee('\u0022kind\u0022:\u0022blog\u0022', false)->assertSee('placeholder.svg', false);
});

test('block content is saved as html and shown again when editing', function () {
    $admin = contentAdmin();

    $this->actingAs($admin)->post(route('admin.pages.store'), [
        'title' => 'About us',
        'slug' => 'about-us',
        'content' => BLOCK_HTML,
        'status' => 'active',
    ])->assertRedirect();

    $page = Page::firstWhere('slug', 'about-us');
    expect($page->content)->toBe(BLOCK_HTML);

    $this->get(route('admin.pages.edit', $page))->assertOk()->assertSee('ps-faq-item', false);

    $this->post(route('admin.blogs.store'), [
        'title' => 'First post',
        'slug' => 'first-post',
        'content' => BLOCK_HTML,
        'status' => 'active',
    ])->assertRedirect();

    $blog = Blog::firstWhere('slug', 'first-post');
    expect($blog->content)->toBe(BLOCK_HTML);

    $this->get(route('admin.blogs.edit', $blog))->assertOk()->assertSee('ps-intro', false);
});

test('content is still required', function () {
    $this->actingAs(contentAdmin())
        ->post(route('admin.pages.store'), ['title' => 'Empty', 'slug' => 'empty', 'content' => '', 'status' => 'active'])
        ->assertSessionHasErrors('content');
});

test('the appearance screen is gone', function () {
    expect(Route::has('admin.appearance.index'))->toBeFalse();

    $this->actingAs(contentAdmin())->get('/admin/appearance')->assertNotFound();
});
