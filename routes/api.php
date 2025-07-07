<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NoteController;



Route::post('v1/register', [AuthController::class, 'register']);
Route::post('v1/login', [AuthController::class, 'login']);


Route::prefix('v1/notes')
    ->controller(NoteController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'detail');         // GET /category
        Route::post('/', 'store');        // POST /category
        Route::put('/{id}', 'update');    // PUT /category/5
        Route::delete('/{id}', 'destroy');// DELETE /category/5
    });