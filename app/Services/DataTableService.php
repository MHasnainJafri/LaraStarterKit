<?php
namespace App\Services;

use App\DTO\DatatableDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DataTableService
{
    public static function applyFilters(Builder $query, DatatableDTO $dto): Builder
    {
        // Apply Search Across Specified Columns
        if (!empty($dto->search)) {
            $query->where(function ($q) use ($dto) {
                foreach ($dto->columns as $column) {
                    if ($column['searchable'] && !empty($column['data'])) {
                        $q->orWhere($column['data'], 'LIKE', "%{$dto->search}%");
                    }
                }
            });
        }

        return $query;
    }

    public static function applySorting(Builder $query, DatatableDTO $dto): Builder
    {
        if (!empty($dto->orderColumn)) {
            $query->orderBy($dto->orderColumn, $dto->orderDirection);
        }

        return $query;
    }

    public static function paginate(Builder $query, DatatableDTO $dto)
    {
        return $query->offset($dto->start)
                     ->limit($dto->length)
                     ->get();
    }

    public static function handle(Model $model, array $data)
    {
        $dto = new DatatableDTO($data);
        $query = $model->newQuery();

        $query = self::applyFilters($query, $dto);
        $query = self::applySorting($query, $dto);

        return [
            'draw' => $dto->draw,
            'recordsTotal' => $model->count(),
            'recordsFiltered' => $query->count(),
            'data' => self::paginate($query, $dto),
        ];
    }
}
