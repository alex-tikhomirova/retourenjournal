<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * OrganizationUser
 *
 * @author Alexandra Tikhomirova
 *
 *  Represents the pivot relation between organizations and users.
 *
 * @property int $organization_id
 *      Identifier of the related organization.
 *
 * @property int $user_id
 *      Identifier of the related user.
 *
 * @property bool $is_owner
 *      Indicates whether the user is an owner of the organization.
 *
 * @property Carbon|null $created_at
 *      Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *      Timestamp when the record was last updated.
 *
 * @property-read Organization $organization
 *      The organization associated with this pivot entry.
 *
 * @property-read User $user
 *      The user associated with this pivot entry.
 */
class OrganizationUser extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_user';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'is_owner',
    ];

    /**
     * Get the organization associated with this pivot entry.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user associated with this pivot entry.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
