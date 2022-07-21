<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\controllers\UserController;
use App\Http\controllers\SectionController;

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
Route::get('/getSection/{id}', [SectionController::class, 'getSectionRecord']);
Route::get('/getAllSections', [SectionController::class, 'getAllSections']);
Route::get('/listAllSections', [SectionController::class, 'listAllSections']);
Route::post('/updateSection', [SectionController::class, 'updateSection']);
