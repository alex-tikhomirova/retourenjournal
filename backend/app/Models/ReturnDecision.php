<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;


use App\Models\Scopes\OrganizationScope;
use App\Models\Support\ReturnEventRefLoadable;
use Illuminate\Database\Eloquent\Model;

/**
 * ReturnDecision
 *
 * @author Alexandra Tikhomirova
 *
 *  Represents a predefined decision that can be applied to a return.
 *
 * @property int $id
 *      Primary identifier of the return decision.
 *
 * @property int|null $organization_id
 * *     Identifier of the organization this status belongs to,
 * *     or null for global default statuses.
 *
 * @property string $code
 *      Unique stable code used for internal logic and API.
 *
 * @property string $name
 *      Human‑readable label of the decision.
 *
 * @property string $description
 *      Short description
 *
 * @property string $outcome
 *      Decision type
 *
 * @property string $requires_inbound_item
 *      Requires inbound item
 *
 * @property string $requires_refund
 *       Requires refund
 *
 * @property string $requires_outbound_shipment
 *       Requires outbound item
 *
 * @property int $sort_order
 *      Sorting priority for UI ordering.
 *
 *
 * @property-read ReturnStatus $nextStatus
 */
class ReturnDecision extends Model
{

    use ReturnEventRefLoadable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
        'outcome',
        'requires_inbound_item',
        'requires_refund',
        'requires_outbound_shipment',
    ];

    public function tenantMode(): string
    {
        return 'or_global';
    }

    /**
     * add global scope
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new OrganizationScope);
    }

    /**
     * Returns next status after completed decision
     *
     * @return ReturnStatus|null
     */
    public function getNextStatusAttribute(): ?ReturnStatus
    {
        if ($this->outcome === 'approve'){
            return ReturnStatus::byCode('approved');
        }
        if ($this->outcome === 'reject'){
            return ReturnStatus::byCode('rejected');
        }
        return null;
    }
}
