<?php
namespace App\Services\Admin;

use Throwable;
use App\Helper\BaseQuery;
use App\Helpers\FileHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    use BaseQuery;
    public function index(){
        return null;
    }
}