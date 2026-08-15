<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use App\Models\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class ReturnRefund
 *
 * Represents a refund issued for a return.
 *
 * @author Alexandra Tikhomirova
 *
 * @property int $id
 *     Primary identifier of the refund.
 *
 * @property int $organization_id
 *     Identifier of the organization this refund belongs to.
 *
 * @property int $refund_number
 *     Sequential refund number unique within the organization.
 *
 * @property int $return_id
 *     Identifier of the return this refund is associated with.
 *
 * @property int $status_id
 *     Identifier of the refund status.
 *
 * @property int $amount_cents
 *     Refund amount in cents.
 *
 * @property string $currency
 *     ISO‑4217 currency code (default: EUR).
 *
 * @property Carbon|null $processed_at
 *     Timestamp when the refund was processed.
 *
 * @property string|null $reference
 *     External reference or transaction identifier.
 *
 * @property int|null $created_by_user_id
 *     User who created the refund record.
 *
 * @property int|null $updated_by_user_id
 *     User who last updated the refund record.
 *
 * @property Carbon|null $created_at
 *     Timestamp when the record was created.
 *
 * @property Carbon|null $updated_at
 *     Timestamp when the record was last updated.
 *
 * @property-read Organization $organization
 * @property-read ReturnModel $return
 * @property-read RefundStatus $status
 * @property-read User|null $createdBy
 * @property-read User|null $updatedBy
 */
class ReturnRefund extends Model
{
    protected $fillable = [
        'organization_id',
        'return_id',
        'status_id',
        'amount_cents',
        'currency',
        'processed_at',
        'reference',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * add global scope
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            /** @var User $user */
            if ($user = auth()->user()) {
                $model->organization_id = $user->current_organization_id;
                $model->created_by_user_id = $user->id;
                $model->updated_by_user_id = $user->id;
            }
        });

        static::updating(function (self $model) {
            if ($userId = auth()->id()) {
                $model->updated_by_user_id = $userId;
            }
        });

        static::saved(function (self $model) {
            $eventFields = ReturnEvent::eventFields('refund');

            $dirtyAttributes = $model->getDirty();
            foreach ($eventFields as $field => $eventField) {
                if (isset($dirtyAttributes[$field])) {
                    $model->return->events()->save(
                        new ReturnEvent([
                            'action' => (int) $model->wasRecentlyCreated,
                            'return_id' => $model->return->id,
                            'field' => "refund.$field",
                            'ref_type' => $eventField['ref_type']??null,
                            'ref_id' => isset($eventField['ref_type'])?$model->getAttributeValue($field):null,
                            'value' => $model->getAttributeValue($field),
                        ])
                    );
                }
            }
        });

        static::addGlobalScope(new OrganizationScope);
    }

    /**
     * Reserve the next refund number for an organization.
     *
     * Must be called inside the transaction that creates the refund. The
     * transaction-level advisory lock serializes concurrent number generation.
     */
    public static function nextNumberForOrganization(int $organizationId): int
    {
        DB::statement(
            'SELECT pg_advisory_xact_lock(hashtextextended(?, 0))',
            ["return_refunds:$organizationId"]
        );

        return ((int) static::query()
            ->withoutGlobalScope(OrganizationScope::class)
            ->where('organization_id', $organizationId)
            ->max('refund_number')) + 1;
    }

    /**
     * Get the organization that owns this refund.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the return this refund belongs to.
     */
    public function return(): BelongsTo
    {
        return $this->belongsTo(ReturnModel::class);
    }

    /**
     * Get the status of the refund.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(RefundStatus::class);
    }

    /**
     * Get the user who created the refund.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who last updated the refund.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
