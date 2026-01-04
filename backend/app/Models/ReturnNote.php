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
 * Class ReturnNote
 *
 * @author Alexandra Tikhomirova
 *
 * Represents an internal note attached to a return.
 *
 * @property int $id
 *     Primary identifier of the note.
 *
 * @property int $organization_id
 *     Identifier of the organization this note belongs to.
 *
 * @property int $return_id
 *     Identifier of the return this note is associated with.
 *
 * @property int|null $created_by_user_id
 *     User who created the note.
 *
 * @property string $note
 *     The note text.
 *
 * @property Carbon|null $created_at
 *     Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *     Timestamp when the record was last updated.
 *
 * @property-read Organization $organization
 * @property-read ReturnModel $return
 * @property-read User|null $createdBy
 */
class ReturnNote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'return_id',
        'created_by_user_id',
        'note',
    ];

    /**
     * Get the organization that owns this note.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the return this note belongs to.
     */
    public function return(): BelongsTo
    {
        return $this->belongsTo(ReturnModel::class);
    }

    /**
     * Get the user who created the note.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
