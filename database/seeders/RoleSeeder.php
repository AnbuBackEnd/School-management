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
        Role::create(['name' => 'SuperAdmin']);

        //Admin Give Permissions
        $role=Role::create(['name' => 'Admin']);
        $permission = Permission::create(['name' => 'addStaffs']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'editStaffs']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'deleteStaffs']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'viewStaffs']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'addSections']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'editSections']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'deleteSections']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'viewSections']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'listSections']);
        $role->givePermissionTo($permission);

        $role=Role::create(['name' => 'TeachingStaff']);
        $permission = Permission::create(['name' => 'addStudent']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'editStudent']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'deleteStudent']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['name' => 'viewStudent']);
        $role->givePermissionTo($permission);
        Role::create(['name' => 'AdministrationStaff']);
        Role::create(['name' => 'librarian']);
    }
}
