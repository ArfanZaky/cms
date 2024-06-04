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

        if ($role) {
            $permissionRelations = PermissionRelations::where('role_id', $role)->with('permission')->get();

            $permissions = $permissionRelations->filter(function ($value, $key) {
                return $value->permission_id;
            })->pluck('permission.name')->values()->toArray();
            $permission_content = $permissionRelations->pluck('content_id')->filter()->unique()->values()->toArray();
        }

        $category = \App\Models\WebContent::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }])
            ->whereIn('id', $permission_content)
            ->where('is_menu', 1)
            ->orderBy('sort', 'asc')
            ->get();


        $data = [];
        if (!empty($category)) {
            $data = collect($category)->map(function ($item) {
                return [
                    'id' => $item->id,
                    'parent' => $item->parent,
                    'children' => [],
                    'name' => $item->translations->first()->name,
                    'visibility' => $item->visibility,
                    'sort' => $item->sort,
                    'status' => $item->status,
                    'url' => '/'.$item->url,
                ];
            });

            $data = \App\Helper\Helper::tree($data);
            $data = menu_table($data, 0, $arr = []);
        }

        session([
            'permission' => $permissions,
            'permission_content' => $data,
        ]);
    }
}
