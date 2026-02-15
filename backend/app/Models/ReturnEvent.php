<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models;

use App\Models\Support\ReturnEventRefLoadable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ReflectionClass;

/**
 * Class ReturnEvent
 *
 * @author Alexandra Tikhomirova
 *
 * Represents an audit/event entry for a return.
 *
 * @property int $id
 *     Primary identifier of the event.
 *
 * @property int $organization_id
 *     Identifier of the organization this event belongs to.
 *
 * @property int $return_id
 *     Identifier of the return this event is associated with.
 *
 * @property int|null $created_by_user_id
 *     User who triggered the event.
 *
 * @property int $action
 *     Event action type (1 = created, 2 = updated).
 *
 * @property string|null $field
 *     Name of the field that changed (e.g. status_id, decision_id).
 *
 * @property string|null $ref_type
 *     Reference type (return_status, return_decision, return_shipment, return_refund).
 *
 * @property int|null $ref_id
 *     Referenced entity ID.
 *
 * @property string|null $value
 *     Text value
 *
 * @property Carbon $created_at
 *     Timestamp when the event occurred.
 *
 * @property-read Organization $organization
 * @property-read ReturnModel $return
 * @property-read User|null $createdBy
 */
class ReturnEvent extends Model
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
        'organization_id',
        'return_id',
        'created_by_user_id',
        'action',
        'field',
        'ref_type',
        'ref_id',
        'value',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    protected $appends = ['event_title', 'event_ref'];

    /**
     * fill some data before saving
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
            }
        });

    }

    public function getEventTitleAttribute(): string
    {
        [$domain, $field] = explode('.', $this->field, 2) + [null, null];
        $events = static::eventFields($domain);
        return $events[$field]['title']??$field;

    }

    /**
     * Resolves and returns the referenced domain entity for this event.
     *
     * The resolution process:
     * - Maps ref_type to a configured model class.
     * - If the model supports history-based resolution,
     *   it delegates to the model's resolveFromEvent() method.
     * - Otherwise, falls back to a simple find() lookup.
     *
     * @return Model|null
     */
    public function getEventRefAttribute(): ?Model
    {
        if (!$this->ref_type || !$this->ref_id) {
            return null;
        }

        $models = [
            'status'   => ReturnStatus::class,
            'shipment' => ReturnShipment::class,
            'shipmentstatus' => ShipmentStatus::class,
            'decision' => ReturnDecision::class,
            'refund'   => ReturnRefund::class,
            'note'     => ReturnNote::class,
        ];

        /** @var ReturnEventRefLoadable|Model $modelClass */
        $modelClass = $models[$this->ref_type] ?? null;

        if (!$modelClass || !is_subclass_of($modelClass, Model::class)) {
            return null;
        }

        // Если модель поддерживает наш трейт — батч-лоадим по необходимости
        if (method_exists($modelClass, 'resolveFromEvent')) {
            return $modelClass::resolveFromEvent($this);
        }

        // fallback (если вдруг без трейта)
        return $modelClass::query()->where((int)$this->ref_id)->first();
    }
    public static function eventFields(?string $domain = null): ?array
    {
        // 'shipping' => ["title" => "Sendung hinzugefügt"],


        $fields = [
            'return' => [
                'status_id' => ["title" => "Status geändert", 'ref_type' => 'status',],
                'decision_id' => ["title" => "Entscheidung gesetzt", 'ref_type' => 'decision'],
                'order_reference' => ["title" => "Bestellnummer geändert"],
                'reason' => ["title" => "Grund geändert"],
            ],
        ];

        if ($domain !== null) {
            return $fields[$domain] ?? null;
        }
        return $fields;
    }

    /**
     * Get the organization that owns this event.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the return this event belongs to.
     */
    public function return(): BelongsTo
    {
        return $this->belongsTo(ReturnModel::class);
    }

    /**
     * Get the user who created the event.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
