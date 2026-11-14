<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;

class YearScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        static $eventColumns = [];
        $table = $model->getTable();
        $connection = $model->getConnectionName() ?: config('database.default');
        $cacheKey = $connection . ':' . $table;

        if (! array_key_exists($cacheKey, $eventColumns)) {
            $eventColumns[$cacheKey] = Schema::connection($connection)->hasColumn($table, 'event_id');
        }

        if ($eventColumns[$cacheKey] && active_event_id()) {
            $builder->where($table . '.event_id', '=', active_event_id());

            return;
        }

        $activeYear = active_year();
        $builder->where($table . '.year', '=', $activeYear);
    }
}
