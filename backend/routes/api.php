<?php

use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\ReturnController;
use App\Http\Controllers\Api\ReturnShipmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organization', [OrganizationController::class, 'showCurrent']);
    Route::post('/organization', [OrganizationController::class, 'store']);
});
Route::middleware(['auth:sanctum', 'org.current'])->group(function () {
    Route::post('/returns/list', [ReturnController::class, 'list']);
    Route::get('/returns/{id}', [ReturnController::class, 'item']);
    Route::post('/returns/store', [ReturnController::class, 'store']);
    Route::post('/returns/{return}/shipments', [ReturnShipmentController::class, 'store']);
    Route::patch('/returns/{return}/shipments/{shipment}', [ReturnShipmentController::class, 'store']);
});

require __DIR__.'/auth.php';
