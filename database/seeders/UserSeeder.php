<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();
        DB::table('role')->truncate();
        DB::table('user')->truncate();

        DB::table('role')->insert([
            [
                'name' => 'Super Admin',
                'is_default' => 1,
                'is_show' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Admin',
                'is_default' => 1,
                'is_show' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Staff',
                'is_default' => 1,
                'is_show' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);


        DB::table('user')->insert([
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'phone' => 0,
                'email' => 'superadmin@gmail.com',
                'password' => bcrypt('123123'),
                'role_id' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
