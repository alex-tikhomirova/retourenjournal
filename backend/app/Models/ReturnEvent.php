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
 * Class ReturnEvent
 *
 * @author Alexandra Tikhomirova
 *
 * Represents an audit/event entry for a return.
 *
 * @property int $id
 *     Primary identifier of the event.
 *
 * @property int $organization_id
 *     Identifier of the organization this event belongs to.
 *
 * @property int $return_id
 *     Identifier of the return this event is associated with.
 *
 * @property int|null $created_by_user_id
 *     User who triggered the event.
 *
 * @property int $action
 *     Event action type (1 = created, 2 = updated).
 *
 * @property string|null $field
 *     Name of the field that changed (e.g. status_id, decision_id).
 *
 * @property string|null $ref_type
 *     Reference type (return_status, return_decision, return_shipment, return_refund).
 *
 * @property int|null $old_ref_id
 *     Previous referenced entity ID.
 *
 * @property int|null $new_ref_id
 *     New referenced entity ID.
 *
 * @property array|null $meta
 *     Additional structured metadata.
 *
 * @property Carbon $created_at
 *     Timestamp when the event occurred.
 *
 * @property-read Organization $organization
 * @property-read ReturnModel $return
 * @property-read User|null $createdBy
 */
class ReturnEvent extends Model
{
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
        'organization_id',
        'return_id',
        'created_by_user_id',
        'action',
        'field',
        'ref_type',
        'old_ref_id',
        'new_ref_id',
        'meta',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the organization that owns this event.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the return this event belongs to.
     */
    public function return(): BelongsTo
    {
        return $this->belongsTo(ReturnModel::class);
    }

    /**
     * Get the user who created the event.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
