<?php
/**
 * Retourenmanagement System
 *
 * @copyright 2026 Alexandra Tikhomirova
 * @license Proprietary
 */

namespace App\Http\Requests\Concerns;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * List queries trait for universal params
 *
 * @author Alexandra Tikhomirova
 *
 * @mixin FormRequest
 */
trait HasListQueryParams
{

    public function perPage(int $default = 25, int $max = 200): int
    {
        $pp = (int) $this->input('per_page', $default);
        if ($pp < 1) {
            $pp = $default;
        }
        if ($pp > $max) {
            $pp = $max;
        }
        return $pp;
    }

    /**
     * sort="-created_at,return_number"
     * sortMap: ['created_at' => 'created_at', 'return_number' => 'return_number']
     * -> [['created_at','desc'], ['return_number','asc']]
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

    public function listBaseRules(): array
    {
        return [
            'page'     => ['sometimes','integer','min:1'],
            'per_page' => ['sometimes','integer','min:1','max:200'],
            'sort'     => ['sometimes','string','max:200'],
            'q'        => ['sometimes','string','max:200'],
        ];
    }
}
