<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use App\Models\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * Class ReturnItem
 *
 * @author Alexandra Tikhomirova
 *
 * Represents a single line item belonging to a return.
 *
 * @property int $id
 *     Primary identifier of the return item.
 *
 * @property int $organization_id
 *     Identifier of the organization this item belongs to.
 *
 * @property int $return_id
 *     Identifier of the return this item is associated with.
 *
 * @property int $line_no
 *     Line number within the return (1..N).
 *
 * @property string|null $sku
 *     Optional SKU of the returned product.
 *
 * @property string $item_name
 *     Human‑readable name of the returned item.
 *
 * @property int $quantity
 *     Quantity of items returned.
 *
 * @property int|null $unit_price_cents
 *     Unit price in cents, if available.
 *
 * @property string $currency
 *     ISO‑4217 currency code (default: EUR).
 *
 * @property-read Organization $organization
 * @property-read ReturnModel $return
 */
class ReturnItem extends Model
{

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'return_id',
        'line_no',
        'sku',
        'item_name',
        'quantity',
        'unit_price_cents',
        'currency',
    ];

    /**
     * fill some data before saving
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            /** @var User $user */
            if ($user = auth()->user()) {
                $model->organization_id = $user->current_organization_id;
            }
        });
        static::addGlobalScope(new OrganizationScope);
    }
    /**
     * Get the organization that owns this return item.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the return this item belongs to.
     */
    public function return(): BelongsTo
    {
        return $this->belongsTo(ReturnModel::class);
    }
}
