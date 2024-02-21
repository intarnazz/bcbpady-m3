<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\File\FileController;

Route::post("/registration", [UserController::class, "reg"]);
Route::post("/authorization", [UserController::class, "login"]);
Route::get("/logout", [UserController::class, "logout"]);
Route::post("/files", [FileController::class, "add"]);
