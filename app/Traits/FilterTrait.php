<?php

namespace App\Traits;

use InvalidArgumentException;

trait FilterTrait
{
    public function filter($model, $order_by, $request, array $properties, $order_direction = 'asc', $globalSearchField = null, array $globalSearchColumns = [])
    {
        if (!class_exists($model)) {
            throw new InvalidArgumentException('Invalid model class provided');
        }

        if (!in_array($order_direction, ['asc', 'desc'])) {
            throw new InvalidArgumentException('Order direction must be either "asc" or "desc"');
        }

        $query = $model::query();
        $query->orderBy($order_by, $order_direction);

        // Handle individual property filters
        foreach ($properties as $property => $condition) {
            if ($request->filled($property)) {
                if ($condition === 'like') {
                    $query->where($property, 'like', '%' . $request->$property . '%');
                } elseif ($condition === '=') {
                    $query->where($property, $request->$property);
                }
            }
        }

        // Handle global search
        if ($globalSearchField && $request->filled($globalSearchField)) {
            $searchValue = $request->$globalSearchField;
            $query->where(function ($subQuery) use ($globalSearchColumns, $searchValue) {
                foreach ($globalSearchColumns as $column) {
                    $subQuery->orWhere($column, 'like', '%' . $searchValue . '%');
                }
            });
        }

        return $query;
    }

}
