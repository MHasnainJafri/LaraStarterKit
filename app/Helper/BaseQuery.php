<?php

namespace App\Helper;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

trait BaseQuery
{
    /**
     * add new record
     */
    public function add($model, $data)
    {
        return $model->create($data);
    }

    /**
     * get all record
     */
    public function get_all($model, $relation = null)
    {
        if ($relation == null) {
            return $model->all();
        } else {
            return $model->with($relation)->get();
        }
    }

    /**
     * get record by its id
     */
    public function get_by_id($model, $id, $relation = null)
    {
        if ($relation == null) {
            return $model->find($id);
        } else {
            return $model->with($relation)->first($id);
        }
    }

    /**
     * get record by its slug
     */
    public function get_by_slug($model, $slug, $relation = null)
    {
        if ($relation == null) {
            return $model->whereSlug($slug)->first();
        } else {
            return $model->with($relation)->whereSlug($slug)->first();
        }
    }

    /**
     * get record by column
     */
    public function get_by_column($model, $columArr, $relation = null)
    {
        if ($relation == null) {
            return $model->where($columArr)->get();
        } else {
            return $model->with($relation)->where($columArr)->get();
        }
    }

    /**
     * get record by column single
     */
    public function get_by_column_single($model, $columArr, $relation = null)
    {
        if ($relation == null) {
            return $model->where($columArr)->first();
        } else {
            return $model->with($relation)->where($columArr)->first();
        }
    }

    /**
     * get record by column
     */
    public function get_by_column_multiple($model, $column, $relation = null)
    {
        if ($relation == null) {
            return $model->where($column)->get();
        } else {
            return $model->with($relation)->where($column)->get();
        }
    }

    /**
     * delete record by its id
     */
    public function delete($model, $id)
    {
        return $model->findOrFail($id)->delete();
    }

    /**
     * Apply a date range filter to the query.
     *
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  string  $column
     */
    public function applyDateRange(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, $startDate, $endDate, $column = 'created_at'): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        if ($startDate && $endDate) {
            return $query->whereBetween($column, [$startDate, $endDate]);
        }

        return $query;
    }

    /**
     * Apply nested filters to the query.
     */
    public function applyNestedFilters(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, array $nestedFilters): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        foreach ($nestedFilters as $field => $value) {
            if (is_array($value)) {
                $query->whereHas($field, function ($query) use ($value) {
                    foreach ($value as $key => $val) {
                        $query->where($key, $val);
                    }
                });
            } else {
                $query->where($field, $value);
            }
        }

        return $query;
    }

    public function applyFilters(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, array $filters): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        foreach ($filters as $key => $value) {
            if (method_exists($this, $scope = 'scope'.ucfirst($key))) {
                $query = $this->$scope($query, $value);
            } elseif (in_array($key, $this->fillable)) {
                $query->where($key, 'like', "%$value%");
            }
        }

        return $query;
    }

    public function applySorting(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, $sortBy = 'id', $sortOrder = 'asc'): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return $query->orderBy($sortBy, $sortOrder);
    }

    public function applyPagination(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, $perPage = 10): LengthAwarePaginator
    {
        return $query->paginate($perPage);
    }

    public function applyEagerLoading(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, array $relationships): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return $query->with($relationships);
    }

    public function applySearch(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, array $searchFields, $searchTerm): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        foreach ($searchFields as $field) {
            $query->orWhere($field, 'like', "%$searchTerm%");
        }

        return $query;
    }

    public function includeSoftDeletes(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, $includeDeleted = false): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        if ($includeDeleted) {
            return $query->withTrashed();
        }

        return $query->withoutTrashed();
    }

    public function applyFullPagination(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, array $filters, $sortBy = 'id', $sortOrder = 'asc', $perPage = 10)
    {
        $query = $this->applyFilters($query, $filters);
        $query = $this->applySorting($query, $sortBy, $sortOrder);

        return $this->applyPagination($query, $perPage);
    }

    public function softDelete(Model $model)
    {
        return $model->delete();
    }

    public function restore(Model $model)
    {
        return $model->restore();
    }

    public function applyWhereIn(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query, $column, array $values): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return $query->whereIn($column, $values);
    }
}
