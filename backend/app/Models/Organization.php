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
 * Class Organization
 *
 * @author Alexandra Tikhomirova
 *
 * Represents a company or entity registered in the system.
 *
 * @property int $id
 *     Primary identifier of the organization.
 *
 * @property string $name
 *     Name of the organization.
 *
 * @property Carbon|null $created_at
 *     Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *     Timestamp when the record was last updated.
 *
 */
class Organization extends Model
{
    protected $fillable = [
        'name',
    ];
}
