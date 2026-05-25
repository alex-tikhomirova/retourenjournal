<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Services;

use App\Models\ReturnModel;
use App\Models\ReturnRefund;
use Illuminate\Support\Facades\DB;

/**
 * ReturnRefundService
 *
 * Handles creation and update of return refunds.
 *
 * @author Alexandra Tikhomirova
 */
class ReturnRefundService
{
    /**
     * Create a new refund for a return.
     */
    public function create(ReturnModel $return, array $payload): ReturnRefund
    {
        return DB::transaction(function () use ($return, $payload) {
            $refund = new ReturnRefund();
            $refund->amount_cents = $payload['amount_cents'];
            $refund->currency = $payload['currency']??'EUR';;
            $refund->reference = $payload['reference'] ?? null;
            $refund->status_id = 2; // pending

            $return->refunds()->save($refund);

            // TODO: Add logging

            // TODO: Recalculate return status

            return $refund;
        });
    }

    /**
     * Update an existing refund.
     */
    public function update(int $id, array $payload): ReturnRefund
    {
        // Check that the refund exists in current organization scope.
        /** @var ReturnRefund|null $refund */
        $refund = ReturnRefund::find($id);
        if (!$refund) {
            abort(404, 'Die Erstattung existiert nicht');
        }

        $refund->reference = $payload['reference'] ?? $refund->reference;
        $refund->status_id = $payload['status_id'] ?? $refund->status_id;

        $refund->save();

        // TODO: Add logging

        // TODO: Recalculate return status

        return $refund;
    }
}
