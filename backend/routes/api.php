<?php

use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ReturnController;
use App\Http\Controllers\Api\ReturnRefundController;
use App\Http\Controllers\Api\ReturnShipmentController;
use App\Http\Resources\RefundStatusResource;
use App\Http\Resources\ReturnDecisionResource;
use App\Http\Resources\ReturnStatusResource;
use App\Http\Resources\ShipmentStatusResource;
use App\Models\RefundStatus;
use App\Models\ReturnDecision;
use App\Models\ReturnStatus;
use App\Models\ShipmentStatus;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organization', [OrganizationController::class, 'showCurrent']);
    Route::post('/organization', [OrganizationController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'org.current'])->group(function () {
    Route::post('/customers/search', [CustomerController::class, 'search']);
    Route::patch('/customers/{id}', [CustomerController::class, 'update']);
    Route::post('/returns/list', [ReturnController::class, 'list']);
    Route::get('/returns/next-number', [ReturnController::class, 'nextNumber']);
    Route::get('/returns/{id}', [ReturnController::class, 'item']);
    Route::patch('/returns/{id}', [ReturnController::class, 'update']);
    Route::post('/returns/store', [ReturnController::class, 'store']);
    Route::patch('/returns/{return}/decision', [ReturnController::class, 'decision']);
    Route::post('/returns/{return}/shipments', [ReturnShipmentController::class, 'store']);
    Route::patch('/returns/{return}/shipments/{shipment}', [ReturnShipmentController::class, 'update']);
    Route::post('/returns/{return}/refunds', [ReturnRefundController::class, 'store']);
    Route::patch('/returns/{return}/refund/{refund}', [ReturnRefundController::class, 'update']);
});

Route::middleware(['auth:sanctum', 'org.current'])->group(function () {
    Route::get('/return-statuses', function () {
        return ReturnStatusResource::collection(
            ReturnStatus::orderBy('sort_order')->get()
        );
    });
    Route::get('/return-decisions', function () {
        return ReturnDecisionResource::collection(
            ReturnDecision::orderBy('sort_order')->get()
        );
    });
    Route::get('/shipment-statuses', function () {
        return ShipmentStatusResource::collection(
            ShipmentStatus::orderBy('sort_order')->get()
        );
    });
    Route::get('/refund-statuses', function () {
        return RefundStatusResource::collection(
            RefundStatus::orderBy('sort_order')->get()
        );
    });

    Route::get('/lookups', [LookupController::class, 'index']);
});

require __DIR__.'/auth.php';
