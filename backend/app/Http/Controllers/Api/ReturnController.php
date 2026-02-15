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

/*    private Builder $query;*/
    private function buildQuery(string $modelClass, ReturnListRequest $request)
    {
        $user = $request->user();
        $orgId = (int) $user->current_organization_id;
        /** @var Model $model */
        $model = new $modelClass();
        $self = new self();
        $query = $model->newQuery();
        $validData = $request->validated();


    }
    public function list(ReturnListRequest $request): AnonymousResourceCollection
    {
        $user = $request->user();
         $orgId = (int) $user->current_organization_id;



        $p = $request->validated();

        $query = ReturnModel::query()
            ->where('organization_id', $orgId)
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
            $query->whereHas('shipments', function ($q) use ($orgId, $tn) {
                $q->where('organization_id', $orgId)
                    ->where('tracking_number', 'ilike', "%{$tn}%"); // Postgres
            });
        }

        // общий поиск (return_number / order_reference / customer.name / sku / item_name)
        if (!empty($p['q'])) {
            $q = trim($p['q']);

            $query->where(function ($qq) use ($q, $orgId) {
                $qq->where('return_number', 'ilike', "%{$q}%")
                    ->orWhere('order_reference', 'ilike', "%{$q}%")
                    ->orWhereHas('customer', fn($c) => $c
                        ->where('organization_id', $orgId)
                        ->where('name', 'ilike', "%{$q}%")
                    )
                    ->orWhereHas('items', fn($i) => $i
                        ->where('organization_id', $orgId)
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
        /** @var User $user */
        $user = auth()->user();

        $return = ReturnModel::query()
            ->where('organization_id', $user->current_organization_id)
            ->where('id', $id)
            ->with([
                'status:id,code,name',
                'customer:id,organization_id,name,email,phone',
                'items:id,return_id,line_no,sku,item_name,quantity,unit_price_cents,currency',
                'shipments.status:id,code,name',
                'shipments.createdBy' => fn ($q) => $q->select('id', 'name'),
                'refunds.status:id,code,name',
                'notes',
                'events.createdBy' => fn ($q) => $q->select('id', 'name'),
            ])

            ->firstOrFail();

        return (new ReturnResource($return));
    }

    public function store(ReturnStoreRequest $request): JsonResponse
    {
        $service = new ReturnService();
        $return = $service->create($request->validated());
        return (new ReturnResource($return))->response()->setStatusCode(201);
    }

}
