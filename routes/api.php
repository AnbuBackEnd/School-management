<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentAttendance;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffSalaryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/adminPersonalInformation', [ReportController::class, 'adminPersonalInformation']);
Route::get('/unauthenticated', [UserController::class, 'unauthenticated'])->name('unauthenticated');
Route::get('/checkingfinal', [StudentAttendance::class, 'checkingfinal']);
Route::post('/registerEncrypt', [UserController::class, 'registerEncrypt']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/accountVerificationEncrypt', [UserController::class, 'accountVerificationEncrypt']);
Route::post('/accountVerification', [UserController::class, 'accountVerification']);
Route::post('/loginEncrypt', [UserController::class, 'loginEncrypt']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/decrypt', [UserController::class, 'decrypt_user']);
Route::post('/addStaffEncrypt', [UserController::class, 'addStaffEncrypt'])->middleWare('auth:api');
Route::post('/addStaff', [UserController::class, 'addStaff'])->middleWare('auth:api');
Route::get('/getStaffList', [UserController::class, 'getStaffs'])->middleWare('auth:api');
Route::get('/enc', [UserController::class, 'encryptData_sample']);
//Exam
Route::post('/addExamEncrypt', [ExamController::class, 'addExamEncrypt'])->middleWare('auth:api');
Route::post('/addExam', [ExamController::class, 'addExam'])->middleWare('auth:api');
Route::post('/addExamRecordsEncrypt', [ExamController::class, 'addExamRecordsEncrypt'])->middleWare('auth:api');
Route::post('/addExamRecords', [ExamController::class, 'addExamRecords'])->middleWare('auth:api');
Route::get('/deleteExam/{id}', [ExamController::class, 'deleteExam'])->middleWare('auth:api');
Route::get('/deleteExamRecord/{id}', [ExamController::class, 'deleteExamRecord'])->middleWare('auth:api');
Route::get('/getExam/{id}', [ExamController::class, 'getExam'])->middleWare('auth:api');
Route::get('/getExamRecord/{id}', [ExamController::class, 'getExamRecord'])->middleWare('auth:api');
Route::get('/getAllExams', [ExamController::class, 'getAllExams'])->middleWare('auth:api');
Route::get('/getAllExamRecord', [ExamController::class, 'getAllExamRecord'])->middleWare('auth:api');
Route::get('/listAllExams', [ExamController::class, 'listAllExams'])->middleWare('auth:api');
Route::get('/listAllExamRecords', [ExamController::class, 'listAllExamRecords'])->middleWare('auth:api');
Route::post('/updateExamEncrypt', [ExamController::class, 'updateExamEncrypt'])->middleWare('auth:api');
Route::post('/updateExam', [ExamController::class, 'updateExam'])->middleWare('auth:api');
Route::post('/updateExamRecordEncrypt', [ExamController::class, 'updateExamRecordEncrypt'])->middleWare('auth:api');
Route::post('/updateExamRecord', [ExamController::class, 'updateExamRecord'])->middleWare('auth:api');

//student
Route::post('/addStudentEncrypt', [StudentController::class, 'addStudentEncrypt'])->middleWare('auth:api');
Route::post('/addStudent', [StudentController::class, 'addStudent'])->middleWare('auth:api');
Route::get('/deleteStudent/{id}', [StudentController::class, 'deleteStudent'])->middleWare('auth:api');
Route::get('/getStudentRecord/{id}', [StudentController::class, 'getStudentRecord'])->middleWare('auth:api');
Route::get('/getAllStudents', [StudentController::class, 'getAllStudents'])->middleWare('auth:api');
Route::get('/listAllStudents', [StudentController::class, 'listAllStudents'])->middleWare('auth:api');
//Result
Route::post('/addResultRecordEncrypt', [ResultController::class, 'addResultRecordEncrypt'])->middleWare('auth:api');
Route::post('/addResultRecord', [ResultController::class, 'addResultRecord'])->middleWare('auth:api');
Route::post('/deleteResultRecord/{id}', [ResultController::class, 'deleteResultRecord'])->middleWare('auth:api');
//Staffs
Route::post('/addStaffEncrypt', [UserController::class, 'addStaffEncrypt'])->middleWare('auth:api');
Route::post('/addStaff', [UserController::class, 'addStaff'])->middleWare('auth:api');
Route::get('/getRoles', [UserController::class, 'getStaffs'])->middleWare('auth:api');
//classes
Route::post('/assignClassesEncrypt', [ClassController::class, 'assignClassesEncrypt'])->middleWare('auth:api');
Route::post('/assignClasses', [ClassController::class, 'assignClasses'])->middleWare('auth:api');
Route::post('/addClassesEncrypt', [ClassController::class, 'addClassesEncrypt'])->middleWare('auth:api');
Route::post('/addClass', [ClassController::class, 'addClasses'])->middleWare('auth:api');
Route::post('/updateClassesEncrypt', [ClassController::class, 'updateClassesEncrypt'])->middleWare('auth:api');
Route::post('/updateClass', [ClassController::class, 'updateClasses'])->middleWare('auth:api');
Route::get('/deleteClass/{id}', [ClassController::class, 'deleteClasses'])->middleWare('auth:api');
Route::get('/deleteAssignClasses/{id}', [ClassController::class, 'deleteAssignClasses'])->middleWare('auth:api');
Route::get('/getClassesRecord/{id}', [ClassController::class, 'getClassesRecord'])->middleWare('auth:api');
Route::get('/getAllClasses', [ClassController::class, 'getAllClasses'])->middleWare('auth:api');
Route::get('/listAllClasses', [ClassController::class, 'listAllClasses'])->middleWare('auth:api');

//Sections
// Route::post('/addSection', [SectionController::class, 'addSection'])->middleWare('auth:api');
// Route::get('/getSection/{id}', [SectionController::class, 'getSectionRecord'])->middleWare('auth:api');
// Route::get('/deleteSection/{id}', [SectionController::class, 'deleteSection'])->middleWare('auth:api');
// Route::get('/getAllSections', [SectionController::class, 'getAllSections'])->middleWare('auth:api');
Route::get('/listAllSections', [SectionController::class, 'listAllSections'])->middleWare('auth:api');
//Route::post('/updateSection', [SectionController::class, 'updateSection'])->middleWare('auth:api');

//subjects
Route::post('/addSubjectEncrypt', [SubjectController::class, 'addSubjectEncrypt'])->middleWare('auth:api');
Route::post('/updateSubjectsEncrypt', [SubjectController::class, 'updateSubjectsEncrypt'])->middleWare('auth:api');
Route::post('/updateSubjects', [SubjectController::class, 'updateSubjects'])->middleWare('auth:api');
Route::post('/addSubject', [SubjectController::class, 'addSubject'])->middleWare('auth:api');
Route::get('/deleteSubject/{id}', [SubjectController::class, 'deleteSubject'])->middleWare('auth:api');
Route::get('/getSubject/{id}', [SubjectController::class, 'getSubjectRecord'])->middleWare('auth:api');
Route::get('/getAllSubjects', [SubjectController::class, 'getAllSubjects'])->middleWare('auth:api');
Route::get('/listAllSubjects', [SubjectController::class, 'listAllSubjects'])->middleWare('auth:api');
//library Process
Route::post('/addBookCatagoryEncrypt', [BookController::class, 'addBookCatagoryEncrypt'])->middleWare('auth:api');
Route::post('/returnBookEncrypt', [BookController::class, 'toReturnEncrypt'])->middleWare('auth:api');
Route::post('/addBookCatagory', [BookController::class, 'addBookCatagory'])->middleWare('auth:api');
Route::post('/request_booksEncrypt', [BookController::class, 'request_booksEncrypt'])->middleWare('auth:api');
Route::post('/request_books', [BookController::class, 'request_books'])->middleWare('auth:api');
Route::get('/deleteBookRecords/{id}', [BookController::class, 'deleteBookRecords'])->middleWare('auth:api');
Route::get('/searchStudent/{id}', [BookController::class, 'searchStudent'])->middleWare('auth:api');
Route::get('/searchStaff/{id}', [BookController::class, 'searchStaff'])->middleWare('auth:api');
Route::get('/todayReturnList', [BookController::class, 'todayReturnList'])->middleWare('auth:api');
Route::post('/returnBook', [BookController::class, 'toReturn'])->middleWare('auth:api');
Route::post('/addBookEncrypt', [BookController::class, 'addBookEncrypt'])->middleWare('auth:api');
Route::post('/addBook', [BookController::class, 'addBook'])->middleWare('auth:api');
Route::post('/addBookSubCatagoryEncrypt', [BookController::class, 'addBookSubCatagoryEncrypt'])->middleWare('auth:api');
Route::post('/addBookSubCatagory', [BookController::class, 'addBookSubCatagory'])->middleWare('auth:api');
Route::post('/updateCatagoryEncrypt', [BookController::class, 'updateCatagoryEncrypt'])->middleWare('auth:api');
Route::post('/updateCatagory', [BookController::class, 'updateCatagory'])->middleWare('auth:api');
Route::post('/updateSubCatagoryEncrypt', [BookController::class, 'updateSubCatagoryEncrypt'])->middleWare('auth:api');
Route::post('/updateSubCatagory', [BookController::class, 'updateSubCatagory'])->middleWare('auth:api');
Route::post('/updateBookEncrypt', [BookController::class, 'updateBookEncrypt'])->middleWare('auth:api');
Route::post('/updateBook', [BookController::class, 'updateBook'])->middleWare('auth:api');
Route::get('/deleteCatagory/{id}', [BookController::class, 'deleteCatagory'])->middleWare('auth:api');
Route::get('/deleteBook/{id}', [BookController::class, 'deleteBook'])->middleWare('auth:api');
Route::get('/deleteBookSubCatagory/{id}', [BookController::class, 'deleteBookSubCatagory'])->middleWare('auth:api');
Route::get('/getCatagory/{id}', [BookController::class, 'getCatagory'])->middleWare('auth:api');
Route::get('/getBook/{id}', [BookController::class, 'getBook'])->middleWare('auth:api');
Route::get('/getBookSubCatagory/{id}', [BookController::class, 'getBookSubCatagory'])->middleWare('auth:api');
Route::get('/getAllCatagory', [BookController::class, 'getAllCatagory'])->middleWare('auth:api');
Route::get('/getAllBooks', [BookController::class, 'getAllBooks'])->middleWare('auth:api');
Route::get('/getAllorders', [BookController::class, 'getAllorders'])->middleWare('auth:api');
Route::get('/listAllCatagory', [BookController::class, 'listAllCatagory'])->middleWare('auth:api');
Route::get('/listAllSubCatagory', [BookController::class, 'listAllSubCatagory'])->middleWare('auth:api');
Route::get('/listAllBooks', [BookController::class, 'listAllBooks'])->middleWare('auth:api');
//Fees
// Route::get('/attend/{id}', [ReportController::class, 'attend']);
// Route::post('/addFees', [FeesController::class, 'addFees'])->middleWare('auth:api');
 Route::post('/initiateFeesEncrypt', [FeesController::class, 'initiateFeesEncrypt'])->middleWare('auth:api');
 Route::post('/initiateFees', [FeesController::class, 'initiateFees'])->middleWare('auth:api');
 Route::post('/pay_feesEncrypt', [FeesController::class, 'pay_feesEncrypt'])->middleWare('auth:api');
 Route::post('/pay_fees', [FeesController::class, 'pay_fees'])->middleWare('auth:api');
 Route::get('/deletePayFees/{id}', [FeesController::class, 'deletePayFees'])->middleWare('auth:api');
 Route::get('/deleteInitiateFees/{id}', [FeesController::class, 'deleteInitiateFees'])->middleWare('auth:api');
// Route::post('/updateFees', [FeesController::class, 'updateFees'])->middleWare('auth:api');
// Route::get('/deleteFees/{id}', [FeesController::class, 'deleteFees'])->middleWare('auth:api');
// Route::get('/getAllFees', [FeesController::class, 'getAllFees'])->middleWare('auth:api');
// Route::get('/getFees/{id}', [FeesController::class, 'getAllFees'])->middleWare('auth:api');
// Route::post('/addFeesCatagory', [FeesController::class, 'addFeesStructureCatagory'])->middleWare('auth:api');
// Route::post('/updateFeesCatagory', [FeesController::class, 'updateFeesStructureCatagory'])->middleWare('auth:api');
// Route::get('/deleteFeesCatagory/{id}', [FeesController::class, 'deleteFeesStructureCatagory'])->middleWare('auth:api');
// Route::get('/getFeesCatagory/{id}', [FeesController::class, 'getFeesStructureCatagory'])->middleWare('auth:api');
// Route::get('/getAllFeescatagory', [FeesController::class, 'getAllFeesStructurecatagory'])->middleWare('auth:api');
// Route::get('/listFeesCatagory', [FeesController::class, 'listAllFeesStructureCatagory'])->middleWare('auth:api');
//standards
Route::get('/listAllStandards', [ClassController::class, 'listAllStandards'])->middleWare('auth:api');
Route::post('/confirmAttendanceStudent', [StudentAttendance::class, 'confirmAttendanceStudent']);
Route::post('/attendanceStudent', [StudentAttendance::class, 'attendanceStudent']);
Route::post('/attendanceStaff', [StudentAttendance::class, 'attendanceStaff']);
//reports
Route::get('/feesNotPaidStudents_admin/{feedId}/{classId}', [ReportController::class, 'feesNotPaidStudents_admin'])->middleWare('auth:api');
Route::get('/feesNotPaidStudents_teacher/{feesId}', [ReportController::class, 'feesNotPaidStudents_teacher'])->middleWare('auth:api');
Route::get('/gradeCalculation_admin/{classId}/{examId}', [ReportController::class, 'gradeCalculation_admin'])->middleWare('auth:api');
Route::get('/gradeCalculation_teacher/{examId}', [ReportController::class, 'gradeCalculation_teacher'])->middleWare('auth:api');
Route::get('/studentAttendanceReport_admin/{classId}/{date}', [ReportController::class, 'studentAttendanceReport_admin'])->middleWare('auth:api');
Route::get('/studentAttendanceReport_teacher/{classId}', [ReportController::class, 'studentAttendanceReport_teacher'])->middleWare('auth:api');
Route::get('/feesAllDetails/{feesId}', [ReportController::class, 'feesAllDetails'])->middleWare('auth:api');
Route::get('/feesNotPaidStudents_teacher/{feesId}', [ReportController::class, 'feesNotPaidStudents_teacher'])->middleWare('auth:api');
Route::get('/feesNotPaidStudents_admin/{feesId}/classId', [ReportController::class, 'feesNotPaidStudents_admin'])->middleWare('auth:api');
Route::get('/PersonalInformation', [ReportController::class, 'PersonalInformation'])->middleWare('auth:api');
Route::get('/OverAllStudentList', [ReportController::class, 'OverAllStudentList'])->middleWare('auth:api');
Route::get('/staffSalarycalculationRecords', [ReportController::class, 'staffSalarycalculationRecords'])->middleWare('auth:api');
Route::get('/staffSalaryCalculationAdmin', [ReportController::class, 'staffSalaryCalculationAdmin'])->middleWare('auth:api');
Route::get('/overAllStaffList', [ReportController::class, 'overAllStaffList'])->middleWare('auth:api');
Route::get('/gradeCalculation_admin/{classId}/{examId}', [ReportController::class, 'gradeCalculation_admin'])->middleWare('auth:api');
//staff salary
Route::post('/addStaffSalaryEncrypt', [StaffSalaryController::class, 'addStaffSalaryEncrypt'])->middleWare('auth:api');
Route::post('/updatestaffSalaryEncrypt', [StaffSalaryController::class, 'updatestaffSalaryEncrypt'])->middleWare('auth:api');
Route::post('/addStaffSalary', [StaffSalaryController::class, 'addStaffSalary'])->middleWare('auth:api');
Route::get('/deleteStaffSalary/{Id}', [StaffSalaryController::class, 'deleteStaffSalary'])->middleWare('auth:api');
Route::get('/getStaffSalary/{Id}', [StaffSalaryController::class, 'getStaffSalary'])->middleWare('auth:api');
Route::get('/getStaffSalaries', [StaffSalaryController::class, 'getStaffSalaries'])->middleWare('auth:api');

