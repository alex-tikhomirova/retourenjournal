<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Return\ReturnDecisionRequest;
use App\Http\Requests\Return\ReturnListRequest;
use App\Http\Requests\Return\ReturnStoreRequest;
use App\Http\Requests\Return\ReturnUpdateRequest;
use App\Http\Resources\ReturnResource;
use App\Models\ReturnModel;
use App\Services\ReturnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;


/**
 * ReturnController
 *
 * @author Alexandra Tikhomirova
 */
class ReturnController extends Controller
{

    /**
     * Relations required to render a full return detail response.
     *
     * @return array<int|string, mixed>
     */
    private function itemRelations(): array
    {
        return [
            'status:id,code,color,name,description,kind,is_active',
            'customer:id,organization_id,name,email,phone,address_text',
            'items:id,return_id,line_no,sku,serial,item_name,quantity,unit_price_cents,currency',
            'shipments.status:id,code,name',
            'shipments.createdBy' => fn ($q) => $q->select('id', 'name'),
            'refunds.status:id,code,name',
            'refunds.createdBy' => fn ($q) => $q->select('id', 'name'),
            'notes',
            'decision',
            'events.createdBy' => fn ($q) => $q->select('id', 'name'),
        ];
    }

    /**
     * Return a paginated list of returns with filters, search and sorting.
     *
     * @param ReturnListRequest $request
     * @return AnonymousResourceCollection
     */
    public function list(ReturnListRequest $request): AnonymousResourceCollection
    {

        $filter = $request->filter();
        $query = ReturnModel::query()
            ->with([
                'customer:id,organization_id,name,email,phone',
                'status',
            ])
            ->withCount(['items', 'shipments', 'refunds']);

        // filters
        if (!empty($filter['status_id'])) {
            $query->whereIn('status_id', $filter['status_id']);
        }

        if (!empty($filter['decision_id'])) {
            $query->where('decision_id', $filter['decision_id']);
        }

        if (!empty($filter['customer_id'])) {
            $query->where('customer_id', (int) $filter['customer_id']);
        }

        if (!empty($filter['return_number'])) {
            $query->where('return_number', 'ilike', '%' . $filter['return_number'] . '%');
        }

        if (!empty($filter['order_reference'])) {
            $query->where('order_reference', 'ilike', '%' . $filter['order_reference'] . '%');
        }

        if (!empty($filter['created_at'])) {
            $query->whereDate('created_at', '>=', $filter['created_at'][0]);
            if (isset($filter['created_at'][1])) {
                $query->whereDate('created_at', '<=', $filter['created_at'][1]);
            }
        }
        if (!empty($filter['updated_at'])) {
            $query->whereDate('updated_at', '>=', $filter['updated_at'][0]);
            if (isset($filter['updated_at'][1])) {
                $query->whereDate('updated_at', '<=', $filter['updated_at'][1]);
            }
        }
        // search
        $q = trim((string) ($filter['q']??''));
        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('return_number',   'ilike', "%$q%")
                    ->orWhere('order_reference', 'ilike', "%$q%")
                    ->orWhereHas('customer', fn($c) => $c->where('name', 'ilike', "%{$q}%"))
                    ->orWhereHas('items', fn($i) => $i->where(function ($x) use ($q) {
                        $x->where('sku',       'ilike', "%$q%")
                            ->orWhere('item_name','ilike', "%$q%");
                    }));
            });
        }

        $request->applySort($query);

        $result = $query->paginate(
            perPage: $request->perPage(),
            page:    $request->currentPage(),
        )->withQueryString();

        return ReturnResource::collection($result);
    }

    /**
     * Return one return with all detail relations loaded.
     *
     * @param Request $request
     * @param int $id
     * @return ReturnResource
     */
    public function item(Request $request, int $id): ReturnResource
    {
        $return = ReturnModel::query()
            ->where('id', $id)
            ->with($this->itemRelations())
            ->firstOrFail();

        return (new ReturnResource($return));
    }

    /**
     * Suggest the next available return number.
     *
     * @return JsonResponse
     */
    public function nextNumber(): JsonResponse
    {
        $service = new ReturnService();

        return response()->json([
            'data' => [
                'return_number' => $service->nextReturnNumber(),
            ],
        ]);
    }


    /**
     * Create a return with customer data and return items.
     *
     * @param ReturnStoreRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ReturnStoreRequest $request): JsonResponse
    {
        $service = new ReturnService();
        $return = $service->create($request->validated())->refresh()->load($this->itemRelations());
        return (new ReturnResource($return))->response()->setStatusCode(201);
    }

    /**
     * Update editable return fields.
     *
     * @param ReturnUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ReturnUpdateRequest $request, int $id): JsonResponse
    {
        $service = new ReturnService();
        $return = $service->update($id, $request->validated())->refresh()->load($this->itemRelations());
        return (new ReturnResource($return))->response()->setStatusCode(201);
    }

    public function decision(ReturnDecisionRequest $request, int $id)
    {
        $service = new ReturnService();
        $return = $service->updateDecision($id, $request->decision_id);
        return (new ReturnResource($return))->response()->setStatusCode(201);
    }


}
