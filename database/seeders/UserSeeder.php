<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PermissionRelations;
use App\Models\Permissions;
use App\Models\RoleRelations;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User data
        $userData = [
            'name' => 'Administrator',
            'email' => 'Administrator@gmail.com',
            'password' => Hash::make('d1pstr4t3gy'),
            'email_verified_at' => now(),
            'status' => 1,
        ];

        $user = User::create($userData);

        // Role data
        $roleData = ['name' => 'Administrator'];
        $role = Roles::create($roleData);

        // Role relation data
        $roleRelation = RoleRelations::create([
            'admin_id' => $user->id,
            'role_id' => $role->id,
        ]);

        // Permissions data
        $permissionsData = [
            ['name' => 'permission', 'code' => 'data permission'],
            ['name' => 'permission/create', 'code' => 'create permission'],
            ['name' => 'permission/edit', 'code' => 'edit permission'],
            ['name' => 'permission/delete', 'code' => 'delete permission'],
            ['name' => 'user', 'code' => 'data users'],
            ['name' => 'user/create', 'code' => 'create users'],
            ['name' => 'user/edit', 'code' => 'edit users'],
            ['name' => 'user/delete', 'code' => 'delete users'],
            ['name' => 'role', 'code' => 'data roles'],
            ['name' => 'role/create', 'code' => 'create roles'],
            ['name' => 'role/edit', 'code' => 'edit roles'],
            ['name' => 'role/delete', 'code' => 'delete roles'],
            ['name' => 'role/permission', 'code' => 'data role permission'],
            ['name' => 'role/permission/store', 'code' => 'store role permission'],
            ['name' => 'settings', 'code' => 'data settings'],
            ['name' => 'settings/create', 'code' => 'create settings'],
            ['name' => 'settings/edit', 'code' => 'edit settings'],
            ['name' => 'settings/delete', 'code' => 'delete settings'],
            ['name' => 'menu', 'code' => 'data menu'],
            ['name' => 'menu/create', 'code' => 'create menu'],
            ['name' => 'menu/edit', 'code' => 'edit menu'],
            ['name' => 'menu/delete', 'code' => 'delete menu'],
            ['name' => 'content/article', 'code' => 'data content article'],
            ['name' => 'content/article/create', 'code' => 'create content article'],
            ['name' => 'content/article/edit', 'code' => 'edit content article'],
            ['name' => 'content/article/delete', 'code' => 'delete content article'],
            ['name' => 'article', 'code' => 'data article'],
            ['name' => 'article/create', 'code' => 'create article'],
            ['name' => 'article/edit', 'code' => 'edit article'],
            ['name' => 'article/delete', 'code' => 'delete article'],
            ['name' => 'post/article', 'code' => 'data post article'],
            ['name' => 'post/article/create', 'code' => 'create post article'],
            ['name' => 'post/article/edit', 'code' => 'edit post article'],
            ['name' => 'post/article/delete', 'code' => 'delete post article'],
            ['name' => 'post/event', 'code' => 'Data Event'],
            ['name' => 'post/event/create', 'code' => 'Add Event'],
            ['name' => 'post/event/edit', 'code' => 'Edit event'],
            ['name' => 'post/event/delete', 'code' => 'Delete Event'],
            ['name' => 'page/generic', 'code' => 'Data Pages Generic'],
            ['name' => 'page/generic/create', 'code' => 'Create Pages Generic'],
            ['name' => 'page/generic/edit', 'code' => 'Edit Page Generic'],
            ['name' => 'page/generic/delete', 'code' => 'Delete Page Generic'],
            ['name' => 'menu/create', 'code' => 'Add Menu'],
            ['name' => 'menu/edit', 'code' => 'Edit Menu'],
            ['name' => 'menu/delete', 'code' => 'Delete menu'],
            ['name' => 'page/section', 'code' => 'Data Page Section'],
            ['name' => 'page/section/create', 'code' => 'Add Page Section'],
            ['name' => 'page/section/edit', 'code' => 'Edit Section'],
            ['name' => 'page/section/delete', 'code' => 'Delete Page Section'],
            ['name' => 'content/gallery', 'code' => 'Data content Gallery'],
            ['name' => 'content/gallery/create', 'code' => 'Create content Gallery'],
            ['name' => 'content/gallery/edit', 'code' => 'Edit content Gallery'],
            ['name' => 'content/gallery/delete', 'code' => 'Delete content Gallery'],
            ['name' => 'content/banner', 'code' => 'Data content Banner'],
            ['name' => 'content/banner/create', 'code' => 'Create content Banner'],
            ['name' => 'content/banner/edit', 'code' => 'Edit content Banner'],
            ['name' => 'content/banner/delete', 'code' => 'Delete content Banner'],
            ['name' => 'content/vacancy', 'code' => 'Data Vacancy'],
            ['name' => 'content/vacancy/create', 'code' => 'Add Vacancy'],
            ['name' => 'content/vacancy/edit', 'code' => 'Edit Vacancy'],
            ['name' => 'content/vacancy/delete', 'code' => 'Delete Vacancy'],
            ['name' => 'content/contact', 'code' => 'Data content Contact'],
            ['name' => 'content/contact/create', 'code' => 'Create content Contact'],
            ['name' => 'content/contact/edit', 'code' => 'Edit content Contact'],
            ['name' => 'content/contact/delete', 'code' => 'Delete content Contact'],
            ['name' => 'content/catalog', 'code' => 'Data content Catalog'],
            ['name' => 'content/catalog/create', 'code' => 'Create content Catalog'],
            ['name' => 'content/catalog/edit', 'code' => 'Edit content Catalog'],
            ['name' => 'content/catalog/delete', 'code' => 'Delete content Catalog'],
            ['name' => 'gallery/file', 'code' => 'Data Gallery File'],
            ['name' => 'gallery/file/create', 'code' => 'Create Gallery File'],
            ['name' => 'gallery/file/edit', 'code' => 'Edit Gallery File'],
            ['name' => 'gallery/file/delete', 'code' => 'Delete Gallery File'],
            ['name' => 'gallery/photo', 'code' => 'Data Gallery Photo'],
            ['name' => 'gallery/photo/create', 'code' => 'Add Gallery Photo'],
            ['name' => 'gallery/photo/edit', 'code' => 'Edit Gallery Photo'],
            ['name' => 'gallery/photo/delete', 'code' => 'Delete Gallery Photo'],
            ['name' => 'banner', 'code' => 'Data Banners'],
            ['name' => 'banner/create', 'code' => 'Add Banners'],
            ['name' => 'banner/edit', 'code' => 'Edit Banner'],
            ['name' => 'banner/delete', 'code' => 'Delete Banner'],
            ['name' => 'vacancy', 'code' => 'Data Vacancy'],
            ['name' => 'vacancy/create', 'code' => 'Add Vacancy'],
            ['name' => 'vacancy/edit', 'code' => 'Edit Vacancy'],
            ['name' => 'vacancy/delete', 'code' => 'Delete Vacancy'],
            ['name' => 'office', 'code' => 'Data Office'],
            ['name' => 'office/create', 'code' => 'Create New Office'],
            ['name' => 'office/edit', 'code' => 'Edit Office'],
            ['name' => 'office/delete', 'code' => 'Delete Office'],
            ['name' => 'product/brand', 'code' => 'Data Brand'],
            ['name' => 'product/brand/create', 'code' => 'Add Brand'],
            ['name' => 'product/brand/edit', 'code' => 'Edit Brand'],
            ['name' => 'product/brand/delete', 'code' => 'Delete Brand'],
            ['name' => 'product', 'code' => 'Data Product'],
            ['name' => 'product/create', 'code' => 'Add Product'],
            ['name' => 'product/edit', 'code' => 'Edit Product'],
            ['name' => 'product/delete', 'code' => 'Delete Product'],
            ['name' => 'form/newsletter', 'code' => 'Data Form Newsletter'],
            ['name' => 'form/contact', 'code' => 'Data Form Contact'],
            ['name' => 'form/vacancy', 'code' => 'Data Form Vacancy'],
            ['name' => 'wordings', 'code' => 'Data Wording'],
            ['name' => 'wordings/create', 'code' => 'Add Wording'],
            ['name' => 'wordings/edit', 'code' => 'Edit Wording'],
            ['name' => 'wordings/delete', 'code' => 'Delete Wording'],
            ['name' => 'content/chatbot', 'code' => 'Data content chatbot'],
            ['name' => 'content/chatbot/create', 'code' => 'Create content chatbot'],
            ['name' => 'content/chatbot/edit', 'code' => 'Edit content chatbot'],
            ['name' => 'content/chatbot/delete', 'code' => 'Delete content chatbot'],
        ];

        $permissions = collect($permissionsData)->map(function ($permissionData) use ($role) {
            $permission = Permissions::create($permissionData);
            $permissionRelationData = [
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ];
            PermissionRelations::create($permissionRelationData);
        });
    }
}
