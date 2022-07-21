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
<<<<<<< HEAD
        Role::create(['name' => 'SuperAdmin']);
=======
        Role::create(['name' => 'Super-Admin']);
>>>>>>> a4bd76dee440c50b817523bfc6e90c50980545b2

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
<<<<<<< HEAD
        $permission = Permission::create(['guard_name' => 'web','name' => 'addSections']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'editSections']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'deleteSections']);
        $role->givePermissionTo($permission);
        $permission = Permission::create(['guard_name' => 'web','name' => 'viewSections']);
        $role->givePermissionTo($permission);
=======
>>>>>>> a4bd76dee440c50b817523bfc6e90c50980545b2

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
<<<<<<< HEAD
=======

>>>>>>> a4bd76dee440c50b817523bfc6e90c50980545b2
        Role::create(['name' => 'librarian']);
    }
}
