<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Services;


use App\Models\Customer;
use App\Models\Organization;
use App\Models\ReturnItem;
use App\Models\ReturnModel;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * ReturnService
 *
 * @author Alexandra Tikhomirova
 */
class ReturnService
{
    public function create(array $payload): ReturnModel
    {
        return DB::transaction(function () use ($payload) {

            $orgId = Organization::currentOrgId();

            // check duplicate
            $exists = ReturnModel::query()
                ->where('organization_id', $orgId)
                ->where('return_number', $payload['return_number'])
                ->exists();
            if ($exists) {
                abort(409, 'Die Retourennummer existiert bereits');
            }

            $customer = $this->upsertCustomer($payload['customer']);

            $return = new ReturnModel();

            $return->return_number = $payload['return_number'];
            $return->customer_id = $customer->id;
            $return->order_reference = $payload['order_reference'] ?? null;

            $return->save();

            $this->syncItems($return, $payload['items']);

            return $return;
        });
    }

    private function syncItems(ReturnModel $return, array $items): void
    {
        foreach ($items as $it) {
            $item = new ReturnItem();
            $item->line_no = (int) $it['line_no'];
            $item->sku = $it['sku'] ?? null;
            $item->item_name = $it['item_name'];
            $item->quantity = (int) $it['quantity'];
            $item->unit_price_cents = isset($it['cost'])
                ? (int) round($it['cost'] * 100)
                : null;
            $item->currency = $it['currency'] ?? 'EUR';

            $return->items()->save($item);
        }
    }
    /**
     * @param array $data
     * @return Customer
     * @throws Exception
     */
    private function upsertCustomer(array $data): Customer
    {
        $customerId = $data['id'] ?? null;
        $orgId = Organization::currentOrgId();
        $customer = Customer::query()
            ->where('organization_id', $orgId)
            ->whereKey($customerId)
            ->first();
        if (!$customer){
            $customer = new Customer();
        }

        $customer->fill(array_filter($data, fn($v) => $v !== null && $v !== ''));

        if (!$customer->save()){
            throw new Exception("Der Kunde konnte nicht gespeichert werden.");
        }
        return $customer;

    }
}
