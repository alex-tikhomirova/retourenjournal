<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Services;


use App\Models\ReturnDecision;
use App\Models\ReturnItem;
use App\Models\ReturnModel;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * ReturnService
 *
 * @author Alexandra Tikhomirova
 */
class ReturnService
{
    private const DEFAULT_RETURN_NUMBER = 'RET-0001';

    /**
     * Create a return, upserting the submitted customer and attaching line items.
     *
     * @param array{
     *     return_number: string,
     *     customer: array<string, mixed>,
     *     items: array<int, array<string, mixed>>,
     *     order_reference?: string|null
     * } $payload
     * @return ReturnModel
     * @throws Throwable
     */
    public function create(array $payload): ReturnModel
    {
        return DB::transaction(function () use ($payload) {

            // check duplicate
            $exists = ReturnModel::query()
                ->where('return_number', $payload['return_number'])
                ->exists();
            if ($exists) {
                abort(409, 'Die Retourennummer existiert bereits');
            }

            $customer = (new CustomerService())->upsertFromReturn($payload['customer']);

            $return = new ReturnModel();

            $return->return_number = $payload['return_number'];
            $return->customer_id = $customer->id;
            $return->reason = $payload['reason'] ?? null;
            $return->order_reference = $payload['order_reference'] ?? null;

            $return->save();

            $this->syncItems($return, $payload['items']);

            return $return;
        });
    }

    /**
     * Update scalar fields on an existing return.
     *
     * @param int $id
     * @param array{
     *     return_number?: string|null,
     *     status_id?: int|null,
     *     decision_id?: int|null,
     *     order_reference?: string|null,
     *     reason?: string|null
     * } $payload
     * @return ReturnModel
     */
    public function update(int $id, array $payload): ReturnModel
    {
        $return = ReturnModel::query()->findOrFail($id);
        $return->return_number = $payload['return_number'] ?? $return->return_number;
        $return->status_id = $payload['status_id'] ?? $return->status_id;
        $return->decision_id = $payload['decision_id'] ?? $return->decision_id;
        $return->order_reference = $payload['order_reference'] ?? $return->order_reference;
        $return->reason = $payload['reason'] ?? $return->reason;
        $return->save();
        return $return;
    }

    public function updateDecision(int $id, int $decisionId): ReturnModel
    {
        $return = ReturnModel::query()->findOrFail($id);
        if ($return->decision_id !== $decisionId) {
            $decision = ReturnDecision::query()->findOrFail($decisionId);
            $return->decision_id = $decision->id;
            $return->status_id = $decision->nextStatus->id;
            $return->save();
        }
        return $return;
    }

    /**
     * Build the next available return number from the latest stored number.
     *
     * @return string
     */
    public function nextReturnNumber(): string
    {
        $lastReturnNumber = ReturnModel::query()
            ->whereNotNull('return_number')
            ->orderByDesc('id')
            ->value('return_number');

        $candidate = $lastReturnNumber
            ? $this->incrementReturnNumber($lastReturnNumber)
            : self::DEFAULT_RETURN_NUMBER;

        if ($candidate === null) {
            $candidate = self::DEFAULT_RETURN_NUMBER;
        }

        while (ReturnModel::query()->where('return_number', $candidate)->exists()) {
            $candidate = $this->incrementReturnNumber($candidate) ?? self::DEFAULT_RETURN_NUMBER;
        }

        return $candidate;
    }

    /**
     * Persist submitted return items for a newly created return.
     *
     * @param ReturnModel $return
     * @param array<int, array{
     *     line_no: int|string,
     *     sku?: string|null,
     *     serial?: string|null,
     *     item_name: string,
     *     quantity: int|string,
     *     unit_price_cents?: int|null,
     *     currency?: string|null
     * }> $items
     * @return void
     */
    private function syncItems(ReturnModel $return, array $items): void
    {
        foreach ($items as $it) {
            $item = new ReturnItem();
            $item->line_no = (int) $it['line_no'];
            $item->sku = $it['sku'] ?? null;
            $item->serial = $it['serial'] ?? null;
            $item->item_name = $it['item_name'];
            $item->quantity = (int) $it['quantity'];
            $item->unit_price_cents = $it['unit_price_cents'] ?? null;
            $item->currency = $it['currency'] ?? 'EUR';

            $return->items()->save($item);
        }
    }

    /**
     * Increment the last numeric block and drop any suffix after that block.
     *
     * @param string $returnNumber
     * @return string|null
     */
    private function incrementReturnNumber(string $returnNumber): ?string
    {
        if (preg_match_all('/\d+/', $returnNumber, $matches, PREG_OFFSET_CAPTURE) === 0) {
            return null;
        }

        $lastMatch = end($matches[0]);
        $number = $lastMatch[0];
        $offset = $lastMatch[1];
        $prefix = substr($returnNumber, 0, $offset);
        $nextNumber = (string) ((int) $number + 1);

        return $prefix . str_pad($nextNumber, strlen($number), '0', STR_PAD_LEFT);
    }

}
