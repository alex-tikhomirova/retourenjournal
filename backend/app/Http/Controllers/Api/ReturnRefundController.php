<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnRefund\ReturnRefundStoreRequest;
use App\Http\Requests\ReturnRefund\ReturnRefundUpdateRequest;
use App\Http\Resources\ReturnRefundResource;
use App\Models\ReturnModel;
use App\Services\ReturnRefundService;
use Illuminate\Http\JsonResponse;

class ReturnRefundController extends Controller
{
    public function store(ReturnRefundStoreRequest $request, ReturnModel $return): JsonResponse
    {
        $service = new ReturnRefundService();
        $refund = $service->create($return, $request->validated());
        return (new ReturnRefundResource($refund))->response()->setStatusCode(201);
    }

    public function update(ReturnRefundUpdateRequest $request, ReturnModel $return, int $refund): JsonResponse
    {
        // $return нужен для route model binding и проверки tenant-доступа через OrganizationScope.
        $service = new ReturnRefundService();
        $updatedShipment = $service->update($refund, $request->validated());
        return (new ReturnRefundResource($updatedShipment))->response()->setStatusCode(201);
    }
}
