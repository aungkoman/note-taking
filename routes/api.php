<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NoteController;



Route::post('V1/register', [AuthController::class, 'register']);
Route::post('V1/login', [AuthController::class, 'login']);


Route::prefix('V1/notes')
    // ->middleware('auth:api')
    ->controller(NoteController::class)
    ->group(function () {
        Route::get('/', 'index');         // GET /category
        Route::post('/', 'store');        // POST /category
        Route::put('/{id}', 'update');    // PUT /category/5
        Route::delete('/{id}', 'destroy');// DELETE /category/5
    });