<?php

namespace App\Http\Middleware;

use App\Models\PermissionRelations;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionsRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $permissiondata = [];
        $role = Auth::user()?->role?->first()?->id;
        if ($role) {
            $permissionRelations = PermissionRelations::where('role_id', $role)->with('permission')->get();
            $permissiondata = $permissionRelations->filter(function ($value, $key) {
                return $value->permission_id;
            })->pluck('permission.name')->values()->toArray();
        }

        if (in_array($permission, $permissiondata)) {
            return $next($request);
        } else {
            return redirect()->route('dashboard')->with('error', 'permission denied, please contact your administrator');
        }
    }
}
