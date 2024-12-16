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

Route::post('register', App\Http\Controllers\Api\RegisterController::class); 
Route::post('login', App\Http\Controllers\Api\LoginController::class);
Route::post('logout', App\Http\Controllers\Api\LogoutController::class);

Route::middleware('jwt.verify')->group(function ()
{
    Route::get('posts', [App\Http\Controllers\Api\PostsController::class, 'index']); // start posts 
    Route::post('posts', [App\Http\Controllers\Api\PostsController::class, 'store']);
    Route::get('posts/{id}', [App\Http\Controllers\Api\PostsController::class, 'detail']);
    Route::put('posts/{id}', [App\Http\Controllers\Api\PostsController::class, 'update']);
    Route::delete('posts/{id}', [App\Http\Controllers\Api\PostsController::class, 'delete']); // end posts
    Route::get('download', [App\Http\Controllers\Api\DownloadController::class, 'index']); // start download
    Route::post('download', [App\Http\Controllers\Api\DownloadController::class, 'store']);
    Route::get('download/{id}', [App\Http\Controllers\Api\DownloadController::class, 'detail']);
    Route::put('download/{id}', [App\Http\Controllers\Api\DownloadController::class, 'update']);
    Route::delete('download/{id}', [App\Http\Controllers\Api\DownloadController::class, 'delete']); // end download
});