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
        $addFees = Permission::create(['name' => 'addFees']);
        $deleteFees = Permission::create(['name' => 'deleteFees']);
        $addPayFees = Permission::create(['name' => 'addPayFees']);
        $deletePayFees = Permission::create(['name' => 'deletePayFees']);
        $addResults = Permission::create(['name' => 'addResults']);
        $deleteResults = Permission::create(['name' => 'deleteResults']);
        $editResults = Permission::create(['name' => 'editResults']);
        $viewResults = Permission::create(['name' => 'viewResults']);
        $putAttendance = Permission::create(['name' => 'putAttendance']);
        $studentAttendanceReport = Permission::create(['name' => 'studentAttendanceReport']);
        $feesNotPaidStudentsAdmin = Permission::create(['name' => 'feesNotPaidStudentsAdmin']);
        $feesNotPaidStudentsTeacher = Permission::create(['name' => 'feesNotPaidStudentsTeacher']);
        $gradeCalculationReportAdmin = Permission::create(['name' => 'gradeCalculationReportAdmin']);
        $gradeCalculationReportTeacher = Permission::create(['name' => 'gradeCalculationReportTeacher']);
        $studentAttendanceReportAdmin = Permission::create(['name' => 'studentAttendanceReportAdmin']);
        $studentAttendanceReportTeacher = Permission::create(['name' => 'studentAttendanceReportTeacher']);
        $assignClasses = Permission::create(['name' => 'assignClasses']);
        $deleteAssignClasses = Permission::create(['name' => 'deleteAssignClasses']);
        $feesAllDetails = Permission::create(['name' => 'feesAllDetails']);
        $overAllStudentListAdmin = Permission::create(['name' => 'overAllStudentList-Admin']);
        $overAllStaffListAdmin = Permission::create(['name' => 'overAllStaffList-Admin']);
        $staffSalaryReportTotal = Permission::create(['name' => 'staffSalaryReport-Total']);
        $staffSalaryReportAdminParticular = Permission::create(['name' => 'staffSalaryReportAdmin-Particular']);
        $addSalary = Permission::create(['name' => 'addSalary']);
        $deleteSalary = Permission::create(['name' => 'deleteSalary']);
        $viewSalary = Permission::create(['name' => 'viewSalary']);
        $editSalary = Permission::create(['name' => 'editSalary']);
        $AttendanceStudent = Permission::create(['name' => 'AttendanceStudent']);
        $AttendanceStaff = Permission::create(['name' => 'AttendanceStaff']);





      // Role::create(['name' => 'SuperAdmin']);
        //Admin Give Permissions
        $role=Role::create(['name' => 'Admin']);
        $role->givePermissionTo($addStaffs);
        $role->givePermissionTo($editStaffs);
        $role->givePermissionTo($deleteStaffs);
        $role->givePermissionTo($listStaffs);
        $role->givePermissionTo($viewStaffs);
        $role->givePermissionTo($listSections);
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
        $role->givePermissionTo($feesNotPaidStudentsAdmin);
        $role->givePermissionTo($gradeCalculationReportAdmin);
        $role->givePermissionTo($studentAttendanceReportAdmin);
        $role->givePermissionTo($assignClasses);
        $role->givePermissionTo($deleteAssignClasses);
        $role->givePermissionTo($feesAllDetails);
        $role->givePermissionTo($overAllStudentListAdmin);
        $role->givePermissionTo($overAllStaffListAdmin);
        $role->givePermissionTo($staffSalaryReportTotal);
        $role->givePermissionTo($staffSalaryReportAdminParticular);
        $role->givePermissionTo($addSalary);
        $role->givePermissionTo($editSalary);
        $role->givePermissionTo($viewSalary);
        $role->givePermissionTo($deleteSalary);
        //Teaching Staff Give Permissions
        $role=Role::create(['name' => 'TeachingStaff']);
        $role->givePermissionTo($addStudents);
        $role->givePermissionTo($editStudents);
        $role->givePermissionTo($viewStudents);
        $role->givePermissionTo($listStudents);
        $role->givePermissionTo($deleteStudents);
        $role->givePermissionTo($putAttendance);
        $role->givePermissionTo($addResults);
        $role->givePermissionTo($deleteResults);
        $role->givePermissionTo($editResults);
        $role->givePermissionTo($viewResults);
        $role->givePermissionTo($studentAttendanceReportTeacher);
        $role->givePermissionTo($gradeCalculationReportTeacher);
        $role->givePermissionTo($feesNotPaidStudentsTeacher);
          //Administration Staff Give Permissions
        $role=Role::create(['name' => 'NonTeachingStaff']);
        $role->givePermissionTo($addExams);
        $role->givePermissionTo($deleteExams);
        $role->givePermissionTo($editExams);
        $role->givePermissionTo($viewExams);
        $role->givePermissionTo($listExams);
        $role->givePermissionTo($addFees);
        $role->givePermissionTo($deleteFees);
        $role->givePermissionTo($deletePayFees);
        $role->givePermissionTo($addPayFees);
        // $role->givePermissionTo($editFees);
        // $role->givePermissionTo($viewFees);
        //librarian Give Permissions
        $role=Role::create(['name' => 'librarian']);
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
        $role->givePermissionTo($deleteBooks);
        $role->givePermissionTo($getBooks);
        $role->givePermissionTo($listBooks);
        $role->givePermissionTo($getAllorders);
        $role->givePermissionTo($searchOrders);
        $role->givePermissionTo($ReturnPermission);
        $role->givePermissionTo($deleteBooksRecords);
        $role->givePermissionTo($request_books);
    }
}
