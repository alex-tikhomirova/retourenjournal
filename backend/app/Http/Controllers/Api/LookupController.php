<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\RefundStatusResource;
use App\Http\Resources\ReturnDecisionResource;
use App\Http\Resources\ReturnStatusResource;
use App\Http\Resources\ShipmentStatusResource;
use App\Models\RefundStatus;
use App\Models\ReturnDecision;
use App\Models\ReturnStatus;
use App\Models\ShipmentStatus;

/**
 * LookupController
 *
 * @author Alexandra Tikhomirova
 */
class LookupController extends Controller
{
    public function index()
    {

        $returnStatuses = ReturnStatus::query()->orderBy('sort_order')->get();
        $returnDecisions = ReturnDecision::query()->orderBy('sort_order')->get();
        $shipmentStatuses = ShipmentStatus::query()->orderBy('sort_order')->get();
        $refundStatuses = RefundStatus::query()->orderBy('sort_order')->get();

        return response()->json([
            'return_statuses'   => ReturnStatusResource::collection($returnStatuses),
            'return_decisions'  => ReturnDecisionResource::collection($returnDecisions),
            'shipment_statuses' => ShipmentStatusResource::collection($shipmentStatuses),
            'refund_statuses'   => RefundStatusResource::collection($refundStatuses),
        ]);
    }
}
