<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*auth*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login',]);
    Route::middleware('auth:api')->group(function () {
        Route::get('user', [\App\Http\Controllers\LoginController::class, 'currentUser']);
        Route::post('logout', [\App\Http\Controllers\LoginController::class, 'logout']);



    });
});
Route::middleware('auth:api')->group(function () {
    Route::apiResource( 'upload', UploadController::class )->only( 'index', 'show', 'store', 'destroy' );

    Route::post('meeting',[\App\Http\Controllers\MeetingController::class,'store']);
    Route::get('meeting',[\App\Http\Controllers\MeetingController::class,'index']);
});
