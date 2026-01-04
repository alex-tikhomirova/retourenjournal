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
 * Class ReturnStatus
 *
 * @author Alexandra Tikhomirova
 *
 * Represents a return status, either global (organization_id = null)
 * or organization‑specific.
 *
 * @property int $id
 *     Primary identifier of the return status.
 *
 * @property int|null $organization_id
 *     Identifier of the organization this status belongs to,
 *     or null for global default statuses.
 *
 * @property string $code
 *     Stable internal code used for seeds, API, and logic.
 *
 * @property string $name
 *     Human‑readable label shown in the UI.
 *
 * @property int $sort_order
 *     Sorting priority within the organization.
 *
 * @property int $kind
 *     Status type: 1 = initial, 2 = normal, 9 = terminal.
 *
 * @property bool $is_active
 *     Indicates whether the status is active.
 *
 * @property Carbon|null $created_at
 *     Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *     Timestamp when the record was last updated.
 *
 * @property-read Organization|null $organization
 *     The organization this status belongs to, or null if global.
 */
class ReturnStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'kind',
        'sort_order',
        'is_active',
    ];

    /**
     * Get the organization that owns this return status.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
