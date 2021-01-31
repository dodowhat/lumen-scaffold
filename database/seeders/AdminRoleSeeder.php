<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\AdminAction;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = AdminRole::create([
            'name' => 'admin'
        ]);
        $user = AdminUser::where('username', 'admin')->first();
        $user->roles()->attach($role->id);

        $role = AdminRole::create([
            'name' => 'test'
        ]);
        $actions = AdminAction::get();
        $role->actions()->sync($actions->modelKeys());

        $user = AdminUser::where('username', 'test')->first();
        $user->roles()->attach($role->id);
    }
}
