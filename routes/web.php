<?php

use App\Http\Controllers\AssetPublicController;
use App\Http\Controllers\AssetQRController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeImportController;

Route::post('/employee/import/preview', [EmployeeImportController::class, 'preview'])->name('employee.import.preview');
Route::post('/employee/import/confirm', [EmployeeImportController::class, 'confirm'])->name('employee.import.confirm');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/assets/{asset}', [AssetPublicController::class, 'show'])
    ->name('assets.public.show');

    Route::get('/assets/qr/print', [AssetQRController::class, 'printAll'])
    ->name('assets.qr.print');

    Route::get('/assets/qr/bulk/{ids}', [AssetPublicController::class, 'bulkPrint'])
    ->name('assets.qr.bulk');

    Route::get('/assets/qr/all', [AssetPublicController::class, 'printAll'])
    ->name('assets.qr.all');
