<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use App\Models\Scopes\OrganizationScope;
use App\Models\Support\ReturnEventRefLoadable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

/**
 * Class ReturnStatus
 *
 * @author Alexandra Tikhomirova
 *
 * Represents a return status, either global (organization_id = null)
 * or organization‑specific.
 *
 * @property int $id
 *     Primary identifier of the return status.
 *
 * @property int|null $organization_id
 *     Identifier of the organization this status belongs to,
 *     or null for global default statuses.
 *
 * @property string $code
 *     Stable internal code used for seeds, API, and logic.
 *
 * @property string $name
 *     Human‑readable label shown in the UI.
 *
 * @property string $description
 *      Short description
 *
 * @property string $color
 *      Icon color
 *
 * @property int $sort_order
 *     Sorting priority within the organization.
 *
 * @property int $kind
 *     Status type: 1 = initial, 2 = normal, 9 = terminal.
 *
 * @property bool $is_active
 *     Indicates whether the status is active.
 *
 * @property Carbon|null $created_at
 *     Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *     Timestamp when the record was last updated.
 *
 * @property-read Organization|null $organization
 *     The organization this status belongs to, or null if global.
 */
class ReturnStatus extends Model
{

    use ReturnEventRefLoadable;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'color',
        'kind',
        'description',
        'sort_order',
        'is_active',
    ];

    /**
     * Cached statuses keyed by id.
     *
     * @var Collection<int, self>|null
     */
    private static ?Collection $states = null;

    public function tenantMode(): string
    {
        return 'or_global';
    }
    /**
     * add global scope
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new OrganizationScope);
    }

    /**
     * Get the organization that owns this return status.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return Collection<int, self>
     */
    public static function allStates(): Collection
    {
        if (null === self::$states) {
            static::$states = static::all()->keyBy('id');
        }
        return self::$states;
    }

    /**
     * Returns state by code from static storage
     *
     * @param string $code
     * @return static|null
     */
    public static function byCode(string $code): ?ReturnStatus
    {
        /** @var self|null $state */
        $state = static::allStates()->firstWhere('code', $code);

        return $state;
    }

    /**
     * Returns state by id from static storage
     *
     * @param int $id
     * @return static|null
     */
    public static function byId(int $id): ?self
    {
        /** @var self|null $state */
        $state = static::allStates()->get($id);

        return $state;
    }

    /**
     * @return ReturnStatus|null
     */
    public static function initialReturnStatus(): ?self
    {
        return static::byCode('created');

    }
}
