<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Shipping\ShipmentStoreRequest;
use App\Http\Requests\Shipping\ShipmentUpdateRequest;
use App\Http\Resources\ReturnShipmentResource;
use App\Models\ReturnModel;
use App\Services\ShipmentService;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * ShipmentController
 *
 * @author Alexandra Tikhomirova
 */
class ReturnShipmentController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(ShipmentStoreRequest $request): JsonResponse
    {
        $service = new ShipmentService();
        $shipment = $service->create($request->validated());
        return (new ReturnShipmentResource($shipment))->response()->setStatusCode(201);
    }

    public function update(ShipmentUpdateRequest $request, int $shipment): JsonResponse
    {
        // $return нужен для route model binding и проверки tenant-доступа через OrganizationScope.
        $service = new ShipmentService();
        $updatedShipment = $service->update($shipment, $request->validated());
        return (new ReturnShipmentResource($updatedShipment))->response()->setStatusCode(201);
    }


}
