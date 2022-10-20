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

/*
| Wszystkie trasy w tej grupie będą sprawdzane czy mają w żądaniu
| HEADER 'Authorization' zawierający token passport. Token ten jest oddawany
| po zalogowaniu jako ciasteczko i ma swoją żywotność.
| (1 tydzień i odświeża się po ponownym logowaniu)
| Jest przechowywany w przeglądarce i musi być dodany po stronie klienta
| do każdego żądania.
*/
    Route::get("/test", \App\Http\Controllers\API\TestController::class);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/keyword',\App\Http\Controllers\API\KeyWordController::class);
    Route::apiResource('group', \App\Http\Controllers\API\GroupController::class);
    Route::prefix('attend')->group(function () {
        Route::get('/user/{user_id}', [\App\Http\Controllers\API\AttendController::class, 'whereIAttend']);
    });
});


