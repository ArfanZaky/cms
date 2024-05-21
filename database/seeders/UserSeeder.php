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
            ['name' => 'category/article', 'code' => 'data category article'],
            ['name' => 'category/article/create', 'code' => 'create category article'],
            ['name' => 'category/article/edit', 'code' => 'edit category article'],
            ['name' => 'category/article/delete', 'code' => 'delete category article'],
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
            ['name' => 'category/gallery', 'code' => 'Data Category Gallery'],
            ['name' => 'category/gallery/create', 'code' => 'Create Category Gallery'],
            ['name' => 'category/gallery/edit', 'code' => 'Edit Category Gallery'],
            ['name' => 'category/gallery/delete', 'code' => 'Delete Category Gallery'],
            ['name' => 'category/banner', 'code' => 'Data Category Banner'],
            ['name' => 'category/banner/create', 'code' => 'Create Category Banner'],
            ['name' => 'category/banner/edit', 'code' => 'Edit Category Banner'],
            ['name' => 'category/banner/delete', 'code' => 'Delete Category Banner'],
            ['name' => 'category/vacancy', 'code' => 'Data Vacancy'],
            ['name' => 'category/vacancy/create', 'code' => 'Add Vacancy'],
            ['name' => 'category/vacancy/edit', 'code' => 'Edit Vacancy'],
            ['name' => 'category/vacancy/delete', 'code' => 'Delete Vacancy'],
            ['name' => 'category/contact', 'code' => 'Data Category Contact'],
            ['name' => 'category/contact/create', 'code' => 'Create Category Contact'],
            ['name' => 'category/contact/edit', 'code' => 'Edit Category Contact'],
            ['name' => 'category/contact/delete', 'code' => 'Delete Category Contact'],
            ['name' => 'category/catalog', 'code' => 'Data Category Catalog'],
            ['name' => 'category/catalog/create', 'code' => 'Create Category Catalog'],
            ['name' => 'category/catalog/edit', 'code' => 'Edit Category Catalog'],
            ['name' => 'category/catalog/delete', 'code' => 'Delete Category Catalog'],
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
            ['name' => 'category/chatbot', 'code' => 'Data Category chatbot'],
            ['name' => 'category/chatbot/create', 'code' => 'Create Category chatbot'],
            ['name' => 'category/chatbot/edit', 'code' => 'Edit Category chatbot'],
            ['name' => 'category/chatbot/delete', 'code' => 'Delete Category chatbot'],
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
