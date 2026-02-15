<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Models\Support;


use App\Models\ReturnEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * ReturnEventRefLoadable
 *
 * Resolves a related model instance from a ReturnEvent.
 *
 *  This method performs lazy batch-loading:
 *  - On first access for a given (model class + return_id),
 *    it loads all referenced IDs of this type from the return's history.
 *  - Subsequent calls are served from an in-memory cache.
 *
 *  This prevents N+1 queries when serializing return events.
 *
 * @author Alexandra Tikhomirova
 */
trait  ReturnEventRefLoadable
{
    /**
     * Кэш: [modelClass => [returnId => [id => Model]]]
     * @var array<string, array<int, array<int, Model>>>
     */
    protected static array $eventRefCache = [];


    /**
     * Resolves a related model instance from a ReturnEvent.
     *
     * This method performs lazy batch-loading:
     * - On first access for a given (model class + return_id),
     *   it loads all referenced IDs of this type from the return's history.
     * - Subsequent calls are served from an in-memory cache.
     *
     * This prevents N+1 queries when serializing return events.
     *
     * @param ReturnEvent $event
     * @return Model|null
     */
    public static function resolveFromEvent(ReturnEvent $event): ?Model
    {
        if (!$event->ref_id) {
            return null;
        }

        $cls = static::class;
        $returnId = (int) $event->return_id;
        $id = (int) $event->ref_id;

        // Если для этого returnId ещё не грузили — грузим пачкой по id из истории
        if (!isset(self::$eventRefCache[$cls][$returnId])) {
            $ids = ReturnEvent::query()
                ->where('return_id', $returnId)
                ->where('ref_type', $event->ref_type) // ключ типа, не FQCN
                ->whereNotNull('ref_id')
                ->pluck('ref_id')
                ->map(fn($v) => (int)$v)
                ->unique()
                ->values()
                ->all();

            self::$eventRefCache[$cls][$returnId] = $ids
                ? static::query()->whereIn('id', $ids)->get()->keyBy('id')->all()
                : [];
        }

        return self::$eventRefCache[$cls][$returnId][$id] ?? null;
    }

    /**
     * Clears the internal history cache for this model class.
     *
     * If $returnId is provided, only the cache for that specific return
     * will be cleared. Otherwise, the entire cache for this model class
     * will be removed.
     *
     * Useful in long-running processes (e.g. queue workers)
     * to avoid memory growth.
     *
     * @param int|null $returnId
     * @return void
     */
    public static function clearHistoryCache(?int $returnId = null): void
    {
        $cls = static::class;
        if ($returnId === null) {
            unset(self::$historyRefCache[$cls]);
            return;
        }
        unset(self::$historyRefCache[$cls][$returnId]);
    }
}
