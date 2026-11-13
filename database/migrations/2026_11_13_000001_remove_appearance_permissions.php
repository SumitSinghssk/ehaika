<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/*
 * The Admin → Appearance screen was removed. The website keeps using the
 * saved (or default) theme; only the permissions for the screen go away.
 */
return new class extends Migration
{
    private const PERMISSIONS = ['admin.appearance.view', 'admin.appearance.update'];

    public function up(): void
    {
        Permission::whereIn('name', self::PERMISSIONS)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // The Appearance screen no longer exists, so there is nothing to restore.
    }
};
