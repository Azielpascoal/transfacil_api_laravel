<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthControllerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RotasControllerController;
use App\Http\Controllers\UserControllerController;

Route::post('login', [AuthControllerController::class, 'authenticate']);
Route::post('register', [AuthControllerController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function () {

    //Trajectoria do user tanto como cliente taxista e Admin
    Route::get('liste_route', [RotasControllerController::class, 'show']);
    Route::post('create_route', [RotasControllerController::class, 'store']);
    Route::post('verificar_is_token', [RotasControllerController::class, 'verificar_is_token']);

        // Routa do user tanto como cliente taxista e Admin
    Route::get('get_user', [UserControllerController::class, 'index']);
});
