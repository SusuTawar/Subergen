<?php

use App\Http\Controllers\DocumentController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/upload", [DocumentController::class, 'store']);

Route::group(['prefix' => 'dokumen', 'as' => 'dokumen.'], static function () {
    Route::post("/info/{name}", [DocumentController::class, 'documentInfo']);
    Route::post("/make/{name}", [DocumentController::class, 'makeDocument'])->name('make');
});
