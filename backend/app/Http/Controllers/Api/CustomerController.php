<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSearchRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

/**
 * CustomerController
 *
 * @author Alexandra Tikhomirova
 */
class CustomerController extends Controller
{
    public function search(CustomerSearchRequest $request): AnonymousResourceCollection
    {
        $service = new CustomerService();

        return CustomerResource::collection($service->search($request->customer()));
    }

    /**
     * Update a customer in the current organization.
     *
     * @param CustomerUpdateRequest $request
     * @param int $id
     * @return CustomerResource
     * @throws Throwable
     */
    public function update(CustomerUpdateRequest $request, int $id): CustomerResource
    {
        $service = new CustomerService();

        return new CustomerResource($service->update($id, $request->customer()));
    }
}
