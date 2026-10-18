<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BankAccountController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ChequeController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DiscountController;
use App\Http\Controllers\Api\V1\ExpenseController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\PurchaseController;
use App\Http\Controllers\Api\V1\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('auth/login', [AuthController::class, 'login'])->name('api.v1.auth.login');
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/refresh', [AuthController::class, 'refreshToken']);

        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('products', ProductController::class);
        Route::get('products/{product}/recipes', [ProductController::class, 'getRecipes']);
        Route::put('products/{product}/recipes', [ProductController::class, 'syncRecipes']);
        Route::get('inventory/logs/{product}', [InventoryController::class, 'logs']);
        Route::post('inventory/adjust', [InventoryController::class, 'adjust']);
        Route::get('inventory', [InventoryController::class, 'index']);
        Route::apiResource('customers', CustomerController::class);
        Route::get('customers/{customer}/purchases', [CustomerController::class, 'purchases']);
        Route::apiResource('discounts', DiscountController::class);
        Route::put('discounts/{discount}/products', [DiscountController::class, 'syncProducts']);
        Route::apiResource('bank-accounts', BankAccountController::class);
        Route::get('bank-accounts/{bank_account}/turnover', [BankAccountController::class, 'turnover']);
        Route::apiResource('cheques', ChequeController::class);
        Route::patch('cheques/{cheque}/status', [ChequeController::class, 'changeStatus']);
        Route::apiResource('expenses', ExpenseController::class);
        Route::apiResource('purchases', PurchaseController::class);
        Route::get('orders', [OrderController::class, 'index']);
        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders/{order}', [OrderController::class, 'show']);
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
        Route::get('reports/daily-sales', [ReportController::class, 'dailySales']);
        Route::get('reports/product-stats', [ReportController::class, 'productStats']);
        Route::get('reports/dashboard-summary', [ReportController::class, 'dashboardSummary']);
    });
});
