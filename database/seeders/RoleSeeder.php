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
        $addStaffs = Permission::create(['name' => 'addStaffs']);
        $editStaffs = Permission::create(['name' => 'editStaffs']);
        $deleteStaffs = Permission::create(['name' => 'deleteStaffs']);
        $viewStaffs = Permission::create(['name' => 'viewStaffs']);
        $listStaffs = Permission::create(['name' => 'listStaffs']);
        $addSections = Permission::create(['name' => 'addSections']);
        $editSections = Permission::create(['name' => 'editSections']);
        $deleteSections = Permission::create(['name' => 'deleteSections']);
        $viewSections = Permission::create(['name' => 'viewSections']);
        $listSections = Permission::create(['name' => 'listSections']);
        $addSubjects = Permission::create(['name' => 'addSubjects']);
        $editSubjects = Permission::create(['name' => 'editSubjects']);
        $viewSubjects = Permission::create(['name' => 'viewSubjects']);
        $listSubjects = Permission::create(['name' => 'listSubjects']);
        $deleteSubjects = Permission::create(['name' => 'deleteSubjects']);
        $listStandards = Permission::create(['name' => 'listStandards']);
        $addStudents = Permission::create(['name' => 'addStudents']);
        $editStudents = Permission::create(['name' => 'editStudents']);
        $deleteStudents = Permission::create(['name' => 'deleteStudents']);
        $viewStudents = Permission::create(['name' => 'viewStudents']);
        $listStudents = Permission::create(['name' => 'listStudents']);
        $addClasses = Permission::create(['name' => 'addClasses']);
        $editClasses = Permission::create(['name' => 'editClasses']);
        $viewClasses = Permission::create(['name' => 'viewClasses']);
        $listClasses = Permission::create(['name' => 'listClasses']);
        $deleteClasses = Permission::create(['name' => 'deleteClasses']);

       Role::create(['name' => 'SuperAdmin']);
        //Admin Give Permissions
        $role=Role::create(['name' => 'Admin']);
        $role->givePermissionTo($addStaffs);
        $role->givePermissionTo($editStaffs);
        $role->givePermissionTo($deleteStaffs);
        $role->givePermissionTo($listStaffs);
        $role->givePermissionTo($viewStaffs);
        $role->givePermissionTo($addSections);
        $role->givePermissionTo($editSections);
        $role->givePermissionTo($viewSections);
        $role->givePermissionTo($listSections);
        $role->givePermissionTo($deleteSections);
        $role->givePermissionTo($addClasses);
        $role->givePermissionTo($editClasses);
        $role->givePermissionTo($viewClasses);
        $role->givePermissionTo($listClasses);
        $role->givePermissionTo($deleteClasses);
        $role->givePermissionTo($addSubjects);
        $role->givePermissionTo($editSubjects);
        $role->givePermissionTo($viewSubjects);
        $role->givePermissionTo($listSubjects);
        $role->givePermissionTo($deleteSubjects);
        $role->givePermissionTo($listStandards);
        $role=Role::create(['name' => 'TeachingStaff']);
        $role->givePermissionTo($addStudents);
        $role->givePermissionTo($editStudents);
        $role->givePermissionTo($viewStudents);
        $role->givePermissionTo($listStudents);
        $role->givePermissionTo($deleteStudents);
        Role::create(['name' => 'AdministrationStaff']);
        Role::create(['name' => 'librarian']);
    }
}
