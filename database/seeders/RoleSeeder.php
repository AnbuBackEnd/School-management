<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Super-Admin']);

        //Admin Give Permissions
        $role=Role::create(['name' => 'Admin']);
        $permission = Permission::create(['guard_name' => 'web','name' => 'addStaffs']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'editStaffs']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'deleteStaffs']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'viewStaffs']);
        $role->givePermissionTo($permission);

        $role=Role::create(['name' => 'TeachingStaff']);
        $permission = Permission::create(['guard_name' => 'web','name' => 'addStudent']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'editStudent']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'deleteStudent']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'viewStudent']);
        $role->givePermissionTo($permission);
        Role::create(['name' => 'AdministrationStaff']);

        Role::create(['name' => 'librarian']);
    }
}
