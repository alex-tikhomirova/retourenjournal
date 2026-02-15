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
            $shipment->cost_cents = isset($payload['cost'])
                ? (int) round($payload['cost'] * 100)
                : null;
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
}
