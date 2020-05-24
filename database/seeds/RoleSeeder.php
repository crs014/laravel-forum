<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
//use App\Models\Post;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            "name" => "admin"
        ]);

        Role::create([
            "name" => "member"
        ]);
    }
}
