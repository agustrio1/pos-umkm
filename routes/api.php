<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\AuthController;

// Grouping routes under 'auth:sanctum' middleware for authenticated access
Route::middleware('auth:sanctum')->group(function () {
    // Product routes
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    // Order routes
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::put('orders/{id}', [OrderController::class, 'update']);
    Route::delete('orders/{id}', [OrderController::class, 'destroy']);

    // Order Item routes
    Route::post('orders/{orderId}/items', [OrderItemController::class, 'store']);
    Route::put('orders/{orderId}/items/{id}', [OrderItemController::class, 'update']);
    Route::delete('orders/{orderId}/items/{id}', [OrderItemController::class, 'destroy']);

    // Customer routes
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{id}', [CustomerController::class, 'show']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::put('customers/{id}', [CustomerController::class, 'update']);
    Route::delete('customers/{id}', [CustomerController::class, 'destroy']);

    // Category routes
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    // Supplier routes
    Route::get('suppliers', [SupplierController::class, 'index']);
    Route::get('suppliers/{id}', [SupplierController::class, 'show']);
    Route::post('suppliers', [SupplierController::class, 'store']);
    Route::put('suppliers/{id}', [SupplierController::class, 'update']);
    Route::delete('suppliers/{id}', [SupplierController::class, 'destroy']);

    // Purchase routes
    Route::get('purchases', [PurchaseController::class, 'index']);
    Route::get('purchases/{id}', [PurchaseController::class, 'show']);
    Route::post('purchases', [PurchaseController::class, 'store']);
    Route::put('purchases/{id}', [PurchaseController::class, 'update']);
    Route::delete('purchases/{id}', [PurchaseController::class, 'destroy']);
});

// Authentication routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);
