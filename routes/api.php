<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});

Route::middleware('jwt.auth')->group(function () {
    Route::get('/index', [ExpenseController::class , 'index']);
    Route::get('/show/{expense}', [ExpenseController::class, 'show']);
    Route::post('/store', [ExpenseController::class , 'store']);
    Route::put('/update/{expense}', [ExpenseController::class, 'update']);
    Route::delete('/delete/{expense}', [ExpenseController::class, 'destroy']);
});
