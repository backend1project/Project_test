<?php

use App\Http\Middleware\CheckTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
Route::post('user/create', [UserController::class, 'create']);
Route::prefix('user')->middleware([CheckTokenMiddleware::class])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/show/{id}',[UserController::class,'show']);
    Route::get('/update/{id}',[UserController::class,'update']);
});



