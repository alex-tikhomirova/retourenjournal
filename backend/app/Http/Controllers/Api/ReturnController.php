<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Return\ReturnListRequest;
use App\Http\Requests\Return\ReturnStoreRequest;
use App\Http\Requests\Return\ReturnUpdateRequest;
use App\Http\Resources\ReturnResource;
use App\Models\ReturnModel;
use App\Models\User;
use App\Services\ReturnService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


/**
 * ReturnController
 *
 * @author Alexandra Tikhomirova
 */
class ReturnController extends Controller
{

    private function itemRelations(): array
    {
        return [
            'status:id,code,name',
            'customer:id,organization_id,name,email,phone',
            'items:id,return_id,line_no,sku,item_name,quantity,unit_price_cents,currency',
            'shipments.status:id,code,name',
            'shipments.createdBy' => fn ($q) => $q->select('id', 'name'),
            'refunds.status:id,code,name',
            'notes',
            'decision',
            'events.createdBy' => fn ($q) => $q->select('id', 'name'),
        ];
    }

    public function list(ReturnListRequest $request): AnonymousResourceCollection
    {
        $p = $request->validated();

        $query = ReturnModel::query()
            ->with([
                'customer:id,organization_id,name,email,phone',
                'status:id,code,name',
            ])
            ->withCount(['items', 'shipments', 'refunds']);

        // --- filters ---
        if (!empty($p['status_id'])) {
            $query->where('status_id', (int) $p['status_id']);
        }

        if (!empty($p['decision_id'])) {
            $query->where('decision_id', (int) $p['decision_id']);
        }

        if (!empty($p['customer_id'])) {
            $query->where('customer_id', (int) $p['customer_id']);
        }

        if (!empty($p['created_from'])) {
            $query->whereDate('created_at', '>=', $p['created_from']);
        }

        if (!empty($p['created_to'])) {
            $query->whereDate('created_at', '<=', $p['created_to']);
        }

        if (array_key_exists('has_shipments', $p)) {
            $p['has_shipments']
                ? $query->whereHas('shipments')
                : $query->whereDoesntHave('shipments');
        }

        if (array_key_exists('has_refunds', $p)) {
            $p['has_refunds']
                ? $query->whereHas('refunds')
                : $query->whereDoesntHave('refunds');
        }

        if (!empty($p['tracking_number'])) {
            $tn = trim($p['tracking_number']);
            $query->whereHas('shipments', function ($q) use ($tn) {
                $q->where('tracking_number', 'ilike', "%{$tn}%"); // Postgres
            });
        }

        // общий поиск (return_number / order_reference / customer.name / sku / item_name)
        if (!empty($p['q'])) {
            $q = trim($p['q']);

            $query->where(function ($qq) use ($q) {
                $qq->where('return_number', 'ilike', "%{$q}%")
                    ->orWhere('order_reference', 'ilike', "%{$q}%")
                    ->orWhereHas('customer', fn($c) => $c
                        ->where('name', 'ilike', "%{$q}%")
                    )
                    ->orWhereHas('items', fn($i) => $i
                        ->where(function ($x) use ($q) {
                            $x->where('sku', 'ilike', "%{$q}%")
                                ->orWhere('item_name', 'ilike', "%{$q}%");
                        })
                    );
            });
        }

        // --- sorting (whitelist уже провалидирован) ---
        $query->orderBy('created_at', 'desc');

        $result = $query->paginate(10)->withQueryString();

        return ReturnResource::collection($result);
    }

    public function item(Request $request, int $id): ReturnResource
    {
        $return = ReturnModel::query()
            ->where('id', $id)
            ->with($this->itemRelations())
            ->firstOrFail();

        return (new ReturnResource($return));
    }


    public function store(ReturnStoreRequest $request): JsonResponse
    {
        $service = new ReturnService();
        $return = $service->create($request->validated())->refresh()->load($this->itemRelations());
        return (new ReturnResource($return))->response()->setStatusCode(201);
    }

    public function update(ReturnUpdateRequest $request, int $id): JsonResponse
    {
        $service = new ReturnService();
        $return = $service->update($id, $request->validated())->refresh()->load($this->itemRelations());
        return (new ReturnResource($return))->response()->setStatusCode(201);
    }


}
