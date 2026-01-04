<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class RefundStatus
 *
 * @author Alexandra Tikhomirova
 *
 * Represents a refund status used to classify refund processing states.
 *
 * @property int $id
 *     Primary identifier of the refund status.
 *
 * @property string $code
 *     Unique stable code used for internal logic and API.
 *
 * @property string $name
 *     Human‑readable label of the refund status.
 *
 * @property bool $is_counted
 *     Indicates whether this status should be included in refund statistics.
 *
 * @property int $sort_order
 *     Sorting priority for UI ordering.
 */
class RefundStatus extends Model
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
        'code',
        'name',
        'is_counted',
        'sort_order',
    ];
}
