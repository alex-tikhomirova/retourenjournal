<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class ShipmentStatus
 *
 * @author Alexandra Tikhomirova
 *
 * Represents a shipment status used to track delivery progress.
 *
 * @property int $id
 *     Primary identifier of the shipment status.
 *
 * @property string $code
 *     Unique stable code used for seeds and internal logic.
 *
 * @property string $name
 *     Human‑readable label shown in the UI.
 *
 * @property int $sort_order
 *     Sorting priority for UI ordering.
 *
 * @property bool $is_terminal
 *     Indicates whether this status represents a final state.
 *
 * @property Carbon|null $created_at
 *     Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *     Timestamp when the record was last updated.
 */
class ShipmentStatus extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'sort_order',
        'is_terminal',
    ];

}
