<?php
namespace App\DTO;

class DatatableDTO
{
    public $draw;
    public $start;
    public $length;
    public $search;
    public $orderColumn;
    public $orderDirection;
    public $columns;

    public function __construct($request)
    {
        $this->draw = $request['draw'] ?? null;
        $this->start = $request['start'] ?? 0;
        $this->length = $request['length'] ?? 10;
        $this->search = $request['search']['value'] ?? '';

        // Extract columns from the request
        $this->columns = array_map(function ($col) {
            return [
                'data' => $col['data'] ?? null,
                'name' => $col['name'] ?? null,
                'searchable' => $col['searchable'] ?? false,
                'orderable' => $col['orderable'] ?? false,
            ];
        }, $request['columns'] ?? []);

        // Extract ordering information
        $orderIndex = $request['order'][0]['column'] ?? 0;
        $this->orderColumn = $this->columns[$orderIndex]['data'] ?? null;
        $this->orderDirection = $request['order'][0]['dir'] ?? 'asc';
    }

    public function getDraw()
    {
        return $this->draw;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function getOrderColumn()
    {
        return $this->orderColumn;
    }

    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
