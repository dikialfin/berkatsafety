<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();
        DB::table('permission')->truncate();
        
        $permission =[
            [
                'name' => 'role.view', 
                'group' => 'role',
                'has_value' => 0,
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'role.create', 
                'group' => 'role',
                'has_value' => 0,
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'role.update', 
                'group' => 'role',
                'has_value' => 0,
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'role.delete', 
                'group' => 'role',
                'has_value' => 0,
                'created_at' => $now, 
                'updated_at' => $now
            ],

            [
                'name' => 'user.view', 
                'group' => 'user',
                'has_value' => 0,
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'user.create', 
                'group' => 'user',
                'has_value' => 0,
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'user.update', 
                'group' => 'user',
                'has_value' => 0,
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'user.delete', 
                'group' => 'user',
                'has_value' => 0,
                'created_at' => $now, 
                'updated_at' => $now
            ],
        ];

        DB::table('permission')->insert($permission);

        //assign all permission to superadmin
        $permissions = Permission::get();
        foreach($permissions as $val){
            $rolePermission = new RolePermission();
            $rolePermission->role_id = 1;
            $rolePermission->permission = $val->name;
            $rolePermission->save();
        }
    }
}
