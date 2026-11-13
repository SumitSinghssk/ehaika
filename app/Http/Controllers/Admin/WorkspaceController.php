<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\View\Components\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class WorkspaceController extends Controller
{
    /**
     * Remember the sidebar workspace (admin / website) on the user, so it survives logout and other browsers.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'workspace' => ['required', 'string', Rule::in(Admin::WORKSPACES)],
        ]);

        $user = $request->user();

        // Before `php artisan migrate` the column is missing: the cookie still works, so don't error on every page.
        if (! Schema::hasColumn($user->getTable(), 'admin_workspace')) {
            return response()->json(['workspace' => $data['workspace'], 'stored' => false]);
        }

        // Base query: a UI preference shouldn't fire model events (activity log) or bump updated_at.
        $user->newQuery()->toBase()->where($user->getKeyName(), $user->getKey())->update(['admin_workspace' => $data['workspace']]);

        // No cookie here: the page already set it, and a late response could overwrite a newer choice.
        return response()->json(['workspace' => $data['workspace']]);
    }
}
