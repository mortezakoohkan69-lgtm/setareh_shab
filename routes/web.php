<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;
use Modules\Store\Http\Controllers\StoreController;
use App\Http\Controllers\Admin\ExportController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/{resource}/export/excel', [ExportController::class, 'excel'])->name('admin.export.excel');
Route::get('/admin/{resource}/export/print', [ExportController::class, 'print'])->name('admin.export.print');

Route::prefix('admin')->group(function (): void {
    foreach (array_keys(StoreController::resources()) as $resource) {
        Route::resource($resource, StoreController::class)
            ->except(['show'])
            ->names("admin.{$resource}");
    }
});
