<?php

use App\Http\Controllers\BucketController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\ObjectController;
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
Route::group(['prefix' => '', 'middleware' => ['auth']], function($routes) {
    Route::get('/cat', [CatController::class, 'index']);
    Route::post('/cat', [CatController::class, 'store']);
    Route::get('/cat/create', [CatController::class, 'create']);
    Route::get('/cat/{cod}', [CatController::class, 'show']);
    Route::get('/cat/{cod}/delete', [CatController::class, 'destroy']);
});
Route::group(['prefix' => 'buckets', 'middleware' => ['auth']], function($routes) {
    Route::get('/', [BucketController::class, 'index']);
    Route::post('/', [BucketController::class, 'store']);
    Route::get('/create', [BucketController::class, 'create']);
    Route::get('/{id}/delete', [BucketController::class, 'destroy']);


    Route::get('/{id}/objects', [ObjectController::class, 'index']);
    Route::post('/{id}/objects', [ObjectController::class, 'store']);
    Route::get('/{id}/objects/create', [ObjectController::class, 'create']);
    Route::get('/{id}/objects/{cod}', [ObjectController::class, 'show']);
    Route::get('/{id}/objects/{cod}/delete', [ObjectController::class, 'destroy']);
});


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');


