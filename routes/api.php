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

/* start auth */
Route::post('register', App\Http\Controllers\Api\RegisterController::class)->name('register'); 
Route::post('login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
/* end auth */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function ()
{
    Route::get('posts', [App\Http\Controllers\Api\PostsController::class, 'index'])->name('posts'); // start posts 
    Route::post('posts', [App\Http\Controllers\Api\PostsController::class, 'store'])->name('posts');
    Route::get('posts/{id}', [App\Http\Controllers\Api\PostsController::class, 'detail'])->name('posts');
    Route::put('posts/{id}', [App\Http\Controllers\Api\PostsController::class, 'update'])->name('posts');
    Route::delete('posts/{id}', [App\Http\Controllers\Api\PostsController::class, 'delete'])->name('posts'); // end posts
    Route::get('download', [App\Http\Controllers\Api\DownloadController::class, 'index'])->name('download'); // start download
    Route::post('download', [App\Http\Controllers\Api\DownloadController::class, 'store'])->name('download');
    Route::get('download/{id}', [App\Http\Controllers\Api\DownloadController::class, 'detail'])->name('download');
    Route::put('download/{id}', [App\Http\Controllers\Api\DownloadController::class, 'update'])->name('download');
    Route::delete('download/{id}', [App\Http\Controllers\Api\DownloadController::class, 'delete'])->name('download'); // end download
});