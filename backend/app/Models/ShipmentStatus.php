<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use League\CommonMark\Exception\LogicException;

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
 * @property string $description
 *     Short description
 *
 * @property string $color
 *     UI badge color.
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
        'description',
        'sort_order',
        'is_terminal',
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
     * Get the UI color for this shipment status.
     *
     * @return Attribute<string, never>
     */
    protected function color(): Attribute
    {
        return Attribute::get(fn (): string => match ($this->code) {
            'created' => '#E0F2FE',
            'shipped' => '#DBEAFE',
            'in_transit' => '#E0E7FF',
            'delivered' => '#D1FAE5',
            'returned' => '#FFE4E6',
            'cancelled' => '#E7E5E4',
            default => '#E7E5E4',
        });
    }

    /**
     * Return the required initial shipment status.
     */
    public static function initialShipmentStatus(): self
    {
        return static::query()
            ->where('code', 'created')
            ->first()
            ?? throw new LogicException(
                'Initial shipment status "created" is not configured.'
            );
    }
}
