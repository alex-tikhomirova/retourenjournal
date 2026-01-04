<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * ReturnCase
 *
 * Central domain model representing a customer return case.
 * Acts as an aggregate root for return items, status transitions,
 * and refund or replacement decisions.
 *
 * @author Alexandra Tikhomirova
 *
 * @property int $id
 *     Primary identifier of the return.
 *
 * @property int $organization_id
 *     Identifier of the organization this return belongs to.
 *
 * @property string $return_number
 *     Unique return number within the organization.
 *
 * @property int|null $customer_id
 *     Identifier of the customer associated with the return.
 *
 * @property int $status_id
 *     Identifier of the return status.
 *
 * @property int|null $decision_id
 *     Identifier of the applied return decision.
 *
 * @property string|null $order_reference
 *     Reference to the related order.
 *
 * @property string|null $reason
 *     Customer‑provided reason for the return.
 *
 * @property string|null $internal_note
 *     Internal note visible only to staff.
 *
 * @property int|null $created_by_user_id
 *     User who created the return.
 *
 * @property int|null $updated_by_user_id
 *     User who last updated the return.
 *
 * @property Carbon|null $created_at
 *     Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *     Timestamp when the record was last updated.
 *
 *
 * @property-read Organization|null $organization
 * @property-read Customer|null $customer
 * @property-read ReturnStatus|null $status
 * @property-read ReturnDecision|null $decision
 * @property-read User|null $createdBy
 * @property-read User|null $updatedBy
 * @property-read Collection<int, ReturnItem> $items
 * @property-read Collection<int, ReturnNote> $notes
 * @property-read Collection<int, ReturnRefund> $refunds
 * @property-read Collection<int, ReturnShipment> $shipments
 * @property-read Collection<int, ReturnEvent> $events
 */
class ReturnModel extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'return_number',
        'customer_id',
        'status_id',
        'decision_id',
        'order_reference',
        'reason',
        'internal_note',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    // ---------------------------------------------------------
    //  BelongsTo relations
    // ---------------------------------------------------------

    /**
     * Get the organization that owns the return.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the customer associated with the return.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the status of the return.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ReturnStatus::class);
    }

    /**
     * Get the decision applied to the return.
     */
    public function decision(): BelongsTo
    {
        return $this->belongsTo(ReturnDecision::class);
    }

    /**
     * Get the user who created the return.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who last updated the return.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }


    // ---------------------------------------------------------
    //  HasMany relations
    // ---------------------------------------------------------

    /**
     * Line items belonging to the return.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    /**
     * Notes attached to the return.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(ReturnNote::class, 'return_id');
    }

    /**
     * Refunds issued for the return.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(ReturnRefund::class, 'return_id');
    }

    /**
     * Shipments associated with the return.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(ReturnShipment::class, 'return_id');
    }

    /**
     * Audit events recorded for the return.
     */
    public function events(): HasMany
    {
        return $this->hasMany(ReturnEvent::class, 'return_id');
    }
}
