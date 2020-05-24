<?php

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolePermission::create(['role_id' => 1, 'permission_id' => 1]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 2]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 3]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 4]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 5]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 6]);

        RolePermission::create(['role_id' => 1, 'permission_id' => 7]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 8]);

        RolePermission::create(['role_id' => 1, 'permission_id' => 9]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 10]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 11]);
        RolePermission::create(['role_id' => 1, 'permission_id' => 12]);

        

        RolePermission::create(['role_id' => 2, 'permission_id' => 1]);
        RolePermission::create(['role_id' => 2, 'permission_id' => 2]);
        RolePermission::create(['role_id' => 2, 'permission_id' => 3]);
        RolePermission::create(['role_id' => 2, 'permission_id' => 4]);
        RolePermission::create(['role_id' => 2, 'permission_id' => 5]);
        RolePermission::create(['role_id' => 2, 'permission_id' => 6]);

        RolePermission::create(['role_id' => 2, 'permission_id' => 7]);

        RolePermission::create(['role_id' => 2, 'permission_id' => 9]);
        RolePermission::create(['role_id' => 2, 'permission_id' => 10]);
        RolePermission::create(['role_id' => 2, 'permission_id' => 11]);
        RolePermission::create(['role_id' => 2, 'permission_id' => 12]);
    }
}
