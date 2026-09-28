<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use LogicException;

/**
 * An immutable record of a document acceptance or acknowledgement.
 *
 * @property int $id
 * @property int|null $organization_id
 * @property int|null $user_id
 * @property int|null $return_id
 * @property string $actor_type
 * @property string $document_key
 * @property string $document_version
 * @property string|null $document_hash
 * @property string $action
 * @property string $context
 * @property Carbon $accepted_at
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon $created_at
 * @property-read Organization|null $organization
 * @property-read User|null $user
 * @property-read ReturnModel|null $return
 */
class LegalAcceptance extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'organization_id',
        'user_id',
        'return_id',
        'actor_type',
        'document_key',
        'document_version',
        'document_hash',
        'action',
        'context',
        'accepted_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::updating(function (): never {
            throw new LogicException('Legal acceptances cannot be updated.');
        });

        static::deleting(function (): never {
            throw new LogicException('Legal acceptances cannot be deleted directly.');
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function return(): BelongsTo
    {
        return $this->belongsTo(ReturnModel::class);
    }
}
