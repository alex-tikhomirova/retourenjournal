<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnRefund\ReturnRefundStoreRequest;
use App\Http\Requests\ReturnRefund\ReturnRefundUpdateRequest;
use App\Http\Resources\ReturnRefundResource;
use App\Services\ReturnRefundService;
use Illuminate\Http\JsonResponse;
use Throwable;

class ReturnRefundController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(ReturnRefundStoreRequest $request): JsonResponse
    {
        $service = new ReturnRefundService();
        $refund = $service->create($request->validated());
        return (new ReturnRefundResource($refund))->response()->setStatusCode(201);
    }

    public function update(ReturnRefundUpdateRequest $request, int $refund): JsonResponse
    {
        $service = new ReturnRefundService();
        $updatedShipment = $service->update($refund, $request->validated());
        return (new ReturnRefundResource($updatedShipment))->response()->setStatusCode(201);
    }
}
