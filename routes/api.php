<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\controllers\UserController;

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
<<<<<<< HEAD
Route::get('/getStaffList', [UserController::class, 'getStaffs']);
=======
Route::get('/getStaffList', [UserController::class, 'getStaffs'])->middleWare('auth:api');
>>>>>>> a4bd76dee440c50b817523bfc6e90c50980545b2
