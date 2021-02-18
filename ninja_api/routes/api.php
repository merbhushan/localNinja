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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('ninja/leader-board', [App\Http\Controllers\API\v1\NinjaLeaderBoardController::class, 'index']);
Route::apiResource('ninja/users', App\Http\Controllers\API\v1\UserController::class)->except([
    'update'
]);

// Route::get('ninja/users', [App\Http\Controllers\API\v1\UserController::class, 'index']);
Route::post('ninja/users/{user}', [App\Http\Controllers\API\v1\UserController::class, 'update']);

// Route::put('ninja/users', [App\Http\Controllers\API\v1\UserController::class, 'store']);
// Route::delete('ninja/users/{user}', [App\Http\Controllers\API\v1\UserController::class, 'delete']);
