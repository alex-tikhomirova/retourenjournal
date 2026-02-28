<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrganizationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $orgId = auth()->user()?->current_organization_id;
        if (!$orgId) {
            return;
        }

        $table = $model->getTable();
        $mode = method_exists($model, 'tenantMode') ? $model->tenantMode() : 'strict';

        $builder->where(function (Builder $q) use ($table, $orgId, $mode) {
            if ($mode === 'or_global') {
                $q->whereNull("$table.organization_id")
                    ->orWhere("$table.organization_id", (int) $orgId);
            } else {
                $q->where("$table.organization_id", (int) $orgId);
            }
        });
    }
}
