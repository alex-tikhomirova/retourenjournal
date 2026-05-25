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
use Illuminate\Support\Facades\DB;

/**
 * ShipmentService
 *
 * @author Alexandra Tikhomirova
 */
class ShipmentService
{
    public function create(ReturnModel $return, array $payload): ReturnShipment
    {
        return DB::transaction(function () use ($return, $payload) {

            $shipment = new ReturnShipment();
            $shipment->direction = $payload['direction'];
            $shipment->cost_cents = $payload['cost_cents']??null;
            $shipment->currency = $payload['currency']??'EUR';
            $shipment->payer = $payload['payer'];
            $shipment->carrier = $payload['carrier'];
            $shipment->tracking_number = $payload['tracking_number']??null;
            $shipment->label_ref = $payload['label_ref']??null;

            $return->shipments()->save($shipment);

            // TODO: Логирование

            // TODO: Пересчёт статуса возврата

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

        $shipment->direction = $payload['direction'] ?? $shipment->direction;
        $shipment->currency = $payload['currency'] ?? $shipment->currency;
        $shipment->payer = $payload['payer'] ?? $shipment->payer;
        $shipment->carrier = $payload['carrier'] ?? $shipment->carrier;
        $shipment->tracking_number = $payload['tracking_number'] ?? $shipment->tracking_number;
        $shipment->label_ref = $payload['label_ref'] ?? $shipment->label_ref;

        if (isset($payload['cost'])) {
            $shipment->cost_cents = (int) round($payload['cost'] * 100);
        }

        $shipment->save();

        // TODO: Логирование

        // TODO: Пересчёт статуса возврата

        return $shipment;
    }
}
