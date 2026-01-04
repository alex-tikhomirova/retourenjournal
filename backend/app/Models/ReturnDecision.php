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
 * ReturnDecision
 *
 * @author Alexandra Tikhomirova
 *
 *  Represents a predefined decision that can be applied to a return.
 *
 * @property int $id
 *      Primary identifier of the return decision.
 *
 * @property string $code
 *      Unique stable code used for internal logic and API.
 *
 * @property string $name
 *      Human‑readable label of the decision.
 *
 * @property int $sort_order
 *      Sorting priority for UI ordering.
 */
class ReturnDecision extends Model
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
        'sort_order',
    ];
}
