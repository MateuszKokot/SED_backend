<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

// Trasa logowani, która oddaje Passport Token jako ciasteczko o nazwie 'access_token'
Route::post("/login", \App\Http\Controllers\API\LoginController::class);
Route::get("/test", \App\Http\Controllers\API\TestController::class);
Route::get("/availableKeywords", [\App\Http\Controllers\API\AvailableKeywordsController::class, "availableKeywords"]);

//
Route::middleware(['auth:api'])->group(function () {
    Route::post('/keyword',\App\Http\Controllers\API\KeyWordController::class);
    Route::apiResource('group', \App\Http\Controllers\API\GroupController::class);
    Route::prefix('attend')->group(function () {
        Route::get('/user/{user_id}', [\App\Http\Controllers\API\AttendController::class, 'whereIAttend']);
    });
    Route::post('/showResults', \App\Http\Controllers\API\ShowResultsController::class);

    // Grupa routingów do zabawy z Firebase
    Route::prefix('firebase')->group(function () {
        Route::get('/userByUID/{uid}', [\App\Http\Controllers\API\FirebaseController::class, 'userByUID']);
    });

    Route::post('/member/create', [\App\Http\Controllers\API\MemberController::class,'store']);
    Route::delete('/member/delete', [\App\Http\Controllers\API\MemberController::class,'destroy']);

});


