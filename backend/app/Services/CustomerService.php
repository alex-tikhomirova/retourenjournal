<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Services;

use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * CustomerService
 *
 * @author Alexandra Tikhomirova
 */
class CustomerService
{
    /**
     * Find customers by exact email or by phone suffix after removing local/country prefix.
     *
     * @param array{email?: string|null, phone?: string|null} $customer
     * @return Collection<int, Customer>
     */
    public function search(array $customer): Collection
    {
        $email = trim((string) ($customer['email'] ?? ''));
        $phone = $this->normalizePhone((string) ($customer['phone'] ?? ''));

        if ($email === '' && $phone === '') {
            return new Collection();
        }

        return Customer::query()
            ->where(function ($query) use ($email, $phone) {
                if ($email !== '') {
                    $query->where('email', $email);
                }

                if ($phone !== '') {
                    if ($email !== '') {
                        $query->orWhere('phone', 'ilike', '%' . $phone);
                        return;
                    }

                    $query->where('phone', 'ilike', '%' . $phone);
                }
            })
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    /**
     * Create or update the customer submitted with a new return.
     *
     * @param array<string, mixed> $payload
     * @return Customer
     * @throws Exception
     */
    public function upsertFromReturn(array $payload): Customer
    {
        if ($customerId = $payload['id'] ?? null) {
            if (!$customer = Customer::query()->whereKey($customerId)->first()) {
                throw new Exception('Der Kunde wurde nicht gefunden');
            }
        } else {
            $customer = new Customer();
        }

        $this->saveCustomer($customer, $payload);

        return $customer;
    }

    /**
     * Update an existing customer.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Customer
     * @throws Exception
     */
    public function update(int $id, array $data): Customer
    {
        /** @var Customer $customer */
        $customer = Customer::query()->findOrFail($id);

        $this->saveCustomer($customer, $data);

        return $customer;
    }

    /**
     * Remove a local leading zero or a two-digit country prefix from the submitted phone.
     *
     * @param string $phone
     * @return string
     */
    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);

        if (preg_match('/^\+\d{2}/', $phone) === 1) {
            return substr($phone, 3);
        }

        if (str_starts_with($phone, '0')) {
            return substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Save the four customer fields accepted by customer forms.
     *
     * @param Customer $customer
     * @param array{name: string, email?: string|null, phone?: string|null, address_text?: string|null} $payload
     * @return void
     * @throws Exception
     */
    private function saveCustomer(Customer $customer, array $payload): void
    {
        $customer->name = $payload['name'];
        $customer->email = $payload['email'] ?? null;
        $customer->phone = $payload['phone'] ?? null;
        $customer->address_text = $payload['address_text'] ?? null;

        if (!$customer->save()) {
            throw new Exception('Der Kunde konnte nicht gespeichert werden.');
        }
    }
}
