<?php

use App\Http\Controllers\PlayIntegrityController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/delete-user', [App\Http\Controllers\ApiController::class, 'deleteUserFromDb'])->name('deleteUserFromDb');

Route::post('/verify-integrity', [PlayIntegrityController::class, 'validateToken']);

// Route::post('/verify-integrity', [PlayIntegrityController::class, 'verifyToken']);
