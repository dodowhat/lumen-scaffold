<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminAction;
use Illuminate\Support\Facades\DB;

class AdminActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_actions')->insert([
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminController@profile",
                'description' => "Admin authenticated user's profile",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminUserController@index",
                'description' => "Admin user list",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminRoleController@index",
                'description' => "Admin role list",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // [
            //     'action' => "App\\Http\\Controllers\\Admin\\AdminActionController@index",
            //     'description' => "Admin action list",
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
        ]);
    }
}
