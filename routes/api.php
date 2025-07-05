<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;



Route::post('V1/register', [AuthController::class, 'register']);
Route::post('V1/login', [AuthController::class, 'login']);

// Route::get('/category', [ExpenseCategoryController::class, 'categories']);

// Route::get('/category', [ExpenseCategoryController::class, 'index']);
// Route::post('/category', [ExpenseCategoryController::class, 'store']);

Route::prefix('V1/category')
    // ->middleware('auth:api')
    ->controller(ExpenseCategoryController::class)
    ->group(function () {
        Route::get('/', 'index');         // GET /category
        Route::post('/', 'store');        // POST /category
        Route::put('/{id}', 'update');    // PUT /category/5
        Route::delete('/{id}', 'destroy');// DELETE /category/5
    });

Route::prefix('V1/expense')
    ->controller(ExpenseRecordController::class)
    ->group(function (){
        Route::get('/',"index");
        Route::post('/',"store");
        Route::put('/{id}',"update");
        Route::delete('/{id}',"destroy");
    });

Route::prefix('V1/income')
    ->controller(IncomeRecordController::class)
    ->group(function (){
        Route::get('/',"index");
        Route::post('/',"store");
        Route::put('/{id}',"update");
        Route::delete('/{id}',"destroy");
    });



