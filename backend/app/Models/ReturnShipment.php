<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class ReturnShipment
 *
 * @author Alexandra Tikhomirova
 *
 * Represents a shipment associated with a return.
 *
 * @property int $id
 *     Primary identifier of the shipment.
 *
 * @property int $organization_id
 *     Identifier of the organization this shipment belongs to.
 *
 * @property int $return_id
 *     Identifier of the return this shipment is associated with.
 *
 * @property int $direction
 *     Shipment direction (1 = to_merchant, 2 = to_customer).
 *
 * @property int $payer
 *     Shipment payer (1 = customer, 2 = merchant, 3 = shared, 4 = unknown).
 *
 * @property int|null $cost_cents
 *     Shipment cost in cents.
 *
 * @property string $currency
 *     ISO‑4217 currency code (default: EUR).
 *
 * @property int $status_id
 *     Identifier of the shipment status.
 *
 * @property string|null $carrier
 *     Carrier name.
 *
 * @property string|null $tracking_number
 *     Tracking number.
 *
 * @property int|null $created_by_user_id
 *     User who created the shipment record.
 *
 * @property int|null $updated_by_user_id
 *     User who last updated the shipment record.
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Organization $organization
 * @property-read ReturnModel $return
 * @property-read ShipmentStatus $status
 * @property-read User|null $createdBy
 * @property-read User|null $updatedBy
 */
class ReturnShipment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'return_id',
        'direction',
        'payer',
        'cost_cents',
        'currency',
        'status_id',
        'carrier',
        'tracking_number',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    /**
     * Get the organization that owns this shipment.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the return this shipment belongs to.
     */
    public function return(): BelongsTo
    {
        return $this->belongsTo(ReturnModel::class);
    }

    /**
     * Get the shipment status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ShipmentStatus::class);
    }

    /**
     * Get the user who created the shipment.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who last updated the shipment.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
