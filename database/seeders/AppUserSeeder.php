<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppUser;

class AppUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppUser::create([
            'username' => 'user1',
            'password' => 'user1',
            'jwt_secret' => AppUser::generateJWTSecret()
        ]);
    }
}
