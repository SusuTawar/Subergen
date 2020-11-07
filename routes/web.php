<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['prefix' => 'dokumen', 'as' => 'dokumen.'], static function () {
    Route::get('/', [DocumentController::class, 'index'])->name('index');
    Route::get('/unggah', [DocumentController::class, 'upload']);
    Route::post('/unggah', [DocumentController::class, 'store']);
    Route::get('/unduh', [DocumentController::class, 'index'])->name('delete');
    Route::get('/unduh/{slug}', [DocumentController::class, 'create'])->name('download');
});
