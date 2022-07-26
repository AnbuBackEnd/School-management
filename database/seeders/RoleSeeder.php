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
        $addBookCatagory = Permission::create(['name' => 'addBookCatagory']);
        $deleteBookCatagory = Permission::create(['name' => 'deleteBookCatagory']);
        $editBookCatagory = Permission::create(['name' => 'editBookCatagory']);
        $viewBookCatagory = Permission::create(['name' => 'viewBookCatagory']);
        $listBookCatagory = Permission::create(['name' => 'listBookCatagory']);
        $addBookSubCatagory = Permission::create(['name' => 'addBookSubCatagory']);
        $deleteBookSubCatagory = Permission::create(['name' => 'deleteBookSubCatagory']);
        $editBookSubCatagory = Permission::create(['name' => 'editBookSubCatagory']);
        $listBookSubCatagory = Permission::create(['name' => 'listBookSubCatagory']);
        $addBooks = Permission::create(['name' => 'addBooks']);
        $deleteBooks = Permission::create(['name' => 'deleteBooks']);
        $editBooks = Permission::create(['name' => 'editBooks']);
        $getBooks = Permission::create(['name' => 'getBooks']);
        $listBooks = Permission::create(['name' => 'listBooks']);
        $getAllorders = Permission::create(['name' => 'getAllorders']);
        $searchOrders = Permission::create(['name' => 'searchOrders']);
        $ReturnPermission = Permission::create(['name' => 'ReturnPermission']);
        $deleteBooksRecords = Permission::create(['name' => 'deleteBooksRecords']);
        $request_books = Permission::create(['name' => 'request_books']);
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
        $addExams = Permission::create(['name' => 'addExams']);
        $deleteExams = Permission::create(['name' => 'deleteExams']);
        $editExams = Permission::create(['name' => 'editExams']);
        $viewExams = Permission::create(['name' => 'viewExams']);
        $listExams = Permission::create(['name' => 'listExams']);
        $addFeesStructureCatagory = Permission::create(['name' => 'addFeesStructureCatagory']);
        $deleteFeesStrutureCatagory = Permission::create(['name' => 'deleteFeesStrutureCatagory']);
        $editFeesStructureCatagory = Permission::create(['name' => 'editFeesStructureCatagory']);
        $viewFeesStructureCatagory = Permission::create(['name' => 'viewFeesStructureCatagory']);
        $listFeesStrutureCatagory = Permission::create(['name' => 'listFeesStrutureCatagory']);
        $addFees = Permission::create(['name' => 'addFees']);
        $deleteFees = Permission::create(['name' => 'deleteFees']);
        $editFees = Permission::create(['name' => 'editFees']);
        $viewFees = Permission::create(['name' => 'viewFees']);
        $addResults = Permission::create(['name' => 'addResults']);
        $deleteResults = Permission::create(['name' => 'deleteResults']);
        $editResults = Permission::create(['name' => 'editResults']);
        $viewResults = Permission::create(['name' => 'viewResults']);
        $putAttendance = Permission::create(['name' => 'putAttendance']);


      // Role::create(['name' => 'SuperAdmin']);
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
        //Teaching Staff Give Permissions
        $role=Role::create(['name' => 'TeachingStaff']);
        $role->givePermissionTo($addStudents);
        $role->givePermissionTo($editStudents);
        $role->givePermissionTo($viewStudents);
        $role->givePermissionTo($listStudents);
        $role->givePermissionTo($deleteStudents);
        $role->givePermissionTo($putAttendance);
          //Administration Staff Give Permissions
        Role::create(['name' => 'AdministrationStaff']);
        $role->givePermissionTo($addExams);
        $role->givePermissionTo($deleteExams);
        $role->givePermissionTo($editExams);
        $role->givePermissionTo($viewExams);
        $role->givePermissionTo($listExams);
        $role->givePermissionTo($addFeesStructureCatagory);
        $role->givePermissionTo($deleteFeesStrutureCatagory);
        $role->givePermissionTo($editFeesStructureCatagory);
        $role->givePermissionTo($viewFeesStructureCatagory);
        $role->givePermissionTo($listFeesStrutureCatagory);
        $role->givePermissionTo($addFees);
        $role->givePermissionTo($deleteFees);
        $role->givePermissionTo($editFees);
        $role->givePermissionTo($viewFees);
        $role->givePermissionTo($addResults);
        $role->givePermissionTo($deleteResults);
        $role->givePermissionTo($editResults);
        $role->givePermissionTo($viewResults);

        //librarian Give Permissions
        Role::create(['name' => 'librarian']);
        $role->givePermissionTo($addBookCatagory);
        $role->givePermissionTo($deleteBookCatagory);
        $role->givePermissionTo($editBookCatagory);
        $role->givePermissionTo($viewBookCatagory);
        $role->givePermissionTo($listBookCatagory);
        $role->givePermissionTo($addBookSubCatagory);
        $role->givePermissionTo($deleteBookSubCatagory);
        $role->givePermissionTo($editBookSubCatagory);
        $role->givePermissionTo($listBookSubCatagory);
        $role->givePermissionTo($addBooks);
        $role->givePermissionTo($editBooks);
        $role->givePermissionTo($getBooks);
        $role->givePermissionTo($listBooks);
        $role->givePermissionTo($getAllorders);
        $role->givePermissionTo($searchOrders);
        $role->givePermissionTo($searchOrders);
        $role->givePermissionTo($ReturnPermission);
        $role->givePermissionTo($deleteBooksRecords);
        $role->givePermissionTo($request_books);
    }
}
