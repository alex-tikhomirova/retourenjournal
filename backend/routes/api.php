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
    Route::patch('/returns/{id}', [ReturnController::class, 'update']);
    Route::post('/returns/store', [ReturnController::class, 'store']);
    Route::post('/returns/{return}/shipments', [ReturnShipmentController::class, 'store']);
    Route::patch('/returns/{return}/shipments/{shipment}', [ReturnShipmentController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'org.current'])->group(function () {
    Route::get('/return-statuses', function () {
        return \App\Http\Resources\ReturnStatusResource::collection(
            \App\Models\ReturnStatus::orderBy('sort_order')->get()
        );
    });
    Route::get('/return-decisions', function () {
        return \App\Http\Resources\ReturnDecisionResource::collection(
            \App\Models\ReturnDecision::orderBy('sort_order')->get()
        );
    });
    Route::get('/shipment-statuses', function () {
        return \App\Http\Resources\ShipmentStatusResource::collection(
            \App\Models\ShipmentStatus::orderBy('sort_order')->get()
        );
    });
    Route::get('/refund-statuses', function () {
        return \App\Http\Resources\RefundStatusResource::collection(
            \App\Models\RefundStatus::orderBy('sort_order')->get()
        );
    });

    Route::get('/lookups', [\App\Http\Controllers\Api\LookupController::class, 'index']);
});

require __DIR__.'/auth.php';
