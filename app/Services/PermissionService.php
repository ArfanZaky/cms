<?php

namespace App\Services;

use App\Models\PermissionRelations;
use Illuminate\Support\Facades\Auth;

class PermissionService
{
    public function handle()
    {
        $role = Auth::user()?->role?->first()?->id;
        $permissions = [];
        $permission_content = [];
        $permission_page = [];

        if ($role) {
            $permissionRelations = PermissionRelations::where('role_id', $role)->with('permission')->get();

            $permissions = $permissionRelations->filter(function ($value, $key) {
                return $value->permission_id;
            })->pluck('permission.name')->values()->toArray();
            $permission_content = $permissionRelations->pluck('content_id')->filter()->unique()->values()->toArray();
            $permission_page = $permissionRelations->pluck('page_id')->filter(function ($value) {
                return ! is_null($value);
            })->values()->toArray();
        }

        session([
            'permission' => $permissions,
            'permission_content' => $permission_content,
            'permission_page' => $permission_page,
        ]);
    }
}
