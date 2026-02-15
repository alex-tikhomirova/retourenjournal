<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public static function currentOrgId(): int
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user || !$user->current_organization_id){
            throw new NotFoundHttpException();
        }
        return $user->current_organization_id;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'organization_user',
            foreignPivotKey: 'organization_id',
            relatedPivotKey: 'user_id'
        )->withTimestamps();
    }
}
