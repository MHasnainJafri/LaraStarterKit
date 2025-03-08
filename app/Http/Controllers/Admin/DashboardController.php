<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\DashboardService;

class DashboardController extends Controller
{
    

    public function __construct(private DashboardService $service,private string $view='admin.dashboard'){}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    public function index(Request $request)
    {
        

        $data = $this->service->index();
        return view("{$this->view}.index", compact('data'));
    }

}
