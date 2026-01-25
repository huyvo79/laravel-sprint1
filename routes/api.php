<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VariantController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('products/import', [ProductController::class, 'import']);
    Route::get('products/export', [ProductController::class, 'export']);
    Route::post('products/bulk-add-tag', [ProductController::class, 'bulkAddTag']);
    Route::post('products/{id}/restore', [ProductController::class, 'restore']);
    Route::apiResource('products', ProductController::class);

    Route::get('products/{productId}/variants', [VariantController::class, 'index']);

    Route::put('variants/{id}', [VariantController::class, 'update']);
    Route::delete('variants/{id}', [VariantController::class, 'destroy']);

});


Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify')
    ->middleware(['signed']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);