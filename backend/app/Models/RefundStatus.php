<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use LogicException;


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
 * @property string $description
 *     Short description
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
        'description',
        'is_counted',
        'sort_order',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = [
        'color',
    ];

    /**
     * Get the UI color for this refund status.
     *
     * @return Attribute<string, never>
     */
    protected function color(): Attribute
    {
        return Attribute::get(fn (): string => match ($this->code) {
            'not_required' => '#F3F4F6',
            'pending' => '#FEF3C7',
            'processing' => '#DBEAFE',
            'refunded' => '#D1FAE5',
            'failed' => '#FFE4E6',
            'cancelled' => '#E7E5E4',
            default => '#E7E5E4',
        });
    }

    /**
     * Return the required initial refund status.
     */
    public static function initialRefundStatus(): self
    {
        return static::query()
            ->where('code', 'pending')
            ->first() ?? throw new LogicException(
            'Initial refund status "pending" is not configured.'
        );
    }
}
