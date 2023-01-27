<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostContoller;
use App\Http\Controllers\API\AuthenticatinController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthenticatinController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthenticatinController::class, 'logout']);
    Route::get('/me', [AuthenticatinController::class, 'me']);
    Route::post('/posts', [PostContoller::class, 'store']);
    Route::patch('/posts/{id}', [PostContoller::class, 'update'])->middleware('pemilik-postingan');
    Route::delete('/posts/{id}', [PostContoller::class, 'destroy'])->middleware('pemilik-postingan');
});


Route::get('/posts', [PostContoller::class, 'index']);
Route::get('/posts/{id}', [PostContoller::class, 'show']);

