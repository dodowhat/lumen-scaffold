<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminActionGroup;
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
        $adminActionGroup = AdminActionGroup::create(['name' => 'AdminUsers']);
        DB::table('admin_actions')->insert([
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminUserController@index",
                'name' => "List AdminUser",
                'admin_action_group_id' => $adminActionGroup->id
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminUserController@store",
                'name' => "Create AdminUser",
                'admin_action_group_id' => $adminActionGroup->id
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminUserController@show",
                'name' => "Show AdminUser",
                'admin_action_group_id' => $adminActionGroup->id
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminUserController@destroy",
                'name' => "Delete AdminUser",
                'admin_action_group_id' => $adminActionGroup->id
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminUserController@assignRoles",
                'name' => "Assign AdminUser Roles",
                'admin_action_group_id' => $adminActionGroup->id
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminUserController@resetPassword",
                'name' => "Reset AdminUser's Password",
                'admin_action_group_id' => $adminActionGroup->id
            ]
        ]);

        $adminActionGroup = AdminActionGroup::create(['name' => 'AdminRoles']);
        DB::table('admin_actions')->insert([
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminRoleController@index",
                'name' => "List AdminRoles",
                'admin_action_group_id' => $adminActionGroup->id
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminRoleController@store",
                'name' => "Create AdminRoles",
                'admin_action_group_id' => $adminActionGroup->id
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminRoleController@destroy",
                'name' => "Delete AdminRoles",
                'admin_action_group_id' => $adminActionGroup->id
            ],
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminRoleController@assignActions",
                'name' => "Assign AdminRoles Actions",
                'admin_action_group_id' => $adminActionGroup->id
            ]
        ]);
        
        $adminActionGroup = AdminActionGroup::create(['name' => 'AdminActions']);
        DB::table('admin_actions')->insert([
            [
                'action' => "App\\Http\\Controllers\\Admin\\AdminActionController@index",
                'name' => "List AdminActions",
                'admin_action_group_id' => $adminActionGroup->id
            ]
        ]);
    }
}
