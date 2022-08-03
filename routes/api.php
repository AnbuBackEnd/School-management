<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\controllers\UserController;
use App\Http\controllers\SectionController;
use App\Http\controllers\SubjectController;
use App\Http\controllers\ClassController;
use App\Http\controllers\StudentAttendance;
use App\Http\controllers\FeesController;
use App\Http\controllers\ExamController;
use App\Http\controllers\StudentController;
use App\Http\controllers\ResultController;
use App\Http\controllers\BookController;
use App\Http\controllers\ReportController;
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

Route::get('/unauthenticated', [UserController::class, 'unauthenticated'])->name('unauthenticated');
Route::post('/register', [UserController::class, 'register']);
Route::post('/accountVerification', [UserController::class, 'accountVerification']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/decrypt', [UserController::class, 'decrypt_user']);
Route::post('/addStaff', [UserController::class, 'addStaff'])->middleWare('auth:api');
Route::get('/getStaffList', [UserController::class, 'getStaffs'])->middleWare('auth:api');
Route::get('/enc', [UserController::class, 'encryptData_sample']);
//Exam
Route::post('/addExam', [ExamController::class, 'addExam'])->middleWare('auth:api');
Route::post('/addExamRecords', [ExamController::class, 'addExamRecords'])->middleWare('auth:api');
Route::get('/deleteExam/{id}', [ExamController::class, 'deleteExam'])->middleWare('auth:api');
Route::get('/deleteExamRecord/{id}', [ExamController::class, 'deleteExamRecord'])->middleWare('auth:api');
Route::get('/getExam/{id}', [ExamController::class, 'getExam'])->middleWare('auth:api');
Route::get('/getExamRecord/{id}', [ExamController::class, 'getExamRecord'])->middleWare('auth:api');
Route::get('/getAllExams', [ExamController::class, 'getAllExams'])->middleWare('auth:api');
Route::get('/getAllExamRecord', [ExamController::class, 'getAllExamRecord'])->middleWare('auth:api');
Route::get('/listAllExams', [ExamController::class, 'listAllExams'])->middleWare('auth:api');
Route::get('/listAllExamRecords', [ExamController::class, 'listAllExamRecords'])->middleWare('auth:api');
Route::post('/updateExam', [ExamController::class, 'updateExam'])->middleWare('auth:api');
Route::post('/updateExamRecord', [ExamController::class, 'updateExamRecord'])->middleWare('auth:api');

//student
Route::post('/addStudent', [StudentController::class, 'addStudent'])->middleWare('auth:api');
Route::get('/deleteStudent/{id}', [StudentController::class, 'deleteStudent'])->middleWare('auth:api');
Route::get('/getStudentRecord/{id}', [StudentController::class, 'getStudentRecord'])->middleWare('auth:api');
Route::get('/getAllStudents', [StudentController::class, 'getAllStudents'])->middleWare('auth:api');
Route::get('/listAllStudents', [StudentController::class, 'listAllStudents'])->middleWare('auth:api');
//Result
Route::post('/addResultRecord', [ResultController::class, 'addResultRecord'])->middleWare('auth:api');
Route::post('/deleteResultRecord/{id}', [ResultController::class, 'deleteResultRecord'])->middleWare('auth:api');
//Staffs
Route::post('/addStaff', [UserController::class, 'addStaff'])->middleWare('auth:api');
Route::get('/getRoles', [UserController::class, 'getStaffs'])->middleWare('auth:api');
//classes
Route::post('/addClass', [ClassController::class, 'addClasses'])->middleWare('auth:api');
Route::post('/updateClass', [ClassController::class, 'updateClasses'])->middleWare('auth:api');
Route::get('/deleteClass/{id}', [ClassController::class, 'deleteClasses'])->middleWare('auth:api');
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
Route::post('/addSubject', [SubjectController::class, 'addSubject'])->middleWare('auth:api');
Route::get('/deleteSubject/{id}', [SubjectController::class, 'deleteSubject'])->middleWare('auth:api');
Route::get('/getSubject/{id}', [SubjectController::class, 'getSubjectRecord'])->middleWare('auth:api');
Route::get('/getAllSubjects', [SubjectController::class, 'getAllSubjects'])->middleWare('auth:api');
Route::get('/listAllSubjects', [SubjectController::class, 'listAllSubjects'])->middleWare('auth:api');
//library Process
Route::post('/addBookCatagory', [BookController::class, 'addBookCatagory'])->middleWare('auth:api');
Route::post('/request_books', [BookController::class, 'request_books'])->middleWare('auth:api');
Route::get('/deleteBookRecords/{id}', [BookController::class, 'deleteBookRecords'])->middleWare('auth:api');
Route::get('/searchStudent/{id}', [BookController::class, 'searchStudent'])->middleWare('auth:api');
Route::get('/searchStaff/{id}', [BookController::class, 'searchStaff'])->middleWare('auth:api');
Route::get('/todayReturnList', [BookController::class, 'todayReturnList'])->middleWare('auth:api');
Route::post('/returnBook', [BookController::class, 'toReturn'])->middleWare('auth:api');
Route::post('/addBook', [BookController::class, 'addBook'])->middleWare('auth:api');
Route::post('/addBookSubCatagory', [BookController::class, 'addBookSubCatagory'])->middleWare('auth:api');
Route::post('/updateCatagory', [BookController::class, 'updateCatagory'])->middleWare('auth:api');
Route::post('/updateSubCatagory', [BookController::class, 'updateSubCatagory'])->middleWare('auth:api');
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
 Route::post('/initiateFees', [FeesController::class, 'initiateFees'])->middleWare('auth:api');
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
Route::post('/putAttendance', [StudentAttendance::class, 'putAttendance']);
//reports
Route::get('/feesNotPaidStudents_admin/{feedId}/{classId}', [ReportController::class, 'feesNotPaidStudents_admin'])->middleWare('auth:api');
Route::get('/feesNotPaidStudents_teacher/{feesId}', [ReportController::class, 'feesNotPaidStudents_teacher'])->middleWare('auth:api');
Route::get('/gradeCalculation_admin/{classId}/{examId}', [ReportController::class, 'gradeCalculation_admin'])->middleWare('auth:api');
Route::get('/gradeCalculation_teacher/{examId}', [ReportController::class, 'gradeCalculation_teacher'])->middleWare('auth:api');
Route::get('/studentAttendanceReport_admin/{classId}/{date}', [ReportController::class, 'studentAttendanceReport_admin'])->middleWare('auth:api');
Route::get('/studentAttendanceReport_teacher/{classId}', [ReportController::class, 'studentAttendanceReport_teacher'])->middleWare('auth:api');
