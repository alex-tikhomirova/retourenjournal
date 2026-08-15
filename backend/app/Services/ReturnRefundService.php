<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Services;

use App\Models\RefundStatus;
use App\Models\ReturnModel;
use App\Models\ReturnRefund;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

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
     * @throws Throwable
     */
    public function create(array $payload): ReturnRefund
    {
        return DB::transaction(function () use ($payload) {
            $return = ReturnModel::findOrFail($payload['return_id']);
            $refund = new ReturnRefund();
            $refund->refund_number = ReturnRefund::nextNumberForOrganization($return->organization_id);
            $refund->amount_cents = $payload['amount_cents'];
            $refund->currency = $payload['currency']??'EUR';;
            $refund->reference = $payload['reference'] ?? null;
            $refund->status_id = $payload['status_id'] ?? RefundStatus::initialRefundStatus()->id; // pending

            $status = RefundStatus::find($refund->status_id);
            if ($status?->code === 'refunded') {
                $refund->processed_at = $payload['processed_at'] ?? Carbon::now();
            } else {
                $refund->processed_at = null;
            }

            $return->refunds()->save($refund);

            return $refund;
        });
    }

    /**
     * Update an existing refund.
     */
    public function update(int $id, array $payload): ReturnRefund
    {
        // check exists
        /** @var ReturnRefund|null $refund */
        $refund = ReturnRefund::find($id);
        if (!$refund) {
            abort(404, 'Die Erstattung existiert nicht');
        }

        $refund->reference = $payload['reference'] ?? $refund->reference;
        $refund->status_id = $payload['status_id'] ?? $refund->status_id;

        $status = RefundStatus::find($refund->status_id);
        if ($status?->code === 'refunded') {
            $refund->processed_at = $payload['processed_at'] ?? $refund->processed_at ?? Carbon::now();
        } else {
            $refund->processed_at = null;
        }

        $refund->save();

        return $refund;
    }
}
