<?php
namespace App\DTO;

// app/DTO/UserDTO.php

namespace App\DTO;

class DatatableDTO
{
    public $draw;
    public $start;
    public $length;
    public $search;
    public $orderColumn;
    public $orderDirection;

    public function __construct($request)
    {

      

        $this->draw = $request['draw'];
        $this->start = $request['start'];
        $this->length = $request['length'];
        $this->search = $request['search']['value'];
        $this->orderColumn = $request['order'][0]['column'];
        $this->orderDirection = $request['order'][0]['dir'];
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
}
