<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminUser::create([
            'username' => 'admin',
            'password' => 'admin',
            'jwt_secret' => AdminUser::generateJWTSecret()
        ]);
        AdminUser::create([
            'username' => 'editor',
            'password' => 'editor',
            'jwt_secret' => AdminUser::generateJWTSecret()
        ]);
    }
}
