<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\controllers\UserController;
use App\Http\controllers\SectionController;
use App\Http\controllers\SubjectController;
use App\Http\controllers\ClassController;
use App\Http\controllers\StudentAttendance;

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

//Sections
Route::post('/addSection', [SectionController::class, 'addSection'])->middleWare('auth:api');
Route::get('/getSection/{id}', [SectionController::class, 'getSectionRecord'])->middleWare('auth:api');
Route::get('/deleteSection/{id}', [SectionController::class, 'deleteSection'])->middleWare('auth:api');
Route::get('/getAllSections', [SectionController::class, 'getAllSections'])->middleWare('auth:api');
Route::get('/listAllSections', [SectionController::class, 'listAllSections'])->middleWare('auth:api');
Route::post('/updateSection', [SectionController::class, 'updateSection'])->middleWare('auth:api');

//subjects
Route::post('/addSubject', [SubjectController::class, 'addSubject'])->middleWare('auth:api');
Route::get('/deleteSubject/{id}', [SubjectController::class, 'deleteSubject'])->middleWare('auth:api');
Route::get('/getSubject/{id}', [SubjectController::class, 'getSubjectRecord'])->middleWare('auth:api');
Route::get('/getAllSubjects', [SubjectController::class, 'getAllSubjects'])->middleWare('auth:api');
Route::get('/listAllSubjects', [SubjectController::class, 'listAllSubjects'])->middleWare('auth:api');

//standards
Route::get('/listAllStandards', [ClassController::class, 'listAllStandards'])->middleWare('auth:api');
Route::get('/putAttendance', [StudentAttendance::class, 'putAttendance']);
