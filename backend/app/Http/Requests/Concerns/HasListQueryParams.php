<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Requests\Concerns;

use App\Models\ReturnModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * List queries trait for universal params
 *
 * Expects payload shape:
 *   { filter: {...}, sort: "field|-field", page: { current_page: int|null, per_page: int } }
 *
 * @author Alexandra Tikhomirova
 *
 * @mixin FormRequest
 */
trait HasListQueryParams
{

    public function perPage(int $default = 20, int $max = 200): int
    {
        $pp = (int) $this->input('pagination.per_page', $default);
        if ($pp < 1) $pp = $default;
        if ($pp > $max) $pp = $max;
        return $pp;
    }

    public function currentPage(): ?int
    {
        $p = $this->input('pagination.current_page');
        if ($p === null) return null;
        $p = (int) $p;
        return $p >= 1 ? $p : null;
    }

    /**
     * sort="-created_at" or "return_number"
     * sortMap: ['created_at' => 'created_at', ...]
     * -> [['created_at','desc']]
     *
     * @throws ValidationException
     */
    public function parsedSort(array $sortMap, array $default = [['created_at','desc']], bool $strict = false): array
    {
        $raw = trim((string) $this->input('sort', ''));
        if ($raw === '') {
            return $default;
        }

        $out = [];
        $unknown = [];

        foreach (explode(',', $raw) as $part) {
            $part = trim($part);
            if ($part === '') continue;

            $dir = str_starts_with($part, '-') ? 'desc' : 'asc';
            $key = ltrim($part, '-');

            if (!array_key_exists($key, $sortMap)) {
                $unknown[] = $key;
                continue;
            }

            $out[] = [$sortMap[$key], $dir];
        }

        if ($strict && $unknown) {
            throw ValidationException::withMessages([
                'sort' => ['Unbekannte Sortierfelder: '.implode(', ', $unknown)],
            ]);
        }

        return $out ?: $default;
    }

    /**
     * Apply whitelisted sort fields, including belongsTo relation fields like `customer.name`.
     *
     * @throws ValidationException
     */
    public function applySort(Builder $query): void
    {
        foreach ($this->parsedSort($this->sortMap()) as [$column, $direction]) {
            if (!str_contains($column, '.')) {
                $query->orderBy($query->getModel()->qualifyColumn($column), $direction);
                continue;
            }

            [$relationName, $relationColumn] = explode('.', $column, 2);
            $relation = $query->getModel()->{$relationName}();
            $query
                ->select($query->getModel()->qualifyColumn('*'))
                ->leftJoin(
                    $relation->getRelated()->getTable(),
                    $relation->getQualifiedOwnerKeyName(),
                    '=',
                    $relation->getQualifiedForeignKeyName()
                )
                ->orderBy($relation->getRelated()->qualifyColumn($relationColumn), $direction);
        }
    }

    public function listBaseRules(): array
    {
        return [
            'filter'            => ['sometimes', 'array'],
            'sort'              => ['sometimes', 'string', 'max:200'],
            'pagination'              => ['sometimes', 'array'],
            'pagination.current_page' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'pagination.per_page'     => ['sometimes', 'integer', 'min:1', 'max:200'],
        ];
    }
}
