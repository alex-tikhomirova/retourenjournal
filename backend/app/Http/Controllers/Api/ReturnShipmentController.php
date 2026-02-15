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
use App\Http\Resources\ReturnShipmentResource;
use App\Models\ReturnModel;
use App\Services\ShipmentService;
use Illuminate\Http\JsonResponse;

/**
 * ShipmentController
 *
 * @author Alexandra Tikhomirova
 */
class ReturnShipmentController extends Controller
{
    public function store(ShipmentStoreRequest $request, ReturnModel $return): JsonResponse
    {
        $service = new ShipmentService();
        $shipment = $service->create($return, $request->validated());
        return (new ReturnShipmentResource($shipment))->response()->setStatusCode(201);
    }


}
