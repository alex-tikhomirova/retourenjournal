<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Services;


use App\Models\ReturnModel;
use App\Models\ReturnShipment;
use App\Models\ShipmentStatus;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * ShipmentService
 *
 * @author Alexandra Tikhomirova
 */
class ShipmentService
{
    /**
     * @throws Throwable
     */
    public function create(array $payload): ReturnShipment
    {
        return DB::transaction(function () use ($payload) {

            $return = ReturnModel::findOrFail($payload['return_id']);

            $shipment = new ReturnShipment();
            $shipment->shipment_number = ReturnShipment::nextNumberForOrganization($return->organization_id);
            $shipment->direction = $payload['direction'];
            $shipment->cost_cents = $payload['cost_cents']??null;
            $shipment->currency = $payload['currency']??'EUR';
            $shipment->payer = $payload['payer'];
            $shipment->carrier = $payload['carrier'];
            $shipment->tracking_number = $payload['tracking_number']??null;
            $shipment->label_ref = $payload['label_ref']??null;
            $shipment->status_id = $payload['status_id']??ShipmentStatus::initialShipmentStatus()->id;
            $return->shipments()->save($shipment);


            return $shipment;
        });
    }

    public function update(int $id, array $payload): ReturnShipment
    {
        // check exists
        /** @var ReturnShipment $shipment */
        $shipment = ReturnShipment::find($id);
        if (!$shipment) {
            abort(404, 'Die Sendung existiert nicht');
        }

        $shipment->status_id = $payload['status_id'] ?? $shipment->status_id;
        $shipment->carrier = $payload['carrier'] ?? $shipment->carrier;
        $shipment->tracking_number = $payload['tracking_number'] ?? $shipment->tracking_number;
        $shipment->label_ref = $payload['label_ref'] ?? $shipment->label_ref;
        $shipment->cost_cents = $payload['cost_cents']??null;
        $shipment->currency = $payload['currency']??'EUR';

        $shipment->save();
        return $shipment;
    }
}
