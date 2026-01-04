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
 * Class Customer
 *
 * @author Alexandra Tikhomirova
 *
 * Represents a customer belonging to an organization.
 *
 * @property int $id
 *     Primary identifier of the customer.
 *
 * @property int $organization_id
 *     Identifier of the organization this customer belongs to.
 *
 * @property string|null $name
 *     Customer's name.
 *
 * @property string|null $email
 *     Customer's email address.
 *
 * @property string|null $phone
 *     Customer's phone number.
 *
 * @property string|null $address_text
 *     Free‑form address text.
 *
 * @property Carbon|null $created_at
 *     Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *     Timestamp when the record was last updated.
 *
 * @property-read Organization|null $organization
 *     The organization this customer belongs to.
 */
class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'address_text',
    ];

    /**
     * Get the organization that owns the customer.
     *
     * @return BelongsTo
     */
    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
