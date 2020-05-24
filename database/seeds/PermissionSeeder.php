<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'post-index']);
        Permission::create(['name' => 'post-store']);
        Permission::create(['name' => 'post-show']);
        Permission::create(['name' => 'post-update']);
        Permission::create(['name' => 'post-destroy']);
        Permission::create(['name' => 'post-comment']);

        Permission::create(['name' => 'comment-index']);
        Permission::create(['name' => 'comment-destroy']);

        Permission::create(['name' => 'follow-followers']);
        Permission::create(['name' => 'follow-following']);
        Permission::create(['name' => 'follow-following-user']);
        Permission::create(['name' => 'follow-destroy']);
    }
}
